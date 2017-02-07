<?php
	include_once('include/phpBB.php');
	include_once('include/new_annonce_functions.php');
	include_once('include/config.php');
	include_once('include/header_footer.php');
?>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Nouvelle annonce</title>
		<link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
		<link href="https://fonts.googleapis.com/css?family=Ubuntu:400,400i,700" rel="stylesheet">
		<link rel="stylesheet" href="css/style.css" />
		<script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
		<link rel="icon" type="image/x-icon" href="favicon.ico" />
	</head>
	<body>
		<?php add_header(); ?>
		<section id="main">	
			<?php
			
			if(!$user->data['is_registered']) include('include/not_registered.php');

			else {
				secure_get();
				$action = $request->variable('action', 'create');
				
				switch($action) {
					case 'create':
						echo('<h1>Poster une nouvelle annonce</h1>');
						
						if(!isset($_POST['Valider'])) print_form_new_offer([], $sort_array, $action);
						
						else {
							$param_array = get_new_annonce_param_array($sort_array);
							
							if(!verif_form_new_annonce($sort_array, $param_array)) {
								$req = $bdd->prepare('INSERT INTO annonces(lieu, superf_h, superf_t, link, habit, time, distance, price, date, auteur, departement)
										VALUES(:lieu, :superf_h, :superf_t, :link, :habit, :time, :distance, :price, NOW(), :auteur, :departement)');
								
								$req->execute(array(
										'lieu' => $param_array['lieu'],
										'superf_h' => $param_array['superf_h'],
										'superf_t' => $param_array['superf_t'],
										'link' => $param_array['link'],
										'habit' => $param_array['habit'],
										'price' => $param_array['price'],
										'auteur' => $user->data['username'],
										'time' => $param_array['time'],
										'distance' => $param_array['distance'],
										'departement' => $param_array['departement']));
									
								$req->closeCursor();
								
								$get_id = $bdd->query('SELECT id, time, price, link, auteur FROM annonces WHERE time = '.$param_array['time'].' AND price = '.$param_array['price'].' AND link = \''.$param_array['link'].'\' AND auteur = \''.$user->data['username'].'\'');
								$annonce = $get_id->fetch();
								
								$id_annonce = $annonce['id'];
								
								$get_id->closeCursor();
								
								$req_note = $bdd->prepare('INSERT INTO notes(auteur, annonce, value) VALUES(:auteur, :annonce, :value)');
								$req_note->execute(array('auteur' => $user->data['username'], 'annonce' => $id_annonce, 'value' => $param_array['note']));
								$req_note->closeCursor();
									
								echo('<div class="box msg-box"><p><i class="success fa fa-check fa-fw"></i> L\'annonce a bien été ajoutée, bien joué !</p></div>');
							}
						
							else {
								echo('<div class="box msg-box"><p><i class="error fa fa-cross fa-fw"></i> Au moins une erreur est survenue</p></div>');
								print_form_new_offer($param_array, $sort_array, $action);
							}
						}
					break;
					
					case 'edit':
						$annonce_id = $request->variable('annonce', 0);
						
						if($annonce_id > 0) {
							echo('<h1>Modification d\'une annonce</h1>');
							
							$get_annonce = $bdd->prepare('SELECT id, auteur, lieu, link, departement, superf_h, superf_t, habit, time, distance, price FROM annonces WHERE id = :id');
							$get_annonce->execute(array('id' => $annonce_id));
							$annonce = $get_annonce->fetch();
							
							if($annonce != NULL) {
								if($annonce['auteur'] == $user->data['username']) {
									$annonce['note'] = get_user_note($annonce_id, $user->data['username']);
									
									if(!isset($_POST['Valider'])) {
										print_form_new_offer($annonce, $sort_array, $action);
									}
										
									else {
										$new_param_array = get_new_annonce_param_array($sort_array);
										
										$to_change = [];
										
										foreach($new_param_array as $new_n => $new_c) {
											if($new_c != $annonce[$new_n]) {
												$to_change[$new_n] = $new_c;
											}
										}
										
										if(!empty($to_change)) {
											if(!(count($to_change) == 1 && !empty($to_change['note']))) {
												$query = 'UPDATE annonces SET date = NOW()';
												$have_to_add_comma = true;
												$have_to_update_note = false;
												
												foreach($to_change as $n => $c) {
													if($n != 'note') {
														if($have_to_add_comma) $query .= ', '.$n.' = :'.$n;
														
														else {
															$query .= $n.' = :'.$n;
															$have_to_add_comma = true;
														}
														
														$array_prepare[$n] = $c;
													}
													
													else $have_to_update_note = true;
												}
											
												$query .= ' WHERE id = :id';
												$array_prepare['id'] = $annonce_id;
												
												$update_annonce = $bdd->prepare($query);
												$update_annonce->execute($array_prepare);
												$update_annonce->closeCursor();
											}
											
											else $have_to_update_note = true;
											
											if($have_to_update_note) {
												$update_note = $bdd->prepare('UPDATE notes SET value = :note WHERE annonce = :id AND auteur = :username');
												$update_note->execute(array('note' => $to_change['note'], 'id' => $annonce_id, 'username' => $user->data['username']));
												$update_note->closeCursor();
											}
											
											echo('<div class="box msg-box"><p><i class="success fa fa-check fa-fw"></i> Les changements ont bien été apportés à l\'annonce '.$annonce_id.'</p></div>');
										}
										
										else echo('<div class="box msg-box"><p><i class="error fa fa-cross fa-fw"></i> Aucun changement apporté à l\'annonce !</p></div>');
									}
								}
								
								else echo('<div class="box msg-box"><p><i class="error fa fa-cross fa-fw"></i> Tu ne peux pas modifier une annonce qui ne t\'appartient pas !</p></div>');
								
								$get_annonce->closeCursor();
							}
							
							else echo('<div class="box msg-box"><p><i class="error fa fa-cross fa-fw"></i> Cette annonce n\'existe pas ou plus !</p></div>');
						}
						
						else echo('<div class="box msg-box"><p><i class="error fa fa-cross fa-fw"></i> Pas d\'annonce à modifier !</p></div>');
					break;
					
					default:
						echo('<div class="box msg-box"><p><i class="error fa fa-cross fa-fw"></i> Mauvaise valeur de action dans l\'url.</p></div>');
					break;
				}
			} ?>
		</section>
		<?php add_footer(); ?>
	</body>
</html>