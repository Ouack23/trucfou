<?php
include('include/functions.php');
include('include/config.php');
?>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Nouvelle annonce</title>
		<link rel="stylesheet" href="style.css" />
	</head>
	<body>
		<?php include_Content('top'); ?>
		<section id="main">	
			<h1>Nouvelle annonce</h1>
			<?php
			echo('<h1>Poster une nouvelle annonce</h1>');
			
			if(! $user->data['is_registered']) include('include/not_registered.php');

			else {
				secureGet();
				
				//Si on a pas encore rempli le formulaire
				if(!isset($_POST['Valider'])) print_form_new_annonce(['', '', '', '', '', '', '']);
				
				else {
					$lieu = $request->variable('lieu', '');
					$superf_h = $request->variable('superf_h', 0);
					$superf_t = $request->variable('superf_t', 0);
					$link = $request->variable('link', '');
					$habit = $request->variable('habit', '');
					$time = $request->variable('time', 0);
					$price = $request->variable('price', 0.0);
					
					//Conversion de habit en chiffre et vérification des valeurs possibles
					$habit = convert_habit($habit);
					
					if($lieu < 0) $lieu = 0;
					if($superf_h < 0) $superf_h = 0;
					if($superf_t < 0) $superf_t = 0;
					if($time < 0) $time = 0;
					
					$param_array = ['lieu' => $lieu, 'superf_h' => $superf_h, 'superf_t' => $superf_t, 'link' => $link, 'habit' => $habit, 'time' => $time, 'price' => $price];
					
					$print_form = false;
					
					//Si tous les champs sont remplis
					if(!empty($lieu) && $superf_h != 0 && $superf_t != 0 && !empty($link) && $habit != 0 && $time != 0 && !empty($price)) {
						//Vérification du lien
						if(preg_match('#^https?://(www.)?[a-zA-Z0-9]+\.[a-z0-9]{1,4}\??#', $link)) {
							//Vérification de time, superf_h, superf_t et price
							if($time < 256) {
								if($superf_h < 65536) {
									if($superf_t < 65536) {
										if($price < 1000) {
											$req = $bdd->prepare('INSERT INTO annonces(lieu, superf_h, superf_t, link, habit, time, price, date, auteur)
																VALUES(:lieu, :superf_h, :superf_t, :link, :habit, :time, :price, NOW(), :auteur)');
											
											$req->execute(array(
												'lieu' => $lieu,
												'superf_h' => $superf_h,
												'superf_t' => $superf_t,
												'link' => $link,
												'habit' => $habit,
												'price' => $price,
												'auteur' => $user->data['username'],
												'time' => $time));
											
											echo('<p id="form" class="success">L\'annonce a bien été ajoutée, bien joué !</p>');
										}
										
										else {
											$print_form = true;
											echo('<p id="form" class="error">Le prix doit être inférieur à 1000 k€ ! Faut pas déconner !</p>');
										}
									}
									
									else {
										$print_form = true;
										echo('<p id="form" class="error">La superficie du terrain doit être comprise entre 1 et 65536 !');
									}
								}
								
								else {
									$print_form = true;
									echo('<p id="form" class="error">La superficie de la maison doit être comprise entre 1 et 65536 !');
								}
								
							}
							
							else{
								$print_form=true;
								echo('<p id="form" class="error">Le temps doit être compris entre 1 et 255 inclus !</p>');
							}
						}
						
						else{
							$print_form=true;
							echo('<p id="form" class="error">Le lien n\'est pas correct !<p>');
						}
					}
					
					//S'il manque des champs
					else {
						$print_form = true;
						echo('<p id="form" class="error">Il faut remplir tous les champs !</p>');
					}
					
					//Si un ou plus des valeurs du formulaire sont mauvaises
					if($print_form) print_form_new_annonce($param_array);
				}
			} ?>
		</section>
		<?php include_Content('bottom'); ?>
	</body>
</html>