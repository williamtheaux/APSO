# Spécification technique Api (STB V.0.1)
> Api dédiée en PHP avec le protocole JSON RPC 2, permettant la démocratie en temps-réel. C'est la partie qui centralise et distribue les données. Elle gère la gestion des actions possibles.

### Ω user_login($a, $t, $s)
> Connexion de l'utilisateur.

**Informations entrantes**

| param | Type | Desc |
|-------|------|------|
| $a | string | Identifiant client (adresse bitcoin). |
| $t | int | Timestamp actuel. |
| $s | string | Signiature (hash sha1 Timestamp+Identifiant). |

**Règles de gestion**

1. Vérification que Timestamp `$t` est number et comprie entre -12h et + 12h `(60*60*12)` ou lever une exception. `ERR-TIMESTAMP-INVALID`
2. Récupérer les donnés utilisateur avec helper. Vérifier si pas d'utilisateur, retourner la variable `$tmp['info'] =  0`.
	
	```php
	// Appel de la fonction helper dans un if.
	if(!$user = help::user($a, $t, $s)) return // info = 0;
	```
	
	* Ajouter à la réponse de retour, les info de l'utilisateur `$tmp['info'] = $user`.
	
3. Vérification du rôle de l'utilisateur.
	* Si Banni. retourner la variable `$tmp['banni'] = 1`.
	* Si Guest. retourner la variable `$tmp['guest'] = 1`.
	* Si citoyen ou admin. ajouter `$tmp['citoyen'] = 1`.

4. Sélectionner toute la base de données.
	
	```php
	// Appel a la fonction du model.
	$dbs = dbs::getDb();
	```

5. Boucle sur la table utilisateur. `$dbs['user'] AS $k => $v`
	* Séparer les utilisateurs par rôle. Ajouter à la réponse de retour, les info de l'utilisateur `$tmp['obs'][$v['role']]['list'][] = array() // info user`. Si le rôle est ADMIN, alors ajouter au rôle CITOYEN.
	
	* Incrémenter le nombre d'utilisateurs par rôle. `$tmp['obs'][$v['role']]['nb'] ++;`
	
	* Incrémenter la variable interne d'utilisateurs. `$users[$v['id']]` = array nom et prénom.

6. Boucle sur la table log. `$dbs['log'] AS $k => $v`
	
	* Decode JSON `$v['jdata']` dans `$jdata`.
	
	* Si l'action est `VOTE`.
		* Si le id_user correspondance a id utilisateur `$user['id']`
			* Récupérer le hash et le id_user dans `$myHashVote`.
	
	* Incrémenter le nombre d'actions dans le log `$tmp['log']['nb']++`
	* Ajouter à la réponse de retour, les infos du log. Dans la limit de 1000.
		
		```php
		$tmp['log']['list'][] = array(
			'id' => $v['id_user'],
			'nom' => $users[$v['id_user']]['nom'],
			'prenom' => $users[$v['id_user']]['prénom'],
			'action' => $v['action'],
			'date' => $v['date'],
			'msg' => ???
		);
		```
		
7. Boucle sur la table vote. `$dbs['vote'] AS $k => $v`
	* Hash le `$v['id']`, `$v['id1']` et `$v['id2']`.
	* Séparer les votes par type `$v['type']`.
		
		* Type poste `CTN`
			* Vérifier le poste et l'utilisateur choisi.
			* Vérifier si `$v['id2']` est present dans key `$voteCTN`.
				* Si oui, Vérifier si `$v['id1']` est present dans key `$voteCTN[$v['id2']]`.
					* Si oui, Incrémenter la variable `$voteCTN[$v['id2']][$v['id1']] ++`.
					* Si non, ajouter l'id1 au tableau `$voteCTN[$v['id2']][$v['id1']] ++`.
				* Si non, ajouter les deux ids au tableau `$voteCTN[$v['id2']][$v['id1']] = 1`.
				* Si le hash est present dans KEY `$myHashVote`.
					* Récupérer `$v['id2'] => $v['id1']` du vote dans `$myCtnVote`.
			* Classer la variable par postes ids, puis par client qui on le plus de votes.
			
		* Type loi `LOS`
			* Vérifier la loi et l'amendement choisi.
			* Vérifier si `$v['id2']` est present dans key `$voteLOS`.
				* Si oui, Incrémenter la variable `$voteLOS[$v['id2']] ++`.
				* Si non, ajouter les deux ids au tableau `$voteLOS[$v['id2']] = 1`.
				* Si le hash est present dans KEY `$myHashVote`.
					* Récupérer `$v['id2']` du vote dans `$myLosVote`.
			* Classer la variable par lois ids, puis par amd qui on le plus de votes.

8. Boucle sur la table poste. `$dbs['poste'] AS $k => $v`
	* Incrémenter le nombre de postes `$tmp['obs']['postes']['nb']++`.
	* Vérifier si `$v['id']` est present dans key `$voteCTN`.
		* Si oui, trouver l'élu. Boucle sur `$tmp['obs']['postes']['list'] AS $k => $v1`
			* Comparér le client élu `$v1['id_elu']` == `$voteCTN[$v['id']][0][KEY]`
				* S'il y a une correspondance, $d = FALSE si non $d = TRUE
		* Si non, définir les variables id_elu, nomElu, prenomElu a NULL.
		* Si $d = FALSE, Recommencez l'opération avec `$voteCTN[$v['id']][1][KEY]`...
		* Si $d = TRUE, $id_elu = `$voteCTN[$v['id']][?][KEY]`
		* Vérifier si poste_id `$v['id']` est present dans key `$myCtnVote`.
			* Récupérer l'id_utilisateur pour qui, on a voter `$myCTNV = $myCtnVote[$v['id']]`. Si non retourner `$myCTNV = 0`.
		
	* Ajouter à la réponse de retour, les infos des postes.
		
		```php
		$tmp['obs']['postes'][list][] = array(
			'id' => $v['id'],
			'poste' => $v['poste'],
			'id_elu' => $id_elu,
			'nomElu' => $users[$id_elu]['nom'],
			'prenomElu' => $users[$id_elu]['prenom'],
			'myVote' => $myCTNV,
			'myVoteName' => $users[$$myCTNV]['nom'], // Or 0
			'myVotePrenom' =>$users[$$myCTNV]['prenom'] // Or 0
		);
		```

9. Boucle sur la table amd `$dbs['amd'] AS $k => $v`
	* Vérifier si `$v['id']` est present dans key `$voteLOS`.
		* Si oui, Récupérer le nobre de votes `$v = $voteLOS[$v1['id']]`.
		* Si non, `$v = 0`.
	* Vérifier si `$v['id_lois']` est present dans key `$amd`.
		* Si oui, ajouter l'id au tableau `$amd[$v['id_lois']][]` avec $v, id et amd.
		* Si non, ajouter les deux ids au tableau `$amd[$v['id_lois']][0]` avec $v (nbVote), id et amd.

10. Boucle sur la table loi `$dbs['lois'] AS $k => $v`
	* Incrémenter le nombre de lois `$tmp['obs']['lois']['nb']++`.
	* Boucle sur la var `$amd[$v['id']]AS $k1 => $v1`.
		* Incrémenter le nombre de amd `$nbAmd++`.
		* Si l'id amd est present dans `$myLosVote`.
			* Si oui, $myV = 1 and $myLoisV = 1, Si non $myV = 0.
		* Comptage des votes. `$cmp = $cmp+$v1['nbVote']`.
		* Créer le tableau des amds.
		
			```php
			$amdAr = [
				[0] : {
					'id' : $v1['id'],
					'desc' : $v1['amd'],
					'nbVote' : $v1['nbVote'],
					'px' : 0
					'myVote' : $myV
				} [1] //...
			```
		
		* Vérifier si nbVote actuel est plus grand que l'adm précédente. $v1['nbVote'] > $eluLos['nbVote']
			* Si oui, Remplacer les information de $eluLos $eluLos['amd'].
	* Calculer le pourcentage de votes par a port au citoyens `$px = 100*$cmp/$tmp['obs']['CITOYEN'][nb]`
		* Si $px est sup a 50%, alors $elu = 1, Si non $elu = O.
	* Boucle sur la var `$amdAr AS $k1 => $v1`.
		* Calculer le pourcentage de votes par a port au total `$pxa = 100*$v1['myVote']/$cmp`.
			* Ajouter le pourcentage par amd a `$amdAr[$k1]['px'] = $pxa`.
	* Ajouter à la réponse de retour, les infos des lois.
		
		```php
		$tmp['obs']['lois'][list][] = array {
			'id' : $v['id'],
			'loi' : $v['nom'],
			'nbAmd' : $nbAmd,
			'elu' : $elu
			'px' : $px,
			'amdElu' : $eluLos['amd'],
			'myVote' : $myLoisV,
			'amd' : $amdAr
		};
		```

11. Poste élu et les fonctions propriétaires. Vérifier.
	* Si l'utilisateur est ADMIN.
		* Boucle sur la table func `$dbs['func'] AS $k => $v`.
			* Si `$v['id']` est 0. Ajouter le nom de la fonction a `$tmp['admin'][] = array($v['name'] => 1)`
	* Si l'utilisateur est CITOYEN.

**Informations sortantes**

```js
{
	'guest' : 1, // L'utilisateur n'est pas encore validé.
	'banni' : 1, // L'utilisateur est banni.
	'citoyen' : 1, 
	'info' : { // Variable $user or 0
		'id' : // L'identifiant unique crée par l'application.
		'adr' : // Identifiant client (adresse bitcoin).
		'nom' : // Le nom du client.
		'prenom' : // Le prénom du client.
		'date' : // La date d'inscription.
		'role' : // Le rôle de l'utilisateur.
	},
	'obs' : {
		'CITOYEN' : { // + admin dans la liste.
			'nb' : // Le nombre d'utilisateur dans list.
			'list' : [
				[0] : {
					'id' : // L'identifiant unique crée par l'application.
					'adr' : // Identifiant client (adresse bitcoin).
					'nom' : // Le nom du client.
					'prenom' : // Le prénom du client.
					'date' : // La date d'inscription.
					'role' : // Le rôle de l'utilisateur.
				} [1] //...
			]
		}
		'GUEST' : {…} // Liste des invités.
		'BANNI' : {…} // Liste des bannis.
		'OBS' : {…} // Liste des observateurs.
		'postes' : {
			'nb' : // Le nombre de postes dans list.
			'list' : [
				[0] : {
					'id' // Identifiant poste.
					'poste' // Le nom du poste.
					'id_elu' // L'identifiant unique du client élu.
					'nomElu' // Le nom du client élu.
					'prenomElu' // Le prénom du client élu.
					'myVote' // L'identifiant unique du client voter.
					'myVoteName' // Le nom du client voter.
					'myVotePrenom' // Le prénom du client voter.
				} [1] //...
			]
		}
		'lois' : {
			'nb' : // Le nombre d'utilisateur dans list.
			'list' : [
				[0] : {
					'id' : // Identifiant loi.
					'loi' : // Le nom de la loi.
					'nbAmd' : // le nombre d'amendements.
					'elu' : // 1 ou 0
					'px' : // 0 a 100.
					'amdElu' : // La desc de l'amendement élu.
					'myVote' : // 0 ou id amd.
					'amd' : [
						[0] : {
							'id' : // Identifiant d'amendement.
							'desc' : // La desc de l'amendement.
							'px' : // 0 a 100.
							'nbVote' : // Nombre de votes pour l'amendement.
							'myVote' : // Si mon vote.
						} [1] //...
				} [1] //...
			]
		}
	}
	'admin' : [
		{'addPoste' : 1}
		{'deletePoste' : 1}
		{'editeRole' : 1}
		{…}
	]
	'log' : {
		'nb' : // Le nombre d'actions dans le log.
		'list' : [
			[0] : {
				'id_user' : // L'identifiant unique crée par l'application.
				'nom' : // Le nom de l'utilisateur.
				'prenom' : // Le prénom de l'utilisateur.
				'action' : // L'action de l'historique.
				'date' : // La date de l'action.
				'msg' : // Le message de l'action.
			} [1] //...
		]
	}
}
```

***

### Ω user_sign($a, $n, $p, $s)
> inscription de l'utilisateur.

**Informations entrantes**

| param | Type | Desc |
|-------|------|------|
| $a | string | Identifiant client (adresse bitcoin). |
| $n | string | Nom. |
| $p | string | Prénom. |
| $s | string | Signiature (hash nom+prénom+Identifiant). |

**Règles de gestion**

1. Vérification que nom `$n` et prénom `$p` son des alpha ou lever une exception. `ERR-NAME-OR-FIRSTNAME-INVALID`
2. Récupérer les donnés utilisateur avec helper. Vérifier, si utilisateur, lever une exception. `ERR-ACCOUNT-ALREADY-EXISTS`
	
	```php
	// Appel de la fonction helper dans un if.
	if($user = help::user($a, $n.$p, $s)) throw new Exception('ERR-ACCOUNT-ALREADY-EXISTS');
	```
3. Enregistrait l'utilisateur.
	
	```php
	// Crée un tableau contenant l'identifiant client, nom, prénom, date, rôle.
	$req = array(
		'adr' => $a,
		'nom' => $n,
		'prenom' => $p,
		'date' => // Timestamp actuel
		'role' => 'GUEST'
	);
	
	// Appel a la fonction du model.
	dbs::setUser($req);
	``` 
4. Récupérer les donnés utilisateur avec helper. Vérifier, si l'utilisateur est enregistrait ou lever une exception. `ERR-ECHEC-SAVE-USER`
	
	```php
	// Appel de la fonction helper dans un if.
	if(!$user = help::user($a, $n.$p, $s)) throw new Exception('ERR-ECHEC-SAVE-USER');
	```
5. Encode en string json le contenu de la variable `$user`
6. Sauvegardait l'action dans l'historique.
	
	```php
	// Crée un tableau contenant l'id user, l'action, date, jdata.
	$req1 = array(
		'id_user' => // id retourner par $user['id'],
		'action' => 'SAVE',
		'date' => // Timestamp actuel
		'jdata' => // string json $user
	);
	
	// Appel a la fonction du model.
	dbs::setLog($req1);
	```
7. Construire et retourner le tableau final.

**Informations sortantes**

```js
{ // Variable $user
	'id' : // L'identifiant unique crée par l'application.
	'adr' : // Identifiant client (adresse bitcoin).
	'nom' : // Le nom du client.
	'prenom' : // Le prénom du client.
	'date' : // La date d'inscription.
	'role' : // Le rôle de l'utilisateur.
}
```

***
	
### Ω etat_addPoste($a, $p, $s)
> Ajouter un nouveaux poste. Fonction propriétaire : **addPoste**

**Informations entrantes**

| param | Type | Desc |
|-------|------|------|
| $a | string | Identifiant client (adresse bitcoin). |
| $p | string | Poste. |
| $s | string | Signiature (hash poste+Identifiant). |

**Règles de gestion**

1. Vérification que poste `$p` est alpha ou lever une exception. `ERR-POSTE-INVALID`
2. Récupérer les donnés utilisateur avec helper. Vérifier, si pas d'utilisateur, lever une exception. `ERR-USER-NOT-EXISTS`
	
	```php
	// Appel de la fonction helper dans un if.
	if(!$user = help::user($a, $p, $s)) throw new Exception('ERR-USER-NOT-EXISTS');
	```

3. Vérification du rôle de l'utilisateur.
	
	```php
	// Appel de la fonction Accés Controle Level.
	if(!help::acl($user, 'addPoste')) throw new Exception('ERR-USER-NOT-ACCESS');
	```

4. Vérifier que le poste n'existe pas déjà dans la base de données.
	
	```php
	// Crée un tableau contenant le poste.
	$reqGet = array('poste' => $p);
	
	// Appel de la fonction model
	$poste = dbs::getPosteByName($reqGet);
	```
	
5. Vérification, si `$poste` n'est pas vide, alors lever une exception. `ERR-POSTE-ALREADY-EXISTS`
6. Enregistrait le poste.

	```php
	// Crée un tableau contenant le poste et la date.
	$req = array(
		'poste' => $p,
		'date' => // Timestamp actuel
	);
	
	// Appel a la fonction du model.
	dbs::setPoste($req);
	```
7. 	Vérifier que le poste fut bien ajouter a la base de données.

	```php
	// Appel de la fonction model
	$poste = dbs::getPosteByName($reqGet);
	```

8. Vérification, si `$poste` est vide, alors lever une exception. `ERR-ECHEC-SAVE-POSTE`

9. Encode en string json le contenu de la variable `$poste`
10. Sauvegardait l'action dans l'historique.
	
	```php
	// Crée un tableau contenant l'id user, l'action, date, jdata.
	$req1 = array(
		'id_user' => // id retourner par $user['id'],
		'action' => 'ADDPOSTE',
		'date' => // Timestamp actuel
		'jdata' => // string json $poste
	);
	
	// Appel a la fonction du model.
	dbs::setLog($req1);
	```
11. Construire et retourner le tableau final.

**Informations sortantes**

```js
{
	'id' :  // L'identifiant unique du poste.
	'poste' :  // Le nom du poste.
	'date' :  // La date de la creation du poste.
}
```

***

### Ω etat_deletePoste($a, $p, $s)
> Suppression du poste. Fonction propriétaire : **deletePoste**

**Informations entrantes**

| param | Type | Desc |
|-------|------|------|
| $a | string | Identifiant client (adresse bitcoin). |
| $p | int | Identifiant Poste. |
| $s | string | Signiature (hash id_poste+Identifiant). |

**Règles de gestion**

1. Vérification que id poste `$p` est int ou lever une exception. `ERR-POSTE-INVALID`
2. Récupérer les donnés utilisateur avec helper. Vérifier, si pas d'utilisateur, lever une exception. `ERR-USER-NOT-EXISTS`
	
	```php
	// Appel de la fonction helper dans un if.
	if(!$user = help::user($a, $p, $s)) throw new Exception('ERR-USER-NOT-EXISTS');
	```

3. Vérification du rôle de l'utilisateur.
	
	```php
	// Appel de la fonction Accés Controle Level.
	if(!help::acl($user, 'deletePoste')) throw new Exception('ERR-USER-NOT-ACCESS');
	```

4. Vérifier que le poste existe dans la base de données.
	
	```php
	// Crée un tableau contenant le poste.
	$req = array('id' => $p);
	
	// Appel de la fonction model
	$poste = dbs::getPosteById($req);
	```
	
5. Vérification, si `$poste` est vide, alors lever une exception. `ERR-POSTE-NOT-EXISTS`
6. Suppression du poste, des votes et des fonctions associer.

	```php
	// Appel de la fonction model
	dbs:deletePoste($req);
	```

7. Encode en string json le contenu de la variable `$poste`
10. Sauvegardait l'action dans l'historique.
	
	```php
	// Crée un tableau contenant l'id user, l'action, date, jdata.
	$req1 = array(
		'id_user' => // id retourner par $user['id'],
		'action' => 'DELETEPOSTE',
		'date' => // Timestamp actuel
		'jdata' => // string json $poste
	);
	
	// Appel a la fonction du model.
	dbs::setLog($req1);
	```
11. Construire et retourner le tableau final.

**Informations sortantes**

```js
{
	'id' :  // L'identifiant unique du poste.
	'poste' :  // Le nom du poste.
	'date' :  // La date de la creation du poste.
	'nb' : // Le nombre de vote.
}
```

***

### Ω etat_editeRole($a, $r, $u, $s)
> Editer le rôle de l'utilisateur. Fonction propriétaire : **editeRole**

**Informations entrantes**

| param | Type | Desc |
|-------|------|------|
| $a | string | Identifiant client (adresse bitcoin). |
| $r | string | Rôle. |
| $u | int | Id user. |
| $s | string | Signiature (hash Role+idUser+Identifiant). |

**Règles de gestion**

1. Vérification que `$r` est [int], le rôle `$r` est `BANNI` ou `OBS` ou `CITOYEN`. Si aucun des trois, lever une exception. `ERR-ROLE-INVALID`
2. Récupérer les donnés utilisateur avec helper. Vérifier, si pas d'utilisateur, lever une exception. `ERR-USER-NOT-EXISTS`
	
	```php
	// Appel de la fonction helper dans un if.
	if(!$user = help::user($a, $r.$u, $s)) throw new Exception('ERR-USER-NOT-EXISTS');
	```

3. Vérification du rôle de l'utilisateur.
	
	```php
	// Appel de la fonction Accés Controle Level.
	if(!help::acl($user, 'editeRole')) throw new Exception('ERR-USER-NOT-ACCESS');
	```

4. Recherche de l'utilisateur dans la base de données par l'id user.
	
	```php
	// Crée un tableau contenant l'identifiant client.
	$req = array('adr' => $a);
	
	// Appel a la fonction du model.
	$client = dbs::getUserById($req);
	```

5. Comparer les deux rôles, si identique lever une exception. `ERR-ROLE-INVALID`

6. Vérifier le rôle du client.
	* Si administrateur, lever une exception. `ERR-NOT-CHANGE-ADMIN`
	* Si citoyen, récupérait ses propre votes, puis les votes effectue pour lui.
		* Récupérait les votes effectuer par le citoyen.
	
			```php
			// Appel a la fonction du helper.
			$vote = help::userVote($client['id']);
			```

7. Modifier le rôle du client.
	* Si citoyen, supprimé ses propre votes, puis supprimé les votes effectue pour lui.



	
8. Encode en string json le contenu de la variable `$role`
9. Sauvegardait l'action dans l'historique.
	
	```php
	// Crée un tableau contenant l'id user, l'action, date, jdata.
	$req1 = array(
		'id_user' => // id retourner par $user['id'],
		'action' => 'EDITEROLE',
		'date' => // Timestamp actuel
		'jdata' => // string json $role
	);
	
	// Appel a la fonction du model.
	dbs::setLog($req1);
	```
10. Construire et retourner le tableau final.

**Informations sortantes**

```js
{
	//...
}
```

***

### Ω vote_send($a, $d1, $d2, $t)
> Permet d'enregistrer la première phase du vote.

**Informations entrantes**

| param | Type | Desc |
|-------|------|------|
| $a | string | Identifiant client (adresse bitcoin). |
| $d1 | int | Le premier identifiant du vote. |
| $d2 | int | Le deuxième identifiant du vote. |
| $t | string | Le type de vote. |

**Règles de gestion**

1. Vérification que `$d1` et `$d2` sont [int], le type est `CTN` ou `LOS`. Si non, lever une exception. `ERR-VAR-VOTE-INVALID`

2. Recherche de l'utilisateur dans la base de données.
	1. Vérifier la validité de l'adresse bitcoin `$a` ou lever une exception. `ERR-BTC-ADR-INVALID`.
	2. Recherche de l'utilisateur dans la base de données par l'identifiant client.
	
		```php
		// Crée un tableau contenant l'identifiant client.
		$req = array('adr' => $a);
		
		// Appel a la fonction du model.
		$user = dbs::getUserByBtc($req);
		```
	
	3. Vérifier la présence de l'utilisateur si non lever une exception. `ERR-USER-NOT-EXISTS`.
	
3. Vérification du rôle de l'utilisateur `$user['role']`.
	* Si citoyen ou administrateur, alors poursuivre. si non lever une exception. `ERR-USER-NOT-ACCESS`.

4. Récupérait les votes effectuer par le citoyen.
	
	```php
	// Appel a la fonction du helper.
	$vote = help::userVote($user['id']);
	```

5. Lancer une boucle sur $vote et compare la présence `$d1` avec le type.
	* Si il y a une correspondance
		* Supprimer le vote dans la base de données.
		
		```php
		// Crée un tableau contenant l'identifiant client.
		$req = array('id' => $vote['id']);
		
		// Appel a la fonction du model.
		dbs::deleteVote($req);
		```

6. Vérifier le type de vote, et l'existance des ids dans la base de données.
	
7. Crée un hash du vote. Sauvegardait le vote dans la base de données.

	```php
	// Crée un tableau contenant l'id vote, id1, id2, type, date.
	$req1 = array(
		'id' => // Hash id1 id2 type date
		'id1' => 
		'id2' => 
		'type' => // type de vote
		'date' => Timestamp actuel.
		'signe' => 0 // Non signé.
	);
	
	// Appel a la fonction du model.
	dbs::setVote($req);
	```

8. Construire et retourner le Hash (id+id1+id2+type) pour la signiature du vote.

**Informations sortantes**

```js
{
	'id' :  // Id du vote.
	'hash' :  // Hash pour la signature.
}
```

***

### Ω vote_fix($a, $d, $ds, $s)
> Permet de confirmer son vote.

**Informations entrantes**

| param | Type | Desc |
|-------|------|------|
| $a | string | Identifiant client (adresse bitcoin). |
| $d | string | L'identifiant du vote. |
| $ds | string | L'identifiant du vote crypter. |
| $s | string | Signiature (hash id+d1+id2+type). |

**Règles de gestion**

1. Vérification que `$d` est [hash], lever une exception. `ERR-VAR-VOTE-INVALID`

2. Recherche de l'utilisateur dans la base de données.
	1. Vérifier la validité de l'adresse bitcoin `$a` ou lever une exception. `ERR-BTC-ADR-INVALID`.
	2. Recherche de l'utilisateur dans la base de données par l'identifiant client.
	
		```php
		// Crée un tableau contenant l'identifiant client.
		$req = array('adr' => $a);
		
		// Appel a la fonction du model.
		$user = dbs::getUserByBtc($req);
		```
	
	3. Vérifier la présence de l'utilisateur si non lever une exception. `ERR-USER-NOT-EXISTS`.
	
3. Vérification du rôle de l'utilisateur `$user['role']`.
	* Si citoyen ou administrateur, alors poursuivre. si non lever une exception. `ERR-USER-NOT-ACCESS`.

4. Suppression des votes non Sagnier dépassant deux minutes dans la base de données.

	```php
	// Crée un tableau contenant l'identifiant client.
	$req = array('date' => // Timestamp - 2 mn);
	
	// Appel a la fonction du model.
	dbs::deleteVoteByTimes($req);
	```

5. Recherche du vote dans la base de données.

	```php
	// Crée un tableau contenant l'identifiant vote.
	$req = array('id' => $d);
	
	// Appel a la fonction du model.
	$vote = dbs::getVoteById($req);
	```
	
4. Vérifier le hash et la signature avec $vote. si non lever une exception. `ERR-VAR-VOTE-INVALID`.

6. Sauvegardait la signature dans la base de données.

	```php
	// Crée un tableau contenant l'identifiant vote et la signature.
	$req = array('id' => $d,
		'signe' => $s);
	
	// Appel a la fonction du model.
	$vote = dbs::setSigneInVote($req);
	```	
	
8. Encode en string json le contenu de la variable `$v` contenant le hash et le contenu du vote crypter `$ds`.
9. Sauvegardait l'action dans l'historique.
	
	```php
	// Crée un tableau contenant l'id user, l'action, date, jdata.
	$req1 = array(
		'id_user' => // id retourner par $user['id'],
		'action' => 'VOTE',
		'date' => // Timestamp actuel
		'jdata' => // string json $v
	);
	
	// Appel a la fonction du model.
	dbs::setLog($req1);
	```
10. Construire et retourner le tableau final.

**Informations sortantes**

```js
{
	//...
}
```

***

### Ω addLois
> Ajouter une nouvelle loi.

* **Informations entrantes**
	* Identifiant client (adresse bitcoin)
	* nom
	* Signiature (hash 'Loi'+Identifiant)
* **Règles de gestion**
	1. Vérification des données entrante.
	2. Recherche de l'utilisateur dans la base de données.
	3. Vérification du rôle de l'utilisateur.
		* Si citoyen ou administrateur, alors poursuivre.
	4. Enregistrait la loi.
	5. Sauvegardait l'action d'ans l'historique.
	6. Sélectionner toutes les données de connexion (`login` 4-11).
* **Informations sortantes**
	* Les données seront retournées comme dans `upData`.

### Ω addAmd
> Ajouter un nouveaux amendement.

* **Informations entrantes**
	* Identifiant client (adresse bitcoin)
	* amendement
	* id loi
	* Signiature (hash 'Loi'+'amendement'+Identifiant)
* **Règles de gestion**
	1. Vérification des données entrante.
	2. Recherche de l'utilisateur dans la base de données.
	3. Vérification du rôle de l'utilisateur.
		* Si citoyen ou administrateur, alors poursuivre.
	4. Enregistrait l'amendement.
	5. Sauvegardait l'action d'ans l'historique.
	6. Sélectionner toutes les données de connexion (`login` 4-11).
* **Informations sortantes**
	* Les données seront retournées comme dans `upData`.

### Ω editeLois
> Editer une loi.

* **Informations entrantes**
	* Identifiant client (adresse bitcoin)
	* nom
	* id loi
	* Signiature (hash idLoi+'nom'+Identifiant)
* **Règles de gestion**
	1. Vérification des données entrante.
	2. Recherche de l'utilisateur dans la base de données.
	3. Vérification du rôle de l'utilisateur.
		* Si administrateur, alors poursuivre.
		* Si citoyen, vérifier les poste est les élus.
	4. Modifier la loi.
	5. Sauvegardait l'action d'ans l'historique.
	6. Sélectionner toutes les données de connexion (`login` 4-11).
* **Informations sortantes**
	* Les données seront retournées comme dans `upData`.

### Ω editeAmd
> Editer un amendement.

* **Informations entrantes**
	* Identifiant client (adresse bitcoin)
	* nom
	* id loi
	* id Amd
	* Signiature (hash idLoi+idAmd+'nom'+Identifiant)
* **Règles de gestion**
	1. Vérification des données entrante.
	2. Recherche de l'utilisateur dans la base de données.
	3. Vérification du rôle de l'utilisateur.
		* Si administrateur, alors poursuivre.
		* Si citoyen, vérifier les poste est les élus.
	4. Modifier l'amendement.
	5. Sauvegardait l'action d'ans l'historique.
	6. Sélectionner toutes les données de connexion (`login` 4-11).
* **Informations sortantes**
	* Les données seront retournées comme dans `upData`.

### Ω deleteLoi
> Suppression d'une loi.

* **Informations entrantes**
	* Identifiant client (adresse bitcoin)
	* id loi
	* Signiature (hash idLoi+Identifiant)
* **Règles de gestion**
	1. Vérification des données entrante.
	2. Recherche de l'utilisateur dans la base de données.
	3. Vérification du rôle de l'utilisateur.
		* Si administrateur, alors poursuivre.
		* Si citoyen, vérifier les poste est les élus.
	4. Suppression de la loi.
		* Suppression des votes.
	5. Sauvegardait l'action d'ans l'historique.
	6. Sélectionner toutes les données de connexion (`login` 4-11).
* **Informations sortantes**
	* Les données seront retournées comme dans `upData`.

### Ω deleteAmd
> Suppression d'un amendemente.

* **Informations entrantes**
	* Identifiant client (adresse bitcoin)
	* id loi
	* id Amd
	* Signiature (hash idLoi+idAmd+Identifiant)
* **Règles de gestion**
	1. Vérification des données entrante.
	2. Recherche de l'utilisateur dans la base de données.
	3. Vérification du rôle de l'utilisateur.
		* Si administrateur, alors poursuivre.
		* Si citoyen, vérifier les poste est les élus.
	4. Suppression de l'amendemente.
		* Suppression des votes.
	5. Sauvegardait l'action d'ans l'historique.
	6. Sélectionner toutes les données de connexion (`login` 4-11).
* **Informations sortantes**
	* Les données seront retournées comme dans `upData`.

### Ω upData
> Mise à jour des données toutes les minutes.

* **Informations entrantes**
	* Identifiant client (adresse bitcoin)
	* Timestamp
	* Signiature (hash Timestamp+Identifiant)
* **Règles de gestion**
	1. Vérification des données entrante. Timestamp dans les 12h du timestamp serveur.
	2. Recherche de l'utilisateur dans la base de données.
	3. Vérification du rôle de l'utilisateur.
		* Si Banni. Retourner la reponse.
		* Si Guest. Retourner la reponse.
	4. Sélectionner toute la base de données.
	5. Boucle sur la table utilisateur.
		* Séparer les utilisateurs par rôle.
		* Compter les utilisateurs par rôle.
	6. Boucle sur la table vote.
		* Séparer les votes par type.
			* Type poste
				* Vérifier le poste et l'utilisateur choisi.
				* Incrémenter la variable de vote des postes.
			* Type loi
				* Vérifier la loi et l'amendement choisi.
				* Incrémenter la variable de vote des lois.
	7. Boucle sur la variable vote poste.
		* Déterminer une liste de postes avec leurs utilisateurs élus. Commencer par le début de la liste, si l'utilisateur est déjà élu dans un poste précédant, alors choisir la personne en second élu pour le poste.
	8. Boucle sur la variable vote loi.
		* Déterminer une liste de lois avec leurs amendements élus.
	9. Boucle sur la variable de l'historique.
		* Marquer les actions du client.
	10. Vérifier si le client appartient à un poste élu.
	11. Si Administrateur ou poste. Inclure les variables dans le retour.
* **Informations sortantes**
	* Banni
	* Guest
	* Observateur
	* Citoyen
	* Administrateur

***

*Mercredi 25 Février 2015*