<?php
session_start();
header('Content-Type: application/json');

if (!file_exists("utilisateurs.json")) {
    echo json_encode(['succes' => false, 'message' => 'Fichier utilisateurs introuvable.']);
    exit;
}

$donnees = json_decode(file_get_contents("utilisateurs.json"), true);
$input = json_decode(file_get_contents("php://input"), true);

if (empty($input['email']) || empty($input['mdp'])) {
    echo json_encode(['succes' => false, 'message' => 'Veuillez remplir tous les champs.']);
    exit;
}

foreach ($donnees["utilisateurs"] as $utilisateur) {
    if ($utilisateur['email'] === $input['email'] && $utilisateur['mdp'] === $input['mdp']) {
        if (isset($utilisateur['bloque']) && $utilisateur['bloque'] === "oui") {
    echo json_encode([
        'succes' => false,
        'message' => 'Votre compte a été bloqué par un administrateur.'
    ]);
    exit;
        }
$_SESSION['user'] = [
            'nom'             => $utilisateur['nom'],
            'prenom'          => $utilisateur['prenom'],
            'telephone'       => $utilisateur['telephone'],
            'email'           => $utilisateur['email'],
            'adresse'         => $utilisateur['adresse'],
            'etage'           => $utilisateur['etage'],
            'code_interphone' => $utilisateur['code_interphone'],
            'role'            => $utilisateur['role'],
            'date_inscription' => $utilisateur['date_inscription']
        ];

        $role = $utilisateur['role'] ?? 'client';
        if ($role === 'restaurateur') {
            $redirection = 'restaurateur.php';
        } elseif ($role === 'administrateur') {
            $redirection = 'administrateur.php';
        } elseif ($role === 'livreur') {
            $redirection = 'livraison.php';
        } else {
            $redirection = $_SESSION['redirect_apres_connexion'] ?? 'index.php';
            unset($_SESSION['redirect_apres_connexion']);
        }

        echo json_encode(['succes' => true, 'message' => 'Connexion réussie !', 'redirection' => $redirection]);
        exit;
    }
}

echo json_encode(['succes' => false, 'message' => 'Email ou mot de passe incorrect.']);
exit;
?>
