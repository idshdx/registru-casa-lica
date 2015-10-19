-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 19, 2015 at 04:23 PM
-- Server version: 5.5.44-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `registru_casa_lica`
--

-- --------------------------------------------------------

--
-- Table structure for table `Furnizori`
--

CREATE TABLE IF NOT EXISTS `Furnizori` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Tip` int(11) NOT NULL,
  `Nume` varchar(50) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `Tip` (`Tip`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Retinem numele furnizorilor pentru autocompletarea lor' AUTO_INCREMENT=14 ;

--
-- Dumping data for table `Furnizori`
--

INSERT INTO `Furnizori` (`ID`, `Tip`, `Nume`) VALUES
(1, 1, 'Furnizor test1'),
(2, 2, 'Furnizor test2'),
(3, 2, 'FurnizorNEW'),
(5, 2, 'FurnizorNEW2'),
(6, 3, 'MI A MANCAT ZILELE'),
(7, 2, 'EROARE'),
(10, 2, 'test'),
(11, 2, 'TESTTT'),
(12, 2, 'Io'),
(13, 3, 'Mi-a mâncat clătitele');

-- --------------------------------------------------------

--
-- Table structure for table `Luni`
--

CREATE TABLE IF NOT EXISTS `Luni` (
  `IDZi` int(11) NOT NULL AUTO_INCREMENT,
  `SoldInitial` decimal(10,2) NOT NULL,
  PRIMARY KEY (`IDZi`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `Luni`
--

INSERT INTO `Luni` (`IDZi`, `SoldInitial`) VALUES
(2, 100.00);

-- --------------------------------------------------------

--
-- Table structure for table `SumeAport`
--

CREATE TABLE IF NOT EXISTS `SumeAport` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `IDZi` int(11) NOT NULL,
  `Suma` decimal(10,2) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `idxSumeAport_IDZi` (`IDZi`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `SumeAport`
--

INSERT INTO `SumeAport` (`ID`, `IDZi`, `Suma`) VALUES
(1, 5, 3000.00);

-- --------------------------------------------------------

--
-- Table structure for table `SumeCheltuieli`
--

CREATE TABLE IF NOT EXISTS `SumeCheltuieli` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `IDZi` int(11) NOT NULL,
  `Suma` decimal(10,2) NOT NULL,
  `IDFurnizor` int(11) NOT NULL,
  `Factura` varchar(50) DEFAULT NULL,
  `Chitanta` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `idxSumeAport_IDZi` (`IDZi`),
  KEY `IDFurnizor` (`IDFurnizor`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `SumeCheltuieli`
--

INSERT INTO `SumeCheltuieli` (`ID`, `IDZi`, `Suma`, `IDFurnizor`, `Factura`, `Chitanta`) VALUES
(1, 1, 300.00, 1, 'FacturaEdited', 'testchitanta1'),
(2, 1, 100.99, 1, NULL, 'testchitanta2'),
(5, 5, 300.00, 6, 'FacturaEdited', NULL),
(6, 3, 300.00, 1, 'FacturaEdited', NULL),
(7, 1, 300.00, 1, 'FacturaEdited', NULL),
(8, 5, 300.00, 1, 'FacturaEdited', NULL),
(10, 5, 300.00, 1, 'FacturaNEW', NULL),
(11, 5, 90.00, 5, 'TestNou', 'TestNouChitanta');

-- --------------------------------------------------------

--
-- Table structure for table `SumeMarfaTVA9`
--

CREATE TABLE IF NOT EXISTS `SumeMarfaTVA9` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `IDZi` int(11) NOT NULL,
  `Suma` decimal(10,2) NOT NULL,
  `IDFurnizor` int(11) NOT NULL,
  `Factura` varchar(50) DEFAULT NULL,
  `Chitanta` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `idxSumeAport_IDZi` (`IDZi`),
  KEY `IDFurnizor` (`IDFurnizor`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `SumeMarfaTVA9`
--

INSERT INTO `SumeMarfaTVA9` (`ID`, `IDZi`, `Suma`, `IDFurnizor`, `Factura`, `Chitanta`) VALUES
(1, 5, 250.00, 2, 'TestFacturaMarfa', 'ChitantaMarfa'),
(11, 1, 2.00, 7, 'a', 'sad'),
(12, 5, 1.00, 7, 'x', 'y'),
(13, 5, 41.50, 12, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `SumeMarfaTVA24`
--

CREATE TABLE IF NOT EXISTS `SumeMarfaTVA24` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `IDZi` int(11) NOT NULL,
  `Suma` decimal(10,2) NOT NULL,
  `IDFurnizor` int(11) NOT NULL,
  `Factura` varchar(50) DEFAULT NULL,
  `Chitanta` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `idxSumeAport_IDZi` (`IDZi`),
  KEY `IDFurnizor` (`IDFurnizor`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `SumeMarfaTVA24`
--

INSERT INTO `SumeMarfaTVA24` (`ID`, `IDZi`, `Suma`, `IDFurnizor`, `Factura`, `Chitanta`) VALUES
(1, 5, 250.00, 6, 'TestFacturaMarfa', 'ChitantaMarfa'),
(2, 1, 150.00, 1, 'FacturaMarfa2', 'ChiantaMarfa2'),
(3, 5, 24.40, 13, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `TipFurnizor`
--

CREATE TABLE IF NOT EXISTS `TipFurnizor` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Tip` varchar(50) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `TipFurnizor`
--

INSERT INTO `TipFurnizor` (`ID`, `Tip`) VALUES
(1, 'Cheltuieli'),
(2, 'MarfaTVA9'),
(3, 'MarfaTVA24');

-- --------------------------------------------------------

--
-- Table structure for table `Utilizatori`
--

CREATE TABLE IF NOT EXISTS `Utilizatori` (
  `Nume` varchar(50) NOT NULL,
  `Parola` varchar(50) NOT NULL,
  PRIMARY KEY (`Nume`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Utilizatori`
--

INSERT INTO `Utilizatori` (`Nume`, `Parola`) VALUES
('admin', 'admin'),
('user', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `Zile`
--

CREATE TABLE IF NOT EXISTS `Zile` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Data` date NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='IDul zilelor va fi folosit in tabelele cu sume' AUTO_INCREMENT=6 ;

--
-- Dumping data for table `Zile`
--

INSERT INTO `Zile` (`ID`, `Data`) VALUES
(1, '2015-09-30'),
(2, '2015-10-01'),
(3, '2015-10-02'),
(4, '2015-10-03'),
(5, '2015-10-04');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Furnizori`
--
ALTER TABLE `Furnizori`
  ADD CONSTRAINT `fkcFurnizori_Tip_TipFurnizorID` FOREIGN KEY (`Tip`) REFERENCES `TipFurnizor` (`ID`);

--
-- Constraints for table `Luni`
--
ALTER TABLE `Luni`
  ADD CONSTRAINT `Luni_ibfk_1` FOREIGN KEY (`IDZi`) REFERENCES `Zile` (`ID`);

--
-- Constraints for table `SumeAport`
--
ALTER TABLE `SumeAport`
  ADD CONSTRAINT `fkcSumeAport_IDZi_ZileID` FOREIGN KEY (`IDZi`) REFERENCES `Zile` (`ID`);

--
-- Constraints for table `SumeCheltuieli`
--
ALTER TABLE `SumeCheltuieli`
  ADD CONSTRAINT `fkcSumeCheltuieli_IDFurnizor_FurnizoriID` FOREIGN KEY (`IDFurnizor`) REFERENCES `Furnizori` (`ID`),
  ADD CONSTRAINT `fkcSumeCheltuieli_IDZi_ZileID` FOREIGN KEY (`IDZi`) REFERENCES `Zile` (`ID`);

--
-- Constraints for table `SumeMarfaTVA9`
--
ALTER TABLE `SumeMarfaTVA9`
  ADD CONSTRAINT `fkcSumeMarfa_IDFurnizor_FurnizoriID` FOREIGN KEY (`IDFurnizor`) REFERENCES `Furnizori` (`ID`),
  ADD CONSTRAINT `fkcSumeMarfa_IDZi_ZileID` FOREIGN KEY (`IDZi`) REFERENCES `Zile` (`ID`);

--
-- Constraints for table `SumeMarfaTVA24`
--
ALTER TABLE `SumeMarfaTVA24`
  ADD CONSTRAINT `SumeMarfaTVA24_ibfk_1` FOREIGN KEY (`IDZi`) REFERENCES `Zile` (`ID`),
  ADD CONSTRAINT `SumeMarfaTVA24_ibfk_2` FOREIGN KEY (`IDFurnizor`) REFERENCES `Furnizori` (`ID`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
