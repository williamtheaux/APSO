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
	* Installez un écouteur sur le formulaire `formVotePoste`avec la fonction `$.????.voteFUNC`

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

* `addPoste` Affiche un formulaire d'inscription final pour l'utilisateur.
	* Formulaire `formAddPoste`

***

## $.etat.addPosteFUNC()
> Elle lance un appel à l'api avec les données du poste au serveur.

**Règles de gestion**

1. Vérifier la presence de `$.m.user.wallet.admin.addPoste` Si non lever une exception `ERR-USER-NOT-ACCESS`.
2. Récupérait l'adresse bitcoin. Signer le poste et l'adresse bitcoin avec l'aide du code pin. Lancer un appel au serveur `etat_addPoste(adr, poste, signature)`
	* Si erreur, lever une exception avec le retour serveur. `data.error`

3. Créer les variables de l'app.

	```js
	$.m.user.wallet.info = data.result  // Return server.
	$.m.user.wallet.guest = 1  // L'utilisateur n'est pas encore validé.
	```

5. Lancer la fonction `$.etat.home()`.

* *Accès*
	* A partir de la page `Ω addPoste HTML`.
	* Accès rôle **Admin** ou un **citoyen élu** au poste donc la fonction dépend, précisément à ce moment-là.
* *Informations*
	* **Texte**
		* **Message succès :** Le nouveau poste fut ajouté avec succès.
	* **Variable interne**
		* Clé privée
		* Phrase secrète crypter
		* Id publique
	* **Variable new**
		* le poste
		* Le code pin
* *Actions possibles*
	* En cas d'erreur
		* Afficher un message d'alerte.
	* En cas de succès
		* Afficher un message de succès.
		* Afficher la page `Ω État HTML`.
* *Règles de gestion*
	* signiature de la variable poste.
	* appel à l'api.

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





### Ω deletePoste HTML
> Elle affiche un formulaire avec le code pin pour la suppression du poste.

* *Accès*
	* A partir de la page `Ω État HTML`.
	* Accès rôle **Admin** ou un **citoyen élu** au poste donc la fonction dépend, précisément à ce moment-là.
* *Maquette*
	* Composer d'un formulaire, icon, titre + Desc.
* *Informations*
	* **Texte**
		* **Titre :** suppression du poste.
	* **Input**
		* L'identifiant du poste
		* Le code pin
* *Actions possibles*
	* Déclencher la fonction `Ω addPoste FUNC`.
* *Règles de gestion*
	* Visibiliter selon les Accès.
	* Validation des champs pendant submit.

### Ω deletePoste FUNC
> Elle lance un appel à l'api pour la suppression du poste.

* *Accès*
	* A partir de la page `Ω deletePoste HTML`.
	* Accès rôle **Admin** ou un **citoyen élu** au poste donc la fonction dépend, précisément à ce moment-là.
* *Informations*
	* **Texte**
		* **Message succès :** Le poste fut supprimer avec succès.
	* **Variable interne**
		* Clé privée
		* Phrase secrète crypter
		* Id publique
	* **Variable new**
		* l'identifiant du poste
		* Le code pin
* *Actions possibles*
	* En cas d'erreur
		* Afficher un message d'alerte.
	* En cas de succès
		* Afficher un message de succès.
		* Afficher la page `Ω État HTML`.
* *Règles de gestion*
	* signiature de la variable poste.
	* appel à l'api.

### Ω editeRole HTML
> Elle affiche un formulaire pour editer le role.

* *Accès*
	* A partir de la page `Ω État HTML`.
	* Accès rôle **Admin** ou un **citoyen élu** au poste donc la fonction dépend, précisément à ce moment-là.
* *Maquette*
	* Composer d'un formulaire, icon, titre + Desc.
* *Informations*
	* **Texte**
		* **Titre :** Modification d'acces de l'utilisateur.
	* **Variable interne**
		* Id utilisateur
	* **Input**
		* Le role
		* Le code pin
* *Actions possibles*
	* Déclencher la fonction `Ω editeRole FUNC`.
* *Règles de gestion*
	* Validation des champs pendant submit.

### Ω editeRole FUNC
> Elle lance un appel à l'api pour editer le role d'un utilisateur.

* *Accès*
	* A partir de la page `Ω editeRole HTML`.
	* Accès rôle **Admin** ou un **citoyen élu** au poste donc la fonction dépend, précisément à ce moment-là.
* *Informations*
	* **Texte**
		* **Message succès :** Le rôle fut mise a jour avec succès.
	* **Variable interne**
		* Clé privée
		* Phrase secrète crypter
		* Id publique
	* **Variable new**
		* le rôle
		* Id user
		* Le code pin
* *Actions possibles*
	* En cas d'erreur
		* Afficher un message d'alerte.		
	* En cas de succès
		* Afficher un message de succès.
		* Afficher la page `Ω État HTML`.
* *Règles de gestion*
	* signiature de la variable rôle.
	* appel à l'api.



### Ω Vote HTML
> Elle affiche un formulaire pour le vote.

* *Accès*
	* A partir de la page `Ω ficheLois HTML` ou `Ω État HTML`.
	* Accès rôle **citoyen**.
* *Informations*
	* **Variable interne**
		* Id publique
	* **Variable new**
		* Type de vote
		* L'identifiant du vote
		* L'identifiant de la catégorie
* *Actions possibles*
	* Déclencher la fonction `Ω Vote FUNC`.
* *Règles de gestion*
	* Déterminer le type de vote.
	* Validation des champs pendant submit.

### Ω Vote FUNC
> Elle lance un appel à l'api avec les données du vote. Si succès, alors confirmer son vote en le signant a l'aide de code pin.

* *Accès*
	* A partir de la page `Ω Vote HTML`.
	* Accès rôle **citoyen**.
* *Maquette*
	* En cas de succès de l'appel, composer d'un formulaire de code pin, icon, titre + Desc.
* *Informations*
	* **Texte**
		* **Message succès :** Confirmer votre vote.
	* **Variable interne**
		* Clé privée
		* Phrase secrète crypter
		* Id publique
		* Type de vote
		* L'identifiant du vote
		* Id user
	* **Variable new**
		* L'identifiant de la catégorie
* *Actions possibles*
	* En cas d'erreur
		* Afficher un message d'alerte.		
	* En cas de succès
		* Afficher un message de succès.
		* Déclencher la fonction `Ω fixVote FUNC`.
* *Règles de gestion*
	* appel à l'api.

### Ω fixVote FUNC
> Elle lance un appel à l'api avec la signature du vote.

* *Accès*
	* A partir de la page `Ω Vote FUNC`.
	* Accès rôle **citoyen**.
* *Informations*
	* **Texte**
		* **Message succès :** Votre vote électronique est réalisés avec succès.
	* **Variable interne**
		* Clé privée
		* Phrase secrète crypter
		* Id publique
		* Type de vote
		* L'identifiant du vote
		* Id user
	* **Variable new**
		* Le code pin
* *Actions possibles*
	* En cas d'erreur
		* Afficher un message d'alerte.		
	* En cas de succès
		* Afficher un message de succès.
		* Afficher la page `Ω État HTML` ou `Ω Lois HTML`.
* *Règles de gestion*
	* signiature de la variable hash vote.
	* Différence entre le type de vote pour l'affichage de la page de retour.
	* appel à l'api.

### Ω addLois HTML
> Elle affiche un formulaire pour ajouter de nouvelles lois.

* *Accès*
	* A partir de la page `Ω Lois HTML`.
	* Accès rôle **citoyen**.
* *Maquette*
	* Composer d'un formulaire, icon, titre + Desc.
* *Informations*
	* **Texte**
		* **Titre :** Ajouter une nouvelle loi.
	* **Input**
		* La loi
		* Le code pin
* *Actions possibles*
	* Déclencher la fonction `Ω addLois FUNC`.
* *Règles de gestion*
	* Validation des champs pendant submit.

### Ω addLois FUNC
> Elle lance un appel à l'api pour ajouter de nouvelles lois.

* *Accès*
	* A partir de la page `Ω addLois HTML`.
	* Accès rôle **citoyen**.
* *Informations*
	* **Texte**
		* **Message succès :** La nouvelle loi fut ajouté avec succès.
	* **Variable interne**
		* Clé privée
		* Phrase secrète crypter
		* Id publique
	* **Variable new**
		* la loi
		* Le premier amendement		
		* Le code pin
* *Actions possibles*
	* En cas d'erreur
		* Afficher un message d'alerte.
	* En cas de succès
		* Afficher un message de succès.
		* Afficher la page `Ω ficheLois HTML`.
* *Règles de gestion*
	* signiature de la variable loi.
	* appel à l'api.

### Ω addAmd HTML
> Elle affiche un formulaire pour ajouter de nouveaux amendements.

* *Accès*
	* A partir de la page `Ω ficheLois HTML`.
	* Accès rôle **citoyen**.
* *Maquette*
	* Composer d'un formulaire, icon, titre + Desc.
* *Informations*
	* **Texte**
		* **Titre :** Ajouter un nouveaux amendements.
	* **Input**
		* L'amendements
		* Le code pin
* *Actions possibles*
	* Déclencher la fonction `Ω addAmd FUNC`.
* *Règles de gestion*
	* Validation des champs pendant submit.

### Ω addAmd FUNC
> Elle lance un appel à l'api pour ajouter de nouveaux amendements.

* *Accès*
	* A partir de la page `Ω addAmd HTML`.
	* Accès rôle **citoyen**.
* *Informations*
	* **Texte**
		* **Message succès :** Le nouveaux amendement fut ajouté avec succès.
	* **Variable interne**
		* Clé privée
		* Phrase secrète crypter
		* Id publique
		* la loi
	* **Variable new**
		* L'amendement		
		* Le code pin
* *Actions possibles*
	* En cas d'erreur
		* Afficher un message d'alerte.
	* En cas de succès
		* Afficher un message de succès.
		* Afficher la page `Ω ficheLois HTML`.
* *Règles de gestion*
	* signiature de la variable d'amendement.
	* appel à l'api.

### Ω editeLois HTML
> Elle affiche un formulaire pour editer une lois.

* *Accès*
	* A partir de la page `Ω ficheLois HTML`.
	* Accès rôle **Admin** ou un **citoyen élu** au poste donc la fonction dépend, précisément à ce moment-là.
* *Maquette*
	* Composer d'un formulaire, icon, titre + Desc.
* *Informations*
	* **Texte**
		* **Titre :** Modification de la loi.
	* **Input**
		* La loi
		* Le code pin
* *Actions possibles*
	* Déclencher la fonction `Ω editeLois FUNC`.
* *Règles de gestion*
	* Validation des champs pendant submit.

### Ω editeLois FUNC
> Elle lance un appel à l'api pour editer une lois.

* *Accès*
	* A partir de la page `Ω editeLois HTML`.
	* Accès rôle **Admin** ou un **citoyen élu** au poste donc la fonction dépend, précisément à ce moment-là.
* *Informations*
	* **Texte**
		* **Message succès :** La loi fut mise a jour avec succès.
	* **Variable interne**
		* Clé privée
		* Phrase secrète crypter
		* Id publique
	* **Variable new**
		* la loi
		* Id user
		* Le code pin
* *Actions possibles*
	* En cas d'erreur
		* Afficher un message d'alerte.		
	* En cas de succès
		* Afficher un message de succès.
		* Afficher la page `Ω ficheLois HTML`.
* *Règles de gestion*
	* signiature de la variable loi.
	* appel à l'api.

### Ω editeAmd HTML
> Elle affiche un formulaire pour editer un amendements.

* *Accès*
	* A partir de la page `Ω ficheLois HTML`.
	* Accès rôle **Admin** ou un **citoyen élu** au poste donc la fonction dépend, précisément à ce moment-là.
* *Maquette*
	* Composer d'un formulaire, icon, titre + Desc.
* *Informations*
	* **Texte**
		* **Titre :** Modification d'amendements.
	* **Input**
		* Le amendements
* *Actions possibles*
	* Déclencher la fonction `Ω editeAmd FUNC`.
* *Règles de gestion*
	* Validation des champs pendant submit.

### Ω editeAmd FUNC
> Elle lance un appel à l'api pour editer un amendements.

* *Accès :*
	* A partir de la page `Ω editeAmd HTML`.
	* Accès rôle **Admin** ou un **citoyen élu** au poste donc la fonction dépend, précisément à ce moment-là.
* *Informations*
	* **Texte**
		* **Message succès :** L'amendement fut mise a jour avec succès.
	* **Variable interne**
		* Clé privée
		* Phrase secrète crypter
		* Id publique
	* **Variable new**
		* L'amendement
		* Id user
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

### Ω deleteLoi HTML
> Elle affiche un formulaire avec le code pin pour la suppression de la loi.

* *Accès*
	* A partir de la page `Ω Lois HTML`.
	* Accès rôle **Admin** ou un **citoyen élu** au poste donc la fonction dépend, précisément à ce moment-là.
* *Maquette*
	* Composer d'un formulaire, icon, titre + Desc.
* *Informations*
	* **Texte**
		* **Titre :** suppression du poste.
	* **Input**
		* L'identifiant de la loi
		* Le code pin
* *Actions possibles*
	* Déclencher la fonction `Ω deleteLoi FUNC`.
* *Règles de gestion*
	* Visibiliter selon les Accès.
	* Validation des champs pendant submit.

### Ω deleteLoi FUNC
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

### Ω deleteAmd HTML
> Elle affiche un formulaire avec le code pin pour la suppression de l'amendement.

* *Accès*
	* A partir de la page `Ω ficheLois HTML`.
	* Accès rôle **Admin** ou un **citoyen élu** au poste donc la fonction dépend, précisément à ce moment-là.
* *Maquette*
	* Composer d'un formulaire, icon, titre + Desc.
* *Informations*
	* **Texte**
		* **Titre :** suppression de l'amendement.
	* **Input**
		* L'identifiant de l'amendement
		* Le code pin
* *Actions possibles*
	* Déclencher la fonction `Ω deleteAmd FUNC`.
* *Règles de gestion*
	* Visibiliter selon les Accès.
	* Validation des champs pendant submit.

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