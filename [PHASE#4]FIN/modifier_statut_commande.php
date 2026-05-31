<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'restaurateur') {
    echo json_encode(["succes" => false, "message" => "Accès refusé."]);
    exit;
}

$requeteBrute  = file_get_contents('php://input');
$donneesRecues = json_decode($requeteBrute, true);

$idCommande   = $donneesRecues['id']      ?? null;
$action       = $donneesRecues['action']  ?? null;
$emailLivreur = $donneesRecues['livreur'] ?? null;

if (!$idCommande || !$action) {
    echo json_encode(["succes" => false, "message" => "Données incomplètes."]);
    exit;
}

$fichierCommandes = "commandes.json";
if (!file_exists($fichierCommandes)) {
    echo json_encode(["succes" => false, "message" => "Fichier de commandes introuvable."]);
    exit;
}

$donneesContenu = json_decode(file_get_contents($fichierCommandes), true);
$commandes      = &$donneesContenu['commandes'];

$commandeTrouvee = false;

foreach ($commandes as &$commande) {
    if ((int)$commande['id'] === (int)$idCommande) {
        $commandeTrouvee = true;

        if ($action === 'preparer' && $commande['statut'] === 'Payée') {
            $commande['statut'] = 'En préparation';

        } elseif ($action === 'prete' && $commande['statut'] === 'En préparation') {
            $commande['statut']          = 'Prête';
            $commande['livreur_assigne'] = $emailLivreur;

        } else {
            echo json_encode(["succes" => false, "message" => "Transition de statut non autorisée."]);
            exit;
        }
        break;
    }
}
unset($commande);

if ($commandeTrouvee) {
    file_put_contents($fichierCommandes, json_encode($donneesContenu, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    echo json_encode(["succes" => true, "message" => "Le statut a bien été mis à jour."]);
} else {
    echo json_encode(["succes" => false, "message" => "Commande introuvable."]);
}
exit;
