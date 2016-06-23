<?php
include('include/functions.php');
 ?>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Un projet de malade</title>
		<link rel="stylesheet" href="style.css" />
	</head>
	<body>
		<?php include_content('top'); ?>
		<section id="main">
			<h1>Accueil du site</h1>
			<p>Yo, je te présente le site internet dédié à notre recherche de Maison / Ferme / Grange / etc ... Si tu t'inscris, tu vas pouvoir
				<a href="<?php echo(append_sid('annonces.php'));?>">ajouter</a> des annonces intéressantes dans la base de données,
				<a href="<?php echo(append_sid('comments.php')); ?>">commenter</a> les annonces déjà publiées,
				<a href="<?php echo(append_sid('listeCR.php')); ?>">lire</a> les comptes-rendus de réunion, et poster des messages sur le
				<a href="<?php echo(append_sid('forum/index.php')); ?>">Forum</a>. N'hésite plus, rejoins-nous, on est bien.</p>
			<p>Si tu te sens un peu trop babos et tu cherches quelqu'un qui te remette à ta place, tu peux écouter la musique ci-dessous.</p>
			<iframe width="420" height="315" src="https://www.youtube.com/embed/Rq3SYvzm6c8"></iframe>
		</section>
		<?php include_content('bottom'); ?>
	</body>
</html>