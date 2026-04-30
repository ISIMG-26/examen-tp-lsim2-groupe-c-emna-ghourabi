<?php 
$pageTitle = 'connexion';
$activePage = 'connexion';
require_once __DIR__ . '/../back/header.php';
?>
<!-- ===== AUTH ===== -->
<section class="auth-page">
  <div class="auth-card">
    <div style="text-align:center;margin-bottom:1.5rem">
      <div style="font-family:'Georgia',serif;font-size:1.8rem;color:var(--pink3)">gharbouch ✿</div>
      <div style="color:var(--muted);font-size:13px;margin-top:4px">Ta boutique artisanale</div>
    </div>

    <!-- Onglets -->
    <div class="auth-tabs">
      <div class="auth-tab active" id="tab-login"    onclick="switchAuth('login')">Connexion</div>
      <div class="auth-tab"        id="tab-register" onclick="switchAuth('register')">Inscription</div>
    </div>

    <!-- ===== FORMULAIRE CONNEXION ===== -->
    <div class="auth-form active" id="form-login">
      <div class="form-row">
        <label for="l-email">Email</label>
        <input type="email" id="l-email" placeholder="ton@email.com" oninput="clearErr('l-email-err')">
        <div class="err" id="l-email-err">Email invalide</div>
      </div>
      <div class="form-row">
        <label for="l-pass">Mot de passe</label>
        <input type="password" id="l-pass" placeholder="••••••••" oninput="clearErr('l-pass-err')">
        <div class="err" id="l-pass-err">Mot de passe requis</div>
      </div>
      <div class="api-error" id="l-api-err"></div>
      <button id="login-btn" class="btn-primary" style="width:100%;margin-top:6px" onclick="doLogin()">Se connecter</button>
      <div class="success-msg" id="login-success"></div>
    </div>

    <!-- ===== FORMULAIRE INSCRIPTION ===== -->
    <div class="auth-form" id="form-register">
      <div class="form-row">
        <label for="r-name">Prénom</label>
        <input type="text" id="r-name" placeholder="Yasmine" oninput="clearErr('r-name-err')">
        <div class="err" id="r-name-err">Prénom requis</div>
      </div>
      <div class="form-row">
        <label for="r-email">Email</label>
        <input type="email" id="r-email" placeholder="ton@email.com"
               oninput="clearErr('r-email-err');checkEmailLive()">
        <div class="err" id="r-email-err">Email invalide</div>
        <!-- Vérification AJAX temps réel -->
        <div id="email-check-indicator" class="email-check"></div>
      </div>
      <div class="form-row">
        <label for="r-pass">Mot de passe</label>
        <input type="password" id="r-pass" placeholder="Min. 8 caractères" oninput="clearErr('r-pass-err')">
        <div class="err" id="r-pass-err">Min. 8 caractères requis</div>
      </div>
      <div class="form-row">
        <label for="r-pass2">Confirmer le mot de passe</label>
        <input type="password" id="r-pass2" placeholder="••••••••" oninput="clearErr('r-pass2-err')">
        <div class="err" id="r-pass2-err">Les mots de passe ne correspondent pas</div>
      </div>
      <div class="api-error" id="r-api-err"></div>
      <button id="register-btn" class="btn-primary" style="width:100%;margin-top:6px" onclick="doRegister()">Créer mon compte</button>
      <div class="success-msg" id="register-success"></div>
    </div>
  </div>
</section>
<?php require_once __DIR__ . '/../back/footer.php';
?>
<script src="../js/connexion.js">
</script>
<?php require_once __DIR__ . '/../back/footer.php';
?>