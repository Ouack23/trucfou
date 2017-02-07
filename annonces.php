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
		<link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
		<link href="https://fonts.googleapis.com/css?family=Ubuntu:400,400i,700" rel="stylesheet">
		<link rel="stylesheet" href="css/style.css" />
		<script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
		<script src="http://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
		<script src="include/functions.js"></script>
		<link rel="stylesheet" href="http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
		<link rel="icon" type="image/x-icon" href="favicon.ico" />
	</head>
	<body>
		<?php add_header(); ?>
		<section id="main">
			<?php
			if(!$user->data['is_registered']) include('include/not_registered.php');
			
			else {
				secure_get();
				$current_page = 'annonces.php';
				
				print_sort_form($current_page, $current_url, $sort_array);
				
				print_all_annonces($current_page, $current_url, $sort_array);
				
				if ($current_url['annonce'] != 0 && $current_url['comments'] == 'true')
					print_comments_annonce($current_page, $current_url, $sort_array);
				
				print_statistics($current_page, $current_url, $sort_array, 'all_annonces');
			} ?>
		</section>
		<?php add_footer(); ?>
	</body>
</html>