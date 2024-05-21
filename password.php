<?php

// Démarrage de la session PHP
session_start();

// Initialisation des variables
$username = "";
$message = "";
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Récupération des valeurs du formulaire
    $username = $_POST['username'];
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Vérification si les mots de passe correspondent
    if($new_password !== $confirm_password){

        // Message si les mots de passe ne correspondent pas
        $message = "Le nouveau mot de passe et la confirmation ne correspondent pas.";
    }
    else{

        // Lecture des utilisateurs depuis le fichier
        $users = file('utilisateurs.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $user_found = false;
        $password_changed = false;
        $new_users = [];
        $role = "";

        // Parcours des utilisateurs pour trouver celui dont le mot de passe doit être changé
        foreach($users as $user){
            if(trim($user) === '') continue;
            $user_data = explode(';', $user);
            $stored_username = $user_data[0];
            $stored_password = $user_data[1];

             // Vérification du nom d'utilisateur et de l'ancien mot de passe
            if($stored_username === $username){
                $user_found = true;

                // Changement du mot de passe
                if($old_password === $stored_password){
                    $user_data[1] = $new_password; 
                    $password_changed = true;
                    $role = $user_data[4]; 
                }
                else{

                    // Message si l'ancien mot de passe est incorrect
                    $message = "Ancien mot de passe incorrect.";
                }
            }
            $new_users[] = implode(';', $user_data);
        }

        // Vérification si l'utilisateur a été trouvé
        if($user_found){

            // Vérification si le mot de passe a été changé
            if($password_changed){

                // Écriture des utilisateurs dans le fichier avec le mot de passe mis à jour
                file_put_contents('utilisateurs.txt', implode(PHP_EOL, $new_users) . PHP_EOL);
                $message = "Mot de passe changé avec succès.";
            }
            else{
                $message = "Erreur lors du changement de mot de passe.";
            }
        }
        else{

            // Message si l'utilisateur n'a pas été trouvé
            $message = "Nom d'utilisateur incorrect.";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Changement de Mot de Passe</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div>

        <!-- Formulaire de changement de mot de passe -->
        <form class='niveau' action="password.php" method="post">
            <h4>Changer le mot de passe :</h4>
            <label for="username">Nom d'utilisateur :</label>
            <input type="text" id="username" name="username" required><br>
            <br>
            <label for="old_password">Ancien mot de passe :</label>
            <input type="password" id="old_password" name="old_password" required><br>
            <br>
            <label for="new_password">Nouveau mot de passe :</label>
            <input type="password" id="new_password" name="new_password" required><br>
            <br>
            <label for="confirm_password">Confirmez le nouveau mot de passe :</label>
            <input type="password" id="confirm_password" name="confirm_password" required><br>
            <br>
            <input class='submitmdp' type="submit" value="Changer le mot de passe">
        </form>

        <!-- Affichage du message -->
        <div>
            <?php
            if($message !== ""){
                echo "<h5>$message</h5>";
            }
            ?>
        </div>
    </div>
    <?php

    // Initialisation de la variable
    $role = ''; 

    // Récupération du nom d'utilisateur actuellement connecté
    if(isset($_SESSION['username'])){
        $username = $_SESSION['username'];
    }

    // Lecture des utilisateurs depuis le fichier
    $users = file('utilisateurs.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    // Parcours des utilisateurs pour récupérer le rôle de l'utilisateur actuel
    foreach($users as $user){
        $user_data = explode(';', $user);
		if($username === $user_data[0]){
			$role = $user_data[4];
			break;
        }
    }

    // Affichage du bouton de retour en fonction du rôle
    if($role === "Professeur"){
        echo '<a href="professeur.php"><button>Retourner à la page initiale</button></a>';
    }
    elseif($role === "Elève"){
        echo '<a href="mode.php"><button>Retourner à la page initiale</button></a>';
    }
    ?>
</body>
</html>
