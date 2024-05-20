<?php

// Démarrer la session
session_start();
$url='';
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $code = $_POST['code'] ?? ''; // Récupérer le code soumis
    $found = false; // Indicateur si le code est trouvé
    $niveau = ''; // Variable pour stocker le niveau

    // Vérifier si le fichier groupe.txt existe et est lisible
    if(file_exists('groupe.txt') && is_readable('groupe.txt')){

        // Lire toutes les lignes du fichier groupe.txt
        $lines = file('groupe.txt');
        foreach($lines as $line){

            // Séparer la ligne par des espaces
            $lineParts = explode(" ", trim($line));
            if(count($lineParts) < 2){
                continue;
            }

            // Extraire le code et le niveau de la ligne
            list($codeFromFile, $niveauFromFile) = $lineParts;

            // Vérifier si le code correspond
            if($codeFromFile == $code){
                $found = true;
                $niveau = $niveauFromFile; // Enregistrer le niveau
                $_SESSION['niveau'] = $niveau; // Stocker le niveau dans la session
                $_SESSION['code'] = $code; // Stocker le code dans la session
                $utilisateurFile = 'utilisateurs.txt';
                $userInfo = '';
                $userFound = false;

                // Vérifier si le fichier utilisateurs.txt existe et est lisible
                if(file_exists($utilisateurFile) && is_readable($utilisateurFile)){

                    // Ouvrir le fichier en lecture
                    $fp = fopen($utilisateurFile, 'r');

                    // Lire chaque ligne du fichier
                    while($ligne = fgets($fp)){
                        $donnees = explode(";", $ligne);
                        if(isset($donnees[2]) && trim($donnees[2]) == $_SESSION['random_id']){
                            $userInfo = $ligne;
                            $userFound = true;
                            break; // Utilisateur trouvé, sortir de la boucle
                        }
                    }

                    // Fermer le fichier
                    fclose($fp);
                }
                if($userFound){
                    $codeFile = $code . '.txt'; // Nom du fichier spécifique au code
                    $tempFile = 'temp.txt'; // Nom du fichier temporaire
                    $fpRead = file_exists($codeFile) && is_readable($codeFile) ? fopen($codeFile, 'r') : null;

                    // Ouvrir le fichier temporaire en écriture
                    $fpWrite = fopen($tempFile, 'w');
                    if(!$fpWrite){
                        echo "Impossible d'ouvrir le fichier temporaire en écriture.";
                        exit;
                    }
                    $userInCodeFile = false;
                    if($fpRead){

                        // Lire chaque ligne du fichier de code
                        while($line = fgets($fpRead)){
                            $lineData = explode(";", $line);
                            if(isset($lineData[0]) && trim($lineData[0]) == trim($donnees[0])){
                                $userInCodeFile = true;
                                $lineData[1] = trim($donnees[2]); // Mettre à jour l'ID utilisateur
                                $lineData[2] = trim($donnees[3]); // Mettre à jour le score utilisateur
                                $line = implode(';', $lineData) . PHP_EOL;
                            }

                            // Écrire la ligne mise à jour dans le fichier temporaire
                            fwrite($fpWrite, $line);
                        }

                        // Fermer le fichier de code
                        fclose($fpRead);
                    }

                    // Ajouter l'utilisateur au fichier temporaire si pas déjà présent
                    if(!$userInCodeFile){
                        fwrite($fpWrite, $donnees[0] . ';' . $donnees[2] . ';' . $donnees[3] . PHP_EOL);
                    }

                    // Fermer le fichier temporaire
                    fclose($fpWrite);

                    // Renommer le fichier temporaire en fichier de code
                    if(!rename($tempFile, $codeFile)){
                        echo "Impossible de renommer le fichier temporaire en $codeFile.";
                        exit;
                    }
                }
                break;
            }
        }
    }
    if($found){
        $_SESSION['code_valid'] = true;
        $_SESSION['message'] = "Le code est valide. Vous êtes dans le niveau : $niveau.";
    }
    else{
        $_SESSION['code_valid'] = false;
        $_SESSION['message'] = "Le code n'est pas valide. Veuillez réessayer.";
    }
}
else{
    $_SESSION['code_valid'] = false;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mode Groupe</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Entrez votre code de groupe</h2>

     <!-- Formulaire pour saisir le code de groupe -->
    <form class='niveau' action="groupe.php" method="post">
        <input type="text" name="code" required>
        <br>
        <button class='submitmdp' type="submit">Vérifier le code</button>
    </form>

     <!-- Afficher le message de session si il est disponible -->
    <?php if(isset($_SESSION['message'])): ?>
        <div class="niveau">
            <p><?php echo $_SESSION['message']; ?></p>
        </div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <!-- Afficher le contenu de l'application si le code est valide -->
    <?php if ($_SESSION['code_valid']): ?>
        <div class="app">
            <header>
                <span class="score">Score : <span id="score">0</span></span>
            </header>
            <h2><span id="num1">0</span> x <span id="num2">0</span> ?</h2>
            <input class='reponse' type="number" placeholder="Entrer la réponse" id="input">
            <button id="valider" class="valider">Valider</button>
            <div id="timer">00:00</div>
            <br>
            <div style="display: flex; justify-content: space-between;">
                <p id="resultat" style="text-align: left;"></p>
                <p id="bonusMessage" style="text-align: center;"></p>
            </div>
			<a id="finDuJeu" class="bouton" href="#" style="text-align: right;">Fin du jeu</a>

             <!-- Script pour gérer les événements de fin de jeu et mise à jour de l'URL -->
            <script>
                var niveau = "<?php echo $_SESSION['niveau']; ?>";
                var uniqid = "<?php echo isset($_SESSION['random_id']) ? $_SESSION['random_id'] : ''; ?>";

                // Ajouter un événement au clic sur le lien de fin de jeu
                document.getElementById('finDuJeu').addEventListener('click', function(event){
                    event.preventDefault();
                    var score = document.getElementById('score').textContent;
                    var url = "<?php echo $url; ?>/classe.php?score=" + score + "&niveau=" + niveau;
                    window.location.href = url;
                });
            </script>
            <script src="groupe.js"></script>
        </div>
    <?php endif; ?>
</body>
</html>
