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
	return 'DATE_FORMAT(date, "%e/%c/%Y") AS date';
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
	global $current_url, $request;
	
	$current_url['reverse'] = $request->variable('reverse', 'false');
	$current_url['order'] = $request->variable('order', 'date');
	$current_url['orderComments'] = $request->variable('orderComments', 'date');
	$current_url['reverseComments'] = $request->variable('reverseComments', 'false');
	$current_url['annonce'] = $request->variable('annonce', 0);
	$current_url['user'] = $request->variable('user', 0);
	$current_url['comments'] = $request->variable('comments', 'false');
	
	if($current_url['comments'] != 'true' && $current_url['comments'] != 'false') {
		$current_url['comments'] = 'false';
	}
}

function print_reverse($whichpage, $criteria, $current_url) {
	global $request, $current_url;
	
	switch($whichpage) {
		case "annonces":
			$possibilities = ['id', 'date', 'auteur', 'lieu', 'superf_h', 'superf_t', 'price', 'habit', 'time'];
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
			<input type="submit" name="Valider" value="Valider" /><p>
		</form>');
}

function print_selected($n, $p) {
	$possibilities = [1, 2, 3, 4, 5];
	if(in_array($n, $possibilities) && in_array($p, $possibilities) && $n == $p)
		return('selected="'.$n.'"');
}

function convert_habit($h){
	switch($h) {
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
			return 0;
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

function print_debut_table($sort_columns_array, $other_columns_array, $title, $current_url, $isAnnonce) {
	echo('<h1>'.$title.'</h1><div id="table"><table><tr class="top">');
	$isFirst = true;
	
	foreach($sort_columns_array as $column_bdd => $column_name) {
		if($isFirst) {
			echo('<td class="left">');
			$isFirst = false;
		}
		else echo('<td>');
		
		if($isAnnonce) echo('<a href="'.append_sid($current_page, 'order='.$column_bdd.'&amp;
																reverse='.print_reverse('annonces', $column_bdd, $current_url).'&amp;
																reverseComments='.$current_url['reverseComments'].'&amp;
																orderComments='.$current_url['orderComments'].'&amp;
																user='.$current_url['user'].'&amp;
																comments='.$current_url['comments'].'&amp;
																annonce='.$current_url['annonce'].'').'">'.$column_name.'</a></td>');
		
		else echo('<a href="'.append_sid($current_page, 'order='.$current_url['order'].'&amp;
														reverse='.$current_url['reverse'].'&amp;
														reverseComments='.print_reverse('comments', $column_bdd, $current_url).'&amp;
														orderComments='.$column_bdd.'&amp;
														user='.$current_url['user'].'&amp;
														comments='.$current_url['comments'].'&amp;
														annonce='.$current_url['annonce'].'').'">'.$column_name.'</a></td>');
	}
	
	foreach($other_columns_array as $other_column_name) {
		if($isFirst) echo('<td class="left">');
		else echo('<td>');
		echo($other_column_name.'</td>');
	}
	
	echo('</tr>');
}

function print_all_annonces($current_page, $current_url) {
	global $bdd;
	
	$columns_array = ['id' => 'N°',
			'date' => 'Date',
			'auteur' => 'Auteur',
			'lieu' => 'Lieu',
			'superf_h' => 'Superficie habitable',
			'superf_t' => 'Superficie du terrain',
			'habit' => 'État',
			'time' => 'Temps de trajet',
			'price' => 'Prix'];
	
	print_debut_table($columns_array, ['Lien', 'Commentaires'], 'Liste des Annonces', $current_url, true);
	
	$reponse_query = 'SELECT id, '.format_date().', auteur, lieu, superf_h, superf_t, price, link, habit, time FROM annonces ORDER BY '.$current_url['order'].'';
	
	if($current_url['reverse'] == "true") $reponse_query .= ' DESC';
	
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
		echo('<td><a href="'.append_sid($current_page, 'annonce='.$donnees['id'].'&amp;comments=true').'">Commentaires</a></td></tr>');
	}
	$reponse->closeCursor();
	
	echo('</table></div>');
}

function print_single_annonce($current_page, $current_url) {
	global $bdd;

	if($current_url['annonce'] != 0) {
		$columns_array = ['N°', 'Date', 'Auteur', 'Lieu', 'Superficie habitable', 'Superficie du terrain', 'État', 'Temps de trajet', 'Prix', 'Lien'];
		
		print_debut_table([], $columns_array, 'Description de l\'annonce '.$current_url['annonce'].'', $current_url, true);
			
		$reponse_query = 'SELECT id, '.format_date().', auteur, lieu, superf_h, superf_t, price, link, habit, time FROM annonces WHERE id = '.$current_url['annonce'].'';
		$reponse = $bdd->query($reponse_query);
		$donnees = $reponse->fetch();

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
		echo('<td><a href='.$donnees['link'].'>Annonce</a></td>');

		$reponse->closeCursor();

		echo('</table></div>');
	}
}

function print_user_annonces($current_page, $current_url) {
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

function print_comments_annonce($current_page, $current_url) {
	global $bdd;
	
	$reponse_query = 'SELECT id, annonce, '.format_date().', auteur, comment FROM comments WHERE annonce = \''.$current_url['annonce'].'\' ORDER BY '.$current_url['orderComments'].'';
	
	if($current_url['reverse'] == 'true')
		$reponse_query .= ' DESC';
			
	$reponse = $bdd->query($reponse_query);
	$donnees = $reponse->fetch();
	
	if($donnees != NULL) {
		print_single_annonce($current_page, $current_url);
		
		$columns_array = ['date' => 'Date', 'auteur' => 'Auteur'];
		
		print_debut_table($columns_array, [], 'Liste des commentaires de l\'annonce '.$current_url['annonce'].'', $current_url, false);
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

function print_sort_form($current_page, $current_url) {
	global $bdd;
	$list_date_query = $bdd->query('SELECT * FROM annonces WHERE date = ');
	
	echo('<form action="#" method="post" id="form_sort_annonce">');
	echo('<p><select name="sort_date">');
	echo('<option value="before">Avant</option>');
	echo('<option value="after">Après</option>');
	echo('</select>');
	echo('<input type="text" name="value_date" id="datepicker" />');
	
	echo('<label for="sort_auteur">Auteur : </label>');
	echo('<select name="sort_auteur">');
	echo('</select>');
	
	echo('<label for="sort_lieu">Lieu : </lieu>');
	echo('<select name="sort_lieu">');
	echo('</select>');
	
	echo('<label for="sort_superf_h">Superficie habitable : </label>');
	echo('<select name="sort_superf_h">');
	echo('<option value="sup">Supérieure à</option>');
	echo('<option value="inf">Inférieure à</option>');
	echo('</select>');
	echo('<input type="range" name="value_superf_h" id="value_superf_h" min="0" max="65535" step="50" value="0" oninput="print_value_superf_h.value = value_superf_h.value;" />');
	echo('<output name="print_value_superf_h" id="print_value_superf_h">0</output>');
	
	echo('<label for="sort_superf_t">Superficie du terrain : </label>');
	echo('<select name="sort_superf_t">');
	echo('<option value="sup">Supérieure à</option>');
	echo('<option value="inf">Inférieure à</option>');
	echo('</select>');
	echo('<input type="range" name="value_superf_t" id="value_superf_t" min="0" max="65535" step="50" value="0" oninput="print_value_superf_t.value = value_superf_t.value;" />');
	echo('<output name="print_value_superf_t" id="print_value_superf_t">0</output>');
	
	echo('<label for="sort_habit">État : </label>');
	echo('<select name="sort_habit">');
	echo('<option value="sup">Plus de</option>');
	echo('<option value="inf">Moins de</option>');
	echo('</select>');
	echo('<input type="range" name="value_habit" id="value_habit" min="1" max="5" step="1" value="1" oninput="print_value_habit.value = value_habit.value;" />');
	echo('<output name="print_value_habit" id="print_value_habit">1</output>');
	
	echo('<label for="sort_time">Temps de trajet : </label>');
	echo('<select name="sort_time">');
	echo('<option value="inf">Inférieur à</option>');
	echo('<option value="sup">Supérieur à</option>');
	echo('</select>');
	echo('<input type="range" name="value_time" id="value_super_t" min="0" max="255" step="10" value="0" oninput="print_value_time.value = value_time.value;" />');
	echo('<output name="print_value_time" id="print_value_time">0</output>');
	
	echo('<label for="sort_price">Prix</label>');
	echo('<select name="sort_price">');
	echo('<option value="inf">Inférieur à</option>');
	echo('<option value="sup">Supérieur à</option>');
	echo('</select>');
	echo('<input type="range" name="value_price" id="value_price" min="0" max="999" step="10" value="0" oninput="print_value_price.value = value_price.value;" />');
	echo('<output name="print_value_price" id="print_value_price">0</output>');
	
	echo('<input type="submit" value="Valider" /></p>');
	echo('</form>');
}
?>
