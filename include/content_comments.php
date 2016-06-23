<?php
if(! $user->data['is_registered']) include('include/not_registered.php');

else {
	secureGet();
	//Tri par date par défaut
	if(empty($current_url_order)) $current_url_order = 'date';
	
	//Si on a demandé les commentaires d'une annonce spécfique
	if(!empty($current_url_annonce)) {
		echo('<h1>Description de l\'annonce</h1>');
		include('content_annonces.php');
		
		echo('<h1>Liste des commentaires de l\'annonce</h1>');
		echo('<div id="comments">'); ?>
			<div id="table">
				<table>
					<tr class="top"><td class="left" colspan="2">Tri</td></tr>
					<tr>
						<td class="left"><a href="?annonce=<?php echo($current_url_annonce); ?>&orderBy=date&reverse=<?php printReverse('comments', 'date'); ?>">Date</a></td>
						<td><a href="?annonce=<?php echo($current_url_annonce); ?>&orderBy=auteur&reverse=<?php printReverse('comments', 'auteur'); ?>">Auteur</a></td>
					</tr>
				</table>
			</div>
			
			<?php
			  if($current_url_reverse == 'false')
			  	$reponse_query = 'SELECT id, annonce, '.format_Date().', auteur, comment FROM comments WHERE annonce = '.$current_url_annonce.' ORDER BY '.$current_url_order.'';
			  else
			  	$reponse_query = 'SELECT id, annonce, '.format_Date().', auteur, comment FROM comments WHERE annonce = '.$current_url_annonce.' ORDER BY '.$current_url_order.' DESC';
			  
			  $reponse=$bdd->query($reponse_query);
			  
			  while($donnees = $reponse->fetch()) {
				  echo('<h3>Commentaire numéro '.$donnees['id'].'</h3>');
				  echo('<p id="description">écrit par '.$donnees['auteur'].' le '.$donnees['date'].'</p>');
				  echo('<p id="content">'.$donnees['comment'].'</p>');
			  }
			  $reponse->closeCursor(); 
		echo('</div>');
	}
	//On demande la sélection d'une annonce
	else {
		echo('<h1>Choisissez une annonce pour voir les commentaires correspondants</h1>');
		
		select_annonce();
	}
}?>