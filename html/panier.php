<?php
$pageTitle = 'panier';
$activePage = 'panier';
require_once __DIR__ . '/../back/header.php';

?>
<!-- ===== PANIER ===== -->
<section class="panier-page">
  <h2 class="section-title">Mon Panier 🛒</h2>

  <!-- Articles -->
  <div id="cart-items"></div>

  <!-- Panier vide -->
  <div id="cart-empty" class="empty-cart" style="display:none">
    <div class="ec-icon">🛒</div>
    <p style="font-weight:500;margin-bottom:.5rem">Ton panier est vide</p>
    <p style="color:var(--muted);font-size:14px;margin-bottom:1.2rem">Découvre nos créations et ajoute tes coups de cœur</p>
    <button class="btn-primary" onclick="window.location.href='catalogue.html'">Voir le catalogue</button>
  </div>

  <!-- Résumé + commande -->
  <div id="cart-summary-box" style="display:none">

    <!-- Formulaire de livraison -->
    <div class="checkout-form">
      <h3>📦 Informations de livraison</h3>
      <div class="form-row">
        <label for="co-nom">Nom complet</label>
        <input type="text" id="co-nom" placeholder="Votre nom" oninput="clearErr('co-nom-err')">
        <div class="err" id="co-nom-err">Nom requis</div>
      </div>
      <div class="form-row">
        <label for="co-email">Email</label>
        <input type="email" id="co-email" placeholder="votre@email.com" oninput="clearErr('co-email-err')">
        <div class="err" id="co-email-err">Email invalide</div>
      </div>
      <div class="api-error" id="co-api-err"></div>
    </div>

    <!-- Récapitulatif prix -->
    <div class="cart-summary">
      <div class="price-summary">
        <div class="price-row"><span>Sous-total</span><span id="cart-subtotal">0 DT</span></div>
        <div class="price-row"><span>Livraison</span><span>7 DT</span></div>
        <div class="price-total"><span>Total</span><span id="cart-total-price">0 DT</span></div>
      </div>
      <button id="checkout-btn" class="btn-primary" style="width:100%;margin-top:8px" onclick="checkout()">
        Commander maintenant ✿
      </button>
      <button class="btn-outline" style="width:100%;margin-top:8px"
              onclick="window.location.href='catalogue.html'">
        Continuer mes achats
      </button>
    </div>
  </div>
</section>

<!-- TOAST -->
<?php require_once __DIR__ . '/../back/footer.php';
?>
<script src="../js/panier.js">
</script>
