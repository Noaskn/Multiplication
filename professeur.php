<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Espace professeur</title>
	<link rel="stylesheet" href="styles.css">
</head>
<body>

     <!-- Section pour générer un code aléatoire -->
	<div class='niveau'>
    <h4>Génération d'un code aléatoire pour créer un groupe :</h4>

    <!-- Formulaire pour générer et enregistrer le code -->
    <form class="niveau">
        <button type="button" onclick="genererCode()" placeholder="Obligatoire" required>Générer un code</button>
		<br>
        <input type="text" id="code" readonly>
        <div>
            <input id="facile" type="radio" name="niveau" value="facile" checked="checked">
            <label for="facile">Facile</label>
            <input id="intermediaire" type="radio" name="niveau" value="intermediaire">
            <label for="intermediaire">Intermediaire</label>
            <input id="difficile" type="radio" name="niveau" value="difficile">
            <label for="difficile">Difficile</label>
        </div>
        <p><button class="submitcode" type="button" onclick="enregistrer()">Enregistrer</button></p>

         <!-- Zone pour afficher les messages -->
		<div id="message" style="display: none;"></div>
	</form><br>

     <!-- Section pour entrer le code de classe et afficher les résultats -->
    <h4>Indiquez le code de votre classe :</h4>

     <!-- Formulaire pour entrer le code de classe -->
    <p><form class="niveau" action="professeur.php" method="post">
        <input type="text" name="code_classe" placeholder="Entrez le code de votre classe" required>
		<br><br>
        <button type="submit">Afficher les résultats</button>
    </form></p>

     <!-- Section pour afficher les résultats si le code de classe est renseigné -->
    <?php if(!empty($_POST['code_classe'])): ?>
    <div id="donnees_classe">
        <?php
        include("parametre.php");
        $url = ''; 
        $code_classe = $_POST["code_classe"] ?? '';

        // Nom du fichier basé sur le code de classe
        $fichier_nom = "$code_classe.txt";
        if(file_exists($fichier_nom)){
            $contenu = file($fichier_nom);
            $donnees_scores = [];
            foreach($contenu as $ligne){
                $donnees = explode(";", $ligne);
                $score = intval($donnees[2]); 
                $donnees_scores[$score] = $donnees;
            }

            // Trier les scores dans l'ordre décroissant
            krsort($donnees_scores);
            echo "<table border='1'>";
            echo "<caption>HIGHSCORE</caption>";
            echo "<tr>";
            echo "<th>Classement</th>";
            echo "<th>Nom de l'utilisateur</th>";
            echo "<th>Score</th>";
            echo "</tr>";
            $classement = 1;

            // Affiche le classement, le nom d'utilisateur et le score
            foreach($donnees_scores as $donnees){
                echo "<tr>";
                echo "<td>".$classement."</td>"; 
                echo "<td>".$donnees[0]."</td>"; 
                echo "<td>".$donnees[2]."</td>"; 
                echo "</tr>";
                $classement++; 
            }
            echo "</table>";
        }
        else{

            // Message d'erreur si le code saisi n'existe pas
            echo "Aucune donnée trouvée pour ce code de classe.";
        }
        ?>
        </div><br>

        // Trier les scores dans l'ordre décroissant
        <div class="niveau" id="recherche_utilisateur">
            <h4>Recherche d'utilisateur</h4>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <input type="hidden" name="code_classe" value="<?php echo isset($_POST['code_classe']) ? $_POST['code_classe'] : ''; ?>">
                <label for="search_user">Rechercher un utilisateur :</label>
                <input type="text" id="search_user" name="search_user">
                <input type="submit" value="Rechercher">
            </form>
			<br>
            <?php
            if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["search_user"])){

                // Récupère la recherche et le code de classe depuis le formulaire POST
                $search_term = $_POST["search_user"];
                $code_classe = $_POST["code_classe"];

                // Vérifie que la recherche et le code de classe ne sont pas vides
                if(!empty($search_term) && !empty($code_classe)){
                    $fichier_nom = "$code_classe.txt";

                    // Vérifie si le fichier existe
                    if(file_exists($fichier_nom)){

                        // Lit le contenu du fichier dans un tableau
                        $contenu = file($fichier_nom);
                        $found = false;

                        // Parcourt chaque ligne du fichier
                        foreach($contenu as $ligne){
                            $donnees = explode(";", $ligne);

                             // Vérifie si la recherche est présent dans le nom d'utilisateur
                            if(stripos($donnees[0], $search_term) !== false){

                                // Affiche les résultats de la recherche
                                echo "<p>Résultat de la recherche : <p>";
                                echo "<p>Nom d'utilisateur : ".$donnees[0]."<p>";
                                echo "<p>Score : ".$donnees[2]."</p>";
                                $found = true;
                            }
                        }

                        // Si aucun utilisateur n'est trouvé, affiche un message d'erreur
                        if(!$found){
                            echo "Utilisateur non trouvé.";
                        }
                    }
                    else{

                        // Si le fichier n'existe pas, affiche un message d'erreur
                        echo "Aucune donnée trouvée pour ce code de classe.";
                    }
                }
                else{

                     // Si la recherche ou le code de classe est vide, affiche un message d'erreur
                    echo "Veuillez entrer un terme de recherche et un code de classe valide.";
                }
            }
            ?>
            </div>			
            <br><br>
    <?php endif; ?>
    <div id="message" style="display:none;"></div>
	</div>

     <!-- Lien pour changer le mot de passe -->
	<div>
		<a class='mdp' href="password.php">Changer le mot de passe</a>
	</div>

    <!-- Lien vers le script JavaScript -->
    <script type="text/javascript" src="professeur.js"></script>

     <!-- Bouton pour retourner à la page d'accueil -->
    <a href="index.php"><button>Retourner à l'accueil</button></a>
</body>
</html>

<?php
include("parametre.php");
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Récupère les valeurs des champs "code" et "niveau" depuis la requête POST, ou les initialise à une chaîne vide si non définis
    $code = $_POST["code"] ?? '';
    $niveau = $_POST["niveau"] ?? '';

    // Vérifie que les variables $code et $niveau ne sont pas vides
    if($code!='' && $niveau!=''){
        $contenu = "$code $niveau\n";

        // Ouvre le fichier "groupe.txt" en mode ajout ou affiche une erreur si le fichier ne peut pas être ouvert
        $fichier = fopen("groupe.txt", "a") or die("Impossible d'ouvrir le fichier.");

        // Écrit le contenu dans le fichier
        fwrite($fichier, $contenu);

        // Ferme le fichier après écriture
        fclose($fichier);
    }
    exit;
}
?>
