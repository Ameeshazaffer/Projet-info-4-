<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'restaurateur') {
    header("Location: connexion.php");
    exit;
}

$donneesCommandes = json_decode(file_get_contents("commandes.json"), true);
$commandes = $donneesCommandes["commandes"] ?? [];

$donneesUtilisateurs = json_decode(file_get_contents("utilisateurs.json"), true);
$livreurs = [];
if (isset($donneesUtilisateurs["utilisateurs"])) {
    foreach ($donneesUtilisateurs["utilisateurs"] as $unUtilisateur) {
        if (($unUtilisateur['role'] ?? '') === 'livreur') {
            $livreurs[] = $unUtilisateur;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>EVEIL - Espace Restaurateur</title>
    <link href="https://fonts.googleapis.com/css2?family=Parisienne&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link id="mode" rel="stylesheet" href="styles.css">
    <script src="restaurateur.js" defer></script>
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
        <select name="statut" class="selection">
            <option value="tous">Tous les statuts</option>
            <option value="payée" <?= ($_GET['statut'] ?? '') === 'payée' ? 'selected' : '' ?>>Payée</option>
            <option value="en préparation" <?= ($_GET['statut'] ?? '') === 'en préparation' ? 'selected' : '' ?>>En préparation</option>
            <option value="prête" <?= ($_GET['statut'] ?? '') === 'prête' ? 'selected' : '' ?>>Prête</option>
            <option value="en livraison" <?= ($_GET['statut'] ?? '') === 'en livraison' ? 'selected' : '' ?>>En livraison</option>
            <option value="livrée" <?= ($_GET['statut'] ?? '') === 'livrée' ? 'selected' : '' ?>>Livrée</option>
        </select>
        <button type="submit">Filtrer</button>
    </form>

    <div class="liv">
        <table id="liste-commandes">
            <thead>
                <tr>
                    <th>N° commande</th>
                    <th>Date</th>
                    <th>Statut</th>
                    <th>Paiement</th>
                    <th>Prix total</th>
                    <th>Actions de Statut</th>
                    <th>Détail</th>
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
                    $idCmd = htmlspecialchars($commande['id']);
                    $statutCmd = htmlspecialchars($commande['statut']);
                    ?>
                    
                    <tr id="ligne-commande-<?= $idCmd ?>">
                        <td><?= $idCmd ?></td>
                        <td><?= htmlspecialchars($commande['date']) ?></td>
                        <td class="cellule-statut"><?= $statutCmd ?></td>
                        <td><?= htmlspecialchars($commande['paiement'] ?? 'Payée') ?></td>
                        <td><?= htmlspecialchars($commande['prix_total'] ?? $commande['prix'] ?? '') ?>€</td>
                        
                        <td class="cellule-actions">
                             <?php if ($statutCmd === 'payée'): ?>
                                <button class="btn-statut btn-preparer" onclick="gererStatut(this, <?= $idCmd ?>, 'preparer')">En préparation</button>
                             <?php elseif ($statutCmd === 'en préparation'): ?>
                                <div class="zone-assignation">
                                    <select class="select-livreur" id="select-livreur-<?= $idCmd ?>">
                                        <option value="">-- Choisir un livreur --</option>
                                        <?php foreach ($livreurs as $livreur): ?>
                                            <option value="<?= htmlspecialchars($livreur['email']) ?>">
                                                <?= htmlspecialchars($livreur['nom'] . ' ' . $livreur['prenom']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button class="btn-statut btn-prete" onclick="gererStatut(this, <?= $idCmd ?>, 'prete')">Prête & Assigner</button>
                                </div>
                            <?php else: ?>
                                <span style="color: gray; font-style: italic;">Aucune action requise</span>
                            <?php endif; ?>
                        </td>
                        
                        <td><a href="detail-commande.php?id=<?= $idCmd ?>">Voir le détail</a></td>
                    </tr>
                    
                    <?php
                }

                if (!$trouve) {
                    echo '<tr><td colspan="7">Aucune commande pour ce statut.</td></tr>';
                }
                ?>
            </tbody>
        </table>
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
