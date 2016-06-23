<?php include('include/functions.php'); ?>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Nouvelle annonce</title>
		<link rel="stylesheet" href="style.css" />
	</head>
	<body>
		<?php include_Content('top'); ?>
		<section id="main">	
			<h1>Nouvelle annonce</h1>
			<?php
			if(! $user->data['is_registered']) include('include/not_registered.php');

			else { ?>
				
				
				
			<?php
			} ?>
		</section>
		<?php include_Content('bottom'); ?>
	</body>
</html>