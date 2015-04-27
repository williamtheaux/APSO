<?php
/*
 * @version 0.6.0
 * @license MIT license
 * @link    https://chagry.com
 * @author  Grigori <git@chagry.com>
 * @package	tmpl_html.php
 */

defined('CHAG') or die('Acces interdit');

class html {
	
	public static function startTmpl() {
		
		// header.
		$tmp='<!DOCTYPE html>';
		$tmp.='<html>';
		$tmp.='<head>';
			$tmp.='<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
			$tmp.='<meta http-equiv="Pragma" content="no-cache">';
			$tmp.='<link rel="stylesheet" href="tmpl/css/defaut.css" type="text/css" />';
			$tmp.='<title>JSON RPC API</title>';
		$tmp.='</head>';
		$tmp.='<body></body>';
		$tmp.='</html>';
		
		// print.
		print($tmp);
	}
}
?>