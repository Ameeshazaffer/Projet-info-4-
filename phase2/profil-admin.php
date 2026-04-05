<?php
$contenu = file_get_contents("utilisateurs.json"); // lit le fichier json
$donnees = json_decode($contenu, true); // met en tableau php


$utilisateurs = array(); // cré tableau vide
if (isset($donnees["utilisateurs"])) {
    $utilisateurs = $donnees["utilisateurs"];
}


if (!isset($_GET["id"])) { // regarde si l'index qui est dans l'URL de l'utilisateur existe ( et qui est le positionnement de l'utilsateur dans le tableau)
    die("Aucun utilisateur sélectionné.");
}


$id = $_GET["id"];


if (!isset($utilisateurs[$id])) {
    die("Utilisateur introuvable.");
}


$user = $utilisateurs[$id]; // on nomme chaque éléments du tableau pour que plus simple lorsque on veut afficher les éléments dans le tableau


if (isset($user["nom"])) {
    $nom = $user["nom"];
} else {
    $nom = "";
}


if (isset($user["prenom"])) {
    $prenom = $user["prenom"];
} else {
    $prenom = "";
}


if (isset($user["telephone"])) {
    $telephone = $user["telephone"];
} else {
    $telephone = "";
}


if (isset($user["email"])) {
    $email = $user["email"];
} else {
    $email = "";
}


if (isset($user["role"])) {
    $role = $user["role"];
} else {
    $role = "client";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>EVEIL - Profil administrateur</title>
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


    <div class="att">
        <h1>PROFIL DE L'UTILISATEUR</h1>


        <table class="liv">
            <tr>
                <th colspan="2">INFORMATIONS</th>
            </tr>
            <tr>
                <th>Nom</th>
                <td><?php echo htmlspecialchars($nom); ?></td> <!-- on affiche le nom... dans le tableau des informations de l'utilisateur -->
            </tr>
            <tr>
                <th>Prénom</th>
                <td><?php echo htmlspecialchars($prenom); ?></td>
            </tr>
            <tr>
                <th>Téléphone</th>
                <td><?php echo htmlspecialchars($telephone); ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?php echo htmlspecialchars($email); ?></td>
            </tr>
            <tr>
                <th>Rôle</th>
                <td><?php echo htmlspecialchars($role); ?></td>
            </tr>
        </table>


        <div class="actions-admin">
            <h2>Actions de l'administrateur</h2>


            <div class="actions-admin-boutons">
                <button type="button">Bloquer le compte</button> <!-- on met toutes les fonctionnalités de l'administrateur sous forme de bouton ( seulement en affichage pour l'instant ) -->
                <button type="button">Désactiver le compte</button>
                <button type="button">Passer en Premium</button>
                <button type="button">Passer en VIP</button>
                <button type="button">Faire une remise de 10%</button>
            </div>
        </div>


        <div class="retour-admin">
            <a href="administrateur.php">← Retour à la liste des utilisateurs</a>
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
