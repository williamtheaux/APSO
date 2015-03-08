# Spécification technique Client (STB V.0.1)
> L'application client, affiche et accepter les inscriptions, authentification, vote, ajout de lois et leur amendements. C'est la partie graphique qui est affichée au client.

![App architecture](annexes/appArchitect.jpg)

## Ω $.user.home()
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

**Template HTML**

* `home` Déjà intégrait dans le framework.
	* Formulaire `formLogin`
* `sign` Affiche un formulaire d'inscription final pour l'utilisateur.
	* Formulaire `formSignUser`
* `validation` Affiche un message de mise en attente avant la validation par un administrateur.
* `bannissement` Affiche un message de bannissement par un administrateur.

***

## Ω $.user.loginFUNC()
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

5. Lancer la fonction `$.user.AccueilHTML()`. afficher tmpl `logoutBtnPart` et le tooltip dans le menu div `mIbtc`.

***

### Ω $.user.signUpFUNC()
> Déclencher par un formulaire. Elle lance un appel à l'api avec les données de l'utilisateur. Si tout, c'est bien passer, elle affiche la page de validation.

* *Accès*
	* A partir de la page `Ω SignUp HTML`.
	* Accès rôle **Guest**.
* *Maquette*
	* Affiche la page `Ω Valide HTML`.
* *Informations*
	* **Variable new**
		* Le retour serveur
* *Actions possibles*
	* En cas d'erreur, afficher un message d'alerte.
	* En cas de succès, afficher la page `Ω Valide HTML`.
* *Règles de gestion*
	* Analyse du JSON retourner par le serveur.

***

### Ω Vérification HTML
> La vérification des signatures permet de valider le message et l'expéditeur. La signature électronique est un procédé permettant de garantir l'authenticité du signataire et de vérifier l'intégrité du message.

* *Accès*
	* A partir du menu principal.
* *Maquette*
	* Composer d'un formulaire, icon, titre + Desc.
* *Informations*
	* **Texte**
		* **Titre :** Vérifier une signature
		* **Desc :** Dans cette section, vous pouvez vérifier une signature et son message avec l'adresse bitcoin du signataire.
	* **Input**
		* Le message signé
		* la signature du message
		* Adresse Bitcoin du signataire
* *Actions possibles*
	* Vérification de la signature. `Ω Vérification FUNC`
* *Règles de gestion*
	* Validation des champs pendant submit.

### Ω Vérification FUNC
> La vérification valider le message et l'expéditeur. La signature électronique vérifier l'intégrité du message.

* *Accès*
	* A partir de la page `Ω Vérification HTML`.
* *Maquette*
	* Modifie le message sur la page `Ω Vérification HTML`.
* *Informations*
	* **Texte**
		* **Message succès :** Le message est bien signiez par l'adresse en question.
	* **Variable interne**
		* Le message signé
		* la signature du message
		* Adresse Bitcoin du signataire
* *Actions possibles*
	* En cas d'erreur, afficher un message d'alerte.
	* En cas de succès, afficher le message de validation sur la page + icon.
* *Règles de gestion*
	* Vérification électronique du message.

### Ω Signature HTML
> La Signature électronique est un procédé permettant de garantir l'authenticité du signataire et de vérifier l'intégrité du message.

* *Accès*
	* A partir du menu principal.
	* Accès rôle **Banni**.
* *Maquette*
	* Composer d'un formulaire, icon, titre + Desc.
* *Informations*
	* **Texte**
		* **Titre :** Signature du message
		* **Desc :** Dans cette section, vous pouvez signer un message avec votre clé prives et vérifiable avec votre adresse bitcoin.
	* **Input**
		* Le message à signer.
		* Le code pin pour le decryptage symétrique de la phrase secrète.
* *Actions possibles*
	* Signature du message. `Ω Signature FUNC`
* *Règles de gestion*
	* Validation des champs pendant submit.

### Ω Signature FUNC
> Procédure de la signature électronique du message.

* *Accès*
	* A partir de la page `Ω Signature HTML`.
	* Accès rôle **Banni**.
* *Maquette*
	* Modifie le message sur la page `Ω Signature HTML`.
* *Informations*
	* **Texte**
		* **Message succès :** Voici la signature électronique de votre message authentifier par votre identifiant publique.
	* **Variable interne**
		* Le message
		* Clé privée
		* Phrase secrète crypter
		* Le code pin
		* Id publique du signataire
	* **Variable new**
		* la signature du message
* *Actions possibles*
	* En cas d'erreur, afficher un message d'alerte.
	* En cas de succès, afficher la signature sur la page.
* *Règles de gestion*
	* Signature électronique du message.

### Ω Déconnexion FUNC
> Elle efface toutes les variable du model, lance un événement de déconnexion dans l'application.

* *Accès*
	A partir du menu principal.
	* Accès rôle **Banni**.
* *Actions possibles*
	* Editer le model, lancer un événement et afficher la page `Ω Accueil HTML`.
* *Règles de gestion*
	* Ne pas afficher si l'utilisateur n'est pas connecté.


### Ω Log HTML
> Elle affiche l'historique du site.

* *Accès*
	* A partir du menu principal.
	* Accès rôle **Observateur**.
* *Maquette*
	* Composer d'un tableau contenant les actions des utilisateurs, icon, titre + Desc.
* *Informations*
	* **Texte**
		* **Titre :** Historique d'état
		* **Desc :** Retrouver ici, l'historique des actions publique de l'Etat souverain.
	* **Tableau**
		* Le nom de l'utilisateur
		* Le pénom de l'utilisateur
		* Le nom de l'action
		* La date de l'action
		* Les info de l'action
			* Les paramètres sont des JSON est sont diffèrent à chaque action.
* *Règles de gestion*
	* Classement par : date Asc.
	* Une pagination est intégrée au pied du tableau. 20 par page.

### Ω État HTML
> Elle affiche les postes et les utilisateurs élus. Elle est le point d'entrer pour toutes les fonctions touchant les postes et citoyens de l'api.

* *Accès*
	* A partir du menu principal.
	* Accès rôle **Observateur**.
* *Maquette*
	* Tableau des citoyens de l'État sur la partie gauche de la page. Tableau des postes avec le citoyen élu sur la partie droite de la page.
	* Si citoyen, Devant chaque poste proposer un formulaire pour voter ou modifier son vote.
	* Si administrateur, afficher un btn en haut du tableau pour ajouter de nouveau poste et devant chaque poste un btn pour pouvoir le supprimer. Sur la partie gauche afficher un btn pour pouvoir modifier le rôle d'accès.
* *Informations*
	* **Tableau** citoyen
		* identifiant
		* Nom
		* Prénom
	* **Tableau** poste
		* identifiant
		* nom
* *Actions possibles*
	* Si **citoyen** voter ou modifier son vote.
	* Si **Admin** ou **membre élu** Ajout des postes, suppression des postes, gestion des utilisateurs.
* *Règles de gestion*
	* Si **citoyen** permettre d'effectuer son vote.
	* Si **Admin** ou **citoyen élu** permettre d'effectuer les actions disponible. Donner accès au liste de citoyens banni ou guest.

### Ω addPoste HTML
> Elle affiche un formulaire pour l'ajout des postes.

* *Accès*
	A partir de la page `Ω État HTML`.
	* Accès rôle **Admin** ou un **citoyen élu** au poste donc la fonction dépend, précisément à ce moment-là.
* *Maquette*
	* Composer d'un formulaire, icon, titre + Desc.
* *Informations*
	* **Texte**
		* **Titre :** Ajouter un nouveaux poste.
	* **Input**
		* Le poste
		* Le code pin
* *Actions possibles*
	* Déclencher la fonction `Ω addPoste FUNC`.
* *Règles de gestion*
	* Visibiliter  dans le menu principal selon les Accès.
	* Validation des champs pendant submit.

### Ω addPoste FUNC
> Elle lance un appel à l'api avec les données du poste au serveur.

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

### Ω Lois HTML
> Elle liste les lois et leurs amendements. Elle est le point d'entrer pour toutes les fonctions touchant les lois.

* *Accès*
	* A partir du menu principal.
	* Accès rôle **Observateur**.
* *Maquette*
	* Tableau des lois sur la page. Chaque ligne conduite a la fiche détaillée de la loi.
	* Si citoyen, Afficher un btn en haut du tableau pour ajouter de nouvelle lois.
	* Si admin, devant chaque poste un btn pour pouvoir supprimer la lois.
* *Informations*
	* **Tableau** Lois
		* identifiant
		* Nom
	* **Tableau** amendements élu.
		* identifiant
		* nom
* *Actions possibles*
	* Si **citoyen** voter ou modifier son vote. Ajout de lois.
	* Si **Admin** ou **membre élu** Suppression et gestion de lois.
* *Règles de gestion*
	* Si **citoyen** permettre d'effectuer son vote.
	* Si **Admin** ou **citoyen élu** permettre d'effectuer les actions disponible.

### Ω ficheLois HTML
> Elle affiche la loi et ses amendements. Elle offre un moyen de modification de données à l'utilisateur ayant les droits nécessaires.

* *Accès*
	* A partir du menu principal.
	* Accès rôle **Observateur**.
* *Maquette*
	* Le nom de la loi.
	* Tableau des amendements. Faire resortire l'amendement élu.
	* Si citoyen, Afficher un btn en haut du tableau pour ajouter de nouveaux amendements.
	* Si admin, devant chaque amendements un btn pour pouvoir supprimer ou modifier.
* *Informations*
	* **Variable** Lois
		* identifiant
		* Nom
	* **Tableau** amendements élu.
		* identifiant
		* nom
* *Actions possibles*
	* Si **citoyen** voter ou modifier son vote.
	* Si **Admin** ou **membre élu** Suppression et gestion des amendements.
* *Règles de gestion*
	* Si **citoyen** permettre d'effectuer son vote.
	* Si **Admin** ou **citoyen élu** permettre d'effectuer les actions disponible.

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