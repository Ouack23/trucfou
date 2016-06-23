<?php
if (! $user->data['is_registered']) include('include/not_registered.php');

else {
	secureGet();
	if(empty($current_url_annonce)) $current_url_annonce = 1;
	if(empty($current_url_order)) $current_url_order = 'id'; ?>
<h1>Liste des commentaires de l'annonce <?php echo($current_url_annonce); ?></h1>
<div id="comments">
	<div id="table">
		<table>
			<tr class="top"><td class="left" colspan="5">Tri</td></tr>
			<tr>
				<td class="left"><a href="?annonce=<?php echo($current_url_annonce); ?>&orderBy=id&reverse=<?php printReverse('comments', 'id'); ?>">N°</a></td>
				<td><a href="?annonce=<?php echo($current_url_annonce); ?>&orderBy=annonce&reverse=<?php printReverse('comments', 'annonce'); ?>">Annonce</a></td>
				<td><a href="?annonce=<?php echo($current_url_annonce); ?>&orderBy=date&reverse=<?php printReverse('comments', 'date'); ?>">Date</a></td>
				<td><a href="?annonce=<?php echo($current_url_annonce); ?>&orderBy=auteur&reverse=<?php printReverse('comments', 'auteur'); ?>">Auteur</a></td>
				<td><a href="?annonce=<?php echo($current_url_annonce); ?>&orderBy=comment&reverse=<?php printReverse('comments', 'comment'); ?>">Commentaire</a></td>
			</tr>
		</table>
	</div>
	
	<?php
	  $dateQuery = format_Date();
	  if ($current_url_reverse == 'false')		$reponse_query = 'SELECT id, annonce, '.$dateQuery.', auteur, comment FROM comments WHERE annonce = '.$current_url_annonce.' ORDER BY '.$current_url_order.'';
	  else														$reponse_query = 'SELECT id, annonce, '.$dateQuery.', auteur, comment FROM comments WHERE annonce = '.$current_url_annonce.' ORDER BY '.$current_url_order.' DESC';
	  
	  $reponse=$bdd->query($reponse_query);
	  
	  while($donnees = $reponse->fetch()) {
		  echo('<h3>Commentaire numéro '.$donnees['id'].'</h3>');
		  echo('<p id="description">concernant <a href="annonces.php?annonce='.$donnees['annonce'].'"> l\'annonce '.$donnees['annonce'].'</a>, écrit par '.$donnees['auteur'].' le '.$donnees['date'].'</p>');
		  echo('<p id="content">'.$donnees['comment'].'</p>');
	  }
	  $reponse->closeCursor(); 
	?>
</div>
<?php
}?>