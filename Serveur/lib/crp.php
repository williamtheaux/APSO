<?php
/*
 * @version     0.1
 * @package		crp.php
 * @license MIT license
 * @link    https://chagry.com
 * @author  Grigori <git@chagry.com>
 * @Func Crypt  crp::crypte('Text', 'Key');
 * @Func Decry  crp::decrypte('Text', 'Key');
 */

/*
 * Class crp.
 */
class crp 
{
	/*
	 * Function GenerationCle
	 */ 
	private static function GenerationCle($Texte,$CleDEncryptage) {
	
		$CleDEncryptage=md5($CleDEncryptage);
		$Compteur=0;
		$VariableTemp="";
		for ($Ctr=0;$Ctr<strlen($Texte);$Ctr++) {
			if($Compteur==strlen($CleDEncryptage)) $Compteur=0;
			$VariableTemp.=substr($Texte,$Ctr,1)^substr($CleDEncryptage,$Compteur,1);
			$Compteur++;
		}
		return $VariableTemp;
	}
	
	/*
	 * Function Crypte
	 */
	public static function crypte($Texte,$Cle) {
	
		srand((double)microtime()*1000000);
		$CleDEncryptage = md5(rand(0,32000));
		$Compteur=0;
		$VariableTemp = "";
		for ($Ctr=0;$Ctr<strlen($Texte);$Ctr++) {
			if($Compteur==strlen($CleDEncryptage)) $Compteur=0;
			$VariableTemp.= substr($CleDEncryptage,$Compteur,1).(substr($Texte,$Ctr,1)^substr($CleDEncryptage,$Compteur,1));
			$Compteur++;
		}
		return base64_encode(self::GenerationCle($VariableTemp,$Cle) );
	}
		
	/*
	 * Function Decrypte
	 */
	public static function decrypte($Texte,$Cle) {
	
		$Texte = self::GenerationCle(base64_decode($Texte),$Cle);
		$VariableTemp = "";
		for ($Ctr=0;$Ctr<strlen($Texte);$Ctr++) {
			$md5 = substr($Texte,$Ctr,1);
			$Ctr++;
			$VariableTemp.=(substr($Texte,$Ctr,1)^$md5);
		}
		return $VariableTemp;
	}
/*
 * Fin de la class crp.
 */
}
?>