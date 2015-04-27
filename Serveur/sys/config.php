<?php
/**
 * @version 0.5.0
 * @license MIT license
 * @link    https://chagry.com
 * @author  Grigori <git@chagry.com>
 * @package sys_config.php
 */

defined('CHAG') or die('Acces interdit');

class config {

	// $params.
	private static $params =array();
	
	/*
	 * Function __callStatic(). 0.4
	 * @var $e  -> params.
	 */ 
	public static function __callStatic($name, $e) {
		
		// array_key_exists.
		if(array_key_exists($name, self::$params)) {
			
			// implode.
			$tmp=implode('', $e);
			
			// if $params exists.
			if($tmp!='' && array_key_exists($tmp, self::$params[$name])) return self::$params[$name][$tmp];
			else return self::$params[$name];	
		}
		
		else return '';
	}
	
	/*
	 * Function addParams(). 0.4
	 * @var $categorie.
	 * @var $params.
	 * @var $valus.
	 */ 
	public static function addParams($c, $p, $v) {
	
		self::$params[util::filtre($c)][util::filtre($p)]=util::filtre($v);
	}
}