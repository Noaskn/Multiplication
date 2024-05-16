<?php
    $utilisateurs = array_filter(file("utilisateurs.txt", FILE_IGNORE_NEW_LINES));
    $utilisateurs = array_filter($utilisateurs, function($ligne) {
        return !empty(trim($ligne));
    });
    if(isset($_GET['action']) && isset($_GET['id'])){
        $action = $_GET['action'];
        $id = $_GET['id'];
        foreach($utilisateurs as $index => $utilisateur){
            $info = explode(";", $utilisateur);
            $utilisateurId = $info[2];
            if($utilisateurId == $id){
                if($action == 'bloquer'){
                    $info[5] = 'Oui';
                }
                elseif($action == 'debloquer'){
                    $info[5] = 'Non';
                }
                $utilisateurs[$index] = implode(";", $info);
                break;
            }
        }
        file_put_contents("utilisateurs.txt", implode("\n", $utilisateurs));
        header("Location: admin.php");
        exit();
    }
    $code_admin = file_get_contents("administrateur.txt");
     $message = '';
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $nouveau_code = $_POST['nouveau_code'] ?? '';
        if(!empty($nouveau_code)){
            file_put_contents("administrateur.txt", $nouveau_code);
            $code_admin = $nouveau_code;
            $message = "Le code a été mis à jour avec succès.";
        }
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Page Admin</title>
</head>
<body>
    <h2>Liste des Utilisateurs</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Nom d'utilisateur</th>
                <th>Mot de passe</th>
                <th>Identifiant</th>
                <th>Score</th>
                <th>Type</th>
                <th>Bloqué</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>

            <?php
            foreach($utilisateurs as $utilisateur){
                $info = explode(";", $utilisateur);
                $nom = $info[0];
                $mdp = $info[1];
                $id = $info[2];
                $score = $info[3];
                $type = $info[4];
                $bloque = isset($info[5]) && $info[5] == 'Oui' ? 'Oui' : 'Non';
                ?>
                <tr>
                    <td><?php echo $nom; ?></td>
                    <td><?php echo $mdp; ?></td>
                    <td><?php echo $id; ?></td>
                    <td><?php echo $score; ?></td>
                    <td><?php echo $type; ?></td>
                    <td><?php echo $bloque; ?></td>
                    <td>
                        <?php if ($bloque == 'Non') : ?>
                            <a href="admin.php?action=bloquer&id=<?php echo $id; ?>">Bloquer</a>
                        <?php else : ?>
                            <a href="admin.php?action=debloquer&id=<?php echo $id; ?>">Débloquer</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php
            }
            ?>

        </tbody>
    </table>
    <h2>Modifier le code administrateur</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="nouveau_code">Nouveau code administrateur :</label>
        <input type="number" id="nouveau_code" name="nouveau_code" required>
        <button type="submit">Valider</button>
    </form>

    <?php if (!empty($message)) : ?>
    <p><?php echo $message; ?></p>
    <?php endif; ?>

    <h3>Code administrateur actuel :</h3>
    <p><?php echo $code_admin; ?></p>
    <a href="index.php"><button>Retourner à l'accueil</button></a>
</body>
</html>
