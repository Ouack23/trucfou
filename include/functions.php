<?php

include_once("database_getters.php");
include_once("phpBB.php");
include_once("utils.php");

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
		if($current_url[$orderName] == $criteria) {
			if($current_url[$reverseName] == 'false')
			 	return('true');
			 else 
			 	return('false');
		}
		else
			return('false');
	}
	
	else echo('<p class="error">Erreur : mauvaise URL empêche la bonne exécution de print_reverse() !</p>');
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
					else
						echo('<th rowspan="2">');

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
		
		default:
			echo('<p class="error">Mauvais paramètre what dans print_annonce_debut_table() : '.$what.'</p>');
		break;
	}
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
			elseif($what == 'all_annonces') $list_all_annonces = $bdd->query($reponse_query);
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
			elseif($what == 'all_annonces') $list_all_annonces = $bdd->query($reponse_query);
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
			elseif($what == 'all_annonces') $list_all_annonces = $bdd->query($reponse_query);
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

function print_checked_enabled_only($sort_array) {
	if($sort_array['hide_disabled'] == 'true') {
		return('checked="checked"');
	}
}

function print_option_select($option_array, $name, $label, $min, $max, $step) {
	global $sort_array;
	
	echo('<label for="sort_'.$name.'">'.$label.'</label>');
	echo('<span class="select-wrapper"><select id="sort_'.$name.'" name="sort_'.$name.'">');
	
	foreach($option_array as $value => $option_label) {
		echo('<option value="'.$value.'"');
		if(isset($_GET['value_date']) && $sort_array['sort_'.$name.''] == $value) echo ' selected';
		echo('>'.$option_label.'</option>');
	}
	
	echo('</select></span>');
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
				echo('<form accept-charset="utf-8" action="#comments" method="post"><p class="center">');
				echo('<input type="submit" class="warning-button" value="Déclarer indisponible" name="unavailable"/>');
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
			echo('
				<form accept-charset="utf-8" class="offer-vote" action="#comments" method="post">
					<p>Vous avez voté '.$user_note.'/5 pour cette annonce.
						<input type="submit" class="submit-button" id="change-note" value="Modifier ce vote" name="modify_note" />
					</p>
				</form>
			');
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
			echo('<span class="select-wrapper"><select id="note_option" name="value_note_submit">');
			echo('<option value="zero">0</option>');
			echo('<option value="un">1</option>');
			echo('<option value="deux">2</option>');
			echo('<option value="trois">3</option>');
			echo('<option value="quatre">4</option>');
			echo('<option value="cinq">5</option>');
			echo('</select></span>');
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
	echo('<form action="'.append_sid('new_annonce.php', 'action=edit&annonce='.$id.'').'" method="post"><p class="center">');
	echo('<input type="submit" value="Modifier l\'annonce" />');
	echo('</p></form>');
}

?>
