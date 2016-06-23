<?php
include("include/functions.php");
include("include/config.php");?>

<html>
	<head>
		<meta charset="utf-8" />
		<title>Un projet de malade - annonces</title>
		<link rel="stylesheet" href="style.css" />
	</head>
	<body>
		<?php include_Content("top"); ?>
		<section id="main">
			<h1>Liste des annonces</h1>
			<?php include('include/content_annonces.php'); ?>
		</section>
		<?php include_Content("bottom"); ?>
	</body>
</html>