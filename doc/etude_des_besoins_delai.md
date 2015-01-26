# Etude des besoins et délai
**Application web APSO**
***Ce document comporte les besoins et délai pour la conception etdéveloppement d'api pour l'appareillage d'expérimentation de la démocratie directe. APSO.***

## Les besoins

* **Pour le vote anonyme :**	* Utilisation d'un algorithme de cryptographie asymétrique. Ça vous aidera à rendre votre api beaucoup plus sure, et par la suite, ça vous aidera dans l'anonymisation du vote. Vous pouvez utiliser la technologie de la cryptographie asymétrique Bitcoin, déjà disponible.
* **L'architecture :**	* L'api doit être modulable avec la possibilité de permettre d'ajouter de nouvelles fonctionnalités dans le future.	* Respecter l'architecture MVC ( Modèle Vue Contrôleur).	* Respecter les dernières normes et standard web.	* La cryptographie RSA devra être adoptée en php et JavaScript.	* La communication client/serveur est réalisée grâce au protocole JSON RPC 2.		* **Côté serveur :**				* Le serveur web représente ici, le coeur de votre system. Centraliser est contrôlé par une administration. Les données sont stoker dans une base MySQL. Ils sont publics et ne représentent aucun risque de vol. Le serveur ne garde pas les données prives.				* Impore de la librairie ECC (Elliptic Curve Cryptography) pour la gestion des algorithmes de signature numérique. L'authentification de l'utilisateur sera vérifiée avec ça signature.				* L'API serveur est réalisé en PHP5, développement orienté objet et une architecture en MC (Modèle Contrôleur). Les données sont retournées en JSON.				* L'API est constitué de deux principaux contrôleurs.						* **Contrôleurs client :**				* Regroupe toutes les fonctions de gestion d'information public, inscription et l'insertion des données par les membres.						* **Contrôleurs admin :**				* Regroupe toutes les fonctions de gestion, suppression et d'édition des données ajouter par les membres.

	* **Côté client :**
		
		* Le client est une application développer uniquement en jQuery, HTML5, CSS3. Sont but est de communiquer avec le serveur, récupérer les informations est de les afficher au client final. Avec une architecture en MVC (Modèle Vue Contrôleur).				* Héberger sur un serveur est accessible directement depuis un navigateur. Accéder à votre espace depuis n'importe où dans le monde.				* L'application intégrer la technologie responsive, est son design s'adapte à tous les écrans et téléphones portable.				* Impore de la librairie Bitcoin.js pour la gestion des algorithmes de cryptographie RSA. L'authentification du client et la gestion des clès public/privé utilise cette class. Pour se connecter, l'utilisateur doit fournir une phrase secrète. Toute la gestion du code, ce pass côté client en JavaScript. Aucune information personnelle est transmise côté serveur.				* Je vous conseille de crée, deux applications, une pour les membres, et l'autre pour la partie administration.						* L'application membre, affiche et accepter les inscriptions, authentification, vote, ajout de règles et leur révision. Accessible a l'URL principal du site. Elle communique avec le contrôleur client.						* L'application admin, contrôle et modifie toutes les données ajouter par les membres. Accessible a l'URL (/adm) du site. Elle communique avec le contrôleur admin.
***

## Le délai

1. *La première étape* **- Les spécifications fonctionnels**

	* C'est la description des fonctions de votre logiciel en vue de sa réalisation. C'est l'architecture de votre application. Cela occupe beaucoup de temps et reste une partie importante du budget.
	
	* Il faudra compter deux à trois semaines de réalisation. Pendant ce temps, vous et moi, devrions avoir une rdv téléphonique au moins une foi tous les deux jours.
	
	* **À cette étape :** Votre API est décrit dans un document en regroupant toutes les fonctionnalités nécessaires a la réalisation du projet.

2. *La deuxième étape* **- Les spécifications techniques**

	* À partir des spécifications fonctionnelles, on détermine le nommage des fonctions. Conception de la base de données. Ça sera la feuille de route pour le développement.
	* Ce document demande moins de 6 jours de travail.
	
	* **À cette étape :** Votre documentation contient toutes les informations techniques pour le bon déroulement du développement.

3. *La troisième étape* **- Domaine et hébergement**

	* Acquisition du nom de domaine avec Dynamic DNS et whoisguard protected.
	* Acquisition Hébergement web pour professionnels. Infomaniak.
		* extensions PHP multiples précision mathématique de librarary: GMP et bcmath.
		* Support de Fopen et des includes.
	* Acquisition certificat SSL. (https://).
	* Redirection des DNS sur le IP du serveur.
	* Creation des mail, comptes FTP et SQL.
	* Délai entre 2 et 3 jours.
	
	* **À cette étape :** Vous posséder un nom de demain protéger, un hébergeur séparer et capable de supporter les classe PHP exiger. Un certificat SSL. Les codes d'access pour le serveur, ftp, domaine, mail, db.
4. *La quatrième étape* **- Base de données**

	* Réalisation de la base de données.
	* Délai : 1 jour de travail.
	
	* **À cette étape :** Votre base de données est opérationnelle est prés à recevoir les premiers membres, vote ou règle.

5. *La cinquième étape* **- L'API serveur**

	* Conception et réalisation de l'api serveur. Dossier BETA.
	* Développement des contrôleurs et leurs fonctions.
	* Création des model pour interagir avec la base de données.
	* Test et contrôle des bug.
	* Mise en place de l'API côté production.
	* Délai : 3 semaines de travail.
	
	* **À cette étape :** Votre API est opérationnel. Il suffit de l'interroger grâce au JSON RPC pour communiquer avec elle. Votre serveur est fonctionnel et APSO peut accueillir ses premiers membres.
	
6. *La sixième étape* **- L'application client**

	* Conception et réalisation de l'application pour les membres. Dossier BETA.
	* Développement des modules et leurs fonctions en jQuery.
	* Communication avec le serveur.
	* Integration de la gestion des données et formulaires.
	* Design de la partie client.
	* Création et intégration des textes.
	* Test et contrôle des bug.
	* Mise en place de l'application côté production.
	* Délai : 2 semaines de travail. Pour 4 pages formulaire, leur fonction de traitement et 4 pages de gestion + affichages des données.
	
	* **À cette étape :** Votre application est opérationnelle. Le client a une structure pour pouvoir s'inscrire, voter, proposer des règles. L'application est reliée au serveur.
	
7. *La septième étape* **- L'application administrateurs**

	* Conception et réalisation de l'application pour les administrateurs. Dossier BETA.
	* Développement des modules et leurs fonctions en jQuery.
	* Communication avec le serveur.
	* Integration de la gestion des données et formulaires.
	* Design de la partie administrateurs..
	* Création et intégration des textes.
	* Test et contrôle des bug.
	* Mise en place de l'application côté production.
	* Délai : 2 semaines de travail. Pour 4 pages formulaire, leur fonction de traitement et 4 pages de gestion + affichages des données.
	
	* **À cette étape :** L'application admin est opérationnelle. Les administrateurs peuvent gérer et éditer les données des membres. Accéder aux statistiques. L'application est reliée au serveur.
	
***

*Vendredi 23 Janvier 2015*