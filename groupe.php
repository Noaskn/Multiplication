<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $code = $_POST['code'] ?? '';
    $found = false;
    $niveau = '';

    $lines = file('groupe.txt');
    foreach ($lines as $line) {
        list($codeFromFile, $niveauFromFile) = explode(" ", trim($line));
        if ($codeFromFile == $code) {
            $found = true;
            $niveau = $niveauFromFile;
            break;
        }
    }

    if ($found) {
        // Créer ou ouvrir le fichier avec le nom de code
        $filename = $code . ".txt";
        file_put_contents($filename, $data);

        echo "Le code est valide. Vous êtes dans le niveau : $niveau.";
    }
    else {
        echo "Le code n'est pas valide. Veuillez réessayer.";
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Groupe</title>
</head>
<body>
    <h2>Entrez votre code de groupe</h2>
    <form action="groupe.php" method="post">
        <input type="text" name="code" required>
        <button type="submit">Vérifier le code</button>
    </form>
</body>
</html>
