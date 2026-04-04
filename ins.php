
//ins.php//

<?php
session_start();
if(!file_exists("utilisateurs.json")){
	echo "Fichier utilisateurs.json innexistant";
	$donnees = array();
}else{
	$donnees = json_decode(file_get_contents("utilisateurs.json"),true); 
}
if(!isset($donnees["utilisateurs"])) {
    $donnees["utilisateurs"] = [];
}
if(!empty($_POST['nom']) && !empty($_POST['prenom']) && !empty($_POST['telephone']) && !empty($_POST['email']) && !empty($_POST['mdp']) && !empty($_POST['adresse']) && !empty($_POST['etage']) && !empty($_POST['code_interphone'])) {
		 if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
            $_SESSION['message'] = "Email invalide";
            header("Location: inscription.php");
            exit;
         }
        foreach($donnees["utilisateurs"] as $utilisateur){
            if($utilisateur['email'] === $_POST["email"]){
                $_SESSION['message'] = "Email déjà utilisé";
                header("Location: inscription.php");
                exit;
            }
        }
        $nouveau = array(
			'nom'=>$_POST['nom'],
			'prenom'=>$_POST['prenom'],
			'telephone'=>$_POST['telephone'],
			'email'=>$_POST['email'],
            'mdp'=> password_hash($_POST['mdp'], PASSWORD_DEFAULT);
            'adresse'=>$_POST['adresse'],
            'etage'=>$_POST['etage'],
            'code_interphone'=>$_POST['code_interphone'],
            'role'=>'client',
            'date_inscription'=>date("Y-m-d")
		);
        $donnees["utilisateurs"][] = $nouveau;
		file_put_contents("utilisateurs.json",json_encode($donnees,JSON_PRETTY_PRINT)); 
        $_SESSION['message'] = "Inscription réussie !";
        header("Location: connexion.php" );
        exit;
} else {
    $_SESSION['message'] = "Veuillez remplir tous les champs";
    header("Location: inscription.php");
    exit;
}
?>
