<?php
/**
 * @version 0.6.1
 * @license MIT license
 * @link    https://chagry.com
 * @author  Grigori <git@chagry.com>
 * @package configuration_params.php
 */

defined('CHAG') or die('Acces interdit');

class params {
	
	public static function start() {
	
		/*
		 * sys. off=0 on=1
		 */
		config::addParams('sys', 'off', 1);
		config::addParams('sys', 'crossDomain', 0);
		
		/*
		 * db.
		 */
		config::addParams('db', 'host', 'HOST');
		config::addParams('db', 'user', 'USER');
		config::addParams('db', 'pass', 'PASS');
		config::addParams('db', 'db', 'DB');
		
		/*
		 * server.
		 */
		config::addParams('us', 'usagent', util::filtre($_SERVER['HTTP_USER_AGENT']));
		config::addParams('us', 'ip', util::filtre($_SERVER['REMOTE_ADDR']));
		config::addParams('us', 'serlang', util::filtre($_SERVER['HTTP_ACCEPT_LANGUAGE']));
		
		/*
		 *************************** PRIVATE ***************************
		 */
		 config::addParams('apso', 'limitLog', 1000);
		 config::addParams('apso', 'pxLoisVote', 50);
		 
		 /*
		 * mail.
		 */
		config::addParams('mail', 'support', 'YOUR_MAIL@YOUR.DOMAIN');
		config::addParams('mail', 'from', 'no-reply@YOUR.DOMAIN');
		config::addParams('mail', 'fromName', 'Support Apso Beta');
		config::addParams('mail', 'pluginDir', 'lib/');
		
		/*
		 * New law and amendment for send mail.
		 */
		config::addParams('mail', 'newLawMail', 'YOUR_MAIL@YOUR_DOMAIN.COM');
		config::addParams('mail', 'fromLawMail', 'FROM_MAIL@YOUR_DOMAIN.COM');
		config::addParams('mail', 'fromLawName', 'New law Apso');
		
		config::addParams('mail', 'toLvsMail', 'YOUR_MAIL@YOUR_DOMAIN.COM');
		config::addParams('mail', 'fromLvsMail', 'FROM_MAIL@YOUR_DOMAIN.COM');
		config::addParams('mail', 'fromLvsName', 'Law info Apso Beta');
	}
}
?>