<?php
/**
 * @version 0.5.0
 * @license MIT license
 * @link    https://chagry.com
 * @author  Grigori <git@chagry.com>
 * @package sys_email.php
 */

defined('CHAG') or die('Acces interdit');

class email {
	
	/*
	 * Function newUser. 0.6
	 * @param e user info. 
	 */
	public static function badNews($e) {
		
		// Instancier la class de librairie phpmailer.
		$mailer = new PHPmailer();
		
		// Utiliser le mail local.
		$mailer->IsMail();
		
		// Les mail sont en HTML.
		$mailer->IsHTML(true);
		
		// Les caracter encoding.
		$mailer->CharSet = "UTF-8";
		
		// Patch url class.
		$mailer->PluginDir = config::mail('pluginDir');
		
		// Var de l'expéditeur est vide.
		$mailer->From=config::mail('from');
		
		// Si la Var nom de l'expéditeur est vide.
		$mailer->FromName=config::mail('fromName');
		
		// Ajout l'adress représente le destinataire.
		$mailer->AddAddress($e['email']);
		
		// le sujet du mail.
		$mailer->Subject='Voting System Information';	
		
		// Contient le corps du message à envoyer.
		$mailer->Body=mail::badNewsHtml($e);
		
		// Envoi mail.
		$mailer->Send();
		
		// Clear addresses.
		$mailer->ClearAddresses();
	}
	
	/*
	 * Function newUser. 0.6
	 * @param e user info. 
	 */
	public static function godNews($e) {
		
		// Instancier la class de librairie phpmailer.
		$mailer = new PHPmailer();
		
		// Utiliser le mail local.
		$mailer->IsMail();
		
		// Les mail sont en HTML.
		$mailer->IsHTML(true);
		
		// Les caracter encoding.
		$mailer->CharSet = "UTF-8";
		
		// Patch url class.
		$mailer->PluginDir = config::mail('pluginDir');
		
		// Var de l'expéditeur est vide.
		$mailer->From=config::mail('from');
		
		// Si la Var nom de l'expéditeur est vide.
		$mailer->FromName=config::mail('fromName');
		
		// Ajout l'adress représente le destinataire.
		$mailer->AddAddress($e['email']);
		
		// le sujet du mail.
		$mailer->Subject='Voting System Information';	
		
		// Contient le corps du message à envoyer.
		$mailer->Body=mail::godNewsHtml($e);
		
		// Envoi mail.
		$mailer->Send();
		
		// Clear addresses.
		$mailer->ClearAddresses();
	}
}
?>