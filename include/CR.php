<?php
if(! $user->data['is_registered']){
	include('include/not_registered.php');
}

else {
	$num = $request->variable('num', 0, true);
	$fichiers = glob('include/pdf/*.pdf');

	if( $fichiers !== false )
		$compte = count($fichiers);
	else
		$compte= 0;
	
	if($compte == 0 || $num <= 0 || $num > $compte)
		echo('<p>Erreur : le numéro du pdf n\'est pas bien défini ! Tu peux rapporter ce problème à Belette en lui disant "Erreur sur CR.PHP avec compte='.$compte.'"</p>');

	else {
		$urlCR =  'include/pdf/CR'.$num.'.pdf'; ?>
			<object data="<?php echo($urlCR);?>" type="application/pdf" height="100%" width="70%">
			<p>
				Votre navigateur est moisi et ne supporte pas l'affichage pdf en html5. Vous pouvez directement télécharger le pdf <a href="<?php echo($urlCR);?>">ici</a>.<br />
				Vous pouvez également télécharger un navigateur potable <a href="https://www.mozilla.org/en-US/firefox/all/#fr">ici</a>.
			</p>
			</object>
	<?php
	}
}?>