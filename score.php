<?php
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $score = $_GET['score'] ?? '';
    $userid = $_GET['userid'] ?? '';

    if (!empty($score) && !empty($userid)) {
        $file = 'utilisateurs.txt';
        $lines = file($file);
        $fp = fopen($file, 'w');
        foreach ($lines as $line) {
            $parts = explode(';', $line);
            if ($parts[2] == $userid) {
                $parts[3] = $score;
                $line = implode(';', $parts);
            }
            fwrite($fp, $line);
        }
        fclose($fp);
    }
}
function comparerScores($a, $b) {
    return $b['score'] - $a['score'];
}
$utilisateurs = [];
$fichier = fopen("utilisateurs.txt", "r");
while ($ligne = fgets($fichier)) {
    $donnees = explode(";", $ligne);
    if (isset($donnees[0]) && isset($donnees[1]) && isset($donnees[4]) && trim($donnees[4]) == "Elève") {
        $utilisateurs[] = ['nom' => $donnees[0], 'score' => intval($donnees[3])];
    }
}
fclose($fichier);
usort($utilisateurs, 'comparerScores');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles.css">
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
                <td><?php echo $utilisateur['nom']; ?></td>
                <td><?php echo $utilisateur['score']; ?></td>
            </tr>
            <?php 
            $classement++;
            ?>
        <?php endforeach; ?>
    </table>
    <a href="index.php"><button>Retourner à l'accueil</button></a>
</body>
</html>
