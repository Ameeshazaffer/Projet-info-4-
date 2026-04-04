<?php
session_start();

// ── Si non connecté, redirection ──
if (!isset($_SESSION['user'])) {
    header("Location: connexion.html");
    exit;
}

$user        = $_SESSION['user'];
$commande_id = $_GET['id'] ?? '';

if ($commande_id === '') {
    header("Location: profil.php");
    exit;
}

// ── Lecture des commandes depuis commandes.json ──
$commande = null;
if (file_exists("commandes.json")) {
    $data = json_decode(file_get_contents("commandes.json"), true);
    foreach ($data["commandes"] ?? [] as $c) {
        if ($c['id'] === $commande_id && $c['email'] === $user['email']) {
            $commande = $c;
            break;
        }
    }
}

// Si commande introuvable ou pas à lui, retour profil
if (!$commande) {
    header("Location: profil.php");
    exit;
}
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

    <!-- NAV -->
    <nav>
        <div class="conteneur-nav">
            <div class="logo-nav">
                <div class="texte-logo-nav">✦ÉVEIL✦</div>
                <div class="paris-logo-nav">PARIS</div>
            </div>
            <ul class="liens-nav">
                <li><a href="index.html">ACCUEIL</a></li>
                <li><a href="profil.php" class="bouton-profil">PROFIL</a></li>
            </ul>
        </div>
    </nav>

    <div class="att">
        <h1>MA COMMANDE N°<?= htmlspecialchars($commande['id']) ?></h1>
        <p style="margin-bottom:1rem; font-family:'Montserrat',sans-serif; color:#666;">
            Passée le <?= htmlspecialchars($commande['date']) ?>
            &nbsp;—&nbsp;
            Statut : <strong><?= htmlspecialchars($commande['statut']) ?></strong>
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

        <p style="margin-top:1.5rem;">
            <a href="profil.php" style="font-family:'Montserrat',sans-serif; color:#1a1a1a; font-weight:600;">
                ← Retour à mon profil
            </a>
        </p>
    </div>

    <!-- FOOTER -->
    <footer>
        <div class="logo-pied-page">
            <div class="texte-logo-pied-page">✧ÉVEIL✧</div>
            <div class="paris-logo-pied-page">PARIS</div>
            <div class="slogan-logo-pied-page">Éveillez vos papilles gustatives.</div>
        </div>
        <div class="infos-pied-page">
            <div class="section-pied-page">
                <h3>ADRESSE</h3>
                <p>123 Avenue des Champs-Élysées<br>75008 Paris, France</p>
            </div>
            <div class="section-pied-page">
                <h3>HORAIRES</h3>
                <p>Mardi - Samedi<br>12h00 - 14h30 | 19h00 - 22h30<br>Fermé Dimanche & Lundi</p>
            </div>
            <div class="section-pied-page">
                <h3>CONTACT</h3>
                <p>Tél: +33 1 23 45 67 89<br>Email: contact@eveilparis.fr</p>
            </div>
        </div>
        <p style="margin-top:2rem;color:#C9B896;">© 2026 EVEIL Paris. Tous droits réservés.</p>
    </footer>

</body>
</html>