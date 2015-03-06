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
> Retourne la table deu poste trouver par son nom (poste).

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

***