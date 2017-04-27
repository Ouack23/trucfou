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
		$test = '';
		if($note_to_set != '')
		{
			$test = vote($annonce_id, $username, $note_to_set);
		}
		$note = get_user_note($annonce_id, $username);
		$comments = get_comments($annonce_id);
		$available = get_available($annonce_id);
	 ?>
	<section id="main">
		<div class="box">
			<div class="box-header">
				<h2>Détails de l'annonce <?php echo $annonce_id; ?> </h2>
			</div>
			<div class="box-content">
				<div class="table">
					<div>
						<table>
							<tbody class="table-reminder">
							</tbody>
						</table>
					</div>
					<form action="details.php" method="post" id="annonce_form">
						<p>
							<label>Note: <?php echo $test; ?></label>
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
						<h3 class="comment_title"></h3>
					</form>
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
