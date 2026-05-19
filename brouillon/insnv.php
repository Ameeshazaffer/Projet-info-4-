<?php
header('Content-Type: application/json');

$jsonRecu = file_get_contents('php://input');
$donneesRecues = json_decode($jsonRecu, true);

if(!file_exists("utilisateurs.json")){
    $donneesJson = [
        "utilisateurs" => []
    ];
} else {
    $donneesJson = json_decode(file_get_contents("utilisateurs.json"), true);
}

if(
    !empty($donneesRecues['nom']) && 
    !empty($donneesRecues['prenom']) && 
    !empty($donneesRecues['email']) && 
    !empty($donneesRecues['mdp']) &&
    !empty($donneesRecues['adresse'])
) {
    
    $email = $donneesRecues['email'];
    $mdp = $donneesRecues['mdp'];

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        echo json_encode([
            "succes" => false,
            "message" => "Erreur : L'adresse email n'est pas au bon format."
        ]);
        exit;
    }

    if(strlen($mdp) > 8) {
        echo json_encode([
            "succes" => false,
            "message" => "Erreur : Le mot de passe ne doit pas dépasser 8 caractères."
        ]);
        exit;
    }

    foreach($donneesJson["utilisateurs"] as $u){
        if($u['email'] === $email){
            echo json_encode([
                "succes" => false,
                "message" => "Erreur : Cet email est déjà utilisé par un autre compte."
            ]);
            exit;
        }
    }

    $nouveau = [
        "nom" => $donneesRecues['nom'],
        "prenom" => $donneesRecues['prenom'],
        "telephone" => $donneesRecues['telephone'],
        "email" => $email,
        "mdp" => $mdp, 
        "adresse" => $donneesRecues['adresse'],
        "etage" => $donneesRecues['etage'],
        "code_interphone" => isset($donneesRecues['code_interphone']) ? $donneesRecues['code_interphone'] : '',
        "commentaires" => isset($donneesRecues['commentaires']) ? $donneesRecues['commentaires'] : '',
        "role" => 'client',
        "date_inscription" => date("Y-m-d")
    ];

    $donneesJson["utilisateurs"][] = $nouveau;
    file_put_contents("utilisateurs.json", json_encode($donneesJson, JSON_PRETTY_PRINT)); 

    echo json_encode([
        "succes" => true,
        "message" => "Inscription réussie ! Vous allez être redirigé..."
    ]);
    exit;

} else {
    echo json_encode([
        "succes" => false,
        "message" => "Erreur : Veuillez remplir tous les champs obligatoires."
    ]);
    exit;
}
?>
