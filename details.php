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
	<?php add_header();?>
	<section id="main">
		<?php include("include/details_content.php"); ?>
	</section>
	<?php add_footer(); ?>
	<script src="js/jquery-ui.min.js"></script>
	<script src="js/functions.js"></script>
	<script src="js/annonce_functions.js"></script>
</body>
</html>
