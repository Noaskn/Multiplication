<?php
    session_start();
    include("parametre.php");
    $message = '';
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $type = $_POST['type'] ?? '';
        $bloque = 'Non';
        if(!empty($username) && !empty($password)){
            $utilisateurs = array_filter(file("utilisateurs.txt", FILE_IGNORE_NEW_LINES));
            $username_exists = false;
            foreach($utilisateurs as $utilisateur){
                $info = explode(";", $utilisateur);
                if ($info[0] == $username) {
                    $username_exists = true;
                    break;
                }
            }
            if($username_exists){
                $message = "Le nom d'utilisateur existe déjà. Veuillez en choisir un autre.";
            }
            else{
                $random_id = uniqid();
                $_SESSION['random_id'] = $random_id;
                $data = "$username;$password;$random_id;0;$type;$bloque\n";
                $file = 'utilisateurs.txt';
                file_put_contents($file, $data, FILE_APPEND);
                if($type == "Professeur"){
                    include("professeur.php");
                }
                else{
                    include("mode.php");
                }
                exit();
            }
        }
    }
?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
	<link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Créer un compte</h2>
	
	 <?php if (!empty($message)): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>
	
    <form action="<?php echo $url."/signup.php"; ?>" class="basique" method="post">
        <div class="debut">
            <label for="username">Nom d'utilisateur :</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="debut">
            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="debut">
            <label for="type"></label>
            <input type="radio" name="type" value="Professeur" checked="checked" /> Professeur
            <input type="radio" name="type" value="Elève" /> Elève
        </div>
        <div class="submitcompte">
            <input type="submit" value="Créer un compte">
        </div>
    </form>
</body>
</html>
