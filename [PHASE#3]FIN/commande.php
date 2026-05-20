<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit;
}

$user = $_SESSION['user'];

/*
CREATION / MODIFICATION COMMANDE
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (empty($_SESSION['panier'])) {
        header("Location: produits.php");
        exit;
    }

    $email   = $user['email'];
    $panier  = $_SESSION['panier'];

    $total = array_sum(
        array_map(fn($i) => $i['prix'] * $i['quantite'], $panier)
    );

    $fichier = "commandes.json";

    $donnees = file_exists($fichier)
        ? json_decode(file_get_contents($fichier), true)
        : ["commandes" => []];

    /*
    SI ON MODIFIE UNE COMMANDE EXISTANTE
    */

    if (isset($_SESSION['commande_en_cours'])) {
        $commande_id = $_SESSION['commande_en_cours'];
        foreach ($donnees["commandes"] as &$c) {
            if ($c['id'] == $commande_id) {
                /*
                ancien montant déjà payé
                */
                $ancien_paye = $c['total_deja_paye'] ?? $c['prix_total'];
                /*
                nouveaux produits
                */
                $c['produits'] = $panier;
                /*
                nouveau total
                */
                $c['prix_total'] = $total;
                /*
                garder ancien montant payé
                */
                $c['total_deja_paye'] = $ancien_paye;
                /*
                remettre en attente paiement
                */
                $c['paiement'] = "Non payé";
                $commande_finale = $c['id'];
                break;
            }
        }
        unset($_SESSION['commande_en_cours']);
    }
    /*
    NOUVELLE COMMANDE
    */

    else {

        $prochain_id = 1;
        foreach ($donnees["commandes"] as $c) {
            if ($c['id'] >= $prochain_id) {
                $prochain_id = $c['id'] + 1;
            }
        }
        $donnees["commandes"][] = [

            "id" => $prochain_id,
            "email" => $email,
            "produits" => $panier,
            "prix_total" => $total,

            /*
            rien payé au début
            */
            "total_deja_paye" => 0,
            "date" => date("d/m/Y H:i"),
            "statut" => "En attente de paiement",
            "paiement" => "Non payé"
        ];
        $commande_finale = $prochain_id;
    }
    file_put_contents(
        $fichier,
        json_encode($donnees, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
    );
    unset($_SESSION['panier']);
    header("Location: commande.php?id=" . $commande_finale);
    exit;
}
/*
AFFICHAGE COMMANDE
*/

$commande_id = intval($_GET['id'] ?? 0);
if ($commande_id === 0) {
    header("Location: profil.php");
    exit;
}
$commande = null;
if (file_exists("commandes.json")) {
    $data = json_decode(file_get_contents("commandes.json"), true);
    foreach ($data["commandes"] ?? [] as $c) {
        if (
            $c['id'] === $commande_id &&
            $c['email'] === $user['email']
        ) {
            $commande = $c;
            break;
        }
    }
}

if (!$commande) {
    header("Location: profil.php");
    exit;
}

/*
CALCUL DIFFERENCE A PAYER
*/

$montant_a_payer = $commande['prix_total'];

if (isset($commande['total_deja_paye'])) {
    $difference = $commande['prix_total'] - $commande['total_deja_paye'];
    if ($difference > 0) {
        $montant_a_payer = $difference;
    } else {
        $montant_a_payer = 0;
    }
}

/*
CYBANK
*/

require('getapikey.php');

$vendeur = 'MI-3_B';

$transaction =
    'EVEIL' .
    str_pad($commande['id'], 6, '0', STR_PAD_LEFT) .
    'PARIS';

$montant = number_format($montant_a_payer, 2, '.', '');

$retour =
    'http://localhost' .
    dirname($_SERVER['PHP_SELF']) .
    '/retour-paiement.php?commande_id=' .
    $commande['id'];

$api_key = getAPIKey($vendeur);

$control = md5(
    $api_key . "#" .
    $transaction . "#" .
    $montant . "#" .
    $vendeur . "#" .
    $retour . "#"
);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>EVEIL - Ma Commande</title>

    <link href="https://fonts.googleapis.com/css2?family=Parisienne&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="styles.css">
</head>
<body>

<nav>
    <div class="conteneur-nav">

        <div class="logo-nav">
            <div class="texte-logo-nav">✦ÉVEIL✦</div>
            <div class="paris-logo-nav">PARIS</div>
        </div>

        <ul class="liens-nav">
            <li><a href="index.php">ACCUEIL</a></li>
            <li><a href="profil.php" class="bouton-profil">PROFIL</a></li>
        </ul>

    </div>
</nav>

<div class="att">
    <h1>MA COMMANDE N°<?= htmlspecialchars($commande['id']) ?></h1>
    <p style="margin-bottom:1rem;font-family:'Montserrat',sans-serif;color:#666;">
        Passée le <?= htmlspecialchars($commande['date']) ?>
        &nbsp;—&nbsp;
        Statut :
        <strong><?= htmlspecialchars($commande['statut']) ?></strong>
    </p>

    <table>
        <tr>
            <th>Plat</th>
            <th>Quantité</th>
            <th>Prix unitaire</th>
            <th>Sous-total</th>
        </tr>
        <?php foreach ($commande['produits'] as $ligne): ?>
            <tr>
                <td><?= htmlspecialchars($ligne['nom']) ?></td>
                <td><?= $ligne['quantite'] ?></td>
                <td><?= $ligne['prix'] ?>€</td>
                <td><?= $ligne['prix'] * $ligne['quantite'] ?>€</td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <th colspan="3">Prix total</th>
            <th><?= htmlspecialchars($commande['prix_total']) ?>€</th>
        </tr>

    </table>

    <div style="display:flex;gap:2rem;margin-top:2rem;align-items:center;flex-wrap:wrap;">

        <a href="profil.php"
           style="font-family:'Montserrat',sans-serif;color:#1a1a1a;font-weight:600;">
            ← Retour à mon profil
        </a>

        <?php if ($montant_a_payer > 0): ?>
            <form action="https://www.plateforme-smc.fr/cybank/index.php" method="POST">
                <input type="hidden" name="transaction" value="<?= $transaction ?>">
                <input type="hidden" name="montant" value="<?= $montant ?>">
                <input type="hidden" name="vendeur" value="<?= $vendeur ?>">
                <input type="hidden" name="retour" value="<?= $retour ?>">
                <input type="hidden" name="control" value="<?= $control ?>">
                <button type="submit" class="bouton-inscription">
                    Payer <?= $montant_a_payer ?>€
                </button>
            </form>
        <?php else: ?>
            <p style="font-family:'Montserrat',sans-serif;color:green;">
                Aucun supplément à payer.
            </p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>