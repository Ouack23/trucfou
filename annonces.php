<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	include_once("include/annonce_functions.php");
	include_once("include/config.php");
	include_once("include/header_footer.php");
?>

<html>
	<head>
		<meta charset="utf-8" />
		<title>Un projet de malade - annonces</title>
		<link rel="stylesheet" href="css/style.css" />
		<link rel="stylesheet" href="css/jquery-ui.css">
		<link rel="icon" type="image/x-icon" href="favicon.ico" />
	</head>
	<body>
		<?php add_header(); ?>
		<section id="main">
			<h1 class="page-title">Annonces</h1>

			<?php
			if(!$user->data['is_registered']) include('include/not_registered.php');
			
			else {

				echo('<div class="flex-container flex-column">');
				secure_get();
				$current_page = 'annonces.php';
				
				print_sort_form($current_page, $current_url, $sort_array);
				
				print_all_annonces($current_page, $current_url, $sort_array);
				
				if ($current_url['annonce'] != 0 && $current_url['comments'] == 'true')
					print_comments_annonce($current_page, $current_url, $sort_array);
				
				print_statistics($current_page, $current_url, $sort_array, 'all_annonces');

				echo('</div>');
			} ?>
		</section>
		<?php add_footer(); ?>
		<script src="js/jquery-ui.min.js"></script>
		<script src="js/functions.js"></script>
	</body>
</html>