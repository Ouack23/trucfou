<!DOCTYPE html>

<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	include_once("include/config.php");
	include_once("include/database_getters.php");
	include_once("include/details_functions.php");
	include_once("include/header_footer.php");
	include_once("include/phpBB.php");
?>

<html>
<head>
		<meta charset="utf-8" />
		<title>Un projet de malade - Details de l'annonce</title>
		<link rel="stylesheet" href="css/style.css" />
		<link rel="icon" type="image/x-icon" href="favicon.ico" />
</head>
<body>
	<?php 
		add_header();
		$username = $user->data["username"];
		$annonce_id = $request->variable('id', '');
		$note_to_set = $request->variable('note_input', '');
		$debug_info;
		if($note_to_set != '')
		{
			vote($annonce_id, $username, $note_to_set);
		}
		$available = $request->variable('available', '');
		if($available != '')
		{
			set_available($annonce_id, $available);
		}
		$note = get_user_note($annonce_id, $username);
		$comments = get_comments($annonce_id);
		$available = get_available($annonce_id);
		$link = get_annonce_link($annonce_id);
	 ?>
	<section id="main">
		<div class="box">
			<div class="box-header">
				<h2>Détails de l'annonce <?php echo $annonce_id; ?> </h2>
			</div>
			<div class="box-content">
				<div class="table">
					<p>
						<a href= <?php echo '"'.$link.'"'; ?> ><h3>Acceder à l'annonce</h3></a>
					</p>
					<form action="details.php" method="post" id="annonce_form">
						<p>
							<label>Note: </label>
							<span class="select-wrapper"><select name="note_input" class="user_note" form="annonce_form">
								<option value="-1" selected="true">aucun vote</option>
								<?php
									for($i = 0 ; $i<6; $i++)
									{
										echo '<option ';
										if($i == $note)
										{
											echo 'selected="true" ';
										}
										echo 'value="' .$i. '">' .$i. '</option>';
									}

								 ?>
							</select></span>
							<input type="text" name="id" value=<?php echo '"'.$annonce_id.'"';?> style='display: none'>
							<input type="submit" name="Vote" value="Voter">
						</p>
					</form>
					<form action="details.php" method="post">
						<?php
							if(!$available)
							{
								echo '<h3>Annonce indisponible !</h3>';
							}
							$set_available = $available ? "0" : "1";
							$button_name = $available ? "Déclarer indisponible" : "Redéclarer disponible";
						?>
						<input type="text" name="id" value=<?php echo '"'.$annonce_id.'"';?> style='display: none'>
						<input type="text" name="available" value=<?php echo '"'. $set_available.'"'; ?> style='display: none'>
						<input type="submit" class="warning-button" value=<?php echo '"'.$button_name.'"' ?>  name="availability">
					</form>
					<form action="confirm_annonce_remove.php" method="post">
						<input type="text" name="id" value=<?php echo '"'.$annonce_id.'"';?> style='display: none'>
						<input type="submit" class="warning-button" value="Supprimer l'annonce" type="button" name="remove">
					</form>

					<h3 class="comment_title"></h3>

					<form action="new_comment.php" method="post">
						<input type="text" name="annonce" value=<?php echo '"'.$annonce_id.'"';?> style='display: none'>
						<input type="submit" name="new_comment" value="Nouveau commentaire">
					</form>

					<?php

						foreach($comments as $comment)
						{
	                        echo '<ul class="block-titre">';
	                        echo '<li class="block-quand"><span class="icon-clock"></span>'. $comment["date"] .'</li>';
	                        echo '<li class="block-quoi"><span class="icon-user"></span> Par <span class="block-author">' .$comment["auteur"].' </span></li>';
	                        echo '</ul>';
	                        echo  '<p>'.  $comment["comment"] . '</p>';

						}
					?>
				</div>
			</div>
		</div>
	</section>
	<?php add_footer(); ?>
	<script src="js/jquery-ui.min.js"></script>
	<script src="js/functions.js"></script>
	<script src="js/annonce_functions.js"></script>
</body>
</html>