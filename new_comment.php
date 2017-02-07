<?php
	include_once("include/config.php");
	include_once("include/header_footer.php");
	include_once("include/new_comment_functions.php");
?>

<html>
	<head>
		<meta charset="utf-8" />
		<title>Nouveau commentaire</title>
		<link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
		<link href="https://fonts.googleapis.com/css?family=Ubuntu:400,400i,700" rel="stylesheet">
		<link rel="stylesheet" href="css/style.css" />
		<link rel="stylesheet" href="include/xbbcode.css" />
		<script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
		<script src="http://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
		<script src="include/xbbcode.js"></script>
		<script src="include/functions.js"></script>
		<link rel="stylesheet" href="http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
		<link rel="icon" type="image/x-icon" href="favicon.ico" />
	</head>
	<body>
<<<<<<< HEAD
		<?php include_content('top');
		echo('<section id="main">');
=======
		<?php
			add_header();
			echo('<section id="main">');	
>>>>>>> The Big Refactor
			echo('<h1>Écrire un nouveau commentaire</h1>');
			
			if(!$user->data['is_registered']) include('include/not_registered.php');

			else {
				$reponse = $bdd->query('SELECT * FROM annonces');
				$isThereAnnonce = $reponse->fetch();
				
				secure_get();
				
				//Si on a pas encore rempli le formulaire
				if(!isset($_POST['submit'])) {
					//Vérification de l'existence d'une annonce au moins
					if(!empty($isThereAnnonce)) {
						//Sélection d'une annonce à commenter
						if(empty($current_url['annonce']) and !isset($_POST['preview'])) {
							echo('<p>Sélectionnez une annonce à commenter</p>');
							
							select_annonce();
						}

						//Création du commentaire
						else {?>
							<h3 class="center">Annonce n°<?php echo($current_url['annonce']);?></h3>
							<div id="BBcode">
								<input type="submit" onclick="insertBalise('b')" value="Gras" />
								<input type="submit" onclick="insertBalise('i')" value="Italique" />
								<input type="submit" onclick="insertBalise('u')" value="Souligné" />
								<input type="submit" onclick="insertBalise('img')" value="Image" />
								<input type="submit" onclick="insertBalise('noparse')" value="Noparse" />
								<input type="submit" onclick="insertBalise('quote')" value="Citation" />
								<input type="submit" onclick="insertBalise('s')" value="Barré" />
								<input type="submit" onclick="insertBalise('table')" value="Tableau" />
								<input type="submit" onclick="insertBalise('tr')" value="Tableau - Ligne" />
								<input type="submit" onclick="insertBalise('td')" value="Tableau - Cellule" />
							</div>
							<form accept-charset="utf-8" action="#" method="post">
								<textarea name="comment" id="comment"></textarea>
								<p class="center"><input type="submit" name="submit" value="Valider" />
								<input type="hidden" name="annonce" value="<?php echo($current_url['annonce']); ?>" /></p>
							</form>
							<p class="center"><input type="submit" name="preview" value="Prévisualiser" onclick="doPreview();" /></p>
							<div id="visualPreview" class="box"><p></p></div>
							<?php
						}
					}
					
					else echo('<div class="box msg-box"><p><i class="error fa fa-cross fa-fw"></i> Pas d\'annonce à commenter, dommage ... Vous pouvez créer une nouvelle annonce <a href="new_annonce.php">ici</a></p></div>');
				}
				
				//Traitement de la validation
				else {
					$req = $bdd->prepare('INSERT INTO comments(annonce, date, auteur, comment) VALUES(:annonce, NOW(), :auteur, :comment)');
					$req->execute(array('annonce' => $current_url['annonce'], 'auteur' => $user->data['username'], 'comment' => $request->variable('comment', '')));
					echo('<div class="box msg-box"><p><i class="success fa fa-check fa-fw"></i> Vous pouvez aller consulter votre commentaire <a href=annonces.php?annonce='.$current_url['annonce'].'>ici</a></p></div>');
				}
			}
		echo('</section>');
		add_footer(); ?>
	</body>
</html>