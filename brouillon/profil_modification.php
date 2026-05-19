<?php

session_start();
header("Content-Type: application/json"); // explique que réponse est en json

if (!isset($_SESSION['user'])) {
    echo json_encode(["succes" => false,"message" => "Vous devez être connecté."]);
    exit;
}

$donnees_recup = json_decode(file_get_contents("php://input"), true); // récupère les infos envoyés par fetch et transforme en tableau php 

if (!isset($donnees_recup["nom"]) || !isset($donnees_recup["prenom"]) || !isset($donnees_recup["telephone"]) || !isset($donnees_recup["adresse"])) { // vérifie que toutes les données récupérées
    echo json_encode([ "succes" => false, "message" => "Données incomplètes."]); envoie le message d'erreur au json
    exit;
}
// prend les informations recupérées et enlève les espaces inutiles
$nom = trim($donnees_recup["nom"]);
$prenom = trim($donnees_recup["prenom"]);
$telephone = trim($donnees_recup["telephone"]);
$adresse = trim($donnees_recup["adresse"]);

if ($nom == "" || $prenom == "" || $telephone == "" || $adresse == "") { // vérifie que pas vide
    echo json_encode([ "succes" => false, "message" => "Tous les champs doivent être remplis."]);
    exit;
}

if (!preg_match("/^[0-9]{10}$/", $telephone)) { // 10 chiffres pour le téléphone
    echo json_encode(["succes" => false, "message" => "Le téléphone doit contenir 10 chiffres."]);
    exit;
}

$fichier = "utilisateurs.json";

if (!file_exists($fichier)) { // vérifie que fichier existe
    echo json_encode(["succes" => false,"message" => "Fichier utilisateurs introuvable."]);
    exit;
}

$contenu = json_decode(file_get_contents($fichier), true); // met le json en tableau 
$trouve = false;

foreach ($contenu["utilisateurs"] as $index => $u) { // on cherche l'utilisateur en question
    if ($u["email"] === $_SESSION['user']['email']) { // on filtre avec son email et on modifie 
        $contenu["utilisateurs"][$index]["nom"] = $nom; 
        $contenu["utilisateurs"][$index]["prenom"] = $prenom;
        $contenu["utilisateurs"][$index]["telephone"] = $telephone;
        $contenu["utilisateurs"][$index]["adresse"] = $adresse;
        $_SESSION['user'] = $contenu["utilisateurs"][$index];
        $trouve = true; // on a trouvé l'utilisateur
        break;
    }
}

if (!$trouve) {
    echo json_encode(["succes" => false, "message" => "Utilisateur introuvable."]);
    exit;
}

file_put_contents($fichier, json_encode($contenu, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)); // on enrengistre les modifications

echo json_encode(["succes" => true, "message" => "Profil mis à jour."]); // on met dans le json un message
?>


