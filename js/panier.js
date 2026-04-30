/* ===== Panier localStorage ===== */
function getCart() { return JSON.parse(localStorage.getItem('gharbouch_cart') || '[]'); }
function saveCart(c) { localStorage.setItem('gharbouch_cart', JSON.stringify(c)); }
function updateCartCount() { document.getElementById('cart-count').textContent = getCart().length; }

/* ===== Toast ===== */
function showToast(msg) {
  var t = document.getElementById('toast');
  t.textContent = msg; t.classList.add('show');
  setTimeout(function() { t.classList.remove('show'); }, 2800);
}

/* ===== Effacer erreur ===== */
function clearErr(id) {
  var el = document.getElementById(id);
  if (el) el.classList.remove('show');
}

/* ===== Afficher les articles du panier ===== */
function renderCart() {
  var cart    = getCart();
  var items   = document.getElementById('cart-items');
  var empty   = document.getElementById('cart-empty');
  var summary = document.getElementById('cart-summary-box');

  updateCartCount();

  if (cart.length === 0) {
    items.innerHTML = '';
    empty.style.display   = 'block';
    summary.style.display = 'none';
    return;
  }

  empty.style.display   = 'none';
  summary.style.display = 'block';

  var html = '';
  for (var i = 0; i < cart.length; i++) {
    var item = cart[i];
    html +=
      '<div class="cart-item">' +
        '<div class="ci-emoji">' + item.emoji + '</div>' +
        '<div class="ci-info">' +
          '<div class="ci-name">' + escHtml(item.nom) + '</div>' +
          '<div class="ci-desc">' + escHtml(item.desc || '') + '</div>' +
        '</div>' +
        '<div class="ci-price">' + parseFloat(item.prix).toFixed(2) + ' DT</div>' +
        '<button class="ci-remove" onclick="removeFromCart(' + item.cartId + ')" title="Supprimer">×</button>' +
      '</div>';
  }
  items.innerHTML = html;

  var sub = cart.reduce(function(a, i) { return a + parseFloat(i.prix); }, 0);
  document.getElementById('cart-subtotal').textContent    = sub.toFixed(2) + ' DT';
  document.getElementById('cart-total-price').textContent = (sub + 7).toFixed(2) + ' DT';

  // Pré-remplir email si utilisateur connecté
  var savedUser = JSON.parse(localStorage.getItem('gharbouch_user') || 'null');
  if (savedUser) {
    var nomInput = document.getElementById('co-nom');
    if (!nomInput.value) nomInput.value = savedUser.prenom;
  }
}

/* ===== Supprimer un article ===== */
function removeFromCart(cartId) {
  var cart = getCart().filter(function(i) { return i.cartId !== cartId; });
  saveCart(cart);
  renderCart();
  showToast('Article retiré du panier');
}

/* ===== AJAX — Passer la commande (back/passer_commande.php) ===== */
function checkout() {
  var nom   = document.getElementById('co-nom').value.trim();
  var email = document.getElementById('co-email').value.trim();
  var nomErr   = document.getElementById('co-nom-err');
  var emailErr = document.getElementById('co-email-err');
  var apiErr   = document.getElementById('co-api-err');
  var ok = true;

  nomErr.classList.remove('show');
  emailErr.classList.remove('show');
  apiErr.classList.remove('show');

  // Validation côté client
  if (!nom) { nomErr.classList.add('show'); ok = false; }
  if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { emailErr.classList.add('show'); ok = false; }
  if (!ok) return;

  var cart = getCart();
  if (cart.length === 0) { showToast('Ton panier est vide !'); return; }

  var btn = document.getElementById('checkout-btn');
  btn.disabled = true;
  btn.textContent = 'Envoi…';

  var fd = new FormData();
  fd.append('nom_client', nom);
  fd.append('email_client', email);
  fd.append('items', JSON.stringify(cart));

  var savedUser = JSON.parse(localStorage.getItem('gharbouch_user') || 'null');
  if (savedUser) fd.append('utilisateur_id', savedUser.id);

  fetch('../back/passer_commande.php', { method: 'POST', body: fd })
    .then(function(r) { return r.json(); })
    .then(function(res) {
      btn.disabled = false;
      btn.textContent = 'Commander maintenant ✿';
      if (res.success) {
        // Vider le panier
        saveCart([]);
        renderCart();
        showToast('🎉 Commande #' + res.commande_id + ' passée ! Merci 🌸');
        document.getElementById('co-nom').value   = '';
        document.getElementById('co-email').value = '';
      } else {
        apiErr.textContent = res.error || 'Erreur serveur.';
        apiErr.classList.add('show');
      }
    })
    .catch(function() {
      btn.disabled = false;
      btn.textContent = 'Commander maintenant ✿';
      apiErr.textContent = 'Impossible de joindre le serveur.';
      apiErr.classList.add('show');
    });
}

/* ===== Utilitaire ===== */
function escHtml(str) {
  return String(str)
    .replace(/&/g, '&amp;').replace(/'/g, '&#39;')
    .replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
}

/* ===== INIT ===== */
renderCart();
