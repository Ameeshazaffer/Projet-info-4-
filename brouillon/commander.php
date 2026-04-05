<?php
session_start();

// ÉTAPE 1 : Est-ce que l'utilisateur est connecté ?
if (!isset($_SESSION['user'])) {
    $_SESSION['redirect_apres_connexion'] = 'produits.php';
    $_SESSION['message'] = "Vous devez être connecté pour commander.";
    header("Location: connexion.html");
    exit;
}

// ÉTAPE 2 : Vérifier que le formulaire est complet
if (empty($_POST['nom_plat']) || empty($_POST['prix'])) {
    header("Location: produits.php");
    exit;
}

// ÉTAPE 3 : Récupérer les infos
$nom_plat = $_POST['nom_plat'];
$prix     = intval($_POST['prix']);
$email    = $_SESSION['user']['email'];

// ÉTAPE 4 : Lire commandes.json
$fichier = "commandes.json";
if (file_exists($fichier)) {
    $donnees = json_decode(file_get_contents($fichier), true);
} else {
    $donnees = ["commandes" => []];
}

// ÉTAPE 5 : Calculer le prochain ID
$prochain_id = 1;
foreach ($donnees["commandes"] as $c) {
    if ($c['id'] >= $prochain_id) {
        $prochain_id = $c['id'] + 1;
    }
}

// ÉTAPE 6 : Créer la commande
$nouvelle_commande = [
    "id"         => $prochain_id,
    "email"      => $email,
    "produits"   => [
        [
            "nom"      => $nom_plat,
            "prix"     => $prix,
            "quantite" => 1
        ]
    ],
    "prix_total" => $prix,
    "date"       => date("d/m/Y H:i"),
    "statut"     => "En attente de paiement"
];

// ÉTAPE 7 : Sauvegarder
$donnees["commandes"][] = $nouvelle_commande;
file_put_contents($fichier, json_encode($donnees, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

// ÉTAPE 8 : Rediriger vers le détail de la commande
header("Location: commande.php?id=" . $prochain_id);
exit;
?>
