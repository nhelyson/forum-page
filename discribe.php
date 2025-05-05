<?php
session_start();
include 'connexion-db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user']['id'])) {
    echo json_encode(['success' => false, 'error' => 'Utilisateur non connecté']);
    exit;
}

$user_id = $_SESSION['user']['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $describe = filter_var(trim($_POST['discribe'] ?? ''), FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if ($describe !== '') {
        $stmt = $pdo->prepare("UPDATE users SET description = ? WHERE id = ?");
        $stmt->execute([$describe, $user_id]);
    }
}

// Récupération
$stmt = $pdo->prepare("SELECT description FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$describe_stocker = $stmt->fetchColumn();

if (is_string($describe_stocker) && trim($describe_stocker) !== '') {
    $describe_content = htmlspecialchars_decode($describe_stocker);
} else {
    $describe_content = 'Cette bio est en vacances... revenez plus tard ! 🏖️';
}

echo json_encode([
    'success' => true,
    'content' => $describe_content
]);
