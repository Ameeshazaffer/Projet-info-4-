<?php
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'restaurateur') {
    echo json_encode(["succes" => false, "message" => "Accès refusé."]);
    exit;
}

$jsonRecu = file_get_contents('php://input');
$donneesRecues = json_decode($jsonRecu, true);

if (empty($donneesRecues['id']) || empty($donneesRecues['action'])) {
    echo json_encode(["succes" => false, "message" => "Données incomplètes."]);
    exit;
}

$idCmd = $donneesRecues['id'];
$action = $donneesRecues['action'];
$livreur = $donneesRecues['livreur'] ?? null;

if (!file_exists("commandes.json")) {
    echo json_encode(["succes" => false, "message" => "Fichier des commandes introuvable."]);
    exit;
}

$donneesBase = json_decode(file_get_contents("commandes.json"), true);
$commandeTrouvee = false;

foreach ($donneesBase['commandes'] as &$commande) {
    if ($commande['id'] == $idCmd) {
        $commandeTrouvee = true;

        if ($action === "preparer") {
            if ($commande['statut'] === "payée") {
                $commande['statut'] = "en préparation";
                $msgSuccess = "La commande n°$idCmd est maintenant en préparation !";
            } else {
                echo json_encode(["succes" => false, "message" => "Le statut actuel ne permet pas la mise en préparation."]);
                exit;
            }
        } elseif ($action === "prete") {
            if ($commande['statut'] === "en préparation") {
                if (empty($livreur)) {
                    echo json_encode(["succes" => false, "message" => "Un livreur doit être spécifié."]);
                    exit;
                }
                $commande['statut'] = "en attente";
                $commande['livreur_assigne'] = $livreur;
                $msgSuccess = "La commande n°$idCmd est prête et assignée au livreur !";
            } else {
                echo json_encode(["succes" => false, "message" => "La commande doit être en préparation pour passer à l'état prête."]);
                exit;
            }
        } else {
            echo json_encode(["succes" => false, "message" => "Action non reconnue."]);
            exit;
        }
        break;
    }
}

if ($commandeTrouvee) {
    file_put_contents("commandes.json", json_encode($donneesBase, JSON_PRETTY_PRINT));
    echo json_encode(["succes" => true, "message" => $msgSuccess]);
} else {
    echo json_encode(["succes" => false, "message" => "Commande introuvable."]);
}
exit;
?>
