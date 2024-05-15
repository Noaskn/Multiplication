<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Page principale</title>
</head>
<body>
    <p>Génération d'un code aléatoire pour créer un groupe :</p>
    
    <form>
        <input type="text" id="code" readonly>
        <button type="button" onclick="genererCode()"  placeholder="Oblogatoire"  required>Générer un code</button>
        <div>
            <input id="facile" type="radio" name="niveau" value="facile" checked="checked">
            <label for="facile">Facile</label>
            
            <input id="moyen" type="radio" name="niveau" value="moyen">
            <label for="moyen">Moyen</label>
            
            <input id="difficile" type="radio" name="niveau" value="difficile">
            <label for="difficile">Difficile</label>
        </div>
        
        <button type="button" onclick="enregistrer()">Enregistrer</button>
    </form>
    
    <p>Indiquez le code de votre classe :</p>
    
    <form action="<?php echo $url."/professeur.php"; ?>" method="post">
    <input type="text" name="code_classe" placeholder="Entrez le code de votre classe" required>
    <button type="submit">Afficher les résultats</button>
</form>


    <?php if (!empty($_POST['code_classe'])): ?>
    <div id="donnees_classe">
        <?php
        include("parametre.php");

        $code_classe = $_POST["code_classe"] ?? '';

        $fichier_nom = "$code_classe.txt";
        if (file_exists($fichier_nom)) {
            $contenu = file($fichier_nom);

            $donnees_scores = [];
            foreach ($contenu as $ligne) {
                $donnees = explode(";", $ligne);
                $score = intval($donnees[3]); 
                $donnees_scores[$score] = $donnees;
            }

            krsort($donnees_scores);

            echo "<table border='1'>";
            echo "<caption>HIGHSCORE</caption>";
            echo "<tr>";
            echo "<th>Classement</th>";
            echo "<th>Nom de l'utilisateur</th>";
            echo "<th>Score</th>";
            echo "</tr>";

            $classement = 1;
            foreach ($donnees_scores as $donnees) {
                echo "<tr>";
                echo "<td>".$classement."</td>"; 
                echo "<td>".$donnees[0]."</td>"; 
                echo "<td>".$donnees[3]."</td>"; 
                echo "</tr>";
                $classement++; 
            }
            echo "</table>";
        } else {
            echo "Aucune donnée trouvée pour ce code de classe.";
        }
        ?>
    </div>
    
    <div id="recherche_utilisateur">
        <h2>Recherche d'utilisateur</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="hidden" name="code_classe" value="<?php echo isset($_POST['code_classe']) ? $_POST['code_classe'] : ''; ?>">
            <label for="search_user">Rechercher un utilisateur :</label>
            <input type="text" id="search_user" name="search_user">
            <input type="submit" value="Rechercher">
        </form>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["search_user"])) {
            $search_term = $_POST["search_user"];
            $code_classe = $_POST["code_classe"];

            if (!empty($search_term) && !empty($code_classe)) {
                $fichier_nom = "$code_classe.txt";
                if (file_exists($fichier_nom)) {
                    $contenu = file($fichier_nom);
                    $found = false;

                    foreach ($contenu as $ligne) {
                        $donnees = explode(";", $ligne);
                        if (stripos($donnees[0], $search_term) !== false) {
                            echo "<p>Résultat de la recherche : <p>";
                            echo "<p>Nom d'utilisateur : ".$donnees[0]."<p>";
                            echo "<p>Score : ".$donnees[3]."</p>";
                            $found = true;
                        }
                    }

                    if (!$found) {
                        echo "Utilisateur non trouvé.";
                    }
                } else {
                    echo "Aucune donnée trouvée pour ce code de classe.";
                }
            } else {
                echo "Veuillez entrer un terme de recherche et un code de classe valide.";
            }
        }
        ?>
    </div>
    <?php endif; ?>

    <div id="message" style="display:none;"></div>
    <script type="text/javascript" src="professeur.js"></script>
    <a href="index.php"><button>Retourner à l'accueil</button></a>
</body>
</html>
