<?php
include_once("utils.php");

function print_form_new_offer($params, $sort_array, $action) {
	echo('<div class="flex-container">
			<div class="posting-form">
				<form accept-charset="utf-8" action="#form" method="post" name="form" id="form">
					<p class="form">
					<label for="lieu">Lieu :</label><input type="text" name="lieu" id="lieu" value="'.$params['lieu'].'"/><br />
					<label for="departement">Département :</label><input type="number" min="'.$sort_array['min_departement'].'" max="'.$sort_array['max_departement'].'" name="departement" id="departement" value="'.$params['departement'].'"/><br />
					<label for="superf_h">Superficie bâtie (en m², 1 si inconnue) :</label><input type="number" min="'.$sort_array['superf_h'].'" max="'.$sort_array['max_superf_h'].'" name="superf_h" id="superf_h" value="'.$params['superf_h'].'"/><br />
					<label for="superf_t">Superficie du terrain (en m², 1 si inconnue) :</label><input type="number" min="'.$sort_array['min_superf_t'].'" max="'.$sort_array['max_superf_t'].'" name="superf_t" id="superf_t" value="'.$params['superf_t'].'"/><br />
					<label for="link">Lien de l\'annonce :</label><input type="text" name="link" id="link" value="'.$params['link'].'"/><br />
					<label for="time">Temps de trajet depuis Lyon (en mn) :</label><input type="number"  min="'.$sort_array['min_time'].'" max="'.$sort_array['max_time'].'" name="time" id="time" value="'.$params['time'].'"/><br />
					<label for="distance">Distance de Lyon (en km) :</label><input type="number" name="distance" min="'.$sort_array['min_distance'].'" max="'.$sort_array['max_distance'].'" id="distance" value="'.$params['distance'].'"/><br />
					<label for="price">Prix (en k€, genre 66,666) :</label><input type="number" min="'.$sort_array['min_price'].'" max="'.$sort_array['max_price'].'" step="0.001" name="price" id="price" value="'.$params['price'].'"/><br />
					<label for="habit">Combien c\'est habitable en l\'état :</label>
					<span class="select-wrapper"><select name="habit" id="habit">
						<option value="zero" '.print_selected($params['habit'], 0).'>0</option>
						<option value="un" '.print_selected($params['habit'], 1).'>1</option>
						<option value="deux" '.print_selected($params['habit'], 2).'>2</option>
						<option value="trois" '.print_selected($params['habit'], 3).'>3</option>
						<option value="quatre" '.print_selected($params['habit'], 4).'>4</option>
						<option value="cinq" '.print_selected($params['habit'], 5).'>5</option>
					</select></span><br />
					<label for="note">Ta note pour cette annonce :</label>
					<span class="select-wrapper"><select name="note" id="note">
						<option value="zero" '.print_selected($params['note'], 0).'>0</option>
						<option value="un" '.print_selected($params['note'], 1).'>1</option>
						<option value="deux" '.print_selected($params['note'], 2).'>2</option>
						<option value="trois" '.print_selected($params['note'], 3).'>3</option>
						<option value="quatre" '.print_selected($params['note'], 4).'>4</option>
						<option value="cinq" '.print_selected($params['note'], 5).'>5</option>
					</select></span><br />

					<span class="submit-container"><input type="submit" name="Valider" value="'. ($action == 'edit' ? 'Mettre à jour' : 'Valider') .'" /></span>

					</p>
				</form>
			</div>
		</div>'
	);
}

function verif_form_new_annonce($sort_array, $param_array) {
	$print_form = false;
	
	if(!(preg_match('#^https?://(www.)?[a-zA-Z0-9]+\.[a-z0-9]{1,4}\??#', $param_array['link']) &&
		preg_match('#^[A-Z][a-zA-Z- ]+#', $param_array['lieu'])			&&
		$param_array['time']		<= $sort_array['max_time']			&&		$param_array['time'] 		>= $sort_array['min_time']			&&
		$param_array['distance']	<= $sort_array['max_distance']		&&		$param_array['distance'] 	>= $sort_array['min_distance']		&&
		$param_array['superf_h']	<= $sort_array['max_superf_h']		&&		$param_array['superf_h'] 	>= $sort_array['min_superf_h']		&&
		$param_array['superf_t']	<= $sort_array['max_superf_t']		&&		$param_array['superf_t'] 	>= $sort_array['min_superf_t']		&&
		$param_array['price']		<= $sort_array['max_price']			&&		$param_array['price'] 		>= $sort_array['min_price']			&&
		$param_array['departement']	<= $sort_array['max_departement']	&&		$param_array['departement'] >= $sort_array['min_departement']	&&
		$param_array['superf_h']	!= 0								&&		$param_array['superf_t'] 	!= 0								&&
		$param_array['time']		!= 0								&&		$param_array['price']		!= 0								&&
		$param_array['distance']	!= 0								&&		$param_array['habit']		!= -1								&&
		!empty($param_array['lieu'])									&&		!empty($param_array['link'])									&&
		$param_array['note'] != -1)) {
				$print_form = true;
				search_error_new_annonce($sort_array, $param_array);
			}
			
	return $print_form;
}

function get_new_annonce_param_array($sort_array) {
	global $request;
	
	$lieu = $request->variable('lieu', '');
	$superf_h = $request->variable('superf_h', $sort_array['min_superf_h']);
	$superf_t = $request->variable('superf_t', $sort_array['min_superf_t']);
	$link = $request->variable('link', '');
	$habit = $request->variable('habit', '');
	$time = $request->variable('time', $sort_array['min_time']);
	$distance = $request->variable('distance', $sort_array['min_distance']);
	$price = (float)$request->variable('price', $sort_array['min_price']);
	$departement = $request->variable('departement', $sort_array['min_departement']);
	$note = $request->variable('note', '');
	
	$habit = convert_str_nb($habit);
	$note = convert_str_nb($note);
	
	if($lieu < 0) $lieu = 0;
	if($superf_h < 0) $superf_h = 0;
	if($superf_t < 0) $superf_t = 0;
	if($time < 0) $time = 0;
	if($distance < 0) $distance = 0;
	if($price < 0.0) $price = 0.0;
	if($depart < 0) $departement = 0;
	
	$param_array = ['lieu' => $lieu,
			'superf_h' => $superf_h,
			'superf_t' => $superf_t,
			'link' => $link,
			'habit' => $habit,
			'time' => $time,
			'distance' => $distance,
			'price' => $price,
			'departement' => $departement,
			'note' => $note];
	
	return $param_array;
}

function search_error_new_annonce($sort_array, $param_array) {
	if(empty($param_array['lieu']) || empty($param_array['link']) || $param_array['superf_h'] == 0 || $param_array['superf_t'] == 0 ||
			$param_array['time'] == 0 || $param_array['distance'] == 0 || $param_array['price'] == 0 || $param_array['habit'] == -1 || $param_array['note'] == -1)
		echo('<p id="form" class="error">Il faut remplir tous les champs !</p>');
	
	if(!preg_match('#^[A-Z][a-zA-Z- ]+#', $param_array['lieu']))
		echo('<p id="form" class="error">Le lieu ne doit contenir que des lettres, des tirets et des espaces, et doit commencer par une lettre majuscule !</p>');

	if($param_array['departement'] > $sort_array['max_departement'] || $param_array['departement'] < $sort_array['min_departement'])
		echo('<p id="form" class="error">Le département doit être compris entre 1 et 95 !</p>');
	
	if($param_array['superf_h'] > $sort_array['max_superf_h'] || $param_array['superf_h'] < $sort_array['min_superf_h'])
		echo('<p id="form" class="error">La superficie de la maison doit être comprise entre '.$sort_array['min_superf_h'].' et '.$sort_array['max_superf_h'].' !</p>');
	
	if($param_array['superf_t'] > $sort_array['max_superf_t'] || $param_array['superf_t'] < $sort_array['min_superf_t'])
		echo('<p id="form" class="error">La superficie du terrain doit être comprise entre '.$sort_array['min_superf_t'].' et '.$sort_array['max_superf_t'].' !</p>');
	
	if(!preg_match('#^https?://(www.)?[a-zA-Z0-9]+\.[a-z0-9]{1,4}\??#', $param_array['link']))
		echo('<p id="form" class="error">Le lien n\'est pas correct !</p>');
	
	if($param_array['time'] > $sort_array['max_time'] || $param_array['time'] < $sort_array['min_time'])
		echo('<p id="form" class="error">Le temps doit être compris entre '.$sort_array['min_time'].' et '.$sort_array['max_time'].' inclus !</p>');
	
	if($param_array['distance'] > $sort_array['max_distance'] || $param_array['distance'] < $sort_array['min_distance'])
		echo('<p id="form" class="error">La distance doit être comprise entre '.$sort_array['min_distance'].' et '.$sort_array['max_distance'].' inclus !</p>');
	
	if($param_array['price'] > $sort_array['max_price'] || $param_array['price'] < $sort_array['min_price'])
		echo('<p id="form" class="error">Le prix doit être compris entre '.$sort_array['min_price'].' et '.$sort_array['max_price'].' k€ !</p>');
}
?>