<?php
session_start();
require("verifier_blocage.php");
// Si non connecté on redirige
if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit;
}

$user = $_SESSION['user']; // on simplifie la variable que on va beaucoup utiliser

// Lecture des commandes depuis le json
$commandes = [];

if (file_exists("commandes.json")) {
    $donneesCommandes = json_decode(file_get_contents("commandes.json"), true);
foreach ($donneesCommandes["commandes"] ?? [] as $commande) {
    if ($commande["email"] === $user["email"]) {
        $commandes[] = $commande;
        }
    }
}
$pointsFidelite = 0;
foreach ($commandes as $commande) {
    if (($commande["statut"] ?? "") === "Livrée") {
        $pointsFidelite = $pointsFidelite + 10;
    }
}

$donneesUtilisateurs = json_decode(file_get_contents("utilisateurs.json"), true);
$vip     = "non";
$premium = "non";
$remise  = 0;
foreach ($donneesUtilisateurs["utilisateurs"] as $u) {
    if ($u["email"] === $user["email"]) {
        $vip     = $u["vip"]     ?? "non";
        $premium = $u["premium"] ?? "non";
        $remise  = $u["remise"]  ?? 0;
        break;
    }
}

function commande_deja_notee($idCommande, $emailClient) {
    if (!file_exists("note.json")) return false;
    $donneesNotes = json_decode(file_get_contents("note.json"), true);
    foreach ($donneesNotes["notes"] ?? [] as $note) {
        if (
            isset($note["commande_id"]) && isset($note["client_email"]) &&
            $note["commande_id"] == $idCommande &&
            $note["client_email"] === $emailClient
        ) { return true; }
    }
    return false;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>EVEIL - Mon Profil</title>

    <link href="https://fonts.googleapis.com/css2?family=Parisienne&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link id="chgmode" rel="stylesheet" href="styles.css">
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
            <li><a href="deconnexion.php" class="bouton-profil">DECONNEXION</a></li>
            <li><button id="btnchgmode" type="button">🌙</button></li>

        </ul>

    </div>
</nav>

<div class="att">

    <h1>MON PROFIL</h1>

    <p id="message-profil" style="font-family:'Montserrat',sans-serif; margin-bottom:1rem;"></p>


    <h2>Mes informations</h2>

<form id="formulaire-profil">
    <table>
        <tr>
            <th>Nom</th>
            <td>
                <input type="text" id="nom" maxlength="15" value="<?= htmlspecialchars($user['nom'] ?? '') ?>" disabled>
                <p id="compteur-nom"></p>
            </td>
        </tr>
        <tr>
            <th>Prénom</th>
            <td>
                <input type="text" id="prenom" maxlength="15" value="<?= htmlspecialchars($user['prenom'] ?? '') ?>" disabled>
                <p id="compteur-prenom"></p>
            </td>
        </tr>
        <tr>
            <th>Email</th>
            <td>
                <input type="email" id="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" readonly disabled>
            </td>
        </tr>
        <tr>
            <th>Téléphone</th>
            <td>
                <input type="text" id="telephone" maxlength="10" value="<?= htmlspecialchars($user['telephone'] ?? '') ?>" disabled>
            </td>
        </tr>
        <tr>
            <th>Adresse</th>
            <td>
                <input type="text" id="adresse" value="<?= htmlspecialchars($user['adresse'] ?? '') ?>" disabled>
            </td>
        </tr>
        <tr>
            <th>Étage</th>
            <td>
                <input type="text" id="etage" value="<?= htmlspecialchars($user['etage'] ?? '') ?>" disabled>
            </td>
        </tr>
        <tr>
            <th>Code interphone</th>
            <td>
                <input type="text" id="code_interphone" value="<?= htmlspecialchars($user['code_interphone'] ?? '') ?>" disabled>
            </td>
        </tr>
        <tr>
            <th>Membre depuis</th>
            <td><?= htmlspecialchars($user['date_inscription'] ?? '—') ?></td>
        </tr>
    </table>

    <button type="button" id="bouton-modifier" class="bouton-inscription">Modifier</button>
    <button type="submit" id="bouton-enregistrer" class="bouton-inscription" style="display:none;">Enregistrer</button>
    <button type="button" id="bouton-annuler" class="bouton-inscription" style="display:none;">Annuler</button>
</form>
    <!-- COMMANDES -->

    <table>

        <tr>
            <th colspan="5">MES COMMANDES</th>
        </tr>

        <tr>
            <th>DATE</th>
            <th>COMMANDE N°</th>
            <th>STATUT</th>
            <th>PRIX TOTAL</th>
            <th>ACTION</th>
        </tr>

        <?php if (empty($commandes)): ?>

            <tr>
                <td colspan="5">Vous n'avez pas encore de commandes.</td>
            </tr>

        <?php else: ?>

            <?php foreach ($commandes as $commande): ?>

                <tr>

                    <td><?= htmlspecialchars($commande['date']) ?></td>

                    <td><?= htmlspecialchars($commande['id']) ?></td>

                    <td><?= htmlspecialchars($commande['statut']) ?></td>

                    <td><?= htmlspecialchars($commande['prix_total']) ?>€</td>

                    <td>

                    <?php

                    $commande_notee = false;

                    if (file_exists("note.json")) {

                        $contenu_notes = json_decode(file_get_contents("note.json"), true);

                        if (isset($contenu_notes["notes"])) {

                            foreach ($contenu_notes["notes"] as $note) {

                                if (
                                    isset($note["commande_id"]) &&
                                    isset($note["client_email"]) &&
                                    $note["commande_id"] == $commande["id"] &&
                                    $note["client_email"] == $user["email"]
                                ) {

                                    $commande_notee = true;
                                }
                            }
                        }
                    }

                    // si commande payée alors modification possible
                    if (
                        isset($commande["statut"]) &&
                        ($commande["statut"] === "Payée" || $commande["statut"] === "Payé")
                    ) {
                    ?>

                        <a href="modifier_commande.php?id=<?= htmlspecialchars($commande['id']); ?>">
                            Modifier la commande
                        </a>

                    <?php

                    }

                    // si commande livrée et pas encore notée
                    elseif (
                        isset($commande["statut"]) &&
                        $commande["statut"] === "Livrée" &&
                        !$commande_notee
                    ) {
                    ?>

                        <a href="notation.php?commande=<?= htmlspecialchars($commande['id']); ?>&client=<?= htmlspecialchars($user['email']); ?>">
                            Noter la commande
                        </a>

                    <?php

                    }

                    // sinon voir commande
                    else {

                    ?>

                        <a href="commande.php?id=<?= htmlspecialchars($commande['id']); ?>">
                            Voir ma commande
                        </a>

                    <?php
                    }
                    ?>

                    </td>

                </tr>

            <?php endforeach; ?>

        <?php endif; ?>

    </table>


   <h2>Compte fidélité</h2>
<table>
    <tr><th>Points fidélité</th><td><?= $pointsFidelite ?> points</td></tr>
    <tr><th>Statut VIP</th><td><?= htmlspecialchars($vip) ?></td></tr>
    <tr><th>Statut Premium</th><td><?= htmlspecialchars($premium) ?></td></tr>
    <tr><th>Remise</th><td><?= htmlspecialchars($remise) ?>%</td></tr>
</table>



    <!-- RETOUR -->

    <p style="margin-top:2rem; font-family:'Montserrat',sans-serif;">

        <a href="produits.php" style="color:#1a1a1a; font-weight:600;">
            ← Retour à la carte
        </a>

    </p>

</div>


<footer>

    <div class="logo-pied-page">

        <div class="texte-logo-pied-page">✧ÉVEIL✧</div>

        <div class="paris-logo-pied-page">PARIS</div>

        <div class="slogan-logo-pied-page">
            Éveillez vos papilles gustatives.
        </div>

    </div>

    <div class="infos-pied-page">

        <div class="section-pied-page">
            <h3>ADRESSE</h3>

            <p>
                123 Avenue des Champs-Élysées<br>
                75008 Paris, France
            </p>
        </div>

        <div class="section-pied-page">

            <h3>HORAIRES</h3>

            <p>
                Mardi - Samedi<br>
                12h00 - 14h30 | 19h00 - 22h30<br>
                Fermé Dimanche & Lundi
            </p>

        </div>

        <div class="section-pied-page">

            <h3>CONTACT</h3>

            <p>
                Tél: +33 1 23 45 67 89<br>
                Email: contact@eveilparis.fr
            </p>

        </div>

    </div>

    <p style="margin-top:2rem;color:#C9B896;">
        © 2026 EVEIL Paris. Tous droits réservés.
    </p>

</footer>


<script src="profil.js"></script>
<script src="mode.js"></script>

</body>
</html>
