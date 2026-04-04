<?php
if (!isset($_GET["commande"]) || !isset($_GET["client"])) { // permet de prendre la valeur de l'URL et voir si existe et si existe pas fait arrêter le programme
    die("Commande ou client manquant.");
}

$commandeId = $_GET["commande"];
$clientEmail = $_GET["client"];
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

    <!-- NAV -->
    <nav>
        <div class="conteneur-nav">
            <div class="logo-nav">
                <div class="texte-logo-nav">✦ÉVEIL✦</div>
                <div class="paris-logo-nav">PARIS</div>
            </div>
            <ul class="liens-nav">
                <li><a href="index.php">ACCUEIL</a></li>
                <li><a href="connexion.html" class="bouton-inscription">CONNEXION</a></li>
            </ul>
        </div>
   </nav>

    <!-- NOTATION -->
    <div class="ins">
        <h1>MON AVIS</h1>

        <form action="traitement_notation.php" method="post" class="connexion">
            <input type="hidden" name="commande_id" value="<?php echo htmlspecialchars($commandeId); ?>">
            <input type="hidden" name="client_email" value="<?php echo htmlspecialchars($clientEmail); ?>">

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
