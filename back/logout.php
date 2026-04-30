<?php
session_start();
session_destroy(); // Détruit la session côté serveur
header('Location: /gharbouch/html/connexion.php'); // Redirige vers connexion
exit;
?>
<script>
  localStorage.removeItem('gharbouch_user'); // ← يمسح من localStorage
  localStorage.removeItem('gharbouch_cart');
  window.location.href = '/gharbouch/html/connexion.php';
</script>