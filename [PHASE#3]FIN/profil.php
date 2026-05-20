<?php
session_start();

// Si non connecté on redirige
if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit;
}

$user = $_SESSION['user']; // on simplifie la variable que on va beaucoup utiliser

// Lecture des commandes depuis le json
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


    <!-- INFORMATIONS PERSONNELLES -->

    <table id="tableau-profil">

        <tr>
            <th colspan="2">MES INFORMATIONS PERSONNELLES</th>
        </tr>

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


    <!-- BOUTONS MODIFICATION -->

    <div style="margin-top:1rem; font-family:'Montserrat',sans-serif;">

        <button id="bouton-modifier" type="button" onclick="fairelamodification()">
            ✏️ Modifier
        </button>

        <button id="bouton-valider" type="button" style="display:none;" onclick="envoyerModifications()">
            Valider
        </button>

        <button id="bouton-annuler" type="button" style="display:none;" onclick="annulerlamodification()">
            Annuler
        </button>

    </div>

    <p id="message-retour" style="font-family:'Montserrat',sans-serif; margin-top:0.5rem;"></p>


    <!-- COMMANDES -->

    <table>

        <tr>
            <th colspan="5">MES COMMANDES</th>
        </tr>

        <tr>
            <th>DATE</th>
            <th>COMMANDE N°</th>
            <th>STATUT</th>
            <th>PRIX TOTAL</th>
            <th>ACTION</th>
        </tr>

        <?php if (empty($commandes)): ?>

            <tr>
                <td colspan="5">Vous n'avez pas encore de commandes.</td>
            </tr>

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

                                if (
                                    isset($note["commande_id"]) &&
                                    isset($note["client_email"]) &&
                                    $note["commande_id"] == $commande["id"] &&
                                    $note["client_email"] == $user["email"]
                                ) {

                                    $commande_notee = true;
                                }
                            }
                        }
                    }

                    // si commande payée alors modification possible
                    if (
                        isset($commande["statut"]) &&
                        ($commande["statut"] === "Payée" || $commande["statut"] === "Payé")
                    ) {
                    ?>

                        <a href="modifier_commande.php?id=<?= htmlspecialchars($commande['id']); ?>">
                            Modifier la commande
                        </a>

                    <?php

                    }

                    // si commande livrée et pas encore notée
                    elseif (
                        isset($commande["statut"]) &&
                        $commande["statut"] === "Livrée" &&
                        !$commande_notee
                    ) {
                    ?>

                        <a href="notation.php?commande=<?= htmlspecialchars($commande['id']); ?>&client=<?= htmlspecialchars($user['email']); ?>">
                            Noter la commande
                        </a>

                    <?php

                    }

                    // sinon voir commande
                    else {

                    ?>

                        <a href="commande.php?id=<?= htmlspecialchars($commande['id']); ?>">
                            Voir ma commande
                        </a>

                    <?php
                    }
                    ?>

                    </td>

                </tr>

            <?php endforeach; ?>

        <?php endif; ?>

    </table>


    <!-- POINTS -->

    <table>

        <tr>
            <th colspan="2">MON COMPTE DE FIDÉLITÉ</th>
        </tr>

        <tr>
            <th>Mes points</th>
            <td><?= $user['points'] ?? 0 ?></td>
        </tr>

    </table>


    <!-- RETOUR -->

    <p style="margin-top:2rem; font-family:'Montserrat',sans-serif;">

        <a href="produits.php" style="color:#1a1a1a; font-weight:600;">
            ← Retour à la carte
        </a>

    </p>

</div>


<footer>

    <div class="logo-pied-page">

        <div class="texte-logo-pied-page">✧ÉVEIL✧</div>

        <div class="paris-logo-pied-page">PARIS</div>

        <div class="slogan-logo-pied-page">
            Éveillez vos papilles gustatives.
        </div>

    </div>

    <div class="infos-pied-page">

        <div class="section-pied-page">
            <h3>ADRESSE</h3>

            <p>
                123 Avenue des Champs-Élysées<br>
                75008 Paris, France
            </p>
        </div>

        <div class="section-pied-page">

            <h3>HORAIRES</h3>

            <p>
                Mardi - Samedi<br>
                12h00 - 14h30 | 19h00 - 22h30<br>
                Fermé Dimanche & Lundi
            </p>

        </div>

        <div class="section-pied-page">

            <h3>CONTACT</h3>

            <p>
                Tél: +33 1 23 45 67 89<br>
                Email: contact@eveilparis.fr
            </p>

        </div>

    </div>

    <p style="margin-top:2rem;color:#C9B896;">
        © 2026 EVEIL Paris. Tous droits réservés.
    </p>

</footer>


<script>

const anciens = {};

function fairelamodification() {

    const champs = ["nom", "prenom", "telephone", "adresse"];

    champs.forEach(function(champ) {

        const cellule = document.getElementById("i" + champ);

        anciens[champ] = cellule.textContent;

        cellule.innerHTML =
        '<input type="text" id="input-' + champ + '" value="' + cellule.textContent + '">';

    });

    document.getElementById("bouton-modifier").style.display = "none";

    document.getElementById("bouton-valider").style.display = "inline-block";

    document.getElementById("bouton-annuler").style.display = "inline-block";

    document.getElementById("message-retour").textContent = "";
}


function annulerlamodification() {

    const champs = ["nom", "prenom", "telephone", "adresse"];

    champs.forEach(function(champ) {

        document.getElementById("i" + champ).textContent = anciens[champ];

    });

    document.getElementById("bouton-modifier").style.display = "inline-block";

    document.getElementById("bouton-valider").style.display = "none";

    document.getElementById("bouton-annuler").style.display = "none";

    document.getElementById("message-retour").textContent = "";
}


async function envoyerModifications() {

    const donnees = {

        nom: document.getElementById("input-nom").value.trim(),

        prenom: document.getElementById("input-prenom").value.trim(),

        telephone: document.getElementById("input-telephone").value.trim(),

        adresse: document.getElementById("input-adresse").value.trim()
    };

    const message = document.getElementById("message-retour");

    if (
        donnees.nom === "" ||
        donnees.prenom === "" ||
        donnees.telephone === "" ||
        donnees.adresse === ""
    ) {

        message.textContent = "Tous les champs doivent être remplis.";

        message.style.color = "red";

        return;
    }

    if (!/^[0-9]{10}$/.test(donnees.telephone)) {

        message.textContent = "Le téléphone doit contenir exactement 10 chiffres.";

        message.style.color = "red";

        return;
    }

    try {

        const reponse = await fetch("profil_modification.php", {

            method: "POST",

            headers: {
                "Content-Type": "application/json"
            },

            body: JSON.stringify(donnees)

        });

        const resultat = await reponse.json();

        if (resultat.succes) {

            const champs = ["nom", "prenom", "telephone", "adresse"];

            champs.forEach(function(champ) {

                document.getElementById("i" + champ).textContent = donnees[champ];

            });

            document.getElementById("bouton-modifier").style.display = "inline-block";

            document.getElementById("bouton-valider").style.display = "none";

            document.getElementById("bouton-annuler").style.display = "none";

            message.textContent = resultat.message;

            message.style.color = "green";

        }

        else {

            message.textContent = resultat.message;

            message.style.color = "red";
        }

    }

    catch (e) {

        message.textContent = "Erreur avec fetch.";

        message.style.color = "red";
    }
}

</script>

</body>
</html>
