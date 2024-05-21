<?php
session_start();
include("parametre.php");

// Initialise la variable pour les messages à afficher

$message = '';
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Récupère le nom d'utilisateur, le mot de passe et le type saisis dans le formulaire
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $type = $_POST['type'] ?? '';

    // Initialise la variable pour le statut de blocage du compte
    $bloque = 'Non';

    // Vérifie si le nom d'utilisateur et le mot de passe ne sont pas vides
    if(!empty($username) && !empty($password)){
        $file_path = "utilisateurs.txt";

        // Vérifie si le fichier des utilisateurs existe
        if(file_exists($file_path)){

            // Lit le contenu du fichier et le stocke dans un tableau
            $utilisateurs = array_filter(file($file_path, FILE_IGNORE_NEW_LINES));
        }
        else{
            $utilisateurs = []; 
        }
        $username_exists = false;

        // Vérifie si le nom d'utilisateur existe déjà dans le fichier
        foreach($utilisateurs as $utilisateur){
            $info = explode(";", $utilisateur);

            // Si le nom d'utilisateur correspond à celui saisi on sort
            if($info[0] == $username){
                $username_exists = true;
                break;
            }
        }

        // Si le nom d'utilisateur existe déjà, affiche un message d'erreur
        if($username_exists){
            $message = "Le nom d'utilisateur existe déjà.<br> Veuillez en choisir un autre.";
        }

        // Si le nom d'utilisateur est unique
        else{

            // Génère un identifiant unique et le stocke dans la session
            $random_id = uniqid();
            $_SESSION['random_id'] = $random_id;

            // Ajoute les données de l'utilisateur au fichier
            $data = "$username;$password;$random_id;0;$type;$bloque\n";
            file_put_contents($file_path, $data, FILE_APPEND);

            // Redirige l'utilisateur en fonction du type de compte
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

    <!-- Affichage du message d'erreur -->
	<?php if(!empty($message)): ?>
        <div><?php echo $message; ?></div>
    <?php endif; ?>

     <!-- Formulaire d'inscription -->
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
