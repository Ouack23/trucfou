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

function format_Date() {
	return 'DATE_FORMAT(date, "%e/%c/%Y") AS date';
}

function include_Content($where) {
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

function secureGet() {
	global $current_url_reverse, $current_url_order, $current_url_annonce, $request;
	$current_url_reverse = $request->variable('reverse', '');
	$current_url_order = $request->variable('orderBy', '');
	$current_url_annonce = $request->variable('annonce', '');
}

function printReverse($whichpage, $criteria) {
	global $request, $current_url_reverse, $current_url_order;
	
	switch($whichpage) {
		case "annonces":
			$possibilities = ['id', 'date', 'auteur', 'lieu', 'superf_h', 'superf_t', 'price'];
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
		if($current_url_order==$criteria) {if($current_url_reverse == 'false') echo('true'); else echo('false');} else echo('false');
	}
	
	else {echo('Erreur');}
}

function select_annonce() {
	global $bdd;
	$reponse = $bdd->query('SELECT id, price,'.format_Date().', auteur, lieu FROM annonces');
	
	echo('<form action="#" method="post">');
	echo('<select name="annonce">');
	
	while($annonces=$reponse->fetch()) {
		echo('<option value="'.$annonces['id'].'">N°'.$annonces['id'].' - par '.$annonces['auteur'].' - le '.$annonces['date'].' - à '.$annonces['lieu'].' - coûtant '.$annonces['price'].'€</option>');
	}
	$reponse->closeCursor();
	echo('</select>');
	echo('<input type="submit" value="Valider" /></form>');
}
?>
