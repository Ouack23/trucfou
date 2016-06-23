<?php
if(!$user->data['is_registered']) include('include/not_registered.php');

else {
	secureGet();
	if(!isset($_GET['annonce'])) { ?>
	<h1>Liste des annonces</h1>
	
	<div id="table">
		<table>
			<tr class="top">
				<td class="left"><a href="?orderBy=id&reverse=<?php printReverse('annonces', 'id'); ?>">N°</a></td>
				<td><a href="?orderBy=date&reverse=<?php printReverse('annonces', 'date'); ?>">Date</a></td>
				<td><a href="?orderBy=auteur&reverse=<?php printReverse('annonces', 'auteur'); ?>">Auteur</a></td>
				<td><a href="?orderBy=lieu&reverse=<?php printReverse('annonces', 'lieu'); ?>">Lieu</a></td>
				<td><a href="?orderBy=superf_h&reverse=<?php printReverse('annonces', 'superf_h'); ?>">Superficie habitable</a></td>
				<td><a href="?orderBy=superf_t&reverse=<?php printReverse('annonces', 'superf_t'); ?>">Superficie terrain</a></td>
				<td><a href="?orderBy=price&reverse=<?php printReverse('annonces', 'price'); ?>">Prix</a></td>
				<td>Lien</td>
				<td>Commentaires</td>
			</tr>
			<?php
			$dateQuery = format_Date();
	
			if($current_url_reverse == "false") $reponse_query = 'SELECT id, '.$dateQuery.', auteur, lieu, superf_h, superf_t, price, link FROM annonces ORDER BY '.$current_url_order.'';
			else 								$reponse_query = 'SELECT id, '.$dateQuery.', auteur, lieu, superf_h, superf_t, price, link FROM annonces ORDER BY '.$current_url_order.' DESC';
	
			$reponse = $bdd->query($reponse_query);
	
			while($donnees = $reponse->fetch()) {
				echo('<tr><td class="left">'.$donnees['id'].'</td>');
				echo('<td>'.$donnees['date'].'</td>');
				echo('<td>'.$donnees['auteur'].'</td>');
				echo('<td>'.$donnees['lieu'].'</td>');
				echo('<td>'.$donnees['superf_h'].'</td>');
				echo('<td>'.$donnees['superf_t'].'</td>');
				echo('<td>'.$donnees['price'].' €</td>');
				echo('<td><a href='.$donnees['link'].'>Annonce</a></td>');
				echo('<td><a href=comments.php?annonce='.$donnees['id'].'>Commentaires</a></td></tr>');
			}
			$reponse->closeCursor(); 
			?>
		</table>
	</div>
<?php
	}
	//Si on ne veut afficher qu'une seule annonce
	else { ?>
		<div id="table">
			<table>
				<tr class="top">
					<td class="left">N°</td>
					<td>Date</td>
					<td>Auteur</td>
					<td>Lieu</td>
					<td>Superficie habitable</td>
					<td>Superficie terrain</td>
					<td>Prix</td>
					<td>Lien</td>
					<td>Commentaires</td>
				</tr>
				<?php
				$dateQuery = format_Date();
				$reponse_query = 'SELECT id, '.$dateQuery.', auteur, lieu, superf_h, superf_t, price, link FROM annonces';
				$reponse = $bdd->query($reponse_query);
				$donnees = $reponse->fetch();
				echo('<tr><td class="left">'.$donnees['id'].'</td>');
					echo('<td>'.$donnees['date'].'</td>');
					echo('<td>'.$donnees['auteur'].'</td>');
					echo('<td>'.$donnees['lieu'].'</td>');
					echo('<td>'.$donnees['superf_h'].'</td>');
					echo('<td>'.$donnees['superf_t'].'</td>');
					echo('<td>'.$donnees['price'].'</td>');
					echo('<td><a href='.$donnees['link'].'>Annonce</a></td>');
					echo('<td><a href=comments.php?annonce='.$donnees['id'].'>Commentaires</a></td></tr>');
				$reponse->closeCursor();
				?>
			</table>
		</div>
	<?php
	}
} ?>