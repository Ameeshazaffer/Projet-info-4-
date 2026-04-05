<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'restaurateur') {
    header("Location: connexion.php");
    exit;
}

$donnees = json_decode(file_get_contents("commandes.json"), true);
$commandes = $donnees["commandes"] ?? [];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>EVEIL - Espace Restaurateur</title>
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
        </ul>
    </div>
</nav>

<div class="att" style="padding-top:2rem;">
    <h1>ESPACE RESTAURATEUR</h1>
    <h2>Liste des commandes</h2>

    <form method="GET" action="restaurateur.php">
        <select name="statut" class="select-box">
            <option value="tous">Tous les statuts</option>
            <option value="en préparation" <?= ($_GET['statut'] ?? '') === 'en préparation' ? 'selected' : '' ?>>En préparation</option>
            <option value="en attente"     <?= ($_GET['statut'] ?? '') === 'en attente'     ? 'selected' : '' ?>>En attente</option>
            <option value="en livraison"   <?= ($_GET['statut'] ?? '') === 'en livraison'   ? 'selected' : '' ?>>En livraison</option>
            <option value="livrée"         <?= ($_GET['statut'] ?? '') === 'livrée'         ? 'selected' : '' ?>>Livrée</option>
        </select>
        <button type="submit">Filtrer</button>
    </form>

    <div class="liv">
        <table>
            <thead>
                <tr>
                    <th>N° commande</th>
                    <th>Date</th>
                    <th>Statut</th>
                    <th>Paiement</th>
                    <th>Prix total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $filtre = $_GET['statut'] ?? 'tous';
                $trouve = false;

                foreach ($commandes as $commande) {
                    if ($filtre !== 'tous' && $commande['statut'] !== $filtre) {
                        continue;
                    }
                    $trouve = true;
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($commande['id']) ?></td>
                        <td><?= htmlspecialchars($commande['date']) ?></td>
                        <td><?= htmlspecialchars($commande['statut']) ?></td>
                        <td><?= htmlspecialchars($commande['paiement'] ?? 'Non payé') ?></td>
                        <td><?= htmlspecialchars($commande['prix_total'] ?? $commande['prix'] ?? '') ?>€</td>
                        <td><a href="detail-commande.php?id=<?= htmlspecialchars($commande['id']) ?>">Voir le détail</a></td>
                    </tr>
                    <?php
                }

                if (!$trouve) {
                    echo '<tr><td colspan="6">Aucune commande pour ce statut.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
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
