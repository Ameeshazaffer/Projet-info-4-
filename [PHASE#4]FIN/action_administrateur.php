<?php
session_start();
header("Content-Type: application/json");

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'administrateur') {
    echo json_encode([
        "succes" => false,
        "message" => "Accès refusé."
    ]);
    exit;
}

$donnees_recues = json_decode(file_get_contents("php://input"), true);

if (!isset($donnees_recues["id"]) || !isset($donnees_recues["action"])) {
    echo json_encode([
        "succes" => false,
        "message" => "Données incomplètes."
    ]);
    exit;
}

$id = intval($donnees_recues["id"]);
$action = $donnees_recues["action"];

if (!file_exists("utilisateurs.json")) {
    echo json_encode([
        "succes" => false,
        "message" => "Fichier utilisateurs introuvable."
    ]);
    exit;
}

$donnees = json_decode(file_get_contents("utilisateurs.json"), true);

if (!isset($donnees["utilisateurs"][$id])) {
    echo json_encode([
        "succes" => false,
        "message" => "Utilisateur introuvable."
    ]);
    exit;
}

if ($action === "bloquer") {

    $donnees["utilisateurs"][$id]["bloque"] = "oui";
}

elseif ($action === "vip") {

    if (($donnees["utilisateurs"][$id]["vip"] ?? "non") === "oui") {

        $donnees["utilisateurs"][$id]["vip"] = "non";

    } else {

        $donnees["utilisateurs"][$id]["vip"] = "oui";

        // retire premium
        $donnees["utilisateurs"][$id]["premium"] = "non";
    }
}

elseif ($action === "premium") {

    if (($donnees["utilisateurs"][$id]["premium"] ?? "non") === "oui") {

        $donnees["utilisateurs"][$id]["premium"] = "non";

    } else {

        $donnees["utilisateurs"][$id]["premium"] = "oui";

        // retire VIP
        $donnees["utilisateurs"][$id]["vip"] = "non";
    }
}

elseif ($action === "remise") {
    if (($donnees["utilisateurs"][$id]["remise"] ?? 0) == 10) {
        $donnees["utilisateurs"][$id]["remise"] = 0;
    } else {
        $donnees["utilisateurs"][$id]["remise"] = 10;
    }
}

else {
    echo json_encode([
        "succes" => false,
        "message" => "Action inconnue."
    ]);
    exit;
}

file_put_contents(
    "utilisateurs.json",
    json_encode($donnees, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
);

$utilisateur = $donnees["utilisateurs"][$id];

echo json_encode([
    "succes" => true,
    "message" => "Modification enregistrée.",
    "bloque" => $utilisateur["bloque"] ?? "non",
    "vip" => $utilisateur["vip"] ?? "non",
    "premium" => $utilisateur["premium"] ?? "non",
    "remise" => $utilisateur["remise"] ?? 0
]);
exit;
?>
