<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if(isset($_POST['mode'])){
		if ($_POST['mode'] == 'solo'){
        header('Location: multiplication.php');
        exit();
    } elseif ($_POST['mode'] == 'groupe') {
        header('Location: groupe.php');
        exit();
    }
	}
	
    
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Espace élève</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <form class='niveau' action="mode.php" method="post">
        <h4>Sélectionnez votre mode de jeu :</h4>
        <input type="radio" id="mode" name="mode" value="solo">
        <label for="solo">Solo</label><br>
        <input type="radio" id="mode" name="mode" value="groupe">
        <label for="groupe">Groupe</label><br>
		<br>
        <input class='submitmdp' type="submit" value="Valider">
    </form>
	<br><br>
    <a class="bouton" href="password.php">Changer le mot de passe</a>
</body>
</html>
