<?php
include("parametre.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $type = $_POST['type'] ?? '';
    if (!empty($username) && !empty($password)) {
        $random_id = uniqid();
        $file = 'utilisateurs.txt';
        $fp = fopen($file, 'a');
        fwrite($fp, "$username;$password;$random_id;0;$type\n");
        fclose($fp);
        if ($type == "Professeur"){
					include("professeur.php");
				}
                else{
					include("multiplication.php");
				}
                exit();
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
    <h2>Créer un compte</h2>
    <form action=<?php echo $url."/signup.php"; ?> class="basique" method="post">
        <div>
            <label for="username">Nom d'utilisateur :</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div>
            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div>
            <label for="type"></label>
            <input type="radio" name="type" value="Professeur" checked="checked" /> Professeur
            <input type="radio" name="type" value="Elève" /> Elève
        </div>
        <div>
            <input type="submit" value="Cr&eacute;er un compte">
        </div>
    </form>
</body>
</html>
