-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: 21.12.2019 klo 10:44
-- Palvelimen versio: 5.7.26
-- PHP Version: 7.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tyoajanseuranta`
--
CREATE DATABASE IF NOT EXISTS `tyoajanseuranta` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `tyoajanseuranta`;

-- --------------------------------------------------------

--
-- Rakenne taululle `projekti`
--

DROP TABLE IF EXISTS `projekti`;
CREATE TABLE IF NOT EXISTS `projekti` (
  `Projektin_ID` int(2) NOT NULL AUTO_INCREMENT,
  `Projektin_Nimi` varchar(20) NOT NULL,
  PRIMARY KEY (`Projektin_ID`),
  UNIQUE KEY `Projektin ID` (`Projektin_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Vedos taulusta `projekti`
--

INSERT INTO `projekti` (`Projektin_ID`, `Projektin_Nimi`) VALUES
(1, 'testiprojekti');

-- --------------------------------------------------------

--
-- Rakenne taululle `tyoajanseuranta`
--

DROP TABLE IF EXISTS `tyoajanseuranta`;
CREATE TABLE IF NOT EXISTS `tyoajanseuranta` (
  `Tyoaika_ID` int(2) NOT NULL AUTO_INCREMENT,
  `Projekti_ID` int(2) NOT NULL,
  `Tyontekija_ID` int(2) NOT NULL,
  `Aloitusaika` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Lopetusaika` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Tyoaika_ID`),
  UNIQUE KEY `Tyoaika ID` (`Tyoaika_ID`),
  KEY `Tyontekija ID` (`Tyontekija_ID`),
  KEY `tyoajanseuranta_ibfk_1` (`Projekti_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

--
-- Vedos taulusta `tyoajanseuranta`
--

INSERT INTO `tyoajanseuranta` (`Tyoaika_ID`, `Projekti_ID`, `Tyontekija_ID`, `Aloitusaika`, `Lopetusaika`) VALUES
(1, 1, 1, '2019-12-16 04:00:00', '2019-12-16 14:00:00'),
(2, 1, 1, '2019-12-17 05:00:00', '2019-12-17 13:00:00'),
(5, 1, 2, '2019-12-18 09:22:26', '2019-12-18 09:22:26'),
(8, 1, 2, '2019-12-18 14:10:37', '2019-12-18 14:10:37'),
(9, 1, 2, '2019-12-18 14:14:02', '2019-12-18 14:14:02'),
(10, 1, 2, '2019-12-18 14:14:46', '2019-12-18 14:14:46'),
(11, 1, 2, '2019-12-18 14:22:08', '2019-12-18 14:22:08'),
(12, 1, 2, '2019-12-21 19:07:00', '2019-12-21 21:08:00'),
(13, 1, 1, '2019-12-21 19:07:00', '2019-12-21 21:08:00'),
(14, 1, 1, '2019-12-21 19:07:00', '2019-12-21 21:08:00');

-- --------------------------------------------------------

--
-- Rakenne taululle `tyontekija`
--

DROP TABLE IF EXISTS `tyontekija`;
CREATE TABLE IF NOT EXISTS `tyontekija` (
  `Tyontekija_ID` int(2) NOT NULL AUTO_INCREMENT,
  `Etunimi` varchar(15) NOT NULL,
  `Sukunimi` varchar(30) NOT NULL,
  `Sahkoposti` varchar(50) NOT NULL,
  PRIMARY KEY (`Tyontekija_ID`),
  UNIQUE KEY `Tyontekija ID` (`Tyontekija_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Vedos taulusta `tyontekija`
--

INSERT INTO `tyontekija` (`Tyontekija_ID`, `Etunimi`, `Sukunimi`, `Sahkoposti`) VALUES
(1, 'teppo', 'testaaja', 'tepi@hotmail.fi'),
(2, 'tommi', 'testaaja', 'testaaja@whitehouse.com');

--
-- Rajoitteet vedostauluille
--

--
-- Rajoitteet taululle `tyoajanseuranta`
--
ALTER TABLE `tyoajanseuranta`
  ADD CONSTRAINT `tyoajanseuranta_ibfk_1` FOREIGN KEY (`Projekti_ID`) REFERENCES `projekti` (`Projektin_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tyoajanseuranta_ibfk_2` FOREIGN KEY (`Tyontekija_ID`) REFERENCES `tyontekija` (`Tyontekija_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
