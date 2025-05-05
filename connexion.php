<?php
session_start();
include 'connexion-db.php';
$error_email = '';
$error_password = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['connexion-email'] ?? '';
    $password = $_POST['connexion-password'] ?? '';
    
    if ($email !== '' && $password !== '') {
        $req = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $req->execute([$email]);

        $user = $req->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if (password_verify($password, $user['password'])) {
                // Authentification réussie
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'nom' => $user['nom'],
                    'prenom' => $user['prenom'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'genre' => $user['sexe'],    
                    'pays' => $user['pays_user'],      
                    'etablissement' => $user['etablissement'],
                    'cours' => $user['metier'],       
                    'img_profile' => $user['img_profile'],
                    'date_enregistrement' => $user['date_enregistrement'],
                ];

                if (isset($_POST['remember'])) {
                    setcookie('user_id', $user['id'], time() + (86400 * 30), "/");
                }

                header("Location: index.php?success=1&type=connexion&username=" . urlencode($user['username']));
                exit;
            } else {
                $error_password = "Mot de passe incorrect.";
            }
        } else {
            $error_email = " Email introuvable.";
        }
    } else {
        $error = "⚠️ Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="auth.css">
   </head>
  <body>  
    <div class="container">
        <h3 class="text-center">Bienvenue sur <span style="color: royalblue;">Student-hub</span></h3>
            <div class="form-container" id="login">
         <form method="POST" action="connexion.php" autocomplete="off" id="form-login">
        <div class="input-item">
        <div class="position-relative" style="top: 2rem;">
         <div class=" mt-3">
          <label for="connexion-email">Email</label>
          <input type="email" name="connexion-email" class="form-control input" placeholder="" autocomplete="on" id="connexion-email" required>
          </div>
            <div class="position-relative mt-3" style="top: 1rem;">
           <label for="connexion-password">Mot de passe</label>
            <input type="password" name="connexion-password" class="form-control border-0 bg-light input" placeholder=""  autocomplete="new-password" id="connexion-password" required>
            <?php if(isset($error)) echo "<div class='fs-1' style='color:red;'>$error</div>"; ?>
            </div>
            <p class="mt-5 ms-2"><input type="checkbox" name="check"> se souvenir de moi</p>
            </div>
            </div>
            <button type="submit" class="btn btn-dark position-relative" style="top: 5rem;">Connexion</button></div>
            </form>
            <span class="mx-auto position-relative" style="top:8rem;left:1.5rem">si vous n'avez pas de compte cree <a href="inscription.php">inscription</a></span>
            </div>
            </div>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
           <script src="form.js"></script>
</body>
</html>

