<?php
session_start();
if(!file_exists("utilisateurs.json")){
    $_SESSION['message'] = "Fichier utilisateurs.json inexistant";
    header("Location: connexion.php");
    exit;
} else {
    $donnees = json_decode(file_get_contents("utilisateurs.json"), true); 
}

if(!empty($_POST['email']) && !empty($_POST['mdp'])) {
    foreach($donnees["utilisateurs"] as $utilisateur){
        if($utilisateur['email'] === $_POST["email"] && $_POST["mdp"]==$utilisateur['mdp']){
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
            $_SESSION['message'] = "Connexion réussie !";
            $role = $utilisateur['role'] ?? 'client';
            if ($role === 'restaurateur') {
                header("Location: restaurateur.php");
            } elseif ($role === 'administrateur') {
                header("Location: administrateur.php");
            } elseif ($role === 'livreur') {
                header("Location: livraison.php");
            } else {
                $redirect = $_SESSION['redirect_apres_connexion'] ?? 'index.php';
                unset($_SESSION['redirect_apres_connexion']);
                header("Location: " . $redirect);
            }
            exit;
        }
    } 
    $_SESSION['message'] = "Email ou mot de passe incorrect";
    header("Location: connexion.php");
    exit;
} else {
    $_SESSION['message'] = "Veuillez remplir tous les champs";
    header("Location: connexion.php");
    exit;
}
?>