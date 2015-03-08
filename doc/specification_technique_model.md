# Spécification technique Model (STB V.0.1)

**Application web APSO**

> Ce document comporte l’ensemble des règles, nommage des fonctions, conception de la base de données. Ça sera la feuille de route pour le développement.

***

## Connexion des utilisateurs

> Accès au information de l'utilisateur côté jQuery, dans toutes les fonctions du framework.

*Variables disponibles.*
```js
// Identifiant client. Address bitcoin.
$.m.user.wallet.adr

// Hash crypter de la phrase secrète.
$.m.user.wallet.hash
```

*Décrypte la phrase secrète*
```js
// hash passphrase.
var hash = Crypto.util.hexToBytes($.crp.decrypte($.m.user.wallet.hash, $.sha1('YOUR-CODE-PIN')));
```

*Récupérer l'adresse bitcoin et la clé privée en string*
```js
// Initialize new bitcoin object.
var sec = new Bitcoin.ECKey(hash);

// Get bitcoin adresse.
var adr = ''+sec.getBitcoinAddress();

// Get private key.
var key = ''+sec.getExportedPrivateKey();
```

*Signer un message*
```js
// Compresse private key.
var payload = Bitcoin.Base58.decode(key);
var compressed = payload.length == 38;

// Signer le message.
var sign = $.btc.sign_message(sec, 'YOUR-MESSAGE', compressed);
```
*Les événement déclenché par la partie utilisateur*

| Event | Desc |
|-------|------|
| login | Cette événement est lancé a la connexion d'un utilisateur. |
| logout | Cette événement est lancé a la déconnexion d'un utilisateur. |

#### Côté serveur

> Vérification de la signature électronique côté serveur par le php, dans toutes les fonctions du framework.

*Validation de la signature*
```php
// Return true or false.
valide::btc_sign($bitcoinAdresse, $message, $signature);
```

***

## Gestion des rôles et accées.

> L'utilisateur dispose de 5 rôles. Les rôles sont attribués par les administrateurs ou par les postes, disposant de la fonction propriétaires.

| Rôle | Code | Desc |
|------|------|------|
| guest | GUEST | Le rôle par défaut après l'inscription de l'utilisateur dans l'api. Retourne un message "En attente de validation par un administrateur." |
| banni | BANNI |Concerne les utilisateurs bannis par un administrateur ou un poste disposant de la fonction propriétaire. Regroupe aussi les visiteurs non identifiés après l'inscription. Retourne un message "Vous êtes bloqué. Veuillez contacter un administrateur." |
| obs | OBS | Le groupe des Observateur, ont seulement accès a l'historique et les résulta du suffrage. |
| citoyen |CITOYEN | Les citoyens ont le droit d'exprimer leurs votes et proposer de nouvelles lois. Ils peuvent aussi être élus. |
| admin | ADMIN | Ils gèrent et modifient toutes les données de l'api. |

#### Gestion des postes.

> les postes permet de définir des fonctions propriétaires accessibles seulement a l'utilisateur élu au poste à ce moment-là. Par défaut, 4 postes sont déja crée.

| Poste | Nom |
|-------|-----|
| 1 | Président |
| 2 | Secrétaire |
| 3 | Trésorier |
| 4 | Vice-Président |

#### Fonction propriétaire.
> Les postes peuvent être associés à des fonctions propriétaires.

| Fonction | Desc | Associés |
|----------|------|----------|
| addPoste | Ajouter un nouveaux poste dans la base de données. | Secrétaire |
| deletePoste | Suppression du poste dans la base de données. | Secrétaire |
| editeRole | Modifier le rôle de l'utilisateur. | Secrétaire |

***

## Gestion de l'historique

> Les actions disponibles dans l'application et leurs descriptions.

| Action | Desc | jdata |
|--------|------|-------|
| SAVE | Inscription de l'utilisateur | id user, adresse bitcoin, nom, prénom, date, rôle |
| ADDPOSTE | Ajouter un nouveaux poste dans la base de données | id poste, le nom, la date |
| DELETEPOSTE | Suppression du poste, des votes et des fonctions associer | id poste, nom, date, le nombre de vote supprimer |
| EDITEROLE | Modification du rôle de l'utilisateur | id user, nom, prénom, rôle, new rôle, le nombre de vote supprimer |
| VOTE | Le vote d'un utilisateur. | hash du vote, id crypter, type |

***

## Erreurs serveur

> Gestion des erreurs déclencher par le serveur et les contrôleurs.

| Code | Desc |
|-------|-----|
| ERR-NOT-FIND-FILE | Erreur lors du chargement du fichier d'exécution. |
| ERR-INVALID-PARAM-OR-METHODE | Méthodes ou Paramètres incorrects. Erreur dans les données d'accès. |
| ERR-CONNECT-MYSQL | Connexion au serveur MySQL impossible. Erreur dans les données d'accès. |
| ERR-OFFLINE-MESSAGE | L'application est actuellement indisponible pour cause de maintenance. Désolé pour le désagrément. |
| ERR-MODEL-DATABASE | Erreur lors de l'exécution de la commande de model SQL. |
| ERR-BTC-ADR-INVALID | L'adresse bitcoin ne semble pas être valide. |
| ERR-BTC-SIGN-INVALID | La signature électronique ne semble pas être valide. |
| ERR-ACCOUNT-ALREADY-EXISTS | L'utilisateur est déjà enregistré dans la base de données. |
| ERR-ECHEC-SAVE-USER | L'enregistrement de l'utilisateur a échoué. |
| ERR-NAME-OR-FIRSTNAME-INVALID | Votre nom ou prénom semble invalide. |
| ERR-POSTE-INVALID | Le poste semble invalide. |
| ERR-USER-NOT-EXISTS | Votre identifiant n'est pas reconnu. |
| ERR-TIMESTAMP-INVALID | Le timestamp semble invalide. |
| ERR-USER-NOT-ACCESS | Vous n'êtes pas autorisé à accéder à cette ressource. |
| ERR-POSTE-ALREADY-EXISTS | Le poste que vous essayiez d'ajouter existe déjà dans la base de données. |
| ERR-ECHEC-SAVE-POSTE | L'enregistrement du poste a échoué |
| ERR-POSTE-NOT-EXISTS | Le poste que vous voulez supprimer n'existe pas. |
| ERR-ROLE-INVALID | Le rôle passer en paramètres semble incorrecte. |
| ERR-NOT-CHANGE-ADMIN | Vous ne pouvez pas modifier les rôles d'administrateurs. |
| ERR-VAR-VOTE-INVALID | Les données du vote semble incorrecte. |

## Erreurs client

> Gestion des erreurs déclencher par le lapplication client.

| Code | Desc |
|-------|-----|
| ERR-ALREADY-CONNECTED | Vous êtes déjà connecté. |

***

*Mercredi 25 Février 2015*