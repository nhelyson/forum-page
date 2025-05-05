<?php
$title = "Post";
include 'connexion-db.php';
include 'session.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titre_post = filter_var(trim($_POST['titre-post']), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $category_post = filter_var(trim($_POST['select-categorie']),FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $content = filter_var(trim($_POST['content-post']), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $id_user = $_SESSION['user']['id'];
    if (isset($_FILES['file_input']) && $_FILES['file_input']['error'] == 0) {
        $file_img = $_FILES['file_input'];
        $file_name = $file_img['name'];
        $file_tmp = $file_img['tmp_name'];
        $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
        $file_base = pathinfo($file_name, PATHINFO_FILENAME);
        $file_emplacement = 'img_post/';
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif',];
        if (in_array($file_ext, $allowed_extensions)) {
            $news_name = uniqid() . '_' . $file_base . '.' . $file_ext;
            $upload_path = $file_emplacement . $news_name;

            if (move_uploaded_file($file_tmp, $upload_path)) {
                $stmt = $pdo->prepare("INSERT INTO post (id_users, image_forum, titre_content, content, categories) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$id_user, $news_name, $titre_post, $content, $category_post]);
                header('Location: index.php?post=success');
                exit();
            } else {
                echo "Échec du téléchargement de l'image.";
            }
        } else {
            echo "Extension de fichier non autorisée.";
        }
    } else  {
        // Cas sans image
        $stmt = $pdo->prepare("INSERT INTO post (id_users, image_forum, titre_content, content, categories) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$id_user, 'vide', $titre_post, $content, $category_post]);
        header('Location: index.php?post=success');
        exit();
    } 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Post</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="post.css">
</head>
   <body>
        <div class="container">
        <form method="post" enctype="multipart/form-data">
        <div class="row mt-5 align-items-start me-auto">
        <div class="col-7 mx-auto">
        <div class=" d-flex">
          <h5 class="mt-3">Créer une discussion</h5>
          <button type="submit" class="btn btn-warning ms-auto mt-3">publier</button>
        </div>
        <div class="post mt-5">
            <div class="row">
              <div class="col">
                <input type="text" id="titre" name="titre-post" class="form-control fs-1" placeholder="Titre" required>
                <select id="category" name="select-categorie" class="form-control mt-5">
                  <option value="Nature">Nature</option>
                  <option value="quotidien">quotidien</option>
                  <option value="Science">Science</option>
                  <option value="Technologie">Technologie</option>
                  <option value="Anime">Anime</option>
                  <option value="manga">manga</option>
                </select>
              </div>
              <div class="col">
                <div class="input_img position-relative" id="content-form">
                <div style="transform: translate(1.5rem,4rem);" id="format">format d'image jpp , png , jpeg , webp</div>
                <label for="input_file" class="position-absolute label-file" style="top:50%!important;left:50%;transform:translate(-50%,-40%);cursor:pointer;">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="#000000" width="50px" height="50px" viewBox="0 -1.5 35 35" version="1.1">
                  <title>upload1</title>
                  <path d="M29.426 15.535c0 0 0.649-8.743-7.361-9.74-6.865-0.701-8.955 5.679-8.955 5.679s-2.067-1.988-4.872-0.364c-2.511 1.55-2.067 4.388-2.067 4.388s-5.576 1.084-5.576 6.768c0.124 5.677 6.054 5.734 6.054 5.734h9.351v-6h-3l5-5 5 5h-3v6h8.467c0 0 5.52 0.006 6.295-5.395 0.369-5.906-5.336-7.070-5.336-7.070z"/>
                  </svg>
                </label>
                <input type="file" id="input_file" name="file_input" class="form-file" accept="image/png, image/jpeg, image/gif, image/webp"  placeholder="" hidden>
                </div>
                <div id="name_file"></div>
              </div>
            </div>
            <textarea id="textrea" name="content-post" class="form-control mt-5" cols="30" rows="10" style="resize: none;" required placeholder="Écrivez votre contenu ici..."></textarea>
        </div>
      </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
    <script src="form.js"></script>
</body>
</html>
