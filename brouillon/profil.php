<?php
session_start();

//  Si non connecté on redirige
if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit;
}

$user = $_SESSION['user']; // on simplifie la variable que on va beaucoup utilisé

//  Lecture des commandes depuis le json
$commandes = [];
if (file_exists("commandes.json")) {
    $data = json_decode(file_get_contents("commandes.json"), true);
    foreach ($data["commandes"] ?? [] as $c) {
        if ($c['email'] === $user['email']) {
            $commandes[] = $c;
        }
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>EVEIL - Mon Profil</title>
    <link href="https://fonts.googleapis.com/css2?family=Parisienne&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>


    <!-- NAV -->
    <nav>
        <div class="conteneur-nav">
            <div class="logo-nav">
                <div class="texte-logo-nav">✦ÉVEIL✦</div>
                <div class="paris-logo-nav">PARIS</div>
            </div>
            <ul class="liens-nav">
                <li><a href="index.php">ACCUEIL</a></li>
                <li><a href="profil.php" class="bouton-profil">PROFIL</a></li>
            </ul>
        </div>
    </nav>


    <div class="att">
        <h1>MON PROFIL</h1>


        <?php if (!empty($_SESSION['message'])): ?>
            <p style="margin-bottom:1rem; font-family:'Montserrat',sans-serif; color:green;">
                <?= htmlspecialchars($_SESSION['message']) ?>
            </p>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>


        <!-- Informations personnelles -->
<table id="tableau-profil">
    <tr><th colspan="2">MES INFORMATIONS PERSONNELLES</th></tr>
    <tr>
        <th>Nom</th>
        <td id="inom"><?= htmlspecialchars($user['nom']) ?></td>
    </tr>
    <tr>
        <th>Prénom</th>
        <td id="iprenom"><?= htmlspecialchars($user['prenom']) ?></td>
    </tr>
    <tr>
        <th>Email</th>
        <td><?= htmlspecialchars($user['email']) ?></td>
    </tr>
    <tr>
        <th>Téléphone</th>
        <td id="itelephone"><?= htmlspecialchars($user['telephone'] ?? '') ?></td>
    </tr>
    <tr>
        <th>Adresse</th>
        <td id="iadresse"><?= htmlspecialchars($user['adresse'] ?? '') ?></td>
    </tr>
    <tr>
        <th>Membre depuis</th>
        <td><?= htmlspecialchars($user['date_inscription'] ?? '—') ?></td>
    </tr>
</table>

<div style="margin-top:1rem; font-family:'Montserrat',sans-serif;">
    <button id="bouton-modifier" type="button" onclick="fairelamodification()">✏️ Modifier</button> <!-- permet au javascript de prendre l’information et le clique appelle la fonction -–>
    <button id="bouton-valider" type="button" style="display:none;" onclick="envoyerlamodifications()">Valider</button>
    <button id="bouton-annuler" type="button" style="display:none;" onclick="annulerlamodification()">Annuler</button>
</div>


<p id="message-retour" style="font-family:'Montserrat',sans-serif; margin-top:0.5rem;"></p>
        <!--  Historique des commandes  -->
        <table>
            <tr><th colspan="5">MES COMMANDES</th></tr>
            <tr>
                <th>DATE</th>
                <th>COMMANDE N°</th>
                <th>STATUT</th>
                <th>PRIX TOTAL</th>
                <th>ACTION</th>
            </tr>

            <?php if (empty($commandes)): ?>
                <tr><td colspan="5">Vous n'avez pas encore de commandes.</td></tr>
            <?php else: ?>
                <?php foreach ($commandes as $commande): ?>
                    <tr>
                        <td><?= htmlspecialchars($commande['date']) ?></td>
                        <td><?= htmlspecialchars($commande['id']) ?></td>
                        <td><?= htmlspecialchars($commande['statut']) ?></td>
                        <td><?= htmlspecialchars($commande['prix_total']) ?>€</td>
                        <td>
                        <?php
                        $commande_notee = false;


                        if (file_exists("note.json")) {
                            $contenu_notes = json_decode(file_get_contents("note.json"), true);
                                if (isset($contenu_notes["notes"])) {
                                    foreach ($contenu_notes["notes"] as $note) {
                                        $commande_notee = false;
                                    if (file_exists("note.json")) {
                                        $contenu_notes = json_decode(file_get_contents("note.json"), true);
                                        if (isset($contenu_notes["notes"])) {
                                            foreach ($contenu_notes["notes"] as $note) {
                                                if (isset($note["commande_id"]) && isset($note["client_email"]) && $note["commande_id"] == $commande["id"] && $note["client_email"] == $user["email"]) {
                                                    $commande_notee = true;
                                                }
                                            }
                                        }
                                    }
                                    }
                                }      
                        }
                        if (isset($commande["statut"]) && $commande["statut"] == "Livrée" && !$commande_notee) { ?> 
                            <a href="notation.php?commande=<?php echo $commande['id']; ?>&client=<?php echo $user['email']; ?>"> Noter la commande </a>
                        <?php
                        } 
                        else{
                        ?>
                            <a href="commande.php?id=<?php echo $commande['id']; ?>"> Voir ma commande </a>
                        <?php
                        } // si commande statut est livrée noter la commande sinon voir la commande comme ça la notation se fait une fois
                        ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>


        <!--  Points de fidélité  -->
        <table>
            <tr><th colspan="2">MON COMPTE DE FIDÉLITÉ</th></tr>
            <tr><th>Mes points</th><td><?= $user['points'] ?? 0 ?></td></tr>
        </table>

        <!-- Lien pour retourner commander -->
        <p style="margin-top:2rem; font-family:'Montserrat',sans-serif;">
            <a href="produits.php" style="color:#1a1a1a; font-weight:600;">← Retour à la carte</a>
        </p>
    </div>


    <!-- FOOTER -->
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
const anciens = {}; // anciennes informations gardés si on veut annuler l’action

function fairelamodification() { // pour pouvoir modifier
    const champs = ["nom", "prenom", "telephone", "adresse"]; // on met les infos que on a besoin dans un tableau
    champs.forEach(function(champ) { // vient prendre chaque infos du tableau 
        const cellule = document.getElementById("i" + champ); // prend le html avce l'identifiant
        anciens[champ] = cellule.textContent; // on met les infos du texte html ( anciennes infos ) dans l'objet
        cellule.innerHTML = '<input type="text" id="input-' + champ + '" value="' + cellule.textContent + '">'; // modifie le contenu du html et met la valeur déjà présente dans le champ 
    }
    );
    document.getElementById("bouton-modifier").style.display = "none"; // on cache le bouton modifier
    document.getElementById("bouton-valider").style.display = "inline-block"; // on affiche le bouton valider 
    document.getElementById("bouton-annuler").style.display = "inline-block"; // pareil pour annuler
    document.getElementById("message-retour").textContent = ""; // enlève le message d'avant donc si mise à jour 
}


function annulerlamodification() { // pour annuler la modification
    const champs = ["nom", "prenom", "telephone", "adresse"];
    
    champs.forEach(function(champ) {
        document.getElementById("i" + champ).textContent = anciens[champ]; // on remet juste les anciennes infos vu que on annule
    }
    );
    document.getElementById("bouton-modifier").style.display = "inline-block"; // on met le bouton modifier
    document.getElementById("bouton-valider").style.display = "none";
    document.getElementById("bouton-annuler").style.display = "none";
    document.getElementById("message-retour").textContent = "";
}

async function envoyerModifications() { // pour envoyer les modifications fait et attend la réponse
    const donnees = { 
        nom: document.getElementById("input-nom").value.trim(), 
        prenom: document.getElementById("input-prenom").value.trim(), 
        telephone: document.getElementById("input-telephone").value.trim(),
        adresse: document.getElementById("input-adresse").value.trim()
    }; // cherche l'info avec un identifiant par exmeple inpu-telephone, récupère la valeur écrite et enlève les espaces
    const message = document.getElementById("message-retour");
    if (donnees.nom === "" || donnees.prenom === "" || donnees.telephone === "" || donnees.adresse === "") {
        message.textContent = "Tous les champs doivent être remplis."; // si un endroit est vide affiche un message
        message.style.color = "red";
        return;
    }
    if (!/^[0-9]{10}$/.test(donnees.telephone)) { // si différent de 10 chiffres affiche message d'erreur
        message.textContent = "Le téléphone doit contenir exactement 10 chiffres.";
        message.style.color = "red";
        return; 
    }
    try {
        const reponse = await fetch("profil_modification.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json" // montre que on envoie des infos en json
            },
            body: JSON.stringify(donnees) // transforme le javascript en json
        }
        );
        const resultat = await reponse.json(); // javascript prend la réponse et met en objet
        if (resultat.succes) { // si réponse bonne alors on fait les modifications
            const champs = ["nom", "prenom", "telephone", "adresse"];
            champs.forEach(function(champ) {
                document.getElementById("i" + champ).textContent = donnees[champ]; // met le nouveau texte tapé 
            }
            );
            document.getElementById("bouton-modifier").style.display = "inline-block"; // on remet si on veut modifier de nouveau
            document.getElementById("bouton-valider").style.display = "none";
            document.getElementById("bouton-annuler").style.display = "none";

            message.textContent = resultat.message;
            message.style.color = "green";
        } 
        else{ // si réponse pas bonne alors message d'erreur
            message.textContent = resultat.message;
            message.style.color = "red";
        }
    } 
    catch (e) { // si fetch arrête on met un autre message
        message.textContent = "Erreur avec fetch.";
        message.style.color = "red";
    }
}
</script>

</body>
</html>
