<?php
$messages = array();
if(!file_exists("utilisateurs.json")) {
	$messages[] = "Fichier utilisateurs.json manquant";
	$backdata = array();
}
else {
	$backdata = json_decode(file_get_contents("utilisateurs.json"), true); 
}
if(!empty($_POST['nom']) && !empty($_POST['prenom']) && !empty($_POST['telephone']) && !empty($_POST['email']) && !empty($_POST['adresse']) && !empty($_POST['etage']) && !empty($_POST['code_interphone'])) {
		 $nouveau = array(
			'nom' => $_POST['nom'],
			'prenom' => $_POST['prenom'],
			'telephone' => $_POST['telephone'],
			'email' => $_POST['email'],
            'mdp' => $_POST['mdp'],
            'adresse' => $_POST['adresse'],
            'etage' => $_POST['etage'],
            'code_interphone'=> $_POST['code_interphone'],
            'role' => 'client',
            'date_inscription' => date("Y-m-d")
		);

        $backdata["utilisateurs"][] = $nouveau;
		file_put_contents("utilisateurs.json", json_encode($backdata, JSON_PRETTY_PRINT)); 
}
header("Location: index.php");
exit;
?>
