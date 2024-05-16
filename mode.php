<?php
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if($_POST['mode'] == 'solo'){
            header('Location: multiplication.php');
            exit();
        }
        elseif($_POST['mode'] == 'groupe'){
            header('Location: groupe.php');
            exit();
        }
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mode de Jeu</title>
</head>
<body>
    <form class='niveau' action="mode.php" method="post">
        <p>Sélectionnez votre mode de jeu :</p>
        <input type="radio" id="solo" name="mode" value="solo">
        <label for="solo">Solo</label><br>
        <input type="radio" id="groupe" name="mode" value="groupe">
        <label for="groupe">Groupe</label><br>
        <input type="submit" value="Valider">
    </form>
    <a href="password.php">Changer le mot de passe</a>
</body>
</html>
