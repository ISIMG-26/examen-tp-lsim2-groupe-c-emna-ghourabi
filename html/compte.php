<?php
$pageTitle = 'Mon compte';
$activePage = 'compte';
require_once __DIR__ . '/../back/header.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: /../html/connexion.php');
    exit;
}
$conn = mysqli_connect('localhost', 'root', '', 'gharbouch');
if (!$conn) {
    die('Erreur de connexion : ' . mysqli_connect_error());
}
mysqli_set_charset($conn, 'utf8');

$user_id = (int)$_SESSION['user_id'];
$res = mysqli_query($conn, "SELECT id, prenom, email, date_inscription FROM utilisateurs WHERE id = $user_id");
$user = mysqli_fetch_assoc($res);
mysqli_close($conn);
if(!$user){
    session_unset();
    session_destroy();
    header('Location: /../html/connexion.php');
    exit;
}
?>
<style>
/* ===== MON COMPTE PAGE ===== */
.compte-hero {
  background: linear-gradient(135deg, #fff0f6, #fff8f0);
  padding: 2.5rem 2rem 2rem;
  text-align: center;
  border-bottom: 1px solid var(--pink1);
}
.compte-hero .avatar-circle {
  width: 68px; height: 68px;
  background: var(--pink3);
  border-radius: 50%;
  display: inline-flex; align-items: center; justify-content: center;
  font-size: 1.8rem;
  margin-bottom: 1rem;
  box-shadow: 0 6px 20px rgba(194,24,91,.22);
}
.compte-hero h1 {
  font-family: 'Georgia', serif;
  font-size: 1.7rem;
  color: var(--text);
  margin-bottom: .3rem;
}
.compte-hero p { color: var(--muted); font-size: 13px; }

.compte-wrap {
  max-width: 700px;
  margin: 2.5rem auto 4rem;
  padding: 0 1.5rem;
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}
.compte-card {
  background: var(--white);
  border-radius: var(--radius);
  padding: 1.8rem 2rem;
  box-shadow: var(--shadow);
  border: 1px solid #f8edf2;
}
.compte-card-header {
  display: flex; align-items: center; gap: .75rem;
  margin-bottom: 1.4rem;
  padding-bottom: 1rem;
  border-bottom: 1px solid var(--pink1);
}
.compte-card-header .card-icon {
  width: 36px; height: 36px;
  border-radius: 10px;
  display: flex; align-items: center; justify-content: center;
  font-size: 1.1rem;
}
.card-icon.pink  { background: var(--pink1); }
.card-icon.blue  { background: #e3f2fd; }
.card-icon.red   { background: #fce4ec; }
.compte-card-header h2 {
  font-family: 'Georgia', serif;
  font-size: 1.05rem;
  color: var(--text);
}
.compte-field { margin-bottom: 1rem; }
.compte-field label {
  display: block;
  font-size: 11px;
  font-weight: 600;
  color: var(--muted);
  letter-spacing: .06em;
  text-transform: uppercase;
  margin-bottom: 5px;
}
.compte-field input {
  width: 100%;
  padding: 10px 14px;
  border: 1.5px solid var(--pink1);
  border-radius: 10px;
  font-family: 'Segoe UI', sans-serif;
  font-size: 14px;
  color: var(--text);
  background: var(--cream);
  outline: none;
  transition: border-color .2s, box-shadow .2s;
}
.compte-field input:focus {
  border-color: var(--pink3);
  box-shadow: 0 0 0 3px rgba(194,24,91,.10);
  background: #fff;
}
.compte-field input[readonly] {
  background: #fdf7fa; color: var(--muted);
  cursor: not-allowed; border-color: #f0e0ea;
}
.field-hint { font-size: 11px; color: var(--muted); margin-top: 4px; }

/* Alert */
.compte-alert {
  padding: 10px 14px;
  border-radius: 10px;
  font-size: 13px;
  font-weight: 500;
  margin-top: 12px;
  display: none;
}
.compte-alert.success { background: #e8f5e9; color: #2e7d32; display: block; }
.compte-alert.error   { background: #fce4ec; color: #c62828; display: block; }

/* Danger */
.danger-box {
  background: #fff8f8;
  border: 1px solid #f5c6c6;
  border-radius: 10px;
  padding: 14px 16px;
  font-size: 13px; color: #7b2d2d;
  margin-bottom: 1.2rem; line-height: 1.6;
}
.danger-box strong { display: block; margin-bottom: 3px; font-size: 13px; }

/* Modal */
.compte-overlay {
  position: fixed; inset: 0;
  background: rgba(58,42,48,.40);
  backdrop-filter: blur(4px);
  display: flex; align-items: center; justify-content: center;
  z-index: 500;
  opacity: 0; pointer-events: none;
  transition: opacity .25s;
}
.compte-overlay.show { opacity: 1; pointer-events: all; }
.compte-modal {
  background: var(--white);
  border-radius: var(--radius);
  padding: 2rem 2.2rem;
  max-width: 380px; width: 90%;
  text-align: center;
  box-shadow: 0 20px 60px rgba(0,0,0,.14);
  transform: translateY(16px);
  transition: transform .25s;
}
.compte-overlay.show .compte-modal { transform: translateY(0); }
.compte-modal .modal-emoji { font-size: 2.2rem; margin-bottom: .7rem; }
.compte-modal h3 { font-family:'Georgia',serif; font-size:1.15rem; margin-bottom:.5rem; }
.compte-modal p { color:var(--muted); font-size:13px; margin-bottom:1.2rem; line-height:1.6; }
.modal-actions { display:flex; gap:10px; justify-content:center; margin-top:1rem; }

.btn-delete {
  background: transparent; color: #c62828;
  border: 1.5px solid #f5c6c6; border-radius: 24px;
  padding: 10px 22px; font-size: 13px; font-weight: 600;
  cursor: pointer; transition: .2s;
}
.btn-delete:hover { background: #fce4ec; }
.btn-cancel {
  background: var(--pink3); color: #fff;
  border: none; border-radius: 24px;
  padding: 10px 22px; font-size: 13px; font-weight: 600;
  cursor: pointer; transition: .2s;
}
.btn-cancel:hover { background: #a0154e; }

@media (max-width: 500px) {
  .compte-card { padding: 1.4rem 1.2rem; }
  .compte-modal { padding: 1.6rem 1.4rem; }
}
</style>


<div class="compte-hero">
  <div class="avatar-circle">🌸</div>
  <h1>Bonjour, <?= htmlspecialchars($user['prenom']) ?> !</h1>
  <p>Membre depuis le <?= date('d/m/Y', strtotime($user['date_inscription'])) ?></p>
</div>

<div class="compte-wrap">

  <!-- INFOS READ-ONLY -->
  <div class="compte-card">
    <div class="compte-card-header">
      <div class="card-icon pink">👤</div>
      <h2>Mes informations</h2>
    </div>
    <div class="compte-field">
      <label>Prénom</label>
      <input type="text" value="<?= htmlspecialchars($user['prenom']) ?>" readonly>
    </div>
    <div class="compte-field">
      <label>Adresse e-mail</label>
      <input type="email" value="<?= htmlspecialchars($user['email']) ?>" readonly>
    </div>
    <p class="field-hint">Pour modifier ces informations, contacte-nous par e-mail. ✿</p>
  </div>

  <!-- CHANGE PASSWORD -->
  <div class="compte-card">
    <div class="compte-card-header">
      <div class="card-icon blue">🔑</div>
      <h2>Changer mon mot de passe</h2>
    </div>
    <div class="compte-field">
      <label>Mot de passe actuel</label>
      <input type="password" id="cur-pass" placeholder="••••••••">
    </div>
    <div class="compte-field">
      <label>Nouveau mot de passe</label>
      <input type="password" id="new-pass" placeholder="Minimum 8 caractères" oninput="evalStrength(this.value)">
    </div>
    <div class="compte-field">
      <label>Confirmer le nouveau mot de passe</label>
      <input type="password" id="conf-pass" placeholder="••••••••">
    </div>
    <button class="btn-primary" id="btn-pwd" onclick="doChangePassword()" style="width:100%;margin-top:.4rem">
      Mettre à jour le mot de passe
    </button>
    <div class="compte-alert" id="pwd-alert"></div>
  </div>

  <!-- DELETE ACCOUNT -->
  <div class="compte-card">
    <div class="compte-card-header">
      <div class="card-icon red">🗑️</div>
      <h2>Supprimer mon compte</h2>
    </div>
    <div class="danger-box">
      <strong>⚠️ Action irréversible</strong>
      La suppression de ton compte effacera définitivement toutes tes données :
      historique de commandes et informations personnelles. Cette action est permanente.
    </div>
    <button class="btn-delete" onclick="openDeleteModal()">Supprimer mon compte</button>
    <div class="compte-alert" id="del-alert"></div>
  </div>

</div>

<!-- DELETE CONFIRM MODAL -->
<div class="compte-overlay" id="del-overlay">
  <div class="compte-modal">
    <div class="modal-emoji">💔</div>
    <h3>Tu vas nous manquer...</h3>
    <p>Cette action supprimera définitivement ton compte. Confirme avec ton mot de passe pour continuer.</p>
    <div class="compte-field" style="text-align:left;margin-bottom:.5rem">
      <label>Mot de passe</label>
      <input type="password" id="del-pass" placeholder="Ton mot de passe actuel">
    </div>
    <div class="compte-alert" id="del-modal-alert"></div>
    <div class="modal-actions">
      <button class="btn-delete" id="btn-del-confirm" onclick="doDeleteAccount()">Oui, supprimer</button>
      <button class="btn-cancel" onclick="closeDeleteModal()">Annuler</button>
    </div>
  </div>
</div

<?php require_once __DIR__ . '/../back/footer.php'; ?>
<script src="../js/compte.js"></script>