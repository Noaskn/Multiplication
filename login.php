<?php
include("parametre.php");
$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    if (!empty($username) && !empty($password)) {
        $file = 'utilisateurs.txt';
        $random_id = uniqid();
        $lines = file($file);
        $fp = fopen($file, 'w');
        foreach ($lines as $line) {
            $parts = explode(';', $line);
            if ($parts[0] == $username && $parts[1] == $password) {
                fwrite($fp, "$username;$password;$random_id;$parts[3]\n");
                fclose($fp);
                include("multiplication.php");
                exit();
            }
            fwrite($fp, $line);
        }
        $error = "Identifiant invalide. Veuillez réessayer.";
        fclose($fp);
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles.css">
    <title>Connexion</title>
</head>
<body>
    <h2>Connexion</h2>
    <?php if (!empty($error)) echo "<p>$error</p>"; ?>
    <form action=<?php echo $url."/login.php"; ?> method="post">
        <div>
            <label for="username">Nom d'utilisateur :</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div>
            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div>
            <input type="submit" value="Se connecter">
        </div>
    </form>
</body>
</html>
