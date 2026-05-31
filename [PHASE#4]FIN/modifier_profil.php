<?php
session_start();
header("Content-Type: application/json");

if (!isset($_SESSION["user"])) {
    echo json_encode([
        "succes" => false,
        "message" => "Vous devez être connecté."
    ]);
    exit;
}

$donneesRecues = json_decode(file_get_contents("php://input"), true);

if (
    !isset($donneesRecues["nom"]) ||
    !isset($donneesRecues["prenom"])
) {
    echo json_encode([
        "succes" => false,
        "message" => "Données incomplètes."
    ]);
    exit;
}

$nom = trim($donneesRecues["nom"]);
$prenom = trim($donneesRecues["prenom"]);
$telephone = trim($donneesRecues["telephone"] ?? "");
$adresse = trim($donneesRecues["adresse"] ?? "");
$etage = trim($donneesRecues["etage"] ?? "");
$codeInterphone = trim($donneesRecues["code_interphone"] ?? "");

if ($nom === "" || $prenom === "") {
    echo json_encode([
        "succes" => false,
        "message" => "Le nom et le prénom sont obligatoires."
    ]);
    exit;
}

if (strlen($nom) > 15 || strlen($prenom) > 15) {
    echo json_encode([
        "succes" => false,
        "message" => "Le nom et le prénom ne doivent pas dépasser 15 caractères."
    ]);
    exit;
}

if ($telephone === "" || !preg_match("/^[0-9]{10}$/", $telephone)) {
    echo json_encode([
        "succes" => false,
        "message" => "Le téléphone doit contenir exactement 10 chiffres."
    ]);
    exit;
}

if ($etage !== "" && !is_numeric($etage)) {
    echo json_encode([
        "succes" => false,
        "message" => "L'étage doit être un nombre."
    ]);
    exit;
}

if (!file_exists("utilisateurs.json")) {
    echo json_encode([
        "succes" => false,
        "message" => "Fichier utilisateurs introuvable."
    ]);
    exit;
}

$donneesUtilisateurs = json_decode(file_get_contents("utilisateurs.json"), true);
$emailConnecte = $_SESSION["user"]["email"];
$trouve = false;

foreach ($donneesUtilisateurs["utilisateurs"] as $index => $utilisateur) {
    if ($utilisateur["email"] === $emailConnecte) {
        $donneesUtilisateurs["utilisateurs"][$index]["nom"] = $nom;
        $donneesUtilisateurs["utilisateurs"][$index]["prenom"] = $prenom;
        $donneesUtilisateurs["utilisateurs"][$index]["telephone"] = $telephone;
        $donneesUtilisateurs["utilisateurs"][$index]["adresse"] = $adresse;
        $donneesUtilisateurs["utilisateurs"][$index]["etage"] = $etage;
        $donneesUtilisateurs["utilisateurs"][$index]["code_interphone"] = $codeInterphone;

        $_SESSION["user"]["nom"] = $nom;
        $_SESSION["user"]["prenom"] = $prenom;
        $_SESSION["user"]["telephone"] = $telephone;
        $_SESSION["user"]["adresse"] = $adresse;
        $_SESSION["user"]["etage"] = $etage;
        $_SESSION["user"]["code_interphone"] = $codeInterphone;

        $trouve = true;
        break;
    }
}

if (!$trouve) {
    echo json_encode([
        "succes" => false,
        "message" => "Utilisateur introuvable."
    ]);
    exit;
}

file_put_contents(
    "utilisateurs.json",
    json_encode($donneesUtilisateurs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
);

echo json_encode([
    "succes" => true,
    "message" => "Informations modifiées avec succès."
]);
exit;
?>
