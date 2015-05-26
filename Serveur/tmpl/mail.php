<?php
/*
 * @version		0.5
 * @date Crea	26/04/2013.
 * @date Modif	12/02/2014.
 * @package		tmpl_mail.php
 * @contact		Chagry.com - git@chagry.com
 */

defined('CHAG') or die('Acces interdit');

class mail {
	
	public static function newUserHtml($e) {
		
		// add header.
		$tmp='<!DOCTYPE html>';
		$tmp.='<html>';
		$tmp.='<head>';
			$tmp.='<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
			$tmp.='<meta http-equiv="Pragma" content="no-cache">';
		$tmp.='</head>';
		$tmp.='<body>';
		
		$tmp.='<p>Nouveau utilisateur : </p>';
		$tmp.='<p>Nom : '.$e['nom'].'</p>';
		$tmp.='<p>Prénom : '.$e['prenom'].'</p>';
		$tmp.='<p>Bitcoin adr : '.$e['adr'].'</p>';
		$tmp.='<p>Email : '.$e['email'].'</p>';
			
		// Fin page.
		$tmp.='</body>';
		$tmp.='</html>';
		
		// Return template.
		return $tmp;
	}
}
?>