<?php
session_start();
header("Content-Type: application/json");

if (!isset($_SESSION['user'])) {
    echo json_encode([
        "succes" => false,
        "message" => "Vous devez être connecté."
    ]);
    exit;
}

$donnees_recues = json_decode(file_get_contents("php://input"), true);

if (!isset($donnees_recues["nom"]) || !isset($donnees_recues["prenom"]) || !isset($donnees_recues["telephone"]) || !isset($donnees_recues["adresse"])) {
    echo json_encode([ "succes" => false, "message" => "Données incomplètes."]);
    exit;
}

$nom = trim($donnees_recues["nom"]);
$prenom = trim($donnees_recues["prenom"]);
$telephone = trim($donnees_recues["telephone"]);
$adresse = trim($donnees_recues["adresse"]);

if ($nom == "" || $prenom == "" || $telephone == "" || $adresse == "") {
    echo json_encode([ "succes" => false, "message" => "Tous les champs doivent être remplis."]);
    exit;
}

if (!preg_match("/^[0-9]{10}$/", $telephone)) {
    echo json_encode(["succes" => false, "message" => "Le téléphone doit contenir 10 chiffres."]);
    exit;
}

$fichier = "utilisateurs.json";

if (!file_exists($fichier)) {
    echo json_encode(["succes" => false,"message" => "Fichier utilisateurs introuvable."]);
    exit;
}

$contenu = json_decode(file_get_contents($fichier), true);
$trouve = false;

foreach ($contenu["utilisateurs"] as $index => $u) {
    if ($u["email"] === $_SESSION['user']['email']) {
        $contenu["utilisateurs"][$index]["nom"] = $nom;
        $contenu["utilisateurs"][$index]["prenom"] = $prenom;
        $contenu["utilisateurs"][$index]["telephone"] = $telephone;
        $contenu["utilisateurs"][$index]["adresse"] = $adresse;
        $_SESSION['user'] = $contenu["utilisateurs"][$index];
        $trouve = true;
        break;
    }
}

if (!$trouve) {
    echo json_encode(["succes" => false, "message" => "Utilisateur introuvable."]);
    exit;
}

file_put_contents($fichier, json_encode($contenu, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo json_encode(["succes" => true, "message" => "Profil mis à jour."]);
?>


