<?php 
$pageTitle = 'bouquet';
$activePage = 'bouquet';
require_once __DIR__ . '/../back/header.php';

?>
<section class="bouquet-page">
  <h2 class="section-title">Crée ton bouquet 💐</h2>
  <p class="section-sub">Compose ton bouquet personnalisé avec les fleurs de ton choix</p>

  <div class="builder-grid">
    <!-- GAUCHE : Choix fleurs + personnalisation -->
    <div>
      <div class="builder-left">
        <div class="section-header"><h3>🌸 Choisis tes fleurs</h3></div>
        <div class="flower-grid" id="flower-grid">
          <p class="loading-msg">Chargement… 🌸</p>
        </div>
      </div>
      <div style="height:16px"></div>
      <div class="builder-left">
        <div class="section-header"><h3>✏️ Personnalise</h3></div>
        <div class="form-row">
          <label for="b-recipient">Prénom du destinataire</label>
          <input type="text" id="b-recipient" placeholder="ex: Yasmine" oninput="updatePreview()">
        </div>
        <div class="form-row">
          <label for="b-ribbon">Couleur du ruban</label>
          <select id="b-ribbon" onchange="updatePreview()">
            <option value="rose">🎀 Rose poudré</option>
            <option value="bleu">💙 Bleu ciel</option>
            <option value="lavande">💜 Lavande</option>
            <option value="blanc">🤍 Blanc ivoire</option>
            <option value="vert">💚 Vert sauge</option>
          </select>
        </div>
        <div class="form-row">
          <label for="b-message">Message sur la carte</label>
          <textarea id="b-message" placeholder="Écris ton message doux ici…" oninput="updatePreview()"></textarea>
        </div>
      </div>
    </div>

    <!-- DROITE : Aperçu + prix -->
    <div>
      <div class="builder-right">
        <div class="section-header"><h3>💐 Ton bouquet</h3></div>
        <div class="bouquet-preview">
          <div class="bouquet-flowers" id="bouquet-display">
            <span style="font-size:2.5rem;opacity:.3">💐</span>
          </div>
          <div id="bouquet-label" style="font-size:12px;color:var(--muted);margin-top:8px">Ajoute des fleurs pour commencer</div>
        </div>

        <!-- Aperçu carte -->
        <div id="bouquet-card-preview" style="background:var(--cream);border-radius:12px;padding:12px;margin-bottom:14px;font-size:13px;display:none">
          <div style="color:var(--muted);font-size:11px;margin-bottom:4px">Carte pour :</div>
          <div id="pc-recipient" style="font-weight:500;color:var(--text)"></div>
          <div style="color:var(--muted);font-size:11px;margin:6px 0 4px">Ruban :</div>
          <div id="pc-ribbon" style="font-size:13px"></div>
          <div style="color:var(--muted);font-size:11px;margin:6px 0 4px">Message :</div>
          <div id="pc-message" style="color:var(--text);font-style:italic;font-size:13px"></div>
        </div>

        <!-- Prix -->
        <div class="price-summary">
          <div class="price-row"><span>Fleurs sélectionnées</span><span id="b-flower-total">0.00 DT</span></div>
          <div class="price-row"><span>Emballage &amp; ruban</span><span>15 DT</span></div>
          <div class="price-row"><span>Carte personnalisée</span><span>5 DT</span></div>
          <div class="price-total"><span>Total</span><span id="b-total">20.00 DT</span></div>
        </div>

        <div id="b-error" class="err">Ajoute au moins une fleur à ton bouquet ✿</div>
        <button class="btn-primary" style="width:100%" onclick="addBouquetToCart()">Ajouter au panier 🛒</button>
      </div>
    </div>
  </div>
</section>
<script src="../js/bouquet.js"></script>
<?php require_once __DIR__ . '/../back/footer.php';
?>