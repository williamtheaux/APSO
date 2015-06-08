<?php
/**
 * @version 0.6.0
 * @license Private
 * @link    https://chagry.com
 * @author  Grigori <git@chagry.com>
 * @package ipn_job.php
 */
 
define('CHAG', 1);
define('PATH', dirname(__FILE__));
define('SL', DIRECTORY_SEPARATOR);

/*
 * Error reporting: add comment from testing Environment.
 */
error_reporting(0);
	
/*
 * Import lib.
 */
require_once PATH.SL.'..'.SL.'lib'.SL.'load.php';
require_once PATH.SL.'..'.SL.'lib'.SL.'util.php';
require_once PATH.SL.'..'.SL.'sys'.SL.'db.php';
require_once PATH.SL.'..'.SL.'sys'.SL.'config.php';
require_once PATH.SL.'..'.SL.'configuration'.SL.'params.php';

// class Params start().
params::start();

// Class model db.
load::auto('.._model_dbs');
load::auto('.._model_help');
load::auto('email');
load::auto('.._lib_class.phpmailer');
load::auto('.._tmpl_mail');

// Creation de l'utilisateur beta.
$user=array(
	'id'=>0,
	'role'=>'ADMIN'
);

// Date Actuel.
$date = new DateTime();

// Clean vote in db.
dbs::cleanVote(array('secudate'=>$date->getTimestamp()-120));

// Récupérer les donnés avec helper.
$tmp = help::getData($user);

// Boucle sur la var des postes.
foreach($tmp['obs']['postes']['list'] AS $k => $v) {
	
	// Recup les poste avec leur élu.
	$poste[$v['id']] = $v['id_elu'];
	
	// Recup les nom des poste.
	$posteName[$v['id']] = $v['poste'];
}

// Recherche du vote befor dans la base de données.
$voteDb = dbs::getControlIpn();

// Vérifier, si pas de vote dans la db.
if(!empty($voteDb)) {
	
	// Recup les vote de la db.
	$vote=json_decode($voteDb['vote'], true);
	
	// Boucle sur la var des postes.
	foreach($poste AS $k => $v) {
		
		// Si le poste est déjà present dans le vote.
		if(array_key_exists($k, $vote)) {
				
			// Si un élu au poste different au vote befor.
			if($v != $vote[$k] && $v != 0) {
				
				// Boucle sur les users.
				foreach($tmp['obs']['CITOYEN']['list'] AS $r => $u) {
					
					// Add poste name.
					$u['poste']=$posteName[$k];
					
					// Compare les id users. Send mail for new élu.
					if($v == $u['id']) email::godNews($u);
					
					// Compare les id users. Send mail for new élu.
					if($vote[$k] == $u['id']) email::badNews($u);
				}
			}
		}
		
		// Si un nouveau poste.
		else {
			
			// Si un élu au poste.
			if($v != 0) {
				
				// Boucle sur les users.
				foreach($tmp['obs']['CITOYEN']['list'] AS $r => $u) {
					
					// Add poste name.
					$u['poste']=$posteName[$k];
					
					// Compare les id users. Send mail for new élu.
					if($v == $u['id']) email::godNews($u);
				}
			} 
		}
	}
}

// Ajouter les poste avec leur vote dans la db.
dbs::setControlIpn(array('vote'=>json_encode($poste)));

?>