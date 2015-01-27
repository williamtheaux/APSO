# Spécification fonctionnelle (SFD V.0.1)
**Application web APSO**> Ce document comporte l’ensemble des règles de fonctionnement de votre logiciel. C'est l'architecture de votre application.***

## Introduction :book:
**Appareillage d'expérimentation de la démocratie directe.**
* Une application permettant des élections anonymes en temps réel ou chaque votant est susceptible d'être élu. À tous moment, un membre peut changer sont vote et basculer le résulta final. Un élu n'a pas le droit au cumul des mondât.
* L'application comporte une section de loi. Chaque votant peux proposer et voter pour des lois et leurs variations.
* En cas d'égalité ou ballotage, les postes ou lois reste inchangées. Le résultat est en permanence accessible pour tous.
* Les administrateurs (Secrétaire) peuvent :
	* Ajouter des postes pour les élections. Par défaut, 4 postes sont déja crée :
		* :bust_in_silhouette: Président
		* :bust_in_silhouette: Vice-Président
		* :bust_in_silhouette: Secrétaire
		* :bust_in_silhouette: Trésorier
	* Valider ou invalider les membres.
	* Suppression et mise en forme des lois et leurs variations.* L'application comporte un log, historique de toutes les actions effectuer par les membres et les administrateurs.
* Donner un espace de connexion pour les observateurs.
***
## Connexion des utilisateurs :closed_lock_with_key:

L'utilisateur rentrer, côté client une phrase secrétée qui génère une clé public, avec là qu'elle, il est identifié côté serveur. Le serveur n'a plus besoins de son mail et mot de pass, de se fait ça enlève le problème de sécurité côté serveur, il y a plus besoin de le pirater, car il ne garde plus aucune donnée sensible concernant le client.

Si l'utilisateur doit faire une modification exigent son authentification, un message est signé côté client, le serveur na plus qu'a vérifier la validité de la signature pour identifier que c'est bien l'utilisateur qui a fait la demande.

:arrow_right: Tout le portefeuille est régénérer à partir de la phrase secrète à chaque utilisation et les données sont traitées en local avec JavaScript.

:arrow_right: Un code pin est demander a la connexion pour cryptée pendant toute la durée de la session locale la phrase secrète. le code pin n'est stocké nulle part.

:arrow_right: Tout le systeme cryptographique reste invisible pour le client final.

* :construction: Travail en cours
***
## Anonymisation des votes :squirrel:* :construction: Travail en cours***## Architecture :books:

### :green_book: Application client

* #### Accueil
	* **Intro :**
	* **Accès :**
	* **Maquette :**
	* **Informations :**
	* **Actions possibles :**
	* **Règles de gestion :**

### :blue_book: Api serveur

* :construction: Travail en cours
***

# Q

:interrobang: Secrétaire = Admin ou élu ado ?

:interrobang: Rôle du président dans l'api ?

:interrobang: Informations fournies par les membres ? Ex : Nom, Prenom…***

:date: *Lundi 26 Janvier 2015*