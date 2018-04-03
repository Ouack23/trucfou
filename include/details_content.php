<section id="details-frame" class="hidden">
	<div class="box-header">
		<h2>Détails de l'annonce</h2>
	</div>
	<div class="box-content">
		<div class="table">
			<div>
				<table>
					<tbody class="table-reminder">
					<?php if(isset($is_details_dedicated_page)) print_annonce_resume(get_annonce_resume($annonce_id));?>
					</tbody>
				</table>
			</div>
			<form method="post" id="annonce_form">
				<p>
					<label for="note_input">Note: </label>
					<span class="select-wrapper"><select name="note_input" id="note_input" class="user_note" form="annonce_form">
						<option value="-1">aucun vote</option>
						<?php
							for($i = 0 ; $i < 6; $i++)
							{
								echo '<option value="' .$i. '">' .$i. '</option>';
							}
						 ?>
					</select></span>
					<input type="hidden" name="id" id="id" value="0">
				</p>
			</form>
			
			<form method="post" id="available_form">
				<input type="hidden" name="id" id="id">
				<input type="hidden" name="available" id="available">
				<input type="submit" id="available_submit" class="warning-button">
			</form>
			
			<form action="confirm_annonce_remove.php" method="post">
				<input type="hidden" name="id" id="id">
				<input type="submit" class="warning-button" value="Supprimer l'annonce" type="button" name="remove">
			</form>

			<h3 class="comment_title"></h3>

			<form action="new_comment.php" method="post">
				<input type="hidden" name="id" id="id">
				<input type="submit" name="new_comment" value="Nouveau commentaire">
			</form>

			<?php

				/*foreach($comments as $comment)
				{
                    echo '<ul class="block-titre">';
                    echo '<li class="block-quand"><span class="icon-clock"></span>'. $comment["date"] .'</li>';
                    echo '<li class="block-quoi"><span class="icon-user"></span> Par <span class="block-author">' .$comment["auteur"].' </span></li>';
                    echo '</ul>';
                    echo  '<p>'.  $comment["comment"] . '</p>';

				}*/
			?>
		</div>
	</div>
</section>