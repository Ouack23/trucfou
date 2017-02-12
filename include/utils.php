<?php

function calcul_quartiles($t) {
	sort($t);
	$count = count($t);
	
	$quartiles = [];
	
	for($i = 1; $i <= 3; $i++) {
		$val = floor(($count - 1) * $i / 4);
		
		if(($i % 2 && $count % 4) || (!($i % 2) && $count % 2)) array_push($quartiles, $t[$val]);
		
		else {
			$low = $t[$val];
			$high = $t[$val+1];
			array_push($quartiles, ($low + $high) / 2);
		}
	}
	
	return $quartiles;
}

function cmp($a, $b) {
	if ($a == $b) {
		return 0;
	}
	return ($a < $b) ? -1 : 1;
}

function cmp_reverse($a, $b) {
	if ($a == $b) {
		return 0;
	}
	return ($a > $b) ? -1 : 1;
}

function convert_str_nb($h){
	switch($h) {
		case 'zero':
			return 0;
		break;
		
		case 'un':
			return 1;
		break;
		
		case 'deux':
			return 2;
		break;
		
		case 'trois':
			return 3;
		break;
		
		case 'quatre':
			return 4;
		break;
		
		case 'cinq':
			return 5;
		break;
		
		default:
			echo('<p class="error">Toi, t\'es vraiment un petit malin !</p>');
			return -1;
		break;
	}
}

function format_date() {
	return 'DATE_FORMAT(date, "%d/%m/%Y") AS date';
}

function print_selected($n, $p) {
	$possibilities = [0, 1, 2, 3, 4, 5];
	if(in_array($n, $possibilities) && in_array($p, $possibilities) && $n == $p)
		return('selected');
}

function print_table_header($columns_array, $title) {

	echo('<h1>'.$title.'</h1><div id="table"><table><tr class="top">');

			echo('<tr class="top">');
			$is_first = true;
			foreach($columns_array as $c) {
				if($is_first) {
					echo('<th class="left">'.$c.'</th>');
					$is_first = false;
				}
				else echo('<th>'.$c.'</th>');
			}

			echo('</tr>');
}

?>
