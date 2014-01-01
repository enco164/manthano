SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

DROP SCHEMA IF EXISTS `ManthanoDB` ;
CREATE SCHEMA IF NOT EXISTS `ManthanoDB` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `ManthanoDB` ;

-- -----------------------------------------------------
-- Table `ManthanoDB`.`User`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ManthanoDB`.`User` (
  `user_id` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(20) UNICODE NOT NULL,
  `password` VARCHAR(64) NOT NULL,
  `acc_type` INT NOT NULL,
  `Usercol` VARCHAR(45) NULL,
  `Usercol1` VARCHAR(45) NULL,
  `mail` VARCHAR(40) NOT NULL,
  `Ext_login` TINYINT(1) NOT NULL,
  `Name` VARCHAR(15) NOT NULL,
  `Surname` VARCHAR(30) NOT NULL,
  `www` VARCHAR(20) NULL,
  `Proffession` VARCHAR(45) NULL,
  `School` VARCHAR(45) NULL,
  `ProfilePicture` VARCHAR(45) NULL,
  `salt` VARCHAR(5) NULL,
  `status` INT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE INDEX `username_UNIQUE` (`username` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ManthanoDB`.`Entity`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ManthanoDB`.`Entity` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tipEntiteta` VARCHAR(10) UNICODE NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ManthanoDB`.`Activity`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ManthanoDB`.`Activity` (
  `idActivity` INT UNSIGNED NOT NULL,
  `Name` VARCHAR(50) NOT NULL,
  `Description` MEDIUMTEXT NOT NULL,
  `BeginDate` DATE NOT NULL,
  `CoverPicture` VARCHAR(100) NOT NULL,
  `lft` INT NOT NULL,
  `rgt` INT NOT NULL,
  `Active` TINYINT(1) NOT NULL DEFAULT true,
  PRIMARY KEY (`idActivity`),
  CONSTRAINT `fk_id_kurs`
    FOREIGN KEY (`idActivity`)
    REFERENCES `ManthanoDB`.`Entity` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ManthanoDB`.`ActivityParticipant`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ManthanoDB`.`ActivityParticipant` (
  `user_id` INT NOT NULL,
  `idActivity` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`user_id`, `idActivity`),
  INDEX `fk_Korisnik_has_Kurs_Kurs1_idx` (`idActivity` ASC),
  CONSTRAINT `fk_Korisnik_has_Kurs_Korisnik1`
    FOREIGN KEY (`user_id`)
    REFERENCES `ManthanoDB`.`User` (`user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Korisnik_has_Kurs_Kurs1`
    FOREIGN KEY (`idActivity`)
    REFERENCES `ManthanoDB`.`Activity` (`idActivity`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ManthanoDB`.`ActivityHolder`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ManthanoDB`.`ActivityHolder` (
  `user_id` INT NOT NULL,
  `idActivity` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`user_id`, `idActivity`),
  INDEX `fk_Korisnik_has_Kurs1_Kurs1_idx` (`idActivity` ASC),
  CONSTRAINT `fk_Korisnik_has_Kurs1_Korisnik1`
    FOREIGN KEY (`user_id`)
    REFERENCES `ManthanoDB`.`User` (`user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Korisnik_has_Kurs1_Kurs1`
    FOREIGN KEY (`idActivity`)
    REFERENCES `ManthanoDB`.`Activity` (`idActivity`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ManthanoDB`.`Proposal`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ManthanoDB`.`Proposal` (
  `idProposal` INT UNSIGNED NOT NULL,
  `UserProposed` INT NOT NULL,
  `Name` VARCHAR(50) NOT NULL,
  `Description` MEDIUMTEXT NOT NULL,
  PRIMARY KEY (`idProposal`, `UserProposed`),
  INDEX `Predlozio_idx` (`UserProposed` ASC),
  CONSTRAINT `Predlozio`
    FOREIGN KEY (`UserProposed`)
    REFERENCES `ManthanoDB`.`User` (`user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_id_predlog`
    FOREIGN KEY (`idProposal`)
    REFERENCES `ManthanoDB`.`Entity` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ManthanoDB`.`ProposalSupport`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ManthanoDB`.`ProposalSupport` (
  `user_id` INT NOT NULL,
  `idProposal` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`user_id`, `idProposal`),
  INDEX `fk_Korisnik_has_Predlog_Predlog1_idx` (`idProposal` ASC),
  UNIQUE INDEX `username_UNIQUE` (`user_id` ASC),
  CONSTRAINT `fk_Korisnik_has_Predlog_Korisnik1`
    FOREIGN KEY (`user_id`)
    REFERENCES `ManthanoDB`.`User` (`user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Korisnik_has_Predlog_Predlog1`
    FOREIGN KEY (`idProposal`)
    REFERENCES `ManthanoDB`.`Proposal` (`idProposal`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ManthanoDB`.`ProposalOwner`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ManthanoDB`.`ProposalOwner` (
  `UserPropose` INT NOT NULL,
  `idProposal` INT UNSIGNED NOT NULL,
  `UserProposed` INT NOT NULL,
  PRIMARY KEY (`UserPropose`, `idProposal`, `UserProposed`),
  INDEX `fk_Korisnik_has_Predlog1_Predlog1_idx` (`idProposal` ASC),
  INDEX `fk_ProposalOwner_User1_idx` (`UserProposed` ASC),
  CONSTRAINT `fk_Korisnik_has_Predlog1_Korisnik1`
    FOREIGN KEY (`UserPropose`)
    REFERENCES `ManthanoDB`.`User` (`user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Korisnik_has_Predlog1_Predlog1`
    FOREIGN KEY (`idProposal`)
    REFERENCES `ManthanoDB`.`Proposal` (`idProposal`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ProposalOwner_User1`
    FOREIGN KEY (`UserProposed`)
    REFERENCES `ManthanoDB`.`User` (`user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ManthanoDB`.`Event`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ManthanoDB`.`Event` (
  `idEvent` INT UNSIGNED NOT NULL,
  `Name` VARCHAR(50) NOT NULL,
  `Description` MEDIUMTEXT NOT NULL,
  `Venue` VARCHAR(50) NOT NULL,
  `Date` DATE NOT NULL,
  `Time` TIME NOT NULL,
  PRIMARY KEY (`idEvent`),
  CONSTRAINT `fk_id_predavanje`
    FOREIGN KEY (`idEvent`)
    REFERENCES `ManthanoDB`.`Entity` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ManthanoDB`.`EventHolder`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ManthanoDB`.`EventHolder` (
  `user_id` INT NOT NULL,
  `idEvent` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`user_id`, `idEvent`),
  INDEX `fk_Korisnik_has_Predavanje_Predavanje1_idx` (`idEvent` ASC),
  CONSTRAINT `fk_Korisnik_has_Predavanje_Korisnik1`
    FOREIGN KEY (`user_id`)
    REFERENCES `ManthanoDB`.`User` (`user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Korisnik_has_Predavanje_Predavanje1`
    FOREIGN KEY (`idEvent`)
    REFERENCES `ManthanoDB`.`Event` (`idEvent`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ManthanoDB`.`Material`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ManthanoDB`.`Material` (
  `idMaterial` INT UNSIGNED NOT NULL,
  `Name` VARCHAR(50) NOT NULL,
  `URI` VARCHAR(100) NOT NULL,
  `Type` VARCHAR(50) NOT NULL,
  `Date` TIMESTAMP NOT NULL,
  `OwnerID` INT NOT NULL,
  PRIMARY KEY (`idMaterial`),
  INDEX `fk_ownerid_idx` (`OwnerID` ASC),
  CONSTRAINT `fk_id_materijal`
    FOREIGN KEY (`idMaterial`)
    REFERENCES `ManthanoDB`.`Entity` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ownerid`
    FOREIGN KEY (`OwnerID`)
    REFERENCES `ManthanoDB`.`User` (`user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ManthanoDB`.`NotificationSettings`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ManthanoDB`.`NotificationSettings` (
  `user_id` INT NOT NULL,
  `FlagUnwanted` INT NOT NULL,
  PRIMARY KEY (`user_id`),
  CONSTRAINT `fk_PodešavanjeObaveštenja_Korisnik1`
    FOREIGN KEY (`user_id`)
    REFERENCES `ManthanoDB`.`User` (`user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ManthanoDB`.`Comment`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ManthanoDB`.`Comment` (
  `time` TIMESTAMP NOT NULL,
  `idParent` INT UNSIGNED NOT NULL,
  `user_id` INT NOT NULL,
  `typeParent` VARCHAR(10) UNICODE NOT NULL,
  `Comment` TINYTEXT NOT NULL,
  PRIMARY KEY (`time`, `user_id`, `idParent`),
  INDEX `fk_Komentari_Korisnik1_idx` (`user_id` ASC),
  INDEX `fk_id_komentar_idx` (`idParent` ASC),
  CONSTRAINT `fk_Komentari_Korisnik1`
    FOREIGN KEY (`user_id`)
    REFERENCES `ManthanoDB`.`User` (`user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_id_komentar`
    FOREIGN KEY (`idParent`)
    REFERENCES `ManthanoDB`.`Entity` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ManthanoDB`.`Notification`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ManthanoDB`.`Notification` (
  `time` TIMESTAMP NOT NULL,
  `Flag` BINARY(3) NOT NULL,
  `idParent` INT UNSIGNED NOT NULL,
  `typeParent` VARCHAR(10) UNICODE NOT NULL,
  PRIMARY KEY (`time`, `idParent`),
  INDEX `id_idx` (`idParent` ASC),
  CONSTRAINT `fk_id_obavestenje`
    FOREIGN KEY (`idParent`)
    REFERENCES `ManthanoDB`.`Entity` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ManthanoDB`.`NotificationUnwanted`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ManthanoDB`.`NotificationUnwanted` (
  `user_id` INT NOT NULL,
  `idParent` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`user_id`, `idParent`),
  INDEX `fk_Obaveštenja_has_PodešavanjeObaveštenja_PodešavanjeOb_idx` (`user_id` ASC),
  INDEX `fk_id_idx` (`idParent` ASC),
  CONSTRAINT `fk_Obaveštenja_has_PodešavanjeObaveštenja_PodešavanjeObav1`
    FOREIGN KEY (`user_id`)
    REFERENCES `ManthanoDB`.`NotificationSettings` (`user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_id`
    FOREIGN KEY (`idParent`)
    REFERENCES `ManthanoDB`.`Entity` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ManthanoDB`.`EventAttendees`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ManthanoDB`.`EventAttendees` (
  `username` INT NOT NULL,
  `idEvent` INT UNSIGNED NOT NULL,
  `idActivity` INT UNSIGNED NOT NULL,
  `Was` TINYINT(1) NOT NULL,
  PRIMARY KEY (`username`, `idEvent`, `idActivity`),
  INDEX `fk_Korisnik_has_Predavanje_Predavanje2_idx` (`idEvent` ASC),
  INDEX `fk_EventAttendees_ActivityParticipant1_idx` (`username` ASC, `idActivity` ASC),
  CONSTRAINT `fk_Korisnik_has_Predavanje_Predavanje2`
    FOREIGN KEY (`idEvent`)
    REFERENCES `ManthanoDB`.`Event` (`idEvent`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_EventAttendees_ActivityParticipant1`
    FOREIGN KEY (`username` , `idActivity`)
    REFERENCES `ManthanoDB`.`ActivityParticipant` (`user_id` , `idActivity`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ManthanoDB`.`Ranking`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ManthanoDB`.`Ranking` (
  `VotedFor` INT NOT NULL,
  `Grade` INT NOT NULL,
  `Vote` INT NOT NULL,
  `idEvent` INT UNSIGNED NOT NULL,
  `idActivity` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`VotedFor`, `Vote`, `idEvent`, `idActivity`),
  INDEX `fk_Glasovi_Predaje1_idx` (`VotedFor` ASC),
  INDEX `fk_Glasovi_Prisustvovao1_idx` (`Vote` ASC, `idEvent` ASC, `idActivity` ASC),
  CONSTRAINT `fk_Glasovi_Predaje1`
    FOREIGN KEY (`VotedFor`)
    REFERENCES `ManthanoDB`.`EventHolder` (`user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Glasovi_Prisustvovao1`
    FOREIGN KEY (`idEvent`)
    REFERENCES `ManthanoDB`.`EventAttendees` (`idEvent`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ManthanoDB`.`ActivityArchive`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ManthanoDB`.`ActivityArchive` (
  `idActivity` INT UNSIGNED NOT NULL,
  `Name` VARCHAR(50) NOT NULL,
  `Description` MEDIUMTEXT NOT NULL,
  `BeginDate` DATE NOT NULL,
  `coverPicture` INT NOT NULL,
  PRIMARY KEY (`idActivity`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ManthanoDB`.`ActivityHolderArchive`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ManthanoDB`.`ActivityHolderArchive` (
  `user_id` INT NOT NULL,
  `idActivity` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`user_id`, `idActivity`),
  INDEX `fk_Korisnik_has_Kurs1_Kurs1_idx` (`idActivity` ASC),
  CONSTRAINT `fk_Korisnik_has_Kurs1_Kurs10`
    FOREIGN KEY (`idActivity`)
    REFERENCES `ManthanoDB`.`ActivityArchive` (`idActivity`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ManthanoDB`.`EventArchive`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ManthanoDB`.`EventArchive` (
  `idEvent` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `Name` VARCHAR(50) NOT NULL,
  `Description` MEDIUMTEXT NOT NULL,
  `Venue` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`idEvent`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ManthanoDB`.`EventHolderArchive`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ManthanoDB`.`EventHolderArchive` (
  `user_id` INT NOT NULL,
  `idEvent` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`user_id`, `idEvent`),
  INDEX `fk_Korisnik_has_Predavanje_Predavanje1_idx` (`idEvent` ASC),
  CONSTRAINT `fk_Korisnik_has_Predavanje_Predavanje10`
    FOREIGN KEY (`idEvent`)
    REFERENCES `ManthanoDB`.`EventArchive` (`idEvent`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ManthanoDB`.`MaterialArchive`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ManthanoDB`.`MaterialArchive` (
  `idMaterial` INT UNSIGNED NOT NULL,
  `Name` VARCHAR(50) NOT NULL,
  `Link` VARCHAR(100) NOT NULL,
  `Type` VARCHAR(50) NOT NULL,
  `OwnerID` VARCHAR(20) UNICODE NOT NULL,
  `Date` TIMESTAMP NOT NULL,
  PRIMARY KEY (`idMaterial`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ManthanoDB`.`ActivityContains`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ManthanoDB`.`ActivityContains` (
  `idEvent` INT UNSIGNED NOT NULL,
  `idActivity` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`idEvent`, `idActivity`),
  INDEX `fk_Predavanje_has_Kurs_Kurs1_idx` (`idActivity` ASC),
  INDEX `fk_Predavanje_has_Kurs_Predavanje1_idx` (`idEvent` ASC),
  CONSTRAINT `fk_Predavanje_has_Kurs_Predavanje1`
    FOREIGN KEY (`idEvent`)
    REFERENCES `ManthanoDB`.`Event` (`idEvent`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Predavanje_has_Kurs_Kurs1`
    FOREIGN KEY (`idActivity`)
    REFERENCES `ManthanoDB`.`Activity` (`idActivity`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ManthanoDB`.`EventContains`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ManthanoDB`.`EventContains` (
  `idEvent` INT UNSIGNED NOT NULL,
  `idMaterial` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`idEvent`, `idMaterial`),
  INDEX `fk_Predavanje_has_Materijal_Materijal1_idx` (`idMaterial` ASC),
  INDEX `fk_Predavanje_has_Materijal_Predavanje1_idx` (`idEvent` ASC),
  CONSTRAINT `fk_Predavanje_has_Materijal_Predavanje1`
    FOREIGN KEY (`idEvent`)
    REFERENCES `ManthanoDB`.`Event` (`idEvent`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Predavanje_has_Materijal_Materijal1`
    FOREIGN KEY (`idMaterial`)
    REFERENCES `ManthanoDB`.`Material` (`idMaterial`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ManthanoDB`.`ActivityContainsArchive`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ManthanoDB`.`ActivityContainsArchive` (
  `idActivity` INT UNSIGNED NOT NULL,
  `Archived` TINYINT(1) NOT NULL,
  `idEvent` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`idActivity`),
  INDEX `fk_KursArhiva_has_PredavanjeArhiva_KursArhiva1_idx` (`idActivity` ASC),
  CONSTRAINT `fk_KursArhiva_has_PredavanjeArhiva_KursArhiva1`
    FOREIGN KEY (`idActivity`)
    REFERENCES `ManthanoDB`.`ActivityArchive` (`idActivity`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ManthanoDB`.`EventContainsArchive`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ManthanoDB`.`EventContainsArchive` (
  `idMaterial` INT UNSIGNED NOT NULL,
  `idEvent` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`idMaterial`, `idEvent`),
  INDEX `fk_MaterijalArhiva_has_PredavanjeArhiva_PredavanjeArhiva1_idx` (`idEvent` ASC),
  INDEX `fk_MaterijalArhiva_has_PredavanjeArhiva_MaterijalArhiva1_idx` (`idMaterial` ASC),
  CONSTRAINT `fk_MaterijalArhiva_has_PredavanjeArhiva_MaterijalArhiva1`
    FOREIGN KEY (`idMaterial`)
    REFERENCES `ManthanoDB`.`MaterialArchive` (`idMaterial`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_MaterijalArhiva_has_PredavanjeArhiva_PredavanjeArhiva1`
    FOREIGN KEY (`idEvent`)
    REFERENCES `ManthanoDB`.`EventArchive` (`idEvent`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

USE `ManthanoDB` ;

-- -----------------------------------------------------
-- procedure addActivityRaw
-- -----------------------------------------------------

DELIMITER $$
USE `ManthanoDB`$$
CREATE PROCEDURE `addActivityRaw` (NAZIV VARCHAR(50), OPIS MEDIUMTEXT, POCETAK DATE,  COVER VARCHAR(100), ACTIVE INT, LFT INT, RGT INT )
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

DELIMITER ;

-- -----------------------------------------------------
-- procedure addEvent
-- -----------------------------------------------------

DELIMITER $$
USE `ManthanoDB`$$
CREATE PROCEDURE `addEvent` (idKurs INT, Naziv VARCHAR(50), OPIS MEDIUMTEXT, Mesto VARCHAR(50), Datum date, Vreme TIME)
BEGIN
	DECLARE id INT default -56;
	INSERT INTO Entity(tipEntiteta) values('Event');
	SET id = last_insert_id();
	INSERT INTO Event(idEvent,Name, Description, Venue, Date, Time) values (id,Naziv, OPIS, Mesto, Datum, Vreme);
	INSERT INTO ActivityContains(idEvent, idActivity) values (id, idKurs);
END$$

DELIMITER ;

-- -----------------------------------------------------
-- procedure addProposal
-- -----------------------------------------------------

DELIMITER $$
USE `ManthanoDB`$$
CREATE PROCEDURE `addProposal` (Predlozio int, Naziv VARCHAR(50), Opis MEDIUMTEXT )
BEGIN
	DECLARE id INT default -56;	
	INSERT INTO Entity(tipEntiteta) values('Proposal');
	SET id = last_insert_id();
	INSERT INTO Proposal(idProposal, UserProposed, Name, Description) values (id, Predlozio, Naziv, Opis);
	INSERT INTO ProposalSupport(user_id, idProposal) values (Predlozio, id);
END$$

DELIMITER ;

-- -----------------------------------------------------
-- procedure addMaterial
-- -----------------------------------------------------

DELIMITER $$
USE `ManthanoDB`$$
CREATE PROCEDURE `addMaterial` (user VARCHAR(20), idPredavanja INT, Naziv VARCHAR(50), Link VARCHAR(100), Tip VARCHAR(50),Datum TIMESTAMP )
BEGIN
	DECLARE id INT default -56;
	INSERT INTO Entity(tipEntiteta) values('Material');
	SET id = last_insert_id();
	INSERT INTO Material(idMaterial, Name, URI, Type, Date, OwnerID) values (id, Naziv, Link, Tip, NOW(), user);
	INSERT INTO EventContains(idEvent, idMaterial) values (idPredavanja, id);
END$$

DELIMITER ;

-- -----------------------------------------------------
-- procedure pathToActivity
-- -----------------------------------------------------

DELIMITER $$
USE `ManthanoDB`$$
CREATE PROCEDURE `pathToActivity` (id INT)
BEGIN
	SELECT parent.idActivity as idActivity, parent.Name as Name
	FROM Activity AS node,
        Activity AS parent
	WHERE node.lft BETWEEN parent.lft AND parent.rgt
        AND node.idActivity = id
	ORDER BY node.lft;
END$$

DELIMITER ;

-- -----------------------------------------------------
-- procedure fullTreeActivity
-- -----------------------------------------------------

DELIMITER $$
USE `ManthanoDB`$$
CREATE PROCEDURE `fullTreeActivity` ()
BEGIN

SELECT node.name
FROM Activity AS node, Activity AS parent
WHERE node.lft BETWEEN parent.lft AND parent.rgt
        AND parent.idActivity = 1 AND node.Active = 1
ORDER BY node.lft;

END$$

DELIMITER ;

-- -----------------------------------------------------
-- procedure sonOfActivity
-- -----------------------------------------------------

DELIMITER $$
USE `ManthanoDB`$$
CREATE PROCEDURE `sonOfActivity` (id INT)
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

DELIMITER ;

-- -----------------------------------------------------
-- procedure moveActivity
-- -----------------------------------------------------

DELIMITER $$
USE `ManthanoDB`$$
CREATE PROCEDURE `moveActivity` (FROMK INT, TOK INT)
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

DELIMITER ;

-- -----------------------------------------------------
-- procedure treeFormated
-- -----------------------------------------------------

DELIMITER $$
USE `ManthanoDB`$$
CREATE PROCEDURE `treeFormated` ()
BEGIN
SELECT CONCAT( REPEAT( '| ', (COUNT(parent.Name) - 1) ), node.Name) AS name
	FROM Activity AS node,
        Activity AS parent
	WHERE node.lft BETWEEN parent.lft AND parent.rgt
	GROUP BY node.Name
	ORDER BY node.lft;
END$$

DELIMITER ;

-- -----------------------------------------------------
-- procedure deleteActivity
-- -----------------------------------------------------

DELIMITER $$
USE `ManthanoDB`$$
CREATE PROCEDURE `deleteActivity` (ID INT)
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

DELIMITER ;

-- -----------------------------------------------------
-- procedure addActivity
-- -----------------------------------------------------

DELIMITER $$
USE `ManthanoDB`$$
CREATE PROCEDURE `addActivity`(ID INT, NAZIV VARCHAR(50), OPIS MEDIUMTEXT, POCETAK DATE,  COVER VARCHAR(100), active int)
BEGIN
SET SQL_SAFE_UPDATES=0;
SELECT @myLeft := lft FROM Activity
WHERE idActivity = ID;

UPDATE Activity SET rgt = rgt + 2 WHERE rgt > @myLeft;
UPDATE Activity SET lft = lft + 2 WHERE lft > @myLeft;

call addActivityRaw(NAZIV, OPIS, POCETAK, COVER, active, @myLeft + 1, @myLeft + 2);
SET SQL_SAFE_UPDATES=1;
END$$

DELIMITER ;

-- -----------------------------------------------------
-- procedure getActivityEvents
-- -----------------------------------------------------

DELIMITER $$
USE `ManthanoDB`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `getActivityEvents`(ID INT)
BEGIN
	SELECT a.idEvent, Name, idActivity
	FROM ActivityContains a JOIN Event e on a.idEvent = e.idEvent
	WHERE idActivity = ID;
END$$

DELIMITER ;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
