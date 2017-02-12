<?php
$host = $request->server('HTTP_HOST', '');
$is_localhost = $host == 'localhost';

$admin = 'Belette';
$GdT = ['Éric', 'Buzz', 'Bastien', 'TimRocket', 'Zaza', 'Keks', 'ratichon'];

$is_admin = $user->data['username'] == $admin;
$is_in_GdT = in_array($user->data['username'], $GdT);

echo('<nav class="nav"><ul>');

echo('<li><a href="'.append_sid('index.php').'">Accueil</a></li>');
echo('<li><a href="'.append_sid('forum/index.php').'">Forum</a></li>');

if($user->data['is_registered']) {
	echo('<li><a href="'.append_sid('docs.php').'">Documents</a></li>');
	echo('<li><a href="'.append_sid('annonces.php', 'reverse=true').'">Annonces</a></li>');
	echo('<li><a href="'.append_sid('new_annonce.php').'">Nouvelle annonce</a></li>');
	
	if(!$is_localhost) {
		echo('<li><a href="'.append_sid('booked/Web/?').'">Calendrier</a></li>');
		
		if($is_admin || $is_in_GdT)
			echo('<li><a href="'.append_sid('survey/index.php/admin/authentication/sa/login').'">Admin Sondages</a></li>');
	}
	
	echo('<li><a href="'.append_sid('forum/ucp.php').'">Mon Profil</a></li>');
	echo('<li><a href="'.append_sid('forum/ucp.php', 'mode=logout', true, $user->session_id).'">Déconnexion</a></li>');
	
	if($is_admin && $is_localhost)
		echo('<li><a href="'.append_sid('phpmyadmin/').'">PMA</a></li>');
} else {

	echo('<li><a href="'.append_sid('forum/ucp.php', 'mode=register', true, $user->session_id).'">Inscription</a></li>');
	echo('<li><span class="connexion-link">Connexion</span></li>');
	echo('<section id="login-frame" class="hidden">
		<div class="box login-box">
			<div class="box-header">
				<h2>Connexion</h2>
			</div>

			<div class="box-content">
				<form accept-charset="utf-8" action="'.append_sid('forum/ucp.php', 'mode=login', true, $user->session_id).'" method="post">
					<label for="username" ><span class="icon-user login-icons"></span></label><input type="text" name="username" id="username" size="10" title="Username" placeholder="Login" /><br />
					<label for="password" ><span class="icon-lock login-icons"></span></label><input type="password" name="password" id="password" size="10" title="Password" placeholder="Mot de passe" /><br />
					<input type="submit" name="login" value="Connexion" />
					<input type="hidden" name="redirect" value="../index.php" />
				</form>
			</div>
		</div>
	</section>
	');
}

echo('</ul></nav>');
?>