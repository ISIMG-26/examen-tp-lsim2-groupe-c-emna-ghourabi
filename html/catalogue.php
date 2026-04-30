<?php 
$pageTitle = 'Catalogue';
$activePage = 'catalogue';
require_once __DIR__ . '/../back/header.php';

?>
<section class="catalogue">
  <h2 class="section-title">Notre Catalogue</h2>
  <p class="section-sub">Toutes nos créations artisanales, prêtes à être adoptées</p>
  <div class="search-bar">
    <input type="text" id="search-input" placeholder="🔍 Rechercher un article..."
           oninput="handleSearch(this.value)" autocomplete="off">
    <button onclick="handleSearch(document.getElementById('search-input').value)">Chercher</button>
  </div>
  <div class="filters">
    <button class="filter-btn" data-cat="all"         onclick="filterProducts('all', this)">Tout voir</button>
    <button class="filter-btn" data-cat="bag"         onclick="filterProducts('bag', this)">Sacs</button>
    <button class="filter-btn" data-cat="flower"      onclick="filterProducts('flower', this)">Fleurs</button>
    <button class="filter-btn" data-cat="keychain"    onclick="filterProducts('keychain', this)">Porte-clés</button>
    <button class="filter-btn" data-cat="Accessories" onclick="filterProducts('Accessories', this)">Accessoires</button>
  </div>

  <div class="products-grid" id="products-grid">
    <p class="loading-msg">Chargement… 🌸</p>
  </div>
</section>
<script src="../js/catalogue.js"></script>
<?php require_once __DIR__ . '/../back/footer.php';
?>