<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>EVEIL - Connexion</title>
    <link href="https://fonts.googleapis.com/css2?family=Parisienne&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link id="chgmode" rel="stylesheet" href="styles.css">
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
                <li>
                    <button id="btnchgmode" type="button">🌙</button>
                </li>
            </ul>
        </div>
    </nav>

    <div class="co">
        <form id="formulaire-co" class="connexion">
            <h1>SE CONNECTER</h1>
            <p id="message"></p>

            <div class="champ-avec-compteur-email">
                <input type="email" id="email" placeholder="Email" required>
            </div>

            <div class="champ-avec-compteur-mdp">
                <input type="password" id="mdp" placeholder="Mot de passe" required>
                <span id="bouton-visibilite-mdp" class="oeil material-icons">visibility_off</span>
            </div>

            <input type="submit" value="Se connecter">

            <div class="redirection">
                Pas encore de compte ? <a href="inscription.php">S'inscrire</a>
            </div>
        </form>
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

    <script src="mode.js"></script>
    <script src="connexion.js"></script>
</body>
</html>
