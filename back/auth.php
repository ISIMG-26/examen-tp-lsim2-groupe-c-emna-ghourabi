<?php
session_start();  
ob_start();
error_reporting(0);
ini_set('display_errors', 0);
header('Content-Type: application/json');
require_once 'db.php';
ob_clean();
// back/auth.php — inscription et connexion

$action = isset($_POST['action']) ? $_POST['action'] : '';

// ---- INSCRIPTION ----
if ($action === 'register') {
    $prenom = trim($_POST['prenom'] ?? '');
    $email  = trim($_POST['email'] ?? '');
    $pass   = $_POST['mot_de_passe'] ?? '';

    if (!$prenom || !$email || !$pass) {
        echo json_encode(['success' => false, 'error' => 'Tous les champs sont requis.']);
        exit;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'error' => 'Email invalide.']);
        exit;
    }
    if (strlen($pass) < 8) {
        echo json_encode(['success' => false, 'error' => 'Mot de passe trop court (min. 8 caractères).']);
        exit;
    }

    // Vérifier email existant
    $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'error' => 'Cet email est déjà utilisé.']);
        exit;
    }

    $hash = password_hash($pass, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO utilisateurs (prenom, email, mot_de_passe) VALUES (?, ?, ?)");
    $stmt->execute([$prenom, $email, $hash]);

    echo json_encode(['success' => true, 'message' => 'Compte créé ! Bienvenue chez Gharbouch 🌸', 'prenom' => $prenom]);
    exit;
}

// ---- CONNEXION ----
if ($action === 'login') {
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['mot_de_passe'] ?? '';

    if (!$email || !$pass) {
        echo json_encode(['success' => false, 'error' => 'Email et mot de passe requis.']);
        exit;
    }

    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($pass, $user['mot_de_passe'])) {
        echo json_encode(['success' => false, 'error' => 'Email ou mot de passe incorrect.']);
        exit;
    }
    session_start();
    $_SESSION['user_id']   = $user['id'];
    $_SESSION['user_name'] = $user['prenom'];


    echo json_encode(['success' => true, 'message' => 'Bienvenue ' . $user['prenom'] . ' ! 🌸', 'prenom' => $user['prenom'], 'id' => $user['id']]);
    exit;
}

// ---- VÉRIFICATION EMAIL (AJAX live) ----
if ($action === 'check_email') {
    $email = trim($_POST['email'] ?? '');
    $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);
    $exists = $stmt->fetch() ? true : false;
    echo json_encode(['exists' => $exists]);
    exit;
}

echo json_encode(['success' => false, 'error' => 'Action inconnue.']);
?>
