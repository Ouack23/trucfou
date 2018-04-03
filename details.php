<?php
	include_once("include/config.php");
	include_once("include/database_getters.php");
	include_once("include/details_functions.php");
	include_once("include/header_footer.php");
	include_once("include/phpBB.php");
?>

<!DOCTYPE html>
<html>
<head>
		<meta charset="utf-8" />
		<title>Un projet de malade - Détails de l'annonce</title>
		<link rel="stylesheet" href="css/style.css" />
		<link rel="icon" type="image/x-icon" href="favicon.ico" />
</head>
<body>
	<?php add_header();?>
	<section id="main">
		<?php
		if(!$user->data['is_registered']) include('include/not_registered.php');
		
		else {
		    $annonce_id = request_var('id', 0);
		    
		    if(isset($annonce_id) and $annonce_id > 0) {
		        $is_details_dedicated_page = true;
		        include("include/details_content.php");
		    }
		    else echo('<p class="error">Invalid annonce id</p>');
		}
		?>
	</section>
	<?php add_footer(); ?>
	<script src="js/jquery-ui.min.js"></script>
	<script src="js/functions.js"></script>
	<script src="js/annonce_functions.js"></script>
	<script type="text/javascript">
    	$(window).on('load', function() {
        	$('#details-frame').show();
        	$('#details-frame #id').val($('#id_').text());
			$('#note_input').val($('#user_note_').text());
			$('#available_form').remove();
        	
    		//Monitoring changes in annonce_form
 			$('#annonce_form').change(function() {
 			    $('#annonce_form').submit();
 			});

 			//Annonce_form management
 			$('#annonce_form').ajaxForm({
 				url: 'include/details_json_management.php',
 				data: {user: '<?php echo $user->data['username']; ?>'},
 				type: 'post',
 				success: processJson,
 				error: function(jqXHR, textStatus, errorThrown){
 				     alert('Error Message: '+textStatus);
 				     alert('HTTP Error: '+errorThrown);
 				}
 			});
 			
 			function processJson(data) {
 				window.location.reload(true);
		    }
    	});
	</script>
</body>
</html>
