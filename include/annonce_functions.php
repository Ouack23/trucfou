<?php
include_once("functions.php");
include_once("utils.php");
function print_sort_form($current_page, $current_url, $sort_array) {
	global $user, $bdd;
	
	$inf_sup_array = ['sup' => 'Supérieur à', 'inf' => 'Inférieur à'];
	
	echo('<div class="box">
			<div class="box-header">
				<h2>Filtres</h2>
			</div>
			<div class="box-content">');
	echo('<form accept-charset="utf-8" action="#" method="get" id="form_sort_annonce"><div class="filters">');
	echo('<p><div class="filter-group"><label for="sort_date">Date</label><select id="sort_date" name="sort_date">');
	echo('<option value="before"');
	if(isset($_GET['value_date']) && $sort_array['sort_date'] == 'before') echo ' selected';
	echo('>Avant</option>');
	echo('<option value="after"');
	if(isset($_GET['value_date']) && $sort_array['sort_date'] == 'after') echo ' selected';
	echo('>Après</option>');
	echo('</select>');
	
	echo('<input type="text" name="value_date" id="datepicker" value="');
	if(!isset($_GET['value_date'])) echo(date('d/m/Y').'"/>');
	else echo($sort_array['value_date'].'"/>');
	echo('</div>');
	
	if($current_page == 'annonces.php') {
		echo('<div class="filter-group"><label for="sort_auteur">Auteur</label>');
		echo('<select id="sort_auteur" name="sort_auteur">');
		print_liste('auteur');
		echo('</select></div>');
	}
	
	echo('<div class="filter-group"><label for="sort_lieu">Lieu</label>');
	echo('<select id="sort_lieu" name="sort_lieu">');
	print_liste('lieu');
	echo('</select></div>');
	
	echo('<div class="filter-group"><label for="sort_departement">Département</label>');
	echo('<select id="sort_departement" name="sort_departement">');
	print_liste('departement');
	echo('</select></div>');
	
	$get_max = $bdd->query('SELECT MAX(superf_h) AS superf_h, MAX(superf_t) AS superf_t, MAX(time) AS time, MAX(price) AS price, MAX(distance) AS distance FROM annonces');
	$max = $get_max->fetch();
	$get_max->closeCursor();
	
	print_option_select($inf_sup_array, 'superf_h', 'Superficie bâtie', $sort_array['min_superf_h'], $max['superf_h'], 50);
	echo('<br />');
	print_option_select($inf_sup_array, 'superf_t', 'Superficie du terrain', $sort_array['min_superf_t'], $max['superf_t'], 50);
	print_option_select($inf_sup_array, 'habit', 'État', $sort_array['min_habit'], $sort_array['max_habit'], 1);
	print_option_select($inf_sup_array, 'time', 'Trajet', $sort_array['min_time'], $max['time'], 10);
	print_option_select($inf_sup_array, 'distance', 'Distance', $sort_array['min_distance'], $max['distance'], 10);
	echo('<br />');
	print_option_select($inf_sup_array, 'price', 'Prix', $sort_array['min_price'], $max['price'], 10);
	print_option_select($inf_sup_array, 'note', 'Note', $sort_array['min_note'], $sort_array['max_note'], 1);
	
	echo('<div class="filter-group"><label for="hide_disabled">Cacher les indisponibles</label><input type="checkbox" name="hide_disabled" id="hide_disabled" value="true" '.print_checked_enabled_only($sort_array).' /></div></div>');
	
	echo('<br /><span class="submit-container"><input type="submit" name="sort" id="sort" value="Valider" /></span></p>');
	echo('</form>');
	echo('</div></div>');
}

function print_all_annonces($current_page, $current_url, $sort_array) {
	global $bdd;
	
	$what = 'all_annonces';
	
	$columns_array = ['id' => 'N°',
			'date' => 'Date',
			'auteur' => 'Auteur',
			'lieu' => 'Lieu',
			'departement' => 'Dpt',
			'superf_h' => 'Superficie bâtie',
			'superf_t' => 'Superficie du terrain',
			'habit' => 'État',
			'time' => 'Trajet',
			'distance' => 'Distance',
			'price' => 'Prix',
			'note' => 'Note',
			'comments' => 'Comms'];
	
	print_debut_table($columns_array, ['Lien', 'Détails'], 'Liste des Annonces', $current_url, $sort_array, 'annonces');
	
	$initial_query = 'SELECT id, '.format_date().', auteur, lieu, superf_h, superf_t, price, link, habit, time, distance, departement, available FROM annonces';
	
	$have_to_add_WHERE = true;
	$have_to_add_AND = false;
	
	$reponse_query = build_annonce_query($initial_query, true, false, $current_page, $current_url, $sort_array, $what);
	
	sort_print_annonces($reponse_query, $current_page, $current_url, $sort_array, $what);
	
	echo('</table></div></div></div>');
}

function print_comments_annonce($current_page, $current_url, $sort_array) {
	global $bdd, $user;
	
	$reponse_query = 'SELECT id, annonce, '.format_date().', auteur, comment FROM comments WHERE annonce = \''.$current_url['annonce'].'\' ORDER BY '.$current_url['orderComments'].'';
	
	if($current_url['reverseComments'] == 'true')
		$reponse_query .= ' DESC';
			
	$reponse = $bdd->query($reponse_query);
	$donnees = $reponse->fetch();
	
	print_single_annonce($current_page, $current_url, $sort_array);

	print_notation($current_url['annonce']);

	if(is_auteur($user->data['username'], $current_url['annonce'])) print_modify_annonce($current_url['annonce']);

	print_available($current_url['annonce']);
	
	
	if($donnees != NULL) {
		$columns_array = ['date' => 'Date', 'auteur' => 'Auteur'];

		echo('<h3>Commentaires</h3>');

		do {
			echo('
				<div class="block">
					<ul class="block-titre">
						<li class="block-quand"><span class="icon-clock"></span> '.$donnees['date'].'</li>
						<li class="block-quoi"><span class="icon-user"></span> Par <span class="block-author">'.$donnees['auteur'].'</span></li>
					</ul>

					<p>'.$donnees['comment'].'</p>
				</div>
			');
		} while ($donnees = $reponse->fetch());
	}
	
	else {
		echo('<h3 id="comments">Pas de commentaire pour cette annonce !</h3>');
	}

	echo('
		<form accept-charset="utf-8" action="new_comment.php?annonce='.$current_url['annonce'].'" method="post">
			<p class="center"><input type="submit" name="new_comment" value="Nouveau commentaire" /></p>
		</form>
	');

	echo('</div>');
	echo('</div></div>');

	$reponse->closeCursor();
}


function print_statistics($current_page, $current_url, $sort_array, $what) {
	global $bdd;
	
	$other_columns_array = ['Catégorie', 'Min', 'Quartile 1', 'Médiane', 'Quartile 3', 'Max', 'Moyenne'];
	
	print_table_header($other_columns_array, 'Statistiques des annonces');
	
	$rows = ['Prix' => 'price', 'Trajet' => 'time', 'Distance' => 'distance', 'Superficie Bâtie' => 'superf_h', 'Superficie Terrain' => 'superf_t', 'État' => 'habit', 'Note' => 'note'];
	
	foreach($rows as $n => $c) {
		if($c == 'note') {
			$get_values = $bdd->query('SELECT value FROM notes');
			$c = 'value';
		}
		else {
			$get_values = $bdd->query('SELECT '.$c.' FROM annonces');
		}
		$min = -1;
		$max = 0;
		$sum = 0;
		$values = [];
		
		while($data = $get_values->fetch()[$c]) {
			if( ($c == 'superf_h' ||  $c == 'superf_t') && $data == 1) {
				continue; // for superficies, 1 means unknown so don't take it in count for stats !
			}
			array_push($values, $data);
			if($min == -1 || $data < $min) {
				$min = $data;
			}
			if($data > $max) {
				$max = $data;
			}
			$sum += $data;
		}
		$nb = count($values);
		if($nb == 0) {
			continue;
		}
		$moy = $sum/$nb;

		$get_values->closeCursor();
		
		echo('<tr><td class="left">'.$n.'</td>');

		$quartiles = calcul_quartiles($values);

		if($c != 'note' && $c != 'habit') {
			
			echo('<td>'.$min.'</td>');
			echo('<td>'.$quartiles[0].'</td>');
			echo('<td>'.$quartiles[1].'</td>');
			echo('<td>'.$quartiles[2].'</td>');
			echo('<td>'.$max.'</td>');
			echo('<td>'.$moy.'</td></tr>');
		}
		
		else {
			echo('<td class="habit'.floor($min).'">'.$min.'</td>');
			echo('<td class="habit'.floor($quartiles[0]).'">'.$quartiles[0].'</td>');
			echo('<td class="habit'.floor($quartiles[1]).'">'.$quartiles[1].'</td>');
			echo('<td class="habit'.floor($quartiles[2]).'">'.$quartiles[2].'</td>');
			echo('<td class="habit'.floor($max).'">'.$max.'</td>');
			echo('<td class="habit'.floor($moy).'">'.$moy.'</td></tr>');
		}
	}
	
	echo('</table></div></div>');

	echo('</div></div>');
}
?>