//co.php//

<?php
session_start();
if(!file_exists("utilisateurs.json")){
	echo "Fichier utilisateurs.json innexistant";
	$donnees = array();
}
else {
	$donnees = json_decode(file_get_contents("utilisateurs.json"),true); 
}
if(!isset($donnees["utilisateurs"])) {
    $donnees["utilisateurs"] = [];
}
if(!empty($_POST['email']) && !empty($_POST['mdp'])) {
		foreach($donnees["utilisateurs"] as $utilisateur){
            if($utilisateur['email'] === $_POST["email"] && password_verify($_POST["mdp"], $utilisateur['mdp']))
                $_SESSION['user']=[
                    'nom'=>$utilisateur['nom'],
			        'prenom'=>$utilisateur['prenom'],
			        'telephone'=>$utilisateur['telephone'],
			        'email'=>$utilisateur['email'],
                    'mdp' => password_hash($_POST['mdp'],PASSWORD_DEFAULT)
                    'adresse'=>$utilisateur['adresse'],
                    'etage'=>$utilisateur['etage'],
                    'code_interphone'=>$utilisateur['code_interphone'],
                    'role'=>$utilisateur['role'],
                ];
                $_SESSION['message'] = "Connexion réussie !";
                header("Location: index.php" );
                exit;
            }
        } 
        $_SESSION['message'] = "Email ou mot de passe incorrect";
        header("Location: connexion.php");
        exit;
}else{
    $_SESSION['message'] = "Veuillez remplir tous les champs";
    header("Location: connexion.php");
    exit;
}
?>
