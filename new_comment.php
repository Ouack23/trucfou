<?php 
include('include/functions.php'); 
include('include/config.php');?>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Nouveau commentaire</title>
		<link rel="stylesheet" href="style.css" />
		<link rel="stylesheet" href="include/xbbcode.css" />
		<script src="include/xbbcode.js"></script>
		<script src="include/BBcode.js"></script>
	</head>
	<body>
		<?php include_content('top');
		echo('<section id="main">');	
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
						if(empty($current_url_annonce) and !isset($_POST['preview'])) {
							echo('<p>Sélectionnez une annonce à commenter :</p>');

							select_annonce();
						}

						//Création du commentaire
						else {?>
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
							
							<form action="#" method="post">
								<p>Commentaire de l'annonce n°<?php echo($current_url_annonce);?> : <br /></p>
								<textarea name="comment"></textarea>
								<div id="preview">
									<input type="submit" name="preview" value="Prévisualiser" /><br />
									<input type="submit" name="submit" value="Valider" />
									<input type="text" name="annonce" value="<?php echo($current_url_annonce); ?>" style="display: none;" />
								</div>
							</form>
							<?php
							if(isset($_POST['preview'])) {
								echo('<div id="invisiblePreview" style="display: none;">');
								echo($request->variable('comment', ''));
								echo('</div>');
								echo('<p id="visualPreview"></p>');
								echo('<script>doPreview();</script>');
							}
						}
					}
					else{echo('<p>Pas d\'annonce à commenter, dommage ... Vous pouvez créer une nouvelle annonce <a href="new_annonce.php">ici</a></p>');}
				}
				
				//Traitement de la validation
				else {
					$req = $bdd->prepare('INSERT INTO comments(annonce, date, auteur, comment) VALUES(:annonce, NOW(), :auteur, :comment)');
					$req->execute(array(
						'annonce' => $current_url_annonce,
						'auteur' => $user->data['username'],
						'comment' => $request->variable('comment', '')
						));
					echo('<h3>Succès !</h3>');
					echo('<p>Vous pouvez aller consulter votre commentaire <a href=comments.php?annonce='.$current_url_annonce.'>ici</a></p>');
				}
			}
		echo('</section>');
		include_content('bottom'); ?>
	</body>
</html>