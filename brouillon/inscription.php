<?php
session_start();

// ── Lecture du fichier JSON existant ──
if (!file_exists("utilisateurs.json")) {
    $donnees = ["utilisateurs" => []];
} else {
    $donnees = json_decode(file_get_contents("utilisateurs.json"), true);
}

if (!isset($donnees["utilisateurs"])) {
    $donnees["utilisateurs"] = [];
}

// ── Vérification que les champs obligatoires sont remplis ──
$champs = ['nom', 'prenom', 'telephone', 'email', 'mdp', 'adresse'];
foreach ($champs as $champ) {
    if (empty($_POST[$champ])) {
        $_SESSION['message'] = "Veuillez remplir tous les champs obligatoires.";
        header("Location: inscription.html");
        exit;
    }
}

$email = trim($_POST['email']);

// ── Vérifier que l'email n'est pas déjà utilisé ──
foreach ($donnees["utilisateurs"] as $utilisateur) {
    if ($utilisateur['email'] === $email) {
        $_SESSION['message'] = "Cette adresse email est déjà utilisée.";
        header("Location: inscription.html");
        exit;
    }
}

// ── Créer le nouvel utilisateur ──
$nouvel_utilisateur = [
    'nom'             => trim($_POST['nom']),
    'prenom'          => trim($_POST['prenom']),
    'telephone'       => trim($_POST['telephone']),
    'email'           => $email,
    'mdp'             => $_POST['mdp'],
    'adresse'         => trim($_POST['adresse']),
    'etage'           => trim($_POST['etage'] ?? ''),
    'code_interphone' => trim($_POST['code_interphone'] ?? ''),
    'role'            => 'client',
    'date_inscription'=> date('Y-m-d'),
    'points'          => 0,
];

// ── Ajouter au tableau et sauvegarder ──
$donnees["utilisateurs"][] = $nouvel_utilisateur;
file_put_contents("utilisateurs.json", json_encode($donnees, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

// ── Connecter directement l'utilisateur après inscription ──
$_SESSION['user'] = [
    'nom'             => $nouvel_utilisateur['nom'],
    'prenom'          => $nouvel_utilisateur['prenom'],
    'telephone'       => $nouvel_utilisateur['telephone'],
    'email'           => $nouvel_utilisateur['email'],
    'adresse'         => $nouvel_utilisateur['adresse'],
    'etage'           => $nouvel_utilisateur['etage'],
    'code_interphone' => $nouvel_utilisateur['code_interphone'],
    'role'            => 'client',
    'date_inscription'=> $nouvel_utilisateur['date_inscription'],
    'points'          => 0,
];

$_SESSION['message'] = "Compte créé avec succès !";

// ── Redirection intelligente ──
// Si l'utilisateur venait de produits.php (il voulait commander), on l'y renvoie
// Sinon on l'envoie sur son profil
if (isset($_SESSION['redirect_apres_connexion'])) {
    $redirect = $_SESSION['redirect_apres_connexion'];
    unset($_SESSION['redirect_apres_connexion']);
    header("Location: " . $redirect);
} else {
    header("Location: profil.php");
}
exit;
?>
