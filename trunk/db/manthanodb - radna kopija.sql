-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 14, 2014 at 12:33 AM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `manthanodb`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `addActivity`(ID INT, NAZIV VARCHAR(50), OPIS MEDIUMTEXT, POCETAK DATE,  COVER VARCHAR(100), active int)
BEGIN
SET SQL_SAFE_UPDATES=0;
SELECT @myLeft := lft FROM Activity
WHERE idActivity = ID;

UPDATE Activity SET rgt = rgt + 2 WHERE rgt > @myLeft;
UPDATE Activity SET lft = lft + 2 WHERE lft > @myLeft;

call addActivityRaw(NAZIV, OPIS, POCETAK, COVER, active, @myLeft + 1, @myLeft + 2);
SET SQL_SAFE_UPDATES=1;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `addActivityRaw`(NAZIV VARCHAR(50), OPIS MEDIUMTEXT, POCETAK DATE,  COVER VARCHAR(100), ACTIVE INT, LFT INT, RGT INT )
BEGIN
	DECLARE id INT default -56;	
	INSERT INTO Entity(tipEntiteta) values('Activity');
	SET id = last_insert_id();
	IF ACTIVE IS NULL then
		SET ACTIVE = 0;
	ELSE
		SET ACTIVE = 1;
	END IF;
	INSERT INTO Activity(idActivity, Name, Description, BeginDate, coverPicture, lft, rgt, Active) values(id, naziv, opis, pocetak, cover, lft, rgt, Active);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `addEvent`(idKurs INT, Naziv VARCHAR(50), OPIS MEDIUMTEXT, Mesto VARCHAR(50), Datum date, Vreme TIME)
BEGIN
	DECLARE id INT default -56;
	INSERT INTO Entity(tipEntiteta) values('Event');
	SET id = last_insert_id();
	INSERT INTO Event(idEvent,Name, Description, Venue, Date, Time) values (id,Naziv, OPIS, Mesto, Datum, Vreme);
	INSERT INTO ActivityContains(idEvent, idActivity) values (id, idKurs);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `addMaterial`(user int, idPredavanja INT, Naziv VARCHAR(50), Link VARCHAR(100), Tip VARCHAR(50),Datum TIMESTAMP )
BEGIN
	DECLARE id INT default -56;
	INSERT INTO Entity(tipEntiteta) values('Material');
	SET id = last_insert_id();
	INSERT INTO Material(idMaterial, Name, URI, Type, Date, OwnerID) values (id, Naziv, Link, Tip, NOW(), user);
	INSERT INTO EventContains(idEvent, idMaterial) values (idPredavanja, id);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `addProposal`(Predlozio int, Naziv VARCHAR(50), Opis MEDIUMTEXT )
BEGIN
	DECLARE id INT default -56;	
	INSERT INTO Entity(tipEntiteta) values('Proposal');
	SET id = last_insert_id();
	INSERT INTO Proposal(idProposal, UserProposed, Name, Description) values (id, Predlozio, Naziv, Opis);
	INSERT INTO ProposalSupport(user_id, idProposal) values (Predlozio, id);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteActivity`(ID INT)
BEGIN
	DECLARE lf INT DEFAULT 0;
	DECLARE rg INT default 0;
	DECLARE place INT default 0;
	DECLARE done INT DEFAULT FALSE;
	DECLARE cur CURSOR FOR SELECT idActivity FROM Activity Where (lft between lf and rg) or (lft = lf);
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
	SET lf = (select lft from Activity where idActivity = ID);
	SET rg = (select rgt from Activity where idActivity = ID);
	
	OPEN cur;
	
	petlja: LOOP
		FETCH cur INTO place;
		IF done then
			LEAVE petlja;
		END IF;
		DELETE FROM ActivityContains WHERE idActivity = place;
		DELETE FROM Activity WHERE idActivity = place;
		
	END LOOP petlja;
	CLOSE cur;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteMaterial`(id int)
BEGIN
	DELETE FROM EventContains where idMaterial = id;
	DELETE FROM Material where idMaterial = id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `fullTreeActivity`()
BEGIN

SELECT node.name, node.idActivity
FROM Activity AS node, Activity AS parent
WHERE node.lft BETWEEN parent.lft AND parent.rgt
        AND parent.idActivity = 1 AND node.Active = 1
ORDER BY node.lft;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getActivityEvents`(ID INT)
BEGIN
	SELECT a.idEvent, Name, idActivity
	FROM ActivityContains a JOIN Event e on a.idEvent = e.idEvent
	WHERE idActivity = ID;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `moveActivity`(FROMK INT, TOK INT)
BEGIN
SET SQL_SAFE_UPDATES=0;
UPDATE `Activity` AS `t0`
	JOIN `Activity` AS `object` ON `object`.`idActivity`=FROMK
	JOIN `Activity` AS `parent` ON `parent`.`idActivity`=TOK
SET
`t0`.`lft` = `t0`.`lft` +
    IF (`parent`.`lft` < `object`.`lft`,
         IF (`t0`.`lft` >= `object`.`rgt` + 1, 0,
                IF (`t0`.`lft` >= `object`.`lft`, `parent`.`lft` - `object`.`lft` + 1,
                        IF (`t0`.`lft` >= `parent`.`lft` + 1, `object`.`rgt` - `object`.`lft` + 1 , 0
                            )
                    )
             ),
         IF (`t0`.`lft` >= `parent`.`lft` + 1, 0,
                IF (`t0`.`lft` >= `object`.`rgt` + 1, -`object`.`rgt` + `object`.`lft` - 1,
                        IF (`t0`.`lft` >= `object`.`lft`, `parent`.`lft` - `object`.`rgt`, 0
                            )
                    )
             )
        ),
`t0`.`rgt` = `t0`.`rgt` +
    IF (`parent`.`lft` < `object`.`lft`,
         IF (`t0`.`rgt` >= `object`.`rgt` + 1, 0,
                IF (`t0`.`rgt` >= `object`.`lft`, `parent`.`lft` - `object`.`lft` + 1,
                        IF (`t0`.`rgt` >= `parent`.`lft` + 1, `object`.`rgt` - `object`.`lft` + 1 , 0
                            )
                    )
             ),
         IF (`t0`.`rgt` >= `parent`.`lft` + 1, 0,
                IF (`t0`.`rgt` >= `object`.`rgt` + 1, -`object`.`rgt` + `object`.`lft` - 1,
                        IF (`t0`.`rgt` >= `object`.`lft`, `parent`.`lft` - `object`.`rgt`, 0
                            )
                    )
             )
        )
WHERE `parent`.`lft` < `object`.`lft` OR `parent`.`lft` > `object`.`rgt`;
SET SQL_SAFE_UPDATES=1;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `pathToActivity`(id INT)
BEGIN
	SELECT parent.idActivity as idActivity, parent.Name as Name
	FROM Activity AS node,
        Activity AS parent
	WHERE node.lft BETWEEN parent.lft AND parent.rgt
        AND node.idActivity = id
	ORDER BY node.lft;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sonOfActivity`(id INT)
BEGIN

SELECT node.Name, node.idActivity, (COUNT(parent.idActivity) - (sub_tree.depth + 1)) AS depth
FROM Activity AS node,
        Activity AS parent,
        Activity AS sub_parent,
        (
                SELECT node.Name, node.idActivity, (COUNT(parent.idActivity) - 1) AS depth
                FROM Activity AS node,
                        Activity AS parent
                WHERE node.lft BETWEEN parent.lft AND parent.rgt
                        AND node.idActivity = id
                GROUP BY node.name, node.idActivity
                ORDER BY node.lft
        )AS sub_tree
WHERE node.lft BETWEEN parent.lft AND parent.rgt
        AND node.lft BETWEEN sub_parent.lft AND sub_parent.rgt
        AND sub_parent.idActivity = sub_tree.idActivity
GROUP BY node.Name, node.idActivity
HAVING depth = 1
ORDER BY node.lft;	
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `treeFormated`()
BEGIN
SELECT CONCAT( REPEAT( '* ', (COUNT(parent.Name) - 1) ), node.Name) AS name, node.idActivity
	FROM Activity AS node,
        Activity AS parent
	WHERE node.lft BETWEEN parent.lft AND parent.rgt
	GROUP BY node.Name
	ORDER BY node.lft;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `activity`
--

CREATE TABLE IF NOT EXISTS `activity` (
  `idActivity` int(10) unsigned NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Description` mediumtext NOT NULL,
  `BeginDate` date NOT NULL,
  `CoverPicture` varchar(100) NOT NULL,
  `lft` int(11) NOT NULL,
  `rgt` int(11) NOT NULL,
  `Active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idActivity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `activity`
--

INSERT INTO `activity` (`idActivity`, `Name`, `Description`, `BeginDate`, `CoverPicture`, `lft`, `rgt`, `Active`) VALUES
(1, 'Root', 'Glavni', '2004-12-20', 'slika.com/coverRoot.png', 1, 30, 1),
(2, 'Informatika', 'Ä‡ÄÄ‡ÄÄ‡ÄÄ‡Ñ›Ñ‡Ñ›Ñ‡Ñ›Ñ‡Ð°ÑÐ´', '2004-12-20', 'slika.com/coverI.png', 2, 21, 1),
(3, 'Matematika', 'MSmer', '2004-12-20', 'slika.com/coverM.png', 22, 27, 1),
(4, 'Astronomija', 'ASmer', '2004-12-20', 'slika.com/coverA.png', 28, 29, 1),
(5, 'UAR', 'Opis', '2004-12-20', 'slika.com/cover.png', 15, 16, 1),
(6, 'UOR', 'Opis', '2004-12-20', 'slika.com/cover.png', 17, 18, 1),
(7, 'UVIT', 'Opis', '2004-12-20', 'slika.com/cover.png', 19, 20, 1),
(8, 'Analiza 1', 'Opis', '2004-12-20', 'slika.com/cover.png', 23, 24, 1),
(9, 'Analiza 2', 'Opis', '2004-12-20', 'slika.com/cover.png', 25, 26, 1),
(24, 'Projektovanje Baza Podataka', 'Kurs osposobljava studenta da samostalno modelira baze podataka koje odgovaraju zahtevnim informacionim sistemima.', '2013-09-23', 'www.matf.bg.ac.rs/slika.png', 3, 14, 1),
(28, 'Predavanja', 'Opis predavanja', '2013-09-23', 'www.slike.com/pbp.png', 6, 7, 1),
(29, 'Vezbe', 'Opis vezbi', '2013-01-23', 'www.nekeslike.com/slike.png', 4, 5, 1);

--
-- Triggers `activity`
--
DROP TRIGGER IF EXISTS `Activity_BDEL`;
DELIMITER //
CREATE TRIGGER `Activity_BDEL` BEFORE DELETE ON `activity`
 FOR EACH ROW begin
	DELETE FROM Comment Where idParent = old.idActivity;
	DELETE FROM Notification Where idParent = old.idActivity;
	DELETE FROM ActivityParticipant Where idActivity = old.idActivity;
	DELETE FROM ActivityHolder Where idActivity = old.idActivity;
	DELETE FROM ActivityContains Where idActivity = old.idActivity;
	DELETE FROM Event Where idEvent not in (select idEvent from ActivityContains);
end
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `activityarchive`
--

CREATE TABLE IF NOT EXISTS `activityarchive` (
  `idActivity` int(10) unsigned NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Description` mediumtext NOT NULL,
  `BeginDate` date NOT NULL,
  `coverPicture` int(11) NOT NULL,
  PRIMARY KEY (`idActivity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `activitycontains`
--

CREATE TABLE IF NOT EXISTS `activitycontains` (
  `idEvent` int(10) unsigned NOT NULL,
  `idActivity` int(10) unsigned NOT NULL,
  PRIMARY KEY (`idEvent`,`idActivity`),
  KEY `fk_Predavanje_has_Kurs_Kurs1_idx` (`idActivity`),
  KEY `fk_Predavanje_has_Kurs_Predavanje1_idx` (`idEvent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `activitycontains`
--

INSERT INTO `activitycontains` (`idEvent`, `idActivity`) VALUES
(15, 5),
(10, 8),
(11, 8),
(12, 9),
(13, 9),
(14, 9),
(30, 28),
(31, 28),
(32, 28),
(33, 28),
(34, 29),
(35, 29),
(36, 29),
(37, 29),
(38, 29);

-- --------------------------------------------------------

--
-- Table structure for table `activitycontainsarchive`
--

CREATE TABLE IF NOT EXISTS `activitycontainsarchive` (
  `idActivity` int(10) unsigned NOT NULL,
  `Archived` tinyint(1) NOT NULL,
  `idEvent` int(10) unsigned NOT NULL,
  PRIMARY KEY (`idActivity`),
  KEY `fk_KursArhiva_has_PredavanjeArhiva_KursArhiva1_idx` (`idActivity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `activityholder`
--

CREATE TABLE IF NOT EXISTS `activityholder` (
  `user_id` int(11) NOT NULL,
  `idActivity` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`idActivity`),
  KEY `fk_Korisnik_has_Kurs1_Kurs1_idx` (`idActivity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `activityholder`
--

INSERT INTO `activityholder` (`user_id`, `idActivity`) VALUES
(1, 2),
(32, 24),
(33, 24),
(32, 28),
(33, 28),
(32, 29),
(33, 29);

-- --------------------------------------------------------

--
-- Table structure for table `activityholderarchive`
--

CREATE TABLE IF NOT EXISTS `activityholderarchive` (
  `user_id` int(11) NOT NULL,
  `idActivity` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`idActivity`),
  KEY `fk_Korisnik_has_Kurs1_Kurs1_idx` (`idActivity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `activityparticipant`
--

CREATE TABLE IF NOT EXISTS `activityparticipant` (
  `user_id` int(11) NOT NULL,
  `idActivity` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`idActivity`),
  KEY `fk_Korisnik_has_Kurs_Kurs1_idx` (`idActivity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `activityparticipant`
--

INSERT INTO `activityparticipant` (`user_id`, `idActivity`) VALUES
(1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE IF NOT EXISTS `comment` (
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `idParent` int(10) unsigned NOT NULL,
  `user_id` int(11) NOT NULL,
  `typeParent` varchar(10) CHARACTER SET ucs2 NOT NULL,
  `Comment` tinytext NOT NULL,
  PRIMARY KEY (`time`,`user_id`,`idParent`),
  KEY `fk_Komentari_Korisnik1_idx` (`user_id`),
  KEY `fk_id_komentar_idx` (`idParent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `entity`
--

CREATE TABLE IF NOT EXISTS `entity` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tipEntiteta` varchar(10) CHARACTER SET ucs2 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=39 ;

--
-- Dumping data for table `entity`
--

INSERT INTO `entity` (`id`, `tipEntiteta`) VALUES
(1, 'Activity'),
(2, 'Activity'),
(3, 'Activity'),
(4, 'Activity'),
(5, 'Activity'),
(6, 'Activity'),
(7, 'Activity'),
(8, 'Activity'),
(9, 'Activity'),
(10, 'Event'),
(11, 'Event'),
(12, 'Event'),
(13, 'Event'),
(14, 'Event'),
(15, 'Event'),
(16, 'Material'),
(17, 'Proposal'),
(18, 'Proposal'),
(19, 'Proposal'),
(20, 'Proposal'),
(21, 'Proposal'),
(22, 'Proposal'),
(23, 'Proposal'),
(24, 'Activity'),
(25, 'Activity'),
(26, 'Activity'),
(27, 'Activity'),
(28, 'Activity'),
(29, 'Activity'),
(30, 'Event'),
(31, 'Event'),
(32, 'Event'),
(33, 'Event'),
(34, 'Event'),
(35, 'Event'),
(36, 'Event'),
(37, 'Event'),
(38, 'Event');

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE IF NOT EXISTS `event` (
  `idEvent` int(10) unsigned NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Description` mediumtext NOT NULL,
  `Venue` varchar(50) NOT NULL,
  `Date` date NOT NULL,
  `Time` time NOT NULL,
  PRIMARY KEY (`idEvent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `event`
--

INSERT INTO `event` (`idEvent`, `Name`, `Description`, `Venue`, `Date`, `Time`) VALUES
(10, 'A1 Event 1', 'First Event', '708', '2004-12-20', '13:00:00'),
(11, 'A1 Event 2', 'Second Event', '708', '2004-12-20', '13:00:00'),
(12, 'A2 Event 1', 'First A2 Event', '708', '2004-12-20', '13:00:00'),
(13, 'A2 Event 2', 'Secon A2 Event', '708', '2004-12-20', '13:00:00'),
(14, 'A2 Event 3', 'Third A2 Event', '708', '2004-12-20', '13:00:00'),
(15, 'Naziv predavanja', 'Prvo Event', '708', '2004-12-20', '13:00:00'),
(30, 'Semantičko modeliranje I', 'http://www.matf.bg.ac.rs/~gordana/PRED4.pdf', '718', '2004-12-20', '18:00:00'),
(31, 'Semantičko modeliranje II', 'http://www.matf.bg.ac.rs/~gordana/PRED5.pdf', '718', '2011-12-20', '18:00:00'),
(32, 'Indeksne Strukture B Stablo', 'http://www.matf.bg.ac.rs/~gordana/B-stablo.pdf', '718', '2018-12-20', '18:00:00'),
(33, 'Indeksne Strukture B+ Stablo', 'http://www.matf.bg.ac.rs/~gordana/B+stablo.pdf', '718', '2025-12-20', '18:00:00'),
(34, 'ER Model, EER Model', 'http://poincare.matf.bg.ac.rs/~ivana/courses/pbp/ERcas1.pdf', 'JAG2', '2001-12-20', '17:00:00'),
(35, 'Prevođenje EER modela u relacioni model', 'http://poincare.matf.bg.ac.rs/~ivana/courses/pbp/LogickoProjektovanje.G.PavlovicLazetic.pdf', 'JAG2', '2008-12-20', '17:00:00'),
(36, 'Triggeri', 'http://poincare.matf.bg.ac.rs/~ivana/courses/pbp/trigeri.pdf', 'JAG2', '2015-12-20', '17:00:00'),
(37, 'Normalizacija.', 'http://poincare.matf.bg.ac.rs/~ivana/courses/pbp/NFalgoritmi.pdf', 'JAG2', '2022-12-20', '17:00:00'),
(38, 'Fizička organizacija', 'http://poincare.matf.bg.ac.rs/~ivana/courses/pbp/B-stabla.pdf', 'JAG2', '2029-12-20', '17:00:00');

--
-- Triggers `event`
--
DROP TRIGGER IF EXISTS `Event_BDEL`;
DELIMITER //
CREATE TRIGGER `Event_BDEL` BEFORE DELETE ON `event`
 FOR EACH ROW begin
	DELETE FROM Ranking Where idEvent = old.idEvent;
	DELETE FROM EventAttendees Where idEvent = old.idEvent;
	DELETE FROM Comment Where idParent = old.idEvent;
	DELETE FROM Notification Where idParent = old.idEvent;
	DELETE FROM ActivityContains where idEvent = old.idEvent;
	DELETE FROM EventHolder Where idEvent = old.idEvent;
	DELETE FROM EventContains Where idEvent = old.idEvent;
	DELETE FROM Material Where idMaterial not in (select idMaterial from EventContains);

end
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `eventarchive`
--

CREATE TABLE IF NOT EXISTS `eventarchive` (
  `idEvent` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(50) NOT NULL,
  `Description` mediumtext NOT NULL,
  `Venue` varchar(50) NOT NULL,
  PRIMARY KEY (`idEvent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `eventattendees`
--

CREATE TABLE IF NOT EXISTS `eventattendees` (
  `username` int(11) NOT NULL,
  `idEvent` int(10) unsigned NOT NULL,
  `idActivity` int(10) unsigned NOT NULL,
  `Was` tinyint(1) NOT NULL,
  PRIMARY KEY (`username`,`idEvent`,`idActivity`),
  KEY `fk_Korisnik_has_Predavanje_Predavanje2_idx` (`idEvent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `eventcontains`
--

CREATE TABLE IF NOT EXISTS `eventcontains` (
  `idEvent` int(10) unsigned NOT NULL,
  `idMaterial` int(10) unsigned NOT NULL,
  PRIMARY KEY (`idEvent`,`idMaterial`),
  KEY `fk_Predavanje_has_Materijal_Materijal1_idx` (`idMaterial`),
  KEY `fk_Predavanje_has_Materijal_Predavanje1_idx` (`idEvent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `eventcontains`
--

INSERT INTO `eventcontains` (`idEvent`, `idMaterial`) VALUES
(13, 16);

-- --------------------------------------------------------

--
-- Table structure for table `eventcontainsarchive`
--

CREATE TABLE IF NOT EXISTS `eventcontainsarchive` (
  `idMaterial` int(10) unsigned NOT NULL,
  `idEvent` int(10) unsigned NOT NULL,
  PRIMARY KEY (`idMaterial`,`idEvent`),
  KEY `fk_MaterijalArhiva_has_PredavanjeArhiva_PredavanjeArhiva1_idx` (`idEvent`),
  KEY `fk_MaterijalArhiva_has_PredavanjeArhiva_MaterijalArhiva1_idx` (`idMaterial`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `eventholder`
--

CREATE TABLE IF NOT EXISTS `eventholder` (
  `user_id` int(11) NOT NULL,
  `idEvent` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`idEvent`),
  KEY `fk_Korisnik_has_Predavanje_Predavanje1_idx` (`idEvent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `eventholder`
--

INSERT INTO `eventholder` (`user_id`, `idEvent`) VALUES
(32, 30),
(33, 30),
(32, 31),
(33, 31),
(32, 32),
(33, 32),
(32, 33),
(33, 33),
(32, 34),
(33, 34),
(32, 35),
(33, 35),
(32, 36),
(33, 36),
(32, 37),
(33, 37),
(32, 38),
(33, 38);

-- --------------------------------------------------------

--
-- Table structure for table `eventholderarchive`
--

CREATE TABLE IF NOT EXISTS `eventholderarchive` (
  `user_id` int(11) NOT NULL,
  `idEvent` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`idEvent`),
  KEY `fk_Korisnik_has_Predavanje_Predavanje1_idx` (`idEvent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `material`
--

CREATE TABLE IF NOT EXISTS `material` (
  `idMaterial` int(10) unsigned NOT NULL,
  `Name` varchar(50) NOT NULL,
  `URI` varchar(100) NOT NULL,
  `Type` varchar(50) NOT NULL,
  `Date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `OwnerID` int(11) NOT NULL,
  PRIMARY KEY (`idMaterial`),
  KEY `fk_ownerid_idx` (`OwnerID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `material`
--

INSERT INTO `material` (`idMaterial`, `Name`, `URI`, `Type`, `Date`, `OwnerID`) VALUES
(16, 'Prvi Materijal č', 'nesto.com/blah.pdf', 'PDF Dokument', '2014-01-08 21:19:50', 1);

--
-- Triggers `material`
--
DROP TRIGGER IF EXISTS `Material_BDEL`;
DELIMITER //
CREATE TRIGGER `Material_BDEL` BEFORE DELETE ON `material`
 FOR EACH ROW begin
	DELETE FROM Comment where idParent = old.idMaterial;
	DELETE FROM Notification where idParent = old.idMaterial;
end
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `materialarchive`
--

CREATE TABLE IF NOT EXISTS `materialarchive` (
  `idMaterial` int(10) unsigned NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Link` varchar(100) NOT NULL,
  `Type` varchar(50) NOT NULL,
  `OwnerID` varchar(20) CHARACTER SET ucs2 NOT NULL,
  `Date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`idMaterial`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE IF NOT EXISTS `notification` (
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Flag` binary(3) NOT NULL,
  `idParent` int(10) unsigned NOT NULL,
  `typeParent` varchar(10) CHARACTER SET ucs2 NOT NULL,
  PRIMARY KEY (`time`,`idParent`),
  KEY `id_idx` (`idParent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `notificationsettings`
--

CREATE TABLE IF NOT EXISTS `notificationsettings` (
  `user_id` int(11) NOT NULL,
  `FlagUnwanted` int(11) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `notificationunwanted`
--

CREATE TABLE IF NOT EXISTS `notificationunwanted` (
  `user_id` int(11) NOT NULL,
  `idParent` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`idParent`),
  KEY `fk_Obaveštenja_has_PodešavanjeObaveštenja_PodešavanjeOb_idx` (`user_id`),
  KEY `fk_id_idx` (`idParent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `proposal`
--

CREATE TABLE IF NOT EXISTS `proposal` (
  `idProposal` int(10) unsigned NOT NULL,
  `UserProposed` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Description` mediumtext NOT NULL,
  PRIMARY KEY (`idProposal`,`UserProposed`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `proposal`
--

INSERT INTO `proposal` (`idProposal`, `UserProposed`, `Name`, `Description`) VALUES
(17, 1, 'predlog', 'opis neki tamo'),
(18, 14, 'probaNayiv', 'dsgvgdvdvshds'),
(19, 14, 'probasdasdasdasdyiv', 'dsgvgdvdvshds'),
(20, 14, 'probaNayiv', 'dsgvgdvdvshds'),
(21, 14, 'probasdasdasdasdyiv', 'dsgvgdvdvshds'),
(22, 1, 'Ime nekog preldoga', 'aÄsdkasdÄgaslÄkgjbaslÄnbaÄslnbklafbadsfg'),
(23, 1, 'Enko predlog', 'bla truc truc');

-- --------------------------------------------------------

--
-- Table structure for table `proposalowner`
--

CREATE TABLE IF NOT EXISTS `proposalowner` (
  `UserPropose` int(11) NOT NULL,
  `idProposal` int(10) unsigned NOT NULL,
  `UserProposed` int(11) NOT NULL,
  PRIMARY KEY (`UserPropose`,`idProposal`,`UserProposed`),
  KEY `fk_Korisnik_has_Predlog1_Predlog1_idx` (`idProposal`),
  KEY `fk_ProposalOwner_User1_idx` (`UserProposed`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `proposalowner`
--

INSERT INTO `proposalowner` (`UserPropose`, `idProposal`, `UserProposed`) VALUES
(1, 23, 1);

-- --------------------------------------------------------

--
-- Table structure for table `proposalsupport`
--

CREATE TABLE IF NOT EXISTS `proposalsupport` (
  `user_id` int(11) NOT NULL,
  `idProposal` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`idProposal`),
  KEY `fk_Korisnik_has_Predlog_Predlog1_idx` (`idProposal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `proposalsupport`
--

INSERT INTO `proposalsupport` (`user_id`, `idProposal`) VALUES
(1, 17),
(14, 18),
(14, 19),
(14, 20),
(14, 21),
(1, 22),
(1, 23),
(31, 23);

-- --------------------------------------------------------

--
-- Table structure for table `ranking`
--

CREATE TABLE IF NOT EXISTS `ranking` (
  `VotedFor` int(11) NOT NULL,
  `Grade` int(11) NOT NULL,
  `Vote` int(11) NOT NULL,
  `idEvent` int(10) unsigned NOT NULL,
  `idActivity` int(10) unsigned NOT NULL,
  PRIMARY KEY (`VotedFor`,`Vote`,`idEvent`,`idActivity`),
  KEY `fk_Glasovi_Predaje1_idx` (`VotedFor`),
  KEY `fk_Glasovi_Prisustvovao1_idx` (`Vote`,`idEvent`,`idActivity`),
  KEY `fk_Glasovi_Prisustvovao1` (`idEvent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) CHARACTER SET ucs2 NOT NULL,
  `password` varchar(64) NOT NULL,
  `acc_type` int(11) NOT NULL,
  `Usercol` varchar(45) DEFAULT NULL,
  `Usercol1` varchar(45) DEFAULT NULL,
  `mail` varchar(40) NOT NULL,
  `Ext_login` tinyint(1) NOT NULL,
  `Name` varchar(15) NOT NULL,
  `Surname` varchar(30) NOT NULL,
  `www` varchar(100) DEFAULT NULL,
  `Proffession` varchar(45) DEFAULT NULL,
  `School` varchar(45) DEFAULT NULL,
  `ProfilePicture` varchar(45) DEFAULT NULL,
  `salt` varchar(5) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username_UNIQUE` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=34 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `username`, `password`, `acc_type`, `Usercol`, `Usercol1`, `mail`, `Ext_login`, `Name`, `Surname`, `www`, `Proffession`, `School`, `ProfilePicture`, `salt`, `status`) VALUES
(1, 'stevos', 'f1ec24e771bb1191a796221cebca4a5a', 99, NULL, NULL, 'teva.biz@gmail.com', 0, 'Stefan', 'Isidorović', '', 'Džabalebaroš/lelemud', 'OS Sveti Sava', NULL, '4eBgJ', NULL),
(2, 'peros', '3c0b3ff6f864f84f34ab10c8e87f4da1', 1, NULL, NULL, 'pera@blah.com', 0, 'Pera', 'Perić', 'www,nekisajt.com', 'Limar', 'OS Sveti Sava', NULL, '3nEne', NULL),
(3, 'darko91', '3570407b25a12f0bc101fa4d6426a5ea', 1, NULL, NULL, 'mi10034@matf.bg.ac.rs', 0, 'Darko', 'Vidakovic', 'www.redtube.com', 'Porno glumac', 'OS Popinski borci ', NULL, 'epZVv', NULL),
(4, 'techaz', 'ca4ebdf22334d5cc4b45093d71b383ac', 1, NULL, NULL, 'vladimir.djordjevic@outlook.com', 0, 'Vladimir', 'Djordjevic', '', 'student', 'visa elektrotehnicka', NULL, 'eZswP', NULL),
(5, 'Kamicak', 'be7c555af2fd022311dac52e13d36397', 1, NULL, NULL, 'mojimail@hotmail.com', 0, 'Tamara', 'Leposavic', 'www.mojsajt.com', 'studentkinja', 'Pavle Savic', NULL, 'FC03W', NULL),
(6, 'guza007', 'bafdbc6b3f1b3b6bc1f9eb607437bb8d', 1, NULL, NULL, 'perperoglu@gmail.com', 0, 'Nenad', 'Avramovic', 'www.24hxxx.com', 'student', 'tesla', NULL, 'bkUIg', NULL),
(7, 'xboxdajmi', '757d1efe403e10c4a718c1e29eda50e1', 1, NULL, NULL, 'bojants91@gmail.com', 0, 'Bojan', 'Nestorovic', 'www.fleksbuks.com', 'Cekam Stevu da mi da xbox', 'OS Sveti Sava! Kako si znao majmune!?', NULL, 'P5x2L', NULL),
(8, 'Blackbolt', '77efccb47961b952ed63c8d8d0847a7c', 1, NULL, NULL, 'blackbolt990@gmail.com', 0, 'Bojan', 'Milic', 'www.malaskolamatemat', 'Student', 'Osnovna', NULL, 'LB8h3', NULL),
(9, 'Komek', 'f49bc0b731cb33b561e0c67b1f08120a', 1, NULL, NULL, 'vlax2709@gmail.com', 0, 'Vladimir', 'Martić', 'www.daregej.com', 'student', 'Pancevacka Skola 2', NULL, '4c8Bd', NULL),
(10, 'Bambi', '4ce3945694a01ed3471e64306acd0416', 1, NULL, NULL, 'lanamandic@hotmail.com', 0, 'Lana', 'Mandic', '', 'hote', 'OS Pavle Savic', NULL, 'N1Rd2', NULL),
(11, 'zibrr', '6a33f5f15e026184b7a5d71dc89febca', 1, NULL, NULL, 'marko.stanacic@gmail.com', 0, 'Marko', 'Stanacic', '', 'Student', '', NULL, 'HU7lD', NULL),
(12, 'Bambi456', '3b39f840099e18bd0f14768d3532e7f8', 1, NULL, NULL, 'lana.mandic.11@hotmail.com', 0, 'Lana', 'Mandic', 'www.mojsajt.com', 'menadzer', 'OS Pavle Savic', NULL, 'Fwz8u', NULL),
(13, 'zdravko', 'bb95dc6ac895bab4b42a4489c0316d93', 1, NULL, NULL, 'fdsaf@fsd.com', 0, 'Zdravko', 'Rakic', '', 'Student', 'Peta gimnazija', NULL, 'ALwjw', NULL),
(14, 'VladaMT', 'eeddb415ea0c0cba0b67aa77a67ddd53', 1, NULL, NULL, 'vladaherbalife@gmail.com', 0, 'Vlada', 'Ivanovic', '', 'wellness savetnik', 'Sedma Beogradska Gimnazija', NULL, '5w144', NULL),
(15, 'Deljenje0NijeDefinis', 'c044955f99a413ac9f8b59fcd478197c', 1, NULL, NULL, 'missuchiha93@gmail.com', 0, 'Branislava', 'Zivkovic', '', 'Kucna pomocnica', 'OS Jovan Miodragovic', NULL, '361OU', NULL),
(16, 'lnluksa', 'bf9ae491b196f124d229ff94cd4dc75a', 1, NULL, NULL, 'pamtii@gmail.com', 0, 'Nikola', 'Lukovic', '', 'Student', 'OS Arilje 2', NULL, '85v3z', NULL),
(17, 'jo.stan8', '539db3d1bb53745732b66502989e056b', 1, NULL, NULL, 'jovanalp.stan@gmail.com', 0, 'Jovana', 'Stanimirović', 'www.nemasajta.com', 'dangubljenje', 'valjevska gimnazija', NULL, 'S269A', NULL),
(18, 'divic', 'a4eb5d39cafd17a1f7287288e7c4942a', 1, NULL, NULL, 'logotijedojaja@vrh.com', 0, 'Nikola', 'Divic', 'www.mojsajt.com', 'Metalokobasičar', 'MATF', NULL, 'uFzqb', NULL),
(19, 'prvul', 'b205799a73190fa2512f7fae09b1721a', 1, NULL, NULL, 'petar.prvulovic@gmail.com', 0, 'petar', 'prvulovic', 'prvulovic.net', 'Mladji prvulovic', 'OS Sveti Prvul', NULL, 'UioRH', NULL),
(20, 'poiuy', '6bfbcf7e783550b6d631d46c1b56554e', 1, NULL, NULL, 'example@example.com', 0, 'Nikola', 'Nikolic', '', 'jibubkib', 'bikb uiuojv', NULL, '2qi7M', NULL),
(21, 'savicmi', '89695bf727267a65b5b0d327739dac52', 1, NULL, NULL, 'savicmi@gmail.com', 0, 'Miloš', 'Savić', '', 'Informatičar', 'OS Miodrag Vuković', NULL, 'jhjWD', NULL),
(22, 'Stefi', 'd1d81fd2e219a6234d8d5fa3c75431a4', 1, NULL, NULL, 'stefanacerovina@gmail.com', 0, 'Stefana', 'Cerovina', '', 'Student', 'OS Varvarin 2', NULL, 'C1Ct5', NULL),
(23, 'wujic', '5441d8e76f748450c105b5a8442a76b6', 1, NULL, NULL, 'wujic88@gmail.com', 0, 'Predrag', 'Vujic', 'www.wujic.com', 'Student, web developer', 'MATF', NULL, 'CVJMt', NULL),
(24, 'nejedinstveno korisn', '50947e17635bdda9613f75f593262c77', 1, NULL, NULL, 'erdos@mejl.com', 0, 'Milica', 'Jovanović', 'www.sajt.com', 'Student', 'OS Nenad Milosavljevic', NULL, '6COP1', NULL),
(25, 'lollol11', 'a9376dd52e04306dd3eba81b90bc56e4', 1, NULL, NULL, 'mirjana_1991@hotmail.com', 0, 'Mirjana', 'Kostić', '....................', '..................................', '...................................', NULL, 'uAdE2', NULL),
(26, 'jupike', '28b9a989a6d8251938a7840ef253756a', 99, NULL, NULL, 'jupikearilje@gmail.com', 0, 'Filip', 'Lukovic', '', 'Student', 'Matematicki fakultet', NULL, '95bK3', NULL),
(27, 'skostic92', 'a40dbec3386d9a7fd3f44a87989356f5', 1, NULL, NULL, 'skostic9242@gmail.com', 0, 'Stefan', 'Kostic', 'nestolazno', 'nestolazno', 'nestolazno', NULL, '44T4P', NULL),
(28, 'enco164', 'c726044d658f914d6a36fb2ffce1f237', 99, NULL, NULL, 'enco164@gmail.com', 0, 'Uros', 'Milenkovic', 'www.matf.bg.ac.rs/~m', 'VBA programer lol', 'Koga briga Stevo za tvoju osnovnu skolu, kaka', NULL, 'R2EBP', NULL),
(29, 'Himenosa', 'f0b5fb9749c27c42eb36ada38b3631c5', 1, NULL, NULL, 'himenosa@gmail.com', 0, 'Ivana', 'Ribić', 'www.mojsajt.com', 'student', 'OŠ Jovan Jovanović Zmaj', NULL, 'psDMZ', NULL),
(30, 'djiraja', '6f96ff2b283c6cfa58886197f656ceac', 1, NULL, NULL, 'djiraja@live.com', 0, 'Marko', 'Makaric', 'www.teslabg.edu.rs', 'Senin', 'ETS Nikola Tesla', NULL, '7Uxb5', NULL),
(31, 'dummy', '84701b29c71bac3563d83f5eccebc2f1', 1, NULL, NULL, 'dum@dum.com', 0, 'Dummy', 'User', 'www.truc.com', 'Glupan', 'Bla bla truć', NULL, 'Qj41x', NULL),
(32, 'ivana', 'e18a9ee54d08e2adcf407e580c2e661f', 99, NULL, NULL, 'ivana@matf.bg.ac.rs', 0, 'Ivana', 'Tanasijević', 'http://poincare.matf.bg.ac.rs/~ivana', 'Asistent', 'Matematički Fakultet', NULL, 'l44md', NULL),
(33, 'jgraovac', '6a5752029867f709d67b938a2bab38d5', 99, NULL, NULL, 'jgraovac@matf.bg.ac.rs', 0, 'Jelena', 'Graovac', 'http://poincare.matf.bg.ac.rs/~jgraovac', 'Asistent', 'Matematički Fakultet', NULL, 't6ug5', NULL);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity`
--
ALTER TABLE `activity`
  ADD CONSTRAINT `fk_id_kurs` FOREIGN KEY (`idActivity`) REFERENCES `entity` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `activitycontains`
--
ALTER TABLE `activitycontains`
  ADD CONSTRAINT `fk_Predavanje_has_Kurs_Predavanje1` FOREIGN KEY (`idEvent`) REFERENCES `event` (`idEvent`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Predavanje_has_Kurs_Kurs1` FOREIGN KEY (`idActivity`) REFERENCES `activity` (`idActivity`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `activitycontainsarchive`
--
ALTER TABLE `activitycontainsarchive`
  ADD CONSTRAINT `fk_KursArhiva_has_PredavanjeArhiva_KursArhiva1` FOREIGN KEY (`idActivity`) REFERENCES `activityarchive` (`idActivity`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `activityholder`
--
ALTER TABLE `activityholder`
  ADD CONSTRAINT `fk_Korisnik_has_Kurs1_Korisnik1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Korisnik_has_Kurs1_Kurs1` FOREIGN KEY (`idActivity`) REFERENCES `activity` (`idActivity`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `activityholderarchive`
--
ALTER TABLE `activityholderarchive`
  ADD CONSTRAINT `fk_Korisnik_has_Kurs1_Kurs10` FOREIGN KEY (`idActivity`) REFERENCES `activityarchive` (`idActivity`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `activityparticipant`
--
ALTER TABLE `activityparticipant`
  ADD CONSTRAINT `fk_Korisnik_has_Kurs_Korisnik1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Korisnik_has_Kurs_Kurs1` FOREIGN KEY (`idActivity`) REFERENCES `activity` (`idActivity`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `fk_Komentari_Korisnik1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_id_komentar` FOREIGN KEY (`idParent`) REFERENCES `entity` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `event`
--
ALTER TABLE `event`
  ADD CONSTRAINT `fk_id_predavanje` FOREIGN KEY (`idEvent`) REFERENCES `entity` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `eventattendees`
--
ALTER TABLE `eventattendees`
  ADD CONSTRAINT `fk_Korisnik_has_Predavanje_Predavanje2` FOREIGN KEY (`idEvent`) REFERENCES `event` (`idEvent`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `eventcontains`
--
ALTER TABLE `eventcontains`
  ADD CONSTRAINT `fk_Predavanje_has_Materijal_Predavanje1` FOREIGN KEY (`idEvent`) REFERENCES `event` (`idEvent`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Predavanje_has_Materijal_Materijal1` FOREIGN KEY (`idMaterial`) REFERENCES `material` (`idMaterial`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `eventcontainsarchive`
--
ALTER TABLE `eventcontainsarchive`
  ADD CONSTRAINT `fk_MaterijalArhiva_has_PredavanjeArhiva_MaterijalArhiva1` FOREIGN KEY (`idMaterial`) REFERENCES `materialarchive` (`idMaterial`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_MaterijalArhiva_has_PredavanjeArhiva_PredavanjeArhiva1` FOREIGN KEY (`idEvent`) REFERENCES `eventarchive` (`idEvent`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `eventholder`
--
ALTER TABLE `eventholder`
  ADD CONSTRAINT `fk_Korisnik_has_Predavanje_Korisnik1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Korisnik_has_Predavanje_Predavanje1` FOREIGN KEY (`idEvent`) REFERENCES `event` (`idEvent`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `eventholderarchive`
--
ALTER TABLE `eventholderarchive`
  ADD CONSTRAINT `fk_Korisnik_has_Predavanje_Predavanje10` FOREIGN KEY (`idEvent`) REFERENCES `eventarchive` (`idEvent`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `material`
--
ALTER TABLE `material`
  ADD CONSTRAINT `fk_id_materijal` FOREIGN KEY (`idMaterial`) REFERENCES `entity` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_ownerid` FOREIGN KEY (`OwnerID`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `fk_id_obavestenje` FOREIGN KEY (`idParent`) REFERENCES `entity` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `notificationsettings`
--
ALTER TABLE `notificationsettings`
  ADD CONSTRAINT `fk_PodešavanjeObaveštenja_Korisnik1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `notificationunwanted`
--
ALTER TABLE `notificationunwanted`
  ADD CONSTRAINT `fk_Obaveštenja_has_PodešavanjeObaveštenja_PodešavanjeObav1` FOREIGN KEY (`user_id`) REFERENCES `notificationsettings` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_id` FOREIGN KEY (`idParent`) REFERENCES `entity` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `proposal`
--
ALTER TABLE `proposal`
  ADD CONSTRAINT `fk_id_predlog` FOREIGN KEY (`idProposal`) REFERENCES `entity` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `proposalowner`
--
ALTER TABLE `proposalowner`
  ADD CONSTRAINT `fk_Korisnik_has_Predlog1_Korisnik1` FOREIGN KEY (`UserPropose`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Korisnik_has_Predlog1_Predlog1` FOREIGN KEY (`idProposal`) REFERENCES `proposal` (`idProposal`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_ProposalOwner_User1` FOREIGN KEY (`UserProposed`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `proposalsupport`
--
ALTER TABLE `proposalsupport`
  ADD CONSTRAINT `fk_Korisnik_has_Predlog_Korisnik1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Korisnik_has_Predlog_Predlog1` FOREIGN KEY (`idProposal`) REFERENCES `proposal` (`idProposal`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `ranking`
--
ALTER TABLE `ranking`
  ADD CONSTRAINT `fk_Glasovi_Predaje1` FOREIGN KEY (`VotedFor`) REFERENCES `eventholder` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Glasovi_Prisustvovao1` FOREIGN KEY (`idEvent`) REFERENCES `eventattendees` (`idEvent`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
