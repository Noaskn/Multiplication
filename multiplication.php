<?php

// Démarre la session pour accéder aux variables de session
session_start();

// Récupère l'ID aléatoire de la session s'il existe
$random_id = $_SESSION['random_id'] ?? '';
$url='';
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mode Solo</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <!-- Section pour choisir le niveau de difficulté -->
    <div id="choixNiveau">
        <p><h2>Choisissez le niveau de difficulté</h2></p>
        <a href="#" class="button" id="facile">Facile</a>
        <a href="#" class="button" id="intermediaire">Intermédiaire</a>
        <a href="#" class="button" id="difficile">Difficile</a>
    </div>

    <!-- Section principale de l'application de multiplication, cachée par défaut -->
    <div class="app" style="display:none;">

        <!-- Affichage du score -->
        <header>
            <span class="score">Score : <span id="score"> 0</span></span>
        </header>

        <!-- Affichage de l'opération à résoudre -->
        <h2><span id="num1">0</span> x <span id="num2">0</span> ? </h2>

        <!-- Entrer et valider la réponse -->
        <input class='reponse' type="number" placeholder="Entrer la réponse" id="input">
        <button id="valider" class="valider">Valider</button>

        <!-- Affichage du chronomètre -->
        <div id="timer">00:00</div>
        <br>

        <!-- Message de bonus -->
        <p class='niveau' id="bonusMessage"></p>

        <!-- Affichage du résultat (bonne ou mauvaise réponse) -->
        <p class='niveau' id="resultat"></p>
        <br>
        <div style="display: flex; justify-content: space-between;">

            <!-- Bouton pour rechoisir le niveau de difficulté -->
            <button class="bouton" id="reselectNiveau">Rechoisir le niveau</button>

            <!-- Lien pour terminer le jeu -->
            <a class="bouton" id='finDuJeu' href="<?php echo $url."/score.php"; ?>">Fin du jeu</a>
        </div>
        <br>

        <!-- Injection de l'ID aléatoire de la session dans le JavaScript -->
        <script>
            var uniqid = "<?php echo isset($_SESSION['random_id']) ? $_SESSION['random_id'] : ''; ?>";
        </script>
    </div>

    <!-- Inclusion du fichier JavaScript pour la logique du jeu -->
    <script type="text/javascript" src="multiplication.js"></script>
</body>
</html>
