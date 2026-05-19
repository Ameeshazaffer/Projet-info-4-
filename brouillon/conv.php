<?php
session_start();

header('Content-Type: application/json');

$jsonRecu = file_get_contents('php://input');
$donneesRecues = json_decode($jsonRecu, true);

if(!file_exists("utilisateurs.json")){
    echo json_encode([
        "succes" => false,
        "message" => "Erreur technique : Base de données absente."
    ]);
    exit;
} else {
    $donnees = json_decode(file_get_contents("utilisateurs.json"), true); 
}

if(!empty($donneesRecues['email']) && !empty($donneesRecues['mdp'])) {
    
    $email = $donneesRecues['email'];
    $mdp = $donneesRecues['mdp'];

    foreach($donnees["utilisateurs"] as $utilisateur){
        if($utilisateur['email'] === $email && $mdp === $utilisateur['mdp']){
            
            $_SESSION['user'] = [
                'nom' => $utilisateur['nom'],
                'prenom' => $utilisateur['prenom'],
                'telephone' => $utilisateur['telephone'],
                'email' => $utilisateur['email'],
                'adresse' => $utilisateur['adresse'],
                'etage' => $utilisateur['etage'],
                'code_interphone' => $utilisateur['code_interphone'],
                'role' => $utilisateur['role'],
                'date_inscription' => $utilisateur['date_inscription']
            ];

            $role = $utilisateur['role'] ?? 'client';
            if ($role === 'restaurateur') {
                $redirectionUrl = "restaurateur.php";
            } elseif ($role === 'administrateur') {
                $redirectionUrl = "administrateur.php";
            } elseif ($role === 'livreur') {
                $redirectionUrl = "livraison.php";
            } else {
                $redirectionUrl = $_SESSION['redirect_apres_connexion'] ?? 'index.php';
                unset($_SESSION['redirect_apres_connexion']);
            }

            echo json_encode([
                "succes" => true,
                "message" => "Connexion réussie !",
                "redirection" => $redirectionUrl
            ]);
            exit;
        }
    } 
    
    echo json_encode([
        "succes" => false,
        "message" => "Erreur : Email ou mot de passe incorrect."
    ]);
    exit;

} else {
    echo json_encode([
        "succes" => false,
        "message" => "Erreur : Veuillez remplir tous les champs."
    ]);
    exit;
}
?>
