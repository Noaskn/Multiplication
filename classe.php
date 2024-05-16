<?php
    session_start();
    if($_SERVER["REQUEST_METHOD"] == "GET"){
        $score = $_GET['score'] ?? '';
        $userid = $_GET['random_id'] ?? $_SESSION['random_id'] ?? '';
        $code = $_SESSION['code'] ?? '';
        $file = $code . '.txt';
        if(file_exists($file) && is_writable($file)){
            $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $fp = fopen($file, 'w');
            if($fp){
                $updated = false;
                foreach($lines as $line){
                    $parts = explode(';', $line);
                    if(count($parts) >= 3 && trim($parts[1]) == trim($userid)){
                        $parts[2] = $score;
                        $line = implode(';', $parts);
                        $updated = true;
                    }
                    fwrite($fp, $line . PHP_EOL);
                }
                fclose($fp);
            } 
        } 
    }

    function comparerScores($a, $b){
        return $b['score'] - $a['score'];
    }

    $utilisateurs = [];

    if(!empty($code)){
        $fichier = fopen($code . '.txt', "r");
        while($ligne = fgets($fichier)){
            $donnees = explode(";", $ligne);
            if(isset($donnees[0]) && isset($donnees[2])){
                $utilisateurs[] = ['nom' => trim($donnees[0]), 'score' => intval(trim($donnees[2]))];
            }
        }
        fclose($fichier);
        usort($utilisateurs, 'comparerScores');
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>HighScore</title>
</head>
<body>
    <table border="1">
        <caption>HIGHSCORE</caption>
        <tr>
            <th>Classement</th>
            <th>Nom de l'utilisateur</th>
            <th>Score</th>
        </tr>

        <?php 
        $classement = 1;
        foreach ($utilisateurs as $utilisateur): 
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
    <a href="index.php"><button>Retourner à l'accueil</button></a>
</body>
</html>
