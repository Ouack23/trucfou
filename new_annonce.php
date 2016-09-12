<?php
include('include/functions.php');
include('include/config.php');
?>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Nouvelle annonce</title>
		<link rel="stylesheet" href="style.css" />
		<link rel="icon" type="image/ico" href="include/images/favicon.ico" />
	</head>
	<body>
		<?php include_content('top'); ?>
		<section id="main">	
			<h1>Poster une nouvelle annonce</h1>
			<?php
			
			if(!$user->data['is_registered']) include('include/not_registered.php');

			else {
				secure_get();
				
				//Si on a pas encore rempli le formulaire
				if(!isset($_POST['Valider'])) print_form_new_annonce([]);
				
				else {
					$lieu = $request->variable('lieu', '');
					$superf_h = $request->variable('superf_h', 0);
					$superf_t = $request->variable('superf_t', 0);
					$link = $request->variable('link', '');
					$habit = $request->variable('habit', '');
					$time = $request->variable('time', 0);
					$price = $request->variable('price', 0.0);
					$depart = $request->variable('depart', 0);
					$note = $request->variable('note', '');
					
					$habit = convert_str_nb($habit);
					$note = convert_str_nb($note);
					
					if($lieu < 0) $lieu = 0;
					if($superf_h < 0) $superf_h = 0;
					if($superf_t < 0) $superf_t = 0;
					if($time < 0) $time = 0;
					if($price < 0.0) $price = 0.0;
					if($depart < 0) $depart = 0;
					
					$param_array = ['lieu' => $lieu, 'superf_h' => $superf_h, 'superf_t' => $superf_t, 'link' => $link, 'habit' => $habit, 'time' => $time, 'price' => $price, 'depart' => $depart, 'note' => $note];
					
					$print_form = false;

					if(!(preg_match('#^https?://(www.)?[a-zA-Z0-9]+\.[a-z0-9]{1,4}\??#', $link) &&
							preg_match('#^[a-zA-Z][a-zA-Z- ]+#', $lieu) &&
							$time <= $sort_array['max_time'] && $time >= $sort_array['min_time'] &&
							$superf_h <= $sort_array['max_superf_h'] && $superf_h >= $sort_array['min_superf_h'] &&
							$superf_t <= $sort_array['max_superf_t'] && $superf_t >= $sort_array['min_superf_t'] &&
							$price <= $sort_array['max_price'] && $price >= $sort_array['min_price'] &&
							$depart <= $sort_array['max_depart'] && $depart >= $sort_array['min_depart'] &&
							!empty($lieu) && !empty($link) &&
							$superf_h != 0 && $superf_t != 0 && $time != 0 && $price != 0 &&
							$habit != -1 && $note != -1)) {
								$print_form = true;
								search_error_new_annonce($sort_array, $param_array);
							}
					
					else {
						$req = $bdd->prepare('INSERT INTO annonces(lieu, superf_h, superf_t, link, habit, time, price, date, auteur, departement)
								VALUES(:lieu, :superf_h, :superf_t, :link, :habit, :time, :price, NOW(), :auteur, :departement)');
						
						$req->execute(array(
								'lieu' => $lieu,
								'superf_h' => $superf_h,
								'superf_t' => $superf_t,
								'link' => $link,
								'habit' => $habit,
								'price' => $price,
								'auteur' => $user->data['username'],
								'time' => $time,
								'departement' => $depart));
							
						$req->closeCursor();
							
						$get_id = $bdd->query('SELECT id, time, price, link, auteur FROM annonces WHERE time = '.$time.' AND price = '.$price.' AND link = \''.$link.'\' AND auteur = \''.$user->data['username'].'\'');
						$annonce = $get_id->fetch();
						
						$id_annonce = $annonce['id'];
						
						$get_id->closeCursor();
							
						$req_note = $bdd->prepare('INSERT INTO notes(auteur, annonce, value) VALUES(:auteur, :annonce, :value)');
						$req_note->execute(array('auteur' => $user->data['username'], 'annonce' => $id_annonce, 'value' => $note));
						$req_note->closeCursor();
							
						echo('<p id="form" class="success">L\'annonce a bien été ajoutée, bien joué !</p>');
					}
					
					//Si une ou plus des valeurs du formulaire sont mauvaises
					if($print_form) print_form_new_annonce($param_array);
				}
			} ?>
		</section>
		<?php include_content('bottom'); ?>
	</body>
</html>