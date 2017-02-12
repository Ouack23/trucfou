<div class="box posting-form"> <div id="annonce_details">
	<h1>Détails de l'annonce <div class="detailsNumber"></div> </h1>
	<form>
		<p>
			<label>Note: </label>
			<span class="select-wrapper"><select class="userNote">
				<option value="-1" selected="true">aucun vote</option>
				<option value="0">0</option>
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5">5</option>
			</select></span>
			<input type="submit" value="Voter">
		</p>
	</form>
	<form class="availability">
		<h3>Annonce indisponible !</h3>
		<input class="warning-button" value="Déclarer indisponible" type="button" name="availability" onclick=unavailable()>
	</form>
	<form class="author-element">
		<input class="warning-button" value="Supprimer l'annonce" type="button" name="remove">
		<input class="warning-button" value="Redéclarer disponible" type="button" name="availability">
	</form>

	<div class="comments_section"><h3>No comments for now !</h3></div>
	<form class="newCommentBtn" method="post">
		<input type="submit" name="new_comment" value="Nouveau commentaire"></input>
	</form>

</div></div>
