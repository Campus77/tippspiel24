-- phpMyAdmin SQL Dump
-- version 4.0.5
-- http://www.phpmyadmin.net
--
-- Host: rdbms
-- Erstellungszeit: 12. Jun 2014 um 23:38
-- Server Version: 5.5.31-log
-- PHP-Version: 5.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

CREATE DATABASE IF NOT EXISTS `competition` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `competition`;
--
-- Datenbank: `DB1725126`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `competition_bets`
--

CREATE TABLE IF NOT EXISTS `competition_bets` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `USER_ID` int(11) NOT NULL,
  `RESULT_ID` int(11) NOT NULL COMMENT 'FK zum Spiel',
  `BET1` int(11) NOT NULL,
  `BET2` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `USER_ID` (`USER_ID`),
  KEY `RESULT_ID` (`RESULT_ID`),
  KEY `USER_ID_2` (`USER_ID`),
  KEY `USER_ID_3` (`USER_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `competition_bets`
--

--
-- Tabellenstruktur für Tabelle `competition_bonus`
--

CREATE TABLE IF NOT EXISTS `competition_bonus` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `QUESTION` text NOT NULL,
  `TYPE` enum('TEAM','TEAM2','TEAM4','TEAM8GROUP','INT') NOT NULL,
  `RESULT` text,
  `BET_LIMIT` datetime NOT NULL,
  `POINTS` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Daten für Tabelle `competition_bonus`
--

INSERT INTO `competition_bonus` (`ID`, `QUESTION`, `TYPE`, `RESULT`, `BET_LIMIT`, `POINTS`) VALUES
(1, 'Wer wird Weltmeister?', 'TEAM', NULL, '2016-06-10 21:00:00', '20'),
(2, 'Welche Teams kommen ins Finale?', 'TEAM2', NULL, '2016-06-10 21:00:00', '8'),
(3, 'Welche Teams kommen ins Halbfinale?', 'TEAM4', NULL, '2016-06-10 21:00:00', '5'),
(4, 'Wer wird Gruppensieger?', 'TEAM8GROUP', NULL, '2016-06-10 21:00:00', '5'),
(5, 'Aus welchem Land kommt der Torsch&uuml;tzenk&ouml;nig?', 'TEAM', NULL, '2016-06-10 21:00:00', '10'),
(6, 'Wie viele Tore werden insgesamt in der Vorrunde geschossen?', 'INT', NULL, '2016-06-10 21:00:00', '3-10');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `competition_bonus_bets`
--

CREATE TABLE IF NOT EXISTS `competition_bonus_bets` (
  `USER_ID` int(11) NOT NULL,
  `BONUS_ID` int(11) NOT NULL,
  `BONUS_BET` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `competition_bonus_bets`
--

--
-- Tabellenstruktur für Tabelle `competition_plan`
--

CREATE TABLE IF NOT EXISTS `competition_plan` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Anpfiff` datetime NOT NULL,
  `Ort` varchar(64) COLLATE latin1_german2_ci NOT NULL,
  `RESULT` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `RESULT` (`RESULT`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci COMMENT='Spielplan' AUTO_INCREMENT=49 ;

--
-- Daten für Tabelle `competition_plan`
--

INSERT INTO `competition_plan` (`ID`, `Anpfiff`, `Ort`, `RESULT`) VALUES
(1, '2016-06-10 21:00:00', 'Paris - Parc des Princes', 2),
(2, '2016-06-11 15:00:00', 'Lens', 3),
(3, '2016-06-11 18:00:00', 'Bordeaux', 4),
(4, '2016-06-11 21:00:00', 'Marseille', 5),
(5, '2016-06-12 15:00:00', 'Paris - Parc des Princes', 6),
(6, '2016-06-12 18:00:00', 'Nizza', 7),
(7, '2016-06-12 21:00:00', 'Lille', 8),
(8, '2016-06-13 15:00:00', 'Tolouse', 9),
(9, '2016-06-13 18:00:00', 'Paris - St. Denis', 10),
(10, '2016-06-13 21:00:00', 'Lyon', 11),
(11, '2016-06-14 18:00:00', 'Bordeaux', 12),
(12, '2016-06-14 21:00:00', 'St. Etienne', 13),
(13, '2016-06-15 15:00:00', 'Lille', 14),
(14, '2016-06-15 18:00:00', 'Paris - Parc des Princes', 15),
(15, '2016-06-15 21:00:00', 'Marseille', 16),
(16, '2016-06-17 21:00:00', 'Estádio Castelão', 17),
(17, '2016-06-19 00:00:00', 'Arena Amazônia', 18),
(18, '2016-06-18 21:00:00', 'Estádio do Maracanã', 19),
(19, '2016-06-18 21:00:00', 'Estádio do Maracanã', 20),
(20, '2016-06-18 18:00:00', 'Estádio Beira-Rio', 21),
(21, '2016-06-19 18:00:00', 'Estádio Nacional', 22),
(22, '2016-06-20 00:00:00', 'Estádio das Dunas', 23),
(23, '2016-06-19 21:00:00', 'Arena de São Paulo', 24),
(24, '2016-06-20 18:00:00', 'Arena Pernambuco', 25),
(25, '2016-06-20 21:00:00', 'Arena Fonte Nova', 26),
(26, '2016-06-21 00:00:00', 'Arena da Baixada', 27),
(27, '2016-06-21 18:00:00', 'Estádio Mineirão', 28),
(28, '2016-06-22 00:00:00', 'Arena Pantanal', 29),
(29, '2016-06-21 21:00:00', 'Estádio Castelão', 30),
(30, '2016-06-23 00:00:00', 'Arena Amazônia', 31),
(31, '2016-06-22 18:00:00', 'Estádio do Maracanã', 32),
(32, '2016-06-22 21:00:00', 'Estádio Beira-Rio', 33),
(33, '2016-06-23 22:00:00', 'Estádio Nacional', 34),
(34, '2016-06-23 22:00:00', 'Arena Pernambuco', 35),
(35, '2016-06-23 18:00:00', 'Arena da Baixada', 36),
(36, '2016-06-23 18:00:00', 'Arena de São Paulo', 37),
(37, '2016-06-24 22:00:00', 'Arena Pantanal', 38),
(38, '2016-06-24 22:00:00', 'Estádio Castelão', 39),
(39, '2016-06-24 18:00:00', 'Estádio das Dunas', 40),
(40, '2016-06-24 18:00:00', 'Estádio Mineirão', 41),
(41, '2016-06-25 22:00:00', 'Arena Amazônia', 42),
(42, '2016-06-25 22:00:00', 'Estádio do Maracanã', 43),
(43, '2016-06-25 18:00:00', 'Estádio Beira-Rio', 44),
(44, '2016-06-25 18:00:00', 'Arena Fonte Nova', 45),
(45, '2016-06-26 18:00:00', 'Arena Pernambuco', 46),
(46, '2016-06-26 18:00:00', 'Estádio Nacional', 47),
(47, '2016-06-26 22:00:00', 'Arena de São Paulo', 48),
(48, '2016-06-26 22:00:00', 'Arena da Baixada', 49);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `competition_results`
--

CREATE TABLE IF NOT EXISTS `competition_results` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `TEAM1` int(11) NOT NULL,
  `TEAM2` int(11) NOT NULL,
  `RESULT1` int(11) DEFAULT '0',
  `RESULT2` int(11) DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `TEAM` (`TEAM1`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=50 ;

--
-- Daten für Tabelle `competition_results`
--

INSERT INTO `competition_results` (`ID`, `TEAM1`, `TEAM2`, `RESULT1`, `RESULT2`) VALUES
(2, 15, 34, NULL, NULL),
(3, 35, 31, NULL, NULL),
(4, 36, 37, NULL, NULL),
(5, 14, 27, NULL, NULL),
(6, 42, 11, NULL, NULL),
(7, 39, 40, NULL, NULL),
(8, 16, 38, NULL, NULL),
(9, 14, 21, NULL, NULL),
(10, 31, 12, NULL, NULL),
(11, 15, 19, NULL, NULL),
(12, 4, 6, NULL, NULL),
(13, 20, 25, NULL, NULL),
(14, 16, 26, NULL, NULL),
(15, 17, 32, NULL, NULL),
(16, 5, 2, NULL, NULL),
(17, 27, 28, NULL, NULL),
(18, 1, 23, NULL, NULL),
(19, 7, 11, NULL, NULL),
(20, 30, 8, NULL, NULL),
(21, 3, 24, NULL, NULL),
(22, 9, 13, NULL, NULL),
(23, 22, 18, NULL, NULL),
(24, 33, 14, NULL, NULL),
(25, 21, 10, NULL, NULL),
(26, 31, 15, NULL, NULL),
(27, 19, 12, NULL, NULL),
(28, 4, 20, NULL, NULL),
(29, 25, 6, NULL, NULL),
(30, 16, 17, NULL, NULL),
(31, 32, 26, NULL, NULL),
(32, 5, 27, NULL, NULL),
(33, 28, 2, NULL, NULL),
(34, 7, 1, NULL, NULL),
(35, 11, 23, NULL, NULL),
(36, 3, 30, NULL, NULL),
(37, 24, 8, NULL, NULL),
(38, 22, 9, NULL, NULL),
(39, 18, 13, NULL, NULL),
(40, 21, 33, NULL, NULL),
(41, 10, 14, NULL, NULL),
(42, 19, 31, NULL, NULL),
(43, 12, 15, NULL, NULL),
(44, 25, 4, NULL, NULL),
(45, 6, 20, NULL, NULL),
(46, 32, 16, NULL, NULL),
(47, 26, 17, NULL, NULL),
(48, 28, 5, NULL, NULL),
(49, 2, 27, NULL, NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `competition_teams`
--

CREATE TABLE IF NOT EXISTS `competition_teams` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ShortName` varchar(3) NOT NULL,
  `FullName` varchar(65) NOT NULL,
  `Status` int(11) NOT NULL DEFAULT '0',
  `Flag` text CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL,
  `InGroup` char(1) NOT NULL,
  `RANKING` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  FULLTEXT KEY `Short` (`ShortName`,`FullName`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=34 ;

--
-- Daten für Tabelle `competition_teams`
--

INSERT INTO `competition_teams` (`ID`, `ShortName`, `FullName`, `Status`, `Flag`, `InGroup`, `RANKING`) VALUES
(1, 'BRA', 'Brasilien', 1, 'img/flags/Brazil.png', 'X', 0),
(2, 'ALG', 'Algerien', 1, 'img/flags/Algeria.png', 'X', 0),
(3, 'AUS', 'Australien', 1, 'img/flags/Australia.png', 'X', 0),
(4, 'ARG', 'Argentinien', 1, 'img/flags/Argentina.png', 'X', 0),
(5, 'BEL', 'Belgien', 1, 'img/flags/Belgium.png', 'E', 1),
(6, 'BIH', 'Bosnien und Herzegowina', 1, 'img/flags/BosniaHerzegovina.png', 'X', 0),
(7, 'CMR', 'Kamerun', 1, 'img/flags/Cameroon.png', 'X', 0),
(8, 'CHI', 'Chile', 1, 'img/flags/Chile.png', 'X', 0),
(9, 'COL', 'Kolumbien', 1, 'img/flags/Colombia.png', 'X', 0),
(10, 'CRC', 'Costa Rica', 1, 'img/flags/Costa_Rica.png', 'X', 0),
(11, 'CRO', 'Kroatien', 1, 'img/flags/Croatia.png', 'D', 4),
(12, 'ECU', 'Ecuador', 1, 'img/flags/Ecuador.png', 'X', 0),
(13, 'CIV', 'Elfenbeinküste', 1, 'img/flags/Elfenbeinkueste.png', 'X', 0),
(14, 'ENG', 'England', 1, 'img/flags/England.png', 'B', 1),
(15, 'FRA', 'Frankreich', 1, 'img/flags/France.png', 'A', 1),
(16, 'GER', 'Deutschland', 1, 'img/flags/Germany.png', 'C', 1),
(17, 'GHA', 'Ghana', 1, 'img/flags/Ghana.png', 'X', 0),
(18, 'GRE', 'Griechenland', 1, 'img/flags/Greece.png', 'X', 0),
(19, 'HON', 'Honduras', 1, 'img/flags/Honduras.png', 'X', 0),
(20, 'IRI', 'Iran', 1, 'img/flags/Iran.png', 'X', 0),
(21, 'ITA', 'Italien', 1, 'img/flags/Italy.png', 'E', 2),
(22, 'JPN', 'Japan', 1, 'img/flags/Japan.png', 'X', 0),
(23, 'MEX', 'Mexiko', 1, 'img/flags/Mexico.png', 'X', 0),
(24, 'NED', 'Niederlande', 1, 'img/flags/Netherlands.png', 'X', 0),
(25, 'NGR', 'Nigeria', 1, 'img/flags/Nigeria.png', 'X', 0),
(26, 'POR', 'Portugal', 1, 'img/flags/Portugal.png', 'F', 1),
(27, 'RUS', 'Russische Förderation', 1, 'img/flags/Russia.png', 'B', 2),
(28, 'KOR', 'Republik Korea', 1, 'img/flags/South_Korea.png', 'X', 0),
(30, 'ESP', 'Spanien', 1, 'img/flags/Spain.png', 'D', 1),
(31, 'SUI', 'Schweiz', 1, 'img/flags/Switzerland.png', 'A', 4),
(32, 'USA', 'Vereinigte Staaten von Amerika', 1, 'img/flags/United_States.png', 'X', 0),
(33, 'URU', 'Uruguay', 1, 'img/flags/Uruguay.png', 'X', 0),
(34, 'ROM', 'Romänien', 1, 'img/flags/Romania.png', 'A', 2),
(35, 'ALB', 'Albanien', 1, 'img/flags/Albania.png', 'A', 3),
(36, 'WAL', 'Wales', 1, 'img/flags/Wales.png', 'B', 3),
(37, 'SVK', 'Slovakai', 1, 'img/flags/Slovakia.png', 'B', 4),
(38, 'UKR', 'Ukraine', 1, 'img/flags/Ukraine.png', 'C', 2),
(39, 'POL', 'Polen', 1, 'img/flags/Poland.png', 'C', 3),
(40, 'NIR', 'Nordirland', 1, 'img/flags/Northern_Ireland.png', 'C', 4),
(41, 'CZE', 'Tschechien', 1, 'img/flags/Czech_Republic.png', 'D', 2),
(42, 'TUR', 'Türkei', 1, 'img/flags/Turkey.png', 'D', 3),
(43, 'IRL', 'Irland', 1, 'img/flags/Ireland.png', 'E', 3),
(44, 'SWE', 'Schweden', 1, 'img/flags/Sweden.png', 'E', 4),
(45, 'ISL', 'Island', 1, 'img/flags/Iceland.png', 'F', 2),
(46, 'AUT', 'Österreich', 1, 'img/flags/Austria.png', 'F', 3),
(47, 'HUN', 'Ungarn', 1, 'img/flags/Hungary.png', 'F', 4);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `competition_users`
--

CREATE TABLE IF NOT EXISTS `competition_users` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `EMail` text COLLATE latin1_german2_ci NOT NULL,
  `Password` varchar(65) COLLATE latin1_german2_ci NOT NULL,
  `Username` varchar(65) COLLATE latin1_german2_ci NOT NULL,
  PRIMARY KEY (`Password`,`Username`),
  UNIQUE KEY `ID` (`ID`),
  UNIQUE KEY `ID_2` (`ID`),
  KEY `Password` (`Password`),
  KEY `Username` (`Username`),
  FULLTEXT KEY `Password_2` (`Password`),
  FULLTEXT KEY `EMail` (`EMail`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci COMMENT='Benutzer' AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `competition_users`
--
INSERT INTO `competition_users` (`ID`, `EMail`, `Password`, `Username`) VALUES
(1, 'marcel@daneyko.org', MD5('12345'), 'Marshall');

--
-- Tabellenstruktur für Tabelle `competition_user_ranking`
--

CREATE TABLE IF NOT EXISTS `competition_user_ranking` (
  `USER_ID` int(11) NOT NULL,
  `SCORES` int(11) NOT NULL DEFAULT '0',
  `Tagessieger` int(11) NOT NULL,
  UNIQUE KEY `USER_ID` (`USER_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
