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
	global $phpbb_root_path, $trucFou_path, $phpEx, $db, $config, $user, $auth, $cache, $template, $request, $session;
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
			echo('Erreur dans l\'argument de la fonction includeContent');
		break;
	}
}

function secure_get() {
	global $current_url_reverse, $current_url_order, $current_url_annonce, $request;
	$current_url_reverse = $request->variable('reverse', '');
	$current_url_order = $request->variable('orderBy', '');
	$current_url_annonce = $request->variable('annonce', '');
}

function print_reverse($whichpage, $criteria) {
	global $request, $current_url_reverse, $current_url_order;
	
	switch($whichpage) {
		case "annonces":
			$possibilities = ['id', 'date', 'auteur', 'lieu', 'superf_h', 'superf_t', 'price', 'habit', 'time'];
		break;

		case "comments":
			$possibilities = ['date', 'auteur'];
		break;

		default:
			$possibilities = '';
			echo'Erreur';
		break;
	}
	
	$current_url_reverse = $request->variable('reverse', 'false');
	$current_url_order = $request->variable('orderBy', 'date');
	$boolArray=['true', 'false'];
	
	if(in_array($current_url_reverse, $boolArray) and in_array($current_url_order, $possibilities)) {
		if($current_url_order==$criteria) {if($current_url_reverse == 'false') return('true'); else return('false');} else return('false');
	}
	
	else {echo('Erreur');}
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
?>
