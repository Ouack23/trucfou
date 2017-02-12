<?php
echo('<div class="flex-container">
		<div class="box">
			<div class="box-content">');

				echo('<p>Vous devez vous connecter. <br />');
				echo('Connectez-vous <span class="connexion-link simili-link">ici</span>,<br />');
				echo('Ou inscrivez-vous <a href="'.append_sid('forum/ucp.php', 'mode=register', false).'">ici</a>.</p>');

echo('</div></div></div>')
?>
