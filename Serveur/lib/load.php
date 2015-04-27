<?php
/**
 * @version 0.6.1
 * @license MIT license
 * @link    https://chagry.com
 * @author  Grigori <git@chagry.com>
 * @package lib_load.php
 */

class load {
	
	public static function start() {
		
		// Load GMP or BcMATH
		if(extension_loaded('gmp') && !defined('USE_EXT')) define ('USE_EXT', 'GMP');
		else if(extension_loaded('bcmath') && !defined('USE_EXT')) define ('USE_EXT', 'BCMATH');
		
		// Auto load files.
		function __autoload($f) {
			
			//load lib class files.
			if (file_exists('lib/'.$f.'.php')) require_once 'lib/'.$f.'.php';
			if (file_exists('tmpl/'.$f.'.php')) require_once 'tmpl/'.$f.'.php';
			if (file_exists('sys/'.$f.'.php')) require_once 'sys/'.$f.'.php';
			if (file_exists('configuration/'.$f.'.php')) require_once 'configuration/'.$f.'.php';
			if (file_exists('model/'.$f.'.php')) require_once 'model/'.$f.'.php';
			
			//load interfaces before class files.
			if (file_exists('lib/ecc/interface/'.$f.'Interface.php')) require_once 'lib/ecc/interface/'.$f.'Interface.php';
		
			//load class files after interfaces.
			if (file_exists('lib/ecc/'.$f.'.php')) require_once 'lib/ecc/'.$f.'.php';
		
			//if utilities are needed load them last.
			if (file_exists('lib/ecc/util/'.$f.'.php')) require_once 'lib/ecc/util/'.$f.'.php';
		}
	}
	
	/*
	 * Function auto(). 0.4
	 * @param $className.
	 */ 
	public static function auto($className) {
	
		$demande = str_replace('_','/',$className).'.php';
		if (!is_file($demande)) throw new Exception('SERV-ERROR-NOT-FIND-FILE');
		else require_once $demande;
	}
}
?>