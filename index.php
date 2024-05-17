<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Page principale</title>
	<link rel="stylesheet" href="styles.css">
</head>
<body>
    <p><h2>Voulez-vous vous connecter ou créer un compte ?</h2></p>
    <a href="signup.php" class="button">Créer un compte</a>
    <a href="login.php" class="button">Se connecter</a>

    <h2>Espace administrateur</h2>
    <form class="codeadm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="code">Entrez votre code :</label>
        <input type="number" id="code" name="code" required>
        <button class="submitcompte" type="submit">Valider</button>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $codeSaisi = $_POST["code"] ?? '';
        $fichierCodesAdmin = "administrateur.txt";
        $contenuFichier = file_get_contents($fichierCodesAdmin);
        if (strpos($contenuFichier, $codeSaisi) !== false) {
            header("Location: admin.php");
            exit();
        }
        else {
            echo "<p>Code incorrect. Veuillez réessayer.</p>";
        }
    }
    ?>
</body>
</html>
