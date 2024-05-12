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
    
    <form action=<?php echo $url."/professeur.php"; ?> method="post">
        <input type="text" name="code_classe" placeholder="Entrez le code de votre classe" required>
        <button type="submit_classe" name="afficher_resultats">Afficher les résultats</button>
    </form>

    <div id="donnees_classe">
        <?php
        include("parametre.php");

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["afficher_resultats"])) {
            $code_classe = $_POST["code_classe"] ?? '';

            $fichier_nom = "groupe_$code_classe.txt";
            if (file_exists($fichier_nom)) {
                $contenu = file($fichier_nom);

                echo "<table border='1'>";
                foreach ($contenu as $ligne) {
                    $donnees = explode(" ", $ligne);
                    echo "<tr>";
                    echo "<td>".$donnees[0]."</td>"; 
                    echo "<td>".$donnees[3]."</td>"; 
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "Aucune donnée trouvée pour ce code de classe.";
            }
        }
        ?>
    </div>

    <div id="message" style="display:none;"></div>
	<script type="text/javascript" src="professeur.js"></script>

</body>
</html>


<?php
include("parametre.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $code = $_POST["code"] ?? '';
    $niveau = $_POST["niveau"] ?? '';

    $contenu = "$code $niveau\n";

    $fichier = fopen("groupe.txt", "a+") or die("Impossible d'ouvrir le fichier.");

    // Vérifier si les données existent déjà dans le fichier
    $contenu_fichier = file_get_contents("groupe.txt");
    if (strpos($contenu_fichier, $contenu) === false) {
        fwrite($fichier, $contenu);
    }

    fclose($fichier);

    exit;
}
?>

