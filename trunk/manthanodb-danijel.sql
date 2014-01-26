/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50532
Source Host           : localhost:3306
Source Database       : manthanodb

Target Server Type    : MYSQL
Target Server Version : 50532
File Encoding         : 65001

Date: 2014-01-26 23:03:09
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `activity`
-- ----------------------------
DROP TABLE IF EXISTS `activity`;
CREATE TABLE `activity` (
  `idActivity` int(10) unsigned NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Description` mediumtext NOT NULL,
  `BeginDate` date NOT NULL,
  `CoverPicture` varchar(100) NOT NULL,
  `lft` int(11) NOT NULL,
  `rgt` int(11) NOT NULL,
  `Active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idActivity`),
  CONSTRAINT `fk_id_kurs` FOREIGN KEY (`idActivity`) REFERENCES `entity` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of activity
-- ----------------------------
INSERT INTO `activity` VALUES ('1', 'Root', 'Glavni', '2004-12-20', 'slika.com/coverRoot.png', '1', '18', '1');
INSERT INTO `activity` VALUES ('2', 'Informatika', 'ISmer', '2004-12-20', 'slika.com/coverI.png', '2', '9', '1');
INSERT INTO `activity` VALUES ('3', 'Matematika', 'MSmer', '2004-12-20', 'slika.com/coverM.png', '10', '15', '1');
INSERT INTO `activity` VALUES ('4', 'Astronomija', 'ASmer', '2004-12-20', 'slika.com/coverA.png', '16', '17', '1');
INSERT INTO `activity` VALUES ('5', 'UAR', 'Opis', '2004-12-20', 'slika.com/cover.png', '3', '4', '1');
INSERT INTO `activity` VALUES ('6', 'UOR', 'Opis', '2004-12-20', 'slika.com/cover.png', '5', '6', '1');
INSERT INTO `activity` VALUES ('7', 'UVIT', 'Opis', '2004-12-20', 'slika.com/cover.png', '7', '8', '1');
INSERT INTO `activity` VALUES ('8', 'Analiza 1', 'Opis', '2004-12-20', 'slika.com/cover.png', '11', '12', '1');
INSERT INTO `activity` VALUES ('9', 'Analiza 2', 'Opis', '2004-12-20', 'slika.com/cover.png', '13', '14', '1');

-- ----------------------------
-- Table structure for `activityarchive`
-- ----------------------------
DROP TABLE IF EXISTS `activityarchive`;
CREATE TABLE `activityarchive` (
  `idActivity` int(10) unsigned NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Description` mediumtext NOT NULL,
  `BeginDate` date NOT NULL,
  `coverPicture` int(11) NOT NULL,
  PRIMARY KEY (`idActivity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of activityarchive
-- ----------------------------

-- ----------------------------
-- Table structure for `activitycontains`
-- ----------------------------
DROP TABLE IF EXISTS `activitycontains`;
CREATE TABLE `activitycontains` (
  `idEvent` int(10) unsigned NOT NULL,
  `idActivity` int(10) unsigned NOT NULL,
  PRIMARY KEY (`idEvent`,`idActivity`),
  KEY `fk_Predavanje_has_Kurs_Kurs1_idx` (`idActivity`),
  KEY `fk_Predavanje_has_Kurs_Predavanje1_idx` (`idEvent`),
  CONSTRAINT `fk_Predavanje_has_Kurs_Kurs1` FOREIGN KEY (`idActivity`) REFERENCES `activity` (`idActivity`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Predavanje_has_Kurs_Predavanje1` FOREIGN KEY (`idEvent`) REFERENCES `event` (`idEvent`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of activitycontains
-- ----------------------------
INSERT INTO `activitycontains` VALUES ('15', '5');
INSERT INTO `activitycontains` VALUES ('10', '8');
INSERT INTO `activitycontains` VALUES ('11', '8');
INSERT INTO `activitycontains` VALUES ('12', '9');
INSERT INTO `activitycontains` VALUES ('13', '9');
INSERT INTO `activitycontains` VALUES ('14', '9');

-- ----------------------------
-- Table structure for `activitycontainsarchive`
-- ----------------------------
DROP TABLE IF EXISTS `activitycontainsarchive`;
CREATE TABLE `activitycontainsarchive` (
  `idActivity` int(10) unsigned NOT NULL,
  `Archived` tinyint(1) NOT NULL,
  `idEvent` int(10) unsigned NOT NULL,
  PRIMARY KEY (`idActivity`),
  KEY `fk_KursArhiva_has_PredavanjeArhiva_KursArhiva1_idx` (`idActivity`),
  CONSTRAINT `fk_KursArhiva_has_PredavanjeArhiva_KursArhiva1` FOREIGN KEY (`idActivity`) REFERENCES `activityarchive` (`idActivity`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of activitycontainsarchive
-- ----------------------------

-- ----------------------------
-- Table structure for `activityholder`
-- ----------------------------
DROP TABLE IF EXISTS `activityholder`;
CREATE TABLE `activityholder` (
  `user_id` int(11) NOT NULL,
  `idActivity` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`idActivity`),
  KEY `fk_Korisnik_has_Kurs1_Kurs1_idx` (`idActivity`),
  CONSTRAINT `fk_Korisnik_has_Kurs1_Korisnik1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Korisnik_has_Kurs1_Kurs1` FOREIGN KEY (`idActivity`) REFERENCES `activity` (`idActivity`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of activityholder
-- ----------------------------

-- ----------------------------
-- Table structure for `activityholderarchive`
-- ----------------------------
DROP TABLE IF EXISTS `activityholderarchive`;
CREATE TABLE `activityholderarchive` (
  `user_id` int(11) NOT NULL,
  `idActivity` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`idActivity`),
  KEY `fk_Korisnik_has_Kurs1_Kurs1_idx` (`idActivity`),
  CONSTRAINT `fk_Korisnik_has_Kurs1_Kurs10` FOREIGN KEY (`idActivity`) REFERENCES `activityarchive` (`idActivity`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of activityholderarchive
-- ----------------------------

-- ----------------------------
-- Table structure for `activityparticipant`
-- ----------------------------
DROP TABLE IF EXISTS `activityparticipant`;
CREATE TABLE `activityparticipant` (
  `user_id` int(11) NOT NULL,
  `idActivity` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`idActivity`),
  KEY `fk_Korisnik_has_Kurs_Kurs1_idx` (`idActivity`),
  CONSTRAINT `fk_Korisnik_has_Kurs_Korisnik1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Korisnik_has_Kurs_Kurs1` FOREIGN KEY (`idActivity`) REFERENCES `activity` (`idActivity`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of activityparticipant
-- ----------------------------

-- ----------------------------
-- Table structure for `comment`
-- ----------------------------
DROP TABLE IF EXISTS `comment`;
CREATE TABLE `comment` (
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `idParent` int(10) unsigned NOT NULL,
  `user_id` int(11) NOT NULL,
  `typeParent` varchar(10) CHARACTER SET ucs2 NOT NULL,
  `Comment` tinytext NOT NULL,
  PRIMARY KEY (`time`,`user_id`,`idParent`),
  KEY `fk_Komentari_Korisnik1_idx` (`user_id`),
  KEY `fk_id_komentar_idx` (`idParent`),
  CONSTRAINT `fk_id_komentar` FOREIGN KEY (`idParent`) REFERENCES `entity` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Komentari_Korisnik1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of comment
-- ----------------------------

-- ----------------------------
-- Table structure for `email_proxy`
-- ----------------------------
DROP TABLE IF EXISTS `email_proxy`;
CREATE TABLE `email_proxy` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `email_from` varchar(50) NOT NULL,
  `email_to` varchar(50) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `message` varchar(1000) NOT NULL,
  `sent` int(1) NOT NULL DEFAULT '0' COMMENT '0 - unsent  1 - sent',
  `date` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of email_proxy
-- ----------------------------
INSERT INTO `email_proxy` VALUES ('1', '', 'manthanoapp@gmail.com', 'danijel84@gmail.com', 'Manthano - Vaš link za kreiranje nove šifre.', 'Molimo vas potvrdite zahtev za promenu vaše šifre klikom ovaj link:<br>http://localhost/user/forgot_password/change_password/dc7904b2cfd754c8b9e03ae0bf8d4f24e9e80ff3', '1', '1390663044');
INSERT INTO `email_proxy` VALUES ('2', '', 'manthanoapp@gmail.com', 'danijel84@gmail.com', 'Manthano - Vaš link za kreiranje nove šifre.', 'Molimo vas potvrdite zahtev za promenu vaše šifre klikom ovaj link:<br>http://localhost/user/forgot_password/change_password/918a2be33405e531815e8770d8365123b456b3fa', '1', '1390663449');
INSERT INTO `email_proxy` VALUES ('3', '', 'manthanoapp@gmail.com', 'danijel84@gmail.com', 'Manthano - Vaš link za kreiranje nove šifre.', 'Molimo vas potvrdite zahtev za promenu vaše šifre klikom ovaj link:<br>http://localhost/user/forgot_password/change_password/d40b8c61723c1015a42cda4c48dcc45a50d3999d', '1', '1390663634');
INSERT INTO `email_proxy` VALUES ('4', '', 'manthanoapp@gmail.com', 'danijel84@gmail.com', 'Manthano - Vaš link za kreiranje nove šifre.', 'Molimo vas potvrdite zahtev za promenu vaše šifre klikom ovaj link:<br>http://localhost/user/forgot_password/change_password/9a6f781b5507e0ada22f2d8e105c5c26184555f1', '1', '1390663947');

-- ----------------------------
-- Table structure for `entity`
-- ----------------------------
DROP TABLE IF EXISTS `entity`;
CREATE TABLE `entity` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tipEntiteta` varchar(10) CHARACTER SET ucs2 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of entity
-- ----------------------------
INSERT INTO `entity` VALUES ('1', 'Activity');
INSERT INTO `entity` VALUES ('2', 'Activity');
INSERT INTO `entity` VALUES ('3', 'Activity');
INSERT INTO `entity` VALUES ('4', 'Activity');
INSERT INTO `entity` VALUES ('5', 'Activity');
INSERT INTO `entity` VALUES ('6', 'Activity');
INSERT INTO `entity` VALUES ('7', 'Activity');
INSERT INTO `entity` VALUES ('8', 'Activity');
INSERT INTO `entity` VALUES ('9', 'Activity');
INSERT INTO `entity` VALUES ('10', 'Event');
INSERT INTO `entity` VALUES ('11', 'Event');
INSERT INTO `entity` VALUES ('12', 'Event');
INSERT INTO `entity` VALUES ('13', 'Event');
INSERT INTO `entity` VALUES ('14', 'Event');
INSERT INTO `entity` VALUES ('15', 'Event');
INSERT INTO `entity` VALUES ('16', 'Material');

-- ----------------------------
-- Table structure for `event`
-- ----------------------------
DROP TABLE IF EXISTS `event`;
CREATE TABLE `event` (
  `idEvent` int(10) unsigned NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Description` mediumtext NOT NULL,
  `Venue` varchar(50) NOT NULL,
  `Date` date NOT NULL,
  `Time` time NOT NULL,
  PRIMARY KEY (`idEvent`),
  CONSTRAINT `fk_id_predavanje` FOREIGN KEY (`idEvent`) REFERENCES `entity` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of event
-- ----------------------------
INSERT INTO `event` VALUES ('10', 'A1 Event 1', 'First Event', '708', '2004-12-20', '13:00:00');
INSERT INTO `event` VALUES ('11', 'A1 Event 2', 'Second Event', '708', '2004-12-20', '13:00:00');
INSERT INTO `event` VALUES ('12', 'A2 Event 1', 'First A2 Event', '708', '2004-12-20', '13:00:00');
INSERT INTO `event` VALUES ('13', 'A2 Event 2', 'Secon A2 Event', '708', '2004-12-20', '13:00:00');
INSERT INTO `event` VALUES ('14', 'A2 Event 3', 'Third A2 Event', '708', '2004-12-20', '13:00:00');
INSERT INTO `event` VALUES ('15', 'Naziv predavanja', 'Prvo Event', '708', '2004-12-20', '13:00:00');

-- ----------------------------
-- Table structure for `eventarchive`
-- ----------------------------
DROP TABLE IF EXISTS `eventarchive`;
CREATE TABLE `eventarchive` (
  `idEvent` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(50) NOT NULL,
  `Description` mediumtext NOT NULL,
  `Venue` varchar(50) NOT NULL,
  PRIMARY KEY (`idEvent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of eventarchive
-- ----------------------------

-- ----------------------------
-- Table structure for `eventattendees`
-- ----------------------------
DROP TABLE IF EXISTS `eventattendees`;
CREATE TABLE `eventattendees` (
  `username` int(11) NOT NULL,
  `idEvent` int(10) unsigned NOT NULL,
  `idActivity` int(10) unsigned NOT NULL,
  `Was` tinyint(1) NOT NULL,
  PRIMARY KEY (`username`,`idEvent`,`idActivity`),
  KEY `fk_Korisnik_has_Predavanje_Predavanje2_idx` (`idEvent`),
  KEY `fk_EventAttendees_ActivityParticipant1_idx` (`username`,`idActivity`),
  CONSTRAINT `fk_EventAttendees_ActivityParticipant1` FOREIGN KEY (`username`, `idActivity`) REFERENCES `activityparticipant` (`user_id`, `idActivity`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Korisnik_has_Predavanje_Predavanje2` FOREIGN KEY (`idEvent`) REFERENCES `event` (`idEvent`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of eventattendees
-- ----------------------------

-- ----------------------------
-- Table structure for `eventcontains`
-- ----------------------------
DROP TABLE IF EXISTS `eventcontains`;
CREATE TABLE `eventcontains` (
  `idEvent` int(10) unsigned NOT NULL,
  `idMaterial` int(10) unsigned NOT NULL,
  PRIMARY KEY (`idEvent`,`idMaterial`),
  KEY `fk_Predavanje_has_Materijal_Materijal1_idx` (`idMaterial`),
  KEY `fk_Predavanje_has_Materijal_Predavanje1_idx` (`idEvent`),
  CONSTRAINT `fk_Predavanje_has_Materijal_Materijal1` FOREIGN KEY (`idMaterial`) REFERENCES `material` (`idMaterial`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Predavanje_has_Materijal_Predavanje1` FOREIGN KEY (`idEvent`) REFERENCES `event` (`idEvent`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of eventcontains
-- ----------------------------

-- ----------------------------
-- Table structure for `eventcontainsarchive`
-- ----------------------------
DROP TABLE IF EXISTS `eventcontainsarchive`;
CREATE TABLE `eventcontainsarchive` (
  `idMaterial` int(10) unsigned NOT NULL,
  `idEvent` int(10) unsigned NOT NULL,
  PRIMARY KEY (`idMaterial`,`idEvent`),
  KEY `fk_MaterijalArhiva_has_PredavanjeArhiva_PredavanjeArhiva1_idx` (`idEvent`),
  KEY `fk_MaterijalArhiva_has_PredavanjeArhiva_MaterijalArhiva1_idx` (`idMaterial`),
  CONSTRAINT `fk_MaterijalArhiva_has_PredavanjeArhiva_MaterijalArhiva1` FOREIGN KEY (`idMaterial`) REFERENCES `materialarchive` (`idMaterial`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_MaterijalArhiva_has_PredavanjeArhiva_PredavanjeArhiva1` FOREIGN KEY (`idEvent`) REFERENCES `eventarchive` (`idEvent`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of eventcontainsarchive
-- ----------------------------

-- ----------------------------
-- Table structure for `eventholder`
-- ----------------------------
DROP TABLE IF EXISTS `eventholder`;
CREATE TABLE `eventholder` (
  `user_id` int(11) NOT NULL,
  `idEvent` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`idEvent`),
  KEY `fk_Korisnik_has_Predavanje_Predavanje1_idx` (`idEvent`),
  CONSTRAINT `fk_Korisnik_has_Predavanje_Korisnik1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Korisnik_has_Predavanje_Predavanje1` FOREIGN KEY (`idEvent`) REFERENCES `event` (`idEvent`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of eventholder
-- ----------------------------

-- ----------------------------
-- Table structure for `eventholderarchive`
-- ----------------------------
DROP TABLE IF EXISTS `eventholderarchive`;
CREATE TABLE `eventholderarchive` (
  `user_id` int(11) NOT NULL,
  `idEvent` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`idEvent`),
  KEY `fk_Korisnik_has_Predavanje_Predavanje1_idx` (`idEvent`),
  CONSTRAINT `fk_Korisnik_has_Predavanje_Predavanje10` FOREIGN KEY (`idEvent`) REFERENCES `eventarchive` (`idEvent`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of eventholderarchive
-- ----------------------------

-- ----------------------------
-- Table structure for `forgot_password`
-- ----------------------------
DROP TABLE IF EXISTS `forgot_password`;
CREATE TABLE `forgot_password` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hash` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of forgot_password
-- ----------------------------
INSERT INTO `forgot_password` VALUES ('5', '9a6f781b5507e0ada22f2d8e105c5c26184555f1', 'danijel84@gmail.com');

-- ----------------------------
-- Table structure for `material`
-- ----------------------------
DROP TABLE IF EXISTS `material`;
CREATE TABLE `material` (
  `idMaterial` int(10) unsigned NOT NULL,
  `Name` varchar(50) NOT NULL,
  `URI` varchar(100) NOT NULL,
  `Type` varchar(50) NOT NULL,
  `Date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `OwnerID` int(11) NOT NULL,
  PRIMARY KEY (`idMaterial`),
  KEY `fk_ownerid_idx` (`OwnerID`),
  CONSTRAINT `fk_id_materijal` FOREIGN KEY (`idMaterial`) REFERENCES `entity` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_ownerid` FOREIGN KEY (`OwnerID`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of material
-- ----------------------------

-- ----------------------------
-- Table structure for `materialarchive`
-- ----------------------------
DROP TABLE IF EXISTS `materialarchive`;
CREATE TABLE `materialarchive` (
  `idMaterial` int(10) unsigned NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Link` varchar(100) NOT NULL,
  `Type` varchar(50) NOT NULL,
  `OwnerID` varchar(20) CHARACTER SET ucs2 NOT NULL,
  `Date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`idMaterial`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of materialarchive
-- ----------------------------

-- ----------------------------
-- Table structure for `notification`
-- ----------------------------
DROP TABLE IF EXISTS `notification`;
CREATE TABLE `notification` (
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Flag` binary(3) NOT NULL,
  `idParent` int(10) unsigned NOT NULL,
  `typeParent` varchar(10) CHARACTER SET ucs2 NOT NULL,
  PRIMARY KEY (`time`,`idParent`),
  KEY `id_idx` (`idParent`),
  CONSTRAINT `fk_id_obavestenje` FOREIGN KEY (`idParent`) REFERENCES `entity` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of notification
-- ----------------------------

-- ----------------------------
-- Table structure for `notificationsettings`
-- ----------------------------
DROP TABLE IF EXISTS `notificationsettings`;
CREATE TABLE `notificationsettings` (
  `user_id` int(11) NOT NULL,
  `FlagUnwanted` int(11) NOT NULL,
  PRIMARY KEY (`user_id`),
  CONSTRAINT `fk_PodešavanjeObaveštenja_Korisnik1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of notificationsettings
-- ----------------------------

-- ----------------------------
-- Table structure for `notificationunwanted`
-- ----------------------------
DROP TABLE IF EXISTS `notificationunwanted`;
CREATE TABLE `notificationunwanted` (
  `user_id` int(11) NOT NULL,
  `idParent` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`idParent`),
  KEY `fk_Obaveštenja_has_PodešavanjeObaveštenja_PodešavanjeOb_idx` (`user_id`),
  KEY `fk_id_idx` (`idParent`),
  CONSTRAINT `fk_id` FOREIGN KEY (`idParent`) REFERENCES `entity` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Obaveštenja_has_PodešavanjeObaveštenja_PodešavanjeObav1` FOREIGN KEY (`user_id`) REFERENCES `notificationsettings` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of notificationunwanted
-- ----------------------------

-- ----------------------------
-- Table structure for `proposal`
-- ----------------------------
DROP TABLE IF EXISTS `proposal`;
CREATE TABLE `proposal` (
  `idProposal` int(10) unsigned NOT NULL,
  `UserProposed` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Description` mediumtext NOT NULL,
  PRIMARY KEY (`idProposal`,`UserProposed`),
  KEY `Predlozio_idx` (`UserProposed`),
  CONSTRAINT `fk_id_predlog` FOREIGN KEY (`idProposal`) REFERENCES `entity` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `Predlozio` FOREIGN KEY (`UserProposed`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of proposal
-- ----------------------------

-- ----------------------------
-- Table structure for `proposalowner`
-- ----------------------------
DROP TABLE IF EXISTS `proposalowner`;
CREATE TABLE `proposalowner` (
  `UserPropose` int(11) NOT NULL,
  `idProposal` int(10) unsigned NOT NULL,
  `UserProposed` int(11) NOT NULL,
  PRIMARY KEY (`UserPropose`,`idProposal`,`UserProposed`),
  KEY `fk_Korisnik_has_Predlog1_Predlog1_idx` (`idProposal`),
  KEY `fk_ProposalOwner_User1_idx` (`UserProposed`),
  CONSTRAINT `fk_Korisnik_has_Predlog1_Korisnik1` FOREIGN KEY (`UserPropose`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Korisnik_has_Predlog1_Predlog1` FOREIGN KEY (`idProposal`) REFERENCES `proposal` (`idProposal`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_ProposalOwner_User1` FOREIGN KEY (`UserProposed`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of proposalowner
-- ----------------------------

-- ----------------------------
-- Table structure for `proposalsupport`
-- ----------------------------
DROP TABLE IF EXISTS `proposalsupport`;
CREATE TABLE `proposalsupport` (
  `user_id` int(11) NOT NULL,
  `idProposal` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`idProposal`),
  UNIQUE KEY `username_UNIQUE` (`user_id`),
  KEY `fk_Korisnik_has_Predlog_Predlog1_idx` (`idProposal`),
  CONSTRAINT `fk_Korisnik_has_Predlog_Korisnik1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Korisnik_has_Predlog_Predlog1` FOREIGN KEY (`idProposal`) REFERENCES `proposal` (`idProposal`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of proposalsupport
-- ----------------------------

-- ----------------------------
-- Table structure for `ranking`
-- ----------------------------
DROP TABLE IF EXISTS `ranking`;
CREATE TABLE `ranking` (
  `VotedFor` int(11) NOT NULL,
  `Grade` int(11) NOT NULL,
  `Vote` int(11) NOT NULL,
  `idEvent` int(10) unsigned NOT NULL,
  `idActivity` int(10) unsigned NOT NULL,
  PRIMARY KEY (`VotedFor`,`Vote`,`idEvent`,`idActivity`),
  KEY `fk_Glasovi_Predaje1_idx` (`VotedFor`),
  KEY `fk_Glasovi_Prisustvovao1_idx` (`Vote`,`idEvent`,`idActivity`),
  KEY `fk_Glasovi_Prisustvovao1` (`idEvent`),
  CONSTRAINT `fk_Glasovi_Predaje1` FOREIGN KEY (`VotedFor`) REFERENCES `eventholder` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Glasovi_Prisustvovao1` FOREIGN KEY (`idEvent`) REFERENCES `eventattendees` (`idEvent`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ranking
-- ----------------------------

-- ----------------------------
-- Table structure for `user`
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
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
  `www` varchar(20) DEFAULT NULL,
  `Proffession` varchar(45) DEFAULT NULL,
  `School` varchar(45) DEFAULT NULL,
  `ProfilePicture` varchar(45) DEFAULT NULL,
  `salt` varchar(5) DEFAULT NULL,
  `hash_time` int(10) unsigned NOT NULL,
  `hash` varchar(32) NOT NULL,
  `status` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username_UNIQUE` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('1', 'ddasdas', '11bd636e890bdc2f257f782b72f19b05', '1', null, null, '2danijel84@gmail.com', '0', 'Danijel', 'Marjanovic', 'www.mojsajt.com', 'student', 'matematicki fakultet', null, '8r673', '0', '', '0');
INSERT INTO `user` VALUES ('2', 'darktrgdsa', '549f798441af6d9362a8dac7940aa55a', '1', null, null, '3danijel84@gmail.com', '0', 'Danijel', 'Marjanovic', 'dadasda.com', 'dasdasd', 'dasdas', null, '0u7VY', '1390734284', 'e2b85aee54d81f011d2216051f5a08b8', '0');
INSERT INTO `user` VALUES ('3', 'darktrgedas', 'f331cd7818f5109b010dec2c11f7d7c9', '1', null, null, '3danijel84@gmail.com', '0', 'Danijel', 'Marjanovic', '', '', '', null, 'H0mqm', '1390734363', 'c6c8cb050ca7536218e19301daeeddae', '0');
INSERT INTO `user` VALUES ('4', 'darktrge', 'd9be0a7cfddd42df37dd62f04e0f7fb1', '1', null, null, 'danijel84@gmail.com', '0', 'Danijel', 'Marjanovic', 'dsadas', 'adsada', 'dasdasdsa', null, 'dvW04', '1390734637', '02177bc627fb3c260192b8ea55e3f7d9', '1');

-- ----------------------------
-- Procedure structure for `addActivity`
-- ----------------------------
DROP PROCEDURE IF EXISTS `addActivity`;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `addActivity`(ID INT, NAZIV VARCHAR(50), OPIS MEDIUMTEXT, POCETAK DATE,  COVER VARCHAR(100), active int)
BEGIN
SET SQL_SAFE_UPDATES=0;
SELECT @myLeft := lft FROM Activity
WHERE idActivity = ID;

UPDATE Activity SET rgt = rgt + 2 WHERE rgt > @myLeft;
UPDATE Activity SET lft = lft + 2 WHERE lft > @myLeft;

call addActivityRaw(NAZIV, OPIS, POCETAK, COVER, active, @myLeft + 1, @myLeft + 2);
SET SQL_SAFE_UPDATES=1;
END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for `addActivityRaw`
-- ----------------------------
DROP PROCEDURE IF EXISTS `addActivityRaw`;
DELIMITER ;;
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
END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for `addEvent`
-- ----------------------------
DROP PROCEDURE IF EXISTS `addEvent`;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `addEvent`(idKurs INT, Naziv VARCHAR(50), OPIS MEDIUMTEXT, Mesto VARCHAR(50), Datum date, Vreme TIME)
BEGIN
	DECLARE id INT default -56;
	INSERT INTO Entity(tipEntiteta) values('Event');
	SET id = last_insert_id();
	INSERT INTO Event(idEvent,Name, Description, Venue, Date, Time) values (id,Naziv, OPIS, Mesto, Datum, Vreme);
	INSERT INTO ActivityContains(idEvent, idActivity) values (id, idKurs);
END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for `addMaterial`
-- ----------------------------
DROP PROCEDURE IF EXISTS `addMaterial`;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `addMaterial`(user VARCHAR(20), idPredavanja INT, Naziv VARCHAR(50), Link VARCHAR(100), Tip VARCHAR(50),Datum TIMESTAMP )
BEGIN
	DECLARE id INT default -56;
	INSERT INTO Entity(tipEntiteta) values('Material');
	SET id = last_insert_id();
	INSERT INTO Material(idMaterial, Name, URI, Type, Date, OwnerID) values (id, Naziv, Link, Tip, NOW(), user);
	INSERT INTO EventContains(idEvent, idMaterial) values (idPredavanja, id);
END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for `addProposal`
-- ----------------------------
DROP PROCEDURE IF EXISTS `addProposal`;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `addProposal`(Predlozio int, Naziv VARCHAR(50), Opis MEDIUMTEXT )
BEGIN
	DECLARE id INT default -56;	
	INSERT INTO Entity(tipEntiteta) values('Proposal');
	SET id = last_insert_id();
	INSERT INTO Proposal(idProposal, UserProposed, Name, Description) values (id, Predlozio, Naziv, Opis);
	INSERT INTO ProposalSupport(user_id, idProposal) values (Predlozio, id);
END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for `deleteActivity`
-- ----------------------------
DROP PROCEDURE IF EXISTS `deleteActivity`;
DELIMITER ;;
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
END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for `fullTreeActivity`
-- ----------------------------
DROP PROCEDURE IF EXISTS `fullTreeActivity`;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `fullTreeActivity`()
BEGIN

SELECT node.name
FROM Activity AS node, Activity AS parent
WHERE node.lft BETWEEN parent.lft AND parent.rgt
        AND parent.idActivity = 1 AND node.Active = 1
ORDER BY node.lft;

END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for `getActivityEvents`
-- ----------------------------
DROP PROCEDURE IF EXISTS `getActivityEvents`;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `getActivityEvents`(ID INT)
BEGIN
	SELECT a.idEvent, Name, idActivity
	FROM ActivityContains a JOIN Event e on a.idEvent = e.idEvent
	WHERE idActivity = ID;
END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for `moveActivity`
-- ----------------------------
DROP PROCEDURE IF EXISTS `moveActivity`;
DELIMITER ;;
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
END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for `pathToActivity`
-- ----------------------------
DROP PROCEDURE IF EXISTS `pathToActivity`;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pathToActivity`(id INT)
BEGIN
	SELECT parent.idActivity as idActivity, parent.Name as Name
	FROM Activity AS node,
        Activity AS parent
	WHERE node.lft BETWEEN parent.lft AND parent.rgt
        AND node.idActivity = id
	ORDER BY node.lft;
END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for `sonOfActivity`
-- ----------------------------
DROP PROCEDURE IF EXISTS `sonOfActivity`;
DELIMITER ;;
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
END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for `treeFormated`
-- ----------------------------
DROP PROCEDURE IF EXISTS `treeFormated`;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `treeFormated`()
BEGIN
SELECT CONCAT( REPEAT( '| ', (COUNT(parent.Name) - 1) ), node.Name) AS name
	FROM Activity AS node,
        Activity AS parent
	WHERE node.lft BETWEEN parent.lft AND parent.rgt
	GROUP BY node.Name
	ORDER BY node.lft;
END
;;
DELIMITER ;
