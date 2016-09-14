<section id="login">
	<?php if(!$user->data['is_registered']) { ?>
		<form accept-charset="utf-8" action="<?php echo(append_sid('forum/ucp.php', 'mode=login', true, $user->session_id)); ?>" method="post">
			<p>
				<label for="username">Username:</label><input type="text" name="username" id="username" size="10" title="Username" /><br />
				<label for="password">Password:</label><input type="password" name="password" id="password" size="10" title="Password" /><br />
				<input type="hidden" name="redirect" value="../index.php" /><br />
				<input type="submit" name="login" value="Login" />
			</p>
		</form>
		<form accept-charset="utf-8" action="<?php echo(append_sid('forum/ucp.php', 'mode=register', true, $user->session_id)); ?>" method="post">
			<p><input type="submit" value="Inscription" /></p>
		</form>
		<?php
	}

	else { ?>
		<form accept-charset="utf-8" action="<?php echo(append_sid('forum/ucp.php', 'mode=logout', true, $user->session_id)); ?>" method=post>
			<p><input type="submit" name="logout" value="Logout" /></p>
		</form>
		
		<form accept-charset="utf-8" action="new_annonce.php" method=post>
			<p><input type="submit" value="Nouvelle annonce" /></p>
		</form>
		<?php
	} ?>
</section>
