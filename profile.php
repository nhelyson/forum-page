<?php 
$title = 'profile';
include 'connexion-db.php';
include 'session.php';
if(isset($_GET['id']) && !empty($_GET['id'])){
    $id_profile = $_GET['id'];
    $profile = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $profile->execute([$id_profile]);
    $info_profile = $profile->fetch(PDO::FETCH_ASSOC);
}
if(isset($_SESSION['user'] , $_GET['id']) && !empty($_SESSION['user']['id']) && !empty($_GET['id'])){
  $id_users_post = $_GET['id'];
  $post_user = $pdo->prepare("
               SELECT users.id,
               users.img_profile ,
               users.username, 
               users.sexe, 
               post.id_forum , 
               post.id_users , 
               post.image_forum,
               post.titre_content, 
               post.content , 
               post.categories,
               creation_post FROM users
               JOIN post ON 
               users.id = post.id_users 
               WHERE id_users = ?
               ORDER BY  creation_post DESC 
                               ");
  $post_user->execute([ $id_users_post]);
  $post_result = $post_user->fetchAll(PDO::FETCH_ASSOC);

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Asap&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Asap&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-white bg-white">
    <div class="container">
        <button class="btn btn-white circle border text-center" id="return">
<svg xmlns="http://www.w3.org/2000/svg" class="position-relative" style="left: 50%!important;right: 50%!important; transform: translate(-50%,-30%)!important;" width="25px" height="25px" viewBox="0 0 24 24" fill="none">
<path d="M6 12H18M6 12L11 7M6 12L11 17" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
</button>

<div class="me-auto ms-3 p-2" style="border-left: 2px solid silver!important;">
    compte id 
    <?php if(isset($_GET['id']) && !empty($_GET['id'])) : ?>
    <?php 
    $id = $_GET['id'];
    ?>
    <span class="text-dark"><?php echo $id ?></span>
    <?php endif ?>
</div>
        <a href="" class="navbar-brand px-3 fs-1 border rounded-3 " style="font-family: 'Asap'!important;font-weight:700!important">Profile</a>
        </div>
    </nav>
 <div class="container mt-5">
<div class="w-100  background" style="height:14rem;">
<?php if (isset($info_profile) && !empty($info_profile)): ?>
    <?php 
        $img_profile = $info_profile['img_profile'] ?? null;
        $sexe = htmlspecialchars($info_profile['sexe'] ?? '');
    ?>

    <?php if (!empty($img_profile)): ?>
        <img src="img_profile/<?php echo htmlspecialchars($img_profile); ?>" 
             class="back" 
             alt="Photo de profil" 
            id="back-image"> 
    <?php elseif ($sexe === "Homme"): ?>
        <img src="img-profile-defaut/homme.png" 
             class="back" 
             alt="Photo de profil par défaut - Homme" 
             id="back-image">

    <?php elseif ($sexe === "Femme"): ?>
        <img src="img-profile-defaut/femme.jpg" 
             class="back" 
             alt="Photo de profil par défaut - Femme" 
             id="back-image">

    <?php else: ?>
        <img src="img-profile-defaut/defaut.jpg" 
             class="back" 
             alt="Photo de profil par défaut - Inconnu" 
             id="back-image">
    <?php endif; ?>
<?php endif; ?>

</div>
<div class="positon-relative">
<?php if (isset($info_profile) && !empty($info_profile)): ?>
    <?php 
        $img_profile = $info_profile['img_profile'] ?? null;
        $sexe = htmlspecialchars($info_profile['sexe'] ?? '');
    ?>
                <?php if (isset($img_profile) && !empty($img_profile) ): ?>
               <img src="img_profile/<?= htmlspecialchars($img_profile) ?>?v=<?= time() ?>" 
                class="profile-img mx-auto position-relative" 
                alt="Photo de profil"
                id="imagePreview">
                <?php elseif (isset($sexe) && $sexe == "Homme"): ?>
                <img src="img-profile-defaut/homme.png" 
                class="profile-img mx-auto position-relative" 
                alt="Photo de profil par défaut - Homme" 
                id="imagePreview">
                <?php elseif (isset($sexe) && $sexe == "Femme"): ?>
                <img src="img-profile-defaut/femme.jpg" 
                class="profile-img mx-auto position-relative" 
                alt="Photo de profil par défaut - Femme" 
                id="imagePreview">
                 <?php else: ?>
                 <img src="img-profile-defaut/defaut.jpg" 
                class="profile-img mx-auto position-relative" 
                alt="Photo de profil par défaut - Inconnu" 
                id="imagePreview">
                <?php endif; ?>
                <?php endif;?>
                 </div>
<?php if (isset($info_profile) && !empty($info_profile)): ?>
    <?php 
        $nom = htmlspecialchars($info_profile['nom']);
        $email = htmlspecialchars($info_profile['email'] ?? '');
        $prenom = htmlspecialchars($info_profile['prenom'])
    ?>
<div class="position-relative" style="top: 1rem;">
        <h4 class="name-users" style="font-family: 'Asap', sans-serif!important;"><?php echo $nom ?> <span><?php echo  $prenom?></span></h4>
        <p class="position-relative" style="top:-0.3rem;"><?php echo $email ?></p>
    </div>
    <?php endif ?>
    <?php if (isset($info_profile) && !empty($info_profile)): ?>
    <?php 
    $description = (isset($info_profile['description']) && !empty($info_profile['description'])) 
    ? htmlspecialchars($info_profile['description']) 
    : 'Cette bio est en vacances... revenez plus tard ! 🏖️';
    ?>
      <p class="text-dark query mt-5" style="font-size: 0.8rem;width:18rem;font-family: 'Asap', sans-serif!important;"><?php  echo htmlspecialchars_decode($description) ?></p>
    <?php endif ?>
    <?php if (isset($info_profile) && !empty($info_profile)): ?>
    <?php 
    $username = htmlspecialchars($info_profile['username']);
    $email = htmlspecialchars($info_profile['email']);
    $pays = htmlspecialchars($info_profile['pays_user']);
    $etablissement = htmlspecialchars($info_profile['etablissement']);
    $cours = htmlspecialchars($info_profile['metier'])
    ?>
    <div class="row row-cols-lg-5 row-cols-md-1 row-cols-1 position-relative" style="top:6rem!important">
                    <ul class="col">
                            <h5 style="color:#333!important">pseudo</h5>
                            <li class="mt-2 details"><?php echo $username ?></li>
                        </ul>
                        <ul  class="col">
                            <h5 style="color:#333">Email</h5>
                            <li class="mt-2 details"><?php echo $email ?></li>
                        </ul>
                        <ul class="col">
                        <h5 style="color:#333!important">pays</h5>
                        <li class="mt-2 details"><?php echo $pays?></li>
                        </ul>
                        <ul  class="col">
                        <h5 style="color:#333!important">metier</h5>
                        <li class="mt-2 details"><?php echo $cours ?></li>
                        </ul>
                        <ul  class="col">
                        <h5 style="color:#333!important">universite</h5>
                        <li class="mt-2 details"><?php echo $etablissement ?></li>
                        </ul>
                    </div>
        <?php endif ?>
        <?php if(!empty($post_result)): ?>
                        <?php foreach( $post_result as $post): ?>
                        <?php
                        $img_profile = $post['img_profile'];
                        $username = htmlspecialchars($post['username']);
                        $id_forum = htmlspecialchars($post['id_forum']);
                        $id_users_post = (int)htmlspecialchars($post[ 'id_users']);
                        $image_forum = $post['image_forum'];
                        $sexe = htmlspecialchars($post['sexe']);
                        $img_profile = $post['img_profile'];
                        $titre_content = $post['titre_content'];
                        $content = htmlspecialchars($post['content']);
                        $categories = htmlspecialchars($post['categories']);
                        $date_post = $post['creation_post'];
                        $date = new DateTime($date_post);
                        if (!function_exists('ago')) {
                            function ago($date) {
                                $date_form = strtotime($date->format('Y-m-d H:i:s'));
                                $diff  = time() - $date_form;
                        
                                if ($diff < 1) {
                                    return "à l'instant";
                                }
                        
                                $sec = array(
                                    31556926 => 'an',
                                    "2629743.83" => 'mois',
                                    86400 => 'jour',
                                    3600 => 'heure',
                                    60 => 'minute',
                                    1 => 'seconde'
                                );
                        
                                foreach ($sec as $sec_value => $label) {
                                    $div = $diff / $sec_value;
                                    if ($div >= 1) {
                                        $time_ago = round($div) . ' ' . $label;
                                        return "il y a " . $time_ago;
                                    }
                                }
                            }
                        }
                        ?>
                         <div class="card border-0 position-relative mt-5" style="top: 10rem!important;">
                            <div class="card-body">
                                <div class="card-title d-flex flex-column">
                                    <div class="users-forum d-flex flex-row">
                                       <div class="users-photo-messages">
                                        <?php if(isset($img_profile) && !empty($img_profile)): ?>
                                        <img src="img_profile/<?php echo  $img_profile ?>" class="img" alt="<?php echo htmlspecialchars($img_profile) ?>">
                                        <?php elseif(isset($sexe) && $sexe == "Homme"): ?>
                                            <img src="img-profile-defaut/homme.png" class="img" alt="homme.jpg">
                                            <?php elseif(isset($sexe) && $sexe == "femme"): ?>
                                            <img src="img-profile-defaut/femme.jpg" class="img" alt="femme.jpg">
                                            <?php else: ?>
                                            <img src="img-profile-defaut/defaut.jpg ?>" class="img" alt="defaut.jpg">
                                         <?php endif ?>
                                         </div>
                                        <p class="ms-3 name-users" style="font-family: Asap !important;"><?php echo $username ?></p>
                                        <span class="ms-2" style="color: #5d5d6c!important;"><?php echo ago($date) ?></span>
                                        <?php if($id_users_post) : ?>
                                        <?php if( $id_users_post === $_SESSION['user']['id']): ?>
                                        <button type="bouton" class="btn btn-primary ms-auto mb-4 text-center" style="height:30px;font-size:0.8rem;">supprrime</button>
                                        <?php else: ?>
                                        <?php endif ?>
                                        <?php endif ?>
                                    </div>
                                    <div class="mt-3">
                                    <h5 class="color_post" style="font-family: Asap !important;font-weight:900"><?php echo htmlspecialchars_decode($titre_content) ?></h5>
                                    <p class="paragraphs_content_profile" style="font-family: Asap !important;"><?php echo htmlspecialchars_decode ($content) ?></p>
                                    </div>
                                    <?php if(isset($post['image_forum']) && !empty($post['image_forum']) && $post['image_forum'] !== null): ?>
                                    <img src="img_post/<?php echo htmlspecialchars($image_forum )?>" class="img rounded-3" alt="">
                                    <?php else : ?>
                                        <img src="img_post/ null" class="img rounded-3" alt="">
                                    <?php endif ?>
                                    </div>
                                    <div class="card-footer bg-transparent d-flex flex-row gap-2 border-0 position-relative" style="left:-1.2rem">  
                                   <form method="POST" action="vote.php">
                                     <input type="hidden" name="id_post" value="<?php echo $id_forum ?>">
                                     <input type="hidden" name="vote" value="1">
                                        <button type="submit" class="btn btn-white rounded-2 enjoy">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 24 24" fill="none">
                                            <path d="M8 10V20M8 10L4 9.99998V20L8 20M8 10L13.1956 3.93847C13.6886 3.3633 14.4642 3.11604 15.1992 3.29977L15.2467 3.31166C16.5885 3.64711 17.1929 5.21057 16.4258 6.36135L14 9.99998H18.5604C19.8225 9.99998 20.7691 11.1546 20.5216 12.3922L19.3216 18.3922C19.1346 19.3271 18.3138 20 17.3604 20L8 20" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                      <?php
                                     $stmt = $pdo->prepare("SELECT COUNT(vote_like) FROM likes WHERE post_id = ?");
                                     $stmt->execute([$id_forum]);
                                     $like_count = (int) $stmt->fetchColumn();
                                     ?>
                                           
                                    <span class="compte like-dark" style="font-weight: 700;"><?php echo $like_count; ?></span>
                                    </button>
                                    </form>
                                   <form method="POST" action="vote.php">
                                    <input type="hidden" name="id_post" value="<?php echo $id_forum ?>">
                                    <input type="hidden" name="vote" value="-1">
                                   <button type="submit" class="btn btn-white enjoy">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 24 24" fill="none" transform="matrix(1, 0, 0, -1, 0, 0)">
                
                                            <g id="SVGRepo_bgCarrier" stroke-width="0"/>
                                            
                                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"/>
                                            
                                            <g id="SVGRepo_iconCarrier"> <path d="M8 10V20M8 10L4 9.99998V20L8 20M8 10L13.1956 3.93847C13.6886 3.3633 14.4642 3.11604 15.1992 3.29977L15.2467 3.31166C16.5885 3.64711 17.1929 5.21057 16.4258 6.36135L14 9.99998H18.5604C19.8225 9.99998 20.7691 11.1546 20.5216 12.3922L19.3216 18.3922C19.1346 19.3271 18.3138 20 17.3604 20L8 20" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> </g>
                                            
                                            </svg>
                                            </svg>
                                      <?php
                                     $stmt = $pdo->prepare("SELECT COUNT(vote_dislike) FROM dislike WHERE post_id = ?");
                                     $stmt->execute([$id_forum]);
                                     $like_count_dislke = (int) $stmt->fetchColumn();
                                     ?>
                                           
                                    <span class="compte like-dark" style="font-weight: 700;"><?php echo $like_count_dislke; ?></span>
                                    </button>
                                    </form>
                                    <a href="message.php?id_post_comment=<?php echo $id_forum ?>">
                                    <button type="bouton" class="btn btn-white enjoy">
                                        <span class="compte like-dark"></span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 24 24" fill="none">
                                        <path d="M21.0039 12C21.0039 16.9706 16.9745 21 12.0039 21C9.9675 21 3.00463 21 3.00463 21C3.00463 21 4.56382 17.2561 3.93982 16.0008C3.34076 14.7956 3.00391 13.4372 3.00391 12C3.00391 7.02944 7.03334 3 12.0039 3C16.9745 3 21.0039 7.02944 21.0039 12Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        <?php 
                                    //REcuperation du nombre de commentaire sur une publication
                                    $commentaire_recup = $pdo->prepare("SELECT COUNT(*) FROM commentaire where id_forum = ?");
                                    $commentaire_recup->execute([$id_forum]);
                                    $commentaire_count = (int) $commentaire_recup->fetchColumn();
                                    ?>
                                    <span class="compte like-dark" style="font-weight: 700;"><?php echo $commentaire_count; ?></span>
                                    </button>
                                    </a>
                            </div>
                            </div>
                        </div>
                        <?php endforeach;?>
                        <?php else: ?>

                            <div class="fs-4 w-100 border position-relative text-center rounded-3 p-5" style="top:15rem!important">Aucune publication trouveé</div>
                            <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="script.js" defer></script>
<script src="ajax.js"></script>
</body>
</html>