<nav>
		<ul>
			<?php
				echo('<li><a href="'.append_sid('index.php').'">Accueil</a></li>');
				echo('<li><a href="'.append_sid('forum/index.php').'">Forum</a></li>');

				if($user->data['is_registered']) {
					echo('<li><a href="'.append_sid('listeCR.php').'">Comptes-Rendus</a></li>');
					echo('<li><a href="'.append_sid('annonces.php').'">Annonces</a></li>');
					echo('<li><a href="'.append_sid('comments.php').'">Commentaires</a></li>');
				}
			?>
		</ul>
</nav>