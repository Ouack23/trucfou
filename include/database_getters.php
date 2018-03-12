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
	$answers = $bdd->query('SELECT id, annonce, '.format_date().', auteur, comment FROM comments WHERE annonce = '.$annonce.'');
	$comments = array();
	if(!$answers)
	{
		return "zob";
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
	else return 0;
}

function get_note_count($annonce) {
    global $bdd;
    
    $int_annonce = intval($annonce);
    
    $get_values = $bdd->prepare('SELECT COUNT(*) AS NB FROM notes WHERE annonce = :id');
    $get_values->execute(array('id' => $int_annonce));
    
    if($get_values) {
        $result = $get_values->fetch()["NB"];
        $get_values->closeCursor();
    }
    
    else {
        echo('<p class="error">Invalid annonce value in get_note_count()</p>');
        return -1;
    }
    
    if($result > 0) return $result;
    else return 10;
}

function is_auteur($username, $id) {
	global $bdd;
	
	$get_annonce = $bdd->prepare('SELECT id, auteur FROM annonces WHERE id = :id');
	$get_annonce->execute(array('id' => $id));
	$auteur = $get_annonce->fetch()['auteur'];
	$get_annonce->closeCursor();
	
	return $auteur == $username;
}

function annonces_query($username="") {
    global $bdd, $user;
    
    if(empty($username)) $username = $user->data['username'];
    $annonces_initial_query = 'SELECT id, '.format_date().', auteur, lieu, superf_h, superf_t, price, link, habit, time, distance, departement, available FROM annonces';
    $annonces_reponse_query = $bdd->query($annonces_initial_query);
    
    $annonces = [];
    $i = 0;
    $query_size = 13;
    
    while($annonce = $annonces_reponse_query->fetch()) {
        for($j = 0; $j < $query_size; $j++) {
            unset($annonce[$j]);
        }
        
        $annonce["note"] = get_note($annonce["id"]);
        $annonce["note_count"] = get_note_count($annonce["id"]);
        $annonce["user_note"] = get_user_note($annonce["id"], $username);
        $annonce["comments"] = get_number_of_comments($annonce["id"]);
        //$annonce["details"] = append_sid("details.php", "id=".$annonce["id"]."");
        $annonces[$i] = $annonce;
        $i++;
    }
    
    $annonces_reponse_query->closeCursor();
    
    return $annonces;
}
?>