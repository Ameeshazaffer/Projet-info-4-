<?php

session_start();
header("Content-Type: application/json"); // la réponse qui va être envoyé est en json

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'administrateur') {
    echo json_encode([ "succes" => false, "message" => "Non autorisé."]);
    exit;
}

$donnees_recup = json_decode(file_get_contents("php://input"), true); // transforme json en tableau php

if (!isset($donnees_recup["id"]) || !isset($donnees_recup["action"])) { // vérifie que l'identifiant et l'action existe car on peut pas travailler sans
    echo json_encode([ "succes" => false, "message" => "Données incomplètes."]);
    exit;
}

// on recupère les variables
$id = $donnees_recup["id"]; 
$action = $donnees_recup["action"];

if ($action !== "bloquer" && $action !== "debloquer") { // on vérifie si action ok 
    echo json_encode([ "succes" => false, "message" => "Action inconnue."]);
    exit;
}

$contenu = json_decode(file_get_contents("utilisateurs.json"), true); // transforme json des utilisateurs en tableau php

if (!isset($contenu["utilisateurs"][$id])) { // si l'utilisateur existe pas
    echo json_encode([ "succes" => false, "message" => "Utilisateur introuvable."]);
    exit;
}


if ($action === "bloquer") { // si l'action est bloqué on met oui
    $contenu["utilisateurs"][$id]["bloque"] = "oui";
} 
else{ // sinon non
    $contenu["utilisateurs"][$id]["bloque"] = "non";
}

file_put_contents("utilisateurs.json", json_encode($contenu, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)); // on met bien les infos dans le json

echo json_encode([ "succes" => true, "action" => $action ]);
?>
