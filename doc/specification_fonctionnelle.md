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

* Fonctions privées définies pour les postes crées précédemment. Permet de définir des fonctions propriétaires accessibles seulement a l'utilisateur en poste à ce moment-là. Les fonctions sont modulables pour être ajoutées ou supprimer. Par défaut, 5 fonctions sont déja crée pour le poste de **secrétaire** :
	* Suppression et mise en forme des lois et leurs variations.
	* Valider ou invalider les membres. Bas niveaux.
	* Ajouter ou supprimer des postes pour les élections.
	> :white_check_mark: Issues [#1](https://github.com/williamtheaux/APSO/issues/1) [#3](https://github.com/williamtheaux/APSO/issues/3) 

* L'application comporte une section de loi. Chaque votant peux proposer et voter pour des lois et leurs variations.

* En cas d'égalité ou ballotage, les postes ou lois reste inchangées. Le résultat est en permanence accessible pour tous.
	> :interrobang: [à propos cumul des mandats #4](https://github.com/williamtheaux/APSO/issues/4)

* Les administrateurs peuvent :
	> Sont ajoutés au Master tous les autres droits (intervention sur les données et sur le système) Par défaut, 7 fonctions sont déja crée.
	* Ajouter ou supprimer des postes pour les élections.
	* Valider ou invalider les membres. Haut niveaux.
	* Suppression et mise en forme des lois et leurs variations.
	* Ajoutées ou supprimer les fonctions propriétaires.

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
