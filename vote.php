<?php 
include 'session.php';
include 'connexion-db.php';
// like et dislike 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id_post = (int) htmlspecialchars($_POST['id_post']);
    $vote = (int) htmlspecialchars($_POST['vote']);
    $user_id = $_SESSION['user']['id'];

    if (!in_array($vote, [1, -1])) {
        echo "Vote invalide.";
        exit;
    }

    if ($vote === 1) {

        $stmt = $pdo->prepare("DELETE FROM dislike WHERE post_id = ? AND user_id = ?");
        $stmt->execute([$id_post, $user_id]);  
    } elseif ($vote === -1) {
        $stmt = $pdo->prepare("DELETE FROM likes WHERE post_id = ? AND user_id = ?");
        $stmt->execute([$id_post, $user_id]);
    }

    $table = ($vote === 1) ? 'likes' : 'dislike';
    $column = ($vote === 1) ? 'vote_like' : 'vote_dislike';

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
    
    header("location: index.php?vote = success");
}