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
			?>
			<script>
				function sortJSONTable(jsonArray, sortKey){
					jsonArray.sort(function(a, b) {
						if(sortKey == "auteur" || sortKey == "lieu") {
							return a[sortKey] > b[sortKey];
						}
						else {
							return a[sortKey] - b[sortKey];
						}
					});
				}

				// callback function
				function onClickOnTableHeader(element) {
					// store element id
					var id = element.id;

					//remove old table
					var oldTable = document.getElementById("annoncesArray");
					oldTable.parentNode.removeChild(oldTable);

					// create new one.
					tableCreate(id);

					// underline and sort
					var th = document.getElementById(id);
					th.style.textDecoration = "underline";
				}

				// callback function
/*				function filterJSON(inputArray) {
					var filteredArray;

					for(var annonce in inputArray){
				    	var display = true;


				    	if(display)
				    	{
				    		filteredArray
				    	}
				    }

					return filteredArray;
				}
*/
				function tableCreate(sortColumn){
				    var body = document.body,
				        tbl  = document.createElement('table'),
				        liste_annonces = <?php echo json_encode($annonces)?>,
				        columns = <?php echo json_encode(($columns)) ?>;
		
					tbl.id = "annoncesArray";

					// generate table header.
			        var tr = tbl.insertRow();
	                for(var col in columns) {
		            	var th = document.createElement("th");
		            	th.appendChild(document.createTextNode(columns[col]));
		            	th.id = col;
		            	tr.appendChild(th);
		            	th.addEventListener("click", function() {onClickOnTableHeader(this);} );
		            }

		            var filtered_annonces = liste_annonces; //filterJSON(liste_annonces);

		            // sort array
		            sortJSONTable(filtered_annonces, sortColumn);

	            	// generate resulting dom
				    for(var annonce in filtered_annonces){
				        tr = tbl.insertRow();
				        var row = filtered_annonces[annonce];
		                for(var col in columns) {
			            	var td = tr.insertCell();
				            td.appendChild(document.createTextNode(row[col]));
				        }
				    }
				    body.appendChild(tbl);
				}

				tableCreate("id");
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