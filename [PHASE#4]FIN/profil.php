<?php
session_start();
require("verifier_blocage.php");
if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit;
}

$user = $_SESSION['user'];

$commandes = [];

if (file_exists("commandes.json")) {
    $donneesCommandes = json_decode(file_get_contents("commandes.json"), true);

    foreach ($donneesCommandes["commandes"] ?? [] as $commande) {
        if ($commande["email"] === $user["email"]) {
            $commandes[] = $commande;
        }
    }
}

$pointsFidelite = 0;

foreach ($commandes as $commande) {
    if (($commande["statut"] ?? "") === "Livrée") {
        $pointsFidelite = $pointsFidelite + 10;
    }
}

$vip = $user["vip"] ?? "non";
$premium = $user["premium"] ?? "non";
$remise = $user["remise"] ?? 0;

function commande_deja_notee($idCommande, $emailClient) {
    if (!file_exists("note.json")) {
        return false;
    }

    $donneesNotes = json_decode(file_get_contents("note.json"), true);

    foreach ($donneesNotes["notes"] ?? [] as $note) {
        if (
            isset($note["commande_id"]) &&
            isset($note["client_email"]) &&
            $note["commande_id"] == $idCommande &&
            $note["client_email"] === $emailClient
        ) {
            return true;
        }
    }

    return false;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>EVEIL - Mon profil</title>
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
            <li><a href="profil.php">PROFIL</a></li>
            <li><a href="deconnexion.php" class="bouton-inscription">DÉCONNEXION</a></li>

            <li><button id="btnchgmode" type="button">🌙</button></li>
        </ul>
    </div>
</nav>

<div class="att">

    <h1>MON PROFIL</h1>

    <p id="message-profil" style="font-family:'Montserrat',sans-serif; margin-bottom:1rem;"></p>

    <h2>Mes informations</h2>

    <form id="formulaire-profil">

        <table>
            <tr>
                <th>Nom</th>
                <td>
                    <input type="text" id="nom" maxlength="15" value="<?= htmlspecialchars($user["nom"] ?? "") ?>" disabled>
                    <p id="compteur-nom"></p>
                </td>
            </tr>

            <tr>
                <th>Prénom</th>
                <td>
                    <input type="text" id="prenom" maxlength="15" value="<?= htmlspecialchars($user["prenom"] ?? "") ?>" disabled>
                    <p id="compteur-prenom"></p>
                </td>
            </tr>

            <tr>
                <th>Email</th>
                <td>
                    <input type="email" id="email" value="<?= htmlspecialchars($user["email"] ?? "") ?>" readonly disabled>
                </td>
            </tr>

            <tr>
                <th>Téléphone</th>
                <td>
                    <input type="text" id="telephone" maxlength="10" value="<?= htmlspecialchars($user["telephone"] ?? "") ?>" disabled>
                </td>
            </tr>

            <tr>
                <th>Adresse</th>
                <td>
                    <input type="text" id="adresse" value="<?= htmlspecialchars($user["adresse"] ?? "") ?>" disabled>
                </td>
            </tr>

            <tr>
                <th>Étage</th>
                <td>
                    <input type="text" id="etage" value="<?= htmlspecialchars($user["etage"] ?? "") ?>" disabled>
                </td>
            </tr>

            <tr>
                <th>Code interphone</th>
                <td>
                    <input type="text" id="code_interphone" value="<?= htmlspecialchars($user["code_interphone"] ?? "") ?>" disabled>
                </td>
            </tr>
        </table>

        <button type="button" id="bouton-modifier" class="bouton-inscription">
            Modifier
        </button>

        <button type="submit" id="bouton-enregistrer" class="bouton-inscription" style="display:none;">
            Enregistrer
        </button>

        <button type="button" id="bouton-annuler" class="bouton-inscription" style="display:none;">
            Annuler
        </button>

    </form>

    <h2>Compte fidélité</h2>

    <table>
        <tr>
            <th>Points fidélité</th>
            <td><?= $pointsFidelite ?> points</td>
        </tr>

        <tr>
            <th>Statut VIP</th>
            <td><?= htmlspecialchars($vip) ?></td>
        </tr>

        <tr>
            <th>Statut Premium</th>
            <td><?= htmlspecialchars($premium) ?></td>
        </tr>

        <tr>
            <th>Remise</th>
            <td><?= htmlspecialchars($remise) ?>%</td>
        </tr>
    </table>

    <h2>Mes anciennes commandes</h2>

    <table>
        <tr>
            <th>N°</th>
            <th>Date</th>
            <th>Statut</th>
            <th>Paiement</th>
            <th>Total</th>
            <th>Action</th>
        </tr>

        <?php if (empty($commandes)) { ?>

            <tr>
                <td colspan="6">Vous n'avez pas encore de commande.</td>
            </tr>

        <?php } else { ?>

            <?php foreach ($commandes as $commande) { ?>

                <tr>
                    <td><?= htmlspecialchars($commande["id"]) ?></td>
                    <td><?= htmlspecialchars($commande["date"] ?? "") ?></td>
                    <td><?= htmlspecialchars($commande["statut"] ?? "") ?></td>
                    <td><?= htmlspecialchars($commande["paiement"] ?? "") ?></td>
                    <td><?= htmlspecialchars($commande["prix_total"] ?? 0) ?>€</td>

                    <td>
                        <?php if (($commande["statut"] ?? "") === "Livrée") { ?>

                            <?php if (commande_deja_notee($commande["id"], $user["email"])) { ?>

                                Commande déjà notée

                            <?php } else { ?>

                                <a href="notation.php?commande=<?= htmlspecialchars($commande["id"]) ?>&client=<?= htmlspecialchars($user["email"]) ?>">
                                    Noter la commande
                                </a>

                            <?php } ?>

                        <?php } else { ?>

                            —

                        <?php } ?>
                    </td>
                </tr>

            <?php } ?>

        <?php } ?>
    </table>

</div>

<footer>
    <div class="logo-pied-page">
        <div class="texte-logo-pied-page">✧ÉVEIL✧</div>
        <div class="paris-logo-pied-page">PARIS</div>
        <div class="slogan-logo-pied-page">Éveillez vos papilles gustatives.</div>
    </div>
</footer>

<script src="profil.js"></script>
<script src="mode.js"></script>

</body>
</html>
