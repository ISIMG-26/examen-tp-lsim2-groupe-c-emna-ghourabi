<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$cart=0; 
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= ($pageTitle ?? 'Gharbouch')?> Gharbouch ✿</title>
  <link rel="stylesheet" href="/gharbouch/css/stylee.css">
</head>
<body date-page="<?= ($activePage ?? 'accueil')?>">

<!-- ===== NAVIGATION ===== -->
<nav>
  <a herf="/gharbouch/index.php" class="logo">gharbouch <span>✿</span></div>
  <ul class="nav-links">
    <li><a href="/gharbouch/index.php"  <?= ($activePage==='home')      ? 'class="active"' : '' ?>>Accueil</a></li>
    <li><a href="/gharbouch/html/catalogue.php"  <?= ($activePage==='catalogue')      ? 'class="active"' : '' ?>>Catalogue</a></li>
    <li><a href="/gharbouch/html/bouquet.php" <?= ($activePage==='bouquet')      ? 'class="active"' : '' ?>>Ton Bouquet</a></li>
    <?php if(isset($_SESSION['user_id'])): ?>
       <li style="color: var(--pink3);font-weight:500;">
            Bonjour <?= htmlspecialchars($_SESSION['user_name']) ?> 🌸
       </li>
       <li>
          <a href="/gharbouch/back/logout.php" style="color:#e87aa0;">Déconnexion</a>
        </li>
        <li>
          <a href="/gharbouch/html/compte.php" style="color:#e87aa0;">Mon Compte</a>
        </li>
    <?php else: ?>
        <li><a href="/gharbouch/html/connexion.php">Connexion</a></li>
    <?php endif; ?>
  </ul>
  <button class="cart-btn" onclick="window.location.href='/gharbouch/html/panier.php'">
    🛒 Panier <span class="cart-count" id="cart-count">0</span>
  </button>
</nav>
<script>
  // Afficher le nombre d'articles dans le panier depuis localStorage
  function updateCartCount() {
    var cart = JSON.parse(localStorage.getItem('gharbouch_cart') || '[]');
    document.getElementById('cart-count').textContent = cart.length;
  }
  updateCartCount();
</script>