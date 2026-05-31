<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'administrateur') {
    header("Location: connexion.php");
    exit;
}

$donnees = json_decode(file_get_contents("utilisateurs.json"), true);
$utilisateurs = $donnees["utilisateurs"] ?? [];

if (!isset($_GET["id"])) die("Aucun utilisateur sélectionné.");

$id = intval($_GET["id"]);

if (!isset($utilisateurs[$id])) die("Utilisateur introuvable.");

$user    = $utilisateurs[$id];
$bloque  = $user["bloque"]  ?? "non";
$vip     = $user["vip"]     ?? "non";
$premium = $user["premium"] ?? "non";
$remise  = $user["remise"]  ?? 0;
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
        <tr><th colspan="2">Informations</th></tr>
        <tr><th>Nom</th>      <td><?= htmlspecialchars($user["nom"]       ?? "") ?></td></tr>
        <tr><th>Prénom</th>   <td><?= htmlspecialchars($user["prenom"]    ?? "") ?></td></tr>
        <tr><th>Email</th>    <td><?= htmlspecialchars($user["email"]     ?? "") ?></td></tr>
        <tr><th>Téléphone</th><td><?= htmlspecialchars($user["telephone"] ?? "") ?></td></tr>
        <tr><th>Adresse</th>  <td><?= htmlspecialchars($user["adresse"]   ?? "") ?></td></tr>
        <tr><th>Rôle</th>     <td><?= htmlspecialchars($user["role"]      ?? "client") ?></td></tr>
        <tr>
            <th>Compte</th>
            <td id="etat-bloque"><?= $bloque === "oui" ? "Bloqué" : "Actif" ?></td>
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

        <button type="button" onclick="actionAdministrateur(<?= $id ?>, 'bloquer')">
            <?= $bloque === "oui" ? "Débloquer le compte" : "Bloquer le compte" ?>
        </button>

        <button type="button" onclick="actionAdministrateur(<?= $id ?>, 'vip')">
            <?= $vip === "oui" ? "Retirer VIP" : "Passer en VIP" ?>
        </button>

        <button type="button" onclick="actionAdministrateur(<?= $id ?>, 'premium')">
            <?= $premium === "oui" ? "Retirer Premium" : "Passer en Premium" ?>
        </button>

        <button type="button" onclick="actionAdministrateur(<?= $id ?>, 'remise')">
            <?= $remise == 10 ? "Retirer la remise" : "Remise 10%" ?>
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

<script src="admin.js"></script>
</body>
</html>
