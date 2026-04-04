<?php

$contenu = file_get_contents("utilisateurs.json");
$donnees = json_decode($contenu, true);

$utilisateurs = array();
if (isset($donnees["utilisateurs"])) {
  $utilisateurs = $donnees["utilisateurs"];
}

$roleapresfiltrage = "tous";
if (isset($_GET["role"]) && $_GET["role"] != "") {
  $roleapresfiltrage = $_GET["role"];
}
?>
