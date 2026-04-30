<?php
// back/get_produits.php — retourne les produits filtrés par catégorie (HTML pur)
require_once 'db.php';
// Inclut le fichier de connexion — $pdo devient disponible dans ce script
$categorie = isset($_GET['categorie']) ? trim($_GET['categorie']) : 'all';
// Lit le paramètre ?categorie= de l'URL
// isset() vérifie s'il existe — trim() supprime les espaces avant/après
// Si absent → 'all' par défaut
// ex: get_produits.php?categorie=bag → $categorie = 'bag'
$search    = isset($_GET['q'])         ? trim($_GET['q'])         : '';
// Lit le paramètre ?q= de l'URL (recherche)
// Si absent → chaîne vide ''
// ex: get_produits.php?q=rose → $search = 'rose'
try {
    // Tente d'exécuter les requêtes SQL — si erreur → saute au catch
    if ($search !== '') {
        // Recherche par nom
        $stmt = $pdo->prepare("
            SELECT p.*, c.nom AS cat_nom, c.slug AS cat_slug, c.icone AS cat_icone
            FROM produits p
            JOIN categories c ON p.categorie_id = c.id
            WHERE p.nom LIKE ?
            ORDER BY p.date_ajout DESC
        ");
         // prepare() crée une requête sécurisée avec ? comme placeholder
        $stmt->execute(['%' . $search . '%']); //ex: LIKE '%rose%' → trouve "Rose rouge", "Eau de rose", "Roseau"

    } elseif ($categorie === 'all') {
        $stmt = $pdo->query("
            SELECT p.*, c.nom AS cat_nom, c.slug AS cat_slug, c.icone AS cat_icone
            FROM produits p
            JOIN categories c ON p.categorie_id = c.id
            ORDER BY p.date_ajout DESC
        ");
        // query() pour les requêtes sans paramètres variables (plus rapide que prepare)
    } else {
        $stmt = $pdo->prepare("
            SELECT p.*, c.nom AS cat_nom, c.slug AS cat_slug, c.icone AS cat_icone
            FROM produits p
            JOIN categories c ON p.categorie_id = c.id
            WHERE c.slug = ?
            ORDER BY p.date_ajout DESC
        ");
        $stmt->execute([$categorie]);
    }

    $produits = $stmt->fetchAll();
    // Récupère tous les résultats en tableau associatif (grâce à FETCH_ASSOC dans db.php)
    // ex: $produits[0]['nom'] = "Sac rose"
    if (empty($produits)) {
        echo '<p class="loading-msg">Aucun produit trouvé.</p>';
        exit;
    }

    foreach ($produits as $p):
        $nom   = htmlspecialchars($p['nom']);
        $desc  = htmlspecialchars($p['description'] ?? '');
        $icone = htmlspecialchars($p['cat_icone']   ?? '🛍️');
        $badge = $p['badge']
            ? '<span class="prod-badge">' . htmlspecialchars($p['badge']) . '</span>'
            : '';
        $img   = $p['image']
            ? '<img src="../' . htmlspecialchars($p['image']) . '" alt="' . $nom . '"
                   onerror="this.parentElement.innerHTML=\'<span style=\\\'font-size:3rem\\\'>' . $icone . '</span>\'">'
            : '<span style="font-size:3.5rem">' . $icone . '</span>';
?>
<div class="product-card">
    <div class="prod-img"><?= $img ?><?= $badge ?></div>
    <div class="prod-body">
        <div class="prod-name"><?= $nom ?></div>
        <div class="prod-desc"><?= $desc ?></div>
        <div class="prod-footer">
            <div class="prod-price"><?= number_format($p['prix'], 2) ?> DT</div>
            <button class="add-btn"
                onclick="addToCart(<?= $p['id'] ?>,'<?= $nom ?>',<?= $p['prix'] ?>,'<?= $desc ?>','<?= $icone ?>')">
                + Panier
            </button>
        </div>
    </div>
</div>
<?php endforeach; ?>
<?php

} catch (PDOException $e) {
    echo '<p class="loading-msg">Erreur base de données.</p>';
}
?>