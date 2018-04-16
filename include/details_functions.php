<?php
include_once("config.php");

function vote($annonce, $author, $note_str)
{
	global $bdd;
	$ret = 'nope';
	$updated = false;

	$note = intval($note_str);

	$get_current_note = $bdd->query('SELECT * FROM notes WHERE annonce = '.$annonce.'');
	if($get_current_note) {

		while($current_note = $get_current_note->fetch()) {
			if($current_note["auteur"] == $author) {
				if($note >= 0 && $note <= 5) { //if valid note, update table
					$update_note = $bdd->prepare('UPDATE notes SET value = :note WHERE id = :id');
					$updated = $update_note->execute(array('note' => $note, 'id' => $current_note["id"]));
					$ret = 'updated !';
					break;
				}
				else { // if note is not valid, remove from table 
					$delete = $bdd->prepare('DELETE FROM notes WHERE id = :id');
					$updated = $delete->execute(array('id' => $current_note["id"]));
					$ret = 'removed !';
					break;
				}
			}
		}
		$get_current_note->closeCursor();
	}

	if(!$updated) // if autor has not already vote for this annonce, insert row in table
	{
		$insert = $bdd->prepare('INSERT INTO notes(auteur, annonce, value) VALUES(:author, :id, :note)');
		$insert->execute(array('id' => $annonce, 'note' => $note, 'author' => $author));
		$ret = 'created !';
	}

	return $ret;
}

function set_available($annonce, $available)
{
	global $bdd;
	
	if($available != '0' and $available != '1') return 'ZOB';
	
	$set_unavailable = $bdd->prepare('UPDATE annonces SET available = :val WHERE id = :id');
	$ret = $set_unavailable->execute(array('val' => $available, 'id' => $annonce));
	return $available;
}

function delete($annonce)
{
	global $bdd;

	$delete = $bdd->prepare('DELETE FROM notes WHERE annonce = :id');
	$updated = $delete->execute(array('id' => $annonce));
	$delete = $bdd->prepare('DELETE FROM annonces WHERE id = :id');
	$updated = $delete->execute(array('id' => $annonce));
	$delete = $bdd->prepare('DELETE FROM comments WHERE annonce = :id');
	$updated = $delete->execute(array('id' => $annonce));
}

function print_annonce_resume($annonce) {
    
    echo '<table>
            <tr class="table-header">
                <th id="id">N</th>
                <th id="date">Date</th>
                <th id="auteur">Auteur</th>
                <th id="lieu">Lieu</th>
                <th id="departement">Dpt</th>
                <th id="superf_h">Batiment</th>
                <th id="superf_t">Terrain</th>
                <th id="habit">Etat</th>
                <th id="time">Trajet</th>
                <th id="distance">Distance</th>
                <th id="price">Prix</th>
                <th id="note">Moy</th>
                <th id="user_note">Note</th>
                <th id="link">Lien</th>
                <th id="comments">Comms</th>
            </tr>';
    
    if($annonce['available'] == 1) echo '<tr class="available">';
    else echo '<tr class="unavailable">';
    
    echo '<td id="id_">'.$annonce['id'].'</td>';
    echo '<td>'.$annonce['date'].'</td>';
    echo '<td>'.$annonce['auteur'].'</td>';
    echo '<td>'.$annonce['lieu'].'</td>';
    echo '<td>'.$annonce['departement'].'</td>';
    echo '<td>'.$annonce['superf_h'].'</td>';
    echo '<td>'.$annonce['superf_t'].'</td>';
    echo '<td class="habit'.$annonce['habit'].'">'.$annonce['superf_t'].'</td>';
    echo '<td>'.$annonce['time'].'</td>';
    echo '<td>'.$annonce['distance'].'</td>';
    echo '<td>'.$annonce['price'].'</td>';
    echo '<td class="habit'.$annonce['note'].'">'.$annonce['note'].' ('.$annonce['note_count'].')</td>';
    echo '<td id="user_note_" class="habit'.$annonce['user_note'].'">'.$annonce['user_note'].'</td>';
    echo '<td><a href="'.$annonce['link'].'" target="_blank">Annonce</a></td>';
    echo '<td>'.$annonce['comments'].'</td>';
    echo '</tr></table>';
}
?>