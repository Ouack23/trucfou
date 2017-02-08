<?php
include_once("include/phpBB.php");
include_once("include/utils.php");
function select_annonce() {
	global $bdd;
	$reponse = $bdd->query('SELECT id, price,'.format_date().', auteur, lieu FROM annonces');
	
	echo('<form accept-charset="utf-8" action="#" method="get"><p>');
	echo('<span class="select-wrapper"><select name="annonce">');
	
	while($annonces=$reponse->fetch()) {
		echo('<option value="'.$annonces['id'].'">N°'.$annonces['id'].' - par '.$annonces['auteur'].' - le '.$annonces['date'].' - à '.$annonces['lieu'].' - coûtant '.$annonces['price'].' k€</option>');
	}
	$reponse->closeCursor();
	
	echo('</select></span>');
	echo('<input type="submit" value="Valider" /></p></form>');
}
?>
