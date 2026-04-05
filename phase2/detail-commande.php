<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'restaurateur') {
    header("Location: connexion.html");
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

// Récupérer les livreurs
$utilisateurs = json_decode(file_get_contents("utilisateurs.json"), true);
$livreurs = [];
foreach ($utilisateurs["utilisateurs"] as $u) {
    if (($u['role'] ?? '') === 'livreur') {
        $livreurs[] = $u;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>EVEIL - Détail commande</title>
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

<div class="att">
    <h1>COMMANDE N°<?= htmlspecialchars($commande['id']) ?></h1>
    <p style="font-family:'Montserrat',sans-serif; color:#666; margin-bottom:1rem;">
        Date : <?= htmlspecialchars($commande['date']) ?>
        &nbsp;—&nbsp;
        Heure : <?= htmlspecialchars($commande['heure'] ?? '') ?>
        &nbsp;—&nbsp;
        Statut : <strong><?= htmlspecialchars($commande['statut']) ?></strong>
        &nbsp;—&nbsp;
        Paiement : <strong><?= htmlspecialchars($commande['paiement'] ?? 'Non payé') ?></strong>
    </p>

    <!-- LISTE DES PLATS -->
    <h2>Plats commandés</h2>
    <table>
        <thead>
            <tr>
                <th>Plat</th>
                <th>Prix</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($commande['plats'] ?? $commande['produits'] ?? [] as $plat): ?>
                <tr>
                    <td><?= htmlspecialchars($plat['nom']) ?></td>
                    <td><?= htmlspecialchars($plat['prix']) ?>€</td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <th>Total</th>
                <th><?= htmlspecialchars($commande['prix'] ?? $commande['prix_total'] ?? '') ?>€</th>
            </tr>
        </tbody>
    </table>

    <!-- CHANGER LE STATUT (affichage seulement) -->
    <h2 style="margin-top:2rem;">Changer le statut</h2>
    <div style="display:flex; gap:1rem; flex-wrap:wrap; margin-bottom:2rem;">
        <button class="bouton-inscription" disabled>En préparation</button>
        <button class="bouton-inscription" disabled>En attente</button>
        <button class="bouton-inscription" disabled>En livraison</button>
        <button class="bouton-inscription" disabled>Livrée</button>
    </div>

    <!-- ATTRIBUER UN LIVREUR (affichage seulement) -->
    <h2>Attribuer un livreur</h2>
    <?php if (empty($livreurs)): ?>
        <p style="font-family:'Montserrat',sans-serif; color:#666;">Aucun livreur disponible.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Téléphone</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($livreurs as $livreur): ?>
                    <tr>
                        <td><?= htmlspecialchars($livreur['nom']) ?></td>
                        <td><?= htmlspecialchars($livreur['prenom']) ?></td>
                        <td><?= htmlspecialchars($livreur['telephone']) ?></td>
                        <td><button class="bouton-inscription" disabled>Attribuer</button></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

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
