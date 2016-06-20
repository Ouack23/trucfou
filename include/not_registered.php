<?php
echo ('<p>Vous devez vous connecter. <br />');
echo ('Connectez-vous <a href="'.append_sid('forum/ucp.php', 'mode=login', false).'">ici</a>,<br />');
echo ('Ou inscrivez-vous <a href="'.append_sid('forum/ucp.php', 'mode=register', false).'">ici</a>.</p>');
?>
