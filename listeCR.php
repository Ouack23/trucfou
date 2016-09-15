<?php
include("include/functions.php");
include("include/config.php");?>

<html>
	<head>
		<meta charset="utf-8" />
		<title>Un projet de malade - CR</title>
		<link rel="stylesheet" href="style.css" />
		<link rel="icon" type="image/x-icon" href="favicon.ico" />
	</head>
	<body>
		<?php include_content("top");
		echo('<section id="main">');
			if(!$user->data['is_registered']) include('include/not_registered.php');
			
			else {
				print_debut_table([], ['N°', 'Date', 'Lien'], 'Liste des comptes-rendus', $current_url, $sort_array, 'other');
				
				$reponse = $bdd->query('SELECT id, '.format_date().' FROM CR'); 

				while($donnees = $reponse->fetch()) {
					echo('<tr><td class="left">'.$donnees['id'].'</td>');
					echo('<td>'.$donnees['date'].'</td>');
					echo('<td><a href="'.append_sid('listeCR.php', 'num='.$donnees['id'].'').'">Visualiser</a></tr>');
				}
				
				$reponse->closeCursor(); 
				?>
				</table></div>
			<?php
			}
		if(isset($_GET['num'])) include('include/CR.php');
		echo('</section>');
		include_content("bottom");?>
	</body>
</html>