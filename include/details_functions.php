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
				if($note >= 0 && $note <= 5 ) { //if valid note, update table
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
?>