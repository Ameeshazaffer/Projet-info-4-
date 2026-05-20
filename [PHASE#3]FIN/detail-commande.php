<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'restaurateur') {
    header("Location: connexion.php");
    exit;
}
$id = $_GET['id'] ?? '';
$donnees = json_decode(file_get_contents("commandes.json"), true);
$commande = null;
foreach ($donnees["commandes"] as $c) {
    if ($c['id'] == $id) {
        $commande = $c;
        break;
    }
}
if (!$commande) {
    header("Location: restaurateur.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détail Commande</title>
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
            <li><a href="restaurateur.php">← Retour aux commandes</a></li>
        </ul>
    </div>
</nav>

<div class="att" style="padding-top:2rem;">
    <h1>Commande N°<?= htmlspecialchars($commande['id']) ?></h1>

    <div class="table-profil" style="width:100%; max-width:500px;">
        <p style="margin-bottom:0.8rem;">
            Statut : <strong><?= htmlspecialchars($commande['statut']) ?></strong>
        </p>
        <p style="margin-bottom:0.8rem;">
            Date : <?= htmlspecialchars($commande['date']) ?>
        </p>
        <p>
            Paiement : <strong><?= htmlspecialchars($commande['paiement'] ?? 'Non payé') ?></strong>
        </p>
    </div>

    <h2 style="margin-top:2rem; font-family:'Montserrat',sans-serif; letter-spacing:2px;">Plats commandés</h2>

    <table style="width:100%; max-width:700px; margin-top:1rem;">
        <thead>
            <tr>
                <th>Plat</th>
                <th>Prix unitaire</th>
                <th>Quantité</th>
                <th>Sous-total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($commande['plats'] ?? $commande['produits'] ?? [] as $plat): ?>
                <tr>
                    <td><?= htmlspecialchars($plat['nom']) ?></td>
                    <td><?= htmlspecialchars($plat['prix']) ?>€</td>
                    <td><?= htmlspecialchars($plat['quantite'] ?? 1) ?></td>
                    <td><?= htmlspecialchars($plat['prix'] * ($plat['quantite'] ?? 1)) ?>€</td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <th colspan="3">Total</th>
                <th><?= htmlspecialchars($commande['prix_total'] ?? $commande['prix'] ?? '') ?>€</th>
            </tr>
        </tbody>
    </table>

    <h2 style="margin-top:2rem; font-family:'Montserrat',sans-serif; letter-spacing:2px;">Livreur</h2>

    <div class="table-profil" style="width:100%; max-width:500px;">
        <?php if (!empty($commande['livreur_assigne'])): ?>
            <p>Assigné à : <strong><?= htmlspecialchars($commande['livreur_assigne']) ?></strong></p>
        <?php else: ?>
            <p><em>Aucun livreur assigné pour le moment.</em></p>
        <?php endif; ?>
    </div>
</div>

<footer>
    <div class="logo-pied-page">
        <div class="texte-logo-pied-page">✧ÉVEIL✦</div>
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