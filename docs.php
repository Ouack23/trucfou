﻿<?php
	include_once("include/config.php");
	include_once("include/header_footer.php");
	include_once("include/phpBB.php");
	include_once("include/utils.php");
?>

<html>
	<head>
		<meta charset="utf-8" />
		<title>Un projet de malade - Documents</title>
		<link rel="stylesheet" href="css/style.css" />
		<link rel="icon" type="image/x-icon" href="favicon.ico" />
	</head>
	
	<body>
		<?php add_header();
		$current_page = 'docs.php';
		
		echo('<section id="main">');
		echo('<h1 class="page-title">Documents</h1>');
		
		if(!$user->data['is_registered']) include('include/not_registered.php');
		
		else {

			echo('<div class="flex-container flex-column">');

			$docs_folder = 'include/docs/';
			$ext = 'pdf';
			$allowed_exts = 'application/pdf';
			$categories = ['admin' => 'Administratif', 'CR_gen' => 'Compte-Rendu - Assemblée Générale', 'CR_gdt' => 'Compte-Rendu - Groupe de Travail', 'other' => 'Autre'];
			
			$reponse = $bdd->query('SELECT id, '.format_date().', auteur, title, name, category, enable FROM CR');

			print_table_header(['N°', 'Date', 'Auteur', 'Catégorie', 'Titre', 'Lien'], 'Liste des Documents');
			
			while($donnees = $reponse->fetch()) {
				if($donnees['enable'] == 1) {
					echo('<tr><td class="left">'.$donnees['id'].'</td>');
					echo('<td>'.$donnees['date'].'</td>');
					echo('<td>'.$donnees['auteur'].'</td>');
					echo('<td>'.$categories[$donnees['category']].'</td>');
					echo('<td>'.$donnees['title'].'</td>');
					echo('<td><a href="'.append_sid($current_page, 'name='.$donnees['name'].'').'">Visualiser</a></td></tr>');
				}
			}

			$reponse->closeCursor();
			echo('</table></div></div></div>');
			
			$to_enable = $bdd->query('SELECT id, '.format_date().', auteur, title, name, category, enable FROM CR WHERE enable = 0');
			
			if($user->data['username'] == 'Belette' && $to_enable->fetch()) {
				$to_enable->closeCursor();
				print_table_header(['N°', 'Date', 'Auteur', 'Catégorie', 'Titre', 'Lien', 'Activer', 'Supprimer'], 'Liste des Documents à activer');
			
				$reponse = $bdd->query('SELECT id, '.format_date().', auteur, title, name, category, enable FROM CR');
			
				while($donnees = $reponse->fetch()) {
					if($donnees['enable'] == 0) {
						echo('<tr><td class="left">'.$donnees['id'].'</td>');
						echo('<td>'.$donnees['date'].'</td>');
						echo('<td>'.$donnees['auteur'].'</td>');
						echo('<td>'.$categories[$donnees['category']].'</td>');
						echo('<td>'.$donnees['title'].'</td>');
						echo('<td><a href="'.append_sid($current_page, 'name='.$donnees['name'].'').'">Visualiser</a></td>');
						echo('<td><a href="'.append_sid($current_page, 'id='.$donnees['id'].'&amp;action=activate').'">Activer</a></td>');
						echo('<td><a href="'.append_sid($current_page, 'id='.$donnees['id'].'&amp;action=delete').'">Supprimer</a></td></tr>');
					}
				}
				
				echo('</table></div></div></div>');
				
				if(isset($_GET['id']) && isset($_GET['action'])) {
					$id = $request->variable('id', 0);
					$action = $request->variable('action', '');
					
					if($action == 'activate') {
						$activate = $bdd->prepare('UPDATE CR SET enable = 1 WHERE id = :id');
						
						if($activate->execute(array('id' => $id))) echo('<div class="notification"><p><span class="icon-checkmark"></span> Le document a été correctement validé !</p></div>');
					}
					
					elseif($action == 'delete') {
						$get_doc = $bdd->prepare('SELECT * FROM CR WHERE id = :id');
						$get_doc->execute(array('id' => $id));
						
						$filename = $get_doc->fetch()['name'];
						$path = $docs_folder.$filename;
						
						if(unlink($path)) echo('<div class="notification"><p><span class="icon-checkmark"></span> Le document a été supprimé !</p></div>');
						
						$get_doc->closeCursor();
						
						$delete = $bdd->prepare('DELETE FROM CR WHERE id = :id');
						
						if($delete->execute(array('id' => $id))) {
							echo('<div class="notification"><p><span class="icon-checkmark"></span> Le document a été correctement supprimé de la base de données.</p></div>');
							$delete->closeCursor();
						}
					}
					
					else echo('<div class="notification"><p><span class="icon-cross"></span> Mauvais paramètres dans l\'URL.</p></div>');
				}
			}
			
			if(isset($_FILES['document'])) {
				$file = $request->file('document');
				
				try {
					switch($file['error']) {
						case UPLOAD_ERR_OK:
							break;
						case UPLOAD_ERR_NO_FILE:
							throw new RuntimeException('<div class="notification"><p><span class="icon-cross"></span> Pas de fichier !</p></div>');
						case UPLOAD_ERR_INI_SIZE:
						case UPLOAD_ERR_FORM_SIZE:
							throw new RuntimeException('<div class="notification"><p><span class="icon-cross"></span> Fichier trop grand !</p></div>');
						default:
							throw new RuntimeException('<div class="notification"><p><span class="icon-cross"></span> Erreur inconnue !</p></div>');
					}
					
					if($file['size'] > $request->variable('MAX_FILE_SIZE', 0))
						throw new RuntimeException('<div class="notification"><p><span class="icon-cross"></span> Fichier trop grand !</p></div>');
					
					$finfo = new finfo(FILEINFO_MIME_TYPE);
					
					if ($finfo->file($file['tmp_name']) != $allowed_exts)
						throw new RuntimeException('<div class="notification"><p><span class="icon-cross"></span> Le fichier n\'est pas un PDF !</p></div>');
					
					else {
						$name = sprintf('%s.%s', sha1_file($file['tmp_name']), $ext);
						$path = sprintf('%s%s', $docs_folder, $name);
					}
					
					if(!in_array($request->variable('category', ''), $categories))
						throw new RuntimeException('<div class="notification"><p><span class="icon-cross"></span> Mauvaise catégorie !</p></div>');
					
					if(!move_uploaded_file($file['tmp_name'], $path))
						throw new RuntimeException('<div class="notification"><p><span class="icon-cross"></span> Déplacement impossible !</p></div>');
					
					echo '<div class="notification"><p><span class="icon-checkmark"></span> Upload réussi !</p></div>';
					
					$req = $bdd->prepare('INSERT INTO CR(date, auteur, category, title, name, enable) VALUES(NOW(), :auteur, :category, :title, :name, 0)');
					
					$req->execute(array('auteur' => $user->data['username'], 'category' => $request->variable('category', ''), 'title' => $request->variable('title', ''), 'name' => $name));
					
					echo('<div class="notification"><p><span class="icon-checkmark"></span> Base de données mise à jour ! Rechargez la page pour voir votre document.</p></div>');
					
					$req->closeCursor();
				}
				
				catch (RuntimeException $e) {
					echo $e->getMessage();
				}
			}

			// Post a new document
			echo('<div class="box hide-by-default">
					<div class="box-header">
						<h2>Poster un nouveau document</h2>
					</div>

					<div class="box-content">
						<div class="flex-container">
							<div class="posting-form">
								<form method="post" action="#" enctype="multipart/form-data" accept-charset="utf-8" id="form" name="form">
									<p>
										<input type="hidden" name="MAX_FILE_SIZE" value="8000000" />
										<label for="document">Fichier à uploader (8 Mo max !) : </label><input type="file" name="document" id="document" /><br />
										<label for="category">Catégorie : </label><select name="category" id="category">
											');
											foreach($categories as $c => $n) {
												echo('<option value="'.$c.'">'.$n.'</option>');
											}
											echo ('
										</select><br />

										<label for="name">Titre du document : </label><input type="text" name="title" id="title" /><br />

										<span class="submit-container"><input type="submit" value="Valider" /></span>
									</p>
								</form>
							</div>
						</div>
					</div>
				</div>
			');
			
			if(isset($_GET['name'])) {
				$complete_name = $docs_folder.$request->variable('name', '');
				
				foreach(glob($docs_folder.'*.pdf') as $doc) {
					if($complete_name == $doc) {
						echo('<object data="'.$doc.'" type="application/pdf" height="100%" width="70%">');
						echo('<p class="error">Votre navigateur est moisi et ne supporte pas l\'affichage pdf en html5. Vous pouvez directement télécharger le pdf <a href="'.$doc.'">ici</a>.<br />');
						echo('Vous pouvez également télécharger un navigateur potable <a href="https://www.mozilla.org/en-US/firefox/all/#fr">ici</a>.</p>');
						echo('</object>');
					}
				}
			}
		}

		echo('</div></div></div>');
		echo('</section>');
		add_footer();?>
	</body>
</html>