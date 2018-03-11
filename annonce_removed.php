
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
		delete($annonce_id);
	?>
	<p>
		<h3> Annonce <?php echo $annonce_id; ?> supprimée ! </h3>
		<form action= "annonces.php"  method="post">
			<input type="submit" class="warning-button" value="Retour aux annonces" name="remove">
		</form>
	</p>
</body>
</html>