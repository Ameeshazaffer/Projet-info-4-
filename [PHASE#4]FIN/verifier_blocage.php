<?php
if (isset($_SESSION['user'])) {
    $donnees = json_decode(file_get_contents("utilisateurs.json"), true);

    foreach ($donnees["utilisateurs"] as $utilisateur) {
        if ($utilisateur["email"] === $_SESSION["user"]["email"]) {
            if (isset($utilisateur["bloque"]) && $utilisateur["bloque"] === "oui") {
                $_SESSION = [];
                session_destroy();
                header("Location: connexion.php");
                exit;
            }
        }
    }
}
?>
