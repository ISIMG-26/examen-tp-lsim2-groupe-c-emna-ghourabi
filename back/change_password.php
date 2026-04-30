<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Database credentials (consider moving to a config file)
$host = "localhost";
$user = "root";
$password = "";
$database = "gharbouch";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// 1. Session Security
if (!isset($_SESSION['user_id'])) {
    die("Utilisateur non connecté.");
}

$user_id = $_SESSION['user_id'];
$old_pass = trim($_POST['old_pass'] ?? '');
$new_pass = trim($_POST['new_pass'] ?? '');
$confirm_pass = trim($_POST['confirm_pass'] ?? '');

// 2. Validation
if (empty($old_pass) || empty($new_pass) || empty($confirm_pass)) {
    die("Tous les champs sont obligatoires.");
}

if ($new_pass !== $confirm_pass) {
    die("Les nouveaux mots de passe ne correspondent pas.");
}

if (strlen($new_pass) < 6) {
    die("Le mot de passe doit contenir au moins 6 caractères.");
}

// 3. Fetch current hash
$stmt = mysqli_prepare($conn, "SELECT mot_de_passe FROM utilisateurs WHERE id = ?");
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$row = mysqli_fetch_assoc($result)) {
    mysqli_stmt_close($stmt);
    die("Utilisateur introuvable.");
}

$hashed_password = $row['mot_de_passe'];
mysqli_stmt_close($stmt);

// 4. Verification
if (!password_verify($old_pass, $hashed_password)) {
    die("Ancien mot de passe incorrect.");
}

// Optional: Prevent changing to same password
if (password_verify($new_pass, $hashed_password)) {
    die("Le nouveau mot de passe doit être différent de l'ancien.");
}

// 5. Update
$new_hash = password_hash($new_pass, PASSWORD_DEFAULT);
$stmt = mysqli_prepare($conn, "UPDATE utilisateurs SET mot_de_passe = ? WHERE id = ?");
mysqli_stmt_bind_param($stmt, 'si', $new_hash, $user_id);

if (mysqli_stmt_execute($stmt)) {
    echo "Mot de passe modifié avec succès.";
} else {
    echo "Erreur lors de la mise à jour : " . mysqli_error($conn);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>