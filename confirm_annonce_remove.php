
<?php
	include_once("include/details_functions.php");
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
		$annonce_id = $request->variable('id', '');
	?>
	<p>
		<h3> Est tu sûr.e de vouloir supprimer l'annonce <?php echo $annonce_id; ?> ? </h3>
		<form action= "annonce_removed.php"   method="post">
			<input type="text" name="id" value=<?php echo '"'.$annonce_id.'"';?> style='display: none'>
			<input type="submit" class="warning-button" value="Supprimer" name="remove">
		</form>
		<form action="details.php" method="post">
			<input type="text" name="id" value=<?php echo '"'.$annonce_id.'"';?> style='display: none'>
			<input type="submit" class="warning-button" value="Retour aux details" name="remove">			
		</form>
	</p>
</body>
</html>