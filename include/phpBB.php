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

	
function secure_get() {
	global $current_url, $sort_array, $request;
	
	$current_url['reverse'] = $request->variable('reverse', 'false');
	$current_url['order'] = $request->variable('order', 'id');
	$current_url['orderComments'] = $request->variable('orderComments', 'date');
	$current_url['reverseComments'] = $request->variable('reverseComments', 'false');
	$current_url['annonce'] = $request->variable('annonce', 0);
	$current_url['user'] = $request->variable('user', 0);
	$current_url['comments'] = $request->variable('comments', 'false');
	
	$sort_array['max_superf_h'] = 65535;
	$sort_array['max_superf_t'] = 65535;
	$sort_array['max_habit'] = 5;
	$sort_array['max_time'] = 255;
	$sort_array['max_distance'] = 1000;
	$sort_array['max_price'] = 100;
	$sort_array['max_departement'] = 95;
	$sort_array['max_note'] = 5;
	
	$sort_array['min_superf_h'] = 1;
	$sort_array['min_superf_t'] = 1;
	$sort_array['min_habit'] = 0;
	$sort_array['min_time'] = 1;
	$sort_array['min_distance'] = 0;
	$sort_array['min_price'] = 0.0;
	$sort_array['min_departement'] = 1;
	$sort_array['min_note'] = 0;
	
	$sort_array['auteur'] = $request->variable('sort_auteur', 'all');
	$sort_array['lieu'] = $request->variable('sort_lieu', 'all');
	$sort_array['departement'] = $request->variable('sort_departement', 'all');
	
	$sort_array['sort_date'] = $request->variable('sort_date', 'after');
	$sort_array['sort_superf_h'] = $request->variable('sort_superf_h', 'sup');
	$sort_array['sort_superf_t'] = $request->variable('sort_superf_t', 'sup');
	$sort_array['sort_habit'] = $request->variable('sort_habit', 'sup');
	$sort_array['sort_time'] = $request->variable('sort_time', 'sup');
	$sort_array['sort_distance'] = $request->variable('sort_distance', 'sup');
	$sort_array['sort_price'] = $request->variable('sort_price', 'sup');
	$sort_array['sort_note'] = $request->variable('sort_note', 'sup');
	
	$sort_array['value_date'] = $request->variable('value_date', '01/01/2016');
	$sort_array['value_superf_h'] = $request->variable('value_superf_h', $sort_array['min_superf_h']);
	$sort_array['value_superf_t'] = $request->variable('value_superf_t', $sort_array['min_superf_t']);
	$sort_array['value_habit'] = $request->variable('value_habit', $sort_array['min_habit']);
	$sort_array['value_time'] = $request->variable('value_time', $sort_array['min_time']);
	$sort_array['value_distance'] = $request->variable('value_distance', $sort_array['min_distance']);
	$sort_array['value_price'] = $request->variable('value_price', $sort_array['min_price']);
	$sort_array['value_note'] = $request->variable('value_note', $sort_array['min_note']);
	
	$sort_array['hide_disabled'] = $request->variable('hide_disabled', 'false');
}
?>