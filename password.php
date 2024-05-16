<?php
    session_start(); 
    $username = "";
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $username = $_POST['username'];
        $old_password = $_POST['old_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        if($new_password !== $confirm_password){
            echo("Le nouveau mot de passe et la confirmation ne correspondent pas.");
        }
        $users = file('utilisateurs.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $user_found = false;
        $password_changed = false;
        $new_users = [];
        $role = "";
        foreach($users as $user){
            if(trim($user) === '') continue;
            $user_data = explode(';', $user);
            $stored_username = $user_data[0];
            $stored_password = $user_data[1];
            if($stored_username === $username){
                $user_found = true;
                if($old_password === $stored_password){
                    $user_data[1] = $new_password; 
                    $password_changed = true;
                    $role = $user_data[4];
                }
                else{
                    echo("Ancien mot de passe incorrect.");
                }
            }
            $new_users[] = implode(';', $user_data);
        }
        if($user_found){
            if($password_changed){
                file_put_contents('utilisateurs.txt', implode(PHP_EOL, $new_users) . PHP_EOL);
                echo "Mot de passe changé avec succès.";
            }
            else {
                echo "Erreur lors du changement de mot de passe.";
            }
        }
        else {
            echo "Nom d'utilisateur incorrect.";
        }
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Changement de Mot de Passe</title>
</head>
<body>
    <div>
        <form action="change_password.php" method="post">
            <p>Changer le mot de passe :</p>
            <label for="username">Nom d'utilisateur :</label>
            <input type="text" id="username" name="username" required><br>
            <label for="old_password">Ancien mot de passe :</label>
            <input type="password" id="old_password" name="old_password" required><br>
            <label for="new_password">Nouveau mot de passe :</label>
            <input type="password" id="new_password" name="new_password" required><br>
            <label for="confirm_password">Confirmez le nouveau mot de passe :</label>
            <input type="password" id="confirm_password" name="confirm_password" required><br>
            <input type="submit" value="Changer le mot de passe">
        </form>
    </div>

    <?php
        $role = ''; 
        if(isset($_SESSION['username'])){
            $username = $_SESSION['username'];
        }
        $users = file('utilisateurs.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach($users as $user){
            $user_data = explode(';', $user);
            if($username === $user_data[0]){
                $role = $user_data[4];
                break;
            }
        }
        if($role === "Professeur"){
            echo '<a href="professeur.php"><button>Retourner à la page initiale</button></a>';
        }
        elseif ($role === "Elève") {
            echo '<a href="mode.php"><button>Retourner à la page initiale</button></a>';
        }
    ?>
    
</body>
</html>
