<?php
// back/get_fleurs.php — retourne les fleurs disponibles pour le bouquet (HTML direct)
require_once 'db.php';

try {
    $stmt = $pdo->query("SELECT * FROM fleurs_bouquet ORDER BY prix ASC");
    $fleurs = $stmt->fetchAll();

    if (empty($fleurs)) {
        echo '<p class="loading-msg">Aucune fleur disponible.</p>';
        exit;
    }

    foreach ($fleurs as $f):
        $slug  = htmlspecialchars($f['slug']);// htmlspecialchars() protège contre XSS
        $nom   = htmlspecialchars($f['nom']);
        $prix  = number_format($f['prix'], 2);
        $emoji = htmlspecialchars($f['emoji'] ?? '🌸');
?>
<div class="flower-item" id="fi-<?= $slug ?>"
     onclick="toggleFlower('<?= $slug ?>','<?= $nom ?>',<?= $f['prix'] ?>,'<?= $emoji ?>')">
    <div class="fi-emoji"><?= $emoji ?></div>
    <div class="fi-name"><?= $nom ?></div>
    <div class="fi-price"><?= $prix ?> DT/tige</div>
    <div class="qty-control" id="qc-<?= $slug ?>" style="display:none">
        <button class="qty-btn" onclick="event.stopPropagation();changeQty('<?= $slug ?>',-1)">−</button>
       <!-- event.stopPropagation() → empêche le clic de remonter à la tuile parente -->
        <span class="qty-num" id="qn-<?= $slug ?>">1</span>
        <button class="qty-btn" onclick="event.stopPropagation();changeQty('<?= $slug ?>',1)">+</button>
    </div>
</div>
<?php endforeach; ?>

<?php
} catch (PDOException $e) {
    echo '<p class="loading-msg">Erreur base de données.</p>';
}
?>