<?php
include_once("include/functions.php");
include_once("include/config.php");
include_once("include/header_footer.php");
include_once("include/phpBB.php");
?>

<html>
	<head>
		<meta charset="utf-8" />
		<title>Un projet de malade - <?php echo($user->data['username']); ?></title>
		<link rel="stylesheet" href="css/style.css" />
		<script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
		<script src="http://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
		<script type="text/javascript" src="include/sticky.js"></script>
		<script src="include/javascript/functions.js"></script>
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
				$current_page = 'user.php';
				
				print_sort_form($current_page, $current_url, $sort_array);
				
				print_user_annonces($current_page, $current_url, $sort_array);
				
				//Si on veut afficher les commentaires
				if ($current_url['annonce'] != 0 && $current_url['comments'] == 'true')
					print_comments_annonce($current_page, $current_url, $sort_array);
			} ?>
		</section>
		<?php add_footer(); ?>
	</body>
</html>