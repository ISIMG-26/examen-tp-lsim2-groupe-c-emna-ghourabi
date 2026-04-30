
var flowerQty = {}; // { slug: { qty, nom, prix, emoji } }
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
/* ===== AJAX — Charger fleurs (back/get_fleurs.php) ===== */
function loadFlowers() {
  var g = document.getElementById('flower-grid');
  g.innerHTML = '<p class="loading-msg">Chargement… 🌸</p>';
  var xhr = new XMLHttpRequest();
  xhr.open('GET', '../back/get_fleurs.php', true); 
  xhr.onload = function() {
    if (xhr.status === 200) {
      g.innerHTML = xhr.responseText; // HTML injecté directement depuis PHP
      flowerQty = {};                 // Réinitialise les sélections
      updatePreview();                // Aperçu vide au départ
    } else {
      g.innerHTML = '<p class="loading-msg">Erreur.</p>';
    }
  };
  xhr.onerror = function() {
    g.innerHTML = '<p class="loading-msg">Serveur inaccessible.</p>';
  };
  xhr.send();
}
/* ===== Sélection des fleurs ===== */
function toggleFlower(slug, nom, prix, emoji) { // slug = "rose-rouge", nom = "Rose rouge", prix = 2.50
  var el = document.getElementById('fi-' + slug);
  // Récupère la tuile de la fleur dans le DOM — ex: l'élément id="fi-rose-rouge"
  var qc = document.getElementById('qc-' + slug);// Récupère le bloc +/− de cette fleur — ex: id="qc-rose-rouge"
  if (flowerQty[slug]) {// Si la fleur EST DÉJÀ dans flowerQty → l'utilisateur re-clique dessus pour la désélectionner
     delete flowerQty[slug];// Supprime complètement cette fleur de l'objet d'état
    el.classList.remove('selected');// Retire le style visuel de sélection (bordure colorée, fond…)
    qc.style.display = 'none';// Cache le contrôle de quantité +/−
  } else {// Si la fleur N'EST PAS encore dans flowerQty → premier clic, on la sélectionne
    flowerQty[slug] = { qty: 1, nom: nom, prix: parseFloat(prix), emoji: emoji };// Ajoute la fleur dans l'état avec qty=1 par défaut parseFloat(prix) convertit en nombre décimal au cas où prix arrive en string
    el.classList.add('selected');// Applique le style visuel de sélection sur la tuile
    qc.style.display = 'flex';  // Affiche le contrôle de quantité +/− (flex pour l'alignement horizontal)
  }
  updatePreview();// Recalcule et redessine l'aperçu du bouquet + les prix après chaque changement
}

function changeQty(slug, d) {
  if (!flowerQty[slug]) return;
  flowerQty[slug].qty = Math.max(1, Math.min(8, flowerQty[slug].qty + d));
  document.getElementById('qn-' + slug).textContent = flowerQty[slug].qty;
  updatePreview();
}

/* ===== Aperçu en temps réel ===== */
function updatePreview() {

  var sel = Object.keys(flowerQty);// Extrait la liste des slugs des fleurs sélectionnées depuis l'objet flowerQty// ex: flowerQty = { 'rose': {...}, 'tulipe': {...} } → sel = ['rose', 'tulipe']

  var display = document.getElementById('bouquet-display');// Récupère la zone où les emojis du bouquet sont affichés visuellement

  var label = document.getElementById('bouquet-label');// Récupère le texte résumé sous le bouquet ex: "3 tiges — 2 variétés"

  if (sel.length === 0) {// Aucune fleur sélectionnée → état vide par défaut

    display.innerHTML = '<span style="font-size:2.5rem;opacity:.3">💐</span>';// Affiche un emoji bouquet grisé/transparent comme placeholder

    label.textContent = 'Ajoute des fleurs pour commencer'; // Message d'invitation à sélectionner des fleurs

  } else {// Au moins une fleur sélectionnée → on construit l'aperçu visuel

    var emojis = ''; // Chaîne qui va accumuler les spans emoji de chaque tige

    for (var i = 0; i < sel.length; i++) {// Boucle sur chaque variété de fleur sélectionnée

      var f = flowerQty[sel[i]];// Récupère les données de cette fleur : { qty, nom, prix, emoji } → ex: { qty: 3, nom: 'rose', prix: 2.5, emoji: '🌹' }
      
      for (var j = 0; j < f.qty; j++) emojis += '<span>' + f.emoji + '</span>'; // Ajoute une <span> emoji par tige — si qty=3 roses → 3 spans 🌹
    }

    display.innerHTML = emojis;// Injecte tous les emojis dans la zone d'affichage du bouquet
    
    var stems = sel.reduce(function(a, s) { return a + flowerQty[s].qty; }, 0);// Calcule le nombre TOTAL de tiges — somme des qty de toutes les fleurs// ex: 2 roses + 3 tulipes → stems = 5
    
    label.textContent = stems + ' tige' + (stems > 1 ? 's' : '') + ' — ' + sel.length + ' variété' + (sel.length > 1 ? 's' : '');// Construit le texte résumé avec pluriel conditionnel// ex: "5 tiges — 2 variétés" ou "1 tige — 1 variété"
  }

  var flowerSum = sel.reduce(function(a, s) { return a + flowerQty[s].prix * flowerQty[s].qty; }, 0);// Calcule le sous-total fleurs : prix × quantité pour chaque fleur, puis somme tout// ex: (rose 2.5 × 2) + (tulipe 1.8 × 3) = 5 + 5.4 = 10.4 DT
  
  var total = flowerSum + 20;// Ajoute les frais fixes : 15 DT emballage + 5 DT carte = 20 DT
  
  document.getElementById('b-flower-total').textContent = flowerSum.toFixed(2) + ' DT';// Affiche le sous-total fleurs formaté à 2 décimales ex: "10.40 DT"
  
  document.getElementById('b-total').textContent = total.toFixed(2) + ' DT';// Affiche le total général formaté ex: "30.40 DT"
  
  var rec = document.getElementById('b-recipient').value;// Récupère le prénom du destinataire saisi dans le champ texte
  
  var rib = document.getElementById('b-ribbon');// Récupère l'élément <select> de la couleur du ruban (pas sa valeur, l'élément entier)
  
  var msg = document.getElementById('b-message').value;// Récupère le message carte saisi dans le textarea
  
  var cardPreview = document.getElementById('bouquet-card-preview');// Récupère le bloc aperçu carte cadeau (caché par défaut)

  if (rec || msg) {// Affiche la carte uniquement si au moins un des deux champs est rempli
    
    cardPreview.style.display = 'block';// Rend le bloc aperçu carte visible
    
    document.getElementById('pc-recipient').textContent = rec || '—';// Affiche le prénom du destinataire, ou "—" si vide
    
    document.getElementById('pc-ribbon').textContent = rib.options[rib.selectedIndex].text;// Affiche le LIBELLÉ de l'option sélectionnée (ex: "🎀 Rose poudré")// rib.selectedIndex = index de l'option choisie → .text = son texte affiché
  
    document.getElementById('pc-message').textContent = msg || 'Pas de message';// Affiche le message ou "Pas de message" si vide
  
  } else {
    cardPreview.style.display = 'none';// Cache le bloc aperçu carte si les deux champs sont vides
  }
}

/* ===== Ajouter bouquet au panier ===== */
function addBouquetToCart() {
  var sel = Object.keys(flowerQty);// Récupère la liste des slugs des fleurs sélectionnées
  var err = document.getElementById('b-error');// Récupère l'élément d'erreur "Ajoute au moins une fleur…"
  if (sel.length === 0) { err.classList.add('show'); return; }
  err.classList.remove('show');
  var flowerSum = 0;
  for (var i = 0; i < sel.length; i++) {
    flowerSum = flowerSum + flowerQty[sel[i]].prix * flowerQty[sel[i]].qty;
  }
  var namesList = [];
  for (var i = 0; i < sel.length; i++) {
    namesList.push(flowerQty[sel[i]].nom);
  }
  var names = namesList.join(', '); 
  var rec       = document.getElementById('b-recipient').value.trim();
  var rib       = document.getElementById('b-ribbon').value;
  var msg       = document.getElementById('b-message').value.trim();

  var cart = getCart();
  cart.push({
    cartId      : Date.now(),
    nom         : 'Bouquet personnalisé',
    desc        : names + (rec ? ' — pour ' + rec : ''),
    prix        : flowerSum + 20,
    emoji       : '💐',
    isBouquet   : true,
    destinataire: rec,
    ruban       : rib,
    message     : msg
  });
  saveCart(cart);
  updateCartCount();
  showToast('💐 Ton bouquet a été ajouté au panier !');
  setTimeout(function() { window.location.href = 'panier.html'; }, 1000);
}
updateCartCount();
loadFlowers();
