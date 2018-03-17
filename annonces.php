<?php
include_once("include/annonce_functions.php");
include_once("include/details_functions.php");
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
                include('include/details_content.php');
				echo('<div class="flex-container flex-column">');
				secure_get();
				$current_page = 'annonces.php';

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
			function getAnnonces() {
				var result = '';
				$.ajax({
	    			   url: 'include/details_json_management.php',
	    			   type: 'POST',
	    			   data: {'user': '<?php echo $user->data['username']; ?>'},
	    			   success: function(data) {
	        			   processJson(data);
	        			   result =  JSON.parse(data);},
					   error: function(xhr, ajaxOptions, thrownError) {
					      //On error do this
					        if (xhr.status == 200) {
					            alert(ajaxOptions);
					        }
					        else {
					            alert(xhr.status);
					            alert(thrownError);
					        }
					    }
	    			 });
   			 	return result;
			}
			
    		$(window).on('load', function() {
    			 getAnnonces();
    		});
    		
			var columns = <?php echo json_encode($columns); ?>;
			var filters = <?php echo json_encode($filters); ?>;
			var user_name = <?php echo json_encode($user->data["username"]); ?>;

		    function generateTable(liste_annonces, sortColumn) {
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

		    function showRequest(formData, jqForm, options) {
		        var queryString = $.param(formData);
		        alert('About to submit: \n\n' + queryString + '\nOptions : ' + options);
		        return true;
		    }

		    function processJson(data) {
		    	//console.log(data);
		    	var formatted_data = JSON.parse(data);
		    	var final_data = [];
		    	var update_id = "";
		    	
		        if(typeof(formatted_data["updatethisid"] != "undefined") && formatted_data["updatethisid"] !== null) {
			       	update_id = formatted_data["updatethisid"];
			       	
    		        for(d in formatted_data) {
        		        if(typeof(formatted_data[d]) != "string") {
               		        final_data[d] = formatted_data[d];
        		        }
    		        }

    		        formatted_data = final_data;
		        }
		        
		        generateTable(formatted_data, 'id');

				if(update_id !== "") {
    		        updateForms($('#annonces-table #id').filter(function() {
    			        return $(this).text() == update_id;
    		        }).parent());
				}
		    }

		    function updateForms(tr) {
			    //console.log(tr);
		    	//Putting the announce id into the details forms
			    //row architecture in annouces table : <tr><td id="id">my_id</td>...<td><a class="details-link">Details</a></td></tr>
			    $('#details-frame #id').val(tr.find('#id').text());
			    //Putting the usernote as selected value in note_input form
				$('#annonce_form #note_input').val(tr.find('#user_note').text());

				//Setting label of availability button
				var available = tr.attr('class');
				var text = 'Rendre ';
				var value = '';
				
				if(available == 'available') {
					text += 'indisponible';
					value = '0';
				}

				else if (available == 'unavailable') {
					text += 'disponible';
					value = '1';
				}

				$('#available_form #available').val(value);
				$('#available_form #available_submit').val(text);
		    }
		    
			// Monitoring changes in filters form for offers table automatic refresh
			$("#form_sort_annonce").change(function() {
				generateTable(getAnnonces());
			});

			//Monitoring changes in annonce_form
			$('#annonce_form').change(function() {
			    $('#annonce_form').submit();
				$('#details-frame').fadeToggle('fast');
			});

			//Annonce_form management
			$('#annonce_form').ajaxForm({
				url: 'include/details_json_management.php',
				data: {user: '<?php echo $user->data['username']; ?>'},
				type: 'post',
				//beforeSubmit: showRequest,
				success: processJson,
				error: function(jqXHR, textStatus, errorThrown){
				     alert('Error Message: '+textStatus);
				     alert('HTTP Error: '+errorThrown);
				}
			});

			// Details frame
			$(document).on('click', '.details-link', function(){
			    $('#details-frame').fadeToggle('fast');
			    updateForms($(this).parent().parent());
			});

			//available_form management
			$('#available_form').ajaxForm({
				url: 'include/details_json_management.php',
				data: {user: '<?php echo $user->data['username']; ?>'},
				type: 'post',
				//beforeSubmit: showRequest,
				success: processJson,
				error: function(jqXHR, textStatus, errorThrown){
				     alert('Error Message: '+textStatus);
				     alert('HTTP Error: '+errorThrown);
				}
			});

			$('#available_form').on('submit', function() {
				$('#details-frame').fadeToggle('fast');
			});
		</script>
	</body>
</html>
