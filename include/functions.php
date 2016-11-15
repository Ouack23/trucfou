<?php
define('IN_PHPBB', true);
$phpbb_root_path =(defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './forum/';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
global $user, $auth, $phpbb_root_path, $phpEx;

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup();

function format_date() {
	return 'DATE_FORMAT(date, "%d/%m/%Y") AS date';
}

function include_content($where) {
	global $phpbb_root_path, $phpEx, $config, $user, $auth, $cache, $template, $request, $session;
	switch($where) {
		case 'top':
			include('include/login.php');
			include('header.php');
			include('menu.php');
		break;

		case 'bottom':
			include('footer.php');
		break;

		default:
			echo('<p class="error">Erreur : mauvais paramètre pour la fonction include_content()</p>');
		break;
	}
}

function secure_get() {
	global $current_url, $sort_array, $request;
	
	$current_url['reverse'] = $request->variable('reverse', 'false');
	$current_url['order'] = $request->variable('order', 'id');
	$current_url['orderComments'] = $request->variable('orderComments', 'date');
	$current_url['reverseComments'] = $request->variable('reverseComments', 'false');
	$current_url['annonce'] = $request->variable('annonce', 0);
	$current_url['user'] = $request->variable('user', 0);
	$current_url['comments'] = $request->variable('comments', 'false');
	
	$sort_array['max_superf_h'] = 65535;
	$sort_array['max_superf_t'] = 65535;
	$sort_array['max_habit'] = 5;
	$sort_array['max_time'] = 255;
	$sort_array['max_distance'] = 1000;
	$sort_array['max_price'] = 100;
	$sort_array['max_departement'] = 95;
	$sort_array['max_note'] = 5;
	
	$sort_array['min_superf_h'] = 1;
	$sort_array['min_superf_t'] = 1;
	$sort_array['min_habit'] = 0;
	$sort_array['min_time'] = 1;
	$sort_array['min_distance'] = 1;
	$sort_array['min_price'] = 0.0;
	$sort_array['min_departement'] = 1;
	$sort_array['min_note'] = 0;
	
	$sort_array['auteur'] = $request->variable('sort_auteur', 'all');
	$sort_array['lieu'] = $request->variable('sort_lieu', 'all');
	$sort_array['departement'] = $request->variable('sort_departement', 'all');
	
	$sort_array['sort_date'] = $request->variable('sort_date', 'after');
	$sort_array['sort_superf_h'] = $request->variable('sort_superf_h', 'sup');
	$sort_array['sort_superf_t'] = $request->variable('sort_superf_t', 'sup');
	$sort_array['sort_habit'] = $request->variable('sort_habit', 'sup');
	$sort_array['sort_time'] = $request->variable('sort_time', 'sup');
	$sort_array['sort_distance'] = $request->variable('sort_distance', 'sup');
	$sort_array['sort_price'] = $request->variable('sort_price', 'sup');
	$sort_array['sort_note'] = $request->variable('sort_note', 'sup');
	
	$sort_array['value_date'] = $request->variable('value_date', '01/01/2016');
	$sort_array['value_superf_h'] = $request->variable('value_superf_h', $sort_array['min_superf_h']);
	$sort_array['value_superf_t'] = $request->variable('value_superf_t', $sort_array['min_superf_t']);
	$sort_array['value_habit'] = $request->variable('value_habit', $sort_array['min_habit']);
	$sort_array['value_time'] = $request->variable('value_time', $sort_array['min_time']);
	$sort_array['value_distance'] = $request->variable('value_distance', $sort_array['min_distance']);
	$sort_array['value_price'] = $request->variable('value_price', $sort_array['min_price']);
	$sort_array['value_note'] = $request->variable('value_note', $sort_array['min_note']);
	
	$sort_array['hide_disabled'] = $request->variable('hide_disabled', 'false');
}

function print_reverse($whichpage, $criteria, $current_url) {
	global $request;
	
	switch($whichpage) {
		case "annonces":
			$possibilities = ['id', 'date', 'auteur', 'lieu', 'departement', 'superf_h', 'superf_t', 'price', 'habit', 'time', 'distance', 'note', 'comments'];
			$orderName = 'order';
			$reverseName = 'reverse';
		break;

		case "comments":
			$possibilities = ['date', 'auteur'];
			$orderName = 'orderComments';
			$reverseName = 'reverseComments';
		break;

		default:
			$possibilities = '';
			$orderName = '';
			$reverseName = '';
			echo('<p class="error">Erreur : mauvais paramètre pour print_reverse().</p>');
		break;
	}
	
	$boolArray=['true', 'false'];
	
	if(in_array($current_url[$reverseName], $boolArray) and in_array($current_url[$orderName], $possibilities)) {
		if($current_url[$orderName] == $criteria) {if($current_url[$reverseName] == 'false') return('true'); else return('false');} else return('false');
	}
	
	else echo('<p class="error">Erreur : mauvaise URL empêche la bonne exécution de print_reverse() !</p>');
}

function select_annonce() {
	global $bdd;
	$reponse = $bdd->query('SELECT id, price,'.format_date().', auteur, lieu FROM annonces');
	
	echo('<form accept-charset="utf-8" action="#" method="get"><p>');
	echo('<select name="annonce">');
	
	while($annonces=$reponse->fetch()) {
		echo('<option value="'.$annonces['id'].'">N°'.$annonces['id'].' - par '.$annonces['auteur'].' - le '.$annonces['date'].' - à '.$annonces['lieu'].' - coûtant '.$annonces['price'].' k€</option>');
	}
	$reponse->closeCursor();
	
	echo('</select>');
	echo('<input type="submit" value="Valider" /></p></form>');
}

function print_form_new_annonce($params, $sort_array, $action) {
	echo('
		<form accept-charset="utf-8" action="#form" method="post" name="form" id="form">
			<p name="form" id="form">
			<label for="lieu">Lieu :</label><input type="text" name="lieu" id="lieu" value="'.$params['lieu'].'"/><br />
			<label for="departement">Département :</label><input type="number" min="'.$sort_array['min_departement'].'" max="'.$sort_array['max_departement'].'" name="departement" id="departement" value="'.$params['departement'].'"/><br />
			<label for="superf_h">Superficie bâtie :</label><input type="number" min="'.$sort_array['superf_h'].'" max="'.$sort_array['max_superf_h'].'" name="superf_h" id="superf_h" value="'.$params['superf_h'].'"/> m² (1 si inconnue)<br />
			<label for="superf_t">Superficie du terrain :</label><input type="number" min="'.$sort_array['min_superf_t'].'" max="'.$sort_array['max_superf_t'].'" name="superf_t" id="superf_t" value="'.$params['superf_t'].'"/> m² (1 si inconnue)<br />
			<label for="link">Lien de l\'annonce :</label><input type="text" name="link" id="link" value="'.$params['link'].'"/><br />
			<label for="time">Temps de trajet depuis Lyon :</label><input type="number"  min="'.$sort_array['min_time'].'" max="'.$sort_array['max_time'].'" name="time" id="time" value="'.$params['time'].'"/> minutes<br />
			<label for="distance">Distance de Lyon :</label><input type="number" name="distance" min="'.$sort_array['min_distance'].'" max="'.$sort_array['max_distance'].'" id="distance" value="'.$params['distance'].'"/> km<br />
			<label for="price">Prix :</label><input type="number" min="'.$sort_array['min_price'].'" max="'.$sort_array['max_price'].'" step="0.001" name="price" id="price" value="'.$params['price'].'"/> k€ LOL (ex : 66.666)<br />
			<label for="habit">Combien c\'est habitable en l\'état :</label>
			<select name="habit" id="habit">
				<option value="zero" '.print_selected($params['habit'], 0).'>0</option>
				<option value="un" '.print_selected($params['habit'], 1).'>1</option>
				<option value="deux" '.print_selected($params['habit'], 2).'>2</option>
				<option value="trois" '.print_selected($params['habit'], 3).'>3</option>
				<option value="quatre" '.print_selected($params['habit'], 4).'>4</option>
				<option value="cinq" '.print_selected($params['habit'], 5).'>5</option>
			</select> sur 5<br />
			<label for="note">Ta note pour cette annonce :</label>
			<select name="note" id="note">
				<option value="zero" '.print_selected($params['note'], 0).'>0</option>
				<option value="un" '.print_selected($params['note'], 1).'>1</option>
				<option value="deux" '.print_selected($params['note'], 2).'>2</option>
				<option value="trois" '.print_selected($params['note'], 3).'>3</option>
				<option value="quatre" '.print_selected($params['note'], 4).'>4</option>
				<option value="cinq" '.print_selected($params['note'], 5).'>5</option>
			</select> sur 5<br />');
	
	if($action == 'create') {
		echo('<input type="submit" name="Valider" value="Valider" />');
	}
	
	elseif($action == 'edit') {
		echo('<input type="submit" name="Valider" value="Mettre à jour" />');
	}
	
	echo('</p></form>');
}

function print_selected($n, $p) {
	$possibilities = [0, 1, 2, 3, 4, 5];
	if(in_array($n, $possibilities) && in_array($p, $possibilities) && $n == $p)
		return('selected');
}

function search_error_new_annonce($sort_array, $param_array) {
	if(empty($param_array['lieu']) || empty($param_array['link']) || $param_array['superf_h'] == 0 || $param_array['superf_t'] == 0 ||
			$param_array['time'] == 0 || $param_array['distance'] == 0 || $param_array['price'] == 0 || $param_array['habit'] == -1 || $param_array['note'] == -1)
		echo('<p id="form" class="error">Il faut remplir tous les champs !</p>');
	
	if(!preg_match('#^[A-Z][a-zA-Z- ]+#', $param_array['lieu']))
		echo('<p id="form" class="error">Le lieu ne doit contenir que des lettres, des tirets et des espaces, et doit commencer par une lettre majuscule !</p>');

	if($param_array['departement'] > $sort_array['max_departement'] || $param_array['departement'] < $sort_array['min_departement'])
		echo('<p id="form" class="error">Le département doit être compris entre 1 et 95 !</p>');
	
	if($param_array['superf_h'] > $sort_array['max_superf_h'] || $param_array['superf_h'] < $sort_array['min_superf_h'])
		echo('<p id="form" class="error">La superficie de la maison doit être comprise entre '.$sort_array['min_superf_h'].' et '.$sort_array['max_superf_h'].' !</p>');
	
	if($param_array['superf_t'] > $sort_array['max_superf_t'] || $param_array['superf_t'] < $sort_array['min_superf_t'])
		echo('<p id="form" class="error">La superficie du terrain doit être comprise entre '.$sort_array['min_superf_t'].' et '.$sort_array['max_superf_t'].' !</p>');
	
	if(!preg_match('#^https?://(www.)?[a-zA-Z0-9]+\.[a-z0-9]{1,4}\??#', $param_array['link']))
		echo('<p id="form" class="error">Le lien n\'est pas correct !</p>');
	
	if($param_array['time'] > $sort_array['max_time'] || $param_array['time'] < $sort_array['min_time'])
		echo('<p id="form" class="error">Le temps doit être compris entre '.$sort_array['min_time'].' et '.$sort_array['max_time'].' inclus !</p>');
	
	if($param_array['distance'] > $sort_array['max_distance'] || $param_array['distance'] < $sort_array['min_distance'])
		echo('<p id="form" class="error">La distance doit être comprise entre '.$sort_array['min_distance'].' et '.$sort_array['max_distance'].' inclus !</p>');
	
	if($param_array['price'] > $sort_array['max_price'] || $param_array['price'] < $sort_array['min_price'])
		echo('<p id="form" class="error">Le prix doit être compris entre '.$sort_array['min_price'].' et '.$sort_array['max_price'].' k€ !</p>');
}

function convert_str_nb($h){
	switch($h) {
		case 'zero':
			return 0;
		break;
		
		case 'un':
			return 1;
		break;
		
		case 'deux':
			return 2;
		break;
		
		case 'trois':
			return 3;
		break;
		
		case 'quatre':
			return 4;
		break;
		
		case 'cinq':
			return 5;
		break;
		
		default:
			echo('<p class="error">Toi, t\'es vraiment un petit malin !</p>');
			return -1;
		break;
	}
}

function get_username($user_id) {
	global $bdd;
	
	$get_username = $bdd->query('SELECT user_id, username FROM phpbb_users WHERE user_id = \''.$user_id.'\'');
	
	if($get_username != NULL) {
		$result = $get_username->fetch();
		$get_username->closeCursor();
		return($result['username']);	
	}
	else {$get_username->closeCursor(); return('');}
}

function print_debut_table($sort_columns_array, $other_columns_array, $title, $current_url, $sort_array, $what) {
	$is_first = true;
	$add_superf = true;
	$superf_string_cmp = 'Superficie';
	$superf_string_print = 'Superficies';
	
	switch($what) {
		case 'annonces':
			echo('<h1>'.$title.'</h1><div id="table"><table><tr class="top">');
			
			foreach($sort_columns_array as $column_bdd => $column_name) {
				if($column_bdd == 'superf_h' || $column_bdd == 'superf_t') {
					if($add_superf) {
						echo('<th colspan="2">Superficies</th>');
						$add_superf = false;
					}
				}
				
				else {
					if($is_first) {
						echo('<th class="left" rowspan="2">');
						$is_first = false;
					}
						
					else echo('<th rowspan="2">');
						
					$string_params = '';
						
					foreach($current_url as $label => $value) {
						if($label == 'order') $string_params .= 'order='.$column_bdd.'&amp;';
						elseif($label == 'reverse') $string_params .= 'reverse='.print_reverse('annonces', $column_bdd, $current_url).'&amp;';
						else $string_params .= $label.'='.$value.'&amp;';
					}
						
					foreach($sort_array as $label => $value) {
						$string_params .= $label.'='.$value.'&amp;';
					}
						
					echo('<a href="'.append_sid($current_page, $string_params).'">'.$column_name.'</a></th>');
				}
			}
			
			foreach($other_columns_array as $other_column_name) {				
				echo('<th rowspan="2">'.$other_column_name.'</th>');
			}
			
			echo('</tr>');
			
			$superf_array = ['superf_h' => 'Bâtie', 'superf_t' => 'Terrain'];
		
			echo('<tr>');
		
			foreach($superf_array as $c => $n) {
				$string_params = '';
				
				foreach($current_url as $label => $value) {
					if($label == 'order') $string_params .= 'order='.$c.'&amp;';
					elseif($label == 'reverse') $string_params .= 'reverse='.print_reverse('annonces', $c, $current_url).'&amp;';
					else $string_params .= $label.'='.$value.'&amp;';
				}
		
				foreach($sort_array as $label => $value) {
					$string_params .= $label.'='.$value.'&amp;';
				}
					
				echo('<th><a href="'.append_sid($current_page, $string_params).'">'.$n.'</a></th>');
			}

			echo('</tr>');
		break;
		
		case 'single_annonce':
			echo('<h1 id="comments">'.$title.'</h1><div id="table"><table><tr class="top">');
			
			$have_to_print_superf_string = true;
			
			echo('<tr class="top">');
			
			foreach($other_columns_array as $other_column_name) {
				if($is_first) {
					echo('<th rowspan="2" class="left">'.$other_column_name.'</th>');
					$is_first = false;
				}
				
				elseif(substr_count($other_column_name, $superf_string_cmp) != 0) {
					if($have_to_print_superf_string) {
						echo('<th colspan="2">'.$superf_string_print.'</th>');
						$have_to_print_superf_string = false;
					}
				}
				
				elseif($other_column_name == 'Note') {
					echo('<th colspan="2">Note</th>');
				}
				
				else echo('<th rowspan="2">'.$other_column_name.'</th>');
			}
				
			echo('</tr>');
			
			$superf_array = ['superf_h' => 'Bâtie', 'superf_t' => 'Terrain', 'note' => 'Valeur', 'count' => 'Nombre'];
			
			echo('<tr>');
			
			foreach($superf_array as $c => $n) {
				echo('<th>'.$n.'</th>');
			}
			
			echo('</tr>');
		break;
		
		case 'comments':
			echo('<h1>'.$title.'</h1><div id="table"><table><tr class="top">');
			
			foreach($sort_columns_array as $column_bdd => $column_name) {
				if($is_first) {
					echo('<th class="left">');
					$is_first = false;
				}
		
				else echo('<th>');
		
				$string_params = '';
		
				foreach($current_url as $label => $value) {
					if($label == 'orderComments') $string_params .= 'orderComments='.$column_bdd.'&amp;';
					elseif($label == 'reverseComments') $string_params .= 'reverseComments='.print_reverse('comments', $column_bdd, $current_url).'&amp;';
					else $string_params .= $label.'='.$value.'&amp;';
				}
		
				foreach($sort_array as $label => $value) {
					$string_params .= $label.'='.$value.'&amp;';
				}
				
				$string_params .= '#comments';
		
				echo('<a href="'.append_sid($current_page, $string_params).'">'.$column_name.'</a></th>');
			}
			echo('</tr>');
		break;
		
		case 'other':
			echo('<h1>'.$title.'</h1><div id="table"><table><tr class="top">');
			
			echo('<tr class="top">');
			
			foreach($other_columns_array as $c) {
				if($is_first) {
					echo('<th class="left">'.$c.'</th>');
					$is_first = false;
				}
				else echo('<th>'.$c.'</th>');
			}
			
			echo('</tr>');
		break;
		
		default:
			echo('<p class="error">Mauvais paramètre what dans print_debut_table() : '.$what.'</p>');
		break;
	}
}

function print_statistics($current_page, $current_url, $sort_array, $what) {
	global $bdd;
	
	$other_columns_array = ['Catégorie', 'Min', 'Quartile 1', 'Médiane', 'Quartile 3', 'Max', 'Moyenne'];
	
	print_debut_table([], $other_columns_array, 'Statistiques des annonces', $current_url, $sort_array, 'other');
	
	$rows = ['Prix' => 'price', 'Trajet' => 'time', 'Distance' => 'distance', 'Superficie Bâtie' => 'superf_h', 'Superficie Terrain' => 'superf_t', 'État' => 'habit', 'Note' => 'note'];
	
	foreach($rows as $n => $c) {
		if($c != 'note') {
			$get_stats = $bdd->query('SELECT AVG('.$c.') AS MOY, MIN('.$c.') AS MIN, MAX('.$c.') AS MAX FROM annonces');
			$datas = $get_stats->fetch();
			$moy = $datas['MOY'];
			$min = $datas['MIN'];
			$max = $datas['MAX'];
			
			$get_stats->closeCursor();
			
			$get_values = $bdd->query('SELECT '.$c.' FROM annonces');
			
			$values = [];
			
			while($data = $get_values->fetch()[$c]) {
				array_push($values, $data);
			}
			
			$get_values->closeCursor();
		}
		
		else {
			$get_stats = $bdd->query('SELECT AVG(value) AS MOY, MIN(value) AS MIN, MAX(value) AS MAX FROM notes');
			$datas = $get_stats->fetch();
			$moy = $datas['MOY'];
			$min = $datas['MIN'];
			$max = $datas['MAX'];
			$get_stats->closeCursor();
			
			$get_values = $bdd->query('SELECT value FROM notes');
			
			$values = [];
			
			while($data = $get_values->fetch()['value']) {
				array_push($values, $data);
			}
			
			$get_values->closeCursor();
		}
		
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
	
	echo('</table></div>');
}

function calcul_quartiles($t) {
	sort($t);
	$count = count($t);
	
	$quartiles = [];
	
	for($i = 1; $i <= 3; $i++) {
		$val = floor(($count - 1) * $i / 4);
		
		if(($i % 2 && $count % 4) || (!($i % 2) && $count % 2)) array_push($quartiles, $t[$val]);
		
		else {
			$low = $t[$val];
			$high = $t[$val+1];
			array_push($quartiles, ($low + $high) / 2);
		}
	}
	
	return $quartiles;
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
	
	echo('</table></div>');
}

function print_single_annonce($current_page, $current_url, $sort_array) {
	global $bdd;

	if($current_url['annonce'] != 0) {
		$columns_array = ['Date', 'Auteur', 'Lieu', 'Dpt', 'Superficie bâtie', 'Superficie du terrain', 'État', 'Trajet', 'Distance', 'Prix', 'Note', 'Lien'];
		
		print_debut_table([], $columns_array, 'Description de l\'annonce '.$current_url['annonce'].'', $current_url, $sort_array, 'single_annonce');
			
		$reponse_query = 'SELECT id, '.format_date().', auteur, lieu, departement, superf_h, superf_t, price, link, habit, time, distance, available FROM annonces WHERE id = '.$current_url['annonce'].'';
		$reponse = $bdd->query($reponse_query);
		$donnees = $reponse->fetch();
		
		print_data($donnees, $current_page, $current_url, $sort_array, 'single_annonce');
		
		$reponse->closeCursor();

		echo('</tr></table></div>');
	}
	
	else echo('<p class="error">Pas d\'annonce à afficher dans print_single_annonce() !</p>');
}

function print_user_annonces($current_page, $current_url, $sort_array) {
	global $bdd, $user;
	
	$username = $user->data['username'];
	$what = 'user_annonces';
	
	$columns_array = ['id' => 'N°',
			'date' => 'Date',
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
	
	print_debut_table($columns_array, ['Liens', 'Détails'], 'Liste des annonces de '.$username.'', $current_url, $sort_array, 'annonces');
	
	$initial_query = 'SELECT id, '.format_date().', auteur, lieu, departement, superf_h, superf_t, price, link, habit, time, distance, available FROM annonces WHERE auteur = \''.$username.'\'';
	
	$reponse_query = build_annonce_query($initial_query, false, true, $current_page, $current_url, $sort_array, $what);
	
	sort_print_annonces($reponse_query, $current_page, $current_url, $sort_array, $what);
	
	echo('</table></div>');
}

function build_annonce_query($reponse_query, $have_to_add_WHERE, $have_to_add_AND, $current_page, $current_url, $sort_array, $what) {	
	if($what == 'user_annonces')
		$sort_array_criteria = ['lieu', 'departement'];
	
	elseif($what == 'all_annonces')
		$sort_array_criteria = ['auteur', 'lieu', 'departement'];
	
	foreach($sort_array_criteria as $s) {
		if($sort_array[$s] != 'all') {
			if($have_to_add_WHERE) {
				$reponse_query .= ' WHERE';
				$have_to_add_WHERE = false;
			}
	
			if($have_to_add_AND) $reponse_query .= ' AND';
			else $have_to_add_AND = true;
	
			$reponse_query .= ' '.$s.' = \''.$replaced = str_replace('\'', '\\\'', htmlspecialchars($sort_array[$s])).'\'';
		}
	}
	
	$sort_array_criteria = ['superf_h', 'superf_t', 'habit', 'time', 'distance', 'price'];
	
	foreach($sort_array_criteria as $s) {
		if($sort_array['sort_'.$s.''] == 'inf' && $sort_array['value_'.$s.''] < $sort_array['max_'.$s.'']) {
			if($have_to_add_WHERE) {
				$reponse_query .= ' WHERE';
				$have_to_add_WHERE = false;
			}
	
			if($have_to_add_AND) $reponse_query .= ' AND';
			else $have_to_add_AND = true;
	
			$reponse_query .= ' '.$s.' <= '.$sort_array['value_'.$s].'';
		}
	
		elseif($sort_array['sort_'.$s.''] == 'sup' && $sort_array['value_'.$s.''] > $sort_array['min_'.$s.'']) {
			if($have_to_add_WHERE) {
				$reponse_query .= ' WHERE';
				$have_to_add_WHERE = false;
			}
	
			if($have_to_add_AND) $reponse_query .= ' AND';
			else $have_to_add_AND = true;
	
			$reponse_query .= ' '.$s.' >= '.$sort_array['value_'.$s].'';
		}
	
		elseif($sort_array['sort_'.$s.''] != 'sup' && $sort_array['sort_'.$s.''] != 'inf') echo('<p class="error">Mauvais critère pour : '.$s.'</p>');
	}
	
	if($sort_array['hide_disabled'] == 'true') {
		if($have_to_add_WHERE) {
			$reponse_query .= ' WHERE';
			$have_to_add_WHERE = false;
		}
		
		if($have_to_add_AND) $reponse_query .= ' AND';
		else $have_to_add_AND = true;
		
		$reponse_query .= ' available = 1';
	}
	
	return $reponse_query;
}

function sort_print_annonces($reponse_query, $current_page, $current_url, $sort_array, $what) {
	global $bdd, $user;
	
	switch($current_url['order']) {
		case 'note':
			if($what == 'user_annonces') $list_all_annonces = $bdd->query('SELECT id FROM annonces WHERE auteur = \''.$user->data['username'].'\'');
			elseif($what == 'all_annonces') $list_all_annonces = $bdd->query('SELECT id FROM annonces');
			else echo('<p class="error">Erreur : mauvaise valeur pour $what dans sort_print_annonces()</p>');
			
			$array_notes = [];
			
			while($liste = $list_all_annonces->fetch()) {
				$array_notes[$liste['id']] = get_note($liste['id']);
			}
			
			if($current_url['reverse'] == 'false') uasort($array_notes, 'cmp');
			elseif ($current_url['reverse'] == 'true') uasort($array_notes, 'cmp_reverse');
			else echo('<p class="error">Le paramètre reverse de l\'url cloche.</p>');
			
			foreach($array_notes as $id => $note) {
				$get_annonce = $bdd->query('SELECT id, '.format_date().', auteur, lieu, superf_h, superf_t, price, link, habit, time, distance, departement, available FROM annonces WHERE id = '.$id.'');
			
				$donnees = $get_annonce->fetch();
			
				print_data($donnees, $current_page, $current_url, $sort_array, $what);
				
				$get_annonce->closeCursor();
			}
			
			$list_all_annonces->closeCursor();
		break;
		
		case 'comments':
			if($what == 'user_annonces') $list_all_annonces = $bdd->query('SELECT id FROM annonces WHERE auteur = \''.$user->data['username'].'\'');
			elseif($what == 'all_annonces') $list_all_annonces = $bdd->query('SELECT id FROM annonces');
			else echo('<p class="error">Erreur : mauvaise valeur pour $what dans sort_print_annonces()</p>');
			
			$array_comments = [];
			
			while($list = $list_all_annonces->fetch()) {
				$array_comments[$list['id']] = get_comments($list['id']);
			}
			
			if($current_url['reverse'] == 'false') uasort($array_comments, 'cmp');
			elseif ($current_url['reverse'] == 'true') uasort($array_comments, 'cmp_reverse');
			else echo('<p class="error">Le paramètre reverse de l\'url cloche.</p>');
			
			foreach($array_comments as $id => $comment) {
				$get_annonce = $bdd->query('SELECT id, '.format_date().', auteur, lieu, superf_h, superf_t, price, link, habit, time, distance, departement, available FROM annonces WHERE id = '.$id.'');
				
				$donnees = $get_annonce->fetch();
				
				print_data($donnees, $current_page, $current_url, $sort_array, $what);
				
				$get_annonce->closeCursor();
			}
			
			$list_all_annonces->closeCursor();
		break;
		
		case 'date':
			if($what == 'user_annonces') $list_all_annonces = $bdd->query('SELECT id FROM annonces WHERE auteur = \''.$user->data['username'].'\'');
			elseif($what == 'all_annonces') $list_all_annonces = $bdd->query('SELECT id, '.format_date().' FROM annonces');
			else echo('<p class="error">Erreur : mauvaise valeur pour $what dans sort_print_annonces()</p>');
			
			$array_dates = [];
				
			while($liste = $list_all_annonces->fetch()) {
				$array_dates[$liste['id']] = preg_replace("#^(\d{2})\/(\d{2})\/(\d{4})$#", "$3$2$1", $liste['date']);
			}
			
			if($current_url['reverse'] == 'false') uasort($array_dates, 'cmp');
			elseif ($current_url['reverse'] == 'true') uasort($array_dates, 'cmp_reverse');
			else echo('<p class="error">Le paramètre reverse de l\'url cloche.</p>');
			
			foreach($array_dates as $id => $date) {
				$get_annonce = $bdd->query('SELECT id, '.format_date().', auteur, lieu, superf_h, superf_t, price, link, habit, time, distance, departement, available FROM annonces WHERE id = '.$id.'');
				
				$donnees = $get_annonce->fetch();
				
				print_data($donnees, $current_page, $current_url, $sort_array, $what);
				
				$get_annonce->closeCursor();
			}
				
			$list_all_annonces->closeCursor();
				
		break;
		
		default:
			$reponse_query .= ' ORDER BY '.$current_url['order'].'';
			
			if($current_url['reverse'] == "true") $reponse_query .= ' DESC';
			
			$reponse = $bdd->query($reponse_query);
			
			while($donnees = $reponse->fetch()) {
				$date_sort_compare = preg_replace('#^(\d{2})\/(\d{2})\/(\d{4})$#', '$3$2$1', $sort_array['value_date']);
				$date_donnees_compare = preg_replace('#^(\d{2})\/(\d{2})\/(\d{4})$#', '$3$2$1', $donnees['date']);
			
				if((($sort_array['sort_date'] == 'before' && $date_sort_compare >= $date_donnees_compare) || ($sort_array['sort_date'] == 'after' && $date_sort_compare <= $date_donnees_compare)) &&
						(($sort_array['sort_note'] == 'sup' && $sort_array['value_note'] <= get_note($donnees['id'])) || ($sort_array['sort_note'] == 'inf' && $sort_array['value_note'] >= get_note($donnees['id']))))
					print_data($donnees, $current_page, $current_url, $sort_array, $what);
			}
			
			$reponse->closeCursor();
		break;
	}
}

function print_data($donnees, $current_page, $current_url, $sort_array, $what) {
	global $bdd;
	
	switch($what) {
		case 'all_annonces':
			$minutes = $donnees['time']%60;
			$hours = ($donnees['time'] - $minutes)/60;
			
			if($donnees['available']) echo('<tr>');
			else  echo('<tr class="unavailable">');
			echo('<td class="left">'.$donnees['id'].'</td>');
			echo('<td>'.$donnees['date'].'</td>');
			echo('<td>'.$donnees['auteur'].'</td>');
			echo('<td>'.$donnees['lieu'].'</td>');
			echo('<td>'.$donnees['departement'].'</td>');
			
			if($donnees['superf_h'] != 1)
				echo('<td>'.$donnees['superf_h'].'</td>');
			else
				echo('<td class="unknown">Inconnue</td>');
			
			if($donnees['superf_t'] != 1)
				echo('<td>'.$donnees['superf_t'].'</td>');
			else
				echo('<td class="unknown">Inconnue</td>');
			
			echo('<td class="habit'.floor($donnees['habit']).'">'.$donnees['habit'].'</td>');
			if($hours == 0) echo('<td>'.$minutes.' min</td>');
			elseif($minutes < 10) echo('<td>'.$hours.'h0'.$minutes.'</td>');
			else echo('<td>'.$hours.'h'.$minutes.'</td>');
			
			echo('<td>'.$donnees['distance'].'</td>');
			
			echo('<td>'.$donnees['price'].' k€</td>');
			
			$note = get_note($donnees['id']);
			if($note == 10) echo('<td class="unnoted"> - </td>');
			else echo('<td class="habit'.floor($note).'">'.$note.'</td>');
			
			echo('<td>'.get_comments($donnees['id']).'</td>');
			
			echo('<td><a href="'.$donnees['link'].'" target="_blank">Annonce</a></td>');
				
			$string_params = 'annonce='.$donnees['id'].'&amp;comments=true&amp;';
				
			foreach($current_url as $label => $value) {
				if($label != 'annonce' && $label != 'comments') $string_params .= $label.'='.$value.'&amp;';
			}
				
			foreach($sort_array as $label => $value) {
				$string_params .= $label.'='.$value.'&amp;';
			}
			
			$string_params .= '#comments';
				
			echo('<td><a href="'.append_sid($current_page, $string_params).'">Détails</a></td></tr>');
		break;
		
		case 'single_annonce':
			$minutes = $donnees['time']%60;
			$hours = ($donnees['time'] - $minutes)/60;
			
			if($donnees['available']) echo('<tr>');
			else  echo('<tr class="unavailable">');
			
			echo('<td class="left">'.$donnees['date'].'</td>');
			
			echo('<td>'.$donnees['auteur'].'</td>');
			
			echo('<td>'.$donnees['lieu'].'</td>');
			
			echo('<td>'.$donnees['departement'].'</td>');
			
			echo('<td>'.$donnees['superf_h'].'</td>');
			
			echo('<td>'.$donnees['superf_t'].'</td>');
			
			echo('<td class="habit'.floor($donnees['habit']).'">'.$donnees['habit'].'</td>');
			
			if($hours == 0) echo('<td>'.$minutes.' min</td>');
			elseif($minutes < 10) echo('<td>'.$hours.'h0'.$minutes.'</td>');
			else echo('<td>'.$hours.'h'.$minutes.'</td>');
			
			echo('<td>'.$donnees['distance'].'</td>');
			
			echo('<td>'.$donnees['price'].' k€</td>');
			
			$note = get_note($donnees['id']);
			if($note == 10) echo('<td class="unnoted"> - </td>');
			else echo('<td class="habit'.floor($note).'">'.$note.'</td>');
			
			if($note != 10) {
				$get_notes_count = $bdd->query('SELECT COUNT(value) AS COUNT FROM notes WHERE annonce = '.$donnees['id'].'');
				$count = $get_notes_count->fetch()['COUNT'];
				echo('<td>'.$count.'</td>');
			}
			
			else echo('<td>0</td>');
			
			echo('<td><a href="'.$donnees['link'].'" target="_blank">Annonce</a></td>');
		break;
		
		case 'user_annonces':
			$minutes = $donnees['time']%60;
			$hours = ($donnees['time'] - $minutes)/60;
			
			if($donnees['available']) echo('<tr>');
			else echo('<tr class="unavailable">');
			
			echo('<td class="left">'.$donnees['id'].'</td>');
			
			echo('<td>'.$donnees['date'].'</td>');
			
			echo('<td>'.$donnees['lieu'].'</td>');
			
			echo('<td>'.$donnees['departement'].'</td>');
			
			echo('<td>'.$donnees['superf_h'].'</td>');
			
			echo('<td>'.$donnees['superf_t'].'</td>');
			
			echo('<td class="habit'.floor($donnees['habit']).'">'.$donnees['habit'].'</td>');
			
			if($hours == 0) echo('<td>'.$minutes.' min</td>');
			elseif($minutes < 10) echo('<td>'.$hours.'h0'.$minutes.'</td>');
			else echo('<td>'.$hours.'h'.$minutes.'</td>');
			
			echo('<td>'.$donnees['distance'].'</td>');
			
			echo('<td>'.$donnees['price'].' k€</td>');
			
			$note = get_note($donnees['id']);
			if($note == 10) echo('<td class="unnoted"> - </td>');
			else echo('<td class="habit'.floor($note).'">'.$note.'</td>');
			
			echo('<td>'.get_comments($donnees['id']).'</td>');
			
			echo('<td><a href="'.$donnees['link'].'" target="_blank">Annonce</a></td>');
			
			$string_params = 'annonce='.$donnees['id'].'&amp;comments=true&amp;';
			
			foreach($current_url as $label => $value) {
				if($label != 'annonce' && $label != 'comments') $string_params .= $label.'='.$value.'&amp;';
			}
				
			foreach($sort_array as $label => $value) {
				$string_params .= $label.'='.$value.'&amp;';
			}
			
			$string_params .= '#comments';
				
			echo('<td><a href="'.append_sid($current_page, $string_params).'">Détails</a></td></tr>');
		break;
		
		default:
			echo('<p class="error">Mauvais paramètre what dans print_data().</p>');
		break;
	}
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
	print_available($current_url['annonce']);
	
	if(is_auteur($user->data['username'], $current_url['annonce'])) print_modify_annonce($current_url['annonce']);
	
	if($donnees != NULL) {
		$columns_array = ['date' => 'Date', 'auteur' => 'Auteur'];
		
		print_debut_table($columns_array, [], 'Liste des commentaires de l\'annonce '.$current_url['annonce'].'', $current_url, $sort_array, 'comments');
		echo('</tr></table></div>');
		
		echo('<form accept-charset="utf-8" action="new_comment.php?annonce='.$current_url['annonce'].'" method="post"><p>');
		echo('<input type="submit" name="new_comment" value="Nouveau commentaire" />');
		echo('</p></form>');
		
		echo('<h3 id="comments">Commentaire numéro '.$donnees['id'].'</h3>');
		echo('<p id="description">écrit par '.$donnees['auteur'].' le '.$donnees['date'].'</p>');
		echo('<p id="content">'.$donnees['comment'].'</p>');
		
		while($donnees = $reponse->fetch()) {
			echo('<h3>Commentaire numéro '.$donnees['id'].'</h3>');
			echo('<p id="description">écrit par '.$donnees['auteur'].' le '.$donnees['date'].'</p>');
			echo('<p id="content">'.$donnees['comment'].'</p>');
		}
	}
	
	else {
		echo('<h1 id="comments">Pas de commentaire pour cette annonce !</h1>');
		
		echo('<form accept-charset="utf-8" action="new_comment.php?annonce='.$current_url['annonce'].'" method="post"><p>');
		echo('<input type="submit" name="new_comment" value="Nouveau commentaire" />');
		echo('</p></form>');
	}
	$reponse->closeCursor();
}

function is_auteur($username, $id) {
	global $bdd;
	
	$get_annonce = $bdd->prepare('SELECT id, auteur FROM annonces WHERE id = :id');
	$get_annonce->execute(array('id' => $id));
	$auteur = $get_annonce->fetch()['auteur'];
	
	return $auteur == $username;
}

function print_sort_form($current_page, $current_url, $sort_array) {
	global $user, $bdd;
	
	$inf_sup_array = ['sup' => 'Supérieur à', 'inf' => 'Inférieur à'];
	
	echo('<form accept-charset="utf-8" action="#" method="get" id="form_sort_annonce">');
	echo('<p><label for="sort_date">Date</label><select id="sort_date" name="sort_date">');
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
	
	if($current_page == 'annonces.php') {
		echo('<label for="sort_auteur">Auteur</label>');
		echo('<select id="sort_auteur" name="sort_auteur">');
		print_liste('auteur');
		echo('</select>');
	}
	
	echo('<label for="sort_lieu">Lieu</label>');
	echo('<select id="sort_lieu" name="sort_lieu">');
	print_liste('lieu');
	echo('</select>');
	
	echo('<label for="sort_departement">Département</label>');
	echo('<select id="sort_departement" name="sort_departement">');
	print_liste('departement');
	echo('</select>');
	
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
	
	echo('<label for="print_disabled">Cacher les indisponibles</label><input type="checkbox" name="hide_disabled" id="hide_disabled" value="true" '.print_checked_enabled_only($sort_array).' /><br />');
	
	echo('<input type="submit" name="sort" id="sort" value="Valider" /></p>');
	echo('</form>');
}

function print_checked_enabled_only($sort_array) {
	if($sort_array['hide_disabled'] == 'true') {
		return('checked="checked"');
	}
}

function print_option_select($option_array, $name, $label, $min, $max, $step) {
	global $sort_array;
	
	echo('<label for="sort_'.$name.'">'.$label.'</label>');
	echo('<select id="sort_'.$name.'" name="sort_'.$name.'">');
	
	foreach($option_array as $value => $option_label) {
		echo('<option value="'.$value.'"');
		if(isset($_GET['value_date']) && $sort_array['sort_'.$name.''] == $value) echo ' selected';
		echo('>'.$option_label.'</option>');
	}
	
	echo('</select>');
	echo('<input type="range" name="value_'.$name.'" id="value_'.$name.'" min="'.$min.'" max="'.$max.'" step="'.$step.'" value="');
	if(isset($_GET['value_date'])) echo($sort_array['value_'.$name]);
	else echo($min);
	echo('" oninput="print_value_'.$name.'.value = value_'.$name.'.value;" />');
	
	echo('<output name="print_value_'.$name.'" id="print_value_'.$name.'">');
	if(isset($_GET['value_date'])) echo($sort_array['value_'.$name]);
	else echo($min);
	echo('</output>');
}

function print_liste($what) {
	global $bdd, $sort_array;
	
	$possibilities = ['auteur', 'lieu', 'departement'];
	
	if(in_array($what, $possibilities)) {
		$list = $bdd->query('SELECT '.$what.' FROM annonces ORDER BY '.$what.'');
		$array = [];
		
		echo('<option value="all">Tous</option>');
		
		while($rep = $list->fetch()) {
			if(!in_array($rep[$what], $array)) {
				array_push($array, $rep[$what]);
				echo('<option value="'.$rep[$what].'"');
				if(isset($_GET['value_date']) && $sort_array[$what] == $rep[$what]) echo ' selected';
				echo('>'.$rep[$what].'</option>');
			}
		}
		
		$list->closeCursor();
	}
	
	else echo('<p class="error">la fonction print_list($what) a été appelée avec un mauvais paramètre</p>');
}

function get_user_note($annonce, $username) {
	global $bdd;
	
	$int_annonce = intval($annonce);
	
	$get_values = $bdd->prepare('SELECT * FROM notes WHERE annonce = :id AND auteur = :username');
	$get_values->execute(array('id' => $int_annonce, 'username' => $username));
	
	if($get_values) {
		$note = $get_values->fetch()['value'];
		$get_values->closeCursor();
		
		return $note;
	}
	
	else {
		echo('<p class="error">No note found for annonce '.$int_annonce.' and user '.$username.'</p>');
		return -1;
	}
}

function get_note($annonce) {
	global $bdd;
	
	$int_annonce = intval($annonce);
	
	$get_values = $bdd->prepare('SELECT * FROM notes WHERE annonce = :id');
	$get_values->execute(array('id' => $int_annonce));
	
	$values_array = [];
	
	if($get_values) {
		while($value = $get_values->fetch()) {
			array_push($values_array, $value['value']);
		}

		$get_values->closeCursor();
	}

	else {
		echo('<p class="error">Invalid annonce value in get_note()</p>'); 
		return -1;
	}
	
	if(!empty($values_array)) return round(array_sum($values_array) / count($values_array), 2);
	else return 10;
}

function get_comments($annonce) {
	global $bdd;
	
	$int_annonce = intval($annonce);
	
	$get_comments = $bdd->query('SELECT * FROM comments WHERE annonce = '.$int_annonce.'');
	$nb_comments = 0;
	
	if($get_comments) {
		while($comments = $get_comments->fetch()) {
			$nb_comments = $nb_comments + 1;
		}
	
		$get_comments->closeCursor();
	}
	
	else {
		echo('<p class="error">Invalid annonce value in get_comments()</p>');
		return -1;
	}
	
	return $nb_comments;
}

function print_available($annonce) {
	global $bdd, $user;
	
	$get_available = $bdd->query('SELECT id, auteur, available FROM annonces WHERE id = '.$annonce.'');
	$avail = $get_available->fetch();
	
	if($avail) {
		if($avail['available']) {
			if(!isset($_POST['unavailable'])) {
				echo('<form accept-charset="utf-8" action="#comments" method="post"><p>');
				echo('<input type="submit" value="Déclarer indisponible" name="unavailable"/>');
				echo('</p></form>');
			}
			
			else {
				$set_unavailable = $bdd->exec('UPDATE annonces SET available = 0 WHERE id = '.$annonce.'');
				echo('<p class="success">Vous avez déclaré l\'annonce '.$annonce.' comme étant indisponible. Rechargez la page pour voir le changement.</p>');
			}
		}
		
		else {
			echo('<p>Cette annonce est déclarée indisponible.</p>');
			
			if($avail['auteur'] == $user->data['username']) {
				if(!isset($_POST['available'])) {
					if(!isset($_POST['delete'])) {
						echo('<form accept-charset="utf-8" action="#comments" method="post"><p>');
						echo('<input type="submit" value="Déclarer disponible" name="available"/>&emsp;');
						echo('<input type="submit" value="Supprimer l\'annonce" name="delete"/>');
						echo('</p><form>');
					}
					
					else {
						$delete_annonce = $bdd->exec('DELETE FROM annonces WHERE id='.$annonce.'');
						$delete_comments = $bdd->exec('DELETE FROM comments WHERE annonce='.$annonce.'');
						$delete_notes = $bdd->exec('DELETE FROM notes WHERE annonce='.$annonce.'');
						
						echo('<p class="success">Vous avez supprimé l\'annonce '.$annonce.'. Rechargez la page pour voir le changement.</p>');
					}
				}
				
				else {
					$set_available = $bdd->exec('UPDATE annonces SET available = 1 WHERE id = '.$annonce.'');
					echo('<p class="success">Vous avez déclaré l\'annonce '.$annonce.' comme étant disponible. Rechargez la page pour voir le changement.</p>');
				}
			}
		}
		
		$get_available->closeCursor();
	}
	
	else echo('<p class="error">Erreur, impossible de savoir si l\'annonce est disponible</p>');
}

function print_notation($annonce) {
	global $bdd, $user, $request;
	
	$get_notes = $bdd->query('SELECT * FROM notes WHERE annonce = '.$annonce.' AND auteur = \''.$user->data['username'].'\'');
	$user_note = -1;

	$notes = $get_notes->fetch();
	
	if($get_notes && !empty($notes)) {
		$user_note = $notes['value'];
		
		if(!isset($_POST['modify_note'])) {
			echo('<form accept-charset="utf-8" action="#comments" method="post"><p>Vous avez voté '.$user_note.'/5 pour cette annonce. ');
			echo('<input type="submit" value="Modifier ce vote" name="modify_note" />');
			echo('</p></form>');
		}
		
		else {
			$delete_note = $bdd->exec('DELETE FROM notes WHERE annonce = '.$annonce.' AND auteur = \''.$user->data['username'].'\'');
			echo('<p class="success">Rechargez la page pour pouvoir voter. C\'est comme les antibiotiques, c\'est pas automatique.</p>');
		}
		
		$get_notes->closeCursor();
	}
	
	else {
		if(!isset($_POST['value_note_submit'])) {
			echo('<form accept-charset="utf-8" action="#comments" method="post"><p>');
			echo('<label for="note_option">Note : </label>');
			echo('<select id="note_option" name="value_note_submit">');
			echo('<option value="zero">0</option>');
			echo('<option value="un">1</option>');
			echo('<option value="deux">2</option>');
			echo('<option value="trois">3</option>');
			echo('<option value="quatre">4</option>');
			echo('<option value="cinq">5</option>');
			echo('</select>');
			echo('<input type="submit" value="Voter" />');
			echo('</p></form>');
		}
		
		else {
			$value_note = convert_str_nb($request->variable('value_note_submit', 'zero'));
			
			if($value_note != -1 && $value_note <= 5 && $user->data['is_registered']) {
				$insert = $bdd->prepare('INSERT INTO notes(auteur, annonce, value) VALUES(:auteur, :annonce, :value)');
				$insert->execute(array('auteur' => $user->data['username'], 'annonce' => $annonce, 'value' => $value_note));
				
				echo('<p class="success">Vous avez voté '.$value_note.'/5 pour cette annonce !</p>');
				echo('<p class="success">Rechargez la page pour voir le résultat. C\'est comme les antibiotiques, c\'est pas automatique.</p>');
			}
			
			else echo('<p class="error">La valeur de la note n\'est pas bonne ou vous n\'êtes pas enregistré !</p>');
		}
	}
}

function print_modify_annonce($id) {
	echo('<form action="'.append_sid('new_annonce.php', 'action=edit&annonce='.$id.'').'" method="post"><p>');
	echo('<input type="submit" value="Modifier l\'annonce" />');
	echo('</p></form>');
}

function get_new_annonce_param_array($sort_array) {
	global $request;
	
	$lieu = $request->variable('lieu', '');
	$superf_h = $request->variable('superf_h', $sort_array['min_superf_h']);
	$superf_t = $request->variable('superf_t', $sort_array['min_superf_t']);
	$link = $request->variable('link', '');
	$habit = $request->variable('habit', '');
	$time = $request->variable('time', $sort_array['min_time']);
	$distance = $request->variable('distance', $sort_array['min_distance']);
	$price = (float)$request->variable('price', $sort_array['min_price']);
	$departement = $request->variable('departement', $sort_array['min_departement']);
	$note = $request->variable('note', '');
	
	$habit = convert_str_nb($habit);
	$note = convert_str_nb($note);
	
	if($lieu < 0) $lieu = 0;
	if($superf_h < 0) $superf_h = 0;
	if($superf_t < 0) $superf_t = 0;
	if($time < 0) $time = 0;
	if($distance < 0) $distance = 0;
	if($price < 0.0) $price = 0.0;
	if($depart < 0) $departement = 0;
	
	$param_array = ['lieu' => $lieu,
			'superf_h' => $superf_h,
			'superf_t' => $superf_t,
			'link' => $link,
			'habit' => $habit,
			'time' => $time,
			'distance' => $distance,
			'price' => $price,
			'departement' => $departement,
			'note' => $note];
	
	return $param_array;
}

function verif_form_new_annonce($sort_array, $param_array) {
	$print_form = false;
	
	if(!(preg_match('#^https?://(www.)?[a-zA-Z0-9]+\.[a-z0-9]{1,4}\??#', $param_array['link']) &&
		preg_match('#^[A-Z][a-zA-Z- ]+#', $param_array['lieu'])			&&
		$param_array['time']		<= $sort_array['max_time']			&&		$param_array['time'] 		>= $sort_array['min_time']			&&
		$param_array['distance']	<= $sort_array['max_distance']		&&		$param_array['distance'] 	>= $sort_array['min_distance']		&&
		$param_array['superf_h']	<= $sort_array['max_superf_h']		&&		$param_array['superf_h'] 	>= $sort_array['min_superf_h']		&&
		$param_array['superf_t']	<= $sort_array['max_superf_t']		&&		$param_array['superf_t'] 	>= $sort_array['min_superf_t']		&&
		$param_array['price']		<= $sort_array['max_price']			&&		$param_array['price'] 		>= $sort_array['min_price']			&&
		$param_array['departement']	<= $sort_array['max_departement']	&&		$param_array['departement'] >= $sort_array['min_departement']	&&
		$param_array['superf_h']	!= 0								&&		$param_array['superf_t'] 	!= 0								&&
		$param_array['time']		!= 0								&&		$param_array['price']		!= 0								&&
		$param_array['distance']	!= 0								&&		$param_array['habit']		!= -1								&&
		!empty($param_array['lieu'])									&&		!empty($param_array['link'])									&&
		$param_array['note'] != -1)) {
				$print_form = true;
				search_error_new_annonce($sort_array, $param_array);
			}
			
	return $print_form;
}

function cmp($a, $b) {
	if ($a == $b) {
		return 0;
	}
	return ($a < $b) ? -1 : 1;
}

function cmp_reverse($a, $b) {
	if ($a == $b) {
		return 0;
	}
	return ($a > $b) ? -1 : 1;
}
?>
