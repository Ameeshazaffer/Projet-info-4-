<?php
session_start();

if (!isset($_SESSION['user'])) {
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
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <nav>
        <a href="restaurateur.php">← Retour</a>
    </nav>

    <h1>Commande N°<?= htmlspecialchars($commande['id']) ?></h1>
    
    <p>Statut actuel : <strong><?= htmlspecialchars($commande['statut']) ?></strong></p>
    <p>Date : <?= htmlspecialchars($commande['date']) ?></p>
    <p>Paiement : <?= htmlspecialchars($commande['paiement'] ?? 'Payée') ?></p>

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
                <th><?= htmlspecialchars($commande['prix_total'] ?? $commande['prix'] ?? '') ?>€</th>
            </tr>
        </tbody>
    </table>

    <h2>Livreur</h2>
    <p>
        <?php if (!empty($commande['livreur_assigne'])): ?>
            Assigné à : <strong><?= htmlspecialchars($commande['livreur_assigne']) ?></strong>
        <?php else: ?>
            <em>Aucun livreur assigné pour le moment.</em>
        <?php endif; ?>
    </p>

</body>
</html>
