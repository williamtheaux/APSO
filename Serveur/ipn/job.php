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
	
	// Var json content vote post => user.
	$JSONpost = json_encode($poste);
	
	// Si le vote est different.
	if($voteDb['vote'] != $JSONpost) {
		
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
						
						// Compare les id users. Send mail for bad news.
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
		
		// Ajouter les poste avec leur vote dans la db.
		dbs::setControlIpn(array('vote'=>$JSONpost));
	}
}

// Ajouter les poste avec leur vote dans la db.
else dbs::setControlIpn(array('vote'=>json_encode($poste)));

// Boucle sur la var des lois.
foreach($tmp['obs']['lois']['list'] AS $k => $v) {
	
	// Si la lois est élu. Ajouter avec le id de l'amd.
	if($v['elu']) {
		
		// Id de l'amd.
		$lois[$v['id']] = $v['idAmdElu'];
	}
	
	// Si non, Ajouter avec le id 0.
	else $lois[$v['id']] = 0;
	
	// Le nom de la loi.
	$loiName[$v['id']] = $v['loi'];
	// Le text de l'amd élu.
	$loiAmd[$v['id']] = $v['amdElu'];
}

// Recherche des lois avant, dans la base de données.
$loisDb = dbs::getControlLaw();

// Vérifier, si pas de vote dans la db.
if(!empty($loisDb)) {
	
	// Var json content vote lois => amd or 0.
	$JSONlois = json_encode($lois);
	
	// Si le vote est different.
	if($loisDb['vote'] != $JSONlois) {
		
		// Recup les vote de la db.
		$voteLois=json_decode($loisDb['vote'], true);
		
		// Boucle sur la var des lois.
		foreach($lois AS $k => $v) {
			
			// Si la loi est déjà present dans le vote.
			if(array_key_exists($k, $voteLois)) {
				
				// Si different du precedent.
				if($v != $voteLois[$k]) {
					
					// Si la loi vient d'etre élu.
					if($voteLois[$k] == 0 && $v != 0) email::loiEluSend(array('loi'=>$loiName[$k], 'amd'=>$loiAmd[$k]));
					
					// Si la loi n'est plus élu.
					elseif($voteLois[$k] != 0 && $v == 0) email::loiNotElu(array('loi'=>$loiName[$k], 'amd'=>$loiAmd[$k]));
					
					// Si juste l'amd a changer.
					else email::loiNewsAmd(array('loi'=>$loiName[$k], 'amd'=>$loiAmd[$k]));
				}
			}
			
			// Si une nouvelle loi.
			else {
				
				// Si la loi est élu. Send mail
				if($v != 0) email::loiEluSend(array('loi'=>$loiName[$k], 'amd'=>$loiAmd[$k]));
			}
		}
		
		// Ajouter les loi avec leur amd dans la db.
		dbs::setControlLaw(array('vote'=>$JSONlois));
	}
}

// Ajouter les lois avec leur amd voter, dans la db.
else dbs::setControlLaw(array('vote'=>json_encode($lois)));

?>