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
            if (isset($parts[2]) && $parts[2] == $userid) {
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
    if (isset($donnees[0], $donnees[1], $donnees[4]) && trim($donnees[4]) == "Elève") {
        $utilisateurs[] = ['nom' => $donnees[0], 'score' => intval($donnees[3] ?? 0)];
    }
}
fclose($fichier);
usort($utilisateurs, 'comparerScores');

$search_result = [];
$searched_username = "";
$searched_score = "";
$searched_rank = "";
$search_not_found = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search_term = $_POST['search'] ?? '';
    if (!empty($search_term)) {
        foreach ($utilisateurs as $classement => $utilisateur) {
            if (stripos($utilisateur['nom'], $search_term) !== false) {
                $search_result[] = $utilisateur;
                $searched_username = $utilisateur['nom'];
                $searched_score = $utilisateur['score'];
                $searched_rank = $classement + 1;
                break;
            }
        }
        if (empty($search_result)) {
            $search_not_found = true;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>HighScore</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
	<div class='highscore'>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="search" class="recherche">Rechercher un utilisateur :</label>
        <input type="text" id="search" name="search" class="recherche">
        <input type="submit" value="Rechercher" class="recherche">
    </form>
	<br>
    <?php if ($search_not_found): ?>
        <div class="recherche">
            <h3>Résultats de la recherche :</h3>
            <p>L'utilisateur recherché est introuvable.</p>
        </div>
    <?php elseif (!empty($search_result)): ?>
        <div class="recherche">
            <h3>Résultats de la recherche :</h3>
            <p>Nom d'utilisateur : <?php echo htmlspecialchars($searched_username); ?></p>
            <p>Classement : <?php echo htmlspecialchars($searched_rank); ?></p>
            <p>Score : <?php echo htmlspecialchars($searched_score); ?></p>
        </div>
    <?php endif; ?>
	
	</div>
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
