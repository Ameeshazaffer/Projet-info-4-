<?php

session_start();
header("Content-Type: application/json");

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'administrateur') {
    echo json_encode([ "succes" => false, "message" => "Non autorisé."]);
    exit;
}

$donnees_recup = json_decode(file_get_contents("php://input"), true);

if (!isset($donnees_recup["id"]) || !isset($donnees_recup["action"])) {
    echo json_encode([ "succes" => false, "message" => "Données incomplètes."]);
    exit;
}


$id = $donnees_recup["id"];
$action = $donnees_recup["action"];

if ($action !== "bloquer" && $action !== "debloquer") {
    echo json_encode([ "succes" => false, "message" => "Action inconnue."]);
    exit;
}

$contenu = json_decode(file_get_contents("utilisateurs.json"), true);

if (!isset($contenu["utilisateurs"][$id])) {
    echo json_encode([ "succes" => false, "message" => "Utilisateur introuvable."]);
    exit;
}


if ($action === "bloquer") {
    $contenu["utilisateurs"][$id]["bloque"] = "oui";
} 
else{
    $contenu["utilisateurs"][$id]["bloque"] = "non";
}

file_put_contents("utilisateurs.json", json_encode($contenu, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo json_encode([ "succes" => true, "action" => $action ]);
?>
