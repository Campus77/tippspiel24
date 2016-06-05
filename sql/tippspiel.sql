SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

CREATE TABLE IF NOT EXISTS `competition_locations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) COLLATE latin1_german2_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1;

--

INSERT INTO `competition_locations` (`id`, `name`) VALUES
(1, 'Paris - Parc des Princes'),
(2, 'Lens - Stade Bollaert-Delelis'),
(3, 'Bordeaux - Stade de Bordeaux'),
(4, 'Marseille - Stade Vélodrome'),
(5, 'Nizza - Stade de Nice'),
(6, 'Villeneuve-d’Ascq (Lille) - Stade Pierre-Mauroy'),
(7, 'Tolouse - Stadium de Toulouse'),
(8, 'Paris Saint-Denis - Stade de France'),
(9, 'Décines-Charpieu - Stade de Lyon'),
(10, 'Saint-Étienne - Stade Geoffroy-Guichard');


CREATE TABLE IF NOT EXISTS `competition_teams` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shortname` varchar(3) NOT NULL,
  `fullname` varchar(65) NOT NULL,
  `flag` varchar(2) NOT NULL,
  `ingroup` char(1) NULL,
  `ranking` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1 ;

INSERT INTO `competition_teams` (`id`, `shortname`, `fullname`, `flag`, `ingroup`, `ranking`) VALUES
(1, 'BRA', 'Brasilien', 'br' , NULL, 0),
(2, 'ALG', 'Algerien', 'dz', NULL, 0),
(3, 'AUS', 'Australien', 'au', NULL, 0),
(4, 'ARG', 'Argentinien', 'ar', NULL, 0),
(5, 'BEL', 'Belgien', 'be', 'E', 1),
(6, 'BIH', 'Bosnien und Herzegowina', 'ba', NULL, 0),
(7, 'CMR', 'Kamerun', 'cm', NULL, 0),
(8, 'CHI', 'Chile', 'cl', NULL, 0),
(9, 'COL', 'Kolumbien', 'co', NULL, 0),
(10, 'CRC', 'Costa Rica', 'cr', NULL, 0),
(11, 'CRO', 'Kroatien', 'hr', 'D', 4),
(12, 'ECU', 'Ecuador', 'ec', NULL, 0),
(13, 'CIV', 'Elfenbeinküste', 'ci', NULL, 0),
(14, 'ENG', 'England', 'en', 'B', 1),
(15, 'FRA', 'Frankreich', 'fr', 'A', 1),
(16, 'GER', 'Deutschland', 'de', 'C', 1),
(17, 'GHA', 'Ghana', 'gh', NULL, 0),
(18, 'GRE', 'Griechenland', 'gr', NULL, 0),
(19, 'HON', 'Honduras', 'hn', NULL, 0),
(20, 'IRI', 'Iran', 'ir', NULL, 0),
(21, 'ITA', 'Italien', 'it', 'E', 2),
(22, 'JPN', 'Japan', 'jp', NULL, 0),
(23, 'MEX', 'Mexiko', 'mx', NULL, 0),
(24, 'NED', 'Niederlande', 'nl', NULL, 0),
(25, 'NGR', 'Nigeria', 'ng', NULL, 0),
(26, 'POR', 'Portugal', 'pt', 'F', 1),
(27, 'RUS', 'Russland', 'ru', 'B', 2),
(28, 'KOR', 'Republik Korea', 'kr', NULL, 0),
(30, 'ESP', 'Spanien', 'es', 'D', 1),
(31, 'SUI', 'Schweiz', 'ch', 'A', 4),
(32, 'USA', 'USA', 'us', NULL, 0),
(33, 'URU', 'Uruguay', 'uy', NULL, 0),
(34, 'ROM', 'Rumänien', 'ro', 'A', 2),
(35, 'ALB', 'Albanien', 'al', 'A', 3),
(36, 'WAL', 'Wales', 'wa', 'B', 3),
(37, 'SVK', 'Slowakei', 'sk', 'B', 4),
(38, 'UKR', 'Ukraine', 'ua', 'C', 2),
(39, 'POL', 'Polen', 'pl', 'C', 3),
(40, 'NIR', 'Nordirland', 'nd', 'C', 4),
(41, 'CZE', 'Tschechien', 'cz', 'D', 2),
(42, 'TUR', 'Türkei', 'tr', 'D', 3),
(43, 'IRL', 'Irland', 'ie', 'E', 3),
(44, 'SWE', 'Schweden', 'se', 'E', 4),
(45, 'ISL', 'Island', 'is', 'F', 2),
(46, 'AUT', 'Österreich', 'at', 'F', 3),
(47, 'HUN', 'Ungarn', 'hu', 'F', 4);

CREATE TABLE IF NOT EXISTS `competition_matches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kickoff` datetime NOT NULL,
  `location_id` int(11) NOT NULL,
  `team1_id` int(11) NULL,
  `team2_id` int(11) NULL,
  `result1` int(11) DEFAULT NULL,
  `result2` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1;

ALTER TABLE `competition_matches` 
ADD CONSTRAINT `FK_competition_matches_1`
  FOREIGN KEY (`location_id`)
  REFERENCES `competition_locations` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE,
ADD CONSTRAINT `FK_competition_matches_2`
  FOREIGN KEY (`team1_id`)
  REFERENCES `competition_teams` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE,
ADD CONSTRAINT `FK_competition_matches_3`
  FOREIGN KEY (`team2_id`)
  REFERENCES `competition_teams` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

INSERT INTO `competition_matches` (`id`, `kickoff`, `location_id`, `team1_id`, `team2_id`, `result1`, `result2`) VALUES
(1, '2016-06-10 21:00:00', 8, 15, 34, NULL, NULL),
(2, '2016-06-11 15:00:00', 2, 35, 31, NULL, NULL),
(3, '2016-06-11 18:00:00', 3, 36, 37, NULL, NULL),
(4, '2016-06-11 21:00:00', 4, 14, 27, NULL, NULL),
(5, '2016-06-12 15:00:00', 1, 42, 11, NULL, NULL),
(6, '2016-06-12 18:00:00', 5, 39, 40, NULL, NULL),
(7, '2016-06-12 21:00:00', 6, 16, 38, NULL, NULL),
(8, '2016-06-13 15:00:00', 7, 30, 41, NULL, NULL),
(9, '2016-06-13 18:00:00', 8, 43, 44, NULL, NULL),
(10, '2016-06-13 21:00:00', 9, 5, 21, NULL, NULL),
(11, '2016-06-14 18:00:00', 3, 46, 47, NULL, NULL),
(12, '2016-06-14 21:00:00', 10, 26, 45, NULL, NULL),
(13, '2016-06-15 15:00:00', 6, 27, 37, NULL, NULL),
(14, '2016-06-15 18:00:00', 1, 34, 31, NULL, NULL),
(15, '2016-06-15 21:00:00', 4, 15, 35, NULL, NULL),
(16, '2016-06-16 15:00:00', 2, 14, 36, NULL, NULL),
(17, '2016-06-16 18:00:00', 9, 38, 40, NULL, NULL),
(18, '2016-06-16 21:00:00', 8, 16, 39, NULL, NULL),
(19, '2016-06-17 15:00:00', 7, 21, 44, NULL, NULL),
(20, '2016-06-17 18:00:00', 10, 41, 11, NULL, NULL),
(21, '2016-06-17 21:00:00', 5, 30, 42, NULL, NULL),
(22, '2016-06-18 15:00:00', 3, 5, 43, NULL, NULL),
(23, '2016-06-18 18:00:00', 4, 45, 47, NULL, NULL),
(24, '2016-06-18 21:00:00', 1, 26, 46, NULL, NULL),
(25, '2016-06-19 21:00:00', 6, 31, 15, NULL, NULL),
(26, '2016-06-19 21:00:00', 9, 34, 35, NULL, NULL),
(27, '2016-06-20 21:00:00', 10, 37, 14, NULL, NULL),
(28, '2016-06-20 21:00:00', 7, 27, 36, NULL, NULL),
(29, '2016-06-21 18:00:00', 4, 38, 39, NULL, NULL),
(30, '2016-06-21 18:00:00', 1, 40, 16, NULL, NULL),
(31, '2016-06-21 21:00:00', 3, 11, 30, NULL, NULL),
(32, '2016-06-21 21:00:00', 2, 41, 42, NULL, NULL),
(33, '2016-06-22 18:00:00', 9, 47, 26, NULL, NULL),
(34, '2016-06-22 18:00:00', 8, 45, 46, NULL, NULL),
(35, '2016-06-22 21:00:00', 6, 21, 43, NULL, NULL),
(36, '2016-06-22 21:00:00', 5, 44, 5, NULL, NULL),
(37, '2016-06-25 15:00:00', 10, NULL, NULL, NULL, NULL),
(38, '2016-06-25 18:00:00', 1, NULL, NULL, NULL, NULL),
(39, '2016-06-25 21:00:00', 2, NULL, NULL, NULL, NULL),
(40, '2016-06-26 15:00:00', 9, NULL, NULL, NULL, NULL),
(41, '2016-06-26 18:00:00', 6, NULL, NULL, NULL, NULL),
(42, '2016-06-26 21:00:00', 7, NULL, NULL, NULL, NULL),
(43, '2016-06-27 18:00:00', 8, NULL, NULL, NULL, NULL),
(44, '2016-06-27 21:00:00', 5, NULL, NULL, NULL, NULL),
(45, '2016-06-30 21:00:00', 4, NULL, NULL, NULL, NULL),
(46, '2016-07-01 21:00:00', 6, NULL, NULL, NULL, NULL),
(47, '2016-07-02 21:00:00', 3, NULL, NULL, NULL, NULL),
(48, '2016-07-03 21:00:00', 8, NULL, NULL, NULL, NULL),
(49, '2016-07-06 21:00:00', 9, NULL, NULL, NULL, NULL),
(50, '2016-07-07 21:00:00', 4, NULL, NULL, NULL, NULL),
(51, '2016-07-10 21:00:00', 8, NULL, NULL, NULL, NULL);





CREATE TABLE IF NOT EXISTS `competition_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` text COLLATE latin1_german2_ci NOT NULL,
  `password` varchar(65) NOT NULL,
  `username` varchar(65) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1;

INSERT INTO `competition_users` (`id`, `email`, `password`, `username`) VALUES
(1, '', MD5('12345'), 'testuser');

CREATE TABLE IF NOT EXISTS `competition_bets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `match_id` int(11) NOT NULL COMMENT 'FK zum Spiel',
  `bet1` int(11) NOT NULL,
  `bet2` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1;

ALTER TABLE `competition_bets` 
ADD CONSTRAINT `FK_competition_bets_1`
  FOREIGN KEY (`user_id`)
  REFERENCES `competition_users` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE,
ADD CONSTRAINT `FK_competition_bets_2`
  FOREIGN KEY (`match_id`)
  REFERENCES `competition_matches` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

CREATE TABLE IF NOT EXISTS `competition_bonus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` text NOT NULL,
  `type` enum('TEAM','TEAM2','TEAM4','TEAMGROUP','INT') NOT NULL,
  `result` text,
  `points` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1;

INSERT INTO `competition_bonus` (`id`, `question`, `type`, `result`, `points`) VALUES
(1, 'Wer wird Europameister?', 'TEAM', NULL, '20'),
(2, 'Welche Teams kommen ins Finale?', 'TEAM2', NULL, '8'),
(3, 'Welche Teams kommen ins Halbfinale?', 'TEAM4', NULL, '5'),
(4, 'Wer wird Gruppensieger?', 'TEAMGROUP', NULL, '5'),
(5, 'Aus welchem Land kommt der Torsch&uuml;tzenk&ouml;nig?', 'TEAM', NULL, '10'),
(6, 'Wie viele Tore werden insgesamt in der Vorrunde geschossen?', 'INT', NULL, '3-10');

CREATE TABLE IF NOT EXISTS `competition_bonus_bets` (
  `user_id` int(11) NOT NULL,
  `bonus_id` int(11) NOT NULL,
  `bonus_bet` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

ALTER TABLE `competition_bonus_bets` 
ADD CONSTRAINT `FK_competition_bonus_bets_1`
  FOREIGN KEY (`user_id`)
  REFERENCES `competition_users` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE,
ADD CONSTRAINT `FK_competition_bonus_bets_2`
  FOREIGN KEY (`bonus_id`)
  REFERENCES `competition_bonus` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
