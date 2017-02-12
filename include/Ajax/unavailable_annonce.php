<?php
	define('IN_PHPBB', true);
	$phpbb_root_path =(defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './forum/';
	$phpEx = substr(strrchr(__FILE__, '.'), 1);
	include($phpbb_root_path . 'common.' . $phpEx);
	global $user, $auth, $phpbb_root_path, $phpEx;

	include_once("config.php");

	$annonce = $_REQUEST["id"];
	$set_unavailable = $bdd->exec('UPDATE annonces SET available = 0 WHERE id = '.$annonce.'');

	echo $set_unavailable;
?>