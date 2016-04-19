-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Mar 12 Avril 2016 à 18:42
-- Version du serveur: 5.5.47-0ubuntu0.14.04.1
-- Version de PHP: 5.6.20-1+deb.sury.org~trusty+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `symfony`
--

-- --------------------------------------------------------

--
-- Structure de la table `agenda`
--

CREATE TABLE IF NOT EXISTS `agenda` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `meeting_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_2CEDC87767433D9C` (`meeting_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=35 ;


-- --------------------------------------------------------

--
-- Structure de la table `item_agenda`
--

CREATE TABLE IF NOT EXISTS `item_agenda` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `agenda_id` int(11) NOT NULL,
  `proposer_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_AA14BDB8EA67784A` (`agenda_id`),
  KEY `IDX_AA14BDB8B13FA634` (`proposer_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=146 ;


-- --------------------------------------------------------

--
-- Structure de la table `item_minute`
--

CREATE TABLE IF NOT EXISTS `item_minute` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `meeting_id` int(11) DEFAULT NULL,
  `item_agenda_id` int(11) DEFAULT NULL,
  `comment` longtext COLLATE utf8_unicode_ci,
  `postponed` tinyint(1) NOT NULL,
  `minutes` time DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_E83BDE7AE6BD6EBA` (`item_agenda_id`),
  KEY `IDX_E83BDE7A67433D9C` (`meeting_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=32 ;

-- --------------------------------------------------------

--
-- Structure de la table `meeting`
--

CREATE TABLE IF NOT EXISTS `meeting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `room` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `chairman_id` int(11) DEFAULT NULL,
  `duration` time NOT NULL,
  `secretary_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_F515E139166D1F9C` (`project_id`),
  KEY `IDX_F515E139CD0B344F` (`chairman_id`),
  KEY `IDX_F515E139A2A63DB2` (`secretary_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=84 ;

-- --------------------------------------------------------

--
-- Structure de la table `meeting_minute`
--

CREATE TABLE IF NOT EXISTS `meeting_minute` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `meeting_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6EAA9AF767433D9C` (`meeting_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=13 ;


-- --------------------------------------------------------

--
-- Structure de la table `minute_action`
--

CREATE TABLE IF NOT EXISTS `minute_action` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) DEFAULT NULL,
  `implementer_id` int(11) DEFAULT NULL,
  `state` smallint(6) NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci NOT NULL,
  `deadline` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_F745D1B0126F525E` (`item_id`),
  KEY `IDX_F745D1B0845CC5FA` (`implementer_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=11 ;


-- --------------------------------------------------------

--
-- Structure de la table `minute_comment`
--

CREATE TABLE IF NOT EXISTS `minute_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `meeting_minute_id` int(11) DEFAULT NULL,
  `commenter_id` int(11) DEFAULT NULL,
  `comment` longtext COLLATE utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_41A49AD56B67886D` (`meeting_minute_id`),
  KEY `IDX_41A49AD5B4D5A9E2` (`commenter_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=11 ;



-- --------------------------------------------------------

--
-- Structure de la table `project`
--

CREATE TABLE IF NOT EXISTS `project` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `leader_id` int(11) DEFAULT NULL,
  `secretary_id` int(11) DEFAULT NULL,
  `project_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `locked` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_2FB3D0EE73154ED4` (`leader_id`),
  KEY `IDX_2FB3D0EEA2A63DB2` (`secretary_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=73 ;


-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `is_admin` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649F85E0677` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=19 ;

--
-- Contenu de la table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `is_admin`) VALUES
(2, 'admin', '$2y$13$ztXYzWcXubsIZuNW9CX4xevqrcJk9ldIErgaEFT3OxQOiaADi2Wc2', 1);

-- --------------------------------------------------------

--
-- Structure de la table `users_projects`
--

CREATE TABLE IF NOT EXISTS `users_projects` (
  `user_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  PRIMARY KEY (`project_id`,`user_id`),
  KEY `IDX_27D2987EA76ED395` (`user_id`),
  KEY `IDX_27D2987E166D1F9C` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- --------------------------------------------------------

--
-- Structure de la table `user_answer`
--

CREATE TABLE IF NOT EXISTS `user_answer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `meeting` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `answer` smallint(6) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_BF8F5118A76ED395` (`user_id`),
  KEY `IDX_BF8F5118F515E139` (`meeting`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=282 ;
-- --------------------------------------------------------

--
-- Structure de la table `user_presence`
--

CREATE TABLE IF NOT EXISTS `user_presence` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `meeting_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `state` smallint(6) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_89FA23A567433D9C` (`meeting_id`),
  KEY `IDX_89FA23A5A76ED395` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=95 ;


-- --------------------------------------------------------

--
-- Structure de la table `user_request`
--

CREATE TABLE IF NOT EXISTS `user_request` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `agenda_id` int(11) DEFAULT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `state` smallint(6) NOT NULL,
  `date` datetime NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `content` longtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_639A9195EA67784A` (`agenda_id`),
  KEY `IDX_639A9195F624B39D` (`sender_id`),
  KEY `IDX_639A9195126F525E` (`item_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;


-- --------------------------------------------------------

--
-- Structure de la table `user_role`
--

CREATE TABLE IF NOT EXISTS `user_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `role_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_2DE8C6A3CB944F1A` (`student_id`),
  KEY `IDX_2DE8C6A3166D1F9C` (`project_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=59 ;


--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `agenda`
--
ALTER TABLE `agenda`
  ADD CONSTRAINT `FK_2CEDC87767433D9C` FOREIGN KEY (`meeting_id`) REFERENCES `meeting` (`id`);

--
-- Contraintes pour la table `item_agenda`
--
ALTER TABLE `item_agenda`
  ADD CONSTRAINT `FK_AA14BDB8B13FA634` FOREIGN KEY (`proposer_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_AA14BDB8EA67784A` FOREIGN KEY (`agenda_id`) REFERENCES `agenda` (`id`);

--
-- Contraintes pour la table `item_minute`
--
ALTER TABLE `item_minute`
  ADD CONSTRAINT `FK_E83BDE7A67433D9C` FOREIGN KEY (`meeting_id`) REFERENCES `meeting_minute` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_E83BDE7AE6BD6EBA` FOREIGN KEY (`item_agenda_id`) REFERENCES `item_agenda` (`id`);

--
-- Contraintes pour la table `meeting`
--
ALTER TABLE `meeting`
  ADD CONSTRAINT `FK_F515E139166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  ADD CONSTRAINT `FK_F515E139A2A63DB2` FOREIGN KEY (`secretary_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_F515E139CD0B344F` FOREIGN KEY (`chairman_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `meeting_minute`
--
ALTER TABLE `meeting_minute`
  ADD CONSTRAINT `FK_6EAA9AF767433D9C` FOREIGN KEY (`meeting_id`) REFERENCES `meeting` (`id`);

--
-- Contraintes pour la table `minute_action`
--
ALTER TABLE `minute_action`
  ADD CONSTRAINT `FK_F745D1B0126F525E` FOREIGN KEY (`item_id`) REFERENCES `item_minute` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_F745D1B0845CC5FA` FOREIGN KEY (`implementer_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `minute_comment`
--
ALTER TABLE `minute_comment`
  ADD CONSTRAINT `FK_41A49AD56B67886D` FOREIGN KEY (`meeting_minute_id`) REFERENCES `meeting_minute` (`id`),
  ADD CONSTRAINT `FK_41A49AD5B4D5A9E2` FOREIGN KEY (`commenter_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `project`
--
ALTER TABLE `project`
  ADD CONSTRAINT `FK_2FB3D0EE73154ED4` FOREIGN KEY (`leader_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_2FB3D0EEA2A63DB2` FOREIGN KEY (`secretary_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `users_projects`
--
ALTER TABLE `users_projects`
  ADD CONSTRAINT `FK_27D2987E166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_27D2987EA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `user_answer`
--
ALTER TABLE `user_answer`
  ADD CONSTRAINT `FK_BF8F5118A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_BF8F5118F515E139` FOREIGN KEY (`meeting`) REFERENCES `meeting` (`id`);

--
-- Contraintes pour la table `user_presence`
--
ALTER TABLE `user_presence`
  ADD CONSTRAINT `FK_89FA23A567433D9C` FOREIGN KEY (`meeting_id`) REFERENCES `meeting_minute` (`id`),
  ADD CONSTRAINT `FK_89FA23A5A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `user_request`
--
ALTER TABLE `user_request`
  ADD CONSTRAINT `FK_639A9195126F525E` FOREIGN KEY (`item_id`) REFERENCES `item_agenda` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_639A9195EA67784A` FOREIGN KEY (`agenda_id`) REFERENCES `agenda` (`id`),
  ADD CONSTRAINT `FK_639A9195F624B39D` FOREIGN KEY (`sender_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `user_role`
--
ALTER TABLE `user_role`
  ADD CONSTRAINT `FK_2DE8C6A3166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  ADD CONSTRAINT `FK_2DE8C6A3CB944F1A` FOREIGN KEY (`student_id`) REFERENCES `user` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
