<?php
session_start();

// ── ÉTAPE 1 : Est-ce que l'utilisateur est connecté ? ──
if (!isset($_SESSION['user'])) {
    // On mémorise qu'il voulait aller sur produits.php
    $_SESSION['redirect_apres_connexion'] = 'produits.php';
    $_SESSION['message'] = "Vous devez être connecté pour commander.";
    header("Location: connexion.html");
    exit;
}

// ── ÉTAPE 2 : Vérifier que le formulaire est complet ──
if (empty($_POST['nom_plat']) || empty($_POST['prix'])) {
    header("Location: produits.php");
    exit;
}

// ── ÉTAPE 3 : Récupérer les infos ──
$nom_plat = $_POST['nom_plat'];
$prix     = intval($_POST['prix']);
$email    = $_SESSION['user']['email'];

// ── ÉTAPE 4 : Lire commandes.json ──
$fichier = "commandes.json";
if (file_exists($fichier)) {
    $donnees = json_decode(file_get_contents($fichier), true);
} else {
    $donnees = ["commandes" => []];
}

// ── ÉTAPE 5 : Créer la commande ──
// On utilise la structure "produits" pour que commande.php puisse l'afficher
$nouvelle_commande = [
    "id"          => uniqid("CMD-"),
    "email"       => $email,
    "produits"    => [
        [
            "nom"      => $nom_plat,
            "prix"     => $prix,
            "quantite" => 1
        ]
    ],
    "prix_total"  => $prix,
    "date"        => date("d/m/Y H:i"),
    "statut"      => "En attente"
];

// ── ÉTAPE 6 : Sauvegarder ──
$donnees["commandes"][] = $nouvelle_commande;
file_put_contents($fichier, json_encode($donnees, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

// ── ÉTAPE 7 : Rediriger vers le profil avec message ──
$_SESSION['message'] = "Votre commande pour \"" . $nom_plat . "\" a bien été enregistrée !";
header("Location: profil.php");
exit;
?>