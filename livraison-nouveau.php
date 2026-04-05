<?php
session_start();

// on vérifie que l'utilisateur est connecté et que c'est un livreur
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'livreur') {
    header("Location: connexion.php");
    exit;
}

$contenu = file_get_contents("commandes.json");
$donnees = json_decode($contenu, true);


$commandes = array(); // on met commandes car on a appelé les commandes "commandes" dans le fichier commandes.json
if (isset($donnees["commandes"])) {
    $commandes = $donnees["commandes"];
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>EVEIL - Livraison</title>
    <link href="https://fonts.googleapis.com/css2?family=Parisienne&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>


    <nav>
        <div class="conteneur-nav">
            <div class="logo-nav">
                <div class="texte-logo-nav">✦ÉVEIL✦</div>
                <div class="paris-logo-nav">PARIS</div>
            </div>
            <ul class="liens-nav">
                <li><a href="index.php">ACCUEIL</a></li>
                <li><a href="connexion.html" class="bouton-inscription">CONNEXION</a></li>
            </ul>
        </div>
    </nav>


    <div class="liv">
        <h1>COMMANDES À LIVRER</h1>

<!-- pour ordinateur ou tablette -->
        <table>
            <thead>
                <tr>
                    <th>Numéro de commande</th>
                    <th>Adresse</th>
                    <th>Code interphone</th>
                    <th>Étage</th>
                    <th>Commentaires</th>
                    <th>Numéro de téléphone</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php


                $Commande = false; // pas encore de commande prise en compte et donc pas encore affichée dans le tableau


                foreach ($commandes as $commande) { // parcourt le tableau et donc regarde chaque commande avec le numero de la commande
                    if (isset($commande["statut"]) && $commande["statut"] != "Payée") { // si le statut de la commande n'est pas "En attente", passer à la commande suivante
                        continue;
                    }


                    $Commande = true; // montre que la commande à afficher a été trouvée


                    $idCommande = ""; // on regarde si existe et on renomme pour que ce soit plus facile pour afficher les données après dans le tableau
                    if (isset($commande["id"])) {
                        $idCommande = $commande["id"];
                    }


                    $adresse = "";
                    if (isset($commande["adresse"])) {
                        $adresse = $commande["adresse"];
                    }


                    $interphone = "";
                    if (isset($commande["code_interphone"])) {
                        $interphone = $commande["code_interphone"];
                    }


                    $etage = "";
                    if (isset($commande["etage"])) {
                        $etage = $commande["etage"];
                    }

                    $commentaire = "";
                    if (isset($commande["commentaires"])) {
                        $commentaire = $commande["commentaires"];
                    }
                    
                    $telephone = "";
                    if (isset($commande["telephone"])) {
                        $telephone = $commande["telephone"];
                    }
               // pour le telephone
                    $telLien = "+33" . ltrim(str_replace(" ", "", $telephone), "0");
                    ?>
                    <tr> <!-- met chaque valeur de la commande dans le tableau -->
                        <td><?php echo htmlspecialchars($idCommande); ?></td>
                        <td class="adresse"><?php echo htmlspecialchars($adresse); ?></td>
                        <td><?php echo htmlspecialchars($interphone); ?></td>
                        <td><?php echo htmlspecialchars($etage); ?></td>
                        <td><?php echo htmlspecialchars($commentaire); ?></td>
                       <td><?php echo htmlspecialchars($telephone); ?></td>
                        <td>
                            <form action="traitement-de-livraison.php" method="POST">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($idCommande); ?>">
                                <input type="hidden" name="action" value="Livrée">
                                <button type="submit">Livrée</button>
                            </form>


                            <form action="traitement-de-livraison.php" method="POST">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($idCommande); ?>">
                                <input type="hidden" name="action" value="Abandonnée">
                                <button type="submit">Abandonnée</button>
                            </form>
                        </td>
                    </tr>
                    <?php
                }


                if (!$Commande) {
                    ?>
                    <tr>
                        <td colspan="6">Il n'y a pas de commande en attente de livraison.</td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>

        <!-- pour le telephone -->
         <?php


                foreach ($commandes as $commande) { // parcourt le tableau et donc regarde chaque commande avec le numero de la commande
                    if (isset($commande["statut"]) && $commande["statut"] != "Payée") { // si le statut de la commande n'est pas "En attente", passer à la commande suivante
                        continue;
                    }


                    $Commande = true; // montre que la commande à afficher a été trouvée


                    $idCommande = ""; // on regarde si existe et on renomme pour que ce soit plus facile pour afficher les données après dans le tableau
                    if (isset($commande["id"])) {
                        $idCommande = $commande["id"];
                    }


                    $adresse = "";
                    if (isset($commande["adresse"])) {
                        $adresse = $commande["adresse"];
                    }


                    $interphone = "";
                    if (isset($commande["code_interphone"])) {
                        $interphone = $commande["code_interphone"];
                    }


                    $etage = "";
                    if (isset($commande["etage"])) {
                        $etage = $commande["etage"];
                    }

                    $commentaire = "";
                    if (isset($commande["commentaires"])) {
                        $commentaire = $commande["commentaires"];
                    }
                    
                    $telephone = "";
                    if (isset($commande["telephone"])) {
                        $telephone = $commande["telephone"];
                    }
               // pour le telephone
                    $telLien = "+33" . ltrim(str_replace(" ", "", $telephone), "0");
                    ?>
                    <div class="liv-commande">
                        <p><strong>Numéro de commande :</strong> <?php echo htmlspecialchars($idCommande); ?></p>
                        <p><strong>Adresse :</strong> <span class="adresse"><?php echo htmlspecialchars($adresse); ?></span></p>
                        <p><strong>Code interphone :</strong> <?php echo htmlspecialchars($interphone); ?></p>
                        <p><strong>Étage :</strong> <?php echo htmlspecialchars($etage); ?></p>
                        <p><strong>Commentaires :</strong> <?php echo htmlspecialchars($commentaire); ?></p>
                        <p><strong>Téléphone :</strong> <a href="tel:<?php echo htmlspecialchars($telLien); ?>"><?php echo htmlspecialchars($telephone); ?></a></p>
                            <form action="traitement-de-livraison.php" method="POST">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($idCommande); ?>">
                                <input type="hidden" name="action" value="Livrée">
                                <button type="submit">Livrée</button>
                            </form>


                            <form action="traitement-de-livraison.php" method="POST">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($idCommande); ?>">
                                <input type="hidden" name="action" value="Abandonnée">
                                <button type="submit">Abandonnée</button>
                            </form>
                        
                    <?php
                }
                ?>

    </div>


    <footer>
        <div class="logo-pied-page">
            <div class="texte-logo-pied-page">✧ÉVEIL✧</div>
            <div class="paris-logo-pied-page">PARIS</div>
            <div class="slogan-logo-pied-page">Éveillez vos papilles gustatives.</div>
        </div>
        <div class="infos-pied-page">
            <div class="section-pied-page">
                <h3>ADRESSE</h3>
                <p>123 Avenue des Champs-Élysées<br>75008 Paris, France</p>
            </div>
            <div class="section-pied-page">
                <h3>HORAIRES</h3>
                <p>Mardi - Samedi<br>12h00 - 14h30 | 19h00 - 22h30<br>Fermé Dimanche & Lundi</p>
            </div>
            <div class="section-pied-page">
                <h3>CONTACT</h3>
                <p>Tél: +33 1 23 45 67 89<br>Email: contact@eveilparis.fr</p>
            </div>
        </div>
        <p style="margin-top:2rem;color:#C9B896;">© 2026 EVEIL Paris. Tous droits réservés.</p>
    </footer>


</body>
</html>
