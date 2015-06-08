<?php
/**
 * @version 0.6.0
 * @license Private
 * @link    https://chagry.com
 * @author  Grigori <git@chagry.com>
 * @package model_dbs.php
 */

defined('CHAG') or die('Acces interdit');

class dbs {
	
	/**
	 * Function getDb.
	 * @access  public
	 * @static
	 */
	public static function getDb() {
	
		try {
		
			// query SQL.
			$reqU = db::go('SELECT * FROM apso_user  ORDER BY nom ASC');
			$reqV = db::go('SELECT * FROM apso_vote WHERE signe != "0" ORDER BY id DESC');
			$reqH = db::go('SELECT * FROM apso_log ORDER BY id DESC');
			$reqP = db::go('SELECT * FROM apso_poste ORDER BY id ASC');
			$reqL = db::go('SELECT * FROM apso_lois ORDER BY id DESC');
			$reqA = db::go('SELECT * FROM apso_amd');
			$reqF = db::go('SELECT * FROM apso_func');
			
			// Exécut requête.
			$reqU->execute();
			$reqV->execute();
			$reqH->execute();
			$reqP->execute();
			$reqL->execute();
			$reqA->execute();
			$reqF->execute();
			
			// Récup donnes db.
			$arrTmp = array(
				'user' => $reqU->fetchAll(),
				'vote' => $reqV->fetchAll(),
				'log' => $reqH->fetchAll(),
				'poste' => $reqP->fetchAll(),
				'lois' => $reqL->fetchAll(),
				'amd' => $reqA->fetchAll(),
				'func' => $reqF->fetchAll(),
			);
			
    		// close requête SQL.
    		$reqU->closeCursor();
			$reqV->closeCursor();
			$reqH->closeCursor();
			$reqP->closeCursor();
			$reqL->closeCursor();
			$reqA->closeCursor();
			$reqF->closeCursor();
			
			// return result.
			return $arrTmp;
		}
		
		catch(Exception $e) { throw new Exception('SERV-ERROR-DATABASE'); }
	}
	
	/**
	 * Function getControlIpn.
	 * @access  public
	 * @static
	 */
	public static function getControlIpn() {
	
		try {
		
			// query SQL.
			$req = db::go('SELECT * FROM apso_ipn ORDER BY id DESC');
			
			// Exécut requête.
			$req->execute();
			
			// Récup donnes db.
			$arrTmp = $req->fetch();
			
    		// close requête SQL.
			$req->closeCursor();
			
			// return result.
			return $arrTmp;
		}
		
		catch(Exception $e) { throw new Exception('SERV-ERROR-DATABASE'); }
	}
	
	/**
	 * Function getUserByBtc.
	 * @access  public
	 * @static
	 */
	public static function getUserByBtc($e) {
	
		try {
		
			// query SQL.
			$req = db::go('SELECT * FROM apso_user WHERE adr=:adr');
			
			// Exécut requête.
			$req->execute($e);
			
			// Récup donnes db.
			$arrTmp = $req->fetch();
			
    		// close requête SQL.
			$req->closeCursor();
			
			// return result.
			return $arrTmp;
		}
		
		catch(Exception $e) { throw new Exception('SERV-ERROR-DATABASE'); }
	}
	
	/**
	 * Function getUserById.
	 * @access  public
	 * @static
	 */
	public static function getUserById($e) {
	
		try {
		
			// query SQL.
			$req = db::go('SELECT * FROM apso_user WHERE id=:id');
			
			// Exécut requête.
			$req->execute($e);
			
			// Récup donnes db.
			$arrTmp = $req->fetch();
			
    		// close requête SQL.
			$req->closeCursor();
			
			// return result.
			return $arrTmp;
		}
		
		catch(Exception $e) { throw new Exception('SERV-ERROR-DATABASE'); }
	}
	
	/**
	 * Function getPosteByName.
	 * @access  public
	 * @static
	 */
	public static function getPosteByName($e) {
	
		try {
		
			// query SQL.
			$req = db::go('SELECT * FROM apso_poste WHERE poste=:poste');
			
			// Exécut requête.
			$req->execute($e);
			
			// Récup donnes db.
			$arrTmp = $req->fetch();
			
    		// close requête SQL.
			$req->closeCursor();
			
			// return result.
			return $arrTmp;
		}
		
		catch(Exception $e) { throw new Exception('SERV-ERROR-DATABASE'); }
	}
	
	/**
	 * Function getPosteById.
	 * @access  public
	 * @static
	 */
	public static function getPosteById($e) {
	
		try {
		
			// query SQL.
			$req = db::go('SELECT * FROM apso_poste WHERE id=:id');
			
			// Exécut requête.
			$req->execute($e);
			
			// Récup donnes db.
			$arrTmp = $req->fetch();
			
    		// close requête SQL.
			$req->closeCursor();
			
			// return result.
			return $arrTmp;
		}
		
		catch(Exception $e) { throw new Exception('SERV-ERROR-DATABASE'); }
	}
	
	/**
	 * Function getLogByUserAndAction.
	 * @access  public
	 * @static
	 */
	public static function getLogByUserAndAction($e) {
	
		try {
		
			// query SQL.
			$reqFunc = db::go('SELECT jdata FROM apso_log WHERE id_user=:id_user AND action=:action');
			
			// Exécut requête.
			$reqFunc->execute($e);
			
			// Récup donnes db.
			$arrTmp = $reqFunc->fetchAll();
			
    		// close requête SQL.
			$reqFunc->closeCursor();
			
			// return result.
			return $arrTmp;
		}
		
		catch(Exception $e) { throw new Exception('SERV-ERROR-DATABASE'); }
	}
	
	/**
	 * Function getVote.
	 * @access  public
	 * @static
	 */
	public static function getVote() {
	
		try {
		
			// query SQL.
			$req = db::go('SELECT * FROM apso_vote WHERE  signe != "0"');
			
			// Exécut requête.
			$req->execute();
			
			// Récup donnes db.
			$arrTmp = $req->fetchAll();
			
    		// close requête SQL.
			$req->closeCursor();
			
			// return result.
			return $arrTmp;
		}
		
		catch(Exception $e) { throw new Exception('SERV-ERROR-DATABASE'); }
	}
	
	/**
	 * Function getVoteById.
	 * @access  public
	 * @static
	 */
	public static function getVoteById($e) {
	
		try {
		
			// query SQL.
			$req = db::go('SELECT * FROM apso_vote WHERE id=:id');
			
			// Exécut requête.
			$req->execute($e);
			
			// Récup donnes db.
			$arrTmp = $req->fetch();
			
    		// close requête SQL.
			$req->closeCursor();
			
			// return result.
			return $arrTmp;
		}
		
		catch(Exception $e) { throw new Exception('SERV-ERROR-DATABASE'); }
	}
	
	/**
	 * Function getAmdById.
	 * @access  public
	 * @static
	 */
	public static function getAmdById($e) {
	
		try {
		
			// query SQL.
			$req = db::go('SELECT * FROM apso_amd WHERE id=:id');
			
			// Exécut requête.
			$req->execute($e);
			
			// Récup donnes db.
			$arrTmp = $req->fetch();
			
    		// close requête SQL.
			$req->closeCursor();
			
			// return result.
			return $arrTmp;
		}
		
		catch(Exception $e) { throw new Exception('SERV-ERROR-DATABASE'); }
	}
	
	/**
	 * Function getAmdByText.
	 * @access  public
	 * @static
	 */
	public static function getAmdByText($e) {
	
		try {
		
			// query SQL.
			$req = db::go('SELECT * FROM apso_amd WHERE id_lois=:id_lois AND amd=:amd');
			
			// Exécut requête.
			$req->execute($e);
			
			// Récup donnes db.
			$arrTmp = $req->fetch();
			
    		// close requête SQL.
			$req->closeCursor();
			
			// return result.
			return $arrTmp;
		}
		
		catch(Exception $e) { throw new Exception('SERV-ERROR-DATABASE'); }
	}
	
	/**
	 * Function getPrivFunc.
	 * @access  public
	 * @static
	 */
	public static function getPrivFunc($e) {
	
		try {
		
			// query SQL.
			$reqFunc = db::go('SELECT id_poste FROM apso_func WHERE names=:names');
			
			// Exécut requête.
			$reqFunc->execute($e);
			
			// Récup donnes db.
			$arrTmp = $reqFunc->fetchAll();
			
    		// close requête SQL.
			$reqFunc->closeCursor();
			
			// return result.
			return $arrTmp;
		}
		
		catch(Exception $e) { throw new Exception('SERV-ERROR-DATABASE '); }
	}
	
	/**
	 * Function getPrivFunc.
	 * @access  public
	 * @static
	 */
	public static function getPrivFuncVote($e) {
	
		try {
		
			// query SQL.
			$reqFunc = db::go('SELECT id_poste FROM apso_func WHERE names=:names');
			
			// query SQL.
			$req = db::go('SELECT p.id, v.id1, COUNT(v.id2) nb 
				FROM apso_poste p 
				LEFT JOIN apso_vote v 
				ON p.id = v.id2 AND v.type = "CTN" AND signe != "0" 
				GROUP BY p.id, v.id1 
				ORDER BY p.id ASC, nb DESC, v.id ASC'
			);
			
			// Exécut requête.
			$req->execute($e);
			$reqFunc->execute($e);
			
			// Récup donnes db.
			$arrTmp['vote'] = $req->fetchAll();
			$arrTmp['func'] = $reqFunc->fetchAll();
			
    		// close requête SQL.
			$req->closeCursor();
			$reqFunc->closeCursor();
			
			// return result.
			return $arrTmp;
		}
		
		catch(Exception $e) { throw new Exception('SERV-ERROR-DATABASE '); }
	}
	
	/**
	 * Function getLoiById.
	 * @access  public
	 * @static
	 */
	public static function getLoiById($e) {
	
		try {
		
			// query SQL.
			$req = db::go('SELECT * FROM apso_lois WHERE id=:id');
			
			// Exécut requête.
			$req->execute($e);
			
			// Récup donnes db.
			$arrTmp = $req->fetch();
			
    		// close requête SQL.
			$req->closeCursor();
			
			// return result.
			return $arrTmp;
		}
		
		catch(Exception $e) { throw new Exception('SERV-ERROR-DATABASE'); }
	}
	
	/**
	 * Function getLoiByName.
	 * @access  public
	 * @static
	 */
	public static function getLoiByName($e) {
	
		try {
		
			// query SQL.
			$req = db::go('SELECT * FROM apso_lois WHERE nom=:nom');
			
			// Exécut requête.
			$req->execute($e);
			
			// Récup donnes db.
			$arrTmp = $req->fetch();
			
    		// close requête SQL.
			$req->closeCursor();
			
			// return result.
			return $arrTmp;
		}
		
		catch(Exception $e) { throw new Exception('SERV-ERROR-DATABASE'); }
	}
	
	/**
	 * Function getNbLog.
	 * @access  public
	 * @static
	 */
	public static function getNbLog() {
	
		try {
		
			// query SQL.
			$req = db::go('SELECT COUNT(*) AS nb FROM apso_log');
			
			// Exécut requête.
			$req->execute();
			
			// Récup donnes db.
			$arrTmp = $req->fetch();
			
    		// close requête SQL.
			$req->closeCursor();
			
			// return result.
			return $arrTmp;
		}
		
		catch(Exception $e) { throw new Exception('SERV-ERROR-DATABASE'); }
	}
	
	/**
	 * Function setControlIpn.
	 * @access  public
	 * @static
	 */
	public static function setControlIpn($e) {
	
		try {
		
			// requête SQL.
			$req = db::go('INSERT INTO apso_ipn VALUES("", :vote)');
			
			// Exécut requête.
			$req->execute($e);
		
			// close requête SQL.
			$req->closeCursor();
		}
	
		catch(Exception $e) { throw new Exception('SERV-ERROR-DATABASE'); }
	}
	
	/**
	 * Function setUser.
	 * @access  public
	 * @static
	 */
	public static function setUser($e) {
	
		try {
		
			// requête SQL.
			$req = db::go('INSERT INTO apso_user VALUES("", :adr, :nom, :prenom, :date, :role, :email)');
			
			// Exécut requête.
			$req->execute($e);
		
			// close requête SQL.
			$req->closeCursor();
		}
		
		catch(Exception $e) { throw new Exception('SERV-ERROR-DATABASE'); }
	}
	
	/**
	 * Function setLog.
	 * @access  public
	 * @static
	 */
	public static function setLog($e) {
	
		try {
		
			// requête SQL.
			$req = db::go('INSERT INTO apso_log VALUES("", :id_user, :action, :date, :jdata)');
			
			// Exécut requête.
			$req->execute($e);
		
			// close requête SQL.
			$req->closeCursor();
		}
	
		catch(Exception $e) { throw new Exception('SERV-ERROR-DATABASE'); }
	}
	
	/**
	 * Function setPoste.
	 * @access  public
	 * @static
	 */
	public static function setPoste($e) {
	
		try {
		
			// query SQL.
			$req = db::go('INSERT INTO apso_poste VALUES("", :poste)');
			
			// Exécut requête.
			$req->execute($e);
			
    		// close requête SQL.
			$req->closeCursor();
		}
		
		catch(Exception $e) { throw new Exception('SERV-ERROR-DATABASE'); }
	}
	
	/**
	 * Function setVote.
	 * @access  public
	 * @static
	 */
	public static function setVote($e) {
	
		try {
		
			// query SQL.
			$req = db::go('INSERT INTO apso_vote VALUES("", :id1, :id2, :type, 0, :secudate)');
			
			// Exécut requête.
			$req->execute($e);
			
			// Récup last insert id in db.
			$arrTmp = db::lastId();
			
    		// close requête SQL.
			$req->closeCursor();
			
			// return result.
			return $arrTmp;
		}
		
		catch(Exception $e) { throw new Exception('SERV-ERROR-DATABASE '); }
	}
	
	/**
	 * Function setLoi.
	 * @access  public
	 * @static
	 */
	public static function setLoi($e) {
	
		try {
		
			// query SQL.
			$req = db::go('INSERT INTO apso_lois VALUES("", :nom)');
			
			// Exécut requête.
			$req->execute($e);
			
    		// close requête SQL.
			$req->closeCursor();
		}
		
		catch(Exception $e) { throw new Exception('SERV-ERROR-DATABASE'); }
	}
	
	/**
	 * Function setAmd.
	 * @access  public
	 * @static
	 */
	public static function setAmd($e) {
	
		try {
		
			// query SQL.
			$req = db::go('INSERT INTO apso_amd VALUES("", :id_lois, :amd)');
			
			// Exécut requête.
			$req->execute($e);
			
    		// close requête SQL.
			$req->closeCursor();
		}
		
		catch(Exception $e) { throw new Exception('SERV-ERROR-DATABASE'); }
	}
	
	/**
	 * Function editeLoiById.
	 * @access  public
	 * @static
	 */
	public static function editeLoiById($e) {
	
		try {
		
			// requête SQL.
			$req = db::go('UPDATE apso_lois SET nom=:nom WHERE id=:id');
			
			// Exécut requête.
			$req->execute($e);
		
			// close requête SQL.
			$req->closeCursor();
		}
		
		catch(Exception $e) { throw new Exception('SERV-ERROR-DATABASE'); }
	}
	
	/**
	 * Function editeAmdById.
	 * @access  public
	 * @static
	 */
	public static function editeAmdById($e) {
	
		try {
		
			// requête SQL.
			$req = db::go('UPDATE apso_amd SET amd=:amd WHERE id=:id');
			
			// Exécut requête.
			$req->execute($e);
		
			// close requête SQL.
			$req->closeCursor();
		}
		
		catch(Exception $e) { throw new Exception('SERV-ERROR-DATABASE'); }
	}
	
	/**
	 * Function editeSigneInVote.
	 * @access  public
	 * @static
	 */
	public static function editeSigneInVote($e) {
	
		try {
		
			// requête SQL.
			$req = db::go('UPDATE apso_vote SET signe=:signe WHERE id=:id');
			
			// Exécut requête.
			$req->execute($e);
		
			// close requête SQL.
			$req->closeCursor();
		}
		
		catch(Exception $e) { throw new Exception('SERV-ERROR-DATABASE'); }
	}
	
	/**
	 * Function editRoleUser.
	 * @access  public
	 * @static
	 */
	public static function editRoleUser($e) {
	
		try {
		
			// requête SQL.
			$req = db::go('UPDATE apso_user SET role=:role WHERE id=:id');
			
			// Exécut requête.
			$req->execute($e);
		
			// close requête SQL.
			$req->closeCursor();
		}
		
		catch(Exception $e) { throw new Exception('SERV-ERROR-DATABASE'); }
	}
	
	/**
	 * Function deletePoste.
	 * @access  public
	 * @static
	 */
	public static function deletePosteById($e) {
	
		try {
		
			// requête SQL.
			$req = db::go('DELETE FROM apso_poste WHERE id=:ids');
			$req1 = db::go('DELETE FROM apso_vote WHERE id2=:ids AND type="CTN"');
			$req2 = db::go('DELETE FROM apso_func WHERE id_poste=:ids');
			
			// Exécut requête.
			$req1->execute($e);
			$req2->execute($e);
			$req->execute($e);
		
			// Close requête SQL.
			$req->closeCursor();
			$req1->closeCursor();
			$req2->closeCursor();
		}
		
		catch(Exception $e) { throw new Exception('SERV-ERROR-DATABASE'); }
	}
	
	/**
	 * Function deleteVote.
	 * @access  public
	 * @static
	 */
	public static function deleteVote($e) {
	
		try {
		
			// requête SQL.
			$req = db::go('DELETE FROM apso_vote WHERE id=:id');
			
			// Exécut requête.
			$req->execute($e);
		
			// Close requête SQL.
			$req->closeCursor();
		}
		
		catch(Exception $e) { throw new Exception('SERV-ERROR-DATABASE'); }
	}
	
	/**
	 * Function deletLoi.
	 * @access  public
	 * @static
	 */
	public static function deletLoi($e) {
	
		try {
		
			// requête SQL.
			$req = db::go('DELETE FROM apso_lois WHERE id=:id');
			
			// Exécut requête.
			$req->execute($e);
		
			// Close requête SQL.
			$req->closeCursor();
		}
		
		catch(Exception $e) { throw new Exception('SERV-ERROR-DATABASE'); }
	}
	
	/**
	 * Function deleteLoiComplet.
	 * @access  public
	 * @static
	 */
	public static function deleteLoiComplet($e) {
	
		try {
		
			// requête SQL.
			$req1 = db::go('DELETE FROM apso_vote WHERE id1=:id AND type="LOS"');
			$req2 = db::go('DELETE FROM apso_amd WHERE id_lois=:id');
			$req = db::go('DELETE FROM apso_lois WHERE id=:id');
			
			// Exécut requête.
			$req1->execute($e);
			$req2->execute($e);
			$req->execute($e);
		
			// Close requête SQL.
			$req->closeCursor();
			$req1->closeCursor();
			$req2->closeCursor();
		}
		
		catch(Exception $e) { throw new Exception('SERV-ERROR-DATABASE'); }
	}
	
	/**
	 * Function deleteAmd.
	 * @access  public
	 * @static
	 */
	public static function deleteAmdById($e) {
	
		try {
		
			// requête SQL.
			$req1 = db::go('DELETE FROM apso_vote WHERE id2=:id AND type="LOS"');
			$req = db::go('DELETE FROM apso_amd WHERE id=:id');
			
			// Exécut requête.
			$req1->execute($e);
			$req->execute($e);
		
			// Close requête SQL.
			$req1->closeCursor();
			$req->closeCursor();
		}
		
		catch(Exception $e) { throw new Exception('SERV-ERROR-DATABASE'); }
	}
	
	/**
	 * Function deleteMyVote.
	 * @access  public
	 * @static
	 */ 
	public static function deleteMyVote($e) {
	
		try {
		
			// requête SQL.
			$req = db::go('DELETE FROM apso_vote WHERE id IN ('.$e.')');
			
			// Exécut requête.
			$req->execute();
		
			// Close requête SQL.
			$req->closeCursor();
		}
	
		catch(Exception $e) { throw new Exception('SERV-ERROR-DATABASE'); }
	}
	
	/**
	 * Function deleteVoteForClient.
	 * @access  public
	 * @static
	 */ 
	public static function deleteVoteForClient($e) {
	
		try {
		
			// requête SQL.
			$req = db::go('DELETE FROM apso_vote WHERE id1=:id1 and type="CTN"');
			
			// Exécut requête.
			$req->execute($e);
		
			// Close requête SQL.
			$req->closeCursor();
		}
	
		catch(Exception $e) { throw new Exception('SERV-ERROR-DATABASE'); }
	}
	
	/**
	 * Function cleanVote.
	 * @access  public
	 * @static
	 */ 
	public static function cleanVote($e) {
	
		try {
		
			// requête SQL.
			$req = db::go('DELETE FROM apso_vote WHERE signe="0" AND secudate<=:secudate');
			
			// Exécut requête.
			$req->execute($e);
		
			// Close requête SQL.
			$req->closeCursor();
		}
	
		catch(Exception $e) { throw new Exception('SERV-ERROR-DATABASE'); }
	}
}
?>