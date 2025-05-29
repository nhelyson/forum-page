<?php
include 'connexion-db.php';
include 'session.php';
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_FILES['profile']['tmp_name']) && !isset($_SESSION['user']['id'])) {
        $response['message'] = 'Fichier ou session manquant';
        echo json_encode($response);
        exit();
    }

    $file = $_FILES['profile'];
    $file_name = $file['name'];
    $file_tmp = $file['tmp_name'];
    $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
    $file_base = pathinfo($file_name, PATHINFO_FILENAME);
    $file_emplacement =  'img_profile/';
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
    $new_name = uniqid() . '_' . $file_base . '.' . $file_ext;
    $upload_path = $file_emplacement . $new_name;

    if (move_uploaded_file($file_tmp, $upload_path)) {
        $id = $_SESSION['user']['id'];

        $stmt = $pdo->prepare("UPDATE users SET img_profile = ? WHERE id = ?");
        $stmt->execute([$new_name, $id]);

        $_SESSION['user']['img_profile'] = $new_name;

         echo json_encode([
            'succes' => true,
            'img_profile' => $_SESSION['user']['img_profile'],
         ]);

    } else {
        $response['message'] = 'Échec du déplacement du fichier.';
        echo json_encode($response);
        exit();
    }
}


