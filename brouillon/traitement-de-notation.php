<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'client') {
    header("Location: connexion.php");
    exit;
}

if (!isset($_POST["commande_id"]) || !isset($_POST["client_email"]) || !isset($_POST["note_livraison"]) || !isset($_POST["note_produits"])) { // regarde si toutes les infos existent 
    die("Il n'y a pas toutes les informations nécessaires.");
}

// on prend les données
$commande_id = $_POST["commande_id"];
$client_email = $_POST["client_email"];
$note_livraison = $_POST["note_livraison"];
$note_produits = $_POST["note_produits"];

if ($client_email !== $_SESSION['user']['email']) { // est ce que commande du client connecté
    die("Vous ne pouvez pas noter cette commande.");
}

if (file_exists("note.json")) { // vérifie si json existe  
    $donnees = json_decode(file_get_contents("note.json"), true); // lit fichier et transforme en tableau
} 
else{
    $donnees = ["notes" => []]; // on crée un tableau vide
}

if (!isset($donnees["notes"])) {
    $donnees["notes"] = [];
}

foreach ($donnees["notes"] as $note) { // on parcourt le tableau 
    if ( isset($note["commande_id"]) && $note["commande_id"] == $commande_id && isset($note["client_email"]) && $note["client_email"] == $client_email ) { // si client déjà noter on peut pas continuer 
        die("Vous avez déjà noté cette commande.");
    }
}

$donnees["notes"][] = ["commande_id" => $commande_id, "client_email" => $client_email, "note_livraison" => $note_livraison, "note_produits" => $note_produits ]; // on ajoute les infos dans le tableau 

file_put_contents("note.json", json_encode($donnees, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)); // on met dans le json 

header("Location: merci.php");
exit;
?>
