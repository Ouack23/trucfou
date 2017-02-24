<?php
	include_once("../config.php");

	$annonce = $_REQUEST["id"];
	$set_unavailable = $bdd->exec('UPDATE annonces SET available = 0 WHERE id = '.$annonce.'');
?>