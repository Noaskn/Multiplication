<?php

// Démarrage de la session PHP pour stocker des variables de session
session_start();
include("parametre.php");
$error = '';
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Récupération des données du formulaire
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $type = $_POST['type'] ?? ''; // Type d'utilisateur (Professeur ou Élève)

    // Vérification si les champs nom d'utilisateur et mot de passe ne sont pas vides
    if(!empty($username) && !empty($password)){
        $file = 'utilisateurs.txt';
        $lines = file($file);
        $found = false;
        $updated_lines = [];

        // Parcourir chaque ligne du fichier des utilisateurs
        foreach($lines as $line){
            $parts = explode(';', $line);

            // Vérifier si l'utilisateur est bloqué
            if(isset($parts[5]) && rtrim($parts[5]) == 'Oui'){

                // Si l'utilisateur est bloqué, définir un message d'erreur et arrêter la vérification
                $error = "Vous êtes bloqué. Veuillez contacter l'administrateur.";
                break;
            }

            // Vérifier si les informations d'identification saisies correspondent à une entrée dans le fichier
            if($parts[0] == $username && $parts[1] == $password && rtrim($parts[4]) == $type){

                 // Si les informations d'identification sont valides, générer un identifiant aléatoire pour la session
                $found = true;
                $random_id = uniqid();
                $_SESSION['random_id'] = $random_id;

                // Mettre à jour les informations de l'utilisateur avec le nouvel identifiant aléatoire
                $updated_lines[] = "$username;$password;$random_id;$parts[3];$type;$parts[5]";
            }
            else{

                // Si les informations d'identification ne correspondent pas, conserver la ligne inchangée
                $updated_lines[] = $line;
            }
        }

         // Si l'utilisateur n'est pas trouvé et aucune erreur n'est survenue
        if(!$found && empty($error)){

            // Définir un message d'erreur indiquant que les informations d'identification sont invalides
            $error = "Identifiant invalide. Veuillez réessayer.";
        }

         // Si aucune erreur n'est survenue
        if(empty($error)){

            // Mettre à jour le fichier des utilisateurs avec les lignes mises à jour
            file_put_contents($file, $updated_lines);

             // Inclure la page appropriée en fonction du type d'utilisateur (Professeur ou Élève)
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
    <title>Connexion</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Connexion</h2>

    <!-- Afficher le message d'erreur s'il existe -->
    <?php if(!empty($error)) echo "<p>$error</p>"; ?>

    <!-- Formulaire de connexion -->
    <form action="<?php echo $url."/login.php"; ?>" class="basique" method="post">
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
            <input type="submit" value="Se connecter">
        </div>
    </form>
</body>
</html>
