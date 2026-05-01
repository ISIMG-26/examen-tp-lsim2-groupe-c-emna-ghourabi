<?php
session_start();
session_destroy(); // Détruit la session côté serveur
header('Location: /gharbouch/html/connexion.php'); // Redirige vers connexion
exit;
?>
