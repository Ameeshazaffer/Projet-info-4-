<?php
$contenu = file_get_contents("utilisateurs.json"); // il lit le fichier utilisateurs.json en entier
$donnees = json_decode($contenu, true); // tranforme le langage json en php sous forme de tableau


$utilisateurs = array();// on cré un tableau vide pour stocker les utilisateurs dedans
if (isset($donnees["utilisateurs"])) { // on regarde si les utilisateurs sont bien dans le tableau
    $utilisateurs = $donnees["utilisateurs"]; // on renomme le tableau en $utilisateurs
}




$roleapresfiltrage = "tous"; // le select est mis directement en tous
if (isset($_GET["role"]) && $_GET["role"] != "") { // on prend la valeur envoyer par le select dans la barre de recherche ( existe et non vide)
    $roleapresfiltrage = $_GET["role"]; // on renomme la valeur prise dans la barre par roleapresfiltrage
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
            <li><a href="administrateur.php">ESPACE</a></li>
            <li><a href="deconnexion.html" class="bouton-inscription">DECONNEXION</a></li>
        </ul>
    </div>
</nav>


<div class="att">
    <h1>ESPACE ADMINISTRATEUR</h1>
    <h2>Gestion des utilisateurs du site</h2>


    <div class="select-container">
        <form method="GET" action="administrateur.php">
            <select name="role" class="select-box"  >
                <option value="tous" <?php if ($roleapresfiltrage == "tous") echo "selected"; ?>>Tous</option> <!-- permet donc de prendre le role apres le filtrage et comparer avec celui de la selection et donc si oui, le marque comme celui selectionner  -->
                <option value="client" <?php if ($roleapresfiltrage == "client") echo "selected"; ?>>Clients</option>
                <option value="admin" <?php if ($roleapresfiltrage == "admin") echo "selected"; ?>>Administrateurs</option>
                <option value="livreur" <?php if ($roleapresfiltrage == "livreur") echo "selected"; ?>>Livreurs</option>
                <option value="restaurateur" <?php if ($roleapresfiltrage == "restaurateur") echo "selected"; ?>>Restaurateurs</option>
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
                <tbody>
                    <?php // partie permettant de faire fonctionner le tableau avec les utilisateurs
                    $utilisateur = false; // aucune personne affichée encore




                    foreach ($utilisateurs as $index => $user) { // permet de parcourir le tableau des utilisateurs avce l'indice ( ex : indice 0 est le premier utilisateur )
                        if (isset($user["role"]) && $user["role"] != "") { // définit le rôle de l'utilisateur que l'on parcourt dans cette boucle
                            $role = $user["role"];
                        }




                        if ($roleapresfiltrage != "tous" && $role != $roleapresfiltrage) { // on prend seulement les utilisateur qui on le même role que le rôle après le filtrage et donc si pas le même on passe à l'utilisateur suivant
                            continue;
                        }




                        $utilisateur = true; // un utilisateur existe


                        ?>
                        <tr> <!--  affiche dans le tableau les données donc nom, prenom et le rôle  -->
                            <td><?php echo htmlspecialchars($user["nom"]); ?></td>
                            <td><?php echo htmlspecialchars($user["prenom"]); ?></td>
                            <td><?php echo htmlspecialchars($role); ?></td>
                            <td>
                               <a href="profil-admin.php?id=<?php echo $index; ?>">Voir le profil</a> <!--pour l'option voir le profil permet d'aller sur la page en question avec le numero d'index pour retrouver l'utilisateur
 -->


                            </td>
                        </tr>
                        <?php
                    }




                    if (!$utilisateur) { // si l'utilisateur n'est pas égal à true ça montre que y en a pas
                        ?>
                        <tr>
                            <td colspan="4">Il n'ya pas d'utilisateur pour le rôle selectionné.</td>
                        </tr>
                        <?php
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



</body>
</html>
