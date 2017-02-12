<?php

	define('IN_PHPBB', true);
	$phpbb_root_path =(defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './forum/';
	$phpEx = substr(strrchr(__FILE__, '.'), 1);
	include($phpbb_root_path . 'common.' . $phpEx);
	global $user, $auth, $phpbb_root_path, $phpEx;

	include_once("../config.php");
	include_once("../database_getters.php");

	$annonce_id = $_REQUEST["id"];
	$note = get_note($annonce_id);
	$comments = get_comments($annonce_id);
	$available = get_available($annonce_id);

	echo json_encode(array(	"id" => $annonce_id,
						 	"note" => $note,
						 	"available" => $available, 
						 	"comments" => $comments
						 ));
?>