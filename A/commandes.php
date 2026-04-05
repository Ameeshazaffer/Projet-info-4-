<?php 
session_start();

if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'restaurateur'){
    header("Location:index.php");
    exit;
}

$fichier_commandes = "commandes.json";
$fichier_utilisateurs = "utilisateurs.json";

$donnees_c = json_decode(file_get_contents($fichier_commandes), true);
$donnees_u = json_decode(file_get_contents($fichier_utilisateurs), true);

if (!isset($donnees_c["commandes"])) $donnees_c["commandes"] = [];
if (!isset($donnees_u["utilisateurs"])) $donnees_u["utilisateurs"] = [];

$utilisateurs_by_id = [];
foreach($donnees_u["utilisateurs"] as $u){
    $utilisateurs_by_id[$u["id"]] = $u;
}

$livreurs_dispo = [];
foreach($donnees_u["utilisateurs"] as $u){
    if($u["role"] === "livreur" && $u["statut"] === "disponible"){
        $livreurs_dispo[] = $u;
    }
}

if(isset($_POST["livrer"]) && isset($_POST["id"]) && isset($_POST["livreur"])){
    
    $id_commande = $_POST["id"];
    $livreur_id = $_POST["livreur"];

    foreach($donnees_c["commandes"] as $i => $c){
        if($c["id"] === $id_commande){
            $donnees_c["commandes"][$i]["statut"] = "en livraison";
            $donnees_c["commandes"][$i]["livreur_id"] = $livreur_id;
            break;
        }
    }

    file_put_contents($fichier_commandes, json_encode($donnees_c, JSON_PRETTY_PRINT));

    foreach($donnees_u["utilisateurs"] as $i => $u){
        if($u["id"] === $livreur_id){
            $donnees_u["utilisateurs"][$i]["statut"] = "occupé";
        }
    }

    file_put_contents($fichier_utilisateurs, json_encode($donnees_u, JSON_PRETTY_PRINT));

    header("Location: commandes.php");
    exit;
}

function obtenirNomClient($client_id, $utilisateurs_by_id){
    if(isset($utilisateurs_by_id[$client_id])){
        $u = $utilisateurs_by_id[$client_id];
        return $u["prenom"] . " " . $u["nom"];
    }
    return "Inconnu";
}

function obtenirNomLivreur($livreur_id, $utilisateurs_by_id){
    if($livreur_id && isset($utilisateurs_by_id[$livreur_id])){
        $u = $utilisateurs_by_id[$livreur_id];
        return $u["prenom"] . " " . $u["nom"];
    }
    return "Non assigné";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>EVEIL - Commandes</title>
    <link href="https://fonts.googleapis.com/css2?family=Parisienne&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            <li><a href="commandes.php">COMMANDES</a></li>
            <li><a href="profil.php">PROFIL</a></li>
            <li><a href="deconnexion.php" class="bouton-deco">DECONNEXION</a></li>
        </ul>
    </div>
</nav>

<div class="att">
  <h1>COMMANDES EN ATTENTE DE PRISE EN CHARGE</h1>
    <table border="1">
    <tr>
        <th>ID</th>
        <th>Client</th>
        <th>Date</th>
        <th>Détails</th>
    </tr>

    <?php foreach($donnees_c["commandes"] as $c): ?>
        <?php if($c["statut"] === "en attente"): ?>
        <tr>
            <td><?= $c["id"] ?></td>
            <td><?= obtenirNomClient($c["client_id"], $utilisateurs_by_id) ?></td>
            <td><?= $c["date"] . " " . $c["heure"] ?></td>
            <td><a href="details.php?id=<?= $c["id"] ?>">[+]</a></td>
        </tr>
        <?php endif; ?>
    <?php endforeach; ?>
    </table>
</div>

<div class="enl">
<h1>COMMANDES A PREPARER</h1>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Client</th>
        <th>Détails</th>
        <th>Livreur</th>
        <th>Action</th>
    </tr>

    <?php foreach($donnees_c["commandes"] as $c): ?>
        <?php if($c["statut"] === "en préparation"): ?>
        <tr>
            <td><?= $c["id"] ?></td>
            <td><?= obtenirNomClient($c["client_id"], $utilisateurs_by_id) ?></td>
            <td><a href="details.php?id=<?= $c["id"] ?>">[+]</a></td>
            <td>
                <form method="POST">
                    <input type="hidden" name="id" value="<?= $c["id"] ?>">

                    <select name="livreur" required>
                        <option value="">Choix livreur</option>
                        <?php foreach($livreurs_dispo as $l): ?>
                            <option value="<?= $l["id"] ?>">
                                <?= $l["prenom"] . " " . $l["nom"] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </form>
            </td>
            <td><button type="submit" name="livrer">Envoyer en livraison</button></td>
        </tr>
        <?php endif; ?>
    <?php endforeach; ?>
</table>
</div>

<div class="enl">
    <h1>COMMANDES EN COURS DE LIVRAISON</h1>
    <table border="1">
    <tr>
        <th>ID</th>
        <th>Client</th>
        <th>Livreur</th>
        <th>Statut</th>
        <th>Détails</th>
    </tr>

    <?php foreach($donnees_c["commandes"] as $c): ?>
        <?php if($c["statut"] === "en livraison"): ?>
        <tr>
            <td><?= $c["id"] ?></td>
            <td><?= obtenirNomClient($c["client_id"], $utilisateurs_by_id) ?></td>
            <td><?= isset($c["livreur_id"]) ? obtenirNomLivreur($c["livreur_id"], $utilisateurs_by_id) : "Non assigné" ?></td>
            <td><?= $c["statut"] ?></td>
            <td><a href="details.php?id=<?= $c["id"] ?>">[+]</a></td>
        </tr>
        <?php endif; ?>
    <?php endforeach; ?>
    </table>
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
