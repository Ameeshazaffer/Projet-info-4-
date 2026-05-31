<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'administrateur') {
    header("Location: connexion.php");
    exit;
}

$donnees = json_decode(file_get_contents("utilisateurs.json"), true);
$utilisateurs = $donnees["utilisateurs"] ?? [];
$filtre = $_GET["role"] ?? "tous";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>EVEIL - Espace administrateur</title>
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
            <li><a href="administrateur.php">ESPACE</a></li>
            <li><a href="deconnexion.php" class="bouton-inscription">DÉCONNEXION</a></li>
            <li><button id="btnchgmode" type="button">🌙</button></li>
        </ul>
    </div>
</nav>

<div class="att">
    <h1>ESPACE ADMINISTRATEUR</h1>
    <h2>Gestion des utilisateurs</h2>

    <div class="select-container">
        <form method="GET" action="administrateur.php">
            <select name="role" class="select-box">
                <option value="tous"          <?= $filtre === "tous"          ? "selected" : "" ?>>Tous</option>
                <option value="client"        <?= $filtre === "client"        ? "selected" : "" ?>>Clients</option>
                <option value="administrateur"<?= $filtre === "administrateur"? "selected" : "" ?>>Administrateurs</option>
                <option value="livreur"       <?= $filtre === "livreur"       ? "selected" : "" ?>>Livreurs</option>
                <option value="restaurateur"  <?= $filtre === "restaurateur"  ? "selected" : "" ?>>Restaurateurs</option>
            </select>
            <button type="submit" class="bouton-inscription">Filtrer</button>
        </form>
    </div>

    <div class="liv" style="width:100%; overflow-x:auto;">
        <table style="width:100%; table-layout:fixed;">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Statut</th>
                    <th>Avantages</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $trouve = false;
            foreach ($utilisateurs as $index => $user) {
                $role = $user["role"] ?? "client";
                if ($filtre !== "tous" && $role !== $filtre) continue;
                $trouve = true;
                $bloque  = $user["bloque"]  ?? "non";
                $vip     = $user["vip"]     ?? "non";
                $premium = $user["premium"] ?? "non";
                $remise  = $user["remise"]  ?? 0;
            ?>
                <tr>
                    <td><?= htmlspecialchars($user["nom"]   ?? "") ?></td>
                    <td><?= htmlspecialchars($user["prenom"] ?? "") ?></td>
                    <td style="word-break:break-word;"><?= htmlspecialchars($user["email"] ?? "") ?></td>
                    <td><?= htmlspecialchars($role) ?></td>
                    <td><?= $bloque === "oui" ? "Bloqué" : "Actif" ?></td>
                    <td style="font-size:0.9rem;">
                        VIP : <?= htmlspecialchars($vip) ?><br>
                        Premium : <?= htmlspecialchars($premium) ?><br>
                        Remise : <?= htmlspecialchars($remise) ?>%
                    </td>
                    <td><a href="profil-admin.php?id=<?= $index ?>">Voir profil</a></td>
                </tr>
            <?php } ?>
            <?php if (!$trouve): ?>
                <tr><td colspan="7">Aucun utilisateur trouvé pour ce rôle.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
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

<script src="mode.js"></script>
</body>
</html>
