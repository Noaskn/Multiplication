<?php
if($_SERVER["REQUEST_METHOD"] == "GET"){

    // Récupère les valeurs des paramètres 'score' et 'userid' depuis la requête GET, ou les initialise à une chaîne vide si non définis
    $score = $_GET['score'] ?? '';
    $userid = $_GET['userid'] ?? '';

    // Vérifie que les variables $score et $userid ne sont pas vides
    if(!empty($score) && !empty($userid)){

        // Fichier contenant les informations des utilisateurs
        $file = 'utilisateurs.txt';

        // Lit toutes les lignes du fichier dans un tableau
        $lines = file($file);

        // Ouvre le fichier en mode écriture
        $fp = fopen($file, 'w');

        // Parcourt chaque ligne du fichier
        foreach($lines as $line){
            $parts = explode(';', $line);

            // Vérifie si l'index 2 correspond à l'identifiant
            if(isset($parts[2]) && $parts[2] == $userid){

                // Met à jour le score de l'utilisateur
                $parts[3] = $score;
                $line = implode(';', $parts);
            }

            // Écrit la ligne (mise à jour ou non) dans le fichier
            fwrite($fp, $line);
        }

        // Ferme le fichier après écriture
        fclose($fp);
    }
}

// Fonction pour comparer les scores des utilisateurs pour le tri
function comparerScores($a, $b){
    return $b['score'] - $a['score'];
}

$utilisateurs = [];

// Ouvre le fichier "utilisateurs.txt" en mode lecture
$fichier = fopen("utilisateurs.txt", "r");

// Parcourt chaque ligne du fichier
while($ligne = fgets($fichier)){
    $donnees = explode(";", $ligne);

    // Vérifie que les segments nécessaires existent et que l'utilisateur a le rôle "Elève"
    if(isset($donnees[0], $donnees[1], $donnees[4]) && trim($donnees[4]) == "Elève"){

        // Ajoute l'utilisateur et son score dans le tableau des utilisateurs
        $utilisateurs[] = ['nom' => $donnees[0], 'score' => intval($donnees[3] ?? 0)];
    }
}

// Ferme le fichier après lecture
fclose($fichier);

// Trie les utilisateurs par score en utilisant la fonction de comparaison
usort($utilisateurs, 'comparerScores');

// Initialisation des variables pour les résultats de recherche
$search_result = [];
$searched_username = "";
$searched_score = "";
$searched_rank = "";
$search_not_found = false;
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Récupère la valeur du champ 'search' ou l'initialise à une chaîne vide si non définie
    $search_term = $_POST['search'] ?? '';

    // Vérifie que la variable $search_term n'est pas vide
    if(!empty($search_term)){

        // Parcourt chaque utilisateur dans le tableau des utilisateurs
        foreach($utilisateurs as $classement => $utilisateur){

            // Vérifie si le nom de l'utilisateur contient la recherche
            if(stripos($utilisateur['nom'], $search_term) !== false){

                // Ajoute l'utilisateur aux résultats de recherche
                $search_result[] = $utilisateur;
                $searched_username = $utilisateur['nom'];
                $searched_score = $utilisateur['score'];
                $searched_rank = $classement + 1;
                break;
            }
        }

        // Si aucun utilisateur correspondant n'est trouvé, met à jour la variable $search_not_found
        if(empty($search_result)){
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

<!-- Formulaire de recherche d'utilisateur -->
<body>
	<div class='highscore'>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="search" class="recherche">Rechercher un utilisateur :</label>
        <input type="text" id="search" name="search" class="recherche">
        <input type="submit" value="Rechercher" class="recherche">
    </form>
	<br>

    <!-- Affichage des résultats de recherche -->
    <?php if($search_not_found): ?>
        <div class="recherche">
            <h3>Résultats de la recherche :</h3>
            <p>L'utilisateur recherché est introuvable.</p>
        </div>
    <?php elseif(!empty($search_result)): ?>
        <div class="recherche">
            <h3>Résultats de la recherche :</h3>
            <p>Nom d'utilisateur : <?php echo htmlspecialchars($searched_username); ?></p>
            <p>Classement : <?php echo htmlspecialchars($searched_rank); ?></p>
            <p>Score : <?php echo htmlspecialchars($searched_score); ?></p>
        </div>
    <?php endif; ?>
	</div>

    <!-- Tableau des scores -->
    <table border="1">
        <caption>HIGHSCORE</caption>
        <tr>
            <th>Classement</th>
            <th>Nom de l'utilisateur</th>
            <th>Score</th>
        </tr>
        <?php 
        $classement = 1;
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

    <!-- Bouton de retour à l'accueil -->
    <a href="index.php"><button>Retourner à l'accueil</button></a>
</body>
</html>
