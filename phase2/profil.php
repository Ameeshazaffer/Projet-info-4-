<?php
session_start();

//  Si non connecté, redirection 
if (!isset($_SESSION['user'])) {
    header("Location: connexion.html");
    exit;
}

$user = $_SESSION['user'];

//  Lecture des commandes depuis commandes.json 
$commandes = [];
if (file_exists("commandes.json")) {
    $data = json_decode(file_get_contents("commandes.json"), true);
    foreach ($data["commandes"] ?? [] as $c) {
        if ($c['email'] === $user['email']) {
            $commandes[] = $c;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>EVEIL - Mon Profil</title>
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
        <h1>MON PROFIL</h1>

        <?php if (!empty($_SESSION['message'])): ?>
            <p style="margin-bottom:1rem; font-family:'Montserrat',sans-serif; color:green;">
                <?= htmlspecialchars($_SESSION['message']) ?>
            </p>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <!--  Informations personnelles  -->
        <table>
            <tr><th colspan="2">MES INFORMATIONS PERSONNELLES</th></tr>
            <tr><th>Nom</th><td><?= htmlspecialchars($user['nom']) ?></td></tr>
            <tr><th>Prénom</th><td><?= htmlspecialchars($user['prenom']) ?></td></tr>
            <tr><th>Email</th><td><?= htmlspecialchars($user['email']) ?></td></tr>
            <tr><th>Téléphone</th><td><?= htmlspecialchars($user['telephone'] ?? '—') ?></td></tr>
            <tr><th>Adresse</th><td><?= htmlspecialchars($user['adresse'] ?? '—') ?></td></tr>
            <tr><th>Membre depuis</th><td><?= htmlspecialchars($user['date_inscription'] ?? '—') ?></td></tr>
        </table>

        <!--  Historique des commandes  -->
        <table>
            <tr><th colspan="5">MES COMMANDES</th></tr>
            <tr>
                <th>DATE</th>
                <th>COMMANDE N°</th>
                <th>STATUT</th>
                <th>PRIX TOTAL</th>
                <th>ACTION</th>
            </tr>

            <?php if (empty($commandes)): ?>
                <tr><td colspan="5">Vous n'avez pas encore de commandes.</td></tr>
            <?php else: ?>
                <?php foreach ($commandes as $commande): ?>
                    <tr>
                        <td><?= htmlspecialchars($commande['date']) ?></td>
                        <td><?= htmlspecialchars($commande['id']) ?></td>
                        <td><?= htmlspecialchars($commande['statut']) ?></td>
                        <td><?= htmlspecialchars($commande['prix_total']) ?>€</td>
                        <td><a href="commande.php?id=<?= htmlspecialchars($commande['id']) ?>">Voir ma commande</a></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>

        <!--  Points de fidélité  -->
        <table>
            <tr><th colspan="2">MON COMPTE DE FIDÉLITÉ</th></tr>
            <tr><th>Mes points</th><td><?= $user['points'] ?? 0 ?></td></tr>
        </table>

        <!-- Lien pour retourner commander -->
        <p style="margin-top:2rem; font-family:'Montserrat',sans-serif;">
            <a href="produits.php" style="color:#1a1a1a; font-weight:600;">← Retour à la carte</a>
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
