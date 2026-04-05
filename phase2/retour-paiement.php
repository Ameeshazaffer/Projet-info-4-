<?php
session_start();

$statut      = $_GET['status']      ?? '';
$commande_id = intval($_GET['commande_id'] ?? 0);

// Mettre à jour le statut dans commandes.json
if ($commande_id > 0 && file_exists("commandes.json")) {
    $donnees = json_decode(file_get_contents("commandes.json"), true);

    foreach ($donnees["commandes"] as &$c) {
        if ($c['id'] == $commande_id) {
            $c['paiement'] = ($statut === 'accepted') ? "Payé" : "Non payé";
            $c['statut']   = "en préparation";
            break;
        }
    }
    unset($c);

    file_put_contents("commandes.json", json_encode($donnees, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>EVEIL - Retour paiement</title>
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
                <li><a href="index.html">ACCUEIL</a></li>
                <li><a href="profil.php" class="bouton-inscription">PROFIL</a></li>
            </ul>
        </div>
    </nav>

    <div class="att">
        <?php if ($statut === 'accepted'): ?>
            <h1>Paiement accepté !</h1>
            <p>Votre commande n°<?= $commande_id ?> a bien été payée.</p>
        <?php else: ?>
            <h1>Paiement refusé</h1>
            <p>Le paiement a été refusé. Veuillez réessayer.</p>
        <?php endif; ?>

        <a href="profil.php" class="bouton-inscription" style="margin-top:2rem; display:inline-block;">
            Mon profil
        </a>
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
