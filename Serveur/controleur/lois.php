<?php
/**
 * @version 0.6.0
 * @license MIT license
 * @link    https://chagry.com
 * @author  Grigori <git@chagry.com>
 * @package controleur_lois.php
 */

defined('CHAG') or die('Acces interdit');

class lois {
	
	/**
	 * Function addLois.
	 * @return	 array value.
	 * @access	 public
	 * @static
	 */
	public static function addLois($a, $n, $amd, $s) {
		
		// Vérification que $n et $amd sont [alpha] ou lever une exception.
		if(!valide::txt($n) || !valide::txt($amd)) throw new Exception('ERR-VAR-INVALID');
		
		// Récupérer les donnés utilisateur avec helper.
		$user = help::user($a, $n.$amd, $s);
		
		// Vérifier, si pas d'utilisateur, lever une exception.
		if(empty($user)) throw new Exception('ERR-USER-NOT-EXISTS');
		
		// Vérification du rôle de l'utilisateur $user['role']. Si citoyen ou administrateur, alors poursuivre. si non lever une exception.
		if($user['role']!='ADMIN' && $user['role']!='CITOYEN') throw new Exception('ERR-USER-NOT-ACCESS');
		
		// Vérifier que la loi n'existe pas déjà dans la base de données.
		$loi = dbs::getLoiByName(array('nom' => $n));
		
		// Vérification, si $loi n'est pas vide, alors lever une exception.
		if(!empty($loi)) throw new Exception('ERR-LOI-ALREADY-EXISTS');
		
		// Enregistrait la loi dans la base de données.
		dbs::setLoi(array('nom' => $n));
		
		// Vérifier que la loi existe dans la base de données.
		$loi = dbs::getLoiByName(array('nom' => $n));
		
		// Vérification, si $loi est vide, Lever une exception.
		if(empty($loi)) throw new Exception('ERR-ECHEC-SAVE-LOI');
		
		// Enregistrait le premier amendement.
		dbs::setAmd(array('id_lois' => $loi['id'], 'amd' => $amd));
		
		// Vérifier que l'amendement fut bien ajouter a la base de données.
		$dbAmd = dbs::getAmdByText(array('id_lois' => $loi['id'], 'amd' => $amd));
		
		// Vérification, si l'amendement est vide.
		if(empty($dbAmd)) {
			
			// Supprimé, la loi précédemment ajoutée.
			dbs::deletLoi(array('id' => $loi['id']));
			
			// Lever une exception.
			throw new Exception('ERR-ECHEC-SAVE-LOI');
		}
		
		// Date Actuel.
		$date = new DateTime();
		
		// Sauvegardait l'action dans l'historique. Crée un tableau contenant l'id user, l'action, date, jdata.
		$log = array(
			'id_user' => $user['id'],
			'action' => 'ADDLOIS',
			'date' => $date->getTimestamp(),
			'jdata' => json_encode(array(
				'id_lois' => $loi['id'],
				'nom' => $loi['nom'],
				'id_amd' => $dbAmd['id'],
				'amd' => $dbAmd['amd']
			))
		);

		// Appel a la fonction du model.
		dbs::setLog($log);
		
		// Send to group mail new law.
		email::newLaw(array('loi'=> $loi['nom'], 'amd'=>$dbAmd['amd']));
		
		// Add for return.
		$tmp['lois']['id'] = $loi['id'];
		$tmp['lois']['loi'] = $loi['nom'];
		$tmp['lois']['nbAmd'] = 1;
		$tmp['lois']['elu'] = 0;
		$tmp['lois']['px'] = 0;
		$tmp['lois']['amdElu'] = $dbAmd['amd'];
		$tmp['lois']['myVote'] = 0;
		$tmp['lois']['amd'][0]['id'] = $dbAmd['id'];
		$tmp['lois']['amd'][0]['desc'] = $dbAmd['amd'];
		$tmp['lois']['amd'][0]['px'] = 0;
		$tmp['lois']['amd'][0]['nbVote'] = 0;
		$tmp['lois']['amd'][0]['myVote'] = 0;
		$tmp['log']['id_user'] = $user['id'];
		$tmp['log']['nom'] = $user['nom'];
		$tmp['log']['prenom'] = $user['prenom'];
		$tmp['log']['action'] = $log['action'];
		$tmp['log']['date'] = $log['date'];
		$tmp['log']['msg'] = help::getMsg($log['jdata']);
		
		// Return array.
		return $tmp;
	}
	
	/**
	 * Function addAmd.
	 * @return	 array value.
	 * @access	 public
	 * @static
	 */
	public static function addAmd($a, $l, $amd, $s) {
		
		// Vérification que $d1 et $d2 sont [int] ou lever une exception.
		if(!valide::ints($l) || !valide::txt($amd)) throw new Exception('ERR-VAR-INVALID');
		
		// Récupérer les donnés utilisateur avec helper.
		$user = help::user($a, $l.$amd, $s);
		
		// Vérifier, si pas d'utilisateur, lever une exception.
		if(empty($user)) throw new Exception('ERR-USER-NOT-EXISTS');
		
		// Vérification du rôle de l'utilisateur $user['role']. Si citoyen ou administrateur, alors poursuivre. si non lever une exception.
		if($user['role']!='ADMIN' && $user['role']!='CITOYEN') throw new Exception('ERR-USER-NOT-ACCESS');
		
		// Vérifier que la loi existe déjà dans la base de données.
		$loi = dbs::getLoiById(array('id' => $l));
		
		// Vérification, si $loi est vide, Lever une exception.
		if(empty($loi)) throw new Exception('ERR-LOI-NOT-EXISTS');
		
		// Vérifier que l'amendement fut bien ajouter a la base de données.
		$dbAmd = dbs::getAmdByText(array('id_lois' => $loi['id'], 'amd' => $amd));
		
		// Vérification, si $amd est vide, Lever une exception.
		if(!empty($dbAmd)) throw new Exception('ERR-LOI-ALREADY-EXISTS');
		
		// Enregistrait l'amendement dans la base de données.
		dbs::setAmd(array('id_lois' => $loi['id'], 'amd' => $amd));
		
		// Vérifier que l'amendement fut bien ajouter a la base de données.
		$dbAmd = dbs::getAmdByText(array('id_lois' => $loi['id'], 'amd' => $amd));
		
		// Vérification, si $amd est vide, Lever une exception.
		if(empty($dbAmd)) throw new Exception('ERR-ECHEC-SAVE-AMD');
		
		// Date Actuel.
		$date = new DateTime();
		
		// Sauvegardait l'action dans l'historique. Crée un tableau contenant l'id user, l'action, date, jdata.
		$log = array(
			'id_user' => $user['id'],
			'action' => 'ADDAMD',
			'date' => $date->getTimestamp(),
			'jdata' => json_encode(array(
				'id_lois' => $loi['id'],
				'nom' => $loi['nom'],
				'id_amd' => $dbAmd['id'],
				'amd' => $dbAmd['amd']
			))
		);

		// Appel a la fonction du model.
		dbs::setLog($log);
		
		// Send to group mail new amendment.
		email::newAmd(array('loi'=> $loi['nom'], 'amd'=>$dbAmd['amd']));
		
		// Add for return.
		$tmp['id_loi'] = $loi['id'];
		$tmp['loi'] = $loi['nom'];
		$tmp['amd']['id'] = $dbAmd['id'];
		$tmp['amd']['desc'] = $dbAmd['amd'];
		$tmp['amd']['px'] = 0;
		$tmp['amd']['nbVote'] = 0;
		$tmp['amd']['myVote'] = 0;
		$tmp['log']['id_user'] = $user['id'];
		$tmp['log']['nom'] = $user['nom'];
		$tmp['log']['prenom'] = $user['prenom'];
		$tmp['log']['action'] = $log['action'];
		$tmp['log']['date'] = $log['date'];
		$tmp['log']['msg'] = help::getMsg($log['jdata']);
		
		// Return array.
		return $tmp;
	}
	
	/**
	 * Function editeLois.
	 * @return	 array value.
	 * @access	 public
	 * @static
	 */
	public static function editeLois($a, $l, $d, $s) {
		
		// Vérification que la loi $l est alpha et $d est INT ou lever une exception.
		if(!valide::ints($d) || !valide::txt($l)) throw new Exception('ERR-VAR-INVALID');
		
		// Récupérer les donnés utilisateur avec helper.
		$user = help::user($a, $l.$d, $s);
		
		// Vérifier, si pas d'utilisateur, lever une exception.
		if(empty($user)) throw new Exception('ERR-USER-NOT-EXISTS');
		
		// Vérification du rôle de l'utilisateur. Appel de la fonction Accés Controle Level.
		if(!help::acl($user, 'editeLois')) throw new Exception('ERR-USER-NOT-ACCESS');
		
		// Vérifier que la loi existe déjà dans la base de données.
		$loi = dbs::getLoiById(array('id' => $d));
		
		// Vérification, si $loi est vide, Lever une exception.
		if(empty($loi)) throw new Exception('ERR-LOI-NOT-EXISTS');
		
		// Modifie la loi dans la base de données.
		dbs::editeLoiById(array('id' => $loi['id'], 'nom' => $l));
		
		// Date Actuel.
		$date = new DateTime();
		
		// Sauvegardait l'action dans l'historique. Crée un tableau contenant l'id user, l'action, date, jdata.
		$log = array(
			'id_user' => $user['id'],
			'action' => 'EDITELOIS',
			'date' => $date->getTimestamp(),
			'jdata' => json_encode(array(
				'id_lois' => $loi['id'],
				'nom' => $loi['nom'],
				'new' => $l
			))
		);

		// Appel a la fonction du model.
		dbs::setLog($log);
		
		// Add for return.
		$tmp['id_loi'] = $loi['id'];
		$tmp['loi'] = $l;
		$tmp['log']['id_user'] = $user['id'];
		$tmp['log']['nom'] = $user['nom'];
		$tmp['log']['prenom'] = $user['prenom'];
		$tmp['log']['action'] = $log['action'];
		$tmp['log']['date'] = $log['date'];
		$tmp['log']['msg'] = help::getMsg($log['jdata']);
		
		// Return array.
		return $tmp;
	}
	
	/**
	 * Function editeAmd.
	 * @return	 array value.
	 * @access	 public
	 * @static
	 */
	public static function editeAmd($a, $amd, $d, $s) {
		
		// Vérification que la loi $l est alpha et $d est INT ou lever une exception.
		if(!valide::ints($d) || !valide::txt($amd)) throw new Exception('ERR-VAR-INVALID');
		
		// Récupérer les donnés utilisateur avec helper.
		$user = help::user($a, $amd.$d, $s);
		
		// Vérifier, si pas d'utilisateur, lever une exception.
		if(empty($user)) throw new Exception('ERR-USER-NOT-EXISTS');
		
		// Vérification du rôle de l'utilisateur. Appel de la fonction Accés Controle Level.
		if(!help::acl($user, 'editeAmd')) throw new Exception('ERR-USER-NOT-ACCESS');
		
		// Vérifier que l'amendement existe déjà dans la base de données.
		$amds = dbs::getAmdById(array('id' => $d));
		
		// Vérification, si $amd est vide, alors lever une exception.
		if(empty($amds)) throw new Exception('ERR-AMD-NOT-EXISTS');
		
		// Modifie l'amd dans la base de données.
		dbs::editeAmdById(array('id' => $amds['id'], 'amd' => $amd));
		
		// Date Actuel.
		$date = new DateTime();
		
		// Sauvegardait l'action dans l'historique. Crée un tableau contenant l'id user, l'action, date, jdata.
		$log = array(
			'id_user' => $user['id'],
			'action' => 'EDITEAMD',
			'date' => $date->getTimestamp(),
			'jdata' => json_encode(array(
				'id_amd' => $amds['id'],
				'amd' => $amds['amd'],
				'new' => $amd
			))
		);

		// Appel a la fonction du model.
		dbs::setLog($log);
		
		// Add for return.
		$tmp['id_amd'] = $amds['id'];
		$tmp['amd'] = $amd;
		$tmp['log']['id_user'] = $user['id'];
		$tmp['log']['nom'] = $user['nom'];
		$tmp['log']['prenom'] = $user['prenom'];
		$tmp['log']['action'] = $log['action'];
		$tmp['log']['date'] = $log['date'];
		$tmp['log']['msg'] = help::getMsg($log['jdata']);
		
		// Return array.
		return $tmp;
	}
	
	/**
	 * Function deleteLoi.
	 * @return	 array value.
	 * @access	 public
	 * @static
	 */
	public static function deleteLoi($a, $d, $s) {
		
		// Vérification que id loi $d est int ou lever une exception.
		if(!valide::ints($d)) throw new Exception('ERR-VAR-INVALID');
		
		// Récupérer les donnés utilisateur avec helper.
		$user = help::user($a, $d, $s);
		
		// Vérifier, si pas d'utilisateur, lever une exception.
		if(empty($user)) throw new Exception('ERR-USER-NOT-EXISTS');
		
		// Vérification du rôle de l'utilisateur. Appel de la fonction Accés Controle Level.
		if(!help::acl($user, 'deleteLois')) throw new Exception('ERR-USER-NOT-ACCESS');
		
		// Vérifier que la loi existe déjà dans la base de données.
		$loi = dbs::getLoiById(array('id' => $d));
		
		// Vérification, si $loi est vide, Lever une exception.
		if(empty($loi)) throw new Exception('ERR-LOI-NOT-EXISTS');
		
		// Suppression de la loi, des amds et des votes associer.
		dbs::deleteLoiComplet(array('id' => $loi['id']));
		
		// Date Actuel.
		$date = new DateTime();
		
		// Sauvegardait l'action dans l'historique. Crée un tableau contenant l'id user, l'action, date, jdata.
		$log = array(
			'id_user' => $user['id'],
			'action' => 'DELETELOIS',
			'date' => $date->getTimestamp(),
			'jdata' => json_encode(array(
				'id_lois' => $loi['id'],
				'loi' => $loi['nom']
			))
		);

		// Appel a la fonction du model.
		dbs::setLog($log);
		
		// Add for return.
		$tmp['id_loi'] = $loi['id'];
		$tmp['loi'] = $loi['nom'];
		$tmp['log']['id_user'] = $user['id'];
		$tmp['log']['nom'] = $user['nom'];
		$tmp['log']['prenom'] = $user['prenom'];
		$tmp['log']['action'] = $log['action'];
		$tmp['log']['date'] = $log['date'];
		$tmp['log']['msg'] = help::getMsg($log['jdata']);
		
		// Return array.
		return $tmp;
	}
	
	/**
	 * Function deleteAmd.
	 * @return	 array value.
	 * @access	 public
	 * @static
	 */
	public static function deleteAmd($a, $d, $s) {
		
		// Vérification que id loi $d est int ou lever une exception.
		if(!valide::ints($d)) throw new Exception('ERR-VAR-INVALID');
		
		// Récupérer les donnés utilisateur avec helper.
		$user = help::user($a, $d, $s);
		
		// Vérifier, si pas d'utilisateur, lever une exception.
		if(empty($user)) throw new Exception('ERR-USER-NOT-EXISTS');
		
		// Vérification du rôle de l'utilisateur. Appel de la fonction Accés Controle Level.
		if(!help::acl($user, 'deleteAmd')) throw new Exception('ERR-USER-NOT-ACCESS');
		
		// Vérifier que l'amendement existe déjà dans la base de données.
		$amds = dbs::getAmdById(array('id' => $d));
		
		// Vérification, si $amd est vide, alors lever une exception.
		if(empty($amds)) throw new Exception('ERR-AMD-NOT-EXISTS');
		
		// Suppression de l'amd et des votes.
		dbs::deleteAmdById(array('id' => $amds['id']));
		
		// Date Actuel.
		$date = new DateTime();
		
		// Sauvegardait l'action dans l'historique. Crée un tableau contenant l'id user, l'action, date, jdata.
		$log = array(
			'id_user' => $user['id'],
			'action' => 'DELETEAMD',
			'date' => $date->getTimestamp(),
			'jdata' => json_encode(array(
				'id_amd' => $amds['id'],
				'amd' => $amds['amd']
			))
		);

		// Appel a la fonction du model.
		dbs::setLog($log);
		
		// Add for return.
		$tmp['id_amd'] = $amds['id'];
		$tmp['amd'] = $amds['amd'];
		$tmp['log']['id_user'] = $user['id'];
		$tmp['log']['nom'] = $user['nom'];
		$tmp['log']['prenom'] = $user['prenom'];
		$tmp['log']['action'] = $log['action'];
		$tmp['log']['date'] = $log['date'];
		$tmp['log']['msg'] = help::getMsg($log['jdata']);
		
		// Return array.
		return $tmp;
	}
}
?>