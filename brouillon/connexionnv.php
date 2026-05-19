<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>EVEIL - Connexion</title>
    <link href="https://fonts.googleapis.com/css2?family=Parisienne&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link id="mode" rel="stylesheet" href="styles.css">
    <script src="connexion.js" defer></script>
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

<div class="co">    
    <form action="co.php" method="post" class="connexion" id="formulaire-co">
        <div class="infos"> 
            <h1>SE CONNECTER</h1>
            
            <div class="champ-avec-compteur">
                <input type="email" id="email" name="email" placeholder="Email">
                <span id="compteur-email" class="compteur-caracteres">0 / 30</span>
            </div>
            
            <div class="champ-avec-compteur mdp-conteneur">
                <input type="password" id="mdp" name="mdp" placeholder="Mot de passe">
                <span id="bouton-visibilite-mdp" class="material-icons">visibility_off</span>
                <span id="compteur-mdp" class="compteur-caracteres">0 / 8</span>
            </div>
        </div>
        
        <div class="bouton">
            <input type="submit" value="Se connecter">
        </div>
        
        <div class="redirection">
            Pas encore de compte ? <a href="inscription.php">S'inscrire</a>
        </div>
        
        <div id="message"></div>
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

</body>
</html>
