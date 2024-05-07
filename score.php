<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles.css">
    <title>HighScore</title>
</head>
<body>
    <table border=  « 1 » >
	<caption> HIGHSCORE </caption>
	<tr>  
		<th> Nom de l'utilisateur </th> 
		<th> Score </th> 
	</tr> 
	<tr>  
		<th> </th> 
		<th> </th> 
	</tr> 
	<?php
	$fichier = fopen("utilisateurs.txt", "r");

	for ($i = 0; $i < 10; $i++) {
		$ligne = fgets($fichier);

		if ($ligne !== false) {
			$donnees = explode(";", $ligne);
        
			if (isset($donnees[0]) && isset($donnees[1])) {
				?>
				<tr>
					<td><?php echo $donnees[0]; ?></td>
					<td><?php echo $donnees[1]; ?></td>
				</tr>
				<?php
			}
		}
	}

	fclose($fichier);
	?>

	
	</table >

</body>
</html>
