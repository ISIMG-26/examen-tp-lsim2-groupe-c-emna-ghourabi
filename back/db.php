<?php
// back/db.php — connexion à la base de données
error_reporting(0);        // ← زد هذا
ini_set('display_errors', 0);

$host = 'localhost';// Adresse du serveur MySQL — 'localhost' = même machine que PHP (XAMPP/WAMP)
$dbname = 'gharbouch';
$user = 'root';
$pass = '';

try {// Tente la connexion — si erreur, saute directement au catch
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    // Crée une connexion PDO (PHP Data Objects)
    // mysql:         → driver MySQL
    // host=$host     → serveur localhost
    // dbname=$dbname → base de données gharbouch
    // charset=utf8mb4 → encodage qui supporte les emojis et caractères arabes
    // $user, $pass   → identifiants de connexion
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
     // Configure PDO pour lancer une exception (PDOException) à chaque erreur SQL
    // Sans ça, les erreurs passent silencieusement sans qu'on le sache
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    // Configure fetchAll() pour retourner des tableaux associatifs par défaut
    // ex: $p['nom'] au lieu de $p[0]
    } catch (PDOException $e) {// Exécuté si la connexion échoue (mauvais mot de passe, DB inexistante...)
    http_response_code(500);
    die('Connexion échouée : ' . $e->getMessage());
    // Affiche le message d'erreur exact et arrête tout le script
    // $e->getMessage() → ex: "Access denied for user root"
}
/* C'est une interface PHP pour communiquer avec une base de données.
$pdo->query()    // exécuter une requête simple
$pdo->prepare()  // préparer une requête sécurisée
$pdo->execute()  // exécuter la requête préparée
$pdo->fetchAll() // récupérer tous les résultats
$pdo->fetch()    // récupérer un seul résultat*/
?>
