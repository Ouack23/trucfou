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
		<?php include_content("top"); ?>
		<section id="main">
			<?php
			if(! $user->data['is_registered']) include('include/not_registered.php');
			else {
				secure_get();
				content_annonce('annonces.php', $current_url);
			}
			?>
		</section>
		<?php include_content("bottom"); ?>
	</body>
</html>