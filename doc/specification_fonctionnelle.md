# Spécification fonctionnelle (SFD V.0.1)

**Application web APSO**

> Ce document comporte l’ensemble des règles de fonctionnement de votre logiciel. C'est l'architecture de votre application.

***

## Introduction :book:

**Appareillage d'expérimentation de la démocratie directe.**

* Une application permettant des **élections anonymes en temps réel** ou chaque votant est susceptible d'être élu. À tous moment, un membre peut changer sont vote et basculer le résulta final. Un élu n'a pas le droit au cumul des mandat.
	> Par défaut, 4 postes sont déja crée :
	* :bust_in_silhouette: Président
	* :bust_in_silhouette: Vice-Président
	* :bust_in_silhouette: Secrétaire
	* :bust_in_silhouette: Trésorier

* **Fonctions propriétaires** définies pour les postes crées précédemment. Permet de définir des fonctions propriétaires accessibles seulement a l'utilisateur en poste à ce moment-là. Les fonctions sont modulables pour être ajoutées ou supprimer. Elles pourront être utilisées sur plusieur postes simultanément.
	> :white_check_mark: Issues [#1](https://github.com/williamtheaux/APSO/issues/1) [#3](https://github.com/williamtheaux/APSO/issues/3) Par défaut, 5 fonctions sont déja crée pour le poste de **secrétaire** :
	* :nut_and_bolt: Suppression et mise en forme des lois et leurs amendements.
	* :nut_and_bolt: Valider ou invalider les membres. Bas niveaux.
	* :nut_and_bolt: Ajouter ou supprimer des postes pour les élections

* L'application comporte le **suffrage universel**. Une section ou chaque votant peut créer des nouvelles lois, proposer des amendements pour des lois existantes, et enfin, exprimer sont vote.
	* Les lois considérés élus, Quant au moins 50% des utilisateurs ont sélectionnées un amendement dans cette loi. Les 50% pourront être changés directement dans les configurations du code.

* En cas **d'égalité ou ballotage**, les postes ou lois reste inchangées. Le résultat est en permanence accessible pour tous.
	> :interrobang: [à propos cumul des mandats #4](https://github.com/williamtheaux/APSO/issues/4)

* Les **administrateurs** peuvent :
	> Sont ajoutés au Master tous les autres droits (intervention sur les données et sur le système) Par défaut, 7 fonctions sont déja crée.
	* :nut_and_bolt: Ajouter ou supprimer des postes pour les élections.
	* :nut_and_bolt: Valider ou invalider les membres. Haut niveaux.
	* :nut_and_bolt: Suppression et mise en forme des lois et leurs amendements.
	* :nut_and_bolt: Ajoutées ou supprimer les fonctions propriétaires.

* L'application comporte un **log**, historique de toutes les actions effectuer par les membres et les administrateurs. Ses informations sont accessible sur les deux applications.

* Donner un espace de connexion pour les **observateurs**.

***

## Connexion des utilisateurs :closed_lock_with_key:

L'utilisateur entre, côté client une phrase secrétée qui génère une **clé public**, avec là qu'elle, il est identifié côté serveur. Le serveur n'a plus besoins de son mail et mot de pass, de se fait ça enlève le problème de sécurité côté serveur, il y a plus besoin de le pirater, car il ne garde plus aucune donnée **sensible** concernant le client.

Si l'utilisateur doit faire une modification exigent son authentification, un message est **signé** côté client, le serveur na plus qu'a vérifier la validité de la signature pour identifier que c'est bien l'utilisateur qui a fait la demande.

:arrow_right: Tout le compte est régénérer à partir de la phrase secrète à chaque utilisation et les données sont traitées en **local** avec JavaScript.

:arrow_right: Un nouveau code pin est demander a la connexion pour un **cryptage symétrique** de la phrase secrète pendant toute la durée de la session locale. le code pin n'est stocké nulle part.

:arrow_right: Tout le systeme cryptographique reste **invisible** pour le client final.

***

## Gestion des utilisateurs :busts_in_silhouette:

L'utilisateur dispose de 5 rôles. Les rôles sont attribués par les administrateurs ou par les postes, disposant de la fonction propriétaires. Si le client se connecte pour la première fois et n'est pas reconnu par l'api, il est invité de finir son inscription en fournissant des données supplémentaires : **Nom** et **Prénom**.

* :bust_in_silhouette: **Guest :** Le rôle par défaut après l'inscription de l'utilisateur dans l'api. Retourne un message "En attente de validation par un administrateur."
* :bust_in_silhouette: **Banni :** Concerne les utilisateurs bannis par un administrateur ou un poste disposant de la fonction propriétaire. Regroupe aussi les visiteurs non identifiés après l'inscription. Retourne un message "Vous êtes bloqué. Veuillez contacter un administrateur."
* :bust_in_silhouette: **Observateur :** Le groupe des Observateur, ont seulement accès a l'historique et les résulta du suffrage.
* :bust_in_silhouette: **Membre :** Les membres ont le droit d'exprimer leurs votes et proposer de nouvelles lois. Ils peuvent aussi être élus.
* :bust_in_silhouette: **Administrateur :** Ils gèrent et modifient toutes les données de l'api.

***

## Anonymisation des votes :squirrel:

* Les **contraintes** techniques du vote.
	* Le vote doit être **anonyme**.
	* Le système de vote doit être **vérifiable**.
	* Le vote peut être **modifiable**.
	* L'utilisateur peut **voir** ses votes.
	* Le résultats des élections est en **temps réel**.
	* Le système de vote doit être adaptable aux élections des **postes** et **lois**.
	* Le système héberger sur un seul **serveur** et une **base de données**.
	* Suppressions des votes si l'utilisateur est **Banni** par un administrateur.

Un système de vote pseudo-anonyme avec une minime utilisation de brute force pour respecter la dernière contrainte. Utilisation d'une cryptographie asymétrique pour la signature des votes et une cryptographie symétrique pour que l'utilisateur puisse les voir et modifier. Le vote se passe en trois étapes :

1. **Récupération des listes électorales**
	* Cette procédure, ce pass au moment de la connexion, ou l'utilisateur récupère toutes les données relatives à l'application.

2. **Procédure du vote**

3. **Signature du vote**

* :construction: Travail en cours

***

# Architecture :books:

> Le projet est constitué d'une api côté serveur et d'une application web côté client.

***

## :green_book: Application Client

> L'application client, affiche et accepter les inscriptions, authentification, vote, ajout de règles et leur révision.

### :hash: Accueil HTML
> C'est la porte d'entrée de votre application. Elle se situe au sommet de la hiérarchie. C'est une page qui explique clairement ce qu'on va trouver sur votre application. C'est la page la plus visitée. Si l'utilisateur est connecter, Elle affiche les données de l'utilisateur.

* **Accès :**
	* Directement sur le domaine principal. https://domaine.com
* **Maquette :**
	* Couleur dominant est le vert. Un arrière-plan blanc, photo.
		* **Si connecter** Données de l'utilisateur.
		* **Si no connecter** un formulaire de connexion et un slide de 3 ou 4 paragraphes.
* **Informations :**
	* **Si connecter**
		* **Variable interne**
			* Id publique. L'adresse bitcoin.
			* Clé privée.
			* Phrase secrète crypter.
			* Données serveur.
	* **Si no connecter**
		* **Texte pour le slide**
			1. **Titre :** La démocratie directe **Desc :** Des élections anonymes en temps réel ou chaque membre peut changer à tous moment sont vote, et basculer le résulta final du scrutin.
			2. **Titre :** Le suffrage universel **Desc :** Exprime un choix, une volonté. Ici chaque membre peut créer des lois, proposer des amendements, et enfin, exprimer sont vote.
		* **Input**
			* La phrase secrète pour le cryptage asymétrique.
			* Le code pin pour le cryptage symétrique de la phrase secrète.
* **Actions possibles :**
	* **Si connecter**
		* Si l'utilisateur n'est pas reconue, afficher la page :hash: `SignUp HTML`
		* Si l'utilisateur est banni, afficher la page :hash: `Block HTML`
	* **Si no connecter**
		* Connexion avec l'application. :hash: `Connexion FUNC`
* **Règles de gestion :**
	* **Si connecter**
		* Si les données de connexion son present.
	* **Si no connecter**
		* Validation des champs pendant submit.

### :hash: Vérification HTML
> La vérification des signatures permet de valider le message et l'expéditeur. La signature électronique est un procédé permettant de garantir l'authenticité du signataire et de vérifier l'intégrité du message.

* **Accès :**
	* A partir du menu principal ou la page :hash: `Accueil HTML`.
* **Maquette :**
	* Composer d'un formulaire, icon, titre + Desc.
* **Informations :**
	* **Texte**
		* **Titre :** Vérifier une signature
		* **Desc :** Dans cette section, vous pouvez vérifier une signature et son message avec l'adresse bitcoin du signataire.
	* **Input**
		* Le message signé
		* la signature du message
		* Adresse Bitcoin du signataire
* **Actions possibles :**
	* Vérification de la signature. :hash: `Vérification FUNC`
* **Règles de gestion :**
	* Validation des champs pendant submit.

### :hash: Vérification FUNC
> La vérification valider le message et l'expéditeur. La signature électronique vérifier l'intégrité du message.

* **Accès :**
	* A partir de la page :hash: `Vérification HTML`.
* **Maquette :**
	* Modifie le message sur la page :hash: `Vérification HTML`.
* **Informations :**
	* **Texte**
		* **Message succès :** Le message est bien signiez par l'adresse en question.
	* **Variable interne**
		* Le message signé
		* la signature du message
		* Adresse Bitcoin du signataire
* **Actions possibles :**
	* En cas d'erreur, afficher un message d'alerte.
	* En cas de succès, afficher le message de validation sur la page + icon.
* **Règles de gestion :**
	* Vérification électronique du message.

### :hash: Signature HTML
> La Signature électronique est un procédé permettant de garantir l'authenticité du signataire et de vérifier l'intégrité du message.

* **Accès :**
	* A partir de la page :hash: `Accueil HTML`.
	* Accès rôle **Guest**.
* **Maquette :**
	* Composer d'un formulaire, icon, titre + Desc.
* **Informations :**
	* **Texte**
		* **Titre :** Signature du message
		* **Desc :** Dans cette section, vous pouvez signer un message avec votre clé prives et vérifiable avec votre adresse bitcoin.
	* **Input**
		* Le message à signer.
		* Le code pin pour le decryptage symétrique de la phrase secrète.
* **Actions possibles :**
	* Signature du message. :hash: `Signature FUNC`
* **Règles de gestion :**
	* Validation des champs pendant submit.

### :hash: Signature FUNC
> Procédure de la signature électronique du message.

* **Accès :**
	* A partir de la page :hash: `Signature HTML`.
	* Accès rôle **Guest**.
* **Maquette :**
	* Modifie le message sur la page :hash: `Signature HTML`.
* **Informations :**
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
* **Actions possibles :**
	* En cas d'erreur, afficher un message d'alerte.
	* En cas de succès, afficher la signature sur la page.
* **Règles de gestion :**
	* Signature électronique du message.

### :hash: Connexion FUNC
> Elle lance un appel à l'api pour les information client. Après analyse des données reçu et si le role d'accès est autorisé, elle lance un événement dans l'application.

* **Accès :**
	* Juste après la connexion.
	* Accès rôle **Guest**.
* **Informations :**
	* **Variable interne**
		* Id publique. L'adresse bitcoin.
		* Clé privée.
		* Phrase secrète crypter.
* **Actions possibles :**
	* Si l'appel échoue, annulé la connexion et lancer une erreur.
	* Editer le model, si l'utilisateur est au minimum **observateur**, lancer un événement et afficher la page :hash: `Accueil HTML`.
* **Règles de gestion :**

### :hash: Déconnexion FUNC
> Elle efface toutes les variable du model, lance un événement de déconnexion dans l'application.

* **Accès :**
	A partir du menu principal ou la page :hash: `Accueil HTML`.
	* Accès rôle **Guest**.
* **Actions possibles :**
	* Editer le model, lancer un événement et afficher la page :hash: `Accueil HTML`.
* **Règles de gestion :**
	* Ne pas afficher si l'utilisateur n'est pas connecté.

### :hash: SignUp HTML
> Si l'utilisateur n'est pas dans la base de données. Elle affiche un formulaire pour s'inscrire.

### :hash: SignUp FUNC
> Déclencher par un formulaire. Elle lance un appel à l'api avec les données de l'utilisateur. Si tout, c'est bien passer, elle affiche la page de validation.

### :hash: Valide HTML
> Si l'utilisateur n'est pas validé par un administrateur. Elle affiche un message de mise en attente.

### :hash: Block HTML
> Si l'utilisateur est banni. Elle affiche un message de bannissement.

### :hash: Log HTML
> Elle affiche l'historique du site.

### :hash: Etas HTML
> Elle affiche les postes et les utilisateurs élus. Elle est le point d'entrer pour toutes les fonctions touchant les postes et membres de l'api.

### :hash: addPoste HTML
> Elle affiche un formulaire pour l'ajout des postes.

### :hash: addPoste FUNC
> Elle lance un appel à l'api avec les données des postes au serveur.

### :hash: deletePoste HTML
> Elle affiche un formulaire pour effacer le poste sélectionné.

### :hash: deletePoste FUNC
> Elle lance un appel à l'api pour la suppression du poste.

### :hash: Vote HTML
> Elle permet de proposer son vote.

### :hash: Vote FUNC
> Elle lance un appel à l'api avec les données du vote. Si succès, alors confirmer son vote en le signant a l'aide de code pin.

### :hash: fixVote FUNC
> Elle lance un appel à l'api avec la signature du vote.

### :hash: Lois HTML
> Elle liste les lois et leurs amendements. Elle est le point d'entrer pour toutes les fonctions touchant les lois.

### :hash: ficheLois HTML
> Elle affiche la loi et ses amendements. Elle offre un moyen de modification de données à l'utilisateur ayant les droits nécessaires.

### :hash: addLois HTML
> Elle affiche un formulaire pour ajouter de nouvelles lois.

### :hash: addLois FUNC
> Elle lance un appel à l'api pour ajouter de nouvelles lois.

### :hash: addAmd HTML
> Elle affiche un formulaire pour ajouter de nouveaux amendements.

### :hash: addAmd FUNC
> Elle lance un appel à l'api pour ajouter de nouveaux amendements.

### :hash: editeLois HTML
> Elle affiche un formulaire pour editer une lois.

### :hash: editeLois FUNC
> Elle lance un appel à l'api pour editer une lois.

### :hash: editeAmd HTML
> Elle affiche un formulaire pour editer un amendements.

### :hash: editeAmd FUNC
> Elle lance un appel à l'api pour editer un amendements.

### :hash: deleteLoi FUNC
> Elle lance un appel à l'api pour la suppression d'une lois.

### :hash: deleteAmd FUNC
> Elle lance un appel à l'api pour la suppression d'un amendements.

***

## :blue_book: Api serveur

> Api dédiée en PHP avec le protocole JSON RPC 2, permettant la démocratie en temps-réel.

* :construction: Travail en cours

***

## Annexes :books:

* :page_facing_up: :gb: [Cryptographic Voting Protocols: A Systems Perspective](annexes/karlof.pdf)
* :page_facing_up: :gb: [David Chaum’s Voter Verification using Encrypted Paper Receipts](annexes/voter_verification_using_Encrypte.pdf)
* :page_facing_up: :gb: [Secure Electronic Voting Protocols](annexes/voting4hb.pdf)
* :earth_americas: :fr: [Bitcoin pour des votes gratuits et vérifiables](http://www.e-ducat.fr/bitcoin-pour-des-votes-gratuits-et-verifiables/)

***

:date: *Lundi 26 Janvier 2015*
