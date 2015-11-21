<?php
/**
 * @version 0.6.0
 * @license MIT license
 * @link    https://chagry.com
 * @author  Grigori <git@chagry.com>
 * @package controleur_user.php
 */

defined('CHAG') or die('Acces interdit');

class user {
	
	/**
	 * Function login.
	 * @return	 array value.
	 * @access	 public
	 * @static
	 */
	public static function login($a, $t, $s) {
		
		// Vérification que $t est number ou lever une exception.
		if(!valide::ints($t)) throw new Exception('ERR-VAR-INVALID');
		
		// Date Actuel.
		$date = new DateTime();
		
		// 12h en Timestamp.
		$inter = 60*60*12;
		
		// Vérification que Timestamp $t est comprie entre -12h et + 12h ou lever une exception.
		if($t<$date->getTimestamp()-$inter || $t>$date->getTimestamp()+$inter) throw new Exception('ERR-TIMESTAMP-INVALID');
		
		// Récupérer les donnés utilisateur avec helper.
		$user = help::user($a, $t, $s);
		
		// Vérifier, si pas d'utilisateur, lever une exception.
		if(empty($user)) return array('info' => 0);
		
		// Vérification du rôle de l'utilisateur. Si Banni. retourner la variable $tmp['banni'] = 1.
		if($user['role'] == 'BANNI') return array('banni' => 1, 'info' => $user);
		
		// Vérification du rôle de l'utilisateur. Si Guest. retourner la variable $tmp['guest'] = 1.
		if($user['role'] == 'GUEST') return array('guest' => 1, 'info' => $user);
		
		// Récupérer les donnés avec helper.
		$tmp = help::getData($user);
		
		// Si citoyen ou admin. ajouter $tmp['citoyen'] = 1.
		if($user['role']=='ADMIN' || $user['role']=='CITOYEN') $tmp['citoyen'] = 1;
		
		// Add for return.
		$tmp['info'] = $user;
		
		// Security hash.
		$tmp['secu'] = sha1($user['id'].$user['nom'].$user['prenom'].$user['date']);
		
		// Return array.
		return $tmp;
	}
	
	/**
	 * Function sign.
	 * @return	 array value.
	 * @access	 public
	 * @static
	 */
	public static function sign($a, $n, $p, $e, $s) {
		
		// Vérification que nom $n et prénom $p son des alpha ou lever une exception.
		if(!valide::txt($n) || !valide::txt($p) || !valide::email($e)) throw new Exception('ERR-VAR-INVALID');
		
		// Récupérer les donnés utilisateur avec helper.
		$user = help::user($a, $n.$p, $s);
		
		// Vérifier si utilisateur, lever une exception.
		if(!empty($user)) throw new Exception('ERR-ACCOUNT-ALREADY-EXISTS');
		
		// Date Actuel.
		$date = new DateTime();
		
		// Crée un tableau contenant l'identifiant client, nom, prénom, date, rôle.
		$req = array(
			'adr' => $a,
			'nom' => $n,
			'prenom' => $p,
			'date' => $date->getTimestamp(),
			'role' => 'GUEST',
			'email' => $e
		);
		
		// Appel a la fonction du model.
		dbs::setUser($req);
		
		// Récupérer les donnés utilisateur avec helper.
		//$user = help::user($a, $n.$p, $s);
		// Recherche de l'utilisateur dans la base de données par l'identifiant client.
		$user = dbs::getUserByBtc(array('adr' => $a));
		
		// Vérifier si utilisateur, lever une exception.
		if(empty($user)) throw new Exception('ERR-ECHEC-SAVE-USER');
		
		// Racourcire l'adr.
		$req['adr'] = substr($req['adr'], 0, 8).'...';
		$req['email'] = substr($req['email'], 0, 5).'...';
		
		// Sauvegardait l'action dans l'historique. Crée un tableau contenant l'id user, l'action, date, jdata.
		$log = array(
			'id_user' => $user['id'],
			'action' => 'SAVE',
			'date' => $date->getTimestamp(),
			'jdata' => json_encode($req)
		);

		// Appel a la fonction du model.
		dbs::setLog($log);
		
		// Send to admin mail.
		email::newUser($user);
		
		// Return array.
		return $user;
	}
	
	/**
	 * Function upData.
	 * @return	 array value.
	 * @access	 public
	 * @static
	 */
	public static function upData($a, $t, $h, $s) {
		
		// Vérification que $t est number ou lever une exception.
		if(!valide::ints($t)) throw new Exception('ERR-VAR-INVALID');
		
		// Récupérer les donnés utilisateur avec helper.
		$user = help::user($a, $h, $s);
		
		// Vérifier, si pas d'utilisateur, lever une exception.
		if(empty($user)) throw new Exception('ERR-USER-NOT-EXISTS');
		
		// Vérifier le secu hash, lever une exception.
		if(sha1($user['id'].$user['nom'].$user['prenom'].$user['date']) != $h) throw new Exception('ERR-SECU-NOT-EXISTS');
		
		// Vérification du rôle de l'utilisateur $user['role']. Si citoyen ou administrateur, alors poursuivre. si non lever une exception.
		if($user['role']=='GUEST' || $user['role']=='BANNI') throw new Exception('ERR-USER-NOT-ACCESS');
		
		// Compter le nombrer d'action dans le log.
		$nb = dbs::getNbLog();
		
		// Comparér le $nb a $t. Si une correspondance.
		if($nb['nb'] == $t) $tmp['upData'] = 0;
		
		// Si pas de correspondance entre le nombre de log.
		else {
			
			// Récupérer les donnés avec helper.
			$tmp = help::getData($user);
			
			// Si citoyen ou admin. ajouter $tmp['citoyen'] = 1.
			if($user['role']=='ADMIN' || $user['role']=='CITOYEN') $tmp['citoyen'] = 1;
			
			// Add for return.
			$tmp['info'] = $user;
			$tmp['upData'] = 1;
		}
		
		// Return array.
		return $tmp;
	}
}
?>