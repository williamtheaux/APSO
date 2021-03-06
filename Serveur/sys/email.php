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
	public static function newUser($e) {
	
		// Class phpmailer.
		load::auto('lib_class.phpmailer');
		
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
		$mailer->AddAddress(config::mail('support'));
		
		// le sujet du mail.
		$mailer->Subject='New User';	
		
		// Contient le corps du message à envoyer.
		$mailer->Body=mail::newUserHtml($e);
		
		// Envoi mail.
		$mailer->Send();
		
		// Clear addresses.
		$mailer->ClearAddresses();
	}
	
	/*
	 * Function newUser. 0.6
	 * @param e user info. 
	 */
	public static function userRole($e) {
	
		// Class phpmailer.
		load::auto('lib_class.phpmailer');
		
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
		$mailer->Subject='User Rôle';	
		
		// Contient le corps du message à envoyer.
		$mailer->Body=mail::userRole($e);
		
		// Envoi mail.
		$mailer->Send();
		
		// Clear addresses.
		$mailer->ClearAddresses();
	}
	
	/*
	 * Function newLaw. 0.7
	 * @param e user info. 
	 */
	public static function newLaw($e) {
	
		// Class phpmailer.
		load::auto('lib_class.phpmailer');
		
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
		$mailer->From=config::mail('fromLawMail');
		
		// Si la Var nom de l'expéditeur est vide.
		$mailer->FromName=config::mail('fromLawName');
		
		// Ajout l'adress représente le destinataire.
		$mailer->AddAddress(config::mail('newLawMail'));
		
		// le sujet du mail.
		$mailer->Subject='New Law';	
		
		// Contient le corps du message à envoyer.
		$mailer->Body=mail::newLawHtml($e);
		
		// Envoi mail.
		$mailer->Send();
		
		// Clear addresses.
		$mailer->ClearAddresses();
	}
	
	/*
	 * Function newAmd. 0.7
	 * @param e user info. 
	 */
	public static function newAmd($e) {
	
		// Class phpmailer.
		load::auto('lib_class.phpmailer');
		
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
		$mailer->From=config::mail('fromLawMail');
		
		// Si la Var nom de l'expéditeur est vide.
		$mailer->FromName=config::mail('fromLawName');
		
		// Ajout l'adress représente le destinataire.
		$mailer->AddAddress(config::mail('newLawMail'));
		
		// le sujet du mail.
		$mailer->Subject='New amendment';	
		
		// Contient le corps du message à envoyer.
		$mailer->Body=mail::newAmdHtml($e);
		
		// Envoi mail.
		$mailer->Send();
		
		// Clear addresses.
		$mailer->ClearAddresses();
	}
}
?>