<?php
	include_once("../config.php");

	$annonce = $_REQUEST["id"];
	$author = $_REQUEST["author"];
	$note = $_REQUEST["note"];

	$updated = false;

	$get_current_note = $bdd->query('SELECT * FROM notes WHERE annonce = '.$annonce.'');
	if($get_current_note) {

		while($current_note = $get_current_note->fetch()) {
			if($current_note["auteur"] == $author) {
				if($note >= 0 && $note <= 5 ) { //if valid note, update table
					$update_note = $bdd->prepare('UPDATE notes SET value = :note WHERE id = :id');
					$updated = $update_note->execute(array('note' => $note, 'id' => $current_note["id"]));
					break;
				}
				else { // if note is not valid, remove from table 
					$delete = $bdd->prepare('DELETE FROM notes WHERE id = :id');
					$updated = $delete->execute(array('id' => $current_note["id"]));
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
	}

	echo json_encode(array(	"id" => $annonce,
						 	"note" => $note,
						 	"author" => $author));
?>