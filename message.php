 <?php
 $title = "Message";
 include 'connexion-db.php';
 include 'session.php';
 if(isset($_GET['id_post_comment']) && !empty($_GET['id_post_comment'])){
   $id_post_comment = htmlspecialchars($_GET['id_post_comment']);
    $message = $pdo->prepare("
              SELECT users.id,
               users.img_profile ,
               users.username,  
               post.id_forum , 
               post.id_users , 
               post.image_forum,
               post.titre_content, 
               post.content , 
               post.categories,
               creation_post 
               FROM users
               JOIN post ON 
               users.id = post.id_users 
                WHERE post.id_forum = ?
               "
 );
  $message->execute([$id_post_comment]);
  $post = $message->fetch(PDO::FETCH_ASSOC);
 }
 
 if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['commentaire'])) {

        $commentaire = filter_var(trim($_POST['commentaire']), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
       
        $id_post_comment =  isset($_GET['id_post_comment']) ? htmlspecialchars($_GET['id_post_comment']) : null;

        $id_user = $_SESSION['user']['id'] ?? null;

        if ($id_user) {

            $stmt = $pdo->prepare("INSERT INTO commentaire (users_commentaire, id_forum, content) VALUES (?, ?, ?)");
            $stmt->execute([$id_user, $id_post_comment, $commentaire]);
        } else {
            echo "Erreur : utilisateur non connecté.";
        }

    } 

}
if (isset($_GET['id_post_comment']) && !empty($_GET['id_post_comment'])) {
    $id_post_comment = htmlspecialchars($_GET['id_post_comment']);

    $message = $pdo->prepare("
        SELECT 
            users.id,
            users.img_profile ,
            users.username ,
            users.sexe,
            commentaire.users_commentaire,  
            commentaire.id_forum,
            commentaire.content,
            commentaire.date_commentaire
            FROM users
            JOIN commentaire ON users.id = commentaire.users_commentaire 
            WHERE commentaire.id_forum = ?
            ORDER BY date_commentaire DESC
    ");

    $message->execute([$id_post_comment]);
    $post_commentaire = $message->fetchAll(PDO::FETCH_ASSOC);
}
// like et dislike 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['vote']) && isset($_POST['id_post_comment'])) {

    $id_post = (int) $_POST['id_post_comment'];
    $vote = (int) $_POST['vote'];
    $user_id = $_SESSION['user']['id'] ?? null;

    if (!$user_id && empty($user_id)) {
        echo "Erreur : utilisateur non connecté.";
        exit;
    }

    if (!in_array($vote, [1, -1])) {
        echo "Vote invalide.";
        exit;
    }

    // Supprimer le vote opposé
    $opposite_table = ($vote === 1) ? 'dislike' : 'likes';
    $stmt = $pdo->prepare("DELETE FROM $opposite_table WHERE post_id = ? AND user_id = ?");
    $stmt->execute([$id_post, $user_id]);

    // Vérifier si le même vote existe déjà
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
}


 ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Asap&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="d-flex">
        <div class="container">
        <?php include 'navbar-left.php'; ?>

        <div class="row">
         <div class="col-lg-11 col-md-12 col-12 mt-5 mx-auto">
        <?php if(isset($post) && !empty($post)): ?>
            <?php 
             $username = htmlspecialchars($post['username']);
             $id_forum = htmlspecialchars($post['id_forum']);
             $id_users = htmlspecialchars($post['id_users']);
             $image_forum = $post['image_forum'];
             $img_profile = $post['img_profile'];
             $titre_content = htmlspecialchars($post['titre_content']);
             $content = htmlspecialchars($post['content']);
             $date_post = $post['creation_post'];
                        $date = new DateTime(  $date_post);
                        $date_now =  new DateTime();
                        $diff = $date_now->diff($date);
                        $resultat = '';
                        if ($diff->y > 0):
                          $s = $diff->y > 1 ? 'ans' : 'an';
                          $resultat = "il y a {$diff->y} $s";
                      
                      elseif ($diff->m > 0):
                          $s = $diff->m > 1 ? 'mois' : 'mois'; 
                          $resultat = "il y a {$diff->m} $s";
                      
                      elseif ($diff->d > 0):
                          $s = $diff->d > 1 ? 'jours' : 'jour';
                          $resultat = "il y a {$diff->d} $s";
                      
                      elseif ($diff->h > 0):
                          $s = $diff->h > 1 ? 'heures' : 'heure';
                          $resultat = htmlspecialchars("il y a {$diff->h} $s");
                      
                      elseif ($diff->i > 0):
                          $s = $diff->i > 1 ? 'minutes' : 'minute';
                          $resultat = "il y a {$diff->i} $s";
                      
                      else:
                          $resultat = "il y a quelques secondes";
                      endif;
             ?>
        <div class="card bg-light border-0 position-relative">
            <div class="card-body">
                <div class="card-title d-flex flex-column">
                    <div class="users-forum d-flex flex-row">
                    <div class="users-photo-messages">
                  <?php if(isset($img_profile) && !empty($img_profile)): ?>
                  <img src="img_profile/<?php echo  $img_profile ?>" class="img" alt="<?php echo htmlspecialchars($img_profile) ?>"></div>
                  <?php elseif(isset($_SESSION['user']['genre']) && $_SESSION['user']['genre'] == "Homme"): ?>
                     <img src="img-profile-defaut/homme.png ?>" class="img" alt="homme.jpg"></div>
                      <?php elseif(isset($_SESSION['user']['genre']) && $_SESSION['user']['genre'] == "femme"): ?>
                      <img src="img-profile-defaut/femme.jpg ?>" class="img" alt="femme.jpg"></div>
                      <?php else: ?>
                      <img src="img-profile-defaut/defaut.jpg ?>" class="img" alt="defaut.jpg"></div>
                   <?php endif ?>
                    <p class="ms-3 name-users" style="font-size: 1.5rem!important;"><?php echo $username ?><span></span></p>
                    <span class="ms-2 mt-2"><?php echo $resultat ?></span>
                    </div>
                    <h3 class="color_post mt-2"><?php echo htmlspecialchars_decode($titre_content) ?></h3>
                    <p class="paragraphs_content fs-lg-5"><?php echo htmlspecialchars_decode($content) ?></p>
                    <?php if(isset($post['image_forum']) && !empty($post['image_forum']) && $post['image_forum'] !== null): ?>
                     <img src="img_post/<?php echo htmlspecialchars($image_forum )?>" class="img rounded-3 mt-3" alt="">
                     <?php else : ?>
                         <img src="img_post/ null" class="img rounded-3" alt="">
                     <?php endif ?>
                    </div>
                </div>
                <div class="card-footer d-flex flex-row gap-2 bg-transparent border-0 position-relative" style="left:-0.4rem">   
                <form method="POST">
                 <input type="hidden" name="id_post_comment" value="<?php echo $id_forum ?>">
                 <input type="hidden" name="vote" value="1">
                    <button type="submit" class="btn btn-white enjoy">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 24 24" fill="none">
                            <path d="M8 10V20M8 10L4 9.99998V20L8 20M8 10L13.1956 3.93847C13.6886 3.3633 14.4642 3.11604 15.1992 3.29977L15.2467 3.31166C16.5885 3.64711 17.1929 5.21057 16.4258 6.36135L14 9.99998H18.5604C19.8225 9.99998 20.7691 11.1546 20.5216 12.3922L19.3216 18.3922C19.1346 19.3271 18.3138 20 17.3604 20L8 20" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <?php
                            $stmt = $pdo->prepare("SELECT COUNT(vote_like) FROM likes WHERE post_id = ?");
                            $stmt->execute([$_GET['id_post_comment']]);
                            $like_count = (int) $stmt->fetchColumn();
                            ?>
                    <span class="compte"><?php echo $like_count ?></span>
                    </button>
                    </form>
                    <form method="POST">
                    <input type="hidden" name="id_post_comment" value="<?php echo $id_forum ?>">
                    <input type="hidden" name="vote" value="-1">
                    <button type="bouton" class="btn btn-white enjoy">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 24 24" fill="none" transform="matrix(1, 0, 0, -1, 0, 0)">

                            <g id="SVGRepo_bgCarrier" stroke-width="0"/>
                            
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"/>
                            
                            <g id="SVGRepo_iconCarrier"> <path d="M8 10V20M8 10L4 9.99998V20L8 20M8 10L13.1956 3.93847C13.6886 3.3633 14.4642 3.11604 15.1992 3.29977L15.2467 3.31166C16.5885 3.64711 17.1929 5.21057 16.4258 6.36135L14 9.99998H18.5604C19.8225 9.99998 20.7691 11.1546 20.5216 12.3922L19.3216 18.3922C19.1346 19.3271 18.3138 20 17.3604 20L8 20" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> </g>
                            
                            </svg>
                            <?php
                             $stmt = $pdo->prepare("SELECT COUNT(vote_dislike) FROM dislike WHERE post_id = ?");
                             $stmt->execute([$id_post_comment]);
                             $like_count_dislke = (int) $stmt->fetchColumn();
                            ?>
                            <span class="compte"><?php echo  $like_count_dislke ?></span>
                    </button>
                    </form>
                    <button type="bouton" class="btn btn-white enjoy">
                        <span class="compte like-dark"></span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 24 24" fill="none">
                        <path d="M21.0039 12C21.0039 16.9706 16.9745 21 12.0039 21C9.9675 21 3.00463 21 3.00463 21C3.00463 21 4.56382 17.2561 3.93982 16.0008C3.34076 14.7956 3.00391 13.4372 3.00391 12C3.00391 7.02944 7.03334 3 12.0039 3C16.9745 3 21.0039 7.02944 21.0039 12Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
            </div>
            <?php endif ?>
            <div class="commentaire">
            <form method="POST" id="form-comment">
            <input type="text" id="comment-input" class="form-control mt-5 rounded-5" name="commentaire" placeholder="Ajouter un comentaire" style="height:3.5rem;">
            </form>
            </div>
            <div class="comment ms-2">
            <h3 class="mt-5">commentaires</h3>
            <?php if(isset($post_commentaire) && !empty($post_commentaire)): ?>
            <?php foreach($post_commentaire as $commentaire): ?>
                <?php 
                $img_profile = $commentaire['img_profile'];
                $username = ($commentaire['username'] ?? '');
                $genre = $commentaire['sexe'];
                $id_forum = $commentaire['id_forum'];
                $users_commentaire = $commentaire['users_commentaire'];
                $content = $commentaire['content'];
                $date_commentaire = $commentaire['date_commentaire'];
                $date_comment = new DateTime(  $date_commentaire);
                $date_now_comment =  new DateTime();
                $diff =  $date_now_comment->diff( $date_comment );
                $resultat = '';
                if ($diff->y > 0):
                  $s = $diff->y > 1 ? 'ans' : 'an';
                  $resultat = "il y a {$diff->y} $s";
              
              elseif ($diff->m > 0):
                  $s = $diff->m > 1 ? 'mois' : 'mois'; 
                  $resultat = "il y a {$diff->m} $s";
              
              elseif ($diff->d > 0):
                  $s = $diff->d > 1 ? 'jours' : 'jour';
                  $resultat = "il y a {$diff->d} $s";
              
              elseif ($diff->h > 0):
                  $s = $diff->h > 1 ? 'heures' : 'heure';
                  $resultat = htmlspecialchars("il y a {$diff->h} $s");
              
              elseif ($diff->i > 0):
                  $s = $diff->i > 1 ? 'minutes' : 'minute';
                  $resultat = "il y a {$diff->i} $s";
              
              else:
                  $resultat = "il y a quelques secondes";
              endif;
                ?>
                <div class="users-forum d-flex flex-row mt-5">
                    <div class="users-photo-forum" style="width:2.1rem !important;;height: 2rem !important;">
                    <?php if(isset($img_profile) && !empty($img_profile)): ?>
                  <img src="img_profile/<?php echo  $img_profile ?>" class="img" alt="<?php echo $img_profile ?>"></div>
                  <?php elseif(isset($genre) &&       $genre== "Homme"): ?>
                     <img src="img-profile-defaut/homme.png ?>" class="img" alt="homme.jpg"></div>
                      <?php elseif(isset($genre) &&   $genre  === "femme"): ?>
                      <img src="img-profile-defaut/femme.jpg ?>" class="img" alt="femme.jpg"></div>
                      <?php else: ?>
                      <img src="img-profile-defaut/defaut.jpg ?>" class="img" alt="defaut.jpg"></div>
                   <?php endif ?>
                     <p class="ms-4 name-users mt-2"><?php echo $username ?></p>
                     <span class="ms-2 mt-2"><?php echo $resultat ?></span>
                 </div>
                 <p id="content-commentaire" class="fs-5" style="width:36rem;font-family: Asap !important"><?php echo htmlspecialchars_decode($content) ?></p>
                 <button class="btn btn-transparent bouton-hover border-0 me-auto">
                    <span class="compte like-dark"></span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="15px" height="15px" viewBox="0 0 24 24" fill="none">
                    <path d="M21.0039 12C21.0039 16.9706 16.9745 21 12.0039 21C9.9675 21 3.00463 21 3.00463 21C3.00463 21 4.56382 17.2561 3.93982 16.0008C3.34076 14.7956 3.00391 13.4372 3.00391 12C3.00391 7.02944 7.03334 3 12.0039 3C16.9745 3 21.0039 7.02944 21.0039 12Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg><span class=" ms-2" style="text-transform: capitalize;font-size: 0.8rem;">repondre</span></button>
            <?php endforeach ?>
            <?php else : ?>
                <div class="fs-5 mx-auto text-center border p-5 mt-5 rounded-3" style="height: 10rem;">Pas de commentaire pour cette publiaction</div>
            <?php endif ?>
            </div>
            </div>
        </div>
    </div>
    </div>
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>
</html>