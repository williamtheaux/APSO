# Spécification technique (STB V.0.1)

**Application web APSO**

> Ce document comporte l’ensemble des règles, nommage des fonctions, conception de la base de données. Ça sera la feuille de route pour le développement.

***

## Introduction

**Appareillage d'expérimentation de la démocratie directe.**


* **Fonctions propriétaires** définies pour les postes crées précédemment. Permet de définir des fonctions propriétaires accessibles seulement a l'utilisateur en poste à ce moment-là. Les fonctions sont modulables pour être ajoutées ou supprimer. Elles pourront être utilisées sur plusieur postes simultanément.
	> Issues [#1](https://github.com/williamtheaux/APSO/issues/1) [#3](https://github.com/williamtheaux/APSO/issues/3) Par défaut, 5 fonctions sont déja crée pour le poste de **secrétaire** :
	* Suppression et mise en forme des lois et leurs amendements.
	* Valider ou invalider les citoyens. Bas niveaux.
	* Ajouter ou supprimer des postes pour les élections

* L'application comporte le **suffrage universel**. Une section ou chaque votant peut créer des nouvelles lois, proposer des amendements pour des lois existantes, et enfin, exprimer sont vote.
	* Les lois considérés élus, Quant au moins 50% des utilisateurs ont sélectionnées un amendement dans cette loi. Les 50% pourront être changés directement dans les configurations du code.

* En cas **d'égalité ou ballotage**, les postes ou lois reste inchangées. Le résultat est en permanence accessible pour tous.
	> Issues [#4](https://github.com/williamtheaux/APSO/issues/4) Commencer le dépouillement des postes par le début de la liste, si l'utilisateur est déjà élu dans un poste précédant, alors choisir la personne en second élu pour le poste.
	* Si les utilisateurs son élu avec le même score, alors c'est le premier de la liste qui obtient le poste. Adopter le même comportement aux élections des amendements.

* Les **administrateurs** peuvent :
	> Sont ajoutés au Master tous les autres droits (intervention sur les données et sur le système) Par défaut, 7 fonctions sont déja crée.
	* Ajouter ou supprimer des postes pour les élections.
	* Valider ou invalider les citoyens. Haut niveaux.
	* Suppression et mise en forme des lois et leurs amendements.
	* Ajoutées ou supprimer les fonctions propriétaires.

* L'application comporte un **log**, historique de toutes les actions effectuer par les citoyens et les administrateurs. Ses informations sont accessible sur les deux applications.

* Donner un espace de connexion pour les **observateurs**.
	* Les observateurs accéderont aux mêmes données que les citoyens.

***

## Connexion des utilisateurs

L'utilisateur entre, côté client une phrase secrétée qui génère une **clé public**, avec là qu'elle, il est identifié côté serveur. Le serveur n'a plus besoins de son mail et mot de pass, de se fait ça enlève le problème de sécurité côté serveur, il y a plus besoin de le pirater, car il ne garde plus aucune donnée **sensible** concernant le client.

Si l'utilisateur doit faire une modification exigent son authentification, un message est **signé** côté client, le serveur na plus qu'a vérifier la validité de la signature pour identifier que c'est bien l'utilisateur qui a fait la demande.

**∆** Tout le compte est régénérer à partir de la phrase secrète à chaque utilisation et les données sont traitées en **local** avec JavaScript.

**∆** Un nouveau code pin est demander a la connexion pour un **cryptage symétrique** de la phrase secrète pendant toute la durée de la session locale. le code pin n'est stocké nulle part.

**∆** Tout le systeme cryptographique reste **invisible** pour le client final.

***

## Gestion des rôles et accées.

L'utilisateur dispose de 5 rôles. Les rôles sont attribués par les administrateurs ou par les postes, disposant de la fonction propriétaires.

| Rôle | Desc |
|------|------|
| guest | Le rôle par défaut après l'inscription de l'utilisateur dans l'api. Retourne un message "En attente de validation par un administrateur." |
| banni | Concerne les utilisateurs bannis par un administrateur ou un poste disposant de la fonction propriétaire. Regroupe aussi les visiteurs non identifiés après l'inscription. Retourne un message "Vous êtes bloqué. Veuillez contacter un administrateur." |
| obs | Le groupe des Observateur, ont seulement accès a l'historique et les résulta du suffrage. |
| citoyen | Les citoyens ont le droit d'exprimer leurs votes et proposer de nouvelles lois. Ils peuvent aussi être élus. |
| admin | Ils gèrent et modifient toutes les données de l'api. |


les postes permet de définir des fonctions propriétaires accessibles seulement a l'utilisateur élu au poste à ce moment-là. Par défaut, 4 postes sont déja crée.

| Poste | Nom |
|-------|-----|
| 1 | Président |
| 2 | Secrétaire |
| 3 | Trésorier |
| 4 | Vice-Président |

***

## Anonymisation des votes

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

***

## Fonctions propriétaires

* L'application cliente gère le retour JSON de connexion dans des variables séparé. Banni, Guest, Observateur, citoyen, Administrateur. Chaque fonction jQuery n'aura plus qu'a vérifier la présence de la variable pour afficher ou non les informations. Les fonctions propriétaires seront placées directement dans la variable admin par le serveur. L'app client ne vérifie pas directement le poste du citoyen, mais affiche ou non les infos de la variable Administrateur. Si un codeur modifie les données pendant l'exécution de l'app et accède au fonctions propriétaires. Le serveur rejettera ces demandes. 

***

# ∑ Architecture

> Le projet est constitué d'une api côté serveur et d'une application web côté client.

***

## ∑ Application Client

> L'application client, affiche et accepter les inscriptions, authentification, vote, ajout de lois et leur amendements. C'est la partie graphique qui est affichée au client.

![App architecture](annexes/appArchitect.jpg)

### Ω Accueil HTML
> C'est la porte d'entrée de votre application. Elle se situe au sommet de la hiérarchie. C'est une page qui explique clairement ce qu'on va trouver sur votre application. C'est la page la plus visitée. Si l'utilisateur est connecter, Elle affiche les données de l'utilisateur.

* *Accès*
	* Directement sur le domaine principal. https://domaine.com
* *Maquette*
	* Couleur dominant est le vert. Un arrière-plan blanc, photo.
		* **Si connecter** Données de l'utilisateur.
		* **Si no connecter** un formulaire de connexion et un slide de 3 ou 4 paragraphes.
* *Informations*
	* **Si connecter**
		* **Texte**
			* **Titre :** Mon compte
		* **Variable interne**
			* Id publique. L'adresse bitcoin.
			* Clé privée.
			* Phrase secrète crypter.
			* Données serveur.
	* **Si no connecter**
		* **Texte pour le slide**
			1. **Titre :** La démocratie directe **Desc :** Des élections anonymes en temps réel ou chaque citoyen peut changer à tous moment sont vote, et basculer le résulta final du scrutin.
			2. **Titre :** Le suffrage universel **Desc :** Exprime un choix, une volonté. Ici chaque citoyen peut créer des lois, proposer des amendements, et enfin, exprimer sont vote.
		* **Input**
			* La phrase secrète pour le cryptage asymétrique.
			* Le code pin pour le cryptage symétrique de la phrase secrète.
* *Actions possibles*
	* **Si connecter**
		* Si l'utilisateur n'est pas reconue, afficher la page `Ω SignUp HTML`
		* Si l'utilisateur est banni, afficher la page `Ω Block HTML`
	* **Si no connecter**
		* Connexion avec l'application. `Ω Connexion FUNC`
* *Règles de gestion*
	* **Si connecter**
		* Si les données de connexion son present.
	* **Si no connecter**
		* Validation des champs pendant submit.

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

### Ω Login FUNC
> Elle lance un appel à l'api pour les information client. Après analyse des données reçu et si le role d'accès est autorisé, elle lance un événement dans l'application.

* *Accès*
	* Juste après la connexion.
	* Accès rôle **Banni**.
* *Informations*
	* **Variable interne**
		* Id publique. L'adresse bitcoin.
		* Clé privée.
		* Phrase secrète crypter.
* *Actions possibles*
	* Si l'appel échoue, annulé la connexion et lancer une erreur.
	* Editer le model, si l'utilisateur est au minimum **observateur**, lancer un événement et afficher la page `Ω Accueil HTML`.
* *Règles de gestion*
	* Analyse du JSON retourner par le serveur.

### Ω Déconnexion FUNC
> Elle efface toutes les variable du model, lance un événement de déconnexion dans l'application.

* *Accès*
	A partir du menu principal.
	* Accès rôle **Banni**.
* *Actions possibles*
	* Editer le model, lancer un événement et afficher la page `Ω Accueil HTML`.
* *Règles de gestion*
	* Ne pas afficher si l'utilisateur n'est pas connecté.

### Ω SignUp HTML
> Si l'utilisateur n'est pas dans la base de données. Elle affiche un formulaire pour s'inscrire.

* *Accès*
	* Juste après la connexion. Elle est affichée si l'utilisateur ne fut pas trouvé dans la base de données.
	* Accès rôle **Guest**.
* *Maquette*
	* Composer d'un formulaire, icon, titre + Desc.
* *Informations*
	* **Texte**
		* **Titre :** Finaliser votre inscription
		* **Desc :** Pour finaliser le processus d'inscription, veuillez envoyer votre nom et prénom.
	* **Input**
		* Le nom
		* Le prénom
* *Actions possibles*
	* Déclencher la fonction `Ω SignUp FUNC`.
* *Règles de gestion*
	* Validation des champs pendant submit.

### Ω SignUp FUNC
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

### Ω Valide HTML
> Si l'utilisateur n'est pas validé par un administrateur. Elle affiche un message de mise en attente.

* *Accès*
	* Juste après la connexion. Elle est affichée si l'utilisateur fut trouvé dans la base de données mais toujours rôle **guest**.
	* Accès rôle **Guest**.
* *Maquette*
	* Composer de icon, titre + Desc.
* *Informations*
	* **Texte**
		* **Titre :** Validation du compte
		* **Desc :** Votre compte est actuellement en attente d'approbation. Une fois cette actions effectuées, vous pourrez poursuivre pour découvrir le déroulement du processus de vote.

### Ω Block HTML
> Si l'utilisateur est banni. Elle affiche un message de bannissement.

* *Accès*
	* Juste après la connexion. Elle est affichée si l'utilisateur est banni.
	* Accès rôle **Banni**.
* *Maquette*
	* Composer de icon, titre + Desc.
* *Informations*
	* **Texte**
		* **Titre :** Compte bloqué
		* **Desc :** Votre compte a été désactivé par un administrateur. Veuillez contacter le service support pour plus d'information.

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

## ∑ Api serveur

> Api dédiée en PHP avec le protocole JSON RPC 2, permettant la démocratie en temps-réel. C'est la partie qui centralise et distribue les données. Elle gère la gestion des actions possibles.

![App architecture](annexes/apiArchitect.jpg)

### Ω login
> Connexion de l'utilisateur.  Si le client se connecte pour la première fois et n'est pas reconnu par l'api, il est invité de finir son inscription en fournissant des données supplémentaires : **Nom** et **Prénom**.

* **Informations entrantes**
	* Identifiant client (adresse bitcoin)
	* Timestamp
	* Signiature (hash Timestamp+Identifiant)
* **Règles de gestion**
	1. Vérification des données entrante. Timestamp dans les 12h du timestamp serveur.
	2. Recherche de l'utilisateur dans la base de données.
	3. Vérification du rôle de l'utilisateur.
		* Si Banni. Retourner la reponse.
		* Si Guest. Retourner la reponse.
	4. Sélectionner toute la base de données.
	5. Boucle sur la table utilisateur.
		* Séparer les utilisateurs par rôle.
		* Compter les utilisateurs par rôle.
	6. Boucle sur la table vote.
		* Séparer les votes par type.
			* Type poste
				* Vérifier le poste et l'utilisateur choisi.
				* Incrémenter la variable de vote des postes.
			* Type loi
				* Vérifier la loi et l'amendement choisi.
				* Incrémenter la variable de vote des lois.
	7. Boucle sur la variable vote poste.
		* Déterminer une liste de postes avec leurs utilisateurs élus. Commencer par le début de la liste, si l'utilisateur est déjà élu dans un poste précédant, alors choisir la personne en second élu pour le poste.
	8. Boucle sur la variable vote loi.
		* Déterminer une liste de lois avec leurs amendements élus.
	9. Boucle sur la variable de l'historique.
		* Marquer les actions du client.
	10. Vérifier si le client appartient à un poste élu.
	11. Si Administrateur ou poste. Inclure les variables dans le retour.

* **Informations sortantes**
> Les données seront retournées dans les variables de rôle. L'app client n'a plus que vérifier le contenu de chaque variable.
	* Banni
	* Guest
	* Observateur
	* Citoyen
	* Administrateur

### Ω SignUp
> inscription de l'utilisateur.

* **Informations entrantes**
	* Identifiant client (adresse bitcoin)
	* Nom
	* Prénom
	* Signiature (hash nom+prénom+'Action'+Identifiant)
* **Règles de gestion**
	1. Vérification des données entrante.
	2. Recherche de l'utilisateur dans la base de données.
	3. Si pas d'utilisateur.
		* Enregistrait l'utilisateur.
		* Sauvegardait l'action d'ans l'historique.
	4. Sélectionner toutes les données de connexion (`login` 4-11).
* **Informations sortantes**
	* Les données seront retournées comme dans login.

### Ω addPoste
> Ajouter un nouveaux poste.

* **Informations entrantes**
	* Identifiant client (adresse bitcoin)
	* Poste
	* Signiature (hash Poste+'Action'+Identifiant)
* **Règles de gestion**
	1. Vérification des données entrante.
	2. Recherche de l'utilisateur dans la base de données.
	3. Vérification du rôle de l'utilisateur.
		* Si administrateur, alors poursuivre.
		* Si citoyen, vérifier les poste est les élus.
	4. Enregistrait le poste.
	5. Sauvegardait l'action d'ans l'historique.
	6. Sélectionner toutes les données de connexion (`login` 4-11).
* **Informations sortantes**
	* Les données seront retournées comme dans `upData`.

### Ω deletePoste
> Suppression du poste.

* **Informations entrantes**
	* Identifiant client (adresse bitcoin)
	* Identifiant Poste
	* Signiature (hash idPoste+Poste+'Action'+Identifiant)
* **Règles de gestion**
	1. Vérification des données entrante.
	2. Recherche de l'utilisateur dans la base de données.
	3. Vérification du rôle de l'utilisateur.
		* Si administrateur, alors poursuivre.
		* Si citoyen, vérifier les poste est les élus.
	4. Suppression du poste.
		* Suppression des votes.
	5. Sauvegardait l'action d'ans l'historique.
	6. Sélectionner toutes les données de connexion (`login` 4-11).
* **Informations sortantes**
	* Les données seront retournées comme dans `upData`.

### Ω editeRole
> Editer le role.

* **Informations entrantes**
	* Identifiant client (adresse bitcoin)
	* role
	* id client
	* Signiature (hash idUser+Role+'Action'+Identifiant)
* **Règles de gestion**
	1. Vérification des données entrante.
	2. Recherche de l'utilisateur dans la base de données.
	3. Vérification du rôle de l'utilisateur.
		* Si administrateur, alors poursuivre.
		* Si citoyen, vérifier les poste est les élus.
	4. Recherche le client dans la base de données.
	5. Vérifier le rôle du client.
		* Si administrateur, lancer une erreur.
	6. Modifier le rôle du client.
		* Si banni, effacer els votes.
	7. Sauvegardait l'action d'ans l'historique.
	8. Sélectionner toutes les données de connexion (`login` 4-11).
* **Informations sortantes**
	* Les données seront retournées comme dans `upData`.

### Ω Vote
> Permet de voter.

* **Informations entrantes**
	* Identifiant client (adresse bitcoin)
	* Type
	* id 1
	* id 2
* **Règles de gestion**
	1. Vérification des données entrante.
	2. Recherche de l'utilisateur dans la base de données.
	3. Vérification du rôle de l'utilisateur.
		* Si citoyen ou administrateur, alors poursuivre.
	4. Vérifier si le client a voté pour ce type et id.
		* SI oui, modifier le vote.
		* Si non, Sauvegardait le vote.
	5. Crée un hash du vote.
* **Informations sortantes**
	* Id du vote
	* Hash pour la signature.

### Ω fixVote
> Permet de confirmer son voter

* **Informations entrantes**
	* Identifiant client (adresse bitcoin)
	* id vote
	* Signiature (Hash)
* **Règles de gestion**
	1. Vérification des données entrante.
	2. Recherche de l'utilisateur dans la base de données.
	3. Vérification du rôle de l'utilisateur.
		* Si citoyen ou administrateur, alors poursuivre.
	4. Vérifier le hash.
	6. Sauvegardait la signature.
	7. Sauvegardait l'action d'ans l'historique.
* **Informations sortantes**
	* Les données seront retournées comme dans `upData`.

### Ω addLois
> Ajouter une nouvelle loi.

* **Informations entrantes**
	* Identifiant client (adresse bitcoin)
	* nom
	* Signiature (hash 'Loi'+Identifiant)
* **Règles de gestion**
	1. Vérification des données entrante.
	2. Recherche de l'utilisateur dans la base de données.
	3. Vérification du rôle de l'utilisateur.
		* Si citoyen ou administrateur, alors poursuivre.
	4. Enregistrait la loi.
	5. Sauvegardait l'action d'ans l'historique.
	6. Sélectionner toutes les données de connexion (`login` 4-11).
* **Informations sortantes**
	* Les données seront retournées comme dans `upData`.

### Ω addAmd
> Ajouter un nouveaux amendement.

* **Informations entrantes**
	* Identifiant client (adresse bitcoin)
	* amendement
	* id loi
	* Signiature (hash 'Loi'+'amendement'+Identifiant)
* **Règles de gestion**
	1. Vérification des données entrante.
	2. Recherche de l'utilisateur dans la base de données.
	3. Vérification du rôle de l'utilisateur.
		* Si citoyen ou administrateur, alors poursuivre.
	4. Enregistrait l'amendement.
	5. Sauvegardait l'action d'ans l'historique.
	6. Sélectionner toutes les données de connexion (`login` 4-11).
* **Informations sortantes**
	* Les données seront retournées comme dans `upData`.

### Ω editeLois
> Editer une loi.

* **Informations entrantes**
	* Identifiant client (adresse bitcoin)
	* nom
	* id loi
	* Signiature (hash idLoi+'nom'+Identifiant)
* **Règles de gestion**
	1. Vérification des données entrante.
	2. Recherche de l'utilisateur dans la base de données.
	3. Vérification du rôle de l'utilisateur.
		* Si administrateur, alors poursuivre.
		* Si citoyen, vérifier les poste est les élus.
	4. Modifier la loi.
	5. Sauvegardait l'action d'ans l'historique.
	6. Sélectionner toutes les données de connexion (`login` 4-11).
* **Informations sortantes**
	* Les données seront retournées comme dans `upData`.

### Ω editeAmd
> Editer un amendement.

* **Informations entrantes**
	* Identifiant client (adresse bitcoin)
	* nom
	* id loi
	* id Amd
	* Signiature (hash idLoi+idAmd+'nom'+Identifiant)
* **Règles de gestion**
	1. Vérification des données entrante.
	2. Recherche de l'utilisateur dans la base de données.
	3. Vérification du rôle de l'utilisateur.
		* Si administrateur, alors poursuivre.
		* Si citoyen, vérifier les poste est les élus.
	4. Modifier l'amendement.
	5. Sauvegardait l'action d'ans l'historique.
	6. Sélectionner toutes les données de connexion (`login` 4-11).
* **Informations sortantes**
	* Les données seront retournées comme dans `upData`.

### Ω deleteLoi
> Suppression d'une loi.

* **Informations entrantes**
	* Identifiant client (adresse bitcoin)
	* id loi
	* Signiature (hash idLoi+Identifiant)
* **Règles de gestion**
	1. Vérification des données entrante.
	2. Recherche de l'utilisateur dans la base de données.
	3. Vérification du rôle de l'utilisateur.
		* Si administrateur, alors poursuivre.
		* Si citoyen, vérifier les poste est les élus.
	4. Suppression de la loi.
		* Suppression des votes.
	5. Sauvegardait l'action d'ans l'historique.
	6. Sélectionner toutes les données de connexion (`login` 4-11).
* **Informations sortantes**
	* Les données seront retournées comme dans `upData`.

### Ω deleteAmd
> Suppression d'un amendemente.

* **Informations entrantes**
	* Identifiant client (adresse bitcoin)
	* id loi
	* id Amd
	* Signiature (hash idLoi+idAmd+Identifiant)
* **Règles de gestion**
	1. Vérification des données entrante.
	2. Recherche de l'utilisateur dans la base de données.
	3. Vérification du rôle de l'utilisateur.
		* Si administrateur, alors poursuivre.
		* Si citoyen, vérifier les poste est les élus.
	4. Suppression de l'amendemente.
		* Suppression des votes.
	5. Sauvegardait l'action d'ans l'historique.
	6. Sélectionner toutes les données de connexion (`login` 4-11).
* **Informations sortantes**
	* Les données seront retournées comme dans `upData`.

### Ω upData
> Mise à jour des données toutes les minutes.

* **Informations entrantes**
	* Identifiant client (adresse bitcoin)
	* Timestamp
	* Signiature (hash Timestamp+Identifiant)
* **Règles de gestion**
	1. Vérification des données entrante. Timestamp dans les 12h du timestamp serveur.
	2. Recherche de l'utilisateur dans la base de données.
	3. Vérification du rôle de l'utilisateur.
		* Si Banni. Retourner la reponse.
		* Si Guest. Retourner la reponse.
	4. Sélectionner toute la base de données.
	5. Boucle sur la table utilisateur.
		* Séparer les utilisateurs par rôle.
		* Compter les utilisateurs par rôle.
	6. Boucle sur la table vote.
		* Séparer les votes par type.
			* Type poste
				* Vérifier le poste et l'utilisateur choisi.
				* Incrémenter la variable de vote des postes.
			* Type loi
				* Vérifier la loi et l'amendement choisi.
				* Incrémenter la variable de vote des lois.
	7. Boucle sur la variable vote poste.
		* Déterminer une liste de postes avec leurs utilisateurs élus. Commencer par le début de la liste, si l'utilisateur est déjà élu dans un poste précédant, alors choisir la personne en second élu pour le poste.
	8. Boucle sur la variable vote loi.
		* Déterminer une liste de lois avec leurs amendements élus.
	9. Boucle sur la variable de l'historique.
		* Marquer les actions du client.
	10. Vérifier si le client appartient à un poste élu.
	11. Si Administrateur ou poste. Inclure les variables dans le retour.
* **Informations sortantes**
	* Banni
	* Guest
	* Observateur
	* Citoyen
	* Administrateur

***

## Annexes

* PDF [Cryptographic Voting Protocols: A Systems Perspective](annexes/karlof.pdf)
* PDF [David Chaum’s Voter Verification using Encrypted Paper Receipts](annexes/voter_verification_using_Encrypte.pdf)
* PDF [Secure Electronic Voting Protocols](annexes/voting4hb.pdf)
* URL [Bitcoin pour des votes gratuits et vérifiables](http://www.e-ducat.fr/bitcoin-pour-des-votes-gratuits-et-verifiables/)

***

*Lundi 26 Janvier 2015*