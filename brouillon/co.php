<?php
session_start();

// ── Lecture du fichier JSON ──
if (!file_exists("utilisateurs.json")) {
    die("Fichier utilisateurs.json inexistant");
}

$donnees = json_decode(file_get_contents("utilisateurs.json"), true);

if (!isset($donnees["utilisateurs"])) {
    $donnees["utilisateurs"] = [];
}

// ── Vérification que les champs sont remplis ──
if (!empty($_POST['email']) && !empty($_POST['mdp'])) {

    $trouve = false;

    foreach ($donnees["utilisateurs"] as $utilisateur) {

        // Vérification email + mot de passe
        // NOTE : dans le JSON les mdp sont en clair pour l'instant
        // Si tu passes à password_hash() dans ins.php, utilise password_verify()
        $mdp_ok = ($utilisateur['mdp'] === $_POST['mdp'])
                  || password_verify($_POST['mdp'], $utilisateur['mdp']);

        if ($utilisateur['email'] === $_POST['email'] && $mdp_ok) {

            // ── Connexion réussie : on stocke en session ──
            $_SESSION['user'] = [
                'nom'             => $utilisateur['nom'],
                'prenom'          => $utilisateur['prenom'],
                'telephone'       => $utilisateur['telephone'],
                'email'           => $utilisateur['email'],
                'adresse'         => $utilisateur['adresse'],
                'etage'           => $utilisateur['etage'],
                'code_interphone' => $utilisateur['code_interphone'],
                'role'            => $utilisateur['role'],
                'date_inscription'=> $utilisateur['date_inscription'],
            ];

            $_SESSION['message'] = "Connexion réussie !";
            header("Location: index.html");
            exit;
        }
    }

    // ── Aucun utilisateur trouvé ──
    $_SESSION['message'] = "Email ou mot de passe incorrect.";
    header("Location: connexion.html");
    exit;

} else {
    $_SESSION['message'] = "Veuillez remplir tous les champs.";
    header("Location: connexion.html");
    exit;
}
?>
