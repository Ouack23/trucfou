<?php
include("include/functions.php");
include("include/config.php");?>

<html>
	<head>
		<meta charset="utf-8" />
		<title>Un projet de malade - commentaires</title>
		<link rel="stylesheet" href="style.css" />
	</head>
	<body>
		<?php include_Content("top"); ?>
		<section id="main">
			<?php include('include/content_comments.php'); ?>
		</section>
		<?php include_Content("bottom"); ?>
	</body>
</html>