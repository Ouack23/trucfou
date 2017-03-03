<?php
	include_once("../config.php");
	include_once("../database_getters.php");

	$annonce_id = $_REQUEST["id"];
	$username = $_REQUEST["username"];
	$note = get_user_note($annonce_id, $username);
	$comments = get_comments($annonce_id);
	$available = get_available($annonce_id);

	echo json_encode(array(	"id" => $annonce_id,
						 	"note" => $note,
						 	"available" => $available, 
						 	"comments" => $comments
						 ));
?>