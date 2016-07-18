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
			echo('Erreur dans l\'argument de la fonction include_content');
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
	
	$sort_array['auteur'] = $request->variable('sort_auteur', 'all');
	$sort_array['lieu'] = $request->variable('sort_lieu', 'all');
	
	$sort_array['sort_date'] = $request->variable('sort_date', 'after');
	$sort_array['sort_superf_h'] = $request->variable('sort_superf_h', 'sup');
	$sort_array['sort_superf_t'] = $request->variable('sort_superf_t', 'sup');
	$sort_array['sort_habit'] = $request->variable('sort_habit', 'sup');
	$sort_array['sort_time'] = $request->variable('sort_time', 'sup');
	$sort_array['sort_price'] = $request->variable('sort_price', 'sup');
	$sort_array['sort_depart'] = $request->variable('sort_depart', 'sup');
	$sort_array['sort_note'] = $request->variable('sort_note', 'sup');
	
	$sort_array['value_date'] = $request->variable('value_date', '01/01/2016');
	$sort_array['value_superf_h'] = $request->variable('value_superf_h', 0);
	$sort_array['value_superf_t'] = $request->variable('value_superf_t', 0);
	$sort_array['value_habit'] = $request->variable('value_habit', 1);
	$sort_array['value_time'] = $request->variable('value_time', 0);
	$sort_array['value_price'] = $request->variable('value_price', 0);
	$sort_array['value_depart'] = $request->variable('value_depart', 0);
	$sort_array['value_note'] = $request->variable('value_note', 0);
}

function print_reverse($whichpage, $criteria, $current_url) {
	global $request, $current_url;
	
	switch($whichpage) {
		case "annonces":
			$possibilities = ['id', 'date', 'auteur', 'lieu', 'departement', 'superf_h', 'superf_t', 'price', 'habit', 'time'];
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
			echo'Erreur';
		break;
	}
	
	$boolArray=['true', 'false'];
	
	if(in_array($current_url[$reverseName], $boolArray) and in_array($current_url[$orderName], $possibilities)) {
		if($current_url[$orderName] == $criteria) {if($current_url[$reverseName] == 'false') return('true'); else return('false');} else return('false');
	}
	
	else {echo('<p class="error">Erreur : mauvaise URL empêche la bonne exécution de print_reverse() !</p>');}
}

function select_annonce() {
	global $bdd;
	$reponse = $bdd->query('SELECT id, price,'.format_date().', auteur, lieu FROM annonces');
	
	echo('<form action="#" method="get">');
	echo('<select name="annonce">');
	
	while($annonces=$reponse->fetch()) {
		echo('<option value="'.$annonces['id'].'">N°'.$annonces['id'].' - par '.$annonces['auteur'].' - le '.$annonces['date'].' - à '.$annonces['lieu'].' - coûtant '.$annonces['price'].' k€</option>');
	}
	$reponse->closeCursor();
	
	echo('</select>');
	echo('<input type="submit" value="Valider" /></form>');
}

function print_form_new_annonce($params) {
	echo('
		<form action="#form" method="post" name="new_announce" id="new_announce">
			<p name="form" id="form"><label for="lieu">Lieu :</label><input type="text" name="lieu" id="lieu" value="'.$params['lieu'].'"/><br />
			<label for="superf_h">Superficie intérieure :</label><input type="text" name="superf_h" id="superf_h" value="'.$params['superf_h'].'"/> m²<br />
			<label for="superf_t">Superficie du terrain :</label><input type="text" name="superf_t" id="superf_t" value="'.$params['superf_t'].'"/> m²<br />
			<label for="link">Lien de l\'annonce :</label><input type="text" name="link" id="link" value="'.$params['link'].'"/><br />
			<label for="time">Temps de trajet depuis Lyon :</label><input type="text" name="time" id="time" value="'.$params['time'].'"/> minutes<br />
			<label for="price">Prix :</label><input type="text" name="price" id="price" value="'.$params['price'].'"/> k€ LOL (ex : 66.666)<br />
			<label for="habit">Combien c\'est habitable en l\'état :</label>
			<select name="habit" id="habit">
				<option value="un" '.print_selected($params['habit'], 1).'>1</option>
				<option value="deux" '.print_selected($params['habit'], 2).'>2</option>
				<option value="trois" '.print_selected($params['habit'], 3).'>3</option>
				<option value="quatre" '.print_selected($params['habit'], 4).'>4</option>
				<option value="cinq" '.print_selected($params['habit'], 5).'>5</option>
			</select> sur 5<br />
			<input type="submit" name="Valider" value="Valider" /></p>
		</form>');
}

function print_selected($n, $p) {
	$possibilities = [1, 2, 3, 4, 5];
	if(in_array($n, $possibilities) && in_array($p, $possibilities) && $n == $p)
		return('selected');
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

function print_debut_table($sort_columns_array, $other_columns_array, $title, $current_url, $sort_array, $isAnnonce) {
	echo('<h1>'.$title.'</h1><div id="table"><table><tr class="top">');
	$isFirst = true;
	
	foreach($sort_columns_array as $column_bdd => $column_name) {
		if($isFirst) {
			echo('<td class="left">');
			$isFirst = false;
		}
		else echo('<td>');
		
		$string_params = '';
		
		foreach($current_url as $label => $value) {
			if($label == 'order') $string_params .= 'order='.$column_bdd.'&amp;';
			elseif($label == 'reverse' && $isAnnonce) $string_params .= 'reverse='.print_reverse('annonces', $column_bdd, $current_url).'&amp;';
			elseif($label == 'reverseComments' && !$isAnnonce) $string_params .= 'reverseComments='.print_reverse('annonces', $column_bdd, $current_url).'&amp;';
			else $string_params .= $label.'='.$value.'&amp;';
		}
		
		foreach($sort_array as $label => $value) {
			$string_params .= $label.'='.$value.'&amp;';
		}
		
		echo('<a href="'.append_sid($current_page, $string_params).'">'.$column_name.'</a></td>');
	}
	
	foreach($other_columns_array as $other_column_name) {
		if($isFirst) {
			echo('<td class="left">');
			$isFirst = false;
		}
		else echo('<td>');
		
		echo($other_column_name.'</td>');
	}
	
	echo('</tr>');
}

function print_all_annonces($current_page, $current_url, $sort_array, $isSorted) {
	global $bdd;
	
	$columns_array = ['id' => 'N°',
			'date' => 'Date',
			'auteur' => 'Auteur',
			'lieu' => 'Lieu',
			'departement' => 'Département',
			'superf_h' => 'Superficie habitable',
			'superf_t' => 'Superficie du terrain',
			'habit' => 'État',
			'time' => 'Temps de trajet',
			'price' => 'Prix',
			'note' => 'Note'];
	
	print_debut_table($columns_array, ['Lien', 'Commentaires & Détails'], 'Liste des Annonces', $current_url, $sort_array, true);
	
	$reponse_query = 'SELECT id, '.format_date().', auteur, lieu, superf_h, superf_t, price, link, habit, time, departement FROM annonces';
	
	if($isSorted) {
		//TODO CHECK COMPATIBILITY WITH EVERY CASE (nottamment tout default sauf date.)
		$reponse_query .= ' WHERE';
		$have_to_add_AND = true;
		
		if($sort_array['auteur'] == 'all' && $sort_array['lieu'] == 'all') $have_to_add_AND = false;
		
		if($sort_array['auteur'] != 'all') $reponse_query .= ' auteur = \''.$sort_array['auteur'].'\'';
		else {if($sort_array['lieu'] != 'all') $reponse_query .= ' AND';}
		
		if($sort_array['lieu'] != 'all') $reponse_query .= ' lieu = \''.$sort_array['lieu'].'\'';
		
		$sort_array_criteria=['superf_h', 'superf_t', 'habit', 'time', 'price'];
		
		foreach($sort_array_criteria as $s) {
			if($have_to_add_AND) $reponse_query .= ' AND';
			if($sort_array['sort_'.$s.''] == 'inf') $reponse_query .= ' '.$s.' <= '.$sort_array['value_'.$s].'';
			elseif($sort_array['sort_'.$s.''] == 'sup') $reponse_query .= ' '.$s.' >= '.$sort_array['value_'.$s].'';
			else echo('<p class="error">Mauvais critère pour : '.$s.'</p>');
			$have_to_add_AND = true;
		}
	}
	
	//TODO ORDER DOES NOT WORK WITH NOTE
	$reponse_query .= ' ORDER BY '.$current_url['order'].'';
	
	if($current_url['reverse'] == "true" || ($current_url['order'] == 'id' && !isset($_GET['value_date']))) $reponse_query .= ' DESC';
	
	$reponse = $bdd->query($reponse_query);
	
	while($donnees = $reponse->fetch()) {
		if($isSorted) {
			$date_sort_compare = preg_replace('#^(\d{2})\/(\d{2})\/(\d{4})$#', '$3$2$1', $sort_array['value_date']);
			$date_donnees_compare = preg_replace('#^(\d{2})\/(\d{2})\/(\d{4})$#', '$3$2$1', $donnees['date']);
			
			if(($sort_array['sort_date'] == 'before' && $date_sort_compare >= $date_donnees_compare) || ($sort_array['sort_date'] == 'after' && $date_sort_compare <= $date_donnees_compare)) {
				$minutes = $donnees['time']%60;
				$hours = ($donnees['time'] - $minutes)/60;
					
				echo('<tr><td class="left">'.$donnees['id'].'</td>');
				echo('<td>'.$donnees['date'].'</td>');
				echo('<td>'.$donnees['auteur'].'</td>');
				echo('<td>'.$donnees['lieu'].'</td>');
				echo('<td>'.$donnees['departement'].'</td>');
				echo('<td>'.$donnees['superf_h'].'</td>');
				echo('<td>'.$donnees['superf_t'].'</td>');
				echo('<td class="habit'.$donnees['habit'].'">'.$donnees['habit'].'</td>');
				if($minutes < 10) echo('<td>'.$hours.'h0'.$minutes.'</td>');
				else echo('<td>'.$hours.'h'.$minutes.'</td>');
				echo('<td>'.$donnees['price'].' k€</td>');
				echo('<td>'.get_note($donnees['id']).'</td>');
				echo('<td><a href="'.$donnees['link'].'">Annonce</a></td>');
				
				$string_params = 'annonce='.$donnees['id'].'&amp;comments=true&amp;';
				
				foreach($current_url as $label => $value) {
					if($label != 'annonce' && $label != 'comments') $string_params .= $label.'='.$value.'&amp;';
				}
				
				foreach($sort_array as $label => $value) {
					$string_params .= $label.'='.$value.'&amp;';
				}
				
				echo('<td><a href="'.append_sid($current_page, $string_params).'">Commentaires</a></td></tr>');
			}
		}
		
		else {
			$minutes = $donnees['time']%60;
			$hours = ($donnees['time'] - $minutes)/60;
			
			echo('<tr><td class="left">'.$donnees['id'].'</td>');
			echo('<td>'.$donnees['date'].'</td>');
			echo('<td>'.$donnees['auteur'].'</td>');
			echo('<td>'.$donnees['lieu'].'</td>');
			echo('<td>'.$donnees['departement'].'</td>');
			echo('<td>'.$donnees['superf_h'].'</td>');
			echo('<td>'.$donnees['superf_t'].'</td>');
			echo('<td class="habit'.$donnees['habit'].'">'.$donnees['habit'].'</td>');
			if($minutes < 10) echo('<td>'.$hours.'h0'.$minutes.'</td>');
			else echo('<td>'.$hours.'h'.$minutes.'</td>');
			echo('<td>'.$donnees['price'].' k€</td>');
			$note = get_note($donnees['id']);
			echo('<td class="habit'.$note.'">'.$note.'</td>');
			echo('<td><a href="'.$donnees['link'].'">Annonce</a></td>');
			echo('<td><a href="'.append_sid($current_page, 'annonce='.$donnees['id'].'&amp;comments=true').'">Commentaires</a></td></tr>');
		}
	}
	$reponse->closeCursor();
	
	echo('</table></div>');
}

function print_single_annonce($current_page, $current_url, $sort_array) {
	global $bdd;

	if($current_url['annonce'] != 0) {
		$columns_array = ['Date', 'Auteur', 'Lieu', 'Département', 'Superficie habitable', 'Superficie du terrain', 'État', 'Temps de trajet', 'Prix', 'Note', 'Lien'];
		
		print_debut_table([], $columns_array, 'Description de l\'annonce '.$current_url['annonce'].'', $current_url, $sort_array, true);
			
		$reponse_query = 'SELECT id, '.format_date().', auteur, lieu, departement, superf_h, superf_t, price, link, habit, time FROM annonces WHERE id = '.$current_url['annonce'].'';
		$reponse = $bdd->query($reponse_query);
		$donnees = $reponse->fetch();

		$minutes = $donnees['time']%60;
		$hours = ($donnees['time'] - $minutes)/60;

		echo('<tr><td class="left">'.$donnees['date'].'</td>');
		echo('<td>'.$donnees['auteur'].'</td>');
		echo('<td>'.$donnees['lieu'].'</td>');
		echo('<td>'.$donnees['departement'].'</td>');
		echo('<td>'.$donnees['superf_h'].'</td>');
		echo('<td>'.$donnees['superf_t'].'</td>');
		echo('<td class="habit'.$donnees['habit'].'">'.$donnees['habit'].'</td>');
		if($minutes < 10) echo('<td>'.$hours.'h0'.$minutes.'</td>');
		else echo('<td>'.$hours.'h'.$minutes.'</td>');
		echo('<td>'.$donnees['price'].' k€</td>');
		echo('<td>'.get_note($donnees['id']).'</td>');
		echo('<td><a href="'.$donnees['link'].'">Annonce</a></td>');

		$reponse->closeCursor();

		echo('</tr></table></div>');
	}
	
	else echo('<p class="error">Pas d\'annonce à afficher dans print_single_annonce() !</p>');
}

function print_user_annonces($current_page, $current_url, $sort_array) {
	global $bdd;
	
	$get_username_result = get_username($current_url['user']);
	
	if (!empty($get_username_result) && $current_url['comments'] != 'false') {
		$columns_array = ['id' => 'N°',
				'date' => 'Date',
				'auteur' => 'Auteur',
				'lieu' => 'Lieu',
				'superf_h' => 'Superficie habitable',
				'superf_t' => 'Superficie du terrain',
				'habit' => 'État',
				'time' => 'Temps de trajet',
				'price' => 'Prix'];
	
		print_debut_table($columns_array, ['Liens', 'Commentaires'], 'Liste des annonces de '.$get_username_result.'', $current_url, true);
	
		$reponse_query = 'SELECT id, '.format_date().', auteur, lieu, superf_h, superf_t, price, link, habit, time FROM annonces WHERE auteur = \''.$get_username_result.'\' ORDER BY '.$current_url['order'].'';
	
		if($current_url['reverse'] == 'true')
			$reponse_query .= ' DESC';
	
			$reponse = $bdd->query($reponse_query);
	
			while($donnees = $reponse->fetch()) {
				$minutes = $donnees['time']%60;
				$hours = ($donnees['time'] - $minutes)/60;
	
				echo('<tr><td class="left">'.$donnees['id'].'</td>');
				echo('<td>'.$donnees['date'].'</td>');
				echo('<td>'.$donnees['auteur'].'</td>');
				echo('<td>'.$donnees['lieu'].'</td>');
				echo('<td>'.$donnees['superf_h'].'</td>');
				echo('<td>'.$donnees['superf_t'].'</td>');
				echo('<td class="habit'.$donnees['habit'].'">'.$donnees['habit'].'</td>');
				if($minutes < 10) echo('<td>'.$hours.'h0'.$minutes.'</td>');
				else echo('<td>'.$hours.'h'.$minutes.'</td>');
				echo('<td>'.$donnees['price'].' k€</td>');
				echo('<td><a href="'.$donnees['link'].'">Annonce</a></td>');
				echo('<td><a href="'.append_sid($current_page, 'annonce='.$donnees['id'].'&amp;user='.$current_url['user'].'&amp;comments='.$current_url['comments'].'').'">Commentaires</a></td></tr>');
			}
			$reponse->closeCursor();
				
			echo('</table></div>');
	} else echo('<p class="error">Erreur dans content_annonce() : comments ou get_username_result n\'est pas défini. Dis le à Belette !</p>');
}

function print_comments_annonce($current_page, $current_url, $sort_array) {
	global $bdd;
	
	$reponse_query = 'SELECT id, annonce, '.format_date().', auteur, comment FROM comments WHERE annonce = \''.$current_url['annonce'].'\' ORDER BY '.$current_url['orderComments'].'';
	
	if($current_url['reverse'] == 'true')
		$reponse_query .= ' DESC';
			
	$reponse = $bdd->query($reponse_query);
	$donnees = $reponse->fetch();
	
	if($donnees != NULL) {
		print_single_annonce($current_page, $current_url, $sort_array);
		
		print_notation($current_url['annonce']);
		
		$columns_array = ['date' => 'Date', 'auteur' => 'Auteur'];
		
		print_debut_table($columns_array, [], 'Liste des commentaires de l\'annonce '.$current_url['annonce'].'', $current_url, $sort_array, false);
		echo('</tr></table></div>');
		
		echo('<h3>Commentaire numéro '.$donnees['id'].'</h3>');
		echo('<p id="description">écrit par '.$donnees['auteur'].' le '.$donnees['date'].'</p>');
		echo('<p id="content">'.$donnees['comment'].'</p>');
		
		while($donnees = $reponse->fetch()) {
			echo('<h3>Commentaire numéro '.$donnees['id'].'</h3>');
			echo('<p id="description">écrit par '.$donnees['auteur'].' le '.$donnees['date'].'</p>');
			echo('<p id="content">'.$donnees['comment'].'</p>');
		}
	}
	
	else echo('<h1>Pas de commentaire pour cette annonce !</h1>');
	$reponse->closeCursor();
}

function print_sort_form($current_page, $current_url, $sort_array) {
	global $user, $bdd;
	
	$inf_sup_array = ['sup' => 'Supérieur à', 'inf' => 'Inférieur à'];
	
	echo('<form action="#" method="get" id="form_sort_annonce">');
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
	
	echo('<label for="sort_auteur">Auteur</label>');
	echo('<select id="sort_auteur" name="sort_auteur">');
	print_liste('auteur');
	echo('</select>');
	
	echo('<label for="sort_lieu">Lieu</label>');
	echo('<select id="sort_lieu" name="sort_lieu">');
	print_liste('lieu');
	echo('</select>');
	
	echo('<label for="sort_depart">Département</label>');
	echo('<select id="sort_depart" name="sort_depart">');
	print_liste('departement');
	echo('</select><br />');
	
	print_option_select($inf_sup_array, 'superf_h', 'Superficie habitable', 0, 65500, 50);
	print_option_select($inf_sup_array, 'superf_t', 'Superficie du terrain', 0, 65500, 50);
	echo('<br />');
	print_option_select($inf_sup_array, 'habit', 'État', 1, 5, 1);
	print_option_select($inf_sup_array, 'time', 'Temps de trajet', 0, 250, 10);
	print_option_select($inf_sup_array, 'price', 'Prix', 0, 999, 10);
	echo('<br />');
	print_option_select($inf_sup_array, 'note', 'Note', -25, 25, 1);
	
	//TODO VERIF NEED THIS
	//echo('<input type="hidden" name="sid" value="'.$user->session_id.'" />');
	
	echo('<input type="submit" name="sort" id="sort" value="Valider" /></p>');
	echo('</form>');
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

function get_note($annonce) {
	global $bdd, $user, $request;
	
	$get_notes = $bdd->query('SELECT * FROM notes WHERE annonce = '.$annonce.'');
	$notes_array = [];
	
	while($notes = $get_notes->fetch()) {
		array_push($notes_array, $notes['value']);
	}
	
	if(!empty($notes_array)) return array_sum($notes_array) / count($notes_array);
	else return 0;
}

function print_notation($annonce) {
	global $bdd, $user, $request;
	
	$get_notes = $bdd->query('SELECT * FROM notes WHERE annonce = '.$annonce.'');
	$user_note = -1;
	
	if($get_notes != false) {
		while($notes = $get_notes->fetch()) {
			if($notes['auteur'] == $user->data['username'] && $user_note == -1) $user_note = $notes['value'];
		}
		
		if($user_note != -1) echo('<p>Vous avez voté '.$user_note.'/5 pour cette annonce.</p>');
	}
	
	else echo('<p>Pas encore de note pour cette annonce.</p>');
	
	if($user_note == -1) {
		if(!isset($_POST['value_note_submit'])) {
			echo('<form action="#" method="post"><p>');
			echo('<label for="note_option">Note :</label>');
			echo('<select id="note_option" name="value_note_submit">');
			echo('<option value="zero">0</option>');
			echo('<option value="un">1</option>');
			echo('<option value="deux">2</option>');
			echo('<option value="trois">3</option>');
			echo('<option value="quatre">4</option>');
			echo('<option value="cinq">5</option>');
			echo('</select>');
			echo('<input type="submit"value="Voter" />');
			echo('</p></form>');
		}
		
		elseif(isset($_POST['value_note_submit'])) {
			$value_note = convert_str_nb($request->variable('value_note_submit', 'zero'));
			
			if($value_note != -1 && $value_note <= 5 && $user->data['is_registered']) {
				$insert = $bdd->prepare('INSERT INTO notes(auteur, annonce, value) VALUES(:auteur, :annonce, :value)');
				$insert->execute(array('auteur' => $user->data['username'], 'annonce' => $annonce, 'value' => $value_note));
				
				echo('<p class="success">Vous avez voté '.$value_note.'/5 pour cette annonce !</p>');
			}
			
			else echo('<p class="error">La valeur de la note n\'est pas bonne ou vous n\'êtes pas enregistré !</p>');
		}
	}
}
?>
