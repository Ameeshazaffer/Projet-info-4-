<?php

$contenu = file_get_contents("utilisateurs.json");
$donnees = json_decode($contenu, true);

$utilisateurs = array();
if (isset($donnees["utilisateurs"])) {
  $utilisateurs = $donnees["utilisateurs"];
}

$roleapresfiltrage = "tous";
if (isset($_GET["role"]) && $_GET["role"] != "") {
  $roleapresfiltrage = $_GET["role"];
}
?>
  
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link href="https://fonts.googleapis.com/css2?family=Parisienne&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <title>EVEIL - Espace administrateur</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>


<nav id="barre-navigation">
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


<div class="att">
    <h1>ESPACE ADMINISTRATEUR</h1>
    <h2>Gestion des utilisateurs du site</h2>


    <div class="select-container">
        <form method="GET" action="administrateur.php">
            <select name="role" class="select-box"  >
                <option value="tous" >Tous</option> <!-- permet donc de prendre le role apres le filtrage et comparer avec celui de la selection et donc si oui, le marque comme celui selectionner  -->
                <option value="client" >Clients</option>
                <option value="admin" >Administrateurs</option>
                <option value="livreur" >Livreurs</option>
                <option value="restaurateur" >Restaurateurs</option>
            </select>
            <button type="submit">Filtrer</button>
        </form>
    </div>


          <div class="liv">
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Rôle</th>
                        <th>Action</th>
                    </tr>
                </thead>
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



