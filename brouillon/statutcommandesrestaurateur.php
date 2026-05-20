<?php
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'restaurateur') {
    echo json_encode(["succes" => false]);
    exit;
}

$donneesRecues = json_decode(file_get_contents('php://input'), true);

if (empty($donneesRecues['id']) || empty($donneesRecues['action'])) {
    echo json_encode(["succes" => false]);
    exit;
}

$idCmd = $donneesRecues['id'];
$action = $donneesRecues['action'];
$livreur = $donneesRecues['livreur'] ?? null;

if (!file_exists("commandes.json")) {
    echo json_encode(["succes" => false]);
    exit;
}

$donneesBase = json_decode(file_get_contents("commandes.json"), true);
$commandeTrouvee = false;

foreach ($donneesBase['commandes'] as &$commande) {
    if ($commande['id'] == $idCmd) {
        $commandeTrouvee = true;

        if ($action === "preparer" && $commande['statut'] === "payée") {
            $commande['statut'] = "en préparation";
        } elseif ($action === "prete" && $commande['statut'] === "en préparation" && !empty($livreur)) {
            $commande['statut'] = "en attente";
            $commande['livreur_assigne'] = $livreur;
        } else {
            echo json_encode(["succes" => false]);
            exit;
        }
        break;
    }
}

if ($commandeTrouvee) {
    file_put_contents("commandes.json", json_encode($donneesBase, JSON_PRETTY_PRINT));
    echo json_encode(["succes" => true]);
} else {
    echo json_encode(["succes" => false]);
}
exit;
?>
