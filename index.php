<?php
include 'session.php';
include 'connexion-db.php';
include 'search.php';
if(isset($_SESSION['user'])  && !empty($_SESSION['user'])){
    $userId =htmlspecialchars($_SESSION['user']['id']);
    $nom = htmlspecialchars($_SESSION['user']['nom']);
    $prenom = htmlspecialchars($_SESSION['user']['prenom']);
    $username = htmlspecialchars($_SESSION['user']['username']);
    $email = htmlspecialchars($_SESSION['user']['email']);
    $genre = htmlspecialchars($_SESSION['user']['genre']);
    $etablissement = htmlspecialchars($_SESSION['user']['etablissement']);
    $cours = htmlspecialchars($_SESSION['user']['cours']);
    $pays = htmlspecialchars($_SESSION['user']['pays']);
    $date = htmlspecialchars($_SESSION['user']['date_enregistrement']);
    $role = htmlspecialchars($_SESSION['user']['role']);

     $timestamp = strtotime($date);

    $formatter = new IntlDateFormatter(
    'fr_FR',
    IntlDateFormatter::FULL,
    IntlDateFormatter::SHORT,
    'Europe/Paris',
    IntlDateFormatter::GREGORIAN
   );
 // On formate la date
  $dateFormatee = $formatter->format($timestamp);

 $dateFormatee = preg_replace('/^(\w+) /', '$1 le ', $dateFormatee);

     
  }
  else{
    if (!isset($_SESSION['user']) && isset($_COOKIE['remember_user_id'])) {
        $req = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $req->execute([$_COOKIE['remember_user_id']]);
        $user = $req->fetch(PDO::FETCH_ASSOC);
    
        if ($user) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'nom' => $user['nom'],
                'prenom' => $user['prenom'],
                'username' => $user['username'],
                'email' => $user['email'],
                'genre' => $user['sexe'],            
                'etablissement' => $user['etablissement'],
                'pays' => $user['pays_user'],
                'cours' => $user['metier'],   
                'img_profile' => $user['img_profile'],
                'date_enregistrement' => $user['date_enregistrement'],     
                'date_de_naissance' => $user['date_de_naissance']
            ];

        }

        header("Location: index.php?success=1&type=cookieconnexion&username=" . urlencode($user['username']));
        exit;
    }
    if (!isset($_SESSION['user'])) {
        header("Location: page.php");
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $describe = filter_var(trim($_POST['discribe'] ?? ''), FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if ($describe !== '') {
        $stmt = $pdo->prepare("UPDATE users SET description = ? WHERE id = ?");
        $stmt->execute([$describe, $_SESSION['user']['id']]);
    }
}

$describe_stocker = '';
$describe_content = '';

$stmt = $pdo->prepare("SELECT description FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user']['id']]);
$describe_stocker = $stmt->fetchColumn();

if (is_string($describe_stocker) && trim($describe_stocker) !== '') {
    $describe_content = htmlspecialchars_decode($describe_stocker);
}else{
    $describe_content = 'Cette bio est en vacances... revenez plus tard ! 🏖️';
}
// Récupérer les posts de tous les utlisateurs
$sql_requete = $pdo->prepare(
"SELECT users.id,
               users.img_profile ,
               users.username,  
               post.id_forum , 
               post.id_users , 
               post.image_forum,
               post.titre_content, 
               post.content , 
               post.categories,
               creation_post FROM users
               JOIN post ON 
               users.id = post.id_users 
               order by post.creation_post DESC
               ");
$sql_requete->execute();
$post_user = $sql_requete->fetchAll(PDO::FETCH_ASSOC);
// Récupérer les posts de l'utilisateur connecté
$post_requete = $pdo->prepare("SELECT users.id,
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
              WHERE users.id = ?
              ");
$post_requete->execute([$_SESSION['user']['id']]);
$post_user_conect = $post_requete->fetchAll(PDO::FETCH_ASSOC);
// recuperation de tous les utlisateurs 
if(isset($_SESSION['user']) && !empty($_SESSION['user'])){
    $All_users ='';
    $users = $pdo->prepare("SELECT id , nom , img_profile , username  ,sexe, description FROM users ORDER BY date_enregistrement DESC ");
    $users->execute();
    $All_users = $users->fetchAll();
}
if(isset($_SESSION['user']) && !empty($_SESSION['user'])){
    $commentaire = $pdo->prepare("SELECT 
                                users.id,
                                users.username,
                                users.sexe,
                                users.img_profile,
                                commentaire.content,
                                commentaire.date_commentaire,
                                post.titre_content
                                FROM users
                                JOIN commentaire ON users.id = commentaire.users_commentaire
                                JOIN post ON commentaire.id_forum = post.id_forum
                                ORDER BY  date_commentaire DESC LIMIT 5
                                "
                                );
    $commentaire->execute();
    $commentaire_user = $commentaire->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Etudiant 2I Forum</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.3/dist/css/splide.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Asap&display=swap" rel="stylesheet">
</head>
<body>
<div id="welcome-popup" class="popup" style="display: none;">
    <div class="popup-content position-relative">
        <p>
         <span class="change-text fs-2">Bienvenu sur student hub,</span></p>
        <button class="btn btn-none" onclick="closePopup()">
        Fermer
    </button>
     <p class="reserve"></p>
    </div>
     </div>
    <div class="d-flex">
       <?php include 'navbar-right.php'; ?>
        <div class="content w-100 blur flex-grow-1">
            <?php include 'navbar-left.php'; ?>
            <div id="home" class="page active mt-5">
                <div class="container">
                    <div class="row">
                    <div class="col-lg-11 slide col-md-12 d-flex flex-row gap-3" style="overflow: hidden;height:19rem;">
                    <?php if (isset($_SESSION['user'], $All_users) && !empty($All_users) && !empty($_SESSION['user'])): ?>
        <div id="user-slider" class="splide" style="margin: 2rem 0;">
          <div class="splide__track">
            <ul class="splide__list">
              <?php foreach ($All_users as $user): ?>
                <?php
          $img_profile = $user['img_profile'];
          $username = htmlspecialchars($user['username']);
          $id_users = htmlspecialchars($user['id']);
          $sexe = htmlspecialchars($user['sexe']);
          $nom = htmlspecialchars($user['nom']);
          $description = $user['description'];
          
          $img_src = "img-profile-defaut/defaut.jpg";
          if (!empty($img_profile)) {
            $img_src = "img_profile/" . htmlspecialchars($img_profile);
          } elseif ($sexe === 'Homme') {
            $img_src = "img-profile-defaut/homme.png";
          } elseif ($sexe === 'Femme') {
            $img_src = "img-profile-defaut/femme.jpg";
          }
        ?>
        <li class="splide__slide">
          <div class="slider-item position-relative rounded-4 overflow-hidden" style="width:90%; height: 16rem;">
            <img src="<?php echo $img_src; ?>" class="img w-100 h-100 object-fit-cover light rounded-4" alt="">
            <div class="users position-absolute" style="top: 2rem; left: 1.3rem;">
              <div class="d-flex gap-2 align-items-center">
                <div class="users-photo" style="width:2.2rem;height:2rem;">
                  <img src="<?php echo $img_src; ?>" class="img user rounded-circle w-100 h-100" alt="">
                </div>
                <p class="text-white ms-2 mt-1"><?php echo $username; ?></p>
              </div>
            </div>
            <div class="position-absolute bottom-0  p-2  text-white">
              <p class=""><?php echo $nom; ?> fait partie de i see you</p>
              <a href="profile.php?id=<?php echo $id_users; ?>" class="btn btn-sm btn-outline-light">Voir profil</a>
             </div>
            </div>
           </li>
            <?php endforeach; ?>
            </ul>
           </div>
            </div>
            <?php else: ?>
            <p>Aucun utilisateur trouvé.</p>
             <?php endif; ?>
                  </div>
                  </div>
                  <div class="row mt-5 align-items-start">
                    <div class="col-lg-7  col-sm-12 col-md-12 bg-transparent mode" style="border-radius: 5px 5px 0 0 ;">
                      <nav class="navbar navbar-expand-lg">
                        <a class="navbar-brand text-dark ms-3 name-users fs-1" href="" style="letter-spacing: 2px;font-weight: 400;">post</a>
                        <button type="bouton" class="ms-auto position-relative border-0 bg-transparent bouton-joined" style="left:-2rem">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 24 24" fill="none">
                            <path d="M19 13C19.5523 13 20 12.5523 20 12C20 11.4477 19.5523 11 19 11C18.4477 11 18 11.4477 18 12C18 12.5523 18.4477 13 19 13Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M5 13C5.55228 13 6 12.5523 6 12C6 11.4477 5.55228 11 5 11C4.44772 11 4 11.4477 4 12C4 12.5523 4.44772 13 5 13Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                      </nav>
                      <div class="d-flex flex-column gap-5 py-2">
                        <?php if(!empty($post_user)): ?>
                        <?php foreach($post_user as $post): ?>
                        <?php
                        $img_profile = $post['img_profile'];
                        $username = htmlspecialchars($post['username']);
                        $id_forum = htmlspecialchars($post['id_forum']);
                        $id_users_post = (int)htmlspecialchars($post[ 'id_users']);
                        $image_forum = $post['image_forum'];
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
                        
                         <div class="card border-0">
                            <div class="card-body">
                                <div class="card-title d-flex flex-column">
                                    <div class="users-forum d-flex flex-row">
                                       <div class="users-photo-forum">
                                        <?php if(isset($img_profile) && !empty($img_profile)): ?>
                                        <img src="img_profile/<?php echo  $img_profile ?>" class="img" alt="<?php echo htmlspecialchars($img_profile) ?>">
                                        <?php elseif(isset($_SESSION['user']['genre']) && $_SESSION['user']['genre'] == "Homme"): ?>
                                            <img src="img-profile-defaut/homme.png" class="img" alt="homme.jpg">
                                            <?php elseif(isset($_SESSION['user']['genre']) && $_SESSION['user']['genre'] == "femme"): ?>
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
                                    <p class="paragraphs_content" style="font-family: Asap !important;" title="<?php  $titre_content?>"><?php echo htmlspecialchars_decode ($content) ?></p>
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

                            <div class="fs-4">Aucune post trouve</div>
                            <?php endif; ?>
                      </div>
                      </div>
                    <div class="col-4 btn-none  bg-light rounded-3 ms-2 position-sticky top-0 left-0">
                        <p class="text-center fs-5 mt-2">Commentaires Recents</p>
                        <?php if(!empty($commentaire_user)): ?>
                        <?php foreach($commentaire_user as $comentaire_all): ?>
                        <?php 
                        $img_profile = $comentaire_all['img_profile'];
                        $username_coment = $comentaire_all['username'];
                        $content = $comentaire_all['content'];
                        $sexe = $comentaire_all['sexe'];
                        $titre_content = $comentaire_all['titre_content'];
                        $date_commentaire = $comentaire_all['date_commentaire'];
                        ?>
                        <div class="ranked-coment p-4">
                            <div class="ranked-item bg-white mode rounded-4">
                                <div class="users-forum d-flex flex-row">
                                    <div class="users-photo-forum" style="width:2.1rem !important;;height: 2rem !important;">
                                        <?php if(isset($img_profile) && !empty($img_profile)) : ?>
                                        <img src="img_profile/<?php echo htmlspecialchars($img_profile) ?>" class="img ms-4 mt-3 rounded-circle" alt="<?php echo $img_profile ?>">
                                        <?php elseif(isset($sexe) && $sexe == 'Homme') : ?>
                                        <img src="img-profile-defaut/homme.png ?>" class="img user rounded-circle ms-4 mt-3" alt="image_de_profile_par_defaut_Homme">
                                        <?php elseif(isset($sexe) && $sexe == 'Femme') : ?>
                                        <img src="img-profile-defaut/femme.jpg ?>" class="img user rounded-circle ms-4 mt-3" alt="image_de_profile_par_defaut_Femme">
                                        <?php else : ?>
                                        <img src="img-profile-defaut/defaut.jpg ?>" class="img user rounded-circle ms-4 mt-3" alt="image_de_profile_par_defaut_Maudit">
                                       <?php endif ?>
                                    </div>
                                     <p class="ms-5 mt-4 name-users" style="font-family: Asap !important;"><?php echo $username ?></p>
                                     <p></p>
                                </div>
                                <p class="mt-3 ms-4 w-80" style="font-family: Asap !important;"><?php echo $content ?></p>
                                <div class="mt-3 ms-4">
                                <h7 class="anime">titre discussions</h7>
                                <p style="font-size: 1rem!important;"><?php echo $titre_content ?></p>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        <?php else: ?>
                            <div class="fs-4">Aucun commentaire recents</div>
                        <?php endif ?>
                    </div>
                    </div>
                </div>
             </div>
            <div id="forums" class="page p-5 mt-5" style="display: none;">
                <h2 class="text-center">Recherchez votre manga prefere</h2>
                <div class="d-flex flex-row gap-3 mt-5">
                <input type="search" class="form-control" id="manga-search" placeholder="Recherche vos manga prefere...." style="height: 3rem!important;">
                <button class="btn btn-primary" style="width: 10rem!important;">upload</button>
                </div>
                <div class="content-manga mt-5">
                 <div class="content-item rounded-3"><img src="Photo_6235.jpg" alt=""></div>
                 <div class="content-item rounded-3"><img src="8343.jpg" alt=""></div>
                 <div class="content-item rounded-3"><img src="ciel bleu.jpg" alt=""></div>
                 <div class="content-item rounded-3"><img src="ciel bleu.jpg" alt=""></div>
                </div>
            </div>
            <div id="meetings" class="page" style="display: none;">
                <h1>Meetings</h1>
                <p>Contenu des réunions...</p>
            </div>
            <div id="community" class="page p-5" style="display: none;">
                <h1>Community</h1>
                <p>Contenu de la communauté...</p>
            </div>

            <div id="message" class="page p-5" style="display: none;">
            </div>
            <div id="compte" class="page p-5" style="display: none;">
                <div class="container-fluid">
                 <div class="w-100  background" style="height:14rem;">
                <?php if (isset($_SESSION['user']['img_profile'])): ?>
               <img src="img_profile/<?= htmlspecialchars($_SESSION['user']['img_profile']) ?>?v=<?= time() ?>" 
                class="back" 
                alt="Photo de profil" 
                id="back-image">
                <?php elseif (isset($_SESSION['user']['genre']) && $_SESSION['user']['genre'] == "Homme"): ?>
                <img src="img-profile-defaut/homme.png" 
                class="back" 
                alt="Photo de profil par défaut - Homme" 
                id="back-image">
                <?php elseif (isset($_SESSION['user']['genre']) && $_SESSION['user']['genre'] == "Femme"): ?>
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
                    </div>
                <div class="row  row-cols-1 align-items-start">
                <div class="col">
                <div class="positon-relative">
                <?php if (isset($_SESSION['user']['img_profile'])): ?>
               <img src="img_profile/<?= htmlspecialchars($_SESSION['user']['img_profile']) ?>?v=<?= time() ?>" 
                class="profile-img mx-auto position-relative" 
                alt="Photo de profil"
                id="imagePreview">
                <?php elseif (isset($_SESSION['user']['genre']) && $_SESSION['user']['genre'] == "Homme"): ?>
                <img src="img-profile-defaut/homme.png" 
                class="profile-img mx-auto position-relative" 
                alt="Photo de profil par défaut - Homme" 
                id="imagePreview">
                <?php elseif (isset($_SESSION['user']['genre']) && $_SESSION['user']['genre'] == "Femme"): ?>
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
                 <button type="button" class="btn btn-primary rounded-circle position-absolute profile-upload" style="transform:translate(-4rem,9.3rem)!important;"  id="pop-upload"><i class="fas fa-camera" style="color: white;scale:0.7!important;transform:translate(-45%,-35%)"></i></button>
                 </div>
                 <div class="pop-upload"  id="upload" style="display: none;">
                <div class=" card content-upload d-flex flex-column  justify-content-center align-items-center" style="height: 15rem;width: 25rem;">
                <div class="card-title">
                    <h5 class="text-start mt-3">upload une photo de profile</h5>
                </div>
                <div class="card-body">
                <label for="image-profile" class="custom-file-upload mt-3" style="width: 23rem;height:4rem;font-weight:normal;letter-spacing:3px;">
                <form action="upload.php" method="post" enctype="multipart/form-data" id="uploadForm">
                <i class="fas fa-upload"></i> selectionner une image
                </label>
                <div class="fs-1 mx-auto" id="error_size"></div>
                <input type="file" name="profile" accept="image/png, image/jpeg, image/gif, image/webp" id="image-profile" onchange="previewImage(event)" style="display: none;">
                </div>
                <div class="card-footer border-0 bg-transparent">
                <button type="reset" class="btn  btn-light" onclick="closeupload()">Annuler</button>
                <button type="submit" class="btn btn-primary">envoyer</button>
                </div>
                </form>
                </div>
                </div>
                <div class="position-relative" style="top: 1rem;">
                <h4 class="name-users" style="font-family: 'Asap', sans-serif!important;"><?php echo $nom ?> <span><?php echo  $prenom?></span></h4>
                <p class="position-relative" style="top:-0.3rem;"><?php echo $email ?></p>
                </div>
                <div>      
                <p class="text-dark query mt-5" style="font-size: 0.8rem;width:18rem;font-family: 'Asap', sans-serif!important;"><?php  echo $describe_content ?></p>
                    <button class="btn btn-transparent border-0" id="describe-pop-visible"><i class="fas fa-pen" style="color:black"></i></button>
                    <div class="pop-upload-describe"id="pop-upload-describe" style="display: none;">
                    <div class=" d-flex flex-column justify-content-center align-items-center content-describe bg-white" style="width:37rem!important;height:30rem;">
                    <div class="d-flex flex-row">
                    <h4 class="position-relative" style="bottom: 4rem;">Ajoute une touche de toi ici</h4>
                    <button class="btn border-0 btn-primary position-relative ms-auto rounded-5 bg-light mini" onclick="closedescribe()" style="top:-4rem;left:7rem"><i class="fa-solid fa-xmark" style="color:silver"></i></button>
                    </div>
                    <form method="POST" id="input_describe">
                        <div class="d-flex flex-column justify-content-center align-items-center">
                        <textarea name="discribe" class="form-control text-disc" id="discribe-edit"  placeholder="parle de vous..." style="resize: none; width:30rem!important;height:10rem!important" required></textarea>
                        <div class="error_texterea me-auto mt-1"></div>
                        <button type="submit" id="describe-pop" class="btn btn-primary mt-5">envoyer</button>
                        </div>
                    </form>
                    </div>
                   </div>
                </p>
               </div>
               <div>
                <span class="details">cree le</span>
                <p class=" text"><?php echo $dateFormatee ?> <i class="fas fa-clock" style="color: #333;"></i></p>
               </div>
               <div>
               </div>
                </div>
                <div class="col d-flex flex-column bg-transparent">
                    <div class="border rounded-4 mt-5 mode">
                    <nav class="navbar bg-transparent navbar-white">
                    <a href="" class="navbar-brand ms-2 details">Détails du profil</a>
                    <button type="bouton" class="btn btn-white border ms-auto me-5 bg-white"><i class="fas fa-pen-to-square"></i> Edit</button>
                    </nav>
                    <div class="container">
                    <div class="row row-cols-lg-5 row-cols-md-1 row-cols-1 mt-3">
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
                </div>
                </div>
                </div>
                </div>
               </div>
               </div>
                </div>
            </div>
            </div>
            </div>
            </div>
            </div>
            </div>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.3/dist/js/splide.min.js"></script>
            <script>
            document.addEventListener('DOMContentLoaded', function () {
                new Splide('#user-slider', {
                type: 'loop',
                perPage: 3,
                gap : '1rem',
                arrows:false,
                snap: true,
                type:"loop",
                pagination:false,
                interval:3000,
                autoplay: true,
                breakpoints: {
                  768: {
                    perPage: 1,
                    gap:'0.5rem',
                  },
                  1225:{
                    perPage: 2
                  },
                  1089:{
                    perPage: 2
                  },
                  1024: {
                    perPage: 2,
                            }
                }
              }).mount();
              });
           </script>
             <script src="script.js"></script>
             <script src="form.js"></script>
         </body>
         </html>