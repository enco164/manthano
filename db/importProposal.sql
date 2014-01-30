-- phpMyAdmin SQL Dump
-- version 4.0.9
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 30, 2014 at 03:07 AM
-- Server version: 5.5.34
-- PHP Version: 5.4.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `manthanodb`
--

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
(82, 1, 'Android kurs', 'Ovde mozemo nauciti da pravimo aplikacije za Android telefone. Potrebno je osnovno poznavanje Jave.'),
(83, 1, 'CUDA Masovno paralelno programiranje', 'Mozda bi valjalo da se organizuje drugi deo ovog kursa, jer na prvom delu smo naucili samo osnove.'),
(84, 1, 'Web programiranje', 'Kolege matematičari, da li ste zainteresovani za jedan kratak kurs u kome ćete naučiti kako da napravite web sajt koji će izgledati lepo i biti funkcionalan! HTML, CSS, JavaScript...'),
(85, 1, 'Turnir u bilijaru', 'Mogli bi svake sedmice da organizujemo po par bilijarskih nadmetanja. Poraženi takmičar plaća piće...'),
(86, 1, 'Španski jezik', 'Koleginica i kolega sa Filološkog fakulteta su nam ponudili da održe kurs od 8 dvočasa španskog jezika... Učile bi se osnove jezika... Rok za prijavu je 7 dana...'),
(87, 1, 'Software design pattern', 'Potrebni su svakom ozbiljnijem programeru. Plan je da se za svaki od najviše korišćenih paterna realizuje jedan primer kroz kucanje koda u nekom programskom jeziku... Možda korišćenje C++ ili C#...');

--
-- Triggers `proposal`
--
DROP TRIGGER IF EXISTS `Proposal_BDEL`;
DELIMITER //
CREATE TRIGGER `Proposal_BDEL` BEFORE DELETE ON `proposal`
 FOR EACH ROW begin
	DELETE FROM ProposalOwner WHERE idProposal = old.idProposal;
	DELETE FROM ProposalSupport WHERE idProposal = old.idProposal;
END
//
DELIMITER ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `proposal`
--
ALTER TABLE `proposal`
  ADD CONSTRAINT `fk_id_predlog` FOREIGN KEY (`idProposal`) REFERENCES `entity` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
