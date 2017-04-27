<?php

include_once("utils.php");

function get_available($annonce) {
	global $bdd;

	$answer = $bdd->query('SELECT available FROM annonces WHERE id = '.$annonce.'');
	if(!$answer)
	{
		return false;
	}
	$available = $answer->fetch();
	$answer->closeCursor();

	return $available["available"];
}

function get_comments($annonce) {
	global $bdd;

	$query = 'SELECT id, annonce, '.format_date().', auteur, comment FROM comments WHERE annonce = '.$annonce.'';
	$answers = $bdd->query($query);
	$comments = array();
	if(!$answer)
	{
		return $comments;
	}

	$i = 0;

	while( $comment = $answers->fetch()) {
		$comments[$i] = $comment;
		$i++;
	}

	$answers->closeCursor();

	return $comments;
}

function get_number_of_comments($annonce) {
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
		echo('<p class="error">Invalid annonce value in get_number_of_comments()</p>');
		return -1;
	}
	
	return $nb_comments;
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

function is_auteur($username, $id) {
	global $bdd;
	
	$get_annonce = $bdd->prepare('SELECT id, auteur FROM annonces WHERE id = :id');
	$get_annonce->execute(array('id' => $id));
	$auteur = $get_annonce->fetch()['auteur'];
	
	return $auteur == $username;
}

?>