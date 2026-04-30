
/* ===== Panier count ===== */
function updateCartCount() {
  var cart = JSON.parse(localStorage.getItem('gharbouch_cart') || '[]');
  document.getElementById('cart-count').textContent = cart.length;
}

/* ===== Toast ===== */
function showToast(msg) {
  var t = document.getElementById('toast');
  t.textContent = msg; t.classList.add('show');
  setTimeout(function() { t.classList.remove('show'); }, 2800);
}

/* ===== Onglets connexion / inscription ===== */
function switchAuth(tab) {
  // Active l'onglet "Connexion" si tab === 'login', sinon le désactive
  // toggle(classe, condition) → ajoute si condition vraie, retire si fausse
  document.getElementById('tab-login').classList.toggle('active', tab === 'login');
  document.getElementById('tab-register').classList.toggle('active', tab === 'register');
  document.getElementById('form-login').classList.toggle('active', tab === 'login');
  document.getElementById('form-register').classList.toggle('active', tab === 'register');
}

/* ===== Effacer erreur ===== */
function clearErr(id) {
  // Appelée au focus d'un champ pour cacher son message d'erreur
  var el = document.getElementById(id);
  if (el) el.classList.remove('show');
}

/* ===== AJAX — Vérification email en temps réel ===== */
var emailCheckTimer = null;// Timer global pour le debounce — évite une requête à chaque frappe
function checkEmailLive() {
  var email = document.getElementById('r-email').value.trim();
  var indicator = document.getElementById('email-check-indicator');
  clearTimeout(emailCheckTimer);
  indicator.textContent = '';
  indicator.className = 'email-check';

  if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) return;
    // Regex qui vérifie le format email — si invalide, arrête la fonction
    // ^[^\s@]+ → au moins un caractère avant @
    // @[^\s@]+ → @ suivi d'au moins un caractère
    // \.[^\s@]+$ → point suivi du domaine (.com, .fr...)
  emailCheckTimer = setTimeout(function() {
    var fd = new FormData();
    fd.append('action', 'check_email');
    fd.append('email', email);
    // Prépare les données POST : action + email à vérifier

    fetch('../back/auth.php', { method: 'POST', body: fd })
    // Envoie la requête POST vers auth.php
      .then(function(r) { return r.json(); })// Transforme la réponse en JSON
      .then(function(res) {
        if (res.exists) {
          indicator.textContent = '✗ Cet email est déjà utilisé';
          indicator.className = 'email-check taken';// Si l'email existe, indique que l'email est déjà utilisé
        } else {
          indicator.textContent = '✓ Email disponible';
          indicator.className = 'email-check free';// Si l'email n'existe pas, indique que l'email est disponible
        }
      })
      .catch(function() {});// En cas d'erreur, ne fait rien
  }, 500);// Vérifie l'email après 500ms
}

/* ===== AJAX — Connexion ===== */
function doLogin() {
  var email = document.getElementById('l-email').value.trim();
  var pass  = document.getElementById('l-pass').value;
    // Récupère les valeurs des champs email et mot de passe
  var emailErr = document.getElementById('l-email-err');
  var passErr  = document.getElementById('l-pass-err');
  var apiErr   = document.getElementById('l-api-err');
  var success  = document.getElementById('login-success');
  // Références aux éléments d'erreur et de succès
  var ok = true;
  // Flag de validation — passe à false si un champ est invalide
  // Réinitialiser les messages d'erreur avant revalidation
  emailErr.classList.remove('show');
  passErr.classList.remove('show');
  apiErr.classList.remove('show');
  success.classList.remove('show');

  // Validation côté client
  if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { emailErr.classList.add('show'); ok = false; }
  if (!pass) { passErr.classList.add('show'); ok = false; }
  if (!ok) return;

  var btn = document.getElementById('login-btn');
  btn.disabled = true;
  btn.textContent = 'Connexion…';

  var fd = new FormData();
  fd.append('action', 'login');
  fd.append('email', email);
  fd.append('mot_de_passe', pass);

  fetch('../back/auth.php', { method: 'POST', body: fd })
  .then(function(r) {
    return r.json(); // ← r.json() only, no r.text()
  })
  .then(function(res) { // ← res is already an object, remove JSON.parse
    btn.disabled = false;
    btn.textContent = 'Se connecter';

    if (res.success) {
      localStorage.setItem('gharbouch_user', JSON.stringify({ id: res.id, prenom: res.prenom }));
      if (success) {
        success.textContent = res.message;
        success.classList.add('show');
      }
      showToast(res.message);
      setTimeout(function() { window.location.href = '../index.php'; }, 1500);
    } else {
      apiErr.textContent = res.error;
      apiErr.classList.add('show');
    }
  })
  .catch(function(err) {
    btn.disabled = false;
    btn.textContent = 'Se connecter';
    apiErr.textContent = 'Erreur serveur.';
    apiErr.classList.add('show');
  });
}

/* ===== AJAX — Inscription ===== */
function doRegister() {
  var name  = document.getElementById('r-name').value.trim();
  var email = document.getElementById('r-email').value.trim();
  var pass  = document.getElementById('r-pass').value;
  var pass2 = document.getElementById('r-pass2').value;
  var nameErr  = document.getElementById('r-name-err');
  var emailErr = document.getElementById('r-email-err');
  var passErr  = document.getElementById('r-pass-err');
  var pass2Err = document.getElementById('r-pass2-err');
  var apiErr   = document.getElementById('r-api-err');
  var success  = document.getElementById('register-success');
  var ok = true;

  // Réinitialiser
  [nameErr, emailErr, passErr, pass2Err, apiErr].forEach(function(e) { e.classList.remove('show'); });
  success.classList.remove('show');

  // Validation côté client
  if (!name)  { nameErr.classList.add('show'); ok = false; }
  if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { emailErr.classList.add('show'); ok = false; }
  if (pass.length < 8) { passErr.classList.add('show'); ok = false; }
  if (pass !== pass2)  { pass2Err.classList.add('show'); ok = false; }
  if (!ok) return;

  var btn = document.getElementById('register-btn');
  btn.disabled = true;
  btn.textContent = 'Création…';

  var fd = new FormData();
  fd.append('action', 'register');
  fd.append('prenom', name);
  fd.append('email', email);
  fd.append('mot_de_passe', pass);

  fetch('../back/auth.php', { method: 'POST', body: fd })
    .then(function(r) { return r.json(); })
    .then(function(res) {
      btn.disabled = false;
      btn.textContent = 'Créer mon compte';
      if (res.success) {
        success.textContent = res.message;
        success.classList.add('show');
        showToast(res.message);
        document.getElementById('email-check-indicator').textContent = '';
        // Basculer vers connexion après inscription
        setTimeout(function() { switchAuth('login'); }, 2000);
      } else {
        apiErr.textContent = res.error;
        apiErr.classList.add('show');
      }
    })
    .catch(function() {
      btn.disabled = false;
      btn.textContent = 'Créer mon compte';
      apiErr.textContent = 'Erreur serveur.';
      apiErr.classList.add('show');
    });
}

/* ===== INIT ===== */
updateCartCount();

// Si déjà connecté, afficher un message de bienvenue
var savedUser = JSON.parse(localStorage.getItem('gharbouch_user') || 'null');
if (savedUser) {
  var loginSuccess = document.getElementById('login-success');
  loginSuccess.textContent = 'Déjà connecté(e) en tant que ' + savedUser.prenom + ' 🌸';
  loginSuccess.classList.add('show');
 // Si utilisateur déjà connecté → affiche message de bienvenue directement
}