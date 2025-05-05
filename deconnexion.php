<?php 
include 'session.php';
session_start();
$_SESSION = array();
session_destroy();
header("location:connexion.php");
exit();
