<?php

session_start();
$random_id = $_SESSION['random_id'] ?? '';

?>

<!DOCTYPE html>
<html lang="fr">

    <head>
        <meta charset="UTF-8">
        <title>Solo</title>
    </head>

    <body>

        <div id="choixNiveau">
            <p><h2>Choisissez le niveau de difficulté</h2></p>
            <a href="#" class="button" id="facile">Facile</a>
            <a href="#" class="button" id="intermediaire">Intermédiaire</a>
            <a href="#" class="button" id="difficile">Difficile</a>
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
                var uniqid = "<?php echo isset($_SESSION['random_id']) ? $_SESSION['random_id'] : ''; ?>";
            </script>

            <a class="bouton" id='finDuJeu' href="<?php echo $url . "/score.php"; ?>">Fin du jeu</a>
            <p id="resultat"></p>

        </div>
        
        <script type="text/javascript" src="multiplication.js"></script>
    </body>
</html>
