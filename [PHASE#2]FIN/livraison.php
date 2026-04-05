<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'livreur') {
    header("Location: connexion.php");
    exit;
}

$donnees    = json_decode(file_get_contents("commandes.json"), true);
$commandes  = $donnees["commandes"] ?? [];

$donnees2     = json_decode(file_get_contents("utilisateurs.json"), true);
$utilisateurs = $donnees2["utilisateurs"] ?? [];

// Créer un tableau associatif email => utilisateur pour retrouver facilement
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
                <li><a href="deconnexion.php" class="bouton-inscription">DÉCONNEXION</a></li>
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
                    if (isset($commande["statut"]) && $commande["statut"] != "Payée") {
                        continue;
                    }

                    $trouve = true;

                    // Retrouver le client via son email
                    $email  = $commande['email'] ?? '';
                    $client = $utilisateurs_par_email[$email] ?? [];

                    $adresse         = $client['adresse']         ?? '';
                    $etage           = $client['etage']           ?? '';
                    $code_interphone = $client['code_interphone'] ?? '';
                    $telephone       = $client['telephone']       ?? '';
                    $commentaires    = $commande['commentaires']  ?? '';
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($commande['id']) ?></td>
                        <td><?= htmlspecialchars($adresse) ?></td>
                        <td><?= htmlspecialchars($etage) ?></td>
                        <td><?= htmlspecialchars($code_interphone) ?></td>
                        <td><?= htmlspecialchars($telephone) ?></td>
                        <td><?= htmlspecialchars($commentaires) ?></td>
                        <td>
                            <form action="traitement-de-livraison.php" method="POST">
                                <input type="hidden" name="id" value="<?= htmlspecialchars($commande['id']) ?>">
                                <input type="hidden" name="action" value="Livrée">
                                <button type="submit">Livrée</button>
                            </form>
                            <form action="traitement-de-livraison.php" method="POST">
                                <input type="hidden" name="id" value="<?= htmlspecialchars($commande['id']) ?>">
                                <input type="hidden" name="action" value="Abandonnée">
                                <button type="submit">Abandonnée</button>
                            </form>
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

</body>
</html>








