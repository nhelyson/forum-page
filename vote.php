<?php 
include 'session.php';
include 'connexion-db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Utilisateur non connecté.'
    ]);
    exit;
}

$user_id = $_SESSION['user']['id'];
$id_post = isset($_GET['id_post']) ? (int) $_GET['id_post'] : 0;

if (isset($_GET['vote']) && $id_post > 0) {
    $vote = (int) $_GET['vote'];

    if (!in_array($vote, [1, -1], true)) {
        echo json_encode([
            'success' => false,
            'message' => 'Vote invalide.'
        ]);
        exit;
    }

    if ($vote === 1) {
        $pdo->prepare("DELETE FROM dislike WHERE post_id = ? AND user_id = ?")
            ->execute([$id_post, $user_id]);
    } else {
        $pdo->prepare("DELETE FROM likes WHERE post_id = ? AND user_id = ?")
            ->execute([$id_post, $user_id]);
    }

    $table = $vote === 1 ? 'likes' : 'dislike';
    $column = $vote === 1 ? 'vote_like' : 'vote_dislike';

    $stmt = $pdo->prepare("SELECT $column FROM $table WHERE post_id = ? AND user_id = ?");
    $stmt->execute([$id_post, $user_id]);
    $vote_existant = $stmt->fetchColumn();

    if ($vote_existant == $vote) {

        $stmt = $pdo->prepare("DELETE FROM $table WHERE post_id = ? AND user_id = ?");
        $stmt->execute([$id_post, $user_id]);
    } elseif ($vote_existant === false) {

        $stmt = $pdo->prepare("INSERT INTO $table (user_id, post_id, $column) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $id_post, $vote]);
    }

    $like_count = $pdo->query("SELECT COUNT(*) FROM likes WHERE post_id = $id_post")->fetchColumn();
    $dislike_count = $pdo->query("SELECT COUNT(*) FROM dislike WHERE post_id = $id_post")->fetchColumn();

    echo json_encode([
        'success' => true,
        'like_count' => (int)$like_count,
        'dislike_count' => (int)$dislike_count
    ]);
    exit;
}

if ($id_post > 0) {
    if (isset($_GET['objet_like']) && $_GET['objet_like'] === 'like') {
        $count = $pdo->query("SELECT COUNT(*) FROM likes WHERE post_id = $id_post")->fetchColumn();
        echo json_encode([
            'success' => true,
            'like_count' => (int)$count
        ]);
        exit;
    }

    if (isset($_GET['objet_dislike']) && $_GET['objet_dislike'] === 'dislike') {
        $count = $pdo->query("SELECT COUNT(*) FROM dislike WHERE post_id = $id_post")->fetchColumn();
        echo json_encode([
            'success' => true,
            'dislike_count' => (int)$count
        ]);
        exit;
    }
}

echo json_encode([
    'success' => false,
    'message' => 'Paramètres manquants ou invalides.'
]);
exit;
