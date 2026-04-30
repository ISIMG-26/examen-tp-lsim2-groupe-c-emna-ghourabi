<?php 
$pageTitle = 'Home Paga';
$activePage = 'home';
require_once __DIR__ . '/back/header.php';
?>
<section class="hero">
  <div class="hero-text">
    <h1>Fait avec amour,<br><em>rien que pour toi</em></h1>
    <p>Crochet artisanal : sacs,Accessoiresm porte-clés, fleurs et bouquets
       personnalisés qui réchauffent le cœur.</p>
    <div style="display:flex;gap:12px;flex-wrap:wrap">
      <button class="btn-primary" onclick="window.location.href='/gharbouch/html/catalogue.php'">Découvrir la boutique</button>
      <button class="btn-outline" onclick="window.location.href='/gharbouch/html/bouquet.php'">Créer mon bouquet ✿</button>
    </div>
  </div>
  <div class="hero-deco">
    <div class="deco-card">🌸<span>Accessoires</span></div>
    <div class="deco-card">🔑<span>Porte-clés</span></div>
    <div class="deco-card">🌷<span>Fleurs</span></div>
    <div class="deco-card">👜<span>Sacs</span></div>
  </div>
</section>
<section class="categories">
  <h2 class="section-title">Nos univers</h2>
  <p class="section-sub">Explorez nos créations faites à la main avec des fils doux et des motifs poétiques</p>
  <div class="cat-grid">
    <div class="cat-card" style="background:linear-gradient(135deg,#fdf0f5,#f9d0e0)"
         onclick="window.location.href='catalogue.html?cat=bag'">
      <div class="cat-icon">👜</div><div class="cat-name">Sacs  Totes</div>
    </div>
    <div class="cat-card" style="background:linear-gradient(135deg,#f0f8ee,#d4eadc)"
         onclick="window.location.href='catalogue.html?cat=flower'">
      <div class="cat-icon">🌸</div><div class="cat-name">Fleurs</div>
    </div>
    <div class="cat-card" style="background:linear-gradient(135deg,#faeeda,#f5d4a0)"
         onclick="window.location.href='catalogue.html?cat=keychain'">
      <div class="cat-icon">🧸</div><div class="cat-name">Porte-clés</div>
    </div>
    <div class="cat-card" style="background:linear-gradient(135deg,#fdf0f5,#fde4ef)"
         onclick="window.location.href='catalogue.html?cat=Accessories'">
      <div class="cat-icon">🎀</div><div class="cat-name">Accessoires</div>
    </div>
    <div class="cat-card" style="background:linear-gradient(135deg,#e8f4fb,#c8e4f5)"
         onclick="window.location.href='bouquet.html'">
      <div class="cat-icon">💐</div><div class="cat-name">Bouquets Perso</div>
    </div>
  </div>
</section>
<section style="padding:3rem 2rem;text-align:center;background:var(--cream)">
    <h2 class="section-title">Pourquoi Gharbouch ?</h2>
    <p class="section-sub">Chaque pièce est unique, imaginée et réalisée avec soin</p>
    <div  style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:20px;max-width:800px;margin:0 auto">
        <div style="background:#fff;border-radius:16px;padding:24px;border:1px solid #f0e0ea">
            <div style="font-size:32px;margin-bottom:11px">🤍</div>
            <div style="font-weight:500;margin-bottom:6px;font-size:14px">Fait main</div>
            <div style="color:var(--muted);font-size:13px">Chaque article est tricoté ou brodé à la main avec amour</div>
        </div>

        <div style="background:#fff;border-radius:16px;padding:24px;border:1px solid #f0e0ea">
            <div style="font-size:32px;margin-bottom:11px">✨</div>
            <div style="font-weight:500;margin-bottom:6px;font-size:14px">Personnalisable</div>
            <div style="color:var(--muted);font-size:13px">Couleurs, taille, message — c'est toi qui choisis</div>
        </div>

        <div style="background:#fff;border-radius:16px;padding:24px;border:1px solid #f0e0ea">
            <div style="font-size:32px;margin-bottom:11px">🎁</div>
            <div style="font-weight:500;margin-bottom:6px;font-size:14px">Cadeau parfait</div>
            <div style="color:var(--muted);font-size:13px">Emballage cadeau soigné inclus pour toute commande</div>
        </div>
    </div>
</section>
<div class="toast" id="toast"></div>
<?php require_once __DIR__ . '/back/footer.php';
?>