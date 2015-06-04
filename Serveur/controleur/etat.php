<?php
/**
 * @version 0.6.0
 * @license MIT license
 * @link    https://chagry.com
 * @author  Grigori <git@chagry.com>
 * @package controleur_etat.php
 */

defined('CHAG') or die('Acces interdit');

class etat {
	
	/**
	 * Function addPoste.
	 * @return	 array value.
	 * @access	 public
	 * @static
	 */
	public static function addPoste($a, $p, $s) {
		
		// Vérification que $n et $amd sont [alpha] ou lever une exception.
		if(!valide::txt($p)) throw new Exception('ERR-POSTE-INVALID');
		
		// Récupérer les donnés utilisateur avec helper.
		$user = help::user($a, $p, $s);
		
		// Vérifier, si pas d'utilisateur, lever une exception.
		if(empty($user)) throw new Exception('ERR-USER-NOT-EXISTS');
		
		// Vérification du rôle de l'utilisateur. Appel de la fonction Accés Controle Level.
		if(!help::acl($user, 'addPoste')) throw new Exception('ERR-USER-NOT-ACCESS');
		
		// Vérifier que le poste n'existe pas déjà dans la base de données.
		$poste = dbs::getPosteByName(array('poste' => $p));
		
		// Vérification, si $poste n'est pas vide, alors lever une exception.
		if(!empty($poste)) throw new Exception('ERR-POSTE-ALREADY-EXISTS');
		
		// Enregistrait le poste dans la base de données.
		dbs::setPoste(array('poste' => $p));
		
		// Vérifier que le poste fut bien ajouter a la base de données.
		$poste = dbs::getPosteByName(array('poste' => $p));
		
		// Vérification, si $poste est vide, alors lever une exception.
		if(empty($poste)) throw new Exception('ERR-ECHEC-SAVE-POSTE');
		
		// Date Actuel.
		$date = new DateTime();
		
		// Sauvegardait l'action dans l'historique. Crée un tableau contenant l'id user, l'action, date, jdata.
		$log = array(
			'id_user' => $user['id'],
			'action' => 'ADDPOSTE',
			'date' => $date->getTimestamp(),
			'jdata' => json_encode(array(
				'id_Poste' => $poste['id'],
				'poste' => $poste['poste']
			))
		);

		// Appel a la fonction du model.
		dbs::setLog($log);
		
		// Add for return.
		$tmp['poste']['id'] = $poste['id'];
		$tmp['poste']['poste'] = $poste['poste'];
		$tmp['poste']['id_elu'] = 0;
		$tmp['poste']['myVote'] = 0;
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
	 * Function deletePoste.
	 * @return	 array value.
	 * @access	 public
	 * @static
	 */
	public static function deletePoste($a, $p, $s) {
		
		// Vérification que $n et $amd sont [alpha] ou lever une exception.
		if(!valide::ints($p)) throw new Exception('ERR-POSTE-INVALID');
		
		// Récupérer les donnés utilisateur avec helper.
		$user = help::user($a, $p, $s);
		
		// Vérifier, si pas d'utilisateur, lever une exception.
		if(empty($user)) throw new Exception('ERR-USER-NOT-EXISTS');
		
		// Vérification du rôle de l'utilisateur. Appel de la fonction Accés Controle Level.
		if(!help::acl($user, 'deletePoste')) throw new Exception('ERR-USER-NOT-ACCESS');
		
		// Vérifier que le poste fut bien ajouter a la base de données.
		$poste = dbs::getPosteById(array('id' => $p));
		
		// Vérification, si $poste est vide, alors lever une exception.
		if(empty($poste)) throw new Exception('ERR-POSTE-NOT-EXISTS');
		
		// Suppression du poste, des votes et des fonctions associer.
		dbs::deletePosteById(array('ids' => $poste['id']));
		
		// Date Actuel.
		$date = new DateTime();
		
		// Sauvegardait l'action dans l'historique. Crée un tableau contenant l'id user, l'action, date, jdata.
		$log = array(
			'id_user' => $user['id'],
			'action' => 'DELETEPOSTE',
			'date' => $date->getTimestamp(),
			'jdata' => json_encode(array(
				'id_Poste' => $poste['id'],
				'poste' => $poste['poste']
			))
		);

		// Appel a la fonction du model.
		dbs::setLog($log);
		
		// Add for return.
		$tmp['poste'] = $poste['id'];
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
	 * Function editeRole.
	 * @return	 array value.
	 * @access	 public
	 * @static
	 */
	public static function editeRole($a, $r, $u, $s) {
		
		// Vérification que $u est [int] ou lever une exception.
		if(!valide::ints($u)) throw new Exception('ERR-VAR-INVALID');
		
		// Vérification que le rôle $r est BANNI ou OBS ou CITOYEN ou lever une exception.
		if($r!='BANNI' && $r!='OBS' && $r!='CITOYEN' ) throw new Exception('ERR-VAR-INVALID');
		
		// Récupérer les donnés utilisateur avec helper.
		$user = help::user($a, $r.$u, $s);
		
		// Vérifier, si pas d'utilisateur, lever une exception.
		if(empty($user)) throw new Exception('ERR-USER-NOT-EXISTS');
		
		// Vérification du rôle de l'utilisateur. Appel de la fonction Accés Controle Level.
		if(!help::acl($user, 'editeRole')) throw new Exception('ERR-USER-NOT-ACCESS');
		
		// Recherche du client dans la base de données par l'id user.
		$client = dbs::getUserById(array('id' => $u));
		
		// Comparer les deux rôles, si identique lever une exception.
		if($client['role'] == $r) throw new Exception('ERR-ROLE-INVALID');
		
		// Si administrateur, lever une exception. 
		if($client['role'] == 'ADMIN') throw new Exception('ERR-NOT-CHANGE-ADMIN');
		
		// Si son propre rôle, lever une exception. 
		if($client['id'] == $user['id']) throw new Exception('ERR-ROLE-INVALID');
		
		// Si citoyen, récupérait ses propre votes.
		if($client['role'] == 'CITOYEN') {
			
			// Récupérer les donnés du vote avec helper.
			$cliVote = help::userVote($client['id']);
			
			// Si il y a des votes.
			if(!empty($cliVote)) {
				
				// conteneur.
				$delete = '';
				
				// Boucle sur $cliVote.
				foreach($cliVote as $k => $v) {
					
					// Si le conteneur n'est pas vide.
					if(!empty($delete)) { 
						
						// Concat "," for sql IN.
						$delete.=', ';
					}
					
					// Concat for sql IN.
					$delete.=$v['id'];	
				}
				
				// Supprimé ses propre votes.
				dbs::deleteMyVote($delete);
				
				// Supprimé les votes effectue pour lui.
				dbs::deleteVoteForClient(array('id1' => $client['id']));
			}
		}
		
		// Modifier le rôle du client.
		dbs::editRoleUser(array('id' => $client['id'], 'role' => $r));
		
		// Date Actuel.
		$date = new DateTime();
		
		// Sauvegardait l'action dans l'historique. Crée un tableau contenant l'id user, l'action, date, jdata.
		$log = array(
			'id_user' => $user['id'],
			'action' => 'EDITEROLE',
			'date' => $date->getTimestamp(),
			'jdata' => json_encode(array(
				'id_user' => $client['id'],
				'nom' => $client['nom'],
				'prenom' => $client['prenom'],
				'role' => $client['role'],
				'new_role' => $r
			))
		);

		// Appel a la fonction du model.
		dbs::setLog($log);
		
		// Add new role to client array.
		$client['new_role'] = $r;
		
		// Send to admin mail.
		email::userRole($client);
		
		// Récupérer les donnés avec helper.
		$tmp = help::getData($user);
		
		// Return array.
		return $tmp;
	}
}
?>