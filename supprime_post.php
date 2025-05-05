<?php 
include 'connexion-db.php';
include 'session.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
  $post_supprime = htmlspecialchars($_POST['supprime_post']);
  $users_post_supprime = $_SESSION['user']['id'];
  if(isset($user_post_supprime , $post_supprime) && $post_supprime !== ''){
    $supprime = $pdo->prepare("DELETE FROM post WHERE id_forum = ? AND id_users = ?");
    $supprime->execute([$post_supprime , $users_post_supprime]);
    header("location: index.php?supprime = poste_supprime");
    exit();
  }
}