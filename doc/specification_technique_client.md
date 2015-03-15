# Spécification technique Client (STB V.0.1)
> L'application client, affiche et accepter les inscriptions, authentification, vote, ajout de lois et leur amendements. C'est la partie graphique qui est affichée au client.

# Module user
> Gestion des utilisateurs.

* mod/user/user.js
* mod/user/user.htm
* mod/user/user.json

## $.user.home()
> C'est la porte d'entrée de votre application. Elle se situe au sommet de la hiérarchie. C'est une page qui explique clairement ce qu'on va trouver sur votre application. C'est la page la plus visitée. Si l'utilisateur est connecter, Elle affiche les données de l'utilisateur.

**Règles de gestion**

1. Vérifier la presence de `$.m.user.wallet.adr` Si non afficher HTML `home`
	* Installez un validateur sur le formulaire `formLogin`
	* Installez un écouteur sur le formulaire `formLogin`avec la fonction `$.user.loginFUNC`
2. Vérifier la presence de `$.m.user.wallet.info` Si non afficher HTML `sign`
	* Installez un validateur sur le formulaire `formSignUser`
	* Installez un écouteur sur le formulaire `formSignUser` avec la fonction `$.user.signUpFUNC`
3. Vérifier l'absence de `$.m.user.wallet.guest` Si non afficher HTML `validation`
4. Vérifier l'absence de `$.m.user.wallet.banni` Si non afficher HTML `bannissement`
5. afficher HTML `compte`

**Template HTML**

* `home` Déjà intégrait dans le framework.
	* Formulaire `formLogin`
* `sign` Affiche un formulaire d'inscription final pour l'utilisateur.
	* Formulaire `formSignUser`
* `validation` Affiche un message de mise en attente avant la validation par un administrateur.
* `bannissement` Affiche un message de bannissement par un administrateur.
* `compte` Affiche les info de l'utilisateur.

	```js
	user.wallet.info.adr // Identifiant client (adresse bitcoin).
	user.wallet.info.nom // Le nom du client.
	user.wallet.info.prenom // Le prénom du client.
	user.wallet.info.date // La date d'inscription.
	user.wallet.info.role // Le rôle de l'utilisateur.
	```

***

## $.user.loginFUNC()
> Elle lance un appel à l'api pour les information client. Après analyse des données reçu et si le role d'accès est autorisé, elle lance un événement dans l'application.

**Règles de gestion**

1. Vérifier la presence de `$.m.user.wallet.adr` Si non lever une exception `ERR-ALREADY-CONNECTED`
2. Crée le timestamp actuel. Signer le timestamp actuel. Récupérait l'adresse bitcoin. Lancer un appel au serveur `user_login(adr, timestamp, signature)`
	* Si erreur, lever une exception avec le retour serveur. `data.error`
3. Créer les variables de l'app.

	```js
	$.m.user.wallet = data.result // Return server.
	$.m.user.wallet.adr // Adresse bitcoin.
	$.m.user.wallet.hash // Hash de la phrase.
	```
4.  Vérifier l'absence de `$.m.user.wallet.guest` et `$.m.user.wallet.banni` pour  envoyer l'évènement `login`.
5. Lancer la fonction `$.user.home()`. afficher tmpl `logoutBtnPart` et le tooltip dans le menu div `mUser`.

***

### $.user.signUpFUNC()
> Déclencher par un formulaire. Elle lance un appel à l'api avec les données de l'utilisateur. Si tout, c'est bien passer, elle affiche la page de validation.

**Règles de gestion**

1. Vérifier la presence de `$.m.user.wallet.adr` Si non lever une exception `ERR-ALREADY-NOT-CONNECTED`.
2. Récupérait l'adresse bitcoin. Signer le nom, prénom et l'adresse bitcoin. Lancer un appel au serveur `user_sign(adr, nom, prenom, signature)`
	* Si erreur, lever une exception avec le retour serveur. `data.error`
3. Créer les variables de l'app.

	```js
	$.m.user.wallet.info = data.result  // Return server.
	$.m.user.wallet.guest = 1  // L'utilisateur n'est pas encore validé.
	```

5. Lancer la fonction `$.user.home()`.

***

## $.user.logoutFUNC()
> Elle efface toutes les variable du model, lance un événement de déconnexion dans l'application.

**Règles de gestion**

1. Vérifier la presence de `$.m.user.wallet.adr` Si non lever une exception `ERR-ALREADY-NOT-CONNECTED`.
3. Supprimer les variables de l'app.

	```js
	$.m.user.wallet = {}  // Return NULL.
	```

4. Envoyer l'évènement `logout`.
5. Lancer la fonction `$.user.home()`. Effacer tmpl `logoutBtnPart` dans le menu div `mUser`.

***

# Module etat
> Gestion des votes pour les postes.

* mod/etat/etat.js
* mod/etat/etat.htm
* mod/etat/etat.json

## $.etat.defautHTML()
> Fonction par defaut.

**Règles de gestion**

1. Installez un écouteur sur `login` avec la fonction `$.etat.???`
2. Installez un écouteur sur `logout` avec la fonction `$.etat.???`

***

## $.etat.logHTML()
> Elle affiche l'historique du site.

**Règles de gestion**

1. Vérifier la presence de `$.m.user.wallet.log` Si non afficher HTML `emptyLog`
2. afficher HTML `logListe`
	* Paginer le tableau `$('#myTxTab').paginateTable({ rowsPerPage: 10, pager: ".pagerMyTx" });`

**Template HTML**

* `emptyLog` Affiche un message, l'historique est vide.
* `logListe` Affiche les info de l'historique en tableau.

	```js
	user.wallet.log [
		id_user // L'identifiant unique crée par l'application.
		nom // Le nom de l'utilisateur.
		prenom // Le prénom de l'utilisateur.
		action // L'action de l'historique.
		date // La date de l'action.
		msg // Le message de l'action.
	]
	```

***


## $.etat.home()
> Elle affiche les postes et les utilisateurs élus. Elle est le point d'entrer pour toutes les fonctions touchant les postes et citoyens de l'api.

**Règles de gestion**

1. Vérifier la presence de `$.m.user.wallet.adr` Si non lever une exception `ERR-ALREADY-NOT-CONNECTED`.
2. afficher HTML `etatHome`
	* Paginer le tableau `$('#userTab').paginateTable({ rowsPerPage: 10, pager: ".pagerUser" });`
	* Paginer le tableau `$('#posteTab').paginateTable({ rowsPerPage: 10, pager: ".pagerPoste" });`
3. Vérifier la presence de `$.m.user.wallet.citoyen`
	* Installez un validateur sur le formulaire `formVotePoste`
	* Installez un écouteur sur le formulaire `formVotePoste`avec la fonction `$.vote.send`

**Template HTML**

* `etatHome` Affiche les listes des postes et utilisateurs.

	```js
	user.wallet.obs.CITOYEN' : { // + admin dans la liste.
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
	```

	* Si `user.wallet.citoyen` Affiche le formulaire de vote `formVotePoste`.
	* Si `user.wallet.admin.addPoste` Affiche un bouton avec la fonction `$.etat.addPosteHTML`.
	* Si `user.wallet.admin.deletePoste` Affiche un bouton pour chaque poste avec la fonction `$.etat.deletePosteHTML`.
	* Si `user.wallet.admin.editeRole` Affiche un bouton devant chaque utilisateur avec la fonction `$.etat.addPosteHTML` et la getion des utilisateurs banni ou guest avec la fonction `$.etat.printUserHTML`.

***


## $.etat.addPosteHTML()
> Elle affiche un formulaire pour l'ajout des postes.

**Règles de gestion**

1. Vérifier la presence de `$.m.user.wallet.admin.addPoste` Si non lever une exception `ERR-USER-NOT-ACCESS`.
2. Afficher HTML `addPoste`
	* Installez un validateur sur le formulaire `formAddPoste`
	* Installez un écouteur sur le formulaire `formAddPoste` avec la fonction `$.etat.addPosteFUNC`

**Template HTML**

* `addPoste` Affiche un formulaire pour l'ajout d'un poste.
	* Formulaire `formAddPoste`

***

## $.etat.addPosteFUNC()
> Elle lance un appel à l'api avec les données du poste au serveur.

**Règles de gestion**

1. Vérifier la presence de `$.m.user.wallet.admin.addPoste` Si non lever une exception `ERR-USER-NOT-ACCESS`.
2. Récupérait l'adresse bitcoin. Signer le poste et l'adresse bitcoin avec l'aide du code pin. Lancer un appel au serveur `etat_addPoste(adr, poste, signature)`
	* Si erreur, lever une exception avec le retour serveur. `data.error`
3. Ajouter au répertoire de la variable app, le retoure.
	* Incrémenter le nombre de postes et log. 

		```js
		$.m.user.wallet.poste.nb ++.
		$.m.user.wallet.log.nb ++.
		```
	* Ajouter au tableau les infos retourner.

		```js
		$.m.user.wallet.poste.list =+ data.result.poste  // En fin du tableau.
		$.m.user.wallet.log.list =+ data.result.log  // Au debut du tableau.
		```
5. Lancer un message dans la console `ETAT-ADD-POSTE-SUCCES-DESC`.
6. Lancer la fonction `$.etat.home()`.

***

## $.etat.deletePosteHTML(id_poste)
> Elle affiche un formulaire avec le code pin pour la suppression du poste.

**Règles de gestion**

1. Vérifier la presence de `$.m.user.wallet.admin.deletePoste` Si non lever une exception `ERR-USER-NOT-ACCESS`.
2. Boucle sur la var `$.m.user.wallet.poste.list AS k => v`.
	* Comparér le id_poste a v.id. Si correspondance. `$.m.etat.newPoste.info = v`
3. Si  `$.m.etat.deletePoste.info == 0`. lever une exception `ERR-VAR-INVALID`.
4. Afficher HTML `deletePoste`
	* Installez un validateur sur le formulaire `formDeletePoste`
	* Installez un écouteur sur le formulaire `formDeletePoste` avec la fonction `$.etat.deletePosteFUNC`

**Template HTML**

* `deletePoste` Affiche un formulaire pour la suppression du poste. Les info son dans `$.m.etat.deletePoste.info`
	* Formulaire `formDeletePoste`

***

## Ω $.etat.deletePosteFUNC()
> Elle lance un appel à l'api pour la suppression du poste.

**Règles de gestion**

1. Vérifier la presence de `$.m.user.wallet.admin.deletePoste` Si non lever une exception `ERR-USER-NOT-ACCESS`.
2. Récupérait l'adresse bitcoin. Signer le id_poste et l'adresse bitcoin avec l'aide du code pin. Lancer un appel au serveur `etat_deletePoste(adr, id_poste, signature)`
	* Si erreur, lever une exception avec le retour serveur. `data.error`
3. Boucle sur la var `$.m.user.wallet.poste.list AS k => v`.
	* Supprimer le poste correspondant.
4. Ajouter au répertoire de la variable app, le retoure.
	* Incrémenter le nombre de postes et log. 

		```js
		$.m.user.wallet.poste.nb --.
		$.m.user.wallet.log.nb ++.
		```
	* Ajouter au tableau les infos retourner.

		```js
		$.m.user.wallet.log.list =+ data.result.log  // Au debut du tableau.
		```
5. Lancer un message dans la console `ETAT-DELETE-POSTE-DESC`.
6. Lancer la fonction `$.etat.home()`.

***


## Ω $.etat.editeRoleHTML(id_user, role_user)
> Elle affiche un formulaire pour editer le role.

**Règles de gestion**

1. Vérifier la presence de `$.m.user.wallet.admin.editeRole` Si non lever une exception `ERR-USER-NOT-ACCESS`.
	* Si le role_user est ADMIN. lever une exception `ERR-NOT-CHANGE-ADMIN`.
2. Boucle sur la var `$.m.user.wallet.obs[role_user].list AS k => v`.
	* Comparér le id_user a v.id. Si correspondance. `$.m.etat.role.info = v`
3. Si  `$.m.etat.role.info == 0`. lever une exception `ERR-VAR-INVALID`.
4. Afficher HTML `editeRole`
	* Installez un validateur sur le formulaire `formEditeRole`
	* Installez un écouteur sur le formulaire `formEditeRole` avec la fonction `$.etat.editeRoleFUNC`

**Template HTML**

* `editeRole` Affiche un formulaire pour l'edition du rôle. Les info son dans `$.m.etat.editeRole.info`
	* Formulaire `formEditeRole`

***

## Ω $.etat.editeRoleFUNC()
> Elle lance un appel à l'api pour editer le role d'un utilisateur.

**Règles de gestion**

1. Vérifier la presence de `$.m.user.wallet.admin.editeRole` Si non lever une exception `ERR-USER-NOT-ACCESS`.
2. Récupérait l'adresse bitcoin. Signer le id_user, le rôle et l'adresse bitcoin avec l'aide du code pin. Lancer un appel au serveur `etat_editeRole(adr, role, id_user, signature)`
	* Si erreur, lever une exception avec le retour serveur. `data.error`
3. Boucle sur la var `$.m.user.wallet.obs.[data.result.last].list AS k => v`.
	* Récupérait l'utilisateur et l'inclure dans `$.m.user.wallet.obs.[data.result.new].list`.
4. Boucle sur la var `$.m.user.wallet.obs.poste.list AS k => v`.
	* Remplacer l'id_elu par 0 et myVote si il y a une correspondance avec data.result.id.

5. Ajouter au répertoire de la variable app, le retoure.
	* Incrémenter le nombre de postes et log. 

		```js
		$.m.user.wallet.log.nb ++.
		```
	* Ajouter au tableau les infos retourner.

		```js
		$.m.user.wallet.log.list =+ data.result.log  // Au debut du tableau.
		```
6. Lancer un message dans la console `ETAT-EDIT-ROLE-DESC`.
7. Lancer la fonction `$.etat.home()`.

***

# Module vote
> Gestion des vote.

## $.vote.send()
> Elle lance un appel à l'api avec les données du vote. Si succès, alors confirmer son vote en le signant a l'aide de code pin.

**Règles de gestion**

1. Vérifier la presence de `$.m.user.wallet.adr` Si non lever une exception `ERR-ALREADY-NOT-CONNECTED`.
2. Récupérait l'adresse bitcoin. les deux ids du vote et le type de vote. Lancer un appel au serveur `vote_send(adr, id1, id2, type)`
	* Si erreur, lever une exception avec le retour serveur. `data.error`
3. Afficher HTML `confirmVote`
	* Installez un validateur sur le formulaire `formConfirmVote`
	* Installez un écouteur sur le formulaire `formConfirmVote` avec la fonction `$.vote.fix`

**Template HTML**

* `editeRole` Affiche un formulaire pour l'edition du rôle. Les info son dans `$.m.vote.info`
	* Formulaire `formConfirmVote`

***

## $.vote.fix()
> Elle lance un appel à l'api avec la signature du vote.

**Règles de gestion**

1. Vérifier la presence de `$.m.vote.info` Si non lever une exception `ERR-ALREADY-NOT-CONNECTED`.
2. Récupérait l'adresse bitcoin. Signer le hash et l'adresse bitcoin avec l'aide du code pin. Lancer un appel au serveur `vote_fix(adr, id_vote, signature)`
	* Si erreur, lever une exception avec le retour serveur. `data.error`

**Template HTML**

* Si type CNT. Lancer la fonction `$.etat.home()`. Si type LOS. Lancer la fonction `$.lois.home()`.

***

# Module lois
> Gestion des votes pour les lois.

* mod/lois/lois.js
* mod/lois/lois.htm
* mod/lois/lois.json

## $.lois.defautHTML()
> Fonction par defaut.

**Règles de gestion**

1. Installez un écouteur sur `login` avec la fonction `$.lois.???`
2. Installez un écouteur sur `logout` avec la fonction `$.lois.???`

***

## $.lois.home()
> Elle liste les lois et leurs amendements. Elle est le point d'entrer pour toutes les fonctions touchant les lois.

**Règles de gestion**

1. Vérifier la presence de `$.m.user.wallet.adr` Si non lever une exception `ERR-ALREADY-NOT-CONNECTED`.

2. afficher HTML `loisHome`
	* Paginer le tableau `$('#loisTab').paginateTable({ rowsPerPage: 10, pager: ".pagerLois" });`

**Template HTML**

* `loisHome` Affiche les lois et infos. Ajouter un bouton pour rejoindre la fiche de la loi. `$.lois.ficheLoisHTML(id_loi)`

	```js
	user.wallet.obs.lois : {
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
	```

	* Si `user.wallet.citoyen` Afficher un bouton en haut du tableau pour ajouter de nouvelle lois. `$.lois.addLoisHTML`, un btn en haut du tableau pour ajouter de nouvelle lois.
	* Si `user.wallet.admin.deleteLois` Affiche un bouton avec la fonction `$.lois.deleteLoisHTML(id_lois)`.
	* Si `user.wallet.admin.editeLois` Affiche un bouton avec la fonction `$.lois.editeLoisHTML(id_lois)`.

***


## $.lois.ficheLoisHTML(id_loi)
> Elle affiche la loi et ses amendements. Elle offre un moyen de modification de données à l'utilisateur ayant les droits nécessaires.

**Règles de gestion**

1. Vérifier la presence de `$.m.user.wallet.adr` Si non lever une exception `ERR-ALREADY-NOT-CONNECTED`.

2. Lancer une boucle sur `$.m.user.wallet.obs.lois`. Récupérer la loi demander. Enregistrait dans la variable `$.m.lois.fiche`

3. afficher HTML `loisFiche`
	* Paginer le tableau `$('#amdTab').paginateTable({ rowsPerPage: 10, pager: ".pagerAmd" });`

4. Vérifier la presence de `$.m.user.wallet.citoyen`
	* Installez un validateur sur le formulaire `formVoteLoi`
	* Installez un écouteur sur le formulaire `formVoteLoi`avec la fonction `$.????.voteFUNC`

**Template HTML**

* `loisFiche` Affiche la fiche de la loi.

	```js
	user.wallet.obs.lois : {
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
	```

	* Si `user.wallet.citoyen` Affiche le formulaire de vote `formVoteLoi`, Un btn en haut du tableau pour ajouter de nouveaux amendements.
	* Si `user.wallet.admin.deleteAmd` Affiche un bouton avec la fonction `$.lois.deleteAmdHTML(id_lois, id_amd)`.
	* Si `user.wallet.admin.editeAmd` Affiche un bouton avec la fonction `$.lois.editeAmdHTML(id_lois, id_amd)`.

***

## $.lois.addLoisHTML()
> Elle affiche un formulaire pour ajouter de nouvelles lois.

**Règles de gestion**

1. Vérifier la presence de `$.m.user.wallet.adr` Si non lever une exception `ERR-ALREADY-NOT-CONNECTED`.
2. Afficher HTML `addLois`
	* Installez un validateur sur le formulaire `formAddLois`
	* Installez un écouteur sur le formulaire `formAddLois` avec la fonction `$.etat.addLoisFUNC`

**Template HTML**

* `addLois` Affiche un formulaire pour l'ajout d'un poste.
	* Formulaire `formAddLois`

***

## $.lois.addLoisFUNC()
> Elle lance un appel à l'api pour ajouter de nouvelles lois.

**Règles de gestion**

1. Vérifier la presence de `$.m.user.wallet.adr` Si non lever une exception `ERR-ALREADY-NOT-CONNECTED`.
2. Récupérait l'adresse bitcoin. Signer la loi et l'adresse bitcoin avec l'aide du code pin. Lancer un appel au serveur `lois_addLois(adr, loi, amd, signature)`
	* Si erreur, lever une exception avec le retour serveur. `data.error`
3. Ajouter au répertoire de la variable app, le retoure.
	* Incrémenter le nombre de lois et log. 

		```js
		$.m.user.wallet.obs.lois.nb ++.
		$.m.user.wallet.obs.log.nb ++.
		```
	* Ajouter au tableau les infos retourner.

		```js
		$.m.user.wallet.obs.lois.list =+ data.result.lois  // En fin du tableau.
		$.m.user.wallet.obs.log.list =+ data.result.log  // Au debut du tableau.
		```
5. Lancer un message dans la console `LOI-ADD-SUCCES-LABEL`.
6. Lancer la fonction `$.lois.ficheLoisHTML(id_loi)`.

***

## $.lois.addAmdHTML()
> Elle affiche un formulaire pour ajouter de nouveaux amendements.

**Règles de gestion**

1. Vérifier la presence de `$.m.user.wallet.adr` Si non lever une exception `ERR-ALREADY-NOT-CONNECTED`.
2. Afficher HTML `addAmd`
	* Installez un validateur sur le formulaire `formAddAmd`
	* Installez un écouteur sur le formulaire `formAddAmd` avec la fonction `$.etat.addAmdFUNC`

**Template HTML**

* `addAmd` Affiche un formulaire pour l'ajout d'un poste.
	* Formulaire `formAddAmd`

***

## $.lois.addAmdFUNC()
> Elle lance un appel à l'api pour ajouter de nouveaux amendements.

**Règles de gestion**

1. Vérifier la presence de `$.m.user.wallet.adr` Si non lever une exception `ERR-ALREADY-NOT-CONNECTED`.
2. Récupérait l'adresse bitcoin. Signer le id_loi, l'amd et l'adresse bitcoin avec l'aide du code pin. Lancer un appel au serveur `lois_addAmd(adr, id_loi, amd, signature)`
	* Si erreur, lever une exception avec le retour serveur. `data.error`

3. Boucle sur la var `$.m.user.wallet.obs.lois.list AS k => v`.
	* Ajouter l'amd si il y a une correspondance avec data.result.id_loi.

4. Ajouter au répertoire de la variable app, le retoure.
	* Incrémenter le nombre d'amendements et log. 

		```js
		$.m.user.wallet.obs.lois.list['is_lois'].nb ++.
		$.m.user.wallet.obs.log.nb ++.
		```
	* Ajouter au tableau les infos retourner.

		```js
		$.m.user.wallet.obs.lois[id_lois].list =+ data.result.amd  // En fin du tableau.
		$.m.user.wallet.obs.log.list =+ data.result.log  // Au debut du tableau.
		```
5. Lancer un message dans la console `LOI-ADD-AMD-SUCCES-LABEL`.
6. Lancer la fonction `$.lois.ficheLoisHTML(id_loi)`.

***

## $.lois.editeLoisHTML(id_loi)
> Elle affiche un formulaire pour editer une lois.

**Règles de gestion**

1. Vérifier la presence de `$.m.user.wallet.admin.editeLois` Si non lever une exception `ERR-USER-NOT-ACCESS`.

3. Boucle sur la var `$.m.user.wallet.obs.lois.list AS k => v`.
	* Ajouter les infos si il y a une correspondance avec data.result.id_loi.

2. Afficher HTML `editeLois`
	* Installez un validateur sur le formulaire `formEditeLois`
	* Installez un écouteur sur le formulaire `formEditeLois` avec la fonction `$.etat.addAmdFUNC`

**Template HTML**

* `editeLois` Affiche un formulaire pour l'ajout d'un poste.
	* Formulaire `formEditeLois`

	```js
	$.m.lois.infos : {
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
	}
	```
***

## $.lois.editeLoisFUNC()
> Elle lance un appel à l'api pour editer une lois.

**Règles de gestion**

1. Vérifier la presence de `$.m.user.wallet.admin.editeLois` Si non lever une exception `ERR-USER-NOT-ACCESS`.
2. Récupérait l'adresse bitcoin. Signer l'id_loi, le nouvau nom de la loi et l'adresse bitcoin avec l'aide du code pin. Lancer un appel au serveur `lois_editeLois(adr, loi, id_loi, signature)`
	* Si erreur, lever une exception avec le retour serveur. `data.error`

3. Boucle sur la var `$.m.user.wallet.obs.lois.list AS k => v`.
	* Editer une loi, si il y a une correspondance avec data.result.id_loi.

4. Ajouter au répertoire de la variable app, le retoure.
	* Incrémenter le log. 

		```js
		$.m.user.wallet.obs.log.nb ++.
		```
	* Ajouter au tableau les infos retourner.

		```js
		$.m.user.wallet.obs.log.list =+ data.result.log  // Au debut du tableau.
		```
5. Lancer un message dans la console `LOI-EDIT-SUCCES-LABEL`.
6. Lancer la fonction `$.lois.ficheLoisHTML(id_loi)`.

****

## $.lois.editeAmdHTML(id_loi, id_amd)
> Elle affiche un formulaire pour editer un amendements.

**Règles de gestion**

1. Vérifier la presence de `$.m.user.wallet.admin.editeAmd` Si non lever une exception `ERR-USER-NOT-ACCESS`.

3. Boucle sur la var `$.m.user.wallet.obs.lois.list AS k => v`.
	* Ajouter les infos si il y a une correspondance avec data.result.id_loi.

2. Afficher HTML `editeAmd`
	* Installez un validateur sur le formulaire `formEditeAmd`
	* Installez un écouteur sur le formulaire `formEditeAmd` avec la fonction `$.etat.addAmdFUNC`

**Template HTML**

* `editeAmd` Affiche un formulaire pour l'ajout d'un poste.
	* Formulaire `formEditeAmd`

	```js
	$.m.lois.infos : {
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
	}
	```
***

## $.lois.editeAmdFUNC()
> Elle lance un appel à l'api pour editer un amendements.

**Règles de gestion**

1. Vérifier la presence de `$.m.user.wallet.admin.editeAmd` Si non lever une exception `ERR-USER-NOT-ACCESS`.
2. Récupérait l'adresse bitcoin. Signer l'id_loi, l'amd et l'adresse bitcoin avec l'aide du code pin. Lancer un appel au serveur `lois_editeAmds(adr, id Amd, amd, signature)`
	* Si erreur, lever une exception avec le retour serveur. `data.error`

3. Boucle sur la var `$.m.user.wallet.obs.lois.list AS k => v`.
	* Boucle sur la var `v.amd.list AS k1 => v1`.
		* Editer l'amd, si il y a une correspondance avec data.result.id_loi.

4. Ajouter au répertoire de la variable app, le retoure.
	* Incrémenter le log. 

		```js
		$.m.user.wallet.obs.log.nb ++.
		```
	* Ajouter au tableau les infos retourner.

		```js
		$.m.user.wallet.obs.log.list =+ data.result.log  // Au debut du tableau.
		```
5. Lancer un message dans la console `LOI-EDIT-AMD-SUCCES`.
6. Lancer la fonction `$.lois.ficheLoisHTML(id_loi)`.

***

## $.lois.deleteLoisHTML(id_loi)
> Elle affiche un formulaire avec le code pin pour la suppression de la loi.

**Règles de gestion**

1. Vérifier la presence de `$.m.user.wallet.admin.deleteLois` Si non lever une exception `ERR-USER-NOT-ACCESS`.
2. Boucle sur la var `$.m.user.wallet.obs.lois.list AS k => v`.
	* Comparér le id_poste a v.id. Si correspondance. `$.m.lois.info = v`
3. Si  `$.m.lois.info == 0`. lever une exception `ERR-VAR-INVALID`.
4. Afficher HTML `deleteLois`
	* Installez un validateur sur le formulaire `formDeleteLois`
	* Installez un écouteur sur le formulaire `formDeleteLois` avec la fonction `$.etat.deleteLoisFUNC`

**Template HTML**

* `deleteLois` Affiche un formulaire pour la suppression de la loi. Les info son dans `$.m.lois.info`
	* Formulaire `formDeleteLois`

***

### Ω deleteLoisFUNC
> Elle lance un appel à l'api pour la suppression d'une lois.

* *Accès :*
	* A partir de la page `Ω deleteLoi HTML`.
	* Accès rôle **Admin** ou un **citoyen élu** au poste donc la fonction dépend, précisément à ce moment-là.
* *Informations*
	* **Texte**
		* **Message succès :** La loi fut supprimer avec succès.
	* **Variable interne**
		* Clé privée
		* Phrase secrète crypter
		* Id publique
	* **Variable new**
		* l'identifiant de la loi
		* Le code pin
* *Actions possibles*
	* En cas d'erreur
		* Afficher un message d'alerte.
	* En cas de succès
		* Afficher un message de succès.
		* Afficher la page `Ω Lois HTML`.
* *Règles de gestion*
	* signiature de la variable loi.
	* appel à l'api.

## $.lois.deleteAmdHTML(id_amd)
> Elle affiche un formulaire avec le code pin pour la suppression de l'amendement.

**Règles de gestion**

1. Vérifier la presence de `$.m.user.wallet.admin.deleteAmd` Si non lever une exception `ERR-USER-NOT-ACCESS`.

2. Boucle sur la var `$.m.user.wallet.obs.lois.list AS k => v`.
	* Boucle sur la var `v.amd.list AS k1 => v1`.
		* Editer l'amd, si il y a une correspondance avec data.result.id_loi.

3. Afficher HTML `deleteAmd`
	* Installez un validateur sur le formulaire `formDeleteAmd`
	* Installez un écouteur sur le formulaire `formDeleteAmd` avec la fonction `$.etat.deleteAmdFUNC`

**Template HTML**

* `deleteAmd` Affiche un formulaire pour la suppression de la loi. Les info son dans `$.m.lois.info`
	* Formulaire `formDeleteAmd`

***

### Ω deleteAmd FUNC
> Elle lance un appel à l'api pour la suppression d'un amendement.

* *Accès :*
	* A partir de la page `Ω ficheLois HTML`.
	* Accès rôle **Admin** ou un **citoyen élu** au poste donc la fonction dépend, précisément à ce moment-là.
* *Informations*
	* **Texte**
		* **Message succès :** L'amendement fut supprimer avec succès.
	* **Variable interne**
		* Clé privée
		* Phrase secrète crypter
		* Id publique
	* **Variable new**
		* l'identifiant de l'amendement
		* Le code pin
* *Actions possibles*
	* En cas d'erreur
		* Afficher un message d'alerte.
	* En cas de succès
		* Afficher un message de succès.
		* Afficher la page `Ω ficheLois HTML`.
* *Règles de gestion*
	* signiature de la variable amendement.
	* appel à l'api.

***

*Mercredi 25 Février 2015*