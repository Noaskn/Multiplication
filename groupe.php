<?php
session_start();

$url='';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $code = $_POST['code'] ?? '';
    $found = false;
    $niveau = '';

    if (file_exists('groupe.txt') && is_readable('groupe.txt')) {
        $lines = file('groupe.txt');
        foreach ($lines as $line) {
            list($codeFromFile, $niveauFromFile) = explode(" ", trim($line));
            if ($codeFromFile == $code) {
                $found = true;
                $niveau = $niveauFromFile;
                $_SESSION['niveau'] = $niveau;
                $_SESSION['code'] = $code;
                $utilisateurFile = 'utilisateurs.txt';
                $userInfo = '';
                $userFound = false;

                if (file_exists($utilisateurFile) && is_readable($utilisateurFile)) {
                    $fp = fopen($utilisateurFile, 'r');
                    while ($ligne = fgets($fp)) {
                        $donnees = explode(";", $ligne);
                        if (isset($donnees[2]) && trim($donnees[2]) == $_SESSION['random_id']) {
                            $userInfo = $ligne;
                            $userFound = true;
                            break;
                        }
                    }
                    fclose($fp);
                }

                if ($userFound) {
                    $codeFile = $code . '.txt';
                    $tempFile = 'temp.txt';

                    if (file_exists($codeFile) && is_readable($codeFile)) {
                        $fpRead = fopen($codeFile, 'r');
                        $fpWrite = fopen($tempFile, 'w');
                        $userInCodeFile = false;
                        while ($line = fgets($fpRead)) {
                            $lineData = explode(";", $line);
                            if (isset($lineData[0]) && trim($lineData[0]) == trim($donnees[0])) {
                                $userInCodeFile = true;
                                $lineData[1] = trim($donnees[2]);
                                $lineData[2] = trim($donnees[3]);
                                $line = implode(';', $lineData) . PHP_EOL;
                            }
                            fwrite($fpWrite, $line);
                        }
                        if (!$userInCodeFile) {
                            fwrite($fpWrite, $donnees[0] . ';' . $donnees[2] . ';' . $donnees[3] . PHP_EOL);
                        }

                        fclose($fpRead);
                        fclose($fpWrite);

                        rename($tempFile, $codeFile);
                    }
                }
                break;
            }
        }
    }

    if ($found) {
        echo "Le code est valide. Vous êtes dans le niveau : $niveau.";
        $_SESSION['code_valid'] = true;
    } else {
        echo "Le code n'est pas valide. Veuillez réessayer.";
        $_SESSION['code_valid'] = false;
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
    <div class="app" style="display: <?php echo isset($_SESSION['code_valid']) && $_SESSION['code_valid'] ? 'block' : 'none'; ?>;">
        <header>
            <span class="score">Score : <span id="score">0</span></span>
        </header>
        <h2><span id="num1">0</span> x <span id="num2">0</span> ?</h2>
        <input type="number" placeholder="Entrer la réponse" id="input">
        <button id="valider" class="valider">Valider</button>
        <div id="timer">00:00</div>
        <p id="bonusMessage"></p>
        <p id="resultat"></p>
    </div>
    <?php if (isset($_SESSION['code_valid']) && $_SESSION['code_valid']): ?>
    <script>
        var niveau = "<?php echo $_SESSION['niveau']; ?>";
        var uniqid = "<?php echo isset($_SESSION['random_id']) ? $_SESSION['random_id'] : ''; ?>";
    </script>
    <a class="bouton" id='classe' href="<?php echo $url."/classe.php"; ?>">Fin du jeu</a>
    <script src="groupe.js"></script>
    <?php endif; ?>
</body>
</html>
