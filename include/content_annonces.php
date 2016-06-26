<?php
if(!$user->data['is_registered']) include('include/not_registered.php');

else {
	secure_get();
	if(!isset($_GET['annonce']) && !isset($_POST['annonce'])) { ?>
	<h1>Liste des annonces</h1>
	
	<div id="table">
		<table>
			<tr class="top">
				<?php $columns_array = ['id' => 'N°',
										'date' => 'Date',
										'auteur' => 'Auteur',
										'lieu' => 'Lieu',
										'superf_h' => 'Superficie habitable',
										'superf_t' => 'Superficie du terrain',
										'habit' => 'État',
										'time' => 'Temps de trajet',
										'price' => 'Prix'];
				foreach($columns_array as $column_bdd => $column_name) {
					if($column_bdd == 'id') echo('<td class="left">');
					else echo('<td>');
					echo('<a href="'.append_sid('annonces.php', 'orderBy='.$column_bdd.'&amp;reverse='.print_reverse('annonces', $column_bdd).'').'">'.$column_name.'</a></td>');
				} ?>
				<td>Lien</td>
				<td>Commentaires</td>
			</tr>
			<?php
	
			if($current_url_reverse == "false")
				$reponse_query = 'SELECT id, '.format_date().', auteur, lieu, superf_h, superf_t, price, link, habit, time FROM annonces ORDER BY '.$current_url_order.'';
			else
				$reponse_query = 'SELECT id, '.format_date().', auteur, lieu, superf_h, superf_t, price, link, habit, time FROM annonces ORDER BY '.$current_url_order.' DESC';
	
			$reponse = $bdd->query($reponse_query);
	
			while($donnees = $reponse->fetch()) {
				$minutes = $donnees['time']%60;
				$hours = ($donnees['time'] - $minutes)/60;
				
				echo('<tr><td class="left">'.$donnees['id'].'</td>');
				echo('<td>'.$donnees['date'].'</td>');
				echo('<td>'.$donnees['auteur'].'</td>');
				echo('<td>'.$donnees['lieu'].'</td>');
				echo('<td>'.$donnees['superf_h'].'</td>');
				echo('<td>'.$donnees['superf_t'].'</td>');
				echo('<td class="habit'.$donnees['habit'].'">'.$donnees['habit'].'</td>');
				if($minutes < 10) echo('<td>'.$hours.'h0'.$minutes.'</td>');
				else echo('<td>'.$hours.'h'.$minutes.'</td>');
				echo('<td>'.$donnees['price'].' k€</td>');
				echo('<td><a href="'.$donnees['link'].'">Annonce</a></td>');
				echo('<td><a href="'.append_sid('comments.php', 'annonce='.$donnees['id'].'').'">Commentaires</a></td></tr>');
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
					<td>État</td>
					<td>Temps de trajet</td>
					<td>Prix</td>
					<td>Lien</td>
				</tr>
				<?php
				$reponse_query = 'SELECT id, '.format_date().', auteur, lieu, superf_h, superf_t, price, link, habit, time FROM annonces WHERE id = '.$current_url_annonce.'';
				$reponse = $bdd->query($reponse_query);
				$donnees = $reponse->fetch();
				
				$minutes = $donnees['time']%60;
				$hours = ($donnees['time'] - $minutes)/60;
				
				echo('<tr><td class="left">'.$donnees['id'].'</td>');
					echo('<td>'.$donnees['date'].'</td>');
					echo('<td>'.$donnees['auteur'].'</td>');
					echo('<td>'.$donnees['lieu'].'</td>');
					echo('<td>'.$donnees['superf_h'].'</td>');
					echo('<td>'.$donnees['superf_t'].'</td>');
					echo('<td class="habit'.$donnees['habit'].'">'.$donnees['habit'].'</td>');
					if($minutes < 10) echo('<td>'.$hours.'h0'.$minutes.'</td>');
					else echo('<td>'.$hours.'h'.$minutes.'</td>');
					echo('<td>'.$donnees['price'].' k€</td>');
					echo('<td><a href='.$donnees['link'].'>Annonce</a></td>');
					
				$reponse->closeCursor();
				?>
			</table>
		</div>
	<?php
	}
} ?>