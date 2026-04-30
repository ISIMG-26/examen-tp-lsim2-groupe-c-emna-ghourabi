
/* ===== PANIER (localStorage partagé entre pages) ===== */
function getCart() {return JSON.parse(localStorage.getItem('gharbouch_cart') || '[]');}
function saveCart(cart) {localStorage.setItem('gharbouch_cart', JSON.stringify(cart));}
function updateCartCount() {document.getElementById('cart-count').textContent = getCart().length;}

/* ===== TOAST ===== */
function showToast(msg) {
  var t = document.getElementById('toast');
  t.textContent = msg;
  t.classList.add('show');
  setTimeout(function() { t.classList.remove('show'); }, 2800);
}

/* ===== AJAX — Charger produits (back/get_produits.php) ===== */
function loadProducts(categorie) {
  var grid = document.getElementById('products-grid');
  grid.innerHTML = '<p class="loading-msg">Chargement… 🌸</p>';
  var xhr = new XMLHttpRequest();
  xhr.open('GET', '../back/get_produits.php?categorie=' + encodeURIComponent(categorie || 'all'), true);
  // Prépare la requête GET vers get_produits.php
  // encodeURIComponent() encode les caractères spéciaux dans l'URL (ex: espaces, &...)
  // categorie || 'all' → si categorie est vide/undefined, envoie 'all'
  // true = requête asynchrone (page ne se bloque pas)
  xhr.onload = function() {
    if (xhr.status === 200) {
      grid.innerHTML = xhr.responseText;
    } else {
      grid.innerHTML = '<p class="loading-msg">Erreur lors du chargement.</p>';
    }
  };
  xhr.onerror = function() {
    grid.innerHTML = '<p class="loading-msg">Impossible de joindre le serveur.</p>';
  };
  xhr.send();
  
}
/* ===== AJAX - Rechercher produits (back/get_produits.php) ===== */
var searchTimer;// Variable globale qui stocke le timer du debounce
function handleSearch(val) { // Fonction appelée à chaque frappe dans le champ de recherche// val = texte saisi par l'utilisateur
    clearTimeout(searchTimer);
    if (val.trim().length < 2){
        loadProducts('all'); return;
    }
    searchTimer= setTimeout(function() {// Lance un timer de 350ms — la requête part seulement si l'utilisateur arrête de taper
        var grid = document.getElementById('products-grid');
        grid.innerHTML = '<p class="loading-msg">Chargement… 🌸</p>';
        var xhr = new XMLHttpRequest();
        xhr.open('GET', '../back/get_produits.php?q=' + encodeURIComponent(val), true);
        xhr.onload = function() {
            if (xhr.status === 200){
                grid.innerHTML = xhr.responseText;
            } else{
                grid.innerHTML = '<p class="loading-msg">Erreur lors du chargement.</p>';
            }
        };
        xhr.onerror = function() {
            grid.innerHTML = '<p class="loading-msg">Impossible de joindre le serveur.</p>';
            };
        xhr.send();
    }, 350);// attend 350ms après la dernière frappe avant d'envoyer
    }

/* ===== Filtre ===== */
function filterProducts(cat, btn) {
  var btns = document.querySelectorAll('.filter-btn');
  for (var i = 0; i < btns.length; i++) {
    btns[i].classList.remove('active');}// Retire la classe active de tous les boutons filtre
  btn.classList.add('active');// Active uniquement le bouton cliqué
  document.getElementById('search-input').value = '';// Vide le champ de recherche au changement de filtre
  loadProducts(cat);
}

/* ===== Ajouter au panier ===== */
function addToCart(id, nom, prix, desc, emoji) {
  var cart = getCart();
  cart.push({ id: id, nom: nom, prix: parseFloat(prix), desc: desc, emoji: emoji, cartId: Date.now(), isBouquet: false });
  saveCart(cart);
  updateCartCount();
  showToast(emoji + ' ' + nom + ' ajouté au panier !');
}
/* ===== INIT ===== */
updateCartCount();

// Lire la catégorie depuis l'URL (?cat=bag)
var urlParams = new URLSearchParams(window.location.search);
// Lit les paramètres de l'URL — ex: catalogue.php?cat=bag → urlParams.get('cat') = 'bag'
var catParam = urlParams.get('cat') || 'all';
// Récupère la valeur de ?cat= — 'all' si absent

// Activer le bon bouton filtre
document.querySelectorAll('.filter-btn').forEach(function(b) {
  if (b.getAttribute('data-cat') === catParam) b.classList.add('active');// Active le bouton filtre correspondant à la catégorie de l'URL
});
// Lance le premier chargement des produits avec la catégorie de l'URL
loadProducts(catParam);

