<?php

    // Récupération de toutes les lignes du fichier "utilisateurs.txt" et suppression des lignes vides
    $utilisateurs = array_filter(file("utilisateurs.txt", FILE_IGNORE_NEW_LINES));
    $utilisateurs = array_filter($utilisateurs, function($ligne){
        return !empty(trim($ligne));
    });

    // Vérification si une action et un identifiant sont présents dans les paramètres GET
    if(isset($_GET['action']) && isset($_GET['id'])){
        $action = $_GET['action'];
        $id = $_GET['id'];

        // Parcours des utilisateurs pour trouver celui correspondant à l'identifiant
        foreach($utilisateurs as $index => $utilisateur){
            $info = explode(";", $utilisateur);
            $utilisateurId = $info[2];
            if($utilisateurId == $id){

                // Blocage ou déblocage de l'utilisateur
                if($action == 'bloquer'){
                    $info[5] = 'Oui';
                }
                elseif($action == 'debloquer'){
                    $info[5] = 'Non';
                }

                // Mise à jour de l'utilisateur dans le tableau
                $utilisateurs[$index] = implode(";", $info);
                break;
            }
        }

        // Sauvegarde des modifications dans le fichier "utilisateurs.txt"
        file_put_contents("utilisateurs.txt", implode("\n", $utilisateurs));

        // Redirection vers la page admin pour éviter le renvoi de formulaire
        header("Location: admin.php");
        exit();
    }

    // Récupération du code administrateur actuel
    $code_admin = file_get_contents("administrateur.txt");
    $message = '';
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $nouveau_code = $_POST['nouveau_code'] ?? '';
        if(!empty($nouveau_code)){

            // Mise à jour du code administrateur dans le fichier "administrateur.txt"
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
    <link rel="stylesheet" href="styles.css">
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

            // Affichage de la liste des utilisateurs
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
                        <?php if($bloque == 'Non') : ?>
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

    <!-- Formulaire pour modifier le code administrateur -->
    <form class='niveau' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="nouveau_code">Nouveau code administrateur :</label>
        <input type="number" id="nouveau_code" name="nouveau_code" required>
        <button type="submit">Valider</button>
    </form>
    <br>
    <?php

        // Affichage du message de confirmation si le code a été mis à jour
        if($message !== ""){
            echo "<h5>$message</h5>";
        }
    ?>
    <br><br>
    <div class='niveau'>
        <h3>Code administrateur actuel :</h3>
        <p><?php echo $code_admin; ?></p>
    </div>

    <!-- Bouton pour retourner à la page d'accueil -->
    <a href="index.php"><button>Retourner à l'accueil</button></a>
</body>
</html>
