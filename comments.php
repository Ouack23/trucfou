<?php
include("include/functions.php");
include("include/config.php");?>

<html>
	<head>
		<meta charset="utf-8" />
		<title>Un projet de malade - commentaires</title>
		<link rel="stylesheet" href="style.css" />
		<link rel="icon" type="image/ico" href="include/images/favicon.ico" />
	</head>
	<body>
		<?php include_content("top"); ?>
		<section id="main">
			<?php 
			if(! $user->data['is_registered']) include('include/not_registered.php');
			else {
				//On demande la sélection d'une annonce
				if(empty($current_url['annonce'])) {
					echo('<h1>Choisissez une annonce pour voir les commentaires correspondants</h1>');
				
					select_annonce();
				}
				
				else {
					//content_comments('comments.php');
				}
			}
			?>
		</section>
		<?php include_content("bottom"); ?>
	</body>
</html>