<?php
if (!isset($_GET["commande"]) || !isset($_GET["client"])) {
    die("Les informations ne sont pas complètes.");
}

$identifiant = $_GET["commande"];
$email = $_GET["client"];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>EVEIL - Notation</title>
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
                <li><a href="profil.php">PROFIL</a></li>
                <li><a href="deconnexion.html" class="bouton-inscription">DECONNEXION</a></li>
            </ul>
        </div>
   </nav>

    <div class="ins">
        <h1>MON AVIS</h1>

        <form action="traitement-de-notation.php" method="post" class="connexion">
            <input type="hidden" name="commande_id" value="<?php echo htmlspecialchars($identifiant); ?>">
            <input type="hidden" name="client_email" value="<?php echo htmlspecialchars($email); ?>">

            <div class="section-notation">
                <h2>Livraison</h2>
                <p>Pour une meilleure expérience, mettez une note sur 5 de votre livraison.</p>

                <select name="note_livraison" required>
                    <option value="">Choisir une note</option>
                    <option value="1">⭐</option>
                    <option value="2">⭐⭐</option>
                    <option value="3">⭐⭐⭐</option>
                    <option value="4">⭐⭐⭐⭐</option>
                    <option value="5">⭐⭐⭐⭐⭐</option>
                </select>
            </div>

            <div class="section-notation">
                <h2>Produits reçus</h2>
                <p>Pour une meilleure expérience, mettez une note sur 5 des produits reçus.</p>

                <select name="note_produits" required>
                    <option value="">Choisir une note</option>
                    <option value="1">⭐</option>
                    <option value="2">⭐⭐</option>
                    <option value="3">⭐⭐⭐</option>
                    <option value="4">⭐⭐⭐⭐</option>
                    <option value="5">⭐⭐⭐⭐⭐</option>
                </select>
            </div>

            <input type="submit" value="Envoyer">
        </form>
    </div>

</body>
</html>
