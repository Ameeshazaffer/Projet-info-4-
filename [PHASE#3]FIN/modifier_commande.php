<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit;
}

$user = $_SESSION['user'];
$id = intval($_GET['id'] ?? 0);

$fichier = "commandes.json";

if (!file_exists($fichier)) {
    header("Location: profil.php");
    exit;
}

$data = json_decode(file_get_contents($fichier), true);

$commande = null;

foreach ($data["commandes"] as $c) {
    if ($c['id'] == $id && $c['email'] === $user['email']) {
        $commande = $c;
        break;
    }
}

if (!$commande) {
    header("Location: profil.php");
    exit;
}

/*
ON MET LA COMMANDE DANS LE PANIER
*/

$_SESSION['panier'] = [];

foreach ($commande['produits'] as $p) {
    $_SESSION['panier'][] = [
        "nom" => $p["nom"],
        "prix" => $p["prix"],
        "quantite" => $p["quantite"]
    ];
}


$_SESSION['commande_en_cours'] = $id;


header("Location: produits.php");
exit;
?>