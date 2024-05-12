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

    <div id="message" style="display:none;"></div>
	<script type="text/javascript" src="professeur.js"></script>

</body>
</html>


<?php
include("parametre.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $code = $_POST["code"] ?? '';
    $niveau = $_POST["niveau"] ?? '';

    $contenu = "$code $niveau\n"; // Ajout d'un saut de ligne après chaque enregistrement

    $fichier = fopen("groupe.txt", "a") or die("Impossible d'ouvrir le fichier.");

    fwrite($fichier, $contenu);

    fclose($fichier);

    exit;
}
?>
