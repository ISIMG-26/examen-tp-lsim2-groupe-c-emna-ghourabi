<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = "localhost";
$user = "root";
$password = "";
$database = "gharbouch";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Vérifier session
if (!isset($_SESSION['user_id'])) {
    die("Utilisateur non connecté.");
}

$user_id = $_SESSION['user_id'];
$del_pass = trim($_POST['del_pass'] ?? '');

// Vérifier mot de passe vide
if (empty($del_pass)) {
    die("Veuillez entrer votre mot de passe.");
}

// Récupérer mot de passe hashé
$stmt = mysqli_prepare($conn, "SELECT mot_de_passe FROM utilisateurs WHERE id = ?");
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$row = mysqli_fetch_assoc($result)) {
    die("Utilisateur introuvable.");
}

$motdepasse = $row['mot_de_passe'];
mysqli_stmt_close($stmt);

// Vérification mot de passe
if (password_verify($del_pass, $motdepasse)) {

    $stmt = mysqli_prepare($conn, "DELETE FROM utilisateurs WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    session_destroy();

    echo "Votre compte a été supprimé avec succès.";

} else {
    echo "Le mot de passe est incorrect.";
}
?>