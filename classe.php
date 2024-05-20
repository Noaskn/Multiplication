<?php

// Démarrage de la session pour accéder aux variables de session
session_start();

// Initialisation de l'URL
$url = '';
if($_SERVER["REQUEST_METHOD"] == "GET"){

    // Récupération des valeurs 'score', 'code' et 'random_id' des paramètres GET ou de la session
    $score = $_GET['score'] ?? '';
    $userid = $_GET['random_id'] ?? $_SESSION['random_id'] ?? '';
    $code = $_SESSION['code'] ?? '';

    // Détermination du nom du fichier à partir du code
    $file = $code . '.txt';

    // Vérification si le fichier existe et est accessible en écriture
    if(file_exists($file) && is_writable($file)){

        // Lecture du fichier ligne par ligne, en ignorant les lignes vides
        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        // Ouverture du fichier en mode écriture
        $fp = fopen($file, 'w');
        if($fp){
            $updated = false;

            // Parcours de chaque ligne du fichier
            foreach($lines as $line){
                $parts = explode(';', $line);

                // Mise à jour du score de l'utilisateur si l'identifiant correspond
                if(count($parts) >= 3 && trim($parts[1]) == trim($userid)){
                    $parts[2] = $score;
                    $line = implode(';', $parts);
                    $updated = true;
                }

                // Écriture de la ligne dans le fichier
                fwrite($fp, $line . PHP_EOL);
            }

            // Fermeture du fichier
            fclose($fp);
        }
        else{

            // Message d'erreur si le fichier ne peut pas être ouvert en écriture
            echo "Échec de l'ouverture du fichier pour écriture.";
        }
    }
    else{

        // Message d'erreur si le fichier n'existe pas ou n'est pas accessible en écriture
        echo "Le fichier n'existe pas ou n'est pas accessible en écriture.";
    }
}

// Fonction pour comparer les scores des utilisateurs pour le tri
function comparerScores($a, $b){
    return $b['score'] - $a['score'];
}

$utilisateurs = [];

// Vérification si le code n'est pas vide
if(!empty($code)){
    $file = $code . '.txt';

    // Vérification si le fichier existe et est accessible en lecture
    if(file_exists($file) && is_readable($file)){

        // Ouverture du fichier en mode lecture
        $fichier = fopen($file, "r");
        if($fichier){

            // Lecture du fichier ligne par ligne
            while($ligne = fgets($fichier)){
                $donnees = explode(";", $ligne);

                // Ajout des données de l'utilisateur dans le tableau des utilisateurs
                if(isset($donnees[0]) && isset($donnees[2])){
                    $utilisateurs[] = ['nom' => trim($donnees[0]), 'score' => intval(trim($donnees[2]))];
                }
            }

            // Fermeture du fichier
            fclose($fichier);

            // Tri des utilisateurs par score décroissant
            usort($utilisateurs, 'comparerScores');
        }
        else{

            // Message d'erreur si le fichier ne peut pas être ouvert en lecture
            echo "Échec de l'ouverture du fichier pour lecture.";
        }
    }
    else{

        // Message d'erreur si le fichier n'existe pas ou n'est pas accessible en lecture
        echo "Le fichier n'existe pas ou n'est pas accessible en lecture.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>HighScore </title>
	<link rel="stylesheet" href="styles.css">
</head>
<body>

    <!-- Table d'affichage des meilleurs scores -->
    <table border="1">
        <caption>HIGHSCORE</caption>
        <tr>
            <th>Classement</th>
            <th>Nom de l'utilisateur</th>
            <th>Score</th>
        </tr>
        <?php 
        $classement = 1;

        // Affichage de chaque utilisateur et son score
        foreach($utilisateurs as $utilisateur): 
        ?>
            <tr>
                <td><?php echo $classement; ?></td>
                <td><?php echo htmlspecialchars($utilisateur['nom']); ?></td>
                <td><?php echo htmlspecialchars($utilisateur['score']); ?></td>
            </tr>
            <?php 
            $classement++;
            ?>
        <?php endforeach; ?>
    </table>
	<br>

    <!-- Bouton pour retourner à la page d'accueil -->
    <a href="index.php"><button>Retourner à l'accueil</button></a>
</body>
</html>
