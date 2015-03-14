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
	
3. Vérification du rôle de l'utilisateur.
	* Si Banni. retourner la variable `$tmp['banni'] = 1`.
	* Si Guest. retourner la variable `$tmp['guest'] = 1`.
	
4. Récupérer les donnés avec helper.
	```php
	// Appel de la fonction helper.
	$tmp = help::getData($user);
	```

5. Ajouter à la réponse de retour, les info de l'utilisateur `$tmp['info'] = $user`.
	* Si citoyen ou admin. ajouter `$tmp['citoyen'] = 1`.

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

## Ω user_upData($a, $t, $s)
> Mise à jour des données toutes les minutes.

**Informations entrantes**

| param | Type | Desc |
|-------|------|------|
| $a | string | Identifiant client (adresse bitcoin). |
| $t | int | nombre d'actions in log. |
| $s | string | Signiature (hash sha1 actions+Identifiant). |

**Règles de gestion**

1. Vérification que `$t` est number ou lever une exception. `ERR-VAR-INVALID`
2. Récupérer les donnés utilisateur avec helper. Vérifier, si pas d'utilisateur, lever une exception. `ERR-USER-NOT-EXISTS`.
	
	```php
	// Appel de la fonction helper dans un if.
	if(!$user = help::user($a, $t, $s)) throw new Exception('ERR-USER-NOT-EXISTS');
	```

3. Vérification du rôle de l'utilisateur.
	* Si Guest ou banni. lever une exception. `ERR-USER-NOT-ACCESS`.
	
4. Compter le nombrer d'action dans le log.
	
	```php
	// Appel a la fonction du model.
	$nb = dbs::getNbLog();
	```
5. Comparér le $nb a $t. Si les nombrer de log on une correspondance.
	* Si correspondance. Retourner `$tmp['upData'] = 0`.
	* Si pas de correspondance.
		* Récupérer les donnés avec helper.
			
			```php
			// Appel de la fonction helper.
			$tmp = help::getData($user);
			```
		* Ajouter la variable `$tmp['info'] =  $user`.
		* Si citoyen ou admin. ajouter `$tmp['citoyen'] = 1`.
		* Ajouter `$tmp['upData'] = 1`.

**Informations sortantes**

```js
{
	'upData' : 1 // Si'il y a une mise a jour.
	//... Idem user_login()			
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
	'postes' : {
		'id' $poste['id']
		'poste' $poste['poste']
		'id_elu' 0
		'myVote' 0
	}
	'log' : {
		'id_user' : $user['id']
		'nom' : $user['nom']
		'prenom' : $user['prenom']
		'action' : $req1['action']
		'date' : $req1['date']
		'msg' : help::getMsg($req1['jdata'])
	}
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
	'postes' : $p
	'log' : {
		'id_user' : $user['id']
		'nom' : $user['nom']
		'prenom' : $user['prenom']
		'action' : $req1['action']
		'date' : $req1['date']
		'msg' : help::getMsg($req1['jdata'])
	}
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
	'id' $client['id']
	'last' BANNI
	'new' OBS
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

### Ω vote_fix($a, $d, $s)
> Permet de confirmer son vote.

**Informations entrantes**

| param | Type | Desc |
|-------|------|------|
| $a | string | Identifiant client (adresse bitcoin). |
| $d | string | L'identifiant du vote. |
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
	'id1' $vote['id1']
	'id2' $vote['id2']
	'type' CNT
}
```

***

### Ω lois_addLois($a, $n, $amd, $s)
> Ajouter une nouvelle loi.

**Informations entrantes**

| param | Type | Desc |
|-------|------|------|
| $a | string | Identifiant client (adresse bitcoin). |
| $n | string | Le nom de la loi. |
| $amd | string | Le premier amendement. |
| $s | string | Signiature (hash id+d1+id2+type). |

**Règles de gestion**

1. Vérification que la loi `$n, $amd` est alpha ou lever une exception. `ERR-VAR-INVALID`
2. Récupérer les donnés utilisateur avec helper. Vérifier, si pas d'utilisateur, lever une exception. `ERR-USER-NOT-EXISTS`
	
	```php
	// Appel de la fonction helper dans un if.
	if(!$user = help::user($a, $n.$amd, $s)) throw new Exception('ERR-USER-NOT-EXISTS');
	```

3. Vérification du rôle de l'utilisateur `$user['role']`.
	* Si citoyen ou administrateur, alors poursuivre. si non lever une exception. `ERR-USER-NOT-ACCESS`.

4. Vérifier que la loi n'existe pas déjà dans la base de données.
	
	```php
	// Crée un tableau contenant le poste.
	$reqGet = array('nom' => $n);
	
	// Appel de la fonction model
	$loi = dbs::getLoiByName($reqGet);
	```
	
5. Vérification, si `$loi` n'est pas vide, alors lever une exception. `ERR-LOI-ALREADY-EXISTS`
6. Enregistrait la loi.

	```php
	// Crée un tableau contenant la loi.
	$req = array(
		'nom' => $n,
	);
	
	// Appel a la fonction du model.
	dbs::setLoi($req);
	```
7. 	Vérifier que la loi fut bien ajouter a la base de données.

	```php
	// Appel de la fonction model
	$loi = dbs::getLoiByName($reqGet);
	```

8. Vérification, si `$loi` est vide, alors lever une exception. `ERR-ECHEC-SAVE-LOI`

9. Enregistrait le premier amendement.

	```php
	// Crée un tableau contenant le id de la loi et l'amd.
	$req = array(
		'id_lois' => $loi['id'],
		'amd' => $amd,
	);
	
	// Appel a la fonction du model.
	dbs::setAmd($req);
	```

10. Vérifier que l'amendement fut bien ajouter a la base de données.

	```php
	// Appel de la fonction model
	$dbAmd = dbs::getAmdByText($reqGet);
	```

11. Vérification, si `$dbAmd` est vide, alors : 
	* Supprimé, la loi précédemment ajoutée. `dbs::deletLoi($e);`
	* Lever une exception. `ERR-ECHEC-SAVE-LOI`.

12. Encode en string json le contenu de la variable `$loi, $dbAmd`
13. Sauvegardait l'action dans l'historique.
	
	```php
	// Crée un tableau contenant l'id user, l'action, date, jdata.
	$req1 = array(
		'id_user' => // id retourner par $user['id'],
		'action' => 'ADDLOIS',
		'date' => // Timestamp actuel
		'jdata' => // string json $loi, $dbAmd
	);
	
	// Appel a la fonction du model.
	dbs::setLog($req1);
	```
14. Construire et retourner le tableau final.

**Informations sortantes**

```js
{
	'lois' : {
		'id' : // Identifiant loi.
		'loi' : // Le nom de la loi.
		'nbAmd' : // le nombre d'amendements.
		'elu' : 0
		'px' : 0
		'amdElu' : 0
		'myVote' : 0
		'amd' : [
			[0] : {
				'id' : // Identifiant d'amendement.
				'desc' : // La desc de l'amendement.
				'px' : 0
				'nbVote' : 0
				'myVote' : 0
			} [1] //...
	}
	'log' : {
		'id_user' : $user['id']
		'nom' : $user['nom']
		'prenom' : $user['prenom']
		'action' : $req1['action']
		'date' : $req1['date']
		'msg' : help::getMsg($req1['jdata'])
	}
}
```

### Ω lois_addAmd($a, $l, $amd, $s)
> Ajouter un nouveaux amendement.

**Informations entrantes**

| param | Type | Desc |
|-------|------|------|
| $a | string | Identifiant client (adresse bitcoin). |
| $l | int | Le id de la loi. |
| $amd | string | Le premier amendement. |
| $s | string | Signiature (hash $l+$amd+$a). |

**Règles de gestion**

1. Vérification que la loi `$amd` est alpha et `$l` est INT ou lever une exception. `ERR-VAR-INVALID`
2. Récupérer les donnés utilisateur avec helper. Vérifier, si pas d'utilisateur, lever une exception. `ERR-USER-NOT-EXISTS`
	
	```php
	// Appel de la fonction helper dans un if.
	if(!$user = help::user($a, $l.$amd, $s)) throw new Exception('ERR-USER-NOT-EXISTS');
	```

3. Vérification du rôle de l'utilisateur `$user['role']`.
	* Si citoyen ou administrateur, alors poursuivre. si non lever une exception. `ERR-USER-NOT-ACCESS`.

4. Vérifier que la loi existe déjà dans la base de données.
	
	```php
	// Crée un tableau contenant le poste.
	$reqGet = array('id' => $l);
	
	// Appel de la fonction model
	$loi = dbs::getLoiById($reqGet);
	```
	
5. Vérification, si `$loi` est vide, alors lever une exception. `ERR-LOI-NOT-EXISTS`

6. Enregistrait l'amendement.

	```php
	// Crée un tableau contenant la loi.
	$req = array(
		'id_lois' => $l,
		'amd' => $amd,
	);
	
	// Appel a la fonction du model.
	dbs::setAmd($req);
	```

10. Vérifier que l'amendement fut bien ajouter a la base de données.

	```php
	// Appel de la fonction model
	$dbAmd = dbs::getAmdByText($reqGet);
	```

11. Vérification, si `$dbAmd` est vide, alors : 
	* Lever une exception. `ERR-ECHEC-SAVE-AMD`.
12. Encode en string json le contenu de la variable `$dbAmd`
13. Sauvegardait l'action dans l'historique.
	
	```php
	// Crée un tableau contenant l'id user, l'action, date, jdata.
	$req1 = array(
		'id_user' => // id retourner par $user['id'],
		'action' => 'ADDAMD',
		'date' => // Timestamp actuel
		'jdata' => // string json id_loi, node de la loi, $dbAmd
	);
	
	// Appel a la fonction du model.
	dbs::setLog($req1);
	```
14. Construire et retourner le tableau final.

**Informations sortantes**

```js
{
	'id_loi' : // Identifiant de la loi.
	'nom' : // le nom de la loi.
	'amd' : {
		'id' : // Identifiant d'amendement.
		'desc' : // La desc de l'amendement.
		'px' : 0
		'nbVote' : 0
		'myVote' : 0
	}
	'log' : {
		'id_user' : $user['id']
		'nom' : $user['nom']
		'prenom' : $user['prenom']
		'action' : $req1['action']
		'date' : $req1['date']
		'msg' : help::getMsg($req1['jdata'])
	}
}
```

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

***

*Mercredi 25 Février 2015*