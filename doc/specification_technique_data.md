# Spécification technique Data (STB V.0.1)
> Le projet est constitué d'une api côté serveur et d'une application web côté client. Nous commençons ici par les fonctions du modèle et la base de données.

![SQL architecture](annexes/sqlArchitect.png)

***

## ∑ Model SQL dbs
> Regroupe les fonctions en rapport avec la table user.

### Ω dbs::getUserByBtc($e)
> Retourne la table de l'utilisateur trouver par son identifiant (adresse bitcoin).

**Informations entrantes**

| param | Type | Desc |
|-------|------|------|
| $e | array | Un tableau contenant l'identifiant de l'utilisateur. |

**Règles de gestion**

En cas d'erreur, lever une exception `ERR-MODEL-DATABASE`.
```php
db::go('SELECT * FROM apso_user WHERE adr=:adr');
```

**Informations sortantes**
```php
Array {
	[id] = // L'identifiant unique crée par l'application.
	[adr] = // Identifiant client (adresse bitcoin).
	[nom] = // Le nom du client.
	[prenom] = // Le prenom du client.
	[date] = // La date d'inscription.
	[role] = // Le rôle de l'utilisateur.
}
```

### Ω dbs::setUser($e)
> Ajoute un nouvel utilisateur dans la base de données.

**Informations entrantes**

| param | Type | Desc |
|-------|------|------|
| $e | array | Un tableau contenant l'identifiant client, nom, prénom, date, rôle. |

**Règles de gestion**

En cas d'erreur, lever une exception `ERR-MODEL-DATABASE`.
```php
db::go('INSERT INTO apso_user VALUES("", :adr, :nom, :prenom, :date, :role)');
```

### Ω dbs::setLog($e)
> Ajoute un nouvel historique dans la base de données. Retourne void.

**Informations entrantes**

| param | Type | Desc |
|-------|------|------|
| $e | array | Un tableau contenant l'id user, l'action, date, jdata. |

**Règles de gestion**

En cas d'erreur, lever une exception `ERR-MODEL-DATABASE`.
```php
db::go('INSERT INTO apso_log VALUES("", :id_user, :action, :date, :jdata)');
```

### Ω dbs::getPrivFunc($e)
> Retourne la fonction propriétaire accompagner des postes assignés et leurs résultats de vote.

**Informations entrantes**

| param | Type | Desc |
|-------|------|------|
| $e | array | Un tableau contenant le nom de la fonction propriétaire. |

En cas d'erreur, lever une exception `ERR-MODEL-DATABASE`.
```php
db::go('SELECT f.name, p.id, v.id1, COUNT(v.id2) nb,
	FROM apso_func f 
	RIGHT JOIN apso_poste p 
	ON f.id_poste = p.id 
	INNER JOIN apso_vote v 
	ON p.id = v.id2 AND v.type = "ctn"
	WHERE f.name=:name 
	GROUP BY v.id1 
	ORDER BY p.id DESC, nb ASC');
```

**Informations sortantes**

```php
Array [
	[0] = Array [
		[name] = // Le nom de la fonction propriétaire. NULL MULTI
		[id] = // Identifiant du poste associé. MULTI
		[id1] = // Identifiant du client élu.
		[nb] = // Nombre de voix obtenues.
	]
	[1] = // ...
]
```

### Ω dbs::setPoste($e)
> Ajoute un nouveau poste dans la base de données.

**Informations entrantes**

| param | Type | Desc |
|-------|------|------|
| $e | array | Un tableau contenant le poste et la date. |

**Règles de gestion**

En cas d'erreur, lever une exception `ERR-MODEL-DATABASE`.
```php
db::go('INSERT INTO apso_poste VALUES("", :poste, :date)');
```

### Ω dbs::getPosteByName($e)
> Retourne la table du poste trouver par son nom (poste).

**Informations entrantes**

| param | Type | Desc |
|-------|------|------|
| $e | array | Un tableau contenant le nom du poste. |

**Règles de gestion**

En cas d'erreur, lever une exception `ERR-MODEL-DATABASE`.
```php
db::go('SELECT * FROM apso_poste WHERE poste=:poste');
```

**Informations sortantes**

```php
Array {
	[id] = // L'identifiant unique du poste.
	[poste] = // Le nom du poste.
	[date] = // La date d'inscription.
}
```

### Ω dbs::getPosteById($e)
> Retourne la table du poste trouvé par son id (poste) + compte le nombre de votes.

**Informations entrantes**

| param | Type | Desc |
|-------|------|------|
| $e | array | Un tableau contenant le id du poste. |

**Règles de gestion**

En cas d'erreur, lever une exception `ERR-MODEL-DATABASE`.
```php
db::go('SELECT p.id, p.poste, p.date, COUNT(v.id2) nb,
	FROM apso_poste p  
	INNER JOIN apso_vote v 
	ON p.id = v.id2 AND v.type = "ctn"
	WHERE p.id=:id');
```

**Informations sortantes**

```php
Array [
	[id] = // Identifiant du poste associé.
	[poste] = // Le nom du poste.
	[date] = // La date de la création du poste.
	[nb] = // Nombre de voix obtenues.
]
```

### Ω dbs::deletePoste($e)
> Suppression du poste, des votes et des fonctions associer.

**Informations entrantes**

| param | Type | Desc |
|-------|------|------|
| $e | array | Un tableau contenant le id du poste. |

**Règles de gestion**

En cas d'erreur, lever une exception `ERR-MODEL-DATABASE`.
```php
// Suppression du poste.
db::go('DELETE FROM apso_poste WHERE id=:id');

// Suppression des vote.
db::go('DELETE FROM apso_vote WHERE id2=:id AND type="ctn"');

// Suppression des fonctions associer.
db::go('DELETE FROM apso_func WHERE id_poste=:id');
```

### Ω dbs::getUserById ($e)
> Retourne la table de l'utilisateur trouver par son id user.

**Informations entrantes**

| param | Type | Desc |
|-------|------|------|
| $e | array | Un tableau contenant l'id user. |

**Règles de gestion**

En cas d'erreur, lever une exception `ERR-MODEL-DATABASE`.
```php
db::go('SELECT * FROM apso_user WHERE id=:id');
```

**Informations sortantes**
```php
Array {
	[id] = // L'identifiant unique crée par l'application.
	[adr] = // Identifiant client (adresse bitcoin).
	[nom] = // Le nom du client.
	[prenom] = // Le prenom du client.
	[date] = // La date d'inscription.
	[role] = // Le rôle de l'utilisateur.
}
```

### Ω dbs::getLogByUserAndAction($e)
> Retourne la table de poste trouver par son nom (poste).

**Informations entrantes**

| param | Type | Desc |
|-------|------|------|
| $e | array | Un tableau contenant le nom du poste. |

**Règles de gestion**

En cas d'erreur, lever une exception `ERR-MODEL-DATABASE`.
```php
db::go('SELECT * FROM apso_log WHERE id_user=:id_user AND action=:action');
```

**Informations sortantes**

```php
Array {
	[0] = Array [
		[id] = // L'identifiant unique du log.
		[id_user] = // L'identifiant unique du client.
		[action] = // L'action du log.
		[date] = // La date d'inscription.
		[jdata] = // Information en format JSON string.
	[1] = // ...
}
```

### Ω dbs::getVote()
> Retourne la table vote au complet.

**Règles de gestion**

En cas d'erreur, lever une exception `ERR-MODEL-DATABASE`.
```php
db::go('SELECT * FROM apso_vote');
```

**Informations sortantes**

```php
Array {
	[0] = Array [
		[id] = // L'identifiant unique du vote.
		[id1]
		[id2]
		[type] = // Le type de vote.
		[date] = // Timestamp.
		[signe] = // La signiature du vote.
	[1] = // ...
}
```

### Ω dbs::deleteVote($e)
> Suppression du vote.

**Informations entrantes**

| param | Type | Desc |
|-------|------|------|
| $e | array | Un tableau contenant le id du vote. |

**Règles de gestion**

En cas d'erreur, lever une exception `ERR-MODEL-DATABASE`.
```php
// Suppression du poste.
db::go('DELETE FROM apso_vote WHERE id=:id');
```

### Ω dbs::setVote($e)
> Ajoute un nouvel vote dans la base de données.

**Informations entrantes**

| param | Type | Desc |
|-------|------|------|
| $e | array | Un tableau contenant l'identifiant vote, id1, id2, type, date, signe. |

**Règles de gestion**

En cas d'erreur, lever une exception `ERR-MODEL-DATABASE`.
```php
db::go('INSERT INTO apso_vote VALUES(:id, :id1, :id2, :type, :date, :signe)');
```

### Ω dbs::getVoteById($e)
> Retourne la table vote par id.

**Informations entrantes**

| param | Type | Desc |
|-------|------|------|
| $e | array | Un tableau contenant l'identifiant du vote. |

**Règles de gestion**

En cas d'erreur, lever une exception `ERR-MODEL-DATABASE`.
```php
db::go('SELECT * FROM apso_vote' WHERE id=:id');
```

**Informations sortantes**

```php
Array [
	[id] = // L'identifiant unique du vote.
	[id1]
	[id2]
	[type] = // Le type de vote.
	[date] = // Timestamp.
	[signe] = // La signiature du vote.
]
```

### Ω dbs::deleteVoteByTimes($e)
> Suppression du vote.

**Informations entrantes**

| param | Type | Desc |
|-------|------|------|
| $e | array | Un tableau contenant le temps. |

**Règles de gestion**

En cas d'erreur, lever une exception `ERR-MODEL-DATABASE`.
```php
// Suppression du poste.
db::go('DELETE FROM apso_vote WHERE date<=:date AND signe=0');
```

### Ω dbs::setSigneInVote($e)
> Insert dans la table vote la signature.

**Informations entrantes**

| param | Type | Desc |
|-------|------|------|
| $e | array | Un tableau contenant l'identifiant du vote et la signature. |

**Règles de gestion**

En cas d'erreur, lever une exception `ERR-MODEL-DATABASE`.
```php
db::go('UPDATE apso_vote SET signe=:signe WHERE id=:id');
```

### Ω dbs::getDb()
> Retourne la base de données au complet.

**Règles de gestion**

En cas d'erreur, lever une exception `ERR-MODEL-DATABASE`.
```php
db::go('SELECT * FROM apso_user');
db::go('SELECT * FROM apso_vote');
db::go('SELECT * FROM apso_log ORDER BY id ASC');
db::go('SELECT * FROM apso_postes ORDER BY id DESC');
db::go('SELECT * FROM apso_lois');
db::go('SELECT * FROM apso_amd');
db::go('SELECT * FROM apso_func');
```

**Informations sortantes**

```php
Array {
	[user]
	[vote]
	[log]
	[postes]
	[lois]
	[amd]
	[func]
}
```

### Ω dbs::getNbLog()
> Compter le nombrer d'action dans le log.

**Règles de gestion**

En cas d'erreur, lever une exception `ERR-MODEL-DATABASE`.
```php
db::go('SELECT COUNT(*) FROM apso_log');
```

### Ω dbs::getLoiByName($e)
> Retourne la table de loi trouver par son nom (loi).

**Informations entrantes**

| param | Type | Desc |
|-------|------|------|
| $e | array | Un tableau contenant le nom de la loi. |

**Règles de gestion**

En cas d'erreur, lever une exception `ERR-MODEL-DATABASE`.
```php
db::go('SELECT * FROM apso_loi WHERE nom=:nom');
```

**Informations sortantes**

```php
Array {
	[id] = // L'identifiant unique de la loi.
	[nom] = // Le nom de la loi.
}
```

### Ω dbs::setLoi($e)
> Ajoute une nouvelle loi dans la base de données. Retourne void.

**Informations entrantes**

| param | Type | Desc |
|-------|------|------|
| $e | array | Un tableau contenant le nom de la loi. |

**Règles de gestion**

En cas d'erreur, lever une exception `ERR-MODEL-DATABASE`.
```php
db::go('INSERT INTO apso_lois VALUES("", :nom)');
```

### Ω dbs::setAmd($e)
> Ajoute un nouveaux amendement dans la base de données. Retourne void.

**Informations entrantes**

| param | Type | Desc |
|-------|------|------|
| $e | array | Un tableau contenant id_lois, le nom de l'amendement. |

**Règles de gestion**

En cas d'erreur, lever une exception `ERR-MODEL-DATABASE`.
```php
db::go('INSERT INTO apso_lois VALUES("", :id_lois, :nom)');
```

### Ω dbs::getAmdByText($e)
> Retourne la table de l'amendement trouver par son text (amd).

**Informations entrantes**

| param | Type | Desc |
|-------|------|------|
| $e | array | Un tableau contenant le text de l'amendement. |

**Règles de gestion**

En cas d'erreur, lever une exception `ERR-MODEL-DATABASE`.
```php
db::go('SELECT * FROM apso_amd WHERE amd=:amd');
```

**Informations sortantes**

```php
Array {
	[id_loi] = // L'identifiant unique de la loi.
	[amd] = // Le text de l'amendement.
}
```

### Ω dbs::deletLoi($e)
> Suppression de la loi.

**Informations entrantes**

| param | Type | Desc |
|-------|------|------|
| $e | array | Un tableau contenant le id_loi. |

**Règles de gestion**

En cas d'erreur, lever une exception `ERR-MODEL-DATABASE`.
```php
// Suppression de la loi.
db::go('DELETE FROM apso_lois WHERE id=:id');
```

### Ω dbs::getLoiById($e)
> Retourne la table de loi trouver par son nom (loi).

**Informations entrantes**

| param | Type | Desc |
|-------|------|------|
| $e | array | Un tableau contenant le id de la loi. |

**Règles de gestion**

En cas d'erreur, lever une exception `ERR-MODEL-DATABASE`.
```php
db::go('SELECT * FROM apso_loi WHERE id=:id');
```

**Informations sortantes**

```php
Array {
	[id] = // L'identifiant unique de la loi.
	[nom] = // Le nom de la loi.
}
```

***

## ∑ HELPER
> Regroupe les fonctions réutilisable dans tous les contrôleurs et actions.

### Ω help::user($a, $v, $s)
> Vérification de l'adresse bitcoin, de la signature du client. Recherche du client dans la base de données. Retourne les infos du client.

**Informations entrantes**

| param | Type | Desc |
|-------|------|------|
| $a | string | Identifiant client (adresse bitcoin). |
| $v | ? | La variable de contrôle de l'utilisateur. |
| $s | string | Signiature (hash sha1 $v+$a). |

**Règles de gestion**

1. Vérifier la validité de l'adresse bitcoin `$a` ou lever une exception. `ERR-BTC-ADR-INVALID`
2. Crée un hash `sha1`  de `$v` et de l'adresse bitcoin `$a`.
3. Vérifier la signature `$s` avec le hash crée précédemment ou lever une exception. `ERR-BTC-SIGN-INVALID`
4. Recherche de l'utilisateur dans la base de données par l'identifiant client.
	
	```php
	// Crée un tableau contenant l'identifiant client.
	$req = array('adr' => $a);
	
	// Appel a la fonction du model.
	$user = dbs::getUserByBtc($req);
	```
5. Vérifier la presence de l'utilisateur si non retourner `false`
6. Construire et retourner le tableau de l'utilisateur.

**Informations sortantes**
```php
Array {
	[id] = // L'identifiant unique crée par l'application.
	[adr] = // Identifiant client (adresse bitcoin).
	[nom] = // Le nom du client.
	[prenom] = // Le prenom du client.
	[date] = // La date d'inscription.
	[role] = // Le rôle de l'utilisateur.
}
```

### Ω help::acl($e, $f)
> Vérification des Accès Contrôle Level. Disponible pour les administrateurs et les citoyens élus a un poste, associé à une fonction propriétaire. 

**Informations entrantes**

| param | Type | Desc |
|-------|------|------|
| $e | array | info client.|
| $f | string | La fonction propriétaire.|

**Règles de gestion**

1. Si administrateur `$e['role']`, alors retourner la variable `$tmp = true`.
2. Si citoyen, vérifier les poste est les élus.
	1. Recherche dans la base de données la fonction propriétaire passer en paramètre `$f`.
		
		```php
		// Crée un tableau contenant la fonction propriétaire.
		$req = array('name' => $f);
		
		// Appel a la fonction du model.
		$func = dbs::getPrivFunc($req);
		```
	2. Créer la variable `$suffrage` et déclencher une boucle sûr `$func` pour créer le tableau suivant. Avant l'insertion du premier élu, faire une boucle sur le tableau lui-même '$suffrage', si l'id1 apparais déjà dans un poste précédant, alors sélectionner le deuxième élu et refaire l'opération.
		
		```php
		Array [
			[id] = Array [ // Identifiant du poste
				[elu] = [id1] // Identifiant du client élu.
				[nb] = [nb] // Nombre de voix obtenues.
				[name] // Le nom de la fonction propriétaire ou NULL
			]
			[id] = // ...
		]
		```
	3. Comparaison des postes avec les données de l'utilisateur. Créer la variable `$tmp = false`. Déclencher une boucle sur `$suffrage`. S'il y a correspondance entre l'élu et `$e['id']` pendant la correspondance du nom de la fonction propriétaire avec `$f`, alors modifier la variable `$tmp = true`. Au final retourner `$tmp`.

3. Si n'y citoyen et n'y administrateur, alors retourner `$tmp = false`.


### Ω help::userVote($e)
> Retourne les votes du client.

**Informations entrantes**

| param | Type | Desc |
|-------|------|------|
| $e | int | id client.|

**Règles de gestion**

1. Charger le log du citoyen et l'action VOTE.

	```php
	// Crée un tableau contenant l'identifiant client.
	$req = array('id_user' => $e
		'action' => 'VOTE');
	
	// Appel a la fonction du model.
	$log = dbs::getLogByUserAndAction($req);
	```

2. Lancer une boucle sur $log pour créer le tableau suivant. Le hash se trouve dans la variable jdata de l'action vote.

	```php
	Array [
		[0] = [hash] // Le hash du vote contenu dans jdata. 
		[1] = // ...
	]
	```

3. Charger la table vote au complet.

	```php
	// Appel a la fonction du model.
	$vote = dbs::getVote();
	```

4.  Lancer une boucle sur $vote. Hash le id_vote, d1 et d2 et compare la présence dans le log créé précédemment. Construire et retourner le tableau.

**Informations sortantes**

```php
Array [
	[id] = Array [ // Identifiant du vote
		[id]
		[id1]		
		[id2]
		[type]
		[hash]
		[signe]
	]
	[id] = // ...
]
```

### Ω help::getData($e)
> Retourne toute la base de données.

**Informations entrantes**

| param | Type | Desc |
|-------|------|------|
| $e | array | info user. |

**Règles de gestion**

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
		* Si le id_user correspondance a id utilisateur `$e['id']`
			* Récupérer le hash et le id_user dans `$myHashVote`.
			
			```php
			$jd[] = '[type] => '.$jdata['type'];
			```
	* Si l'action n'est pas `VOTE`.
		
		```php
		$jd = help::getMsg($jdata);
		```

	* Incrémenter le nombre d'actions dans le log `$tmp['log']['nb']++`
	* Ajouter à la réponse de retour, les infos du log. Dans la limit de 1000.
		
		```php
		$tmp['log']['list'][] = array(
			'id' => $v['id_user'],
			'nom' => $users[$v['id_user']]['nom'],
			'prenom' => $users[$v['id_user']]['prénom'],
			'action' => $v['action'],
			'date' => $v['date'],
			'msg' => $jd
		);
		```
		
7. Boucle sur la table vote. `$dbs['vote'] AS $k => $v`
	* Hash le `$v['id']`, `$v['id1']` et `$v['id2']`.
	* Séparer les votes par type `$v['type']`.
		
		* Type poste `CTN`
			* Vérifier si `$v['id2']` est present dans key `$voteCTN`.
				* Si oui, Vérifier si `$v['id1']` est present dans key `$voteCTN[$v['id2']]`.
					* Si oui, Incrémenter la variable `$voteCTN[$v['id2']][$v['id1']] ++`.
					* Si non, ajouter l'id1 au tableau `$voteCTN[$v['id2']][$v['id1']] ++`.
				* Si non, ajouter les deux ids au tableau `$voteCTN[$v['id2']][$v['id1']] = 1`.
				* Si le hash est present dans KEY `$myHashVote`.
					* Récupérer `$v['id2'] => $v['id1']` du vote dans `$myCtnVote`.
			* Classer la variable par postes ids, puis par client qui on le plus de votes.
			
		* Type loi `LOS`
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

11. Poste élu et les fonctions propriétaires.
	* Vérifier, si l'utilisateur est ADMIN.
		* Boucle sur la table func `$dbs['func'] AS $k => $v`.
			* Si `$v['id']` est 0. Ajouter le nom de la fonction a `$tmp['admin'][] = array($v['name'] => 1)`
	* Vérifier, si l'utilisateur est CITOYEN.
		* Boucle sur la table func `$tmp['obs']['postes'][list] AS $k => $v`.
			* Si `$v['id_elu']` est == a $e['id'].
				* Boucle sur la table func `$dbs['func'] AS $k1 => $v1`.
					* Si `$v['id']` est == a `$v1['id']`. Ajouter le nom de la fonction a `$tmp['admin'][] = array($v1['name'] => 1)`
					* Break.

**Informations sortantes**
	
```php
{
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
```

### Ω help::getMsg($e)
> Retourne toute la base de données.

**Informations entrantes**

| param | Type | Desc |
|-------|------|------|
| $e | string | info jdata dans le log. |

**Règles de gestion**

1. Boucle sur `$e  AS $k => $v`.
		
		```php
		$jd[] = '['.$k'] => '.$v;
		```

***

*Mercredi 25 Février 2015*