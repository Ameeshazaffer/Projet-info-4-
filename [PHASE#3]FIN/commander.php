<?php
session_start();

if (!isset($_SESSION['user'])) {
    $_SESSION['redirect_apres_connexion'] = 'produits.php';
    $_SESSION['message'] = "Vous devez être connecté pour commander.";
    header("Location: connexion.php");
    exit;
}

if (empty($_POST['nom_plat']) || empty($_POST['prix'])) {
    header("Location: produits.php");
    exit;
}

$nom_plat = $_POST['nom_plat'];
$prix     = intval($_POST['prix']);

if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

$trouve = false;
foreach ($_SESSION['panier'] as &$item) {
    if ($item['nom'] === $nom_plat) {
        $item['quantite']++;
        $trouve = true;
        break;
    }
}
unset($item);

if (!$trouve) {
    $_SESSION['panier'][] = [
        "nom"      => $nom_plat,
        "prix"     => $prix,
        "quantite" => 1
    ];
}

$_SESSION['message'] = "« $nom_plat » ajouté à votre commande !";
header("Location: produits.php");
exit;
?>
