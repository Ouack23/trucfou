﻿<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	include('include/phpBB.php');
	include('include/header_footer.php');
?>

<html>
	<head>
		<meta charset="utf-8" />
		<title>Un projet de malade</title>
		<link rel="stylesheet" href="css/style.css" />
		<script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
		<link rel="icon" type="image/x-icon" href="favicon.ico" />
	</head>
	<body>
		<?php add_header('top'); ?>
		
		<section id="main">

			<h1 class="page-title">Accueil</h1>

			<div class="flex-container">

				<div class="box news">

					<div class="box-header">
						<h2><span class="icon-bubbles4"></span>Dernières nouvelles</h2>
					</div>

					<div class="box-content">
						<h3>Les points chauds du moment</h3>

						<p>
							Nous devons (entre autres considérations sémantiques) trouver un <a href="forum/viewtopic.php?f=7&t=3">nom à notre association</a> !<br />
						</p>

			            <p>
			            	Afin de pouvoir lancer le processus d'escapades campagnardes, il va nous être utile d'établir <a href="forum/viewtopic.php?f=4&t=37">une feuille de visite</a>. Faites marcher vos méninges et participez pour la compléter ! (il faudra également trouver un format pratique pour celle-ci)<br />
			            </p>

			            <p>
			            	Et qui dit visites dit également qu'il faut continuer à <a href="annonces.php?reverse=true">chercher des annonces intéressantes.</a> Ainsi qu'à les noter bien évidemment ;)
			            </p>

			            <p>
			            	Enfin, l'idée de s'organiser un week-end entre nous a été évoquée et globalement approuvée. Pour l'organisation (frama, lieu, etc.), ça se passe <a href="forum/viewtopic.php?f=19&t=65">ici.</a>
			            </p>

						<h3>Le Groupe de Travail</h3>

						<p>
							La création d'une association est nécessaire avant d'envisager tout investissement financier. Il nous faut donc définir des <em>statuts</em> et un <em>règlement intérieur</em>.<br /><br/>

							Après l'observation du fait que les discussions en groupe entier à ces sujets étaient bien trop longues, un groupe de travail a donc été constitué, autour des personnes suivantes : Keks, <s>Zaza</s>, Bastien, Éric, Koline, TimRocket,<del> Ratich</del>, <del>Niko</del>, LeLukas, Fanny et <del>Belette</del>.<br />
							Ils se sont engagés quant à leur disponibilité et leur travail actif à ces sujets administratifs d'une importance majeure. Leur but est de déblayer au maximum la question avant de soumettre leur premier jet de texte au reste du groupe.<br />
							Leurs réunions sont évidemment accessibles à tous.<br/>
							Ces réunions ont généralement lieu le mardi soir. Pour être sûr du lieu et/ou de l'horaire, allez voir <a href="http://trucfou.pe.hu/forum/viewforum.php?f=19">ici</a>.<br />
							Les comptes rendus des réunions Groupe de Travail sont ensuite tous uploadés <a href="http://trucfou.pe.hu/forum/viewforum.php?f=18">ici</a>.
						</p>
					</div>

				</div>

				<div class="box calendar">

					<div class="box-header">
						<h2><span class="icon-calendar"></span>Prochaines dates</h2>
					</div>

					<div class="box-content">

						<div class="block">
							<ul class="block-titre">
								<li class="block-quand"><span class="icon-clock"></span> Date à définir</li>
								<li class="block-quoi"><span class="icon-checkmark"></span> Grand Groupe <em>(réu plénière)</em></li>
								<li class="block-ou"><span class="icon-location"></span> Lieu à définir</li>
							</ul>

							<p>
								La prochaine réunion Grand Groupe aura lieu à une date encore inconnue, située entre février et mars 2017, dans un lieu qui reste encore à définir. Soyez motivés, soyez chatoyants et donnez vos disponibilités sur <a href="https://framadate.org/Eb3ttHBSSZ6EjVvB">ce framazob !</a>

								<br /><br />

								<u><em>Pour rappel, voici le sage message de notre cher LeLukas concernant la réunion précédente et qui exprimait fort justement la pensée de tous : </em> </u><br />
								<em>« En fait on s'est dit que faire une réunion à 12 c'était pas optireprésentatif et que nous n'en ferions plus à moins de 16 gens. A vos agenda les poulets ! Pensez à modifier vos disponibilités sur le framazobie si vos disponibilités changent. »</em>
							</p>
						</div>

						<div class="block">
							<ul class="block-titre">
								<li class="block-quand"><span class="icon-clock"></span> Mardi prochain</li>
								<li class="block-quoi"><span class="icon-checkmark"></span> Groupe de Travail</em></li>
								<li class="block-ou"><span class="icon-location"></span> Lieu à définir</li>
							</ul>

							<p>
								Les réunions Groupe de Travail ont lieu tous les mardis soirs à 20h. Tout le monde peut y participer. Pour être sûr du lieu, allez voir <a href="http://trucfou.pe.hu/forum/viewforum.php?f=19">ici</a>.
							</p>
						</div>

					</div>
				</div>

			</div>
		</section>
		<?php add_footer('bottom'); ?>
	</body>
</html>
