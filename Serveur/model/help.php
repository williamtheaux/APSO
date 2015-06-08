<?php
/**
 * @version 0.6.0
 * @license Private
 * @link    https://chagry.com
 * @author  Grigori <git@chagry.com>
 * @package model_help.php
 */

defined('CHAG') or die('Acces interdit');

class help {
	
	/**
	 * Function user.
	 * @access  public
	 * @static
	 */
	public static function user($a, $v, $s) {
		
		// Vérifier la validité de l'adresse bitcoin $a ou lever une exception.
		if(!valide::btc($a)) throw new Exception('ERR-BTC-ADR-INVALID');
		
		// Vérifier la signature $s avec le hash sha1 de $v et de l'adresse bitcoin $a. ou lever une exception.
		if(!valide::btc_sign($a, sha1($v.$a), $s)) throw new Exception('ERR-BTC-SIGN-INVALID');
		
		// Recherche de l'utilisateur dans la base de données par l'identifiant client.
		$user = dbs::getUserByBtc(array('adr' => $a));
		
		// Return array.
		return $user;
	}
	
	/**
	 * Function acl.
	 * @access  public
	 * @static
	 */
	public static function acl($e, $f) {
		
		// Add for return.
		$tmp = false;
		
		// Si L'utilisateur est administrateur $e['role'], alors retourner la variable $tmp = true.
		if($e['role'] == 'ADMIN') {
			
			// Recherche dans la base de données la fonction propriétaire passer en paramètre $f.
			$func = dbs::getPrivFunc(array('names' => $f));
			
			// Déclencher une boucle sûr $func pour vérifier si l'admin peux utiliser la fonction.
			foreach($func as $k => $v) {
				
				// Si le poste contien un 0 pour admin.
				if((int)$v['id_poste'] === 0) {
					
					// Valider le acl.
					$tmp = true;
				}
			}
		}
		
		// Si citoyen, vérifier les poste est les élus.
		elseif($e['role'] == 'CITOYEN') {
			
			// Recherche dans la base de données la fonction propriétaire passer en paramètre $f.
			$func = dbs::getPrivFuncVote(array('names' => $f));
			
			// Var contenant les vote.
			$suffrage = array();
			
			// Déclencher une boucle sûr $func pour créer un tableau de vote.
			foreach($func['vote'] as $k => $v) {
				
				// Si le poste est déjà present.
				if(!array_key_exists((int)$v['id'], $suffrage)) {
					
					// Si l'id1 n'apparais pas déjà dans un poste précédant.
					if(!in_array((int)$v['id1'], $suffrage)) { 
						
						// Ajouter l'élu au poste.
						$suffrage[(int)$v['id']] = (int)$v['id1'];
					}
				}
			}
			
			// Déclencher une boucle sûr $func pour créer un tableau de fonction.
			foreach($func['func'] as $k => $v) {
				
				// Si correspondance du nom de la fonction propriétaire avec $f.
				if(array_key_exists((int)$v['id_poste'], $suffrage)) {
					
					// S'il y a correspondance entre l'élu et $e['id'].
					if((int)$suffrage[(int)$v['id_poste']] == (int)$e['id']) { 
						
						// Valider le acl.
						$tmp = true;
					}
				}
			}
		}
		
		// Return array.
		return $tmp;
	}
	
	/**
	 * Function userVote.
	 * @access  public
	 * @static
	 */
	public static function userVote($e) {
		
		// Charger le log du citoyen et l'action VOTE.
		$log = dbs::getLogByUserAndAction(array('id_user' => $e, 'action' => 'VOTE'));
		
		// conteneur Hash.
		$hash = array();
		
		// boucle sur $log pour créer le hash se trouve dans la variable jdata de l'action vote.
		foreach($log as $k => $v) {
			
			// Decode JSON le $jdata
			$json = json_decode($v['jdata'], true);
			
			// Ajouter le hash du vote au conteneur.
			$hash[] = $json['hash'];
		}
		
		// conteneur my vote
		$myVote = array();
		
		// Si il y a des vote dans le log.
		if(!empty($hash)) {
			
			// Charger la table vote au complet.
			$vote = dbs::getVote();
			
			// Lancer une boucle sur $vote.
			foreach($vote as $k => $v) {
				
				// Hash le id_vote, d1 et d2.
				$shash = sha1($v['id'].$v['id1'].$v['id2'].$v['type']);
				
				// Compare la présence dans le log créé précédemment.
				if(in_array($shash, $hash)) { 
					
					// Ajouter a mes votes.
					$myVote[] = $v;
				}
			}
		}
		
		// Return array.
		return $myVote;
	}
	
	/**
	 * Function getData.
	 * @access  public
	 * @static
	 */
	public static function getData($e) {
		
		// Add for return.
		$dbe = dbs::getDb();
		
		// Conteneur de retour.
		$tmp = array();
		
		// Conteneur interne des utilisateurs.
		$users = array();
		
		// Conteneur interne des votes pour les postes.
		$voteCTN = array();
		
		// Conteneur interne des votes pour les lois.
		$voteLOS = array();
		
		// Conteneur de mes hashs du vote.
		$myHashVote = array();
		
		// Conteneur interne de mes votes pour les postes.
		$myCtnVote = array();
		
		// Conteneur interne de mes votes pour les lois.
		$myLosVote = array();
		
		// Conteneur interne des amd.
		$amd = array();
		
		// Boucle sur la table utilisateur.
		foreach($dbe['user'] as $k => $v) {
			
			// Si le rôle est ADMIN.
			if($v['role'] == 'ADMIN') { 
				
				// Ajouter à la réponse de retour, les info de l'utilisateur.
				$tmp['obs']['CITOYEN']['list'][] = $v;
				
				// Incrémenter le nombre d'utilisateurs par rôle.
				$tmp['obs']['CITOYEN']['nb'] ++;
			}
			
			// Si non, séparer les utilisateurs par rôle.
			else {
				
				// Ajouter à la réponse de retour, les info de l'utilisateur.
				$tmp['obs'][$v['role']]['list'][] = $v;
				
				// Incrémenter le nombre d'utilisateurs par rôle.
				$tmp['obs'][$v['role']]['nb'] ++;
			}
			
			// Incrémenter la variable interne d'utilisateurs.
			$users[$v['id']] = array('nom' => $v['nom'], 'prenom' => $v['prenom']);
		}
		
		// Conteneur de limit du log a retourner.
		$iLog = 0;
		
		// Boucle sur la table log.
		foreach($dbe['log'] as $k => $v) {
			
			// Conteneur info action.
			$jd = array();
			
			// Incrémenter le nombre d'actions dans le log.
			$tmp['log']['nb']++;
			
			// Si l'action est VOTE.
			if($v['action'] == 'VOTE') {
				
				// Decode Json du log.
				$jdata = json_decode($v['jdata'], true);
				
				// Récupérer le type de vote.
				$jd[] = '[type => '.$jdata['type'].']';
				
				// Si le id_user correspondance a id utilisateur.
				if($v['id_user'] == $e['id']) $myHashVote[] = $jdata['hash'];
			}
			
			// Si l'action n'est pas VOTE. Récupérer les info de l'action.
			else $jd = self::getMsg($v['jdata']);
			
			// Vérifier la limit du log retour.
			if($iLog < config::apso('limitLog')) {
				
				// Ajouter à la réponse de retour, les infos du log.
				$tmp['log']['list'][] = array(
					'id' => $v['id_user'],
					'nom' => $users[$v['id_user']]['nom'],
					'prenom' => $users[$v['id_user']]['prenom'],
					'action' => $v['action'],
					'date' => $v['date'],
					'msg' => $jd
				);
			}
			
			// Incrémenter la limit du log.
			$iLog++;
		}
		
		// Boucle sur la table vote.
		foreach($dbe['vote'] as $k => $v) {
			
			// Construire le Hash du vote. (id+id1+id2+type).
			$shash = sha1($v['id'].$v['id1'].$v['id2'].$v['type']);
			
			// Si le type du vote est poste CTN.
			if($v['type'] == 'CTN') { 
				
				// Vérifier si $v['id2'] est present dans key $voteCTN.
				if(array_key_exists($v['id2'], $voteCTN)) {
					
					// Vérifier si $v['id1'] est present dans key $voteCTN[$v['id2']]. Incrémenter la variable.
					if(array_key_exists($v['id1'], $voteCTN[$v['id2']])) $voteCTN[$v['id2']][$v['id1']] ++;
					
					// Si non, ajouter l'id1 au tableau $voteCTN[$v['id2']][$v['id1']].
					else $voteCTN[$v['id2']][$v['id1']] = 1;
				}
				
				// Si non, ajouter les deux ids au tableau.
				else $voteCTN[$v['id2']][$v['id1']] = 1;
				
				// Si le hash est present dans mes votes. Récupérer le poste et le vote dans $myCtnVote.
				if(in_array($shash, $myHashVote)) $myCtnVote[$v['id2']] = $v['id1'];
			}
			
			// Si le type du vote est lois LOS.
			elseif($v['type'] == 'LOS') {
				 
				// Vérifier si $v['id2'] est present dans key $voteLOS. Incrémenter la variable.
				if(array_key_exists($v['id2'], $voteLOS)) $voteLOS[$v['id2']] ++;
				
				// Si non, ajouter les deux ids au tableau.
				else $voteLOS[$v['id2']] = 1;
				
				// Si le hash est present dans mes votes. Récupérer le poste et le vote dans $myCtnVote.
				if(in_array($shash, $myHashVote)) $myLosVote[] = $v['id2'];
			}
		}
		
		// Boucle sur la variable des vote $voteCTN.
		foreach($voteCTN as $k => $v) {
			
			// Trie le tableau des vote en ordre inverse.
			arsort($voteCTN[$k]);
		}
		
		// Boucle sur la table poste.
		foreach($dbe['poste'] as $k => $v) {
			
			// Incrémenter le nombre de postes.
			$tmp['obs']['postes']['nb']++;
			
			// Définir les variables d'élu a NULL.
			$idElu = 0;
			$nomElu = 0;
			$prenomElu = 0;
			
			// Vérifier si le id poste est present dans key $voteCTN.
			if(array_key_exists($v['id'], $voteCTN)) {
				
				// Boucle sur les vote du poste.
				foreach($voteCTN[$v['id']] AS $k1 => $v1) {
					
					// Conteneur si le client est déjà élu.
					$d = true;
					
					// Boucle sur la liste de poste ajouter précédemment.
					foreach($tmp['obs']['postes']['list'] AS $k2 => $v2) {
						
						// Comparér le client élu précédemment au client actuel. S'il y a une correspondance, $d = FALSE.
						if($v2['id_elu'] == $k1) $d = false;
					}
					
					// Si pas élu précédemment.
					if($d) {
						
						// Ajouter le client a la variables d'élu.
						$idElu = $k1;
						$nomElu = $users[$k1]['nom'];
						$prenomElu = $users[$k1]['prenom'];
						break;
					}
				}
			}
			
			// Définir les variables de mes vote a NULL.
			$myVote = 0;
			$myVoteNom = 0;
			$myVotePrenom = 0;

			// Vérifier si poste_id $v['id'] est present dans key $myCtnVote.
			if(array_key_exists($v['id'], $myCtnVote)) {
				
				// Récupérer l'id_utilisateur pour qui, on a voter.
				$myVote = $myCtnVote[$v['id']];
				$myVoteNom = $users[$myCtnVote[$v['id']]]['nom'];
				$myVotePrenom = $users[$myCtnVote[$v['id']]]['prenom'];
			}
			
			// Ajouter à la réponse de retour, les infos des postes.
			$tmp['obs']['postes']['list'][] = array(
				'id' => $v['id'],
				'poste' => $v['poste'],
				'id_elu' => $idElu,
				'nomElu' => $nomElu,
				'prenomElu' => $prenomElu,
				'myVote' => $myVote,
				'myVoteNom' => $myVoteNom,
				'myVotePrenom' => $myVotePrenom
			);
		}
		
		// Boucle sur la table amd.
		foreach($dbe['amd'] as $k => $v) {
			
			// le nombre de votes est null.
			$nbv = 0;
			
			// Vérifier si le id amd est present dans key $voteLOS. Récupérer le nobre de votes.
			if(array_key_exists($v['id'], $voteLOS)) $nbv = $voteLOS[$v['id']];
				
			// Ajouter les deux ids au tableau $amd[$v['id_lois']][0] avec $v (nbVote), id et amd.
			$amd[$v['id_lois']][] = array('vote' => $nbv, 'amd' => $v['amd'], 'id' => $v['id']);	
		}
		
		// Boucle sur la table lois.
		foreach($dbe['lois'] as $k => $v) {
			
			// Incrémenter le nombre de lois.
			$tmp['obs']['lois']['nb']++;
			
			// Conteneur nombre d'amd.
			$nbAmd = 0;
			
			// Conteneur mes vote.
			$myLoisV = 0;
			
			// Conteneur de comptage des votes.
			$cmp = 0;
			
			// Conteneur de l'amd élu.
			$eluLos = array('nbVote' => 0, 'amd' => $amd[$v['id']][0]['amd']);
			
			// Conteneur des amd.
			$amdAr = array();
			
			// Conteneur de lois élus.
			$elu = 0;
			
			// Boucle sur la var $amd.
			foreach($amd[$v['id']] as $k1 => $v1) {
				
				// Conteneur mes vote.
				$myV = 0;
				
				// Incrémenter le nombre d'amd.
				$nbAmd++;
				
				// Si l'id amd est present dans $myLosVote.
				if(in_array($v1['id'], $myLosVote)) {
					
					// Inclure mon vote.
					$myV = 1;
					$myLoisV = 1;
				}
				
				// Comptage des votes.
				$cmp = $cmp+$v1['vote'].
				
				// Créer le tableau des amds.
				$amdAr[] = array(
					'id' => $v1['id'],
					'desc' => $v1['amd'],
					'nbVote' => $v1['vote'],
					'px' => 0,
					'myVote' => $myV
				);
				
				// Vérifier si nbVote actuel est plus grand que l'adm précédente.
				if($v1['vote'] > $eluLos['nbVote'])	{
					
					// Remplacer les information de $eluLos.
					$eluLos['nbVote'] = $v1['vote'];
					$eluLos['amd'] = $v1['amd'];
				}
			}
			
			// Calculer le pourcentage de votes par a port au citoyens.
			$px = round(100*$cmp/$tmp['obs']['CITOYEN']['nb']);
			
			// Si $px est sup au pourcentage de config. La loi est élu.
			if($px >= config::apso('pxLoisVote')) $elu = 1;
			
			// Boucle sur la var $amdAr.
			foreach($amdAr as $k1 => $v1) {
				
				// Calculer le pourcentage de votes par a port au total.
				$pxa = ($cmp!=0)? round(100*$v1['nbVote']/$cmp) : 0;
				
				// Ajouter le pourcentage par amd.
				$amdAr[$k1]['px'] = $pxa;
			}
			
			// Ajouter à la réponse de retour, les infos des lois.
			$tmp['obs']['lois']['list'][] = array(
				'id' => $v['id'],
				'loi' => $v['nom'],
				'nbAmd' => $nbAmd,
				'elu' => $elu,
				'px' => $px,
				'amdElu' => $eluLos['amd'],
				'myVote' => $myLoisV,
				'amd' => $amdAr
			);
		}
		
		// Détermine si la variable lois nb est définie.
		if (!isset($tmp['obs']['lois']['nb'])) {
			
			// Définir la variable lois nb a 0.
			$tmp['obs']['lois']['nb']=0;
			$tmp['obs']['lois']['list']=array();
		}
		
		// Poste élu et les fonctions propriétaires. Si l'utilisateur est ADMIN.
		if($e['role'] == 'ADMIN') {
			
			// Boucle sur la table func.
			foreach($dbe['func'] AS $k => $v) {
				
				// Si $v['id'] est 0, Ajouter le nom de la fonction a l'admin.
				if($v['id_poste'] == 0) $tmp['admin'][$v['names']] = 1;	
			}
		}
		
		// Poste élu et les fonctions propriétaires. Si l'utilisateur est CITOYEN.
		if($e['role'] == 'CITOYEN') {
			
			// Boucle sur la var des postes.
			foreach($tmp['obs']['postes']['list'] AS $k => $v) {
				
				// S'il y a une correspondance entre utilisateur et l'élu.
				if($v['id_elu'] == $e['id']) {
					
					// Boucle sur la table func.
					foreach($dbe['func'] AS $k1 => $v1) {
						
						// Si $v['id'] est == a $v1['id']. Ajouter le nom de la fonction dans la var admin.
						if($v['id'] == $v1['id_poste']) $tmp['admin'][$v1['names']] = 1;
					}
				}
			}
		}
		
		// Return array.
		return $tmp;
	}
	
	/**
	 * Function getMsg.
	 * @access  public
	 * @static
	 */
	public static function getMsg($e) {
		
		// Conteneur.
		$jde = array();
		
		// Decode Json le $e.
		$tab = json_decode($e, true);
		
		// Lancer une boucle sur $e qui est un array.
		foreach($tab as $k => $v) {
				
			// Crée un array de contenu.
			$jde[] = '['.$k.' => '.$v.']';
		}
		
		// Return array.
		return $jde;
	}
}
?>