<div class="box" style="display:none" id="annonce_details">
	<div class="box-header">
		<h2>Détails de l'annonce <span class="details_number"> </span> </h2>
	</div>
	<div class="box-content">
		<div class="table">
			<div>
				<table>
					<tbody class="table-reminder">
					</tbody>
				</table>
			</div>
			<form>
				<p>
					<label>Note: </label>
					<span class="select-wrapper"><select class="user_note">
						<option value="-1" selected="true">aucun vote</option>
						<option value="0">0</option>
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5">5</option>
					</select></span>
					<input type="button" value="Voter" onclick=vote()>
				</p>
			</form>
			<form class="availability">
				<h3 class="error-color">Annonce indisponible !</h3>
				<input class="warning-button" value="Déclarer indisponible" type="button" name="availability" onclick=unavailable()>
			</form>
			<form class="author-element">
				<input class="warning-button" value="Supprimer l'annonce" type="button" name="remove">
				<input class="warning-button" value="Redéclarer disponible" type="button" name="availability">
			</form>
			<h3 class="comment_title"></h3>
			<form class="new_comment_btn" method="post">
				<input type="submit" name="new_comment" value="Nouveau commentaire"></input>
			</form>
		</div>
	</div>
</div>
