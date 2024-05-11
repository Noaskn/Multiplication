
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        
        <title>Multiplication</title>
    </head>
    <body>
        <div id="choixNiveau">
            <p><h2>Choisissez le niveau de difficulté</h2></p>
            <a href="#" class="button" id="facile">Facile</a>
            <a href="#" class="button" id="intermediaire">Intermédiaire</a>
            <a href="#" class="button" id="difficile">Difficile</a>
        </div>
		<div >
            <form id="code" action="verifierCode.php" method="post">
				<label for="code">Entrez votre code :</label>
				<input type="number" id="code" name="code" required>
				<a class="bouton" id='finDuJeu' href=<?php echo $url."/multiplicationGroupe.php"; ?>>Valider</a>
			</form>
        </div>
        <div class="app" style="display:none;">
            <header>
                <span class="score">Score : <span id="score"> 0</span></span>
            </header>
            <h2><span id="num1">0</span> x <span id="num2">0</span> ? </h2>
            <input type="number" placeholder="Entrer la réponse" id="input">
            <button id="valider" class="valider">Valider</button>
            <div id="timer">00:00</div>
			<p id="bonusMessage"></p>
            <button class="bouton" id="reselectNiveau">Rechoisir le niveau</button>
            <script>
                var uniqid = "<?php echo $random_id; ?>";
            </script>
            <a class="bouton" id='finDuJeu' href=<?php echo $url."/score.php"; ?>>Fin du jeu</a>
            <p id="resultat"></p>
        </div>
        <script type="text/javascript" src="multiplication.js"></script>
    </body>
</html>

<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $code = $_POST["code"] ?? '';

    $contenu = file_get_contents("groupe.txt");

    if (preg_match("/Code: $code  Niveau: (\w+)/", $contenu, $matches)) {
        include("multiplicationGroupe.php");
        exit;
    } else {
        echo "Code invalide. Veuillez réessayer.";
    }
}
?>
