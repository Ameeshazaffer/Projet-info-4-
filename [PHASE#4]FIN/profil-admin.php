<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'administrateur') {
    header("Location: connexion.php");
    exit;
}

$donnees = json_decode(file_get_contents("utilisateurs.json"), true);
$utilisateurs = $donnees["utilisateurs"] ?? [];

if (!isset($_GET["id"])) {
    die("Aucun utilisateur sélectionné.");
}

$id = intval($_GET["id"]);

if (!isset($utilisateurs[$id])) {
    die("Utilisateur introuvable.");
}

$user = $utilisateurs[$id];

$nom = $user["nom"] ?? "";
$prenom = $user["prenom"] ?? "";
$email = $user["email"] ?? "";
$telephone = $user["telephone"] ?? "";
$adresse = $user["adresse"] ?? "";
$role = $user["role"] ?? "client";

$bloque = $user["bloque"] ?? "non";
$vip = $user["vip"] ?? "non";
$premium = $user["premium"] ?? "non";
$remise = $user["remise"] ?? 0;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>EVEIL - Profil administrateur</title>
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
            <li><a href="administrateur.php">ESPACE ADMIN</a></li>
            <li><a href="deconnexion.php" class="bouton-inscription">DÉCONNEXION</a></li>
        </ul>
    </div>
</nav>

<div class="att">
    <h1>PROFIL DE L'UTILISATEUR</h1>

    <table class="liv">
        <tr>
            <th colspan="2">Informations</th>
        </tr>

        <tr>
            <th>Nom</th>
            <td><?= htmlspecialchars($nom) ?></td>
        </tr>

        <tr>
            <th>Prénom</th>
            <td><?= htmlspecialchars($prenom) ?></td>
        </tr>

        <tr>
            <th>Email</th>
            <td><?= htmlspecialchars($email) ?></td>
        </tr>

        <tr>
            <th>Téléphone</th>
            <td><?= htmlspecialchars($telephone) ?></td>
        </tr>

        <tr>
            <th>Adresse</th>
            <td><?= htmlspecialchars($adresse) ?></td>
        </tr>

        <tr>
            <th>Rôle</th>
            <td><?= htmlspecialchars($role) ?></td>
        </tr>

        <tr>
            <th>Compte</th>
            <td id="etat-bloque">
                <?= $bloque === "oui" ? "Bloqué" : "Actif" ?>
            </td>
        </tr>

        <tr>
            <th>Avantages</th>
            <td id="etat-avantages">
                VIP : <?= htmlspecialchars($vip) ?><br>
                Premium : <?= htmlspecialchars($premium) ?><br>
                Remise : <?= htmlspecialchars($remise) ?>%
            </td>
        </tr>
    </table>

    <div class="actions-admin">
    <h2>Actions administrateur</h2>

    <?php if ($bloque !== "oui") { ?>

        <button type="button"
            onclick="actionAdministrateur(<?= $id ?>, 'bloquer')">
            Bloquer le compte
        </button>

    <?php } else { ?>

        <p style="color:red; font-weight:bold;">
            Ce compte est bloqué.
        </p>

    <?php } ?>

    <button type="button"
        onclick="actionAdministrateur(<?= $id ?>, 'vip')">
        Modifier VIP
    </button>

    <button type="button"
        onclick="actionAdministrateur(<?= $id ?>, 'premium')">
        Modifier Premium
    </button>

    <button type="button"
        onclick="actionAdministrateur(<?= $id ?>, 'remise')">
        Remise 10%
    </button>
</div>

    <p id="message-admin" style="font-family:'Montserrat',sans-serif; margin-top:1rem;"></p>

    <br>
    <a href="administrateur.php">← Retour à la liste</a>
</div>

<footer>
    <div class="logo-pied-page">
        <div class="texte-logo-pied-page">✧ÉVEIL✧</div>
        <div class="paris-logo-pied-page">PARIS</div>
        <div class="slogan-logo-pied-page">Éveillez vos papilles gustatives.</div>
    </div>
</footer>

<script src="admin.js"></script>

</body>
</html>
