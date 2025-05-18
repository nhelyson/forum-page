<?php
include 'connexion-db.php';

if (isset($_GET['input-search'])) {
    $keyword = '%' . $_GET['input-search'] . '%';

    $stmt = $pdo->prepare("SELECT * FROM post WHERE titre_content LIKE :keyword OR content LIKE :keyword
                                   ");
    $stmt->execute([':keyword' => $keyword]);

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($results);
    exit;
}