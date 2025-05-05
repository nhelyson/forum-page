<?php
// Configuration
$host = 'localhost';       
$username = 'root';      
$password = '';            

// Connexion avec PDO
try {
    $pdo = new PDO("mysql:host=$host;dbname=forum;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Afficher un message en cas de succès
    // echo "Connexion réussie !";
} catch (PDOException $e) {
    // En cas d'erreur
    die("Erreur de connexion : " . $e->getMessage());
}
?>
