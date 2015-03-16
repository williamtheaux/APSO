-- Client: Apso
-- Généré le: Lun 16 Mars 2015 à 04:10
-- Version du serveur: 5.5.40-log
-- Version de PHP: 5.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `apso`
--

-- --------------------------------------------------------

--
-- Structure de la table `apso_amd`
--

CREATE TABLE IF NOT EXISTS `apso_amd` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_lois` int(11) NOT NULL,
  `amd` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `apso_func`
--

CREATE TABLE IF NOT EXISTS `apso_func` (
  `id_poste` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  KEY `id_poste` (`id_poste`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Contenu de la table `apso_func`
--

INSERT INTO `apso_func` (`id_poste`, `name`) VALUES
(0, 'addPoste'),
(2, 'addPoste'),
(0, 'deletePoste'),
(2, 'deletePoste'),
(0, 'editeRole'),
(2, 'editeRole'),
(0, 'deleteLois'),
(2, 'deleteLois'),
(0, 'deleteAmd'),
(2, 'deleteAmd'),
(0, 'editeAmd'),
(2, 'editeAmd'),
(0, 'editeLois'),
(2, 'editeLois');

-- --------------------------------------------------------

--
-- Structure de la table `apso_log`
--

CREATE TABLE IF NOT EXISTS `apso_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `action` varchar(45) NOT NULL,
  `date` bigint(45) NOT NULL,
  `jdata` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `apso_lois`
--

CREATE TABLE IF NOT EXISTS `apso_lois` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `apso_poste`
--

CREATE TABLE IF NOT EXISTS `apso_poste` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `poste` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `apso_poste`
--

INSERT INTO `apso_poste` (`id`, `poste`) VALUES
(1, 'Président'),
(2, 'Secrétaire'),
(3, 'Trésorier'),
(4, 'Vice-Président');

-- --------------------------------------------------------

--
-- Structure de la table `apso_user`
--

CREATE TABLE IF NOT EXISTS `apso_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adr` varchar(45) NOT NULL DEFAULT '0',
  `nom` varchar(45) NOT NULL,
  `prenom` varchar(45) NOT NULL,
  `date` bigint(45) NOT NULL,
  `role` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `apso_vote`
--

CREATE TABLE IF NOT EXISTS `apso_vote` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id1` int(11) NOT NULL,
  `id2` int(11) NOT NULL,
  `type` varchar(45) NOT NULL,
  `signe` varchar(65) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
