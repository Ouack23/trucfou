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
		<script src="include/annonce_functions.js"></script>
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
				
				$initial_query = 'SELECT id, '.format_date().', auteur, lieu, superf_h, superf_t, price, link, habit, time, distance, departement, available FROM annonces';
				$reponse_query = $bdd->query($initial_query);
				$i = 0;
				while($annonce = $reponse_query->fetch()) {
					$annonces[$i] = $annonce;
					$i++;
				}
				$columns = [
							'id' 		=> 'N°',
							'date' 		=> 'Date',
							'auteur' 	=> 'Auteur',
							'lieu' 		=> 'Lieu',
							'departement'=> 'Dpt',
							'superf_h' 	=> 'Superficie bâtie',
							'superf_t' 	=> 'Superficie du terrain',
							'habit'		=> 'État',
							'time' 		=> 'Trajet',
							'distance' 	=> 'Distance',
							'price' 	=> 'Prix',
							'link' 		=> 'Annonce',
//							'note' 		=> 'Note',
//							'comments' 	=> 'Comms'
							];

				$filters = [
//							'id' 		=> 'N°',
//							'date' 		=> 'Date',
							'auteur' 	=> 'sort_auteur',
							'lieu' 		=> 'sort_lieu',
							'departement'=> 'sort_departement',
							'superf_h' 	=> 'value_superf_h',
							'superf_t' 	=> 'value_superf_t',
							'habit'		=> 'value_habit',
							'time' 		=> 'value_time',
							'distance' 	=> 'value_distance',
							'price' 	=> 'value_price' //,
//							'link' 		=> 'Annonce',
//							'note' 		=> 'Note',
//							'comments' 	=> 'Comms'
							];
				print_sort_form($current_page, $current_url, $sort_array);
			?>
			<h1 id="annonceTitle"> Annonces </h1>
			<script>
				function generateTable(sortColumn) {
			        var liste_annonces = <?php echo json_encode($annonces)?>,
				        columns = <?php echo json_encode($columns) ?>,
				        filters = <?php echo json_encode($filters) ?>;

			        // look for the currently selected column.
			        if(sortColumn == undefined) {
			        	var table = document.getElementById("annoncesArray");
			        	var headers = table.getElementsByTagName("th");
			        	for(var i = 0; i < headers.length; i++) {
			        		if(headers[i].getAttribute("type") == "sorted") {
			        			sortColumn = headers[i].id;
			        		}
			        	}
			        }

					createTable(sortColumn, liste_annonces, columns, filters);
				}
			</script>
			<script>
				generateTable("id");
			</script>
			<?php
				if ($current_url['annonce'] != 0 && $current_url['comments'] == 'true')
					print_comments_annonce($current_page, $current_url, $sort_array);
				
				print_statistics($current_page, $current_url, $sort_array, 'all_annonces');
			} ?>
		</section>
		<?php add_footer(); ?>
	</body>
</html>
