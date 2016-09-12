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
			echo('<h1>Liste des comptes-rendus</h1>');
			
			if(! $user->data['is_registered']) include('include/not_registered.php');
			
			else { ?>
			<div id="table">
				<table>
					<tr class="top">
						<td class="left">N°</td>
						<td>Date</td>
						<td>Lien</td>
					</tr>
					<?php
					$reponse = $bdd->query('SELECT id, '.format_date().' FROM CR'); 

					while($donnees = $reponse->fetch()) {
						echo('<tr><td class="left">'.$donnees['id'].'</td>');
						echo('<td>'.$donnees['date'].'</td>');
						echo('<td><a href="'.append_sid('listeCR.php', 'num='.$donnees['id'].'').'">Visualiser</a></tr>');
					}
					$reponse->closeCursor(); 
					?>
				</table>
			</div>
			<?php
			}
		if(isset($_GET['num'])) {include('include/CR.php');}
		echo('</section>');
		include_content("bottom");?>
	</body>
</html>