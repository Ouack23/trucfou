<?php

function add_header() {
	global $phpbb_root_path, $phpEx, $config, $user, $auth, $cache, $template, $request, $session;
	include_once('header.php');
	include_once('menu.php');
}

function add_footer() {
	global $phpbb_root_path, $phpEx, $config, $user, $auth, $cache, $template, $request, $session;
	include_once('footer.php');
}

?>