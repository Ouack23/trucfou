<?php
	if(!$user->data['is_registered']) {
?>
	<section id="login">
		<form accept-charset="utf-8" action="<?php echo(append_sid('forum/ucp.php', 'mode=login', true, $user->session_id)); ?>" method="post">
			<p>
				<label for="username">Identifiant : </label><input type="text" name="username" id="username" size="10" title="Username" />&emsp;
				<label for="password">Mot de passe : </label><input type="password" name="password" id="password" size="10" title="Password" />
				<input type="submit" name="login" value="Connexion" />
				<input type="hidden" name="redirect" value="../index.php" />
			</p>
		</form>
	</section>
<?php
	}
?>
