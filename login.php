<?php
include("parametre.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    if (!empty($username) && !empty($password)) {
        $file = 'utilisateurs.txt';
        $fp = fopen($file, 'r');
        while (!feof($fp)) {
            $line = fgets($fp);
            $parts = explode(';', $line);
            if ($parts[0] == $username && $parts[1] == $password) {
                fclose($fp);
                include("multiplication.php");
                exit();
            }
        }
        echo "Identifiants invalides. Veuillez réessayer.";
        fclose($fp);
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles.css">
    <title>Inscription</title>
</head>
<body>
    <h2>Connexion</h2>
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
            <input type="submit" value="Cr&eacute;er un compte">
        </div>
    </form>
</body>
</html>
