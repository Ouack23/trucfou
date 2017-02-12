<?php
//	include_once("phpBB.php");
	define('IN_PHPBB', true);
	$phpbb_root_path =(defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './forum/';
	$phpEx = substr(strrchr(__FILE__, '.'), 1);
	include($phpbb_root_path . 'common.' . $phpEx);
	global $user, $auth, $phpbb_root_path, $phpEx;

	include_once("config.php");
	include_once("database_getters.php");

	$q = $_REQUEST["q"];
	$note = get_note($q);

	echo json_encode(array("id" => $q, "note" => $note));
?>