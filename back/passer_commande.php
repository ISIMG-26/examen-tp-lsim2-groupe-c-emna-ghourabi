<?php
// back/passer_commande.php — enregistre une commande avec ses lignes
header('Content-Type: application/json');
require_once 'db.php';

$nom_client   = trim($_POST['nom_client'] ?? '');
$email_client = trim($_POST['email_client'] ?? '');
$items_json   = $_POST['items'] ?? '[]';
$utilisateur_id = isset($_POST['utilisateur_id']) && is_numeric($_POST['utilisateur_id'])
                  ? (int)$_POST['utilisateur_id'] : null;

if (!$nom_client || !$email_client) {
    echo json_encode(['success' => false, 'error' => 'Nom et email requis.']);
    exit;
}
if (!filter_var($email_client, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'error' => 'Email invalide.']);
    exit;
}

$items = json_decode($items_json, true);
if (!$items || count($items) === 0) {
    echo json_encode(['success' => false, 'error' => 'Panier vide.']);
    exit;
}

// Calcul du total
$total = array_reduce($items, function($carry, $item) {
    return $carry + (float)($item['prix'] ?? 0);
}, 0);
$total += 7; // livraison

try {
    $pdo->beginTransaction();

    // INSERT commande
    $stmt = $pdo->prepare("
        INSERT INTO commandes (utilisateur_id, nom_client, email_client, total)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->execute([$utilisateur_id, $nom_client, $email_client, $total]);
    $commande_id = $pdo->lastInsertId();

    // INSERT lignes
    foreach ($items as $item) {
        if (!empty($item['isBouquet'])) {
            // Ligne bouquet
            $stmt = $pdo->prepare("
                INSERT INTO lignes_commande (commande_id, type_ligne, nom_article, quantite, prix_unitaire)
                VALUES (?, 'bouquet', ?, 1, ?)
            ");
            $stmt->execute([$commande_id, $item['nom'], (float)$item['prix']]);
            $ligne_id = $pdo->lastInsertId();

            // Détails bouquet
            $stmt2 = $pdo->prepare("
                INSERT INTO bouquets_commandes (commande_id, destinataire, couleur_ruban, message_carte)
                VALUES (?, ?, ?, ?)
            ");
            $stmt2->execute([
                $commande_id,
                $item['destinataire'] ?? null,
                $item['ruban'] ?? null,
                $item['message'] ?? null
            ]);
        } else {
            // Ligne produit standard
            $stmt = $pdo->prepare("
                INSERT INTO lignes_commande (commande_id, produit_id, type_ligne, nom_article, quantite, prix_unitaire)
                VALUES (?, ?, 'produit', ?, 1, ?)
            ");
            $stmt->execute([
                $commande_id,
                $item['id'] ?? null,
                $item['nom'],
                (float)$item['prix']
            ]);
        }
    }

    $pdo->commit();
    echo json_encode(['success' => true, 'message' => 'Commande enregistrée ! Merci 🌸', 'commande_id' => $commande_id]);
} catch (PDOException $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
