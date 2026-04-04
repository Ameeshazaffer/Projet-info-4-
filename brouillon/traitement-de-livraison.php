<?php

if (!isset($_POST["id"]) || !isset($_POST["action"])) { // on regarde si les données reçues par le form existent 
    die("Les données ne sont pas complètes.");
}


$id = $_POST["id"]; // on renomme les données du form 
$action = $_POST["action"];

$contenu = file_get_contents("commandes.json");
$donnees = json_decode($contenu, true);


if (isset($donnees["commandes"])) {

    foreach ($donnees["commandes"] as $index => $commande) { // parcourt le tableau de commande avec l'aide de l'index et donc la position de la commande dans le tableau 

        // vérifie si on est sur la bonne commande
        if (isset($commande["id"]) && $commande["id"] == $id) {

            
            if ($action == "Livrée") { // si l'action envoyé est livrée ça modifie le statut dans le json
                $donnees["commandes"][$index]["statut"] = "Livrée"; // va sur les données des commandes et sur la position du tbaleau et ensuite vers le statut de cette commande
            }
            else { // pareil pour l'action abandonné
                if ($action == "Abandonnée") {
                    $donnees["commandes"][$index]["statut"] = "Abandonnée";
                }
            }
        }
    }
}


file_put_contents("commandes.json", json_encode($donnees, JSON_PRETTY_PRINT)); // envoie dans le fichier json

// une fois modifier revient à la page de livraison.php
header("Location: livraison.php");
exit;
?>
