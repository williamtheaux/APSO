<?php
/**
 * @version 0.6.0
 * @license MIT license
 * @link    https://chagry.com
 * @author  Grigori <git@chagry.com>
 * @package lib_valide.php
 */

class valide
{
	/*
	 * Function email(). 0.5
	 * @param. mail.
	 * @return boolean TRUE or FALSE. 
	 */
	public static function email($str='')
	{
		// RegEx.
		$tmp = false;
		
		// Control.
		if(filter_var($str, FILTER_VALIDATE_EMAIL)) $tmp = true;
		else $tmp = false;
		
		// Return.
		return $tmp;
	}
	
	/*
	 * Function strMd5(). 0.4
	 * @param. String md5.
	 * @return boolean TRUE or FALSE. 
	 */
	public static function strMd5($str='')
	{
		// RegEx.
		$Syntaxe = '#^[a-f0-9]{32}$#' ;
		$tmp = false;
		
		// Control.
		if(preg_match($Syntaxe,$str)) $tmp = true;
		else $tmp = false;
		
		// Return.
		return $tmp;
	}
	
	/*
	 * Function strSha1(). 0.4
	 * @param. String Sha1.
	 * @return boolean TRUE or FALSE. 
	 */
	public static function strSha1($str='')
	{
		// RegEx.
		$Syntaxe = '#^[a-f0-9]{40}$#' ;
		$tmp = false;
		
		// Control.
		if(preg_match($Syntaxe,$str)) $tmp = true;
		else $tmp = false;
		
		// Return.
		return $tmp;
	}
	
	/*
	 * Function url(). 0.5
	 * @param. String url.
	 * @return boolean TRUE or FALSE. 
	 */
	public static function url($str='')
	{
		// RegEx.
		$tmp = false;
		
		// Control.
		if(filter_var($str, FILTER_VALIDATE_URL)) $tmp = true;
		else $tmp = false;
		
		// Return.
		return $tmp;
	}
	
	/*
	 * Function domain(). 0.5
	 * @param. String url.
	 * @return boolean TRUE or FALSE. 
	 */
	public static function domain($str='')
	{
		//valid chars check
		$Syntaxe = '#^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i';
		//overall length check
		$Syntaxe1 = '#^.{1,253}$';
		//length of each label
		$Syntaxe2 = '#^[^\.]{1,63}(\.[^\.]{1,63})*$';
		
		$tmp = false;
		
		// Control.
		if(preg_match($Syntaxe,$str) && preg_match($Syntaxe1,$str) && preg_match($Syntaxe2,$str)) $tmp = true;
		else $tmp = false;
		
		// Return.
		return $tmp;
	}
	
	/*
	 * Function ip(). 0.5
	 * @param. String ip.
	 * @return boolean TRUE or FALSE. 
	 */
	public static function ip($str='')
	{
		// RegEx.
		$tmp = false;
		
		// Control.
		if(filter_var($str, FILTER_VALIDATE_IP)) $tmp = true;
		else $tmp = false;
		
		// Return.
		return $tmp;
	}
	
	/*
	 * Function floats(). 0.5
	 * @param. String float.
	 * @return boolean TRUE or FALSE. 
	 */
	public static function floats($str='')
	{
		// RegEx.
		$tmp = false;
		
		// Control.
		if(filter_var($str, FILTER_VALIDATE_FLOAT)) $tmp = true;
		else $tmp = false;
		
		// Return.
		return $tmp;
	}
	
	/*
	 * Function ints(). 0.5
	 * @param. String int.
	 * @return boolean TRUE or FALSE. 
	 */
	public static function ints($str='')
	{
		// RegEx.
		$tmp = false;
		
		// Control.
		if(filter_var($str, FILTER_VALIDATE_INT)) $tmp = true;
		else $tmp = false;
		
		// Return.
		return $tmp;
	}
	
	/*
	 * Function alpha(). 0.5
	 * @param. String alpha.
	 * @return boolean TRUE or FALSE. 
	 */
	public static function alpha($str='')
	{
		// RegEx.
		$Syntaxe = '#^[a-zA-ZéÉèÈçÇàôÔùúûÛÚÙÝîÎêÊäëïöüÿÄËÏÖÜŸãñõÃÑÕœÆÀÁÂÃÒÓ]+$#';
		$tmp = false;
		
		// Control.
		if(preg_match($Syntaxe,$str)) $tmp = true;
		else $tmp = false;
		
		// Return.
		return $tmp;
	}
	
	/*
	 * Function txt(). 0.6
	 * @param. String alpha.
	 * @return boolean TRUE or FALSE. 
	 */
	public static function txt($str='')
	{
		// RegEx.
		$Syntaxe = '#^[ a-zA-Z0-9+\',.-_?!()=*"/:&éÉèÈçÇàôÔùúûÛÚÙÝîÎêÊäëïöüÿÄËÏÖÜŸãñõÃÑÕœÆÀÁÂÃÒÓ]+$#';
		$tmp = false;
		
		// Control.
		if(preg_match($Syntaxe,$str)) $tmp = true;
		else $tmp = false;
		
		// Return.
		return $tmp;
	}
	
	/*
	 * Function alpha_numeric(). 0.5
	 * @param. String alpha_numeric.
	 * @return boolean TRUE or FALSE. 
	 */
	public static function alpha_numeric($str='')
	{
		// RegEx.
		$Syntaxe = '#^[a-zA-Z0-9]+$#' ;
		$tmp = false;
		
		// Control.
		if(preg_match($Syntaxe,$str)) $tmp = true;
		else $tmp = false;
		
		// Return.
		return $tmp;
	}
	
	/*
	 * Function numeric(). 0.5
	 * @param. String numeric.
	 * @return boolean TRUE or FALSE. 
	 */
	public static function numeric($str='')
	{
		// RegEx.
		$Syntaxe = '#^[0-9]+$#' ;
		$tmp = false;
		
		// Control.
		if(preg_match($Syntaxe,$str)) $tmp = true;
		else $tmp = false;
		
		// Return.
		return $tmp;
	}
	
	/*
	 * Function btc(). 0.5
	 * @param. String btc.
	 * @return boolean TRUE or FALSE. 
	 */
	public static function btc($address='') {
		
		$origbase58 = $address;
		$dec = "0";
		
		for ($i = 0; $i < strlen($address); $i++)
		{
			$dec = bcadd(bcmul($dec,"58",0),strpos("123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz",substr($address,$i,1)),0);
		}
		
		$address = "";
		
		while (bccomp($dec,0) == 1)
		{
			$dv = bcdiv($dec,"16",0);
			$rem = (integer)bcmod($dec,"16");
			$dec = $dv;
			$address = $address.substr("0123456789ABCDEF",$rem,1);
		}
		
		$address = strrev($address);
		
		for ($i = 0; $i < strlen($origbase58) && substr($origbase58,$i,1) == "1"; $i++)
		{
			$address = "00".$address;
		}
		
		if (strlen($address)%2 != 0) $address = "0".$address;
		if (strlen($address) != 50) return false;
		if (hexdec(substr($address,0,2)) > 0) return false;
		
		return true;
	}
	
	/*
	 * Function btc_key(). 0.5
	 * @param. String btc key.
	 * @return boolean TRUE or FALSE. 
	 */
	public static function btc_key($str='')
	{
		// RegEx.
		$Syntaxe = '#^[123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz]{50,54}$#' ;
		$tmp = false;
		
		// Control.
		if(preg_match($Syntaxe,$str)) $tmp = true;
		else $tmp = false;
		
		// Return.
		return $tmp;
	}
	
	/*
	 * Function btc_sign(). 0.6
	 * @Param $a adr.
	 * @Param $m mess.
	 * @Param $s sign.
	 * @return boolean TRUE or FALSE. 
	 */
	public static function btc_sign($a='', $m='', $s='')
	{
		// RegEx.
		$tmp = false;
		
		try {
			
			// Class model dbClub.
			load::auto('lib_verifymessage');
			
			// Control.
			if(verifymessage($a, $s, $m)) $tmp = true;
			
			// Return.
			return $tmp;
		}
		
		catch(Exception $e) { return $tmp; }
	}
}
?>