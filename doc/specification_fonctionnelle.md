# Spécification fonctionnelle (SFD V.0.1)

**Application web APSO**

> Ce document comporte l’ensemble des règles de fonctionnement de votre logiciel. C'est l'architecture de votre application.

***

## Introduction :book:

**Appareillage d'expérimentation de la démocratie directe.**

* Une application permettant des élections anonymes en temps réel ou chaque votant est susceptible d'être élu. À tous moment, un membre peut changer sont vote et basculer le résulta final. Un élu n'a pas le droit au cumul des mandat. Par défaut, 4 postes sont déja crée :
	* :bust_in_silhouette: Président
	* :bust_in_silhouette: Vice-Président
	* :bust_in_silhouette: Secrétaire
	* :bust_in_silhouette: Trésorier

* L'application comporte une section de loi. Chaque votant peux proposer et voter pour des lois et leurs variations.

* En cas d'égalité ou ballotage, les postes ou lois reste inchangées. Le résultat est en permanence accessible pour tous.

---------------------------------------------------------++

Les administrateurs sont au nombre de deux : un Master et un Secrétaire. Les droits du Master dominent ceux du secrétaire ; les actions du Secrétaire doivent être validées par le Master. Secrétaire et Master peuvent :
= Ajouter des postes pour les élections (par défaut, 4 postes sont déjà créés : Président, Vice-Président, Secrétaire, Trésorier).

= Valider ou invalider les membres.

= Supprimer et mettre en forme des lois et leurs variations.

Si les droits du Master dominent ceux du secrétaire et les actions du Secrétaire doivent être validées par le Master. Sur le plant technique et modulable dans le futur, il serait plus interessant de crée des fonction specifique au poste de secrétaire et accessible uniquement a la personne élu a ce moment la. Les fonction vont permetre au secrétaire de proposer une modification de loi, creation d'un nouveau poste, ou de la supression d'un membre.

Pour la validation de l'action par un master, vous avez deux possibilistes.

1. Le Secrétaire effectuer directement la modification dans les données. Sans la validation.
	* Avantage
		* Une liberter pour les utilisateur.
		* Les administrateurs possèdent déjà les fonctions de modification des données en question et un access a l'historique.
	* Inconvénient
		* Le Secrétaire ne pourra pas avoir access a la fonction de suppression de lois, postes.  

+++++++++++++

Je préfère cette première - parce que a) elle me semble plus simple, plus légère - b) dans les minutes, heures qui suivent la modifPARsecrétaire, le Master peut éventuellement intervenir et corriger la modif - c) et parce qu'il y a toujours le recours à la restauration sur sauvegarde journalière.

(deux questions à vérifier 1) les modifs sont visibles... MAIS y a-t-il aussi une fonction 'alerte' qui informe le Master qu'une modif a été posée  ? ---   2) il y a une sauvegarde journalière et possibilité de restauration... MAIS jusqu'à quel historique remonte la restauration possible (autrement dit - peut-on faire une restauration à jour-moins-trois,  moins-quatre etc... ou bien n'y a-t-il jamais qu'une seule restauration possible (celle de la veille) ?

Par contre je ne comprends pas pourquoi cette solution empêche le Secrétaire à avoir accès à "fonction de suppression de lois, postes"

+++++++++++++



2. Les actions devraient être validées par un master.
	* Avantage
		* Les actions ne peuvent pas être visibles avant d'être validées.
		* Une bonne sécurité pour l'administration.
	* Inconvénient
		* Développement de fonction supplémentaire pour la validation des actions des secrétaires.

Sur le plant futur, il faut travailler ses fonctions associées à un poste, le plus modulable possible pour pouvoir associer d'autre fonction à d'autres postes créés. Ex, comme le système est baser sur le bitcoin, ont pourra toujours crée des fonctions pour le trésorier, qui seront capable de faire des transfert.

+++++++++++++//////////

exact - toujours le plus modulable possible

+++++++++++++//////////

---------------------------------------------------------++

* Les administrateurs peuvent :
> Sont ajoutés au Master tous les autres droits (intervention sur les données et sur le système).
	* Ajouter des postes pour les élections.
	* Valider ou invalider les membres.
	* Suppression et mise en forme des lois et leurs variations.
	* Validées les actions des fonctions generait par les postes de secrétaire...

* L'application comporte un log, historique de toutes les actions effectuer par les membres et les administrateurs. Ses informations sont accessible sur les deux applications.

* Donner un espace de connexion pour les observateurs.

***

## Connexion des utilisateurs :closed_lock_with_key:

L'utilisateur entre, côté client une phrase secrétée qui génère une clé public, avec là qu'elle, il est identifié côté serveur. Le serveur n'a plus besoins de son mail et mot de pass, de se fait ça enlève le problème de sécurité côté serveur, il y a plus besoin de le pirater, car il ne garde plus aucune donnée sensible concernant le client.

Si l'utilisateur doit faire une modification exigent son authentification, un message est signé côté client, le serveur na plus qu'a vérifier la validité de la signature pour identifier que c'est bien l'utilisateur qui a fait la demande.

:arrow_right: Tout le compte est régénérer à partir de la phrase secrète à chaque utilisation et les données sont traitées en local avec JavaScript.

:arrow_right: Un nouveau code pin est demander a la connexion pour un cryptage symétrique de la phrase secrète pendant toute la durée de la session locale. le code pin n'est stocké nulle part.

:arrow_right: Tout le systeme cryptographique reste invisible pour le client final.

***

## Gestion des utilisateurs :busts_in_silhouette:

* :construction: Travail en cours

***

## Anonymisation des votes :squirrel:

* Les **contraintes** techniques du vote.
	* Le vote doit être **anonyme**.
	* Le vote peut être **modifiable**.
	* L'utilisateur peut **voir** ses votes.
	* Le résultats des élections est en **temps réel**.
	* Le système de vote doit être adaptable aux élections des **postes** et **lois**.

* :construction: Travail en cours

***

## Architecture :books:

> Le projet est constitué d'une api côté serveur et de deux applications web côté client.

### :green_book: Application Client

> L'application client, affiche et accepter les inscriptions, authentification, vote, ajout de règles et leur révision.

* **Accueil**
	* **Intro :**
	* **Accès :**
	* **Maquette :**
	* **Informations :**
	* **Actions possibles :**
	* **Règles de gestion :**

### :closed_book: Application Admin

> L'application admin, contrôle et modifie toutes les données ajouter par les membres.

* **Accueil**
	* **Intro :**
	* **Accès :**
	* **Maquette :**
	* **Informations :**
	* **Actions possibles :**
	* **Règles de gestion :**

### :blue_book: Api serveur

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
