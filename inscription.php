<?php
include 'connexion-db.php';

session_start();

$error_email = '';
$error_username='';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = filter_var(trim($_POST['nom'] ?? ''),FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $prenom = filter_var(trim($_POST['prenom'] ?? ''),FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $username = filter_var(trim($_POST['username'] ?? ''),FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $genre = $_POST['genre_select'] ?? '';
    $pays = $_POST['pays-select'] ?? '';
    $date_de_naissance = trim($_POST['date_de_naissance'] ?? '');
    $password = $_POST['password'] ?? '';
    $etablissement = filter_var(trim($_POST['etablissement'] ?? ''),FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $cours = filter_var(trim($_POST['cours'] ?? ''),FILTER_SANITIZE_FULL_SPECIAL_CHARS); // s'assurer que cette variable est définie dans le formulaire

    if (
        $nom !== '' && $prenom !== '' && $username !== '' &&
        $email !== '' && $genre !=='' && $date_de_naissance !== '' && $password !== ''
    ) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        try {
              
          $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
          $stmt->execute([$email]);
          $emailExiste = $stmt->fetchColumn();
         if ($emailExiste) {
          $_SESSION['errors']['email'] = " cet e-mail est déjà utilisé.";
           header("Location: inscription.php?error_email =identique");
          exit;
         }

      
         $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
         $stmt->execute([$username]);
         $pseudoExiste = $stmt->fetchColumn();

         if ($pseudoExiste) {
          $_SESSION['errors']['username'] = "Cet pseudo est déjà utilisé.";
          header("Location: inscription.php?error_username=identique");
          exit;
         } 
            $stmt = $pdo->prepare("INSERT INTO users (nom, prenom, username, email, sexe, pays_user, etablissement, metier, date_de_naissance, password)
                                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$nom, $prenom, $username, $email, $genre, $pays, $etablissement, $cours, $date_de_naissance, $passwordHash]);

            $userId = $pdo->lastInsertId();
            $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            var_dump($user);

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
                'date_enregistrement' => $user['date_enregistrement'],     
                'date_de_naissance' => $user['date_de_naissance']
            ];


            if (isset($_POST['remember'])) {
                setcookie('user_id', $user['id'], time() + (86400 * 30), "/"); 
            }
             
            header("Location: index.php?success=1&type=inscription&username=" . urlencode($user['username']));
            exit;

        } catch (PDOException $e) {
            echo "Échec de l'enregistrement : " . $e->getMessage();
        }
    } else {
        header("Location: inscription.php?error=1");
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="auth.css">
</head>
<body>
<div class="container container-connexion">
<div class="w-100 bg-dark h-50 rounded-3 p-3" ><h3 class="text-center text-white">Bienvenue sur <span>Student-hub</span></h3></div>
<div class="form-container mt-5" id="signup">
    <form method="POST" action="inscription.php" id="form-signup">
  <div class="input-item mb-3">
    <div class="row">
    <div class="col">
    <?php if (!isset($_SESSION['errors']['username'])): ?> 
    <label for="username">Pseudo</label>
    <input type="text" class="form-control" id="username" name="username"  placeholder="" required>
    <?php endif; ?>
  
    <?php if (isset($_SESSION['errors']['username'])): ?> 
      <label for="username">pseudo</label>
    <input type="text" class="form-control" id="username"  style='border: 2px solid red;' name="username" placeholder="" required>
     <div class="ms-2"  id="error_username" style='color:red; font-size: 0.7rem;'><?php echo $_SESSION['errors']['username']; ?></div>
      <?php unset($_SESSION['errors']['username']); ?>
    <?php endif; ?>
  </div>
  <div class="col">
  <div class="input-item mb-3">
  <i class="fa fa-user"></i> 
    <label for="nom" class="form-label">Nom</label>
    <input type="text" class="form-control" id="nom" name="nom" required>
  </div>
  </div>
  <div class="col">
  <div class="input-item mb-3">
    <label for="prenom" class="form-label">Prénom</label>
    <input type="text" class="form-control" id="prenom" name="prenom" required>
  </div>
  </div>
  </div>

  <div class="input-item mt-5">
    <label for="date_de_naissance" class="form-label">Date de Naissance</label>
    <input type="date" class="form-control" id="birthdate" name="date_de_naissance" required>
  </div>

  <div class="input-item mt-5">
  <?php if (!isset($_SESSION['errors']['email'])): ?> 
    <label for="email">Email</label>
    <input type="email" class="form-control" id="email" name="email"  placeholder="" required>
    <?php endif; ?>
  
    <?php if (isset($_SESSION['errors']['email'])): ?> 
      <label for="email">Email</label>
    <input type="email" class="form-control" id="email" style='border: 2px solid red;' name="email" placeholder="" required>
     <div class="ms-2" style='color:red; font-size: 0.7rem;'><?php echo $_SESSION['errors']['email']; ?></div>
      <?php unset($_SESSION['errors']['email']); ?>
    <?php endif; ?>
  </div>
   <div class="row mt-5">
    <div class="col">
   <div class="mb-3">
   <select name="genre_select" id="genre-select" class="form-select" required>
        <option value="genre" disabled>  choisisez votre genre</option>
        <option value="Homme">Homme</option>
        <option value="Femme">Femme</option>
        <option value="Maudit">Maudit</option>
    </select>
   </div>
   </div>
   <div class="col">
    <div class="input-item mb-3">
   <select name="pays-select" class="form-control" id="pays-select" required>
  <option value="">Sélectionnez votre pays</option>
  <option value="Afghanistan">Afghanistan</option>
  <option value="Afrique du Sud">Afrique du Sud</option>
  <option value="Albanie">Albanie</option>
  <option value="Algérie">Algérie</option>
  <option value="Allemagne">Allemagne</option>
  <option value="Andorre">Andorre</option>
  <option value="Angola">Angola</option>
  <option value="Arabie Saoudite">Arabie Saoudite</option>
  <option value="Argentine">Argentine</option>
  <option value="Arménie">Arménie</option>
  <option value="Australie">Australie</option>
  <option value="Autriche">Autriche</option>
  <option value="Azerbaïdjan">Azerbaïdjan</option>
  <option value="Bahamas">Bahamas</option>
  <option value="Bahreïn">Bahreïn</option>
  <option value="Bangladesh">Bangladesh</option>
  <option value="Belgique">Belgique</option>
  <option value="Bénin">Bénin</option>
  <option value="Bhoutan">Bhoutan</option>
  <option value="Biélorussie">Biélorussie</option>
  <option value="Birmanie">Birmanie</option>
  <option value="Bolivie">Bolivie</option>
  <option value="Bosnie-Herzégovine">Bosnie-Herzégovine</option>
  <option value="Botswana">Botswana</option>
  <option value="Brésil">Brésil</option>
  <option value="Brunei">Brunei</option>
  <option value="Bulgarie">Bulgarie</option>
  <option value="Burkina Faso">Burkina Faso</option>
  <option value="Burundi">Burundi</option>
  <option value="Cameroun">Cameroun</option>
  <option value="Canada">Canada</option>
  <option value="Cap-Vert">Cap-Vert</option>
  <option value="Chili">Chili</option>
  <option value="Chine">Chine</option>
  <option value="Chypre">Chypre</option>
  <option value="Colombie">Colombie</option>
  <option value="Comores">Comores</option>
  <option value="Republique du congo">Republique du congo</option>
  <option value="République démocratique du Congo">République démocratique du Congo</option>
  <option value="Corée du Nord">Corée du Nord</option>
  <option value="Corée du Sud">Corée du Sud</option>
  <option value="Costa Rica">Costa Rica</option>
  <option value="Côte d'Ivoire">Côte d'Ivoire</option>
  <option value="Croatie">Croatie</option>
  <option value="Cuba">Cuba</option>
  <option value="Danemark">Danemark</option>
  <option value="Djibouti">Djibouti</option>
  <option value="Dominique">Dominique</option>
  <option value="Égypte">Égypte</option>
  <option value="Émirats arabes unis">Émirats arabes unis</option>
  <option value="Équateur">Équateur</option>
  <option value="Érythrée">Érythrée</option>
  <option value="Espagne">Espagne</option>
  <option value="Estonie">Estonie</option>
  <option value="Eswatini">Eswatini</option>
  <option value="États-Unis">États-Unis</option>
  <option value="Éthiopie">Éthiopie</option>
  <option value="Fidji">Fidji</option>
  <option value="Finlande">Finlande</option>
  <option value="France">France</option>
  <option value="Gabon">Gabon</option>
  <option value="Gambie">Gambie</option>
  <option value="Géorgie">Géorgie</option>
  <option value="Ghana">Ghana</option>
  <option value="Grèce">Grèce</option>
  <option value="Guatemala">Guatemala</option>
  <option value="Guinée">Guinée</option>
  <option value="Guinée équatoriale">Guinée équatoriale</option>
  <option value="Guinée-Bissau">Guinée-Bissau</option>
  <option value="Guyana">Guyana</option>
  <option value="Haïti">Haïti</option>
  <option value="Honduras">Honduras</option>
  <option value="Hongrie">Hongrie</option>
  <option value="Inde">Inde</option>
  <option value="Indonésie">Indonésie</option>
  <option value="Irak">Irak</option>
  <option value="Iran">Iran</option>
  <option value="Irlande">Irlande</option>
  <option value="Islande">Islande</option>
  <option value="Israël">Israël</option>
  <option value="Italie">Italie</option>
  <option value="Jamaïque">Jamaïque</option>
  <option value="Japon">Japon</option>
  <option value="Jordanie">Jordanie</option>
  <option value="Kazakhstan">Kazakhstan</option>
  <option value="Kenya">Kenya</option>
  <option value="Kirghizistan">Kirghizistan</option>
  <option value="Kiribati">Kiribati</option>
  <option value="Koweït">Koweït</option>
  <option value="Laos">Laos</option>
  <option value="Lesotho">Lesotho</option>
  <option value="Lettonie">Lettonie</option>
  <option value="Liban">Liban</option>
  <option value="Libéria">Libéria</option>
  <option value="Libye">Libye</option>
  <option value="Liechtenstein">Liechtenstein</option>
  <option value="Lituanie">Lituanie</option>
  <option value="Luxembourg">Luxembourg</option>
  <option value="Macédoine du Nord">Macédoine du Nord</option>
  <option value="Madagascar">Madagascar</option>
  <option value="Malaisie">Malaisie</option>
  <option value="Malawi">Malawi</option>
  <option value="Maldives">Maldives</option>
  <option value="Mali">Mali</option>
  <option value="Malte">Malte</option>
  <option value="Maroc">Maroc</option>
  <option value="Maurice">Maurice</option>
  <option value="Mauritanie">Mauritanie</option>
  <option value="Mexique">Mexique</option>
  <option value="Micronésie">Micronésie</option>
  <option value="Moldavie">Moldavie</option>
  <option value="Monaco">Monaco</option>
  <option value="Mongolie">Mongolie</option>
  <option value="Monténégro">Monténégro</option>
  <option value="Mozambique">Mozambique</option>
  <option value="Namibie">Namibie</option>
  <option value="Népal">Népal</option>
  <option value="Nicaragua">Nicaragua</option>
  <option value="Niger">Niger</option>
  <option value="Nigéria">Nigéria</option>
  <option value="Norvège">Norvège</option>
  <option value="Nouvelle-Zélande">Nouvelle-Zélande</option>
  <option value="Oman">Oman</option>
  <option value="Ouganda">Ouganda</option>
  <option value="Ouzbékistan">Ouzbékistan</option>
  <option value="Pakistan">Pakistan</option>
  <option value="Palaos">Palaos</option>
  <option value="Palestine">Palestine</option>
  <option value="Panama">Panama</option>
  <option value="Papouasie-Nouvelle-Guinée">Papouasie-Nouvelle-Guinée</option>
  <option value="Paraguay">Paraguay</option>
  <option value="Pays-Bas">Pays-Bas</option>
  <option value="Pérou">Pérou</option>
  <option value="Philippines">Philippines</option>
  <option value="Pologne">Pologne</option>
  <option value="Portugal">Portugal</option>
  <option value="Qatar">Qatar</option>
  <option value="République centrafricaine">République centrafricaine</option>
  <option value="République tchèque">République tchèque</option>
  <option value="Roumanie">Roumanie</option>
  <option value="Royaume-Uni">Royaume-Uni</option>
  <option value="Russie">Russie</option>
  <option value="Rwanda">Rwanda</option>
  <option value="Saint-Kitts-et-Nevis">Saint-Kitts-et-Nevis</option>
  <option value="Saint-Marin">Saint-Marin</option>
  <option value="Saint-Vincent-et-les-Grenadines">Saint-Vincent-et-les-Grenadines</option>
  <option value="Sainte-Lucie">Sainte-Lucie</option>
  <option value="Salvador">Salvador</option>
  <option value="Samoa">Samoa</option>
  <option value="Sao Tomé-et-Principe">Sao Tomé-et-Principe</option>
  <option value="Sénégal">Sénégal</option>
  <option value="Serbie">Serbie</option>
  <option value="Seychelles">Seychelles</option>
  <option value="Sierra Leone">Sierra Leone</option>
  <option value="Singapour">Singapour</option>
  <option value="Slovaquie">Slovaquie</option>
  <option value="Slovénie">Slovénie</option>
  <option value="Somalie">Somalie</option>
  <option value="Soudan">Soudan</option>
  <option value="Soudan du Sud">Soudan du Sud</option>
  <option value="Sri Lanka">Sri Lanka</option>
  <option value="Suède">Suède</option>
  <option value="Suisse">Suisse</option>
  <option value="Suriname">Suriname</option>
  <option value="Syrie">Syrie</option>
  <option value="Tadjikistan">Tadjikistan</option>
  <option value="Tanzanie">Tanzanie</option>
  <option value="Tchad">Tchad</option>
  <option value="Thaïlande">Thaïlande</option>
  <option value="Timor oriental">Timor oriental</option>
  <option value="Togo">Togo</option>
  <option value="Tonga">Tonga</option>
  <option value="Trinité-et-Tobago">Trinité-et-Tobago</option>
  <option value="Tunisie">Tunisie</option>
  <option value="Turkménistan">Turkménistan</option>
  <option value="Turquie">Turquie</option>
  <option value="Tuvalu">Tuvalu</option>
  <option value="Ukraine">Ukraine</option>
  <option value="Uruguay">Uruguay</option>
  <option value="Vanuatu">Vanuatu</option>
  <option value="Vatican">Vatican</option>
  <option value="Venezuela">Venezuela</option>
  <option value="Viêt Nam">Viêt Nam</option>
  <option value="Yémen">Yémen</option>
  <option value="Zambie">Zambie</option>
  <option value="Zimbabwe">Zimbabwe</option>
</select>

  </div>
  </div>
  <div class="input-item mb-5">
    <label for="cours">cours</label>
    <input type="text" class="form-control" id="inscription_cours" name="cours" placeholder="" required>
  </div>
  <div class="input-item mb-5">
    <label for="etablissement">universite</label>
    <input type="text" class="form-control" id="inscription_etablissement" name="etablissement" placeholder="" required>
  </div>
  <div class="input-item mb-5">
    <label for="password" class="form-label">Mot de passe</label>
    <input type="password" class="form-control" id="password" name="password" autocomplete="new-password" required>
    <div id="error-password" class="form-text text-danger"></div>
  </div>

  <div class="input-item mb-5">
    <label for="signup-confirm-password" class="form-label">Confirmer le mot de passe</label>
    <input type="password" class="form-control" id="signup-confirm-password" name="signup_confirm_password" required>
  </div>
  <p class="ms-2 position-relative" style="top:-1rem"><input type="checkbox" name="check"> se souvenir de moi</p>
  <button type="submit" class="btn  mx-auto fs-4 w-100">S'inscrire</button>
  <span class=" mt-5 position-relative" style="left:13rem">si vous n'avez deja un compte <a href="connexion.php">connexion</a></span>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/country-region-selector/0.4.1/crs.min.js"></script>
  <script src="script.js"></script>
  <script src="form.js"></script>
</form>
</div>
</div>
</body>
</html>
