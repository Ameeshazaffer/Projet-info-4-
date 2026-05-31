<?php
session_start();
require("verifier_blocage.php");
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'livreur') {
    header("Location: connexion.php");
    exit;
}

$donnees = json_decode(file_get_contents("commandes.json"), true);
$commandes = array();
if (isset($donnees["commandes"])) {
    $commandes = $donnees["commandes"];
}

$donnees2 = json_decode(file_get_contents("utilisateurs.json"), true);
$utilisateurs = array();
if (isset($donnees2["utilisateurs"])) {
    $utilisateurs = $donnees2["utilisateurs"];
}

// on cré un tableau associatif email : utilisateur pour retrouver facilement
$utilisateurs_par_email = [];
foreach ($utilisateurs as $u) {
    $utilisateurs_par_email[$u['email']] = $u;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>EVEIL - Livraison</title>
    <link href="https://fonts.googleapis.com/css2?family=Parisienne&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link id="chgmode" rel="stylesheet" href="styles.css">
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
                <li><a href="deconnexion.php" class="bouton-inscription">DÉCONNEXION</a></li>

              <li><button id="btnchgmode" type="button">🌙</button></li>
            </ul>
        </div>
    </nav>

    <div class="liv">
        <h1>COMMANDES À LIVRER</h1>
        <table>
            <thead>
                <tr>
                    <th>N° commande</th>
                    <th>Adresse</th>
                    <th>Étage</th>
                    <th>Code interphone</th>
                    <th>Téléphone</th>
                    <th>Commentaires</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $trouve = false;
                foreach ($commandes as $commande) {
                    if (!isset($commande["statut"]) || $commande["statut"] != "Payée") {
                        continue;
                    }
                    $trouve = true;
                    // Retrouver le client avec son email
                    if (isset($commande['email'])) {
                        $email = $commande['email'];
                    } 
                    else {
                        $email = "";
                    }
                    if (isset($utilisateurs_par_email[$email])) {
                        $client = $utilisateurs_par_email[$email];
                    } 
                    else {
                        $client = array();
                    }
                    if (isset($client['adresse'])) {
                        $adresse = $client['adresse'];
                    } 
                    else {
                        $adresse = "";
                    }
                    if (isset($client['etage'])) {
                        $etage = $client['etage'];
                    } 
                    else {
                        $etage = "";
                    }
                    if (isset($client['code_interphone'])) {
                        $code_interphone = $client['code_interphone'];
                    } 
                    else {
                        $code_interphone = "";
                    }
                    if (isset($client['telephone'])) {
                        $telephone = $client['telephone'];
                    } 
                    else {
                        $telephone = "";
                    }
                    if (isset($commande['commentaires'])) {
                        $commentaires = $commande['commentaires'];
                    } 
                    else {
                        $commentaires = "";
                    }
                    ?>
                    <tr id="ligne-<?= htmlspecialchars($commande['id']) ?>">
                        <td><?= htmlspecialchars($commande['id']) ?></td>
                        <td><?= htmlspecialchars($adresse) ?></td>
                        <td><?= htmlspecialchars($etage) ?></td>
                        <td><?= htmlspecialchars($code_interphone) ?></td>
                        <td><?= htmlspecialchars($telephone) ?></td>
                        <td><?= htmlspecialchars($commentaires) ?></td>
                        <td>
                        <button type="button" onclick="Livraison('<?= htmlspecialchars($commande['id']) ?>', 'Livrée')"> Livrée </button>
                        <button type="button" onclick="Livraison('<?= htmlspecialchars($commande['id']) ?>', 'Abandonnée')"> Abandonnée </button>
                        </td>
                    </tr>
                    <?php
                }

                if (!$trouve) {
                    echo '<tr><td colspan="7">Il n\'y a pas de commande en attente de livraison.</td></tr>';
                }
                ?>
            </tbody>
        </table>
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

 <script>
        async function Livraison(idCommande, action) { // on prend l'identifiant et l'action donc livré ou abandonnée
    try {
        const reponse = await fetch("traitement-de-livraison.php", {
            method: "POST",
            headers: { "Content-Type": "application/json"}, // données en json
            body: JSON.stringify({ id: idCommande, action: action }) // transformation du java en json
        }
        );
        const resultat = await reponse.json();
        
        if (resultat.succes) { // si on a true qui est repondu alors l'action a marché
            document.getElementById("ligne-" + idCommande).remove(); // donc on enlève la ligne du tableau
        } else {
            alert("Erreur : " + resultat.message); // sinon on met un message d'erreur
        }
        
    } 
    catch (e){
        alert("Erreur avec fetch.");
    }
}
</script>
<script src="mode.js"></script>


</body>
</html>






