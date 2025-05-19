<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div id="background-menu">
<nav class="menu  sticky-top  vh-100 d-flex flex-column text-white  bg-white px-5 py-3" id="menu-right" >
            <a class="navbar-brand fs-5 mt-4" href="#" style="transform: translate(-10px);">
            </a>
            <ul class="nav flex-column justify-content-center mt-5 border-bottom">
                <li class="nav-item">
                    <a class="nav-link nav-links d-flex align-items-center bg-warning border-left active-menu" href="index.php#meetings" data-page="home" id="tab-1">
                        <i class="bi bi-house-door me-2 "></i> Accueil
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-links nav-select text-white" href="#" data-page="forums" id="tab-2">
                        <i class="bi bi-chat-left-text me-2"></i>book store
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-links nav-select d-flex align-items-center" href="#" data-page="community" id="tab-3">
                        <i class="bi bi-people me-2"></i> communaute
                    </a>
                </li>
            </ul>
            <ul class="nav flex-column position-relative mt-5">
            <li class="nav-item"><a class="nav-link nav-links nav-select d-flex align-items-center" href="#" data-page="compte" id="tab-4"><i class="bi bi-question-circle me-2" data-page="compte"></i> mon compte</a></li>
                <li class="nav-item"><a href="connexion.php" type="bouton" class="nav-link text-white"><i class="bi bi-calendar-event me-2"></i>se connecter</a></li>
            </ul>
             <?php
           if (
            (isset($_SESSION['user']) &&  !empty($_SESSION['user'])) && 
           ($_SESSION['user']['role'] ==='admin' || 
           $_SESSION['user']['role'] ==='super-admin') ) {
            echo "
            <ul>
                <li class='nav-item'><a href='#' class='nav-link nav-links' data-page='admin' id='tab-5'>admin interface</a></li>
            </ul>
            ";
   
            }
            ?>
        </nav>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


