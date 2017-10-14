<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	include_once("include/annonce_functions.php");
	include_once("include/config.php");
	include_once("include/database_getters.php");
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
				
				$annonces_initial_query = 'SELECT id, '.format_date().', auteur, lieu, superf_h, superf_t, price, link, habit, time, distance, departement, available FROM annonces';
				$annonces_reponse_query = $bdd->query($annonces_initial_query);

				$annonces = [];
				$i = 0;
				while($annonce = $annonces_reponse_query->fetch()) {
					$annonce["note"] = get_note($annonce["id"]);
					$annonce["user_note"] = get_user_note($annonce["id"], $user->data['username']);
					$annonce["comments"] = get_number_of_comments($annonce["id"]);
					$annonce["details"] = append_sid("details.php", "id=".$annonce["id"]."");
					$annonces[$i] = $annonce;
					$i++;
				}

				// Order of elements is order in the displayed table
				$columns = [
							'id' 		=> 'N',
							'date' 		=> 'Date',
							'auteur' 	=> 'Auteur',
							'lieu' 		=> 'Lieu',
							'departement'=> 'Dpt',
							'superf_h' 	=> 'Batiment',
							'superf_t' 	=> 'Terrain',
							'habit'		=> 'Etat',
							'time' 		=> 'Trajet',
							'distance' 	=> 'Distance',
							'price' 	=> 'Prix',
							'note' 		=> 'Moy',
							'user_note' => 'Note',
							'link' 		=> 'Lien',
							'details'	=> 'Details',
							'comments' 	=> 'Comms'
							];

				// keys and values matters: used to get the elements in filter method
				$filters = [
							//'date' 		  => 'Date',
							'auteur' 	  => 'sort_auteur',
							'lieu' 		  => 'sort_lieu',
							'departement' => 'sort_departement',
							'superf_h' 	  => 'value_superf_h',
							'superf_t' 	  => 'value_superf_t',
							'habit'		  => 'value_habit',
							'time' 		  => 'value_time',
							'distance' 	  => 'value_distance',
							'price' 	  => 'value_price',
							'note' 		  => 'value_note',
							'disable'	  => 'hide_disabled'
							];
				print_sort_form($current_page, $current_url, $sort_array);
			?>

			<div class="box">
				<div class="box-header">
					<h2 class="annonce-title">Annonces</h2>
				</div>
				<div class="box-content" id="annonces-table">
					<div>table des annonces</div>
				</div>
			</div>

			<?php
				
				print_statistics($current_page, $current_url, $sort_array, 'all_annonces');

				echo('</div>');
			} ?>
		</section>
		<?php add_footer(); ?>
		<script src="js/jquery-ui.min.js"></script>
		<script src="js/functions.js"></script>
		<script src="js/annonce_functions.js"></script>
		<script>
			var columns = <?php echo json_encode($columns) ?>,
			    filters = <?php echo json_encode($filters) ?>,
			    user_name = <?php echo json_encode($user->data["username"]) ?>;

			    function generateTable(sortColumn) {
				    var liste_annonces = <?php echo json_encode($annonces)?>;

				    if(!liste_annonces || !columns || !filters || !user_name) {
						console.log("ERREUR");
				    }

				    // look for the currently selected column. Used when we ask for sorting
				    if(sortColumn == undefined) {
				        var table = document.getElementById("annonces-table");
				        var headers = table.getElementsByTagName("th");
				        for(var i = 0; i < headers.length; i++) {
				            if(headers[i].getAttribute("type") == "sorted") {
				                sortColumn = headers[i].id;
				            }
				        }
				    }

				    createTable(sortColumn, false, liste_annonces, columns, filters, user_name);
				}

			// At page load, generate offers table sorted by id
			generateTable("id");

			// Monitoring changes in filters form for offers table automatic refresh
			$("#form_sort_annonce").change(function() {
				generateTable();
			});

		</script>
	</body>
</html>
