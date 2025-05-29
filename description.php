<?php
include 'connexion-db.php';
include 'session.php';
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $describe = filter_var(trim($_POST['discribe'] ?? ''), FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if ($describe !== '') {
        $stmt = $pdo->prepare("UPDATE users SET description = ? WHERE id = ?");
        $stmt->execute([$describe, $_SESSION['user']['id']]);
    }
 
}

$describe_stocker = '';

$stmt = $pdo->prepare("SELECT description FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user']['id']]);
$describe_stocker = $stmt->fetchColumn();
  if(!empty($describe_stocker)){
  echo json_encode([
    'succes' => true,
    'describe' => $describe_stocker
   ]);
   exit;
 }else{
    echo json_encode([
        'succes' => false,
        'describe' => 'Desole nous avons pas pu mettre a jour votre description'
    ]);
    exit;
}

?>