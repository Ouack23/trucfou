<?php
include('include/functions.php');
 ?>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Un projet de malade</title>
		<link rel="stylesheet" href="style.css" />
		<link rel="icon" type="image/x-icon" href="favicon.ico" />
	</head>
	<body>
		<?php include_content('top'); ?>
		<section id="main">
			<h1>Accueil du site</h1>
			<p>Yo, je te présente le site internet dédié à notre recherche de Maison / Ferme / Grange / etc ... Si tu t'inscris, tu vas pouvoir
				<a href="<?php echo(append_sid('annonces.php'));?>">ajouter</a> des annonces intéressantes dans la base de données,
				<a href="<?php echo(append_sid('comments.php')); ?>">commenter</a> les annonces déjà publiées,
				<a href="<?php echo(append_sid('docs.php')); ?>">lire</a> les comptes-rendus de réunion, et poster des messages sur le
				<a href="<?php echo(append_sid('forum/index.php')); ?>">Forum</a>. N'hésite plus, rejoins-nous, on est bien !<br /><br />
			</p>
			
			<h2>Prochaine réunion</h2>
			<p>Remplissez le <a href="https://framadate.org/TNsCagUIBPnns1XT">Framadate</a> de la prochaine réunion !</p>
		</section>
		<?php include_content('bottom'); ?>
	</body>
</html>