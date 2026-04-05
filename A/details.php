<?php 
session_start();
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'restaurateur'){
    header("Location: index.php");
    exit;
}
if(!isset($_GET['id'])){
    echo "ID manquant";
    exit;
}
$id = $_GET['id'];
$fichier = "commandes.json";
if(!file_exists($fichier)){
    echo "Fichier commandes.json introuvable";
    exit;
}

$donnees_c = json_decode(file_get_contents($fichier), true);
$trouve = false;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>EVEIL - Détails commande</title>
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
            <li><a href="index.php">ACCUEIL</a></li>
            <li><a href="commandes.php">COMMANDES</a></li>
            <li><a href="profil.php">PROFIL</a></li>
            <li><a href="deconnexion.php">DECONNEXION</a></li>
        </ul>
    </div>
</nav>

<!-- CONTENU -->
<div class="details-container">

    <h1 class="details-title">Détails de la commande</h1>

    <?php
    foreach($donnees_c["commandes"] as $commande) {
        if($commande["id"] == $id) {
            $trouve = true;
    ?>

        <div class="details-card">
            <h2 class="details-subtitle">Commande #<?= $commande["id"] ?></h2>

            <h3>Plats :</h3>
            <ul class="details-list">
                <?php foreach($commande["plats"] as $plat): ?>
                    <li><?= $plat["nom"] ?> - <?= $plat["prix"] ?> €</li>
                <?php endforeach; ?>
            </ul>

            <h3>Total : <?= $commande["prix"] ?> €</h3>

            <h3>Informations :</h3>
            <p>Commentaires : <?= $commande["commentaires"] ?? "Aucun" ?></p>
            <p>Paiement : <?= $commande["paiement"] ?? "Non précisé" ?></p>
        </div>

    <?php
            break;
        }
    }

    if(!$trouve){
        echo "<p class='details-error'>Commande introuvable</p>";
    }
    ?>

    <div class="details-back">
        <a href="commandes.php" class="btn-retour">Retour aux commandes</a>
    </div>

</div>

</body>
</html>

