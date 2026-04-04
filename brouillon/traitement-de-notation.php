<?php

if (!isset($_POST["commande_id"]) ||!isset($_POST["client_email"]) ||!isset($_POST["note_livraison"]) || !isset($_POST["note_produits"])
) {
    die("Il n'y a pas toutes les informations nécessaires.");
}

$commandeId = $_POST["commande_id"];
$clientEmail = $_POST["client_email"];
$noteLivraison = $_POST["note_livraison"];
$noteProduits = $_POST["note_produits"];

$contenu = file_get_contents("note.json");
$donnees = json_decode($contenu, true);

if (!isset($donnees["notes"])) {
    $donnees["notes"] = array();
}

$Note = array(
    "commande_id" => $commandeId,
    "client_email" => $clientEmail,
    "note_livraison" => $noteLivraison,
    "note_produits" => $noteProduits
);

$donnees["notes"][] = $Note;

file_put_contents("note.json", json_encode($donnees, JSON_PRETTY_PRINT));

header("Location: merci.html");
exit;

?>
