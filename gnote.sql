-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 29 juil. 2022 à 12:10
-- Version du serveur :  5.7.31
-- Version de PHP : 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gnote`
--

-- --------------------------------------------------------

--
-- Structure de la table `absence`
--

DROP TABLE IF EXISTS `absence`;
CREATE TABLE IF NOT EXISTS `absence` (
  `id_absence` int(11) NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int(11) NOT NULL,
  `id_eleve` int(11) NOT NULL,
  `id_annee` int(11) NOT NULL,
  `id_semestre` int(11) NOT NULL,
  `id_heure` int(11) NOT NULL,
  `commentaire` text NOT NULL,
  `date_ajout` date NOT NULL,
  `date_sup` datetime DEFAULT NULL,
  `statut` int(11) NOT NULL,
  PRIMARY KEY (`id_absence`),
  KEY `id_annee` (`id_annee`),
  KEY `id_eleve` (`id_eleve`),
  KEY `id_semestre` (`id_semestre`),
  KEY `id_utilisateur` (`id_utilisateur`),
  KEY `id_heure` (`id_heure`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `allocation`
--

DROP TABLE IF EXISTS `allocation`;
CREATE TABLE IF NOT EXISTS `allocation` (
  `id_allocation` int(11) NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int(11) NOT NULL,
  `id_annee` int(11) NOT NULL,
  `id_eleve` int(11) NOT NULL,
  `first_semestre` int(11) DEFAULT NULL,
  `second_semestre` int(11) DEFAULT NULL,
  `date_ajout` date NOT NULL,
  `date_sup` datetime DEFAULT NULL,
  `date_paiement` datetime DEFAULT NULL,
  `statut` int(11) NOT NULL,
  PRIMARY KEY (`id_allocation`),
  KEY `id_annee` (`id_annee`),
  KEY `id_eleve` (`id_eleve`),
  KEY `id_utilisateur` (`id_utilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `annee`
--

DROP TABLE IF EXISTS `annee`;
CREATE TABLE IF NOT EXISTS `annee` (
  `id_annee` int(11) NOT NULL AUTO_INCREMENT,
  `libelle_annee` varchar(255) NOT NULL,
  `date_ajout` date NOT NULL,
  `date_sup` datetime DEFAULT NULL,
  `statut` int(11) NOT NULL,
  PRIMARY KEY (`id_annee`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `annee`
--

INSERT INTO `annee` (`id_annee`, `libelle_annee`, `date_ajout`, `date_sup`, `statut`) VALUES
(1, '2021/2022', '2022-06-01', NULL, 1),
(2, '2022/2023', '2022-06-01', NULL, 1);

-- --------------------------------------------------------

--
-- Structure de la table `annee_encours`
--

DROP TABLE IF EXISTS `annee_encours`;
CREATE TABLE IF NOT EXISTS `annee_encours` (
  `id_annee_encours` int(11) NOT NULL AUTO_INCREMENT,
  `id_annee` int(11) NOT NULL,
  `id_semestre` int(11) NOT NULL,
  `statut` int(11) NOT NULL,
  PRIMARY KEY (`id_annee_encours`),
  KEY `id_annee` (`id_annee`),
  KEY `id_semestre` (`id_semestre`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `annee_encours`
--

INSERT INTO `annee_encours` (`id_annee_encours`, `id_annee`, `id_semestre`, `statut`) VALUES
(3, 1, 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `bulletin`
--

DROP TABLE IF EXISTS `bulletin`;
CREATE TABLE IF NOT EXISTS `bulletin` (
  `id_bulletin` int(11) NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int(11) NOT NULL,
  `id_eleve` int(11) NOT NULL,
  `id_annee` int(11) NOT NULL,
  `id_semestre` int(11) NOT NULL,
  `total` double NOT NULL,
  `moyenne` double NOT NULL,
  `rang` int(11) DEFAULT NULL,
  `date_ajout` date NOT NULL,
  `date_sup` datetime DEFAULT NULL,
  `statut` int(11) NOT NULL,
  PRIMARY KEY (`id_bulletin`),
  KEY `id_annee` (`id_annee`),
  KEY `id_eleve` (`id_eleve`),
  KEY `id_semestre` (`id_semestre`),
  KEY `id_utilisateur` (`id_utilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `cahier_texte`
--

DROP TABLE IF EXISTS `cahier_texte`;
CREATE TABLE IF NOT EXISTS `cahier_texte` (
  `id_cahier_texte` int(11) NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int(11) NOT NULL,
  `id_annee` int(11) NOT NULL,
  `id_semestre` int(11) NOT NULL,
  `id_jour` int(11) NOT NULL,
  `id_heure` int(11) NOT NULL,
  `contenu` text NOT NULL,
  `date_ajout` date NOT NULL,
  `date_sup` datetime DEFAULT NULL,
  `validation` int(11) NOT NULL,
  `statut` int(11) NOT NULL,
  PRIMARY KEY (`id_cahier_texte`),
  KEY `id_annee` (`id_annee`),
  KEY `id_heure` (`id_heure`),
  KEY `id_semestre` (`id_semestre`),
  KEY `id_utilisateur` (`id_utilisateur`),
  KEY `id_jour` (`id_jour`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `classe`
--

DROP TABLE IF EXISTS `classe`;
CREATE TABLE IF NOT EXISTS `classe` (
  `id_classe` int(11) NOT NULL AUTO_INCREMENT,
  `id_niveau` int(11) NOT NULL,
  `code_classe` varchar(255) NOT NULL,
  `libelle_classe` varchar(255) NOT NULL,
  `nbre_place` int(11) DEFAULT NULL,
  `date_ajout` date NOT NULL,
  `date_sup` datetime DEFAULT NULL,
  `statut` int(11) NOT NULL,
  PRIMARY KEY (`id_classe`),
  KEY `id_niveau` (`id_niveau`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `classe_prof`
--

DROP TABLE IF EXISTS `classe_prof`;
CREATE TABLE IF NOT EXISTS `classe_prof` (
  `id_classe_prof` int(11) NOT NULL AUTO_INCREMENT,
  `id_classe` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `id_annee` int(11) NOT NULL,
  `date_ajout` date NOT NULL,
  `date_sup` datetime DEFAULT NULL,
  `responsable` int(11) NOT NULL,
  `statut` int(11) NOT NULL,
  PRIMARY KEY (`id_classe_prof`),
  KEY `id_classe` (`id_classe`),
  KEY `classe_prof_ibfk_2` (`id_utilisateur`),
  KEY `id_annee` (`id_annee`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `classe_surveillant`
--

DROP TABLE IF EXISTS `classe_surveillant`;
CREATE TABLE IF NOT EXISTS `classe_surveillant` (
  `id_classe_surveillant` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `id_classe` int(11) NOT NULL,
  `id_annee` int(11) DEFAULT NULL,
  `responsable` int(11) DEFAULT NULL,
  `date_ajout` date NOT NULL,
  `date_sup` datetime DEFAULT NULL,
  `statut` int(11) NOT NULL,
  PRIMARY KEY (`id_classe_surveillant`),
  KEY `id_classe` (`id_classe`),
  KEY `id_utilisateur` (`id_utilisateur`),
  KEY `id_annee` (`id_annee`),
  KEY `id_user` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `eleve`
--

DROP TABLE IF EXISTS `eleve`;
CREATE TABLE IF NOT EXISTS `eleve` (
  `id_eleve` int(11) NOT NULL AUTO_INCREMENT,
  `matricule_eleve` varchar(255) NOT NULL,
  `nom_eleve` varchar(255) NOT NULL,
  `prenom_eleve` varchar(255) NOT NULL,
  `datenais_eleve` date DEFAULT NULL,
  `lieunais_eleve` varchar(255) DEFAULT NULL,
  `tel_eleve` varchar(255) DEFAULT NULL,
  `nationalite_eleve` varchar(255) DEFAULT NULL,
  `sexe` varchar(1) DEFAULT NULL,
  `date_ajout` date NOT NULL,
  `date_sup` datetime DEFAULT NULL,
  `statut` int(11) NOT NULL,
  PRIMARY KEY (`id_eleve`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `exclure`
--

DROP TABLE IF EXISTS `exclure`;
CREATE TABLE IF NOT EXISTS `exclure` (
  `id_exclure` int(11) NOT NULL AUTO_INCREMENT,
  `id_eleve` int(11) NOT NULL,
  `id_annee` int(11) NOT NULL,
  `id_niveau` int(11) NOT NULL,
  `date_ajout` int(11) NOT NULL,
  `date_sup` int(11) DEFAULT NULL,
  `statut` int(11) NOT NULL,
  PRIMARY KEY (`id_exclure`),
  KEY `id_eleve` (`id_eleve`),
  KEY `id_niveau` (`id_niveau`),
  KEY `id_annee` (`id_annee`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `existant`
--

DROP TABLE IF EXISTS `existant`;
CREATE TABLE IF NOT EXISTS `existant` (
  `id_existant` int(11) NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int(11) DEFAULT NULL,
  `id_eleve` int(11) NOT NULL,
  `id_annee` int(11) NOT NULL,
  `id_niveau` int(11) NOT NULL,
  `redouble` varchar(255) DEFAULT NULL,
  `inscrit` int(11) NOT NULL,
  `date_ajout` date NOT NULL,
  `date_sup` datetime DEFAULT NULL,
  `statut` int(11) NOT NULL,
  PRIMARY KEY (`id_existant`),
  KEY `id_eleve` (`id_eleve`),
  KEY `id_niveau` (`id_niveau`),
  KEY `id_utilisateur` (`id_utilisateur`),
  KEY `id_annee` (`id_annee`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `generation`
--

DROP TABLE IF EXISTS `generation`;
CREATE TABLE IF NOT EXISTS `generation` (
  `id_generation` int(11) NOT NULL AUTO_INCREMENT,
  `id_annee` int(11) NOT NULL,
  `id_semestre` int(11) NOT NULL,
  `id_classe` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `date_generation` datetime NOT NULL,
  `statut` int(11) NOT NULL,
  PRIMARY KEY (`id_generation`),
  KEY `id_annee` (`id_annee`),
  KEY `id_classe` (`id_classe`),
  KEY `id_semestre` (`id_semestre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `heure`
--

DROP TABLE IF EXISTS `heure`;
CREATE TABLE IF NOT EXISTS `heure` (
  `id_heure` int(11) NOT NULL AUTO_INCREMENT,
  `libelle_heure` varchar(255) NOT NULL,
  `date_ajout` date NOT NULL,
  `date_sup` datetime DEFAULT NULL,
  `statut` int(11) NOT NULL,
  PRIMARY KEY (`id_heure`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `heure`
--

INSERT INTO `heure` (`id_heure`, `libelle_heure`, `date_ajout`, `date_sup`, `statut`) VALUES
(1, '08h-09h', '2022-02-19', NULL, 1),
(2, '09h-10h', '2022-02-19', NULL, 1),
(3, '10h-11h', '2022-02-19', NULL, 1),
(4, '11h30-12h30', '2022-02-19', NULL, 1),
(5, '12h30-13h30', '2022-02-19', NULL, 1),
(6, '08h-10h', '2022-02-19', NULL, 1),
(7, '9h-11h', '2022-02-19', NULL, 1),
(8, '11h30-13h30', '2022-02-19', NULL, 1);

-- --------------------------------------------------------

--
-- Structure de la table `inscription`
--

DROP TABLE IF EXISTS `inscription`;
CREATE TABLE IF NOT EXISTS `inscription` (
  `id_inscription` int(11) NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int(11) NOT NULL,
  `id_eleve` int(11) NOT NULL,
  `id_classe` int(11) NOT NULL,
  `id_annee` int(11) NOT NULL,
  `date_ajout` date NOT NULL,
  `date_sup` datetime DEFAULT NULL,
  `date_paiement` datetime DEFAULT NULL,
  `versement_effectuer` decimal(10,0) DEFAULT NULL,
  `versement_restant` decimal(10,0) DEFAULT NULL,
  `coges` int(11) DEFAULT NULL,
  `statut` int(11) NOT NULL,
  PRIMARY KEY (`id_inscription`),
  KEY `id_annee` (`id_annee`),
  KEY `id_classe` (`id_classe`),
  KEY `id_eleve` (`id_eleve`),
  KEY `id_utilisateur` (`id_utilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `jour`
--

DROP TABLE IF EXISTS `jour`;
CREATE TABLE IF NOT EXISTS `jour` (
  `id_jour` int(11) NOT NULL AUTO_INCREMENT,
  `libelle_jour` varchar(11) NOT NULL,
  `date_ajout` date NOT NULL,
  `date_sup` datetime DEFAULT NULL,
  `statut` int(11) NOT NULL,
  PRIMARY KEY (`id_jour`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `jour`
--

INSERT INTO `jour` (`id_jour`, `libelle_jour`, `date_ajout`, `date_sup`, `statut`) VALUES
(1, 'Lundi', '2022-02-21', NULL, 1),
(2, 'Mardi', '2022-02-21', NULL, 1),
(3, 'Mercredi', '2022-02-21', NULL, 1),
(4, 'Jeudi', '2022-02-21', NULL, 1),
(5, 'Vendredi', '2022-02-21', NULL, 1),
(6, 'Samedi', '2022-02-21', NULL, 1);

-- --------------------------------------------------------

--
-- Structure de la table `matiere`
--

DROP TABLE IF EXISTS `matiere`;
CREATE TABLE IF NOT EXISTS `matiere` (
  `id_matiere` int(11) NOT NULL AUTO_INCREMENT,
  `id_niveau` int(11) NOT NULL,
  `id_type_matiere` int(11) NOT NULL,
  `code_matiere` varchar(255) DEFAULT NULL,
  `libelle_matiere` varchar(255) NOT NULL,
  `coefficient_matiere` int(11) NOT NULL,
  `date_ajout` date NOT NULL,
  `date_sup` datetime DEFAULT NULL,
  `statut` int(11) NOT NULL,
  PRIMARY KEY (`id_matiere`),
  KEY `id_niveau` (`id_niveau`),
  KEY `id_type_matiere` (`id_type_matiere`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `matiere`
--

INSERT INTO `matiere` (`id_matiere`, `id_niveau`, `id_type_matiere`, `code_matiere`, `libelle_matiere`, `coefficient_matiere`, `date_ajout`, `date_sup`, `statut`) VALUES
(1, 1, 1, 'Maths', 'Mathématique', 2, '2022-03-08', NULL, 1),
(2, 1, 2, 'PC', 'Physique Chimie', 2, '2022-03-08', NULL, 1),
(3, 1, 3, 'SVT', 'Science de la vie', 2, '2022-03-08', NULL, 1),
(4, 1, 5, 'ANG', 'Anglais', 2, '2022-03-08', NULL, 1),
(5, 1, 4, 'HG', 'Histoire-géo', 2, '2022-03-08', NULL, 1),
(6, 1, 9, 'FR', 'Français', 4, '2022-03-08', NULL, 1),
(7, 1, 10, 'EF', 'Economie Familiale', 1, '2022-03-08', NULL, 1),
(8, 1, 8, 'Conduite', 'Conduite', 1, '2022-03-08', NULL, 1);

-- --------------------------------------------------------

--
-- Structure de la table `matiere_prof`
--

DROP TABLE IF EXISTS `matiere_prof`;
CREATE TABLE IF NOT EXISTS `matiere_prof` (
  `id_matiere_prof` int(11) NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int(11) NOT NULL,
  `id_annee` int(11) NOT NULL,
  `id_type_matiere` int(11) NOT NULL,
  `date_ajout` date NOT NULL,
  `date_sup` datetime DEFAULT NULL,
  `statut` int(11) NOT NULL,
  PRIMARY KEY (`id_matiere_prof`),
  KEY `id_utilisateur` (`id_utilisateur`),
  KEY `id_annee` (`id_annee`),
  KEY `matiere_prof_ibfk_3` (`id_type_matiere`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `niveau`
--

DROP TABLE IF EXISTS `niveau`;
CREATE TABLE IF NOT EXISTS `niveau` (
  `id_niveau` int(11) NOT NULL AUTO_INCREMENT,
  `id_section` int(11) NOT NULL,
  `code_niveau` varchar(255) DEFAULT NULL,
  `libelle_niveau` varchar(255) NOT NULL,
  `frais_scolarite` decimal(10,0) NOT NULL,
  `date_ajout` date NOT NULL,
  `date_sup` datetime DEFAULT NULL,
  `statut` int(11) NOT NULL,
  PRIMARY KEY (`id_niveau`),
  KEY `id_section` (`id_section`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `niveau`
--

INSERT INTO `niveau` (`id_niveau`, `id_section`, `code_niveau`, `libelle_niveau`, `frais_scolarite`, `date_ajout`, `date_sup`, `statut`) VALUES
(1, 1, '6e', 'Sixieme', '100000', '2022-01-30', NULL, 1),
(2, 1, '5e', 'Cinquième', '100000', '2022-01-30', NULL, 1),
(3, 1, '4e', 'Quatrième', '100000', '2022-01-30', NULL, 1),
(4, 1, '3e', 'Troisième', '100000', '2022-01-30', NULL, 1),
(5, 2, '2nd A', 'Seconde A', '200000', '2022-01-30', NULL, 1),
(6, 2, '2nd C', 'Seconde C', '200000', '2022-01-30', NULL, 1),
(7, 2, '1ere A', 'Première A', '200000', '2022-01-30', NULL, 1),
(8, 2, '1ere D', 'Première D', '200000', '2022-01-30', NULL, 1),
(9, 2, 'Tle A', 'Terminale A', '200000', '2022-01-30', NULL, 1),
(10, 2, 'Tle D', 'Terminale D', '200000', '2022-01-30', NULL, 1);

-- --------------------------------------------------------

--
-- Structure de la table `note`
--

DROP TABLE IF EXISTS `note`;
CREATE TABLE IF NOT EXISTS `note` (
  `id_note` int(11) NOT NULL AUTO_INCREMENT,
  `id_eleve` int(11) NOT NULL,
  `id_classe` int(11) NOT NULL,
  `id_annee` int(11) NOT NULL,
  `id_semestre` int(11) NOT NULL,
  `id_type_devoir` int(11) NOT NULL,
  `id_matiere` int(11) NOT NULL,
  `note` float NOT NULL,
  `date_ajout` date NOT NULL,
  `date_sup` datetime DEFAULT NULL,
  `statut` int(11) NOT NULL,
  PRIMARY KEY (`id_note`),
  KEY `id_annee` (`id_annee`),
  KEY `id_eleve` (`id_eleve`),
  KEY `id_matiere` (`id_matiere`),
  KEY `id_semestre` (`id_semestre`),
  KEY `id_type_devoir` (`id_type_devoir`),
  KEY `id_classe` (`id_classe`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `note_semestrielle`
--

DROP TABLE IF EXISTS `note_semestrielle`;
CREATE TABLE IF NOT EXISTS `note_semestrielle` (
  `id_note_semestrielle` int(11) NOT NULL AUTO_INCREMENT,
  `id_prof` int(11) NOT NULL,
  `id_eleve` int(11) NOT NULL,
  `id_annee` int(11) NOT NULL,
  `id_semestre` int(11) NOT NULL,
  `id_matiere` int(11) NOT NULL,
  `coefficient` int(11) NOT NULL,
  `note_classe` double NOT NULL,
  `note_compo` double NOT NULL,
  `date_ajout` date NOT NULL,
  `date_sup` datetime DEFAULT NULL,
  `statut` int(11) NOT NULL,
  PRIMARY KEY (`id_note_semestrielle`),
  KEY `id_annee` (`id_annee`),
  KEY `id_eleve` (`id_eleve`),
  KEY `id_matiere` (`id_matiere`),
  KEY `id_semestre` (`id_semestre`),
  KEY `note_semestrielle_ibfk_4` (`id_prof`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `oriente`
--

DROP TABLE IF EXISTS `oriente`;
CREATE TABLE IF NOT EXISTS `oriente` (
  `id_oriente` int(11) NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int(11) DEFAULT NULL,
  `id_eleve` int(11) NOT NULL,
  `id_annee` int(11) NOT NULL,
  `id_niveau` int(11) NOT NULL,
  `inscrit` int(11) NOT NULL,
  `date_oriente` date DEFAULT NULL,
  `date_ajout` date NOT NULL,
  `date_sup` datetime DEFAULT NULL,
  `statut` int(11) NOT NULL,
  PRIMARY KEY (`id_oriente`),
  KEY `id_annee` (`id_annee`),
  KEY `id_eleve` (`id_eleve`),
  KEY `id_niveau` (`id_niveau`),
  KEY `id_utilisateur` (`id_utilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `prof`
--

DROP TABLE IF EXISTS `prof`;
CREATE TABLE IF NOT EXISTS `prof` (
  `id_prof` int(11) NOT NULL AUTO_INCREMENT,
  `nom_prof` varchar(255) NOT NULL,
  `prenom_prof` varchar(255) NOT NULL,
  `tel_prof` varchar(255) NOT NULL,
  `date_ajout` date NOT NULL,
  `date_sup` datetime DEFAULT NULL,
  `statut` int(11) NOT NULL,
  PRIMARY KEY (`id_prof`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `profil`
--

DROP TABLE IF EXISTS `profil`;
CREATE TABLE IF NOT EXISTS `profil` (
  `id_profil` int(11) NOT NULL AUTO_INCREMENT,
  `libelle_profil` varchar(255) NOT NULL,
  `date_ajout` date NOT NULL,
  `date_sup` datetime DEFAULT NULL,
  `statut` int(11) NOT NULL,
  PRIMARY KEY (`id_profil`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `profil`
--

INSERT INTO `profil` (`id_profil`, `libelle_profil`, `date_ajout`, `date_sup`, `statut`) VALUES
(1, 'Admin', '2022-01-30', NULL, 1),
(2, 'Surveillant', '2022-02-02', NULL, 1),
(3, 'Censeur', '2022-02-12', NULL, 1),
(4, 'Prof', '2022-02-12', NULL, 1),
(5, 'Comptable', '2022-04-09', NULL, 1);

-- --------------------------------------------------------

--
-- Structure de la table `redouble`
--

DROP TABLE IF EXISTS `redouble`;
CREATE TABLE IF NOT EXISTS `redouble` (
  `id_redouble` int(11) NOT NULL AUTO_INCREMENT,
  `id_eleve` int(11) NOT NULL,
  `id_annee` int(11) DEFAULT NULL,
  `id_niveau` int(11) NOT NULL,
  `date_ajout` date NOT NULL,
  `date_sup` datetime DEFAULT NULL,
  `statut` int(11) NOT NULL,
  PRIMARY KEY (`id_redouble`),
  KEY `id_annee` (`id_annee`),
  KEY `id_eleve` (`id_eleve`),
  KEY `id_niveau` (`id_niveau`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `section`
--

DROP TABLE IF EXISTS `section`;
CREATE TABLE IF NOT EXISTS `section` (
  `id_section` int(11) NOT NULL AUTO_INCREMENT,
  `libelle_section` varchar(255) NOT NULL,
  `date_ajout` date NOT NULL,
  `date_sup` datetime DEFAULT NULL,
  `statut` int(11) NOT NULL,
  PRIMARY KEY (`id_section`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `section`
--

INSERT INTO `section` (`id_section`, `libelle_section`, `date_ajout`, `date_sup`, `statut`) VALUES
(1, 'Collège', '2022-01-30', NULL, 1),
(2, 'Lycée', '2022-01-30', NULL, 1);

-- --------------------------------------------------------

--
-- Structure de la table `semestre`
--

DROP TABLE IF EXISTS `semestre`;
CREATE TABLE IF NOT EXISTS `semestre` (
  `id_semestre` int(11) NOT NULL AUTO_INCREMENT,
  `libelle_semestre` varchar(255) NOT NULL,
  `date_ajout` date NOT NULL,
  `date_sup` datetime DEFAULT NULL,
  `statut` int(11) NOT NULL,
  PRIMARY KEY (`id_semestre`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `semestre`
--

INSERT INTO `semestre` (`id_semestre`, `libelle_semestre`, `date_ajout`, `date_sup`, `statut`) VALUES
(1, '1er semestre', '2022-01-30', NULL, 1),
(2, '2e semestre', '2022-01-30', NULL, 1);

-- --------------------------------------------------------

--
-- Structure de la table `statut`
--

DROP TABLE IF EXISTS `statut`;
CREATE TABLE IF NOT EXISTS `statut` (
  `id_statut` int(11) NOT NULL AUTO_INCREMENT,
  `libelle_statut` varchar(255) NOT NULL,
  `date_ajout` date NOT NULL,
  `date_sup` datetime DEFAULT NULL,
  `statut` int(11) NOT NULL,
  PRIMARY KEY (`id_statut`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `statut`
--

INSERT INTO `statut` (`id_statut`, `libelle_statut`, `date_ajout`, `date_sup`, `statut`) VALUES
(1, 'Activé', '2022-01-30', NULL, 1),
(2, 'Désactivé', '2022-01-30', NULL, 1),
(3, 'Supprimé', '2022-01-30', NULL, 1);

-- --------------------------------------------------------

--
-- Structure de la table `transfert`
--

DROP TABLE IF EXISTS `transfert`;
CREATE TABLE IF NOT EXISTS `transfert` (
  `id_transfert` int(11) NOT NULL AUTO_INCREMENT,
  `reference_transfert` varchar(255) DEFAULT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `id_eleve` int(11) NOT NULL,
  `id_annee` int(11) NOT NULL,
  `id_niveau` int(11) NOT NULL,
  `source` varchar(255) DEFAULT NULL,
  `destination` varchar(255) DEFAULT NULL,
  `redouble` varchar(255) DEFAULT NULL,
  `inscrit` int(11) DEFAULT NULL,
  `type_transfert` int(11) NOT NULL,
  `date_transfert` date DEFAULT NULL,
  `date_ajout` date NOT NULL,
  `date_sup` datetime DEFAULT NULL,
  `statut` int(11) NOT NULL,
  PRIMARY KEY (`id_transfert`),
  KEY `id_annee` (`id_annee`),
  KEY `id_eleve` (`id_eleve`),
  KEY `id_utilisateur` (`id_utilisateur`),
  KEY `transfert_ibfk_2` (`id_niveau`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `type_calcul`
--

DROP TABLE IF EXISTS `type_calcul`;
CREATE TABLE IF NOT EXISTS `type_calcul` (
  `id_calcul` int(11) NOT NULL AUTO_INCREMENT,
  `libelle_calcul` varchar(255) NOT NULL,
  `date_ajout` date NOT NULL,
  `date_sup` datetime DEFAULT NULL,
  `statut` int(11) NOT NULL,
  PRIMARY KEY (`id_calcul`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `type_calcul`
--

INSERT INTO `type_calcul` (`id_calcul`, `libelle_calcul`, `date_ajout`, `date_sup`, `statut`) VALUES
(1, 'Max', '2022-01-30', NULL, 1),
(2, 'Moyenne', '2022-01-30', NULL, 1),
(3, 'Min', '2022-01-30', NULL, 1);

-- --------------------------------------------------------

--
-- Structure de la table `type_devoir`
--

DROP TABLE IF EXISTS `type_devoir`;
CREATE TABLE IF NOT EXISTS `type_devoir` (
  `id_type_devoir` int(11) NOT NULL AUTO_INCREMENT,
  `libelle_type_devoir` varchar(255) NOT NULL,
  `date_ajout` date NOT NULL,
  `date_sup` datetime DEFAULT NULL,
  `statut` int(11) NOT NULL,
  PRIMARY KEY (`id_type_devoir`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `type_devoir`
--

INSERT INTO `type_devoir` (`id_type_devoir`, `libelle_type_devoir`, `date_ajout`, `date_sup`, `statut`) VALUES
(1, 'Interrogation', '2022-02-19', NULL, 1),
(2, 'Devoir', '2022-02-19', NULL, 1),
(3, 'Devoir (surveillé/commun)', '2022-02-19', NULL, 1),
(4, 'Composition', '2022-02-19', NULL, 1);

-- --------------------------------------------------------

--
-- Structure de la table `type_matiere`
--

DROP TABLE IF EXISTS `type_matiere`;
CREATE TABLE IF NOT EXISTS `type_matiere` (
  `id_type_matiere` int(11) NOT NULL AUTO_INCREMENT,
  `code_type_matiere` varchar(255) NOT NULL,
  `libelle_type_matiere` varchar(255) NOT NULL,
  `date_ajout` date NOT NULL,
  `date_sup` datetime DEFAULT NULL,
  `statut` int(11) NOT NULL,
  PRIMARY KEY (`id_type_matiere`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `type_matiere`
--

INSERT INTO `type_matiere` (`id_type_matiere`, `code_type_matiere`, `libelle_type_matiere`, `date_ajout`, `date_sup`, `statut`) VALUES
(1, 'Maths', 'Mathématique', '2022-02-12', NULL, 1),
(2, 'PC', 'Physique Chimie', '2022-02-12', NULL, 1),
(3, 'SVT', 'Science de la vie', '2022-03-08', NULL, 1),
(4, 'HG', 'Histoire-géo', '2022-03-08', NULL, 1),
(5, 'ANG', 'Anglais', '2022-03-08', NULL, 1),
(6, 'Philo', 'Philosophie', '2022-03-08', NULL, 1),
(7, 'ARB', 'Arabe', '2022-03-08', NULL, 1),
(8, 'Conduite', 'Conduite', '2022-03-08', NULL, 1),
(9, 'FR', 'Français', '2022-03-08', NULL, 1),
(10, 'EF', 'Economie Familiale', '2022-03-08', NULL, 1);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `id_utilisateur` int(11) NOT NULL AUTO_INCREMENT,
  `id_profil` int(11) DEFAULT NULL,
  `nom_utilisateur` varchar(255) DEFAULT NULL,
  `prenom_utilisateur` varchar(255) DEFAULT NULL,
  `tel_utilisateur` varchar(255) DEFAULT NULL,
  `login` varchar(255) NOT NULL,
  `mot_passe` varchar(255) NOT NULL,
  `pre_connexion` int(11) NOT NULL,
  `date_ajout` date NOT NULL,
  `date_sup` datetime DEFAULT NULL,
  `id_statut` int(11) NOT NULL,
  `statut` int(11) NOT NULL,
  PRIMARY KEY (`id_utilisateur`),
  KEY `id_profil` (`id_profil`),
  KEY `id_statut` (`id_statut`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id_utilisateur`, `id_profil`, `nom_utilisateur`, `prenom_utilisateur`, `tel_utilisateur`, `login`, `mot_passe`, `pre_connexion`, `date_ajout`, `date_sup`, `id_statut`, `statut`) VALUES
(1, 1, 'ismo', 'one', '98241842', 'admin', '$2y$13$1CYTDKOBAs2AGS7RxlVpiOYw6f6zfakNTX2YV2HKcN.l1Xguvm9Yi', 0, '2022-06-01', NULL, 1, 1),
(2, 2, 'nom1', 'prenom1', '96000000', 'surveillant1', '$2y$13$fcOH7HVU6WUkGzmY5vJzT.4BRKmd9dm/3/L8z1X2TMlJD.Y.NdB0u', 0, '2022-06-01', NULL, 1, 1),
(3, 3, 'nom2', 'prenom2', '96000001', 'censeur1', '$2y$13$axxrYOxksu3oL0ntgjU/B../VyBfbQLN9tYMR3lhUMHmKl3B.RWm2', 0, '2022-06-01', NULL, 1, 1),
(4, 4, 'nom3', 'prenom3', '96000002', 'prof1', '$2y$13$n/YruTyg3HKAyfF6HnEWUeeSAvyYhwSKffap7himm055mboN05yqq', 0, '2022-06-01', NULL, 1, 1),
(5, 5, 'nom4', 'prenom4', '96000003', 'comptable1', '$2y$13$7SACwaYbiVYuWZK6l.ykUu10hy9IIYgHvgvlHKVGtA3oWvf2NyBou', 0, '2022-06-01', NULL, 1, 1),
(6, 4, 'nom5', 'prenom5', '96000004', 'prof2', '$2y$13$c5.N5ucbJIli2r.zScJokehgU6flNsqcv.QMmYlnmm.gLfxDiOEIq', 0, '2022-06-01', NULL, 1, 1),
(7, 4, 'nom6', 'prenom6', '96000005', 'prof3', '$2y$13$ttdhOiUssM1TYrloReSbfewrp/OdtXJjJDA6oilO1Gkojp1NStk7u', 0, '2022-06-01', NULL, 1, 1),
(8, 4, 'nom7', 'prenom7', '96000006', 'prof4', '$2y$13$.4z1cL./XvQCHX2SJUnfoOBNufd3OjjjQM6FIGYHczsiY1rWYbf/G', 0, '2022-06-01', NULL, 1, 1),
(9, 4, 'nom8', 'prenom8', '96000008', 'prof5', '$2y$13$Mu0g1.9yr3fa7lDV7V9iWuExn4OaZbjynaF7aQFbZPvulBLeXpGSW', 0, '2022-06-01', NULL, 1, 1),
(10, 4, 'nom9', 'prenom9', '96000007', 'prof6', '$2y$13$0ekG9A6XX9/lDS4PJuCNLuS9uNNdXkqKqk8qO.pFuSH5E3qh/0Feu', 0, '2022-06-01', NULL, 1, 1),
(11, 4, 'nom10', 'prenom10', '96000009', 'prof7', '$2y$13$VmovOZ2cCfKZKLO/oX7DK.nKju7d995GyMsdwNIAC5YgP9uq5ksNO', 0, '2022-06-01', NULL, 1, 1),
(12, 4, 'nom11', 'prenom11', '96000009', 'prof8', '$2y$13$Oc17btpom1VL.R1.8e1T5.ZdhuYD.q2fUfM1KHYwnNjL161PxLmc2', 0, '2022-06-01', NULL, 1, 1),
(13, 4, 'nom12', 'prenom12', '96000010', 'prof9', '$2y$13$LZb8sO4J9CPzOD5mVPFjDOTHF3wfv.NdQYmTWZ1Rvh.tuqQRzWBsi', 0, '2022-06-01', NULL, 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `versement`
--

DROP TABLE IF EXISTS `versement`;
CREATE TABLE IF NOT EXISTS `versement` (
  `id_versement` int(11) NOT NULL AUTO_INCREMENT,
  `id_inscription` int(11) NOT NULL,
  `montant` decimal(10,0) NOT NULL,
  `date_ajout` date NOT NULL,
  `date_sup` datetime DEFAULT NULL,
  `statut` int(11) NOT NULL,
  PRIMARY KEY (`id_versement`),
  KEY `id_inscription` (`id_inscription`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `versement_coges`
--

DROP TABLE IF EXISTS `versement_coges`;
CREATE TABLE IF NOT EXISTS `versement_coges` (
  `id_versement_coges` int(11) NOT NULL AUTO_INCREMENT,
  `id_inscription` int(11) NOT NULL,
  `montant` decimal(10,0) NOT NULL,
  `date_ajout` date NOT NULL,
  `date_sup` datetime DEFAULT NULL,
  `statut` int(11) NOT NULL,
  PRIMARY KEY (`id_versement_coges`),
  KEY `id_inscription` (`id_inscription`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `absence`
--
ALTER TABLE `absence`
  ADD CONSTRAINT `absence_ibfk_1` FOREIGN KEY (`id_annee`) REFERENCES `annee` (`id_annee`),
  ADD CONSTRAINT `absence_ibfk_2` FOREIGN KEY (`id_eleve`) REFERENCES `eleve` (`id_eleve`),
  ADD CONSTRAINT `absence_ibfk_3` FOREIGN KEY (`id_semestre`) REFERENCES `semestre` (`id_semestre`),
  ADD CONSTRAINT `absence_ibfk_4` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`),
  ADD CONSTRAINT `absence_ibfk_5` FOREIGN KEY (`id_heure`) REFERENCES `heure` (`id_heure`);

--
-- Contraintes pour la table `allocation`
--
ALTER TABLE `allocation`
  ADD CONSTRAINT `allocation_ibfk_1` FOREIGN KEY (`id_annee`) REFERENCES `annee` (`id_annee`),
  ADD CONSTRAINT `allocation_ibfk_2` FOREIGN KEY (`id_eleve`) REFERENCES `eleve` (`id_eleve`),
  ADD CONSTRAINT `allocation_ibfk_3` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`);

--
-- Contraintes pour la table `annee_encours`
--
ALTER TABLE `annee_encours`
  ADD CONSTRAINT `annee_encours_ibfk_1` FOREIGN KEY (`id_annee`) REFERENCES `annee` (`id_annee`),
  ADD CONSTRAINT `annee_encours_ibfk_2` FOREIGN KEY (`id_semestre`) REFERENCES `semestre` (`id_semestre`);

--
-- Contraintes pour la table `bulletin`
--
ALTER TABLE `bulletin`
  ADD CONSTRAINT `bulletin_ibfk_1` FOREIGN KEY (`id_annee`) REFERENCES `annee` (`id_annee`),
  ADD CONSTRAINT `bulletin_ibfk_2` FOREIGN KEY (`id_eleve`) REFERENCES `eleve` (`id_eleve`),
  ADD CONSTRAINT `bulletin_ibfk_3` FOREIGN KEY (`id_semestre`) REFERENCES `semestre` (`id_semestre`),
  ADD CONSTRAINT `bulletin_ibfk_4` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`);

--
-- Contraintes pour la table `cahier_texte`
--
ALTER TABLE `cahier_texte`
  ADD CONSTRAINT `cahier_texte_ibfk_1` FOREIGN KEY (`id_annee`) REFERENCES `annee` (`id_annee`),
  ADD CONSTRAINT `cahier_texte_ibfk_2` FOREIGN KEY (`id_heure`) REFERENCES `heure` (`id_heure`),
  ADD CONSTRAINT `cahier_texte_ibfk_3` FOREIGN KEY (`id_semestre`) REFERENCES `semestre` (`id_semestre`),
  ADD CONSTRAINT `cahier_texte_ibfk_4` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`),
  ADD CONSTRAINT `cahier_texte_ibfk_5` FOREIGN KEY (`id_jour`) REFERENCES `jour` (`id_jour`);

--
-- Contraintes pour la table `classe`
--
ALTER TABLE `classe`
  ADD CONSTRAINT `classe_ibfk_1` FOREIGN KEY (`id_niveau`) REFERENCES `niveau` (`id_niveau`);

--
-- Contraintes pour la table `classe_prof`
--
ALTER TABLE `classe_prof`
  ADD CONSTRAINT `classe_prof_ibfk_1` FOREIGN KEY (`id_classe`) REFERENCES `classe` (`id_classe`),
  ADD CONSTRAINT `classe_prof_ibfk_2` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`),
  ADD CONSTRAINT `classe_prof_ibfk_3` FOREIGN KEY (`id_annee`) REFERENCES `annee` (`id_annee`);

--
-- Contraintes pour la table `classe_surveillant`
--
ALTER TABLE `classe_surveillant`
  ADD CONSTRAINT `classe_surveillant_ibfk_1` FOREIGN KEY (`id_classe`) REFERENCES `classe` (`id_classe`),
  ADD CONSTRAINT `classe_surveillant_ibfk_2` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`),
  ADD CONSTRAINT `classe_surveillant_ibfk_3` FOREIGN KEY (`id_annee`) REFERENCES `annee` (`id_annee`),
  ADD CONSTRAINT `classe_surveillant_ibfk_4` FOREIGN KEY (`id_user`) REFERENCES `utilisateur` (`id_utilisateur`);

--
-- Contraintes pour la table `exclure`
--
ALTER TABLE `exclure`
  ADD CONSTRAINT `exclure_ibfk_1` FOREIGN KEY (`id_eleve`) REFERENCES `eleve` (`id_eleve`),
  ADD CONSTRAINT `exclure_ibfk_2` FOREIGN KEY (`id_niveau`) REFERENCES `niveau` (`id_niveau`),
  ADD CONSTRAINT `exclure_ibfk_3` FOREIGN KEY (`id_annee`) REFERENCES `annee` (`id_annee`);

--
-- Contraintes pour la table `existant`
--
ALTER TABLE `existant`
  ADD CONSTRAINT `existant_ibfk_1` FOREIGN KEY (`id_eleve`) REFERENCES `eleve` (`id_eleve`),
  ADD CONSTRAINT `existant_ibfk_2` FOREIGN KEY (`id_niveau`) REFERENCES `niveau` (`id_niveau`),
  ADD CONSTRAINT `existant_ibfk_3` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`),
  ADD CONSTRAINT `existant_ibfk_4` FOREIGN KEY (`id_annee`) REFERENCES `annee` (`id_annee`);

--
-- Contraintes pour la table `generation`
--
ALTER TABLE `generation`
  ADD CONSTRAINT `generation_ibfk_1` FOREIGN KEY (`id_annee`) REFERENCES `annee` (`id_annee`),
  ADD CONSTRAINT `generation_ibfk_2` FOREIGN KEY (`id_classe`) REFERENCES `classe` (`id_classe`),
  ADD CONSTRAINT `generation_ibfk_3` FOREIGN KEY (`id_semestre`) REFERENCES `semestre` (`id_semestre`);

--
-- Contraintes pour la table `inscription`
--
ALTER TABLE `inscription`
  ADD CONSTRAINT `inscription_ibfk_1` FOREIGN KEY (`id_annee`) REFERENCES `annee` (`id_annee`),
  ADD CONSTRAINT `inscription_ibfk_2` FOREIGN KEY (`id_classe`) REFERENCES `classe` (`id_classe`),
  ADD CONSTRAINT `inscription_ibfk_3` FOREIGN KEY (`id_eleve`) REFERENCES `eleve` (`id_eleve`),
  ADD CONSTRAINT `inscription_ibfk_4` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`);

--
-- Contraintes pour la table `matiere`
--
ALTER TABLE `matiere`
  ADD CONSTRAINT `matiere_ibfk_1` FOREIGN KEY (`id_niveau`) REFERENCES `niveau` (`id_niveau`),
  ADD CONSTRAINT `matiere_ibfk_2` FOREIGN KEY (`id_type_matiere`) REFERENCES `type_matiere` (`id_type_matiere`);

--
-- Contraintes pour la table `matiere_prof`
--
ALTER TABLE `matiere_prof`
  ADD CONSTRAINT `matiere_prof_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`),
  ADD CONSTRAINT `matiere_prof_ibfk_2` FOREIGN KEY (`id_annee`) REFERENCES `annee` (`id_annee`),
  ADD CONSTRAINT `matiere_prof_ibfk_3` FOREIGN KEY (`id_type_matiere`) REFERENCES `type_matiere` (`id_type_matiere`);

--
-- Contraintes pour la table `niveau`
--
ALTER TABLE `niveau`
  ADD CONSTRAINT `niveau_ibfk_1` FOREIGN KEY (`id_section`) REFERENCES `section` (`id_section`);

--
-- Contraintes pour la table `note`
--
ALTER TABLE `note`
  ADD CONSTRAINT `note_ibfk_1` FOREIGN KEY (`id_annee`) REFERENCES `annee` (`id_annee`),
  ADD CONSTRAINT `note_ibfk_2` FOREIGN KEY (`id_eleve`) REFERENCES `eleve` (`id_eleve`),
  ADD CONSTRAINT `note_ibfk_3` FOREIGN KEY (`id_matiere`) REFERENCES `matiere` (`id_matiere`),
  ADD CONSTRAINT `note_ibfk_4` FOREIGN KEY (`id_semestre`) REFERENCES `semestre` (`id_semestre`),
  ADD CONSTRAINT `note_ibfk_5` FOREIGN KEY (`id_type_devoir`) REFERENCES `type_devoir` (`id_type_devoir`),
  ADD CONSTRAINT `note_ibfk_6` FOREIGN KEY (`id_classe`) REFERENCES `classe` (`id_classe`);

--
-- Contraintes pour la table `note_semestrielle`
--
ALTER TABLE `note_semestrielle`
  ADD CONSTRAINT `note_semestrielle_ibfk_1` FOREIGN KEY (`id_annee`) REFERENCES `annee` (`id_annee`),
  ADD CONSTRAINT `note_semestrielle_ibfk_2` FOREIGN KEY (`id_eleve`) REFERENCES `eleve` (`id_eleve`),
  ADD CONSTRAINT `note_semestrielle_ibfk_3` FOREIGN KEY (`id_matiere`) REFERENCES `matiere` (`id_matiere`),
  ADD CONSTRAINT `note_semestrielle_ibfk_4` FOREIGN KEY (`id_prof`) REFERENCES `utilisateur` (`id_utilisateur`),
  ADD CONSTRAINT `note_semestrielle_ibfk_5` FOREIGN KEY (`id_semestre`) REFERENCES `semestre` (`id_semestre`);

--
-- Contraintes pour la table `oriente`
--
ALTER TABLE `oriente`
  ADD CONSTRAINT `oriente_ibfk_1` FOREIGN KEY (`id_annee`) REFERENCES `annee` (`id_annee`),
  ADD CONSTRAINT `oriente_ibfk_2` FOREIGN KEY (`id_eleve`) REFERENCES `eleve` (`id_eleve`),
  ADD CONSTRAINT `oriente_ibfk_3` FOREIGN KEY (`id_niveau`) REFERENCES `niveau` (`id_niveau`),
  ADD CONSTRAINT `oriente_ibfk_4` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`);

--
-- Contraintes pour la table `redouble`
--
ALTER TABLE `redouble`
  ADD CONSTRAINT `redouble_ibfk_1` FOREIGN KEY (`id_annee`) REFERENCES `annee` (`id_annee`),
  ADD CONSTRAINT `redouble_ibfk_2` FOREIGN KEY (`id_eleve`) REFERENCES `eleve` (`id_eleve`),
  ADD CONSTRAINT `redouble_ibfk_3` FOREIGN KEY (`id_niveau`) REFERENCES `niveau` (`id_niveau`);

--
-- Contraintes pour la table `transfert`
--
ALTER TABLE `transfert`
  ADD CONSTRAINT `transfert_ibfk_1` FOREIGN KEY (`id_annee`) REFERENCES `annee` (`id_annee`),
  ADD CONSTRAINT `transfert_ibfk_2` FOREIGN KEY (`id_niveau`) REFERENCES `niveau` (`id_niveau`),
  ADD CONSTRAINT `transfert_ibfk_3` FOREIGN KEY (`id_eleve`) REFERENCES `eleve` (`id_eleve`),
  ADD CONSTRAINT `transfert_ibfk_4` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`);

--
-- Contraintes pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD CONSTRAINT `utilisateur_ibfk_2` FOREIGN KEY (`id_profil`) REFERENCES `profil` (`id_profil`),
  ADD CONSTRAINT `utilisateur_ibfk_3` FOREIGN KEY (`id_statut`) REFERENCES `statut` (`id_statut`);

--
-- Contraintes pour la table `versement`
--
ALTER TABLE `versement`
  ADD CONSTRAINT `versement_ibfk_1` FOREIGN KEY (`id_inscription`) REFERENCES `inscription` (`id_inscription`);

--
-- Contraintes pour la table `versement_coges`
--
ALTER TABLE `versement_coges`
  ADD CONSTRAINT `versement_coges_ibfk_1` FOREIGN KEY (`id_inscription`) REFERENCES `inscription` (`id_inscription`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
