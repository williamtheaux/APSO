<?php
/**
 * @version 0.6.0
 * @license MIT license
 * @link    https://chagry.com
 * @author  Grigori <git@chagry.com>
 * @package controleur_vote.php
 */

defined('CHAG') or die('Acces interdit');

class vote {
	
	/**
	 * Function sending.
	 * @return	 array value.
	 * @access	 public
	 * @static
	 */
	public static function sending($a, $d1, $d2, $t) {
		
		// Vérification que $d1 et $d2 sont [int] ou lever une exception.
		if(!valide::ints($d1) || !valide::ints($d2)) throw new Exception('ERR-VAR-VOTE-INVALID');
		
		// Vérifier la validité de l'adresse bitcoin $a ou lever une exception.
		if(!valide::btc($a)) throw new Exception('ERR-BTC-ADR-INVALID');
		
		// Recherche de l'utilisateur dans la base de données par l'identifiant client.
		$user = dbs::getUserByBtc(array('adr' => $a));
		
		// Vérifier, si pas d'utilisateur, lever une exception.
		if(empty($user)) throw new Exception('ERR-USER-NOT-EXISTS');
		
		// Vérification du rôle de l'utilisateur $user['role']. Si citoyen ou administrateur, alors poursuivre. si non lever une exception.
		if($user['role']!='ADMIN' && $user['role']!='CITOYEN') throw new Exception('ERR-USER-NOT-ACCESS');
		
		// Vérification que le type est CTN.
		if($t =='CTN') {
			
			// Recherche du client dans la base de données par l'id user.
			$client = dbs::getUserById(array('id' => $d1));
			
			// Vérifier, si pas d'utilisateur, lever une exception.
			if(empty($client)) throw new Exception('ERR-VAR-INVALID');
			
			// Vérification si citoyen ou administrateur ou lever une exception.
			if($client['role']!='ADMIN' && $client['role']!='CITOYEN') throw new Exception('ERR-VAR-INVALID');
			
			// Recherche du poste dans la base de données par l'id poste.
			$poste = dbs::getPosteById(array('id' => $d2));
			
			// Vérifier, si pas de poste, lever une exception.
			if(empty($poste)) throw new Exception('ERR-VAR-INVALID');
		}
		
		// Vérification que le type est LOS.
		elseif($t =='LOS') {
			
			// Recherche de la loi dans la base de données par l'id loi.
			$lois = dbs::getLoiById(array('id' => $d1));
			
			// Vérifier, si pas de loi, lever une exception.
			if(empty($lois)) throw new Exception('ERR-VAR-INVALID');
			
			// Recherche de l'amendement dans la base de données par l'id amd.
			$amd = dbs::getAmdById(array('id' => $d2));
			
			// Vérifier, si pas d'amd, lever une exception.
			if(empty($amd)) throw new Exception('ERR-VAR-INVALID');
			
			// Vérification si l'amendement dispose de l'id lois.
			if($amd['id_lois']!=$lois['id']) throw new Exception('ERR-VAR-INVALID');
		}
		
		// Vérification que le type est CTN ou LOS ou lever une exception.
		else throw new Exception('ERR-VAR-VOTE-INVALID');
		
		// Date Actuel.
		$date = new DateTime();
		
		// Sauvegardait le vote dans la base de données. Crée un tableau contenant l'id vote, id1, id2, type, date.
		$req = array(
			'id1' => $d1,
			'id2' => $d2,
			'type' => $t,
			'secudate' => $date->getTimestamp()
		);
		
		// Appel a la fonction du model.
		$idVote = dbs::setVote($req);

		// Construire et retourner le Hash (id+id1+id2+type) pour la signiature du vote.
		$shash = sha1($idVote.$d1.$d2.$t);
		
		// Add for return.
		$tmp = array(
			'id' => $idVote,
			'hash' => $shash
		);
		
		// Return array.
		return $tmp;
	}
	
	/**
	 * Function fix.
	 * @return	 array value.
	 * @access	 public
	 * @static
	 */
	public static function fix($a, $d, $s) {
		
		// Vérification que $d est [int] ou lever une exception.
		if(!valide::ints($d)) throw new Exception('ERR-VAR-VOTE-INVALID');
		
		// Vérifier la validité de l'adresse bitcoin $a ou lever une exception.
		if(!valide::btc($a)) throw new Exception('ERR-BTC-ADR-INVALID');
		
		// Recherche de l'utilisateur dans la base de données par l'identifiant client.
		$user = dbs::getUserByBtc(array('adr' => $a));
		
		// Vérifier, si pas d'utilisateur, lever une exception.
		if(empty($user)) throw new Exception('ERR-USER-NOT-EXISTS');
		
		// Vérification du rôle de l'utilisateur $user['role']. Si citoyen ou administrateur, alors poursuivre. si non lever une exception.
		if($user['role']!='ADMIN' && $user['role']!='CITOYEN') throw new Exception('ERR-USER-NOT-ACCESS');
		
		// Recherche du vote dans la base de données. Appel a la fonction du model.
		$vote = dbs::getVoteById(array('id' => $d));
		
		// Vérifier, si pas d'utilisateur, lever une exception.
		if(empty($vote)) throw new Exception('ERR-VAR-VOTE-INVALID');
		
		// Vérifier que le vote n'est pas déjà signier.
		if($vote['signe'] != 0) throw new Exception('ERR-VAR-VOTE-INVALID');
		
		// Hash le id_vote, d1 et d2 et le type.
		$shash = sha1($vote['id'].$vote['id1'].$vote['id2'].$vote['type']);
		
		// Vérifier la signature avec $vote. si non lever une exception.
		if(!valide::btc_sign($a, $shash, $s)) throw new Exception('ERR-VAR-VOTE-INVALID');
		
		// Récupérer les donnés du vote avec helper.
		$votes = help::userVote($user['id']);
		
		// Lancer une boucle sur $votes.
		foreach($votes as $k => $v) {
			
			// Vérification que le type est CTN.
			if($v['type'] =='CTN' && $vote['type'] == 'CTN') {
				
				// Compare si l'utlisateur a déjà effectuer le vote.
				if($v['id2'] == $vote['id2']) { 
					
					// Supprimer le vote dans la base de données.
					dbs::deleteVote(array('id' => $v['id']));
				}
			}
			
			// Vérification que le type est LOS.
			elseif($v['type'] =='LOS' && $vote['type'] == 'LOS') {
				
				// Compare si l'utlisateur a déjà effectuer le vote.
				if($v['id1'] == $vote['id1']) { 
					
					// Supprimer le vote dans la base de données.
					dbs::deleteVote(array('id' => $v['id']));
				}
			}
		}
		
		// Sauvegardait la signature dans la base de données. Crée un tableau contenant l'identifiant vote et la signature.
		$req = array('id' => $d, 'signe' => $s);
			
		// Appel a la fonction du model.
		dbs::editeSigneInVote($req);
		
		// Date Actuel.
		$date = new DateTime();
		
		// Sauvegardait l'action dans l'historique. Crée un tableau contenant l'id user, l'action, date, jdata.
		$log = array(
			'id_user' => $user['id'],
			'action' => 'VOTE',
			'date' => $date->getTimestamp(),
			'jdata' => json_encode(array(
				'hash' => $shash,
				'type' => $vote['type']
			))
		);

		// Appel a la fonction du model.
		dbs::setLog($log);
		
		// Récupérer les donnés avec helper.
		$tmp = help::getData($user);
		
		// Return array.
		return $tmp;
	}
}
?>