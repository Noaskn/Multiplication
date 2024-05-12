<?php
include("parametre.php");
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $type = $_POST['type'] ?? '';
    
    if (!empty($username) && !empty($password)) {
        $file = 'utilisateurs.txt';
        $random_id = uniqid();
        $lines = file($file);
        $found = false;
        $updated_lines = [];
        
        foreach ($lines as $line) {
            $parts = explode(';', $line);
            if (rtrim($parts[5]) == 'Oui') {
                $error = "Vous êtes bloqué. Veuillez contacter l'administrateur.";
                break;
            }
            
            if ($parts[0] == $username && $parts[1] == $password && rtrim($parts[4]) == $type) {
                $found = true;
                $updated_lines[] = "$username;$password;$random_id;$parts[3];$type;$parts[5]\n";
            }
            else {
                $updated_lines[] = $line;
            }
        }
        
        if (!$found && empty($error)) {
            $error = "Identifiant invalide. Veuillez réessayer.";
        }
        
        if (empty($error)) {
            file_put_contents($file, $updated_lines);
            
            if ($type == "Professeur"){
                include("professeur.php");
            } else {
                include("multiplication.php");
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
</head>
<body>
    <h2>Connexion</h2>
    <?php if (!empty($error)) echo "<p>$error</p>"; ?>
    <form action=<?php echo $url."/login.php"; ?> class="basique" method="post">
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
        <div class="submit">
            <input type="submit" value="Se connecter">
        </div>
    </form>
</body>
</html>
