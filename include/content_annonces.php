<?php
if (! $user->data['is_registered']) include('include/not_registered.php');

else { secureGet();?>
	<div id="table">
		<table>
			<tr class="top">
				<?php 
				?>
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
	
			if ($current_url_reverse == "false") $reponse_query  = 'SELECT id, '.$dateQuery.', auteur, lieu, superf_h, superf_t, price, link FROM annonces ORDER BY '.$current_url_order.'';
			else 												  $reponse_query = 'SELECT id, '.$dateQuery.', auteur, lieu, superf_h, superf_t, price, link FROM annonces ORDER BY '.$current_url_order.' DESC';
	
			$reponse = $bdd->query($reponse_query);
	
			while($donnees = $reponse->fetch()) {
				echo('<tr><td class="left">'.$donnees['id'].'</td>');
				echo('<td>'.$donnees['date'].'</td>');
				echo('<td>'.$donnees['auteur'].'</td>');
				echo('<td>'.$donnees['lieu'].'</td>');
				echo('<td>'.$donnees['superf_h'].'</td>');
				echo('<td>'.$donnees['superf_t'].'</td>');
				echo('<td>'.$donnees['price'].'</td>');
				echo('<td><a href='.$donnees['link'].'>Annonce</a></td>');
				echo('<td><a href=comments.php?annonce='.$donnees['id'].'>Commentaires</a></td></tr>');
			}
			$reponse->closeCursor(); 
			?>
		</table>
	</div>
<?php
} ?>