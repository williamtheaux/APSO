<?php
/**
 * @version 0.5.0
 * @license MIT license
 * @link    https://chagry.com
 * @author  Grigori <git@chagry.com>
 * @package lib_util.php
 */

class util {

	/**
	 * Function filtre.
	 * @param   string $str.
	 * @return  string filtered
	 * @access  public
	 * @static
	 */
	public static function filtre($str='') {
	
		/*
		 * strip_tags.
		 * rtrim.
		 * ltrim.
		 */
		$str = strip_tags($str);
		$str = rtrim($str);
		$str = ltrim($str);
		return $str;
	}
	
	/**
	 * Function rands.
	 * @param   int $e.
	 * @return  int rands
	 * @access  public
	 * @static
	 */
	public static function rands($e=6) {
		
		// Boucle nb.
		$nps = '';
		for($i=0;$i<$e;$i++)
		{
			$nps .= mt_rand(1, 9);
		}
		
		// Return number.
		return $nps;
	}
	
	/*
	 * Function appendHexZeros(). 0.5
	 * @param. add.
	 * @return $hexEncodedAddress. 
	 */
	public static function appendHexZeros($inputAddress, $hexEncodedAddress) {
		
		//Append Zeros where nessecary
		for ($i = 0; $i < strlen($inputAddress) && $inputAddress[$i] == "1"; $i++) {
			$hexEncodedAddress = "00" . $hexEncodedAddress;
		}
		if (strlen($return) % 2 != 0) {
			$hexEncodedAddress = "0" . $hexEncodedAddress;
		}
		
		// Return result.
		return $hexEncodedAddress;
	}
	
	/**
	 * Function encodeHex.
	 * @param   string $dec
	 * @return  string encodeHex
	 * @access  public
	 * @static
	 */
	public static function encodeHex($dec) {
		
		$chars="0123456789ABCDEF";
		$return="";
		while (bccomp($dec,0)==1){
			$dv=(string)bcdiv($dec,"16",0);
			$rem=(integer)bcmod($dec,"16");
			$dec=$dv;
			$return=$return.$chars[$rem];
		}
		
		// Return result.
		return strrev($return);
	}
	
	/**
	 * Function base58_decode.
	 * @param   string $base58.
	 * @return  string base58_decode
	 * @access  public
	 * @static
	 */
	public static function base58_decode($base58) {
		
		$origbase58 = $base58;
		//Define vairables
		$base58chars = "123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz";
		$return = "0";
		
		for ($i = 0; $i < strlen($base58); $i++) {
			$current = (string) strpos($base58chars, $base58[$i]);
			$return = (string) bcmul($return, "58", 0);
			$return = (string) bcadd($return, $current, 0);
		}
		
		// Return result.
		return $return;
	}
	
	/**
	 * Function base58_encode.
	 * @param   string $string
	 * @return  string $base58
	 * @access  public
	 * @static
	 */
	public static function base58_encode($string) {
		
		//Define vairables
		$table = '123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz';
		$long_value = gmp_init(bin2hex($string), 16);
		$result = '';
		
		while(gmp_cmp($long_value, 58) > 0) {
			list($long_value, $mod) = gmp_div_qr($long_value, 58);
			$result .= $table[gmp_intval($mod)];
		}
		$result .= $table[gmp_intval($long_value)];
		
		for($nPad = 0; $string[$nPad] == "\0"; ++$nPad);
		
		// Return result.
		return str_repeat($table[0], $nPad).strrev($result);
	}
}
?>