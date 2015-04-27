<?php
/**
 * @version 0.5.0
 * @license MIT license
 * @link    https://chagry.com
 * @author  Grigori <git@chagry.com>
 * @package sys_db.php
 */

defined('CHAG') or die('Acces interdit');

class db {

	/*
	 * @var $go -> class PDO.
	 */
	private static $go ='';
	
	/*
	 * Function connect(). 0.6
	 * @param $dbcon.
	 * @return new PDO.
	 */
	private static function connect($dbcon) {
	
		// if not PDO.
		if(self::$go=='') {
			
			try {
			
				// Option error PDO.
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$pdo_options[PDO::ATTR_DEFAULT_FETCH_MODE] = PDO::FETCH_ASSOC;
				// Conexion.
				self::$go = new PDO('mysql:host='.$dbcon['host'].';dbname='.$dbcon['db'],$dbcon['user'],$dbcon['pass'], $pdo_options);
			}
			
			// Exception.
			catch(Exception $e) { throw new Exception('SERV-ERROR-CONNECT-MYSQL'); }
		}
	}
	
	/*
	 * Function go(). 0.4
	 * @param $prepa.
	 * @return new PDO.
	 */
	public static function go($prepa) {
	
		// if PDO.
		if(self::$go=='') self::connect(config::db());
		// Return
		return self::$go->prepare($prepa);
	}
	
	/*
	 * Function go(). 0.6
	 * @return last insert id in PDO.
	 */
	public static function lastId() {
	
		// Return
		return self::$go->lastInsertId();
	}
}
?>