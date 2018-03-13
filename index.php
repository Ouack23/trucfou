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
		<title>Le Crew d'Secours</title>
		<link rel="stylesheet" href="css/style.css" />
		<link rel="icon" type="image/x-icon" href="favicon.ico" />
	</head>
	<body>
		<?php add_header('top'); ?>

		<section id="main">

			<h1 class="page-title">Accueil</h1>

			<div class="flex-container">

				<div class="box news hide-by-default-mobile">

					<div class="box-header">
						<h2><span class="icon-bubbles4"></span>Dernières nouvelles</h2>
					</div>

					<div class="box-content">
						<h3>Les points chauds du moment</h3>

			            <p>
			            	On est nombreux, on est chauds, mais on a pas de maison. Alors zou les visites !
			            </p>

						<h3>Le Groupe de Travail</h3>

						<p>
							[...]
						</p>
					</div>

				</div>

				<div class="box calendar hide-by-default-mobile">

					<div class="box-header">
						<h2><span class="icon-calendar"></span>Prochaines dates</h2>
					</div>

					<div class="box-content">

						<div class="block">
							<ul class="block-titre">
								<li class="block-quand"><span class="icon-clock"></span> 30 mars</li>
								<li class="block-quoi"><span class="icon-checkmark"></span> Grand Groupe <em>(réu plénière)</em></li>
								<li class="block-ou"><span class="icon-location"></span> Lieu à définir</li>
							</ul>

							<p>
								La prochaine réunion Grand Groupe aura lieu le 30 mars 2018, dans un lieu qui reste encore à définir. 
								<br /><br />

								<u><em>Pour rappel, voici le sage message de notre cher LeLukas concernant la réunion précédente et qui exprimait fort justement la pensée de tous : </em> </u><br />
								<em>« En fait on s'est dit que faire une réunion à 12 c'était pas optireprésentatif et que nous n'en ferions plus à moins de 16 gens. »</em>
							</p>
						</div>

					</div>
				</div>

			</div>
		</section>
		<?php add_footer('bottom'); ?>
	</body>
</html>
