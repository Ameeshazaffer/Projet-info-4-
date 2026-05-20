<?php

session_start();
header("Content-Type: application/json");


if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'livreur') {
    echo json_encode([ "succes" => false, "message" => "Non autorisé."]);
    exit;
}

$donnees_recup = json_decode(file_get_contents("php://input"), true); // récupère json et transforme en tableau 


if (!isset($donnees_recup["id"]) || !isset($donnees_recup["action"])) { // si identifiant ou action existe pas dans le tableau 
    echo json_encode(["succes" => false, "message" => "Données incomplètes."]);
    exit;
}

$identifiant = $donnees_recup["id"];
$action = $donnees_recup["action"];


if ($action !== "Livrée" && $action !== "Abandonnée") { // si l'action n'est ni livré ni abandonné pas possible de continuer
    echo json_encode(["succes" => false, "message" => "Action incorrecte."]);
    exit;
}

$contenu = file_get_contents("commandes.json");
$donnees = json_decode($contenu, true); // transforme en tableau les commandes 

$trouve = false; // on voit si la commande a été trouvée ou pas

foreach ($donnees["commandes"] as $index => $commande) { // parcourt le tableau créé
    if ($commande["id"] == $identifiant) { // on cherche l'identifiant pour trouve la commande 
        $donnees["commandes"][$index]["statut"] = $action; // si l'action est livrée met et pareil pour abandon
        $trouve = true;
        break;
    }
}


if ($trouve) {
    file_put_contents("commandes.json", json_encode($donnees, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));// on met le tableau en json et on écrit dans le fichier json des commandes
    echo json_encode(["succes" => true, "action" => $action ]);
} 
else{
    echo json_encode([ "succes" => false, "message" => "Commande introuvable."]);
}
?>


