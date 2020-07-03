-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 07, 2014 at 03:13 PM
-- Server version: 5.6.12-log
-- PHP Version: 5.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `raspberrypints`
--
CREATE DATABASE IF NOT EXISTS `raspberrypints` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `raspberrypints`;

-- --------------------------------------------------------

--
-- Table structure for table `beerStyleGuideLines`
--

CREATE TABLE IF NOT EXISTS `beerStyleGuidelines` (
	`id` int(4) NOT NULL,
	`name` tinytext NOT NULL,
	`createdDate` TIMESTAMP NULL,
	`modifiedDate` TIMESTAMP NULL,

	PRIMARY KEY (`id`)
) ENGINE=InnoDB	DEFAULT CHARSET=latin1;

--
-- clear table `beerStyleGuidelines` and load data from CSV
--
DELETE FROM `beerStyleGuidelines`;

LOAD DATA INFILE './data/beerStyleGuidelines.csv'
INTO TABLE `beerStyleGuidelines`
FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '"'
IGNORE 1 ROWS
(id, name)
SET createdDate = NOW(), modifiedDate = NOW();

--
-- Table structure for table `beerStyles`
--

CREATE TABLE IF NOT EXISTS `beerStyles` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`beerStyleGuidelineId` int(4) NOT NULL,
	`name` tinytext NOT NULL,
	`catNum` tinytext NOT NULL,
	`category` tinytext NOT NULL,
	`ogMin` decimal(4,3) NOT NULL,
	`ogMax` decimal(4,3) NOT NULL,
	`fgMin` decimal(4,3) NOT NULL,
	`fgMax` decimal(4,3) NOT NULL,
	`abvMin` decimal(3,1) NOT NULL,
	`abvMax` decimal(3,1) NOT NULL,
	`ibuMin` decimal(3) NOT NULL,
	`ibuMax` decimal(3) NOT NULL,
	`srmMin` decimal(2) NOT NULL,
	`srmMax` decimal(2) NOT NULL,
	`createdDate` TIMESTAMP NULL,
	`modifiedDate` TIMESTAMP NULL,

	PRIMARY KEY (`id`),
	FOREIGN KEY (`beerStyleGuidelineId`) REFERENCES beerStyleGuidelines(`id`) ON DELETE CASCADE
) ENGINE=InnoDB	DEFAULT CHARSET=latin1;
commit;
--
-- clear table `beerStyles` and load data from CSV
--
DELETE FROM `beerStyles`;

LOAD DATA INFILE './data/beerStyles2008BJCP.csv'
INTO TABLE `beerStyles`
FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '"'
IGNORE 1 ROWS
(name, catNum, category, ogMin, ogMax, fgMin, fgMax, abvMin, abvMax, ibuMin, ibuMax, srmMin, srmMax)
SET beerStyleGuidelineId = 2008, createdDate = NOW(), modifiedDate = NOW();

LOAD DATA INFILE './data/beerStyles2015BJCP.csv'
INTO TABLE `beerStyles`
FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '"'
IGNORE 1 ROWS
(name, catNum, category, ogMin, ogMax, fgMin, fgMax, abvMin, abvMax, ibuMin, ibuMax, srmMin, srmMax)
SET beerStyleGuidelineId = 2015, createdDate = NOW(), modifiedDate = NOW();

LOAD DATA INFILE './data/beerStylesMiscellaneous.csv'
INTO TABLE `beerStyles`
FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '"'
IGNORE 1 ROWS
(name, catNum, category, ogMin, ogMax, fgMin, fgMax, abvMin, abvMax, ibuMin, ibuMax, srmMin, srmMax)
SET beerStyleGuidelineId = 0, createdDate = NOW(), modifiedDate = NOW();

-- --------------------------------------------------------

--
-- Table structure for table `beers`
--

CREATE TABLE IF NOT EXISTS `beers` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`name` text NOT NULL,
	`beerStyleId` int(11) NOT NULL,
	`notes` text NOT NULL,
	`ogEst` decimal(4,3) NOT NULL,
	`fgEst` decimal(4,3) NOT NULL,
	`srmEst` decimal(3,1) NOT NULL,
	`ibuEst` int(4) NOT NULL,
	`active` tinyint(1) NOT NULL DEFAULT 1,
	`createdDate` TIMESTAMP NULL,
	`modifiedDate` TIMESTAMP NULL,

PRIMARY KEY (`id`),
FOREIGN KEY (`beerStyleId`) REFERENCES beerStyles(`id`) ON DELETE CASCADE
) ENGINE=InnoDB	DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE IF NOT EXISTS `config` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`configName` varchar(50) NOT NULL,
	`configValue` longtext NOT NULL,
	`displayName` varchar(65) NOT NULL,
	`showOnPanel` tinyint(2) NOT NULL,
	`createdDate` TIMESTAMP NULL,
	`modifiedDate` TIMESTAMP NULL,

	PRIMARY KEY (`id`),
	UNIQUE KEY `configName_UNIQUE` (`configName`)
) ENGINE=InnoDB	DEFAULT CHARSET=latin1;

--
-- Dumping data for table `config`
--
DELETE FROM `config`;
INSERT INTO `config` ( configName, configValue, displayName, showOnPanel, createdDate, modifiedDate ) VALUES
( 'showTapNumCol', '1', 'Tap Column', '1', NOW(), NOW() ),
( 'showSrmCol', '1', 'SRM Column', '1', NOW(), NOW() ),
( 'showIbuCol', '1', 'IBU Column', '1', NOW(), NOW() ),
( 'showAbvCol', '1', 'ABV Column', '1', NOW(), NOW() ),
( 'showAbvImg', '1', 'ABV Images', '1', NOW(), NOW() ),
( 'showKegCol', '0', 'Keg Column', '1', NOW(), NOW() ),
( 'useHighResolution', '0', '4k Monitor Support', '1', NOW(), NOW() ),
( 'logoUrl', 'img/logo.png', 'Logo Url', '0', NOW(), NOW() ),
( 'adminLogoUrl', 'admin/img/logo.png', 'Admin Logo Url', '0', NOW(), NOW() ),
( 'headerText', 'Currently On Tap', 'Header Text', '0', NOW(), NOW() ),
( 'numberOfTaps', '0', 'Number of Taps', '0', NOW(), NOW() ),
( 'version', '1.0.3.395', 'Version', '0', NOW(), NOW() ),
( 'headerTextTruncLen' ,'20', 'Header Text Truncate Length', '0', NOW(), NOW() ),
( 'useFlowMeter','0','Use Flow Monitoring', '1', NOW(),NOW() );


-- --------------------------------------------------------

--
-- Table structure for table `kegTypes`
--

CREATE TABLE IF NOT EXISTS `kegTypes` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`displayName` text NOT NULL,
	`maxAmount` decimal(6,2) NOT NULL,
	`createdDate` TIMESTAMP NULL,
	`modifiedDate` TIMESTAMP NULL,

	PRIMARY KEY (`id`)
) ENGINE=InnoDB	DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kegTypes`
--
DELETE from `kegTypes`;
INSERT INTO `kegTypes` ( displayName, maxAmount, createdDate, modifiedDate ) VALUES
( 'Ball Lock (5 gal)', '5', NOW(), NOW() ),
( 'Ball Lock (2.5 gal)', '2.5', NOW(), NOW() ),
( 'Ball Lock (3 gal)', '3', NOW(), NOW() ),
( 'Ball Lock (10 gal)', '10', NOW(), NOW() ),
( 'Pin Lock (5 gal)', '5', NOW(), NOW() ),
( 'Sanke (1/6 bbl)', '5.16', NOW(), NOW() ),
( 'Sanke (1/4 bbl)', '7.75', NOW(), NOW() ),
( 'Sanke (slim 1/4 bbl)', '7.75', NOW(), NOW() ),
( 'Sanke (1/2 bbl)', '15.5', NOW(), NOW() ),
( 'Sanke (Euro)', '13.2', NOW(), NOW() ),
( 'Cask (pin)', '10.81', NOW(), NOW() ),
( 'Cask (firkin)', '10.81', NOW(), NOW() ),
( 'Cask (kilderkin)', '21.62', NOW(), NOW() ),
( 'Cask (barrel)', '43.23', NOW(), NOW() ),
( 'Cask (hogshead)', '64.85', NOW(), NOW() );

-- --------------------------------------------------------

--
-- Table structure for table `kegStatuses`
--

CREATE TABLE IF NOT EXISTS `kegStatuses` (
	`code` varchar(20) NOT NULL,
	`name` text NOT NULL,
	`createdDate` TIMESTAMP NULL,
	`modifiedDate` TIMESTAMP NULL,

	PRIMARY KEY (`code`)
) ENGINE=InnoDB	DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kegStatuses`
--
DELETE FROM `kegStatuses`;
INSERT INTO `kegStatuses` ( code, name, createdDate, modifiedDate ) VALUES
( 'SERVING', 'Serving', NOW(), NOW() ),
( 'PRIMARY', 'Primary', NOW(), NOW() ),
( 'SECONDARY', 'Secondary', NOW(), NOW() ),
( 'DRY_HOPPING', 'Dry Hopping', NOW(), NOW() ),
( 'CONDITIONING', 'Conditioning', NOW(), NOW() ),
( 'CLEAN', 'Clean', NOW(), NOW() ),
( 'NEEDS_CLEANING', 'Needs Cleaning', NOW(), NOW() ),
( 'NEEDS_PARTS', 'Needs Parts', NOW(), NOW() ),
( 'NEEDS_REPAIRS', 'Needs Repairs', NOW(), NOW() );

-- --------------------------------------------------------

--
-- Table structure for table `kegs`
--

CREATE TABLE IF NOT EXISTS `kegs` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`label` int(11) NOT NULL,
	`kegTypeId` int(11) NOT NULL,
	`make` text NOT NULL,
	`model` text NOT NULL,
	`serial` text NOT NULL,
	`stampedOwner` text NOT NULL,
	`stampedLoc` text NOT NULL,
	`notes` text NOT NULL,
	`kegStatusCode` varchar(20) NOT NULL,
	`weight` decimal(11,4) NOT NULL,
	`active` tinyint(1) NOT NULL DEFAULT 1,
	`createdDate` TIMESTAMP NULL,
	`modifiedDate` TIMESTAMP NULL,

	PRIMARY KEY (`id`),
	FOREIGN KEY (`kegStatusCode`) REFERENCES kegStatuses(`Code`) ON DELETE CASCADE,
	FOREIGN KEY (`kegTypeId`) REFERENCES kegTypes(`id`) ON DELETE CASCADE
) ENGINE=InnoDB	DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Table structure for table `taps`
--

CREATE TABLE IF NOT EXISTS `taps` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`beerId` int(11) NOT NULL,
	`kegId` int(11) NOT NULL,
	`tapNumber` int(11) NOT NULL,
	`pinId` int(2) DEFAULT NULL,
	`active` tinyint(1) NOT NULL,
	`ogAct` decimal(4,3) NOT NULL,
	`fgAct` decimal(4,3) NOT NULL,
	`srmAct` decimal(3,1) NOT NULL,
	`ibuAct` int(4) NOT NULL,
	`startAmount` decimal(6,1) NOT NULL,
	`currentAmount` decimal(6,1) NOT NULL,
	`createdDate` TIMESTAMP NULL,
	`modifiedDate` TIMESTAMP NULL,

	PRIMARY KEY (`id`),
	FOREIGN KEY (`beerId`) REFERENCES beers(`id`) ON DELETE CASCADE,
	FOREIGN KEY (`kegId`) REFERENCES kegs(`id`) ON DELETE CASCADE
) ENGINE=InnoDB	DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pours`
--

CREATE TABLE IF NOT EXISTS `pours` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`tapId` int(11) NOT NULL,
	`pinId` int(11) DEFAULT NULL,
  `amountPoured` float(6,3) NOT NULL,
  `pulses` int(6) NOT NULL,
 
	`createdDate` TIMESTAMP NULL,
	`modifiedDate` TIMESTAMP NULL,

	PRIMARY KEY (`id`),
	FOREIGN KEY (tapId) REFERENCES taps(id) ON DELETE CASCADE
) ENGINE=InnoDB	DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE IF NOT EXISTS `users` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`username` varchar(65) CHARACTER SET utf8 NOT NULL,
	`password` varchar(65) CHARACTER SET utf8 NOT NULL,
	`name` varchar(65) CHARACTER SET utf8 NOT NULL,
	`email` varchar(65) CHARACTER SET utf8 NOT NULL,
	`createdDate` TIMESTAMP NULL,
	`modifiedDate` TIMESTAMP NULL,

	PRIMARY KEY (`id`),
	UNIQUE KEY `username_UNIQUE` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



CREATE TABLE IF NOT EXISTS `srmRgb` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`srm` decimal(3,1) NOT NULL,
	`rgb` varchar(12) NOT NULL,
	`createdDate` TIMESTAMP NULL,
	`modifiedDate` TIMESTAMP NULL,

	PRIMARY KEY (`id`),
	UNIQUE KEY `srm_UNIQUE` (`srm`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data for table `srmRgb`
DELETE from srmRgb;
INSERT INTO srmRgb ( srm, rgb, createdDate, modifiedDate ) VALUES
( '0.0','252,252,243', NOW(), NOW() ),
( '0.1','248,248,230', NOW(), NOW() ),
( '0.2','248,248,220', NOW(), NOW() ),
( '0.3','247,247,199', NOW(), NOW() ),
( '0.4','244,249,185', NOW(), NOW() ),
( '0.5','247,249,180', NOW(), NOW() ),
( '0.6','248,249,178', NOW(), NOW() ),
( '0.7','244,246,169', NOW(), NOW() ),
( '0.8','245,247,166', NOW(), NOW() ),
( '0.9','246,247,156', NOW(), NOW() ),
( '1.0','243,249,147', NOW(), NOW() ),
( '1.1','246,248,141', NOW(), NOW() ),
( '1.2','246,249,136', NOW(), NOW() ),
( '1.3','245,250,128', NOW(), NOW() ),
( '1.4','246,249,121', NOW(), NOW() ),
( '1.5','248,249,114', NOW(), NOW() ),
( '1.6','243,249,104', NOW(), NOW() ),
( '1.7','246,248,107', NOW(), NOW() ),
( '1.8','248,247,99', NOW(), NOW() ),
( '1.9','245,247,92', NOW(), NOW() ),
( '2.0','248,247,83', NOW(), NOW() ),
( '2.1','244,248,72', NOW(), NOW() ),
( '2.2','248,247,73', NOW(), NOW() ),
( '2.3','246,247,62', NOW(), NOW() ),
( '2.4','241,248,53', NOW(), NOW() ),
( '2.5','244,247,48', NOW(), NOW() ),
( '2.6','246,249,40', NOW(), NOW() ),
( '2.7','243,249,34', NOW(), NOW() ),
( '2.8','245,247,30', NOW(), NOW() ),
( '2.9','248,245,22', NOW(), NOW() ),
( '3.0','246,245,19', NOW(), NOW() ),
( '3.1','244,242,22', NOW(), NOW() ),
( '3.2','244,240,21', NOW(), NOW() ),
( '3.3','243,242,19', NOW(), NOW() ),
( '3.4','244,238,24', NOW(), NOW() ),
( '3.5','244,237,29', NOW(), NOW() ),
( '3.6','238,233,22', NOW(), NOW() ),
( '3.7','240,233,23', NOW(), NOW() ),
( '3.8','238,231,25', NOW(), NOW() ),
( '3.9','234,230,21', NOW(), NOW() ),
( '4.0','236,230,26', NOW(), NOW() ),
( '4.1','230,225,24', NOW(), NOW() ),
( '4.2','232,225,25', NOW(), NOW() ),
( '4.3','230,221,27', NOW(), NOW() ),
( '4.4','224,218,23', NOW(), NOW() ),
( '4.5','229,216,31', NOW(), NOW() ),
( '4.6','229,214,30', NOW(), NOW() ),
( '4.7','223,213,26', NOW(), NOW() ),
( '4.8','226,213,28', NOW(), NOW() ),
( '4.9','223,209,29', NOW(), NOW() ),
( '5.0','224,208,27', NOW(), NOW() ),
( '5.1','224,204,32', NOW(), NOW() ),
( '5.2','221,204,33', NOW(), NOW() ),
( '5.3','220,203,29', NOW(), NOW() ),
( '5.4','218,200,32', NOW(), NOW() ),
( '5.5','220,197,34', NOW(), NOW() ),
( '5.6','218,196,41', NOW(), NOW() ),
( '5.7','217,194,43', NOW(), NOW() ),
( '5.8','216,192,39', NOW(), NOW() ),
( '5.9','213,190,37', NOW(), NOW() ),
( '6.0','213,188,38', NOW(), NOW() ),
( '6.1','212,184,39', NOW(), NOW() ),
( '6.2','214,183,43', NOW(), NOW() ),
( '6.3','213,180,45', NOW(), NOW() ),
( '6.4','210,179,41', NOW(), NOW() ),
( '6.5','208,178,42', NOW(), NOW() ),
( '6.6','208,176,46', NOW(), NOW() ),
( '6.7','204,172,48', NOW(), NOW() ),
( '6.8','204,172,52', NOW(), NOW() ),
( '6.9','205,170,55', NOW(), NOW() ),
( '7.0','201,167,50', NOW(), NOW() ),
( '7.1','202,167,52', NOW(), NOW() ),
( '7.2','201,166,51', NOW(), NOW() ),
( '7.3','199,162,54', NOW(), NOW() ),
( '7.4','198,160,56', NOW(), NOW() ),
( '7.5','200,158,60', NOW(), NOW() ),
( '7.6','194,156,54', NOW(), NOW() ),
( '7.7','196,155,54', NOW(), NOW() ),
( '7.8','198,151,60', NOW(), NOW() ),
( '7.9','193,150,60', NOW(), NOW() ),
( '8.0','191,146,59', NOW(), NOW() ),
( '8.1','190,147,57', NOW(), NOW() ),
( '8.2','190,147,59', NOW(), NOW() ),
( '8.3','190,145,60', NOW(), NOW() ),
( '8.4','186,148,56', NOW(), NOW() ),
( '8.5','190,145,58', NOW(), NOW() ),
( '8.6','193,145,59', NOW(), NOW() ),
( '8.7','190,145,58', NOW(), NOW() ),
( '8.8','191,143,59', NOW(), NOW() ),
( '8.9','191,141,61', NOW(), NOW() ),
( '9.0','190,140,58', NOW(), NOW() ),
( '9.1','192,140,61', NOW(), NOW() ),
( '9.2','193,138,62', NOW(), NOW() ),
( '9.3','192,137,59', NOW(), NOW() ),
( '9.4','193,136,59', NOW(), NOW() ),
( '9.5','195,135,63', NOW(), NOW() ),
( '9.6','191,136,58', NOW(), NOW() ),
( '9.7','191,134,67', NOW(), NOW() ),
( '9.8','193,131,67', NOW(), NOW() ),
( '9.9','190,130,58', NOW(), NOW() ),
( '10.0','191,129,58', NOW(), NOW() ),
( '10.1','191,131,57', NOW(), NOW() ),
( '10.2','191,129,58', NOW(), NOW() ),
( '10.3','191,129,58', NOW(), NOW() ),
( '10.4','190,129,55', NOW(), NOW() ),
( '10.5','191,127,59', NOW(), NOW() ),
( '10.6','194,126,59', NOW(), NOW() ),
( '10.7','188,128,54', NOW(), NOW() ),
( '10.8','190,124,55', NOW(), NOW() ),
( '10.9','193,122,55', NOW(), NOW() ),
( '11.0','190,124,55', NOW(), NOW() ),
( '11.1','194,121,59', NOW(), NOW() ),
( '11.2','193,120,56', NOW(), NOW() ),
( '11.3','190,119,52', NOW(), NOW() ),
( '11.4','182,117,54', NOW(), NOW() ),
( '11.5','196,116,59', NOW(), NOW() ),
( '11.6','191,118,56', NOW(), NOW() ),
( '11.7','190,116,57', NOW(), NOW() ),
( '11.8','191,115,58', NOW(), NOW() ),
( '11.9','189,115,56', NOW(), NOW() ),
( '12.0','191,113,56', NOW(), NOW() ),
( '12.1','191,113,53', NOW(), NOW() ),
( '12.2','188,112,57', NOW(), NOW() ),
( '12.3','190,112,55', NOW(), NOW() ),
( '12.4','184,110,52', NOW(), NOW() ),
( '12.5','188,109,55', NOW(), NOW() ),
( '12.6','189,109,55', NOW(), NOW() ),
( '12.7','186,106,50', NOW(), NOW() ),
( '12.8','190,103,52', NOW(), NOW() ),
( '12.9','189,104,54', NOW(), NOW() ),
( '13.0','188,103,51', NOW(), NOW() ),
( '13.1','188,103,51', NOW(), NOW() ),
( '13.2','186,101,51', NOW(), NOW() ),
( '13.3','186,102,56', NOW(), NOW() ),
( '13.4','185,100,56', NOW(), NOW() ),
( '13.5','185,98,59', NOW(), NOW() ),
( '13.6','183,98,54', NOW(), NOW() ),
( '13.7','181,100,53', NOW(), NOW() ),
( '13.8','182,97,55', NOW(), NOW() ),
( '13.9','177,97,51', NOW(), NOW() ),
( '14.0','178,96,51', NOW(), NOW() ),
( '14.1','176,96,49', NOW(), NOW() ),
( '14.2','177,96,55', NOW(), NOW() ),
( '14.3','178,95,55', NOW(), NOW() ),
( '14.4','171,94,55', NOW(), NOW() ),
( '14.5','171,92,56', NOW(), NOW() ),
( '14.6','172,93,59', NOW(), NOW() ),
( '14.7','168,92,55', NOW(), NOW() ),
( '14.8','169,90,54', NOW(), NOW() ),
( '14.9','168,88,57', NOW(), NOW() ),
( '15.0','165,89,54', NOW(), NOW() ),
( '15.1','166,88,54', NOW(), NOW() ),
( '15.2','165,88,58', NOW(), NOW() ),
( '15.3','161,88,52', NOW(), NOW() ),
( '15.4','163,85,55', NOW(), NOW() ),
( '15.5','160,86,56', NOW(), NOW() ),
( '15.6','158,85,57', NOW(), NOW() ),
( '15.7','158,86,54', NOW(), NOW() ),
( '15.8','159,84,57', NOW(), NOW() ),
( '15.9','156,83,53', NOW(), NOW() ),
( '16.0','152,83,54', NOW(), NOW() ),
( '16.1','150,83,55', NOW(), NOW() ),
( '16.2','150,81,56', NOW(), NOW() ),
( '16.3','146,81,56', NOW(), NOW() ),
( '16.4','147,79,54', NOW(), NOW() ),
( '16.5','147,79,55', NOW(), NOW() ),
( '16.6','146,78,54', NOW(), NOW() ),
( '16.7','142,77,51', NOW(), NOW() ),
( '16.8','143,79,53', NOW(), NOW() ),
( '16.9','142,77,54', NOW(), NOW() ),
( '17.0','141,76,50', NOW(), NOW() ),
( '17.1','140,75,50', NOW(), NOW() ),
( '17.2','138,73,49', NOW(), NOW() ),
( '17.3','135,70,45', NOW(), NOW() ),
( '17.4','136,71,49', NOW(), NOW() ),
( '17.5','140,72,49', NOW(), NOW() ),
( '17.6','128,70,45', NOW(), NOW() ),
( '17.7','129,71,46', NOW(), NOW() ),
( '17.8','130,69,47', NOW(), NOW() ),
( '17.9','123,69,45', NOW(), NOW() ),
( '18.0','124,69,45', NOW(), NOW() ),
( '18.1','121,66,40', NOW(), NOW() ),
( '18.2','120,67,40', NOW(), NOW() ),
( '18.3','119,64,38', NOW(), NOW() ),
( '18.4','116,63,34', NOW(), NOW() ),
( '18.5','120,63,35', NOW(), NOW() ),
( '18.6','120,62,37', NOW(), NOW() ),
( '18.7','112,63,35', NOW(), NOW() ),
( '18.8','111,62,36', NOW(), NOW() ),
( '18.9','109,60,34', NOW(), NOW() ),
( '19.0','107,58,30', NOW(), NOW() ),
( '19.1','106,57,31', NOW(), NOW() ),
( '19.2','107,56,31', NOW(), NOW() ),
( '19.3','105,56,28', NOW(), NOW() ),
( '19.4','105,56,28', NOW(), NOW() ),
( '19.5','104,52,31', NOW(), NOW() ),
( '19.6','102,53,27', NOW(), NOW() ),
( '19.7','100,53,26', NOW(), NOW() ),
( '19.8','99,52,25', NOW(), NOW() ),
( '19.9','93,53,24', NOW(), NOW() ),
( '20.0','93,52,26', NOW(), NOW() ),
( '20.1','89,49,20', NOW(), NOW() ),
( '20.2','90,50,21', NOW(), NOW() ),
( '20.3','91,48,20', NOW(), NOW() ),
( '20.4','83,48,15', NOW(), NOW() ),
( '20.5','88,48,17', NOW(), NOW() ),
( '20.6','86,46,17', NOW(), NOW() ),
( '20.7','81,45,15', NOW(), NOW() ),
( '20.8','83,44,15', NOW(), NOW() ),
( '20.9','81,45,15', NOW(), NOW() ),
( '21.0','78,42,12', NOW(), NOW() ),
( '21.1','77,43,12', NOW(), NOW() ),
( '21.2','75,41,12', NOW(), NOW() ),
( '21.3','74,41,5', NOW(), NOW() ),
( '21.4','78,40,23', NOW(), NOW() ),
( '21.5','83,43,46', NOW(), NOW() ),
( '21.6','78,43,41', NOW(), NOW() ),
( '21.7','78,40,41', NOW(), NOW() ),
( '21.8','76,41,41', NOW(), NOW() ),
( '21.9','74,39,39', NOW(), NOW() ),
( '22.0','74,39,39', NOW(), NOW() ),
( '22.1','69,39,35', NOW(), NOW() ),
( '22.2','70,37,37', NOW(), NOW() ),
( '22.3','68,38,36', NOW(), NOW() ),
( '22.4','64,35,34', NOW(), NOW() ),
( '22.5','64,35,34', NOW(), NOW() ),
( '22.6','62,33,32', NOW(), NOW() ),
( '22.7','58,33,31', NOW(), NOW() ),
( '22.8','61,33,31', NOW(), NOW() ),
( '22.9','58,33,33', NOW(), NOW() ),
( '23.0','54,31,27', NOW(), NOW() ),
( '23.1','52,29,28', NOW(), NOW() ),
( '23.2','52,29,28', NOW(), NOW() ),
( '23.3','49,28,27', NOW(), NOW() ),
( '23.4','48,27,26', NOW(), NOW() ),
( '23.5','48,27,26', NOW(), NOW() ),
( '23.6','44,25,25', NOW(), NOW() ),
( '23.7','44,25,23', NOW(), NOW() ),
( '23.8','42,24,26', NOW(), NOW() ),
( '23.9','40,23,22', NOW(), NOW() ),
( '24.0','38,23,22', NOW(), NOW() ),
( '24.1','38,23,22', NOW(), NOW() ),
( '24.2','38,23,22', NOW(), NOW() ),
( '24.3','38,23,22', NOW(), NOW() ),
( '24.4','38,23,22', NOW(), NOW() ),
( '24.5','38,23,22', NOW(), NOW() ),
( '24.6','38,23,22', NOW(), NOW() ),
( '24.7','38,23,22', NOW(), NOW() ),
( '24.8','38,23,22', NOW(), NOW() ),
( '24.9','38,23,22', NOW(), NOW() ),
( '25.0','38,23,22', NOW(), NOW() ),
( '25.1','38,23,22', NOW(), NOW() ),
( '25.2','38,23,22', NOW(), NOW() ),
( '25.3','38,23,22', NOW(), NOW() ),
( '25.4','38,23,22', NOW(), NOW() ),
( '25.5','38,23,22', NOW(), NOW() ),
( '25.6','38,23,24', NOW(), NOW() ),
( '25.7','25,16,15', NOW(), NOW() ),
( '25.8','25,16,15', NOW(), NOW() ),
( '25.9','25,16,15', NOW(), NOW() ),
( '26.0','25,16,15', NOW(), NOW() ),
( '26.1','25,16,15', NOW(), NOW() ),
( '26.2','25,16,15', NOW(), NOW() ),
( '26.3','25,16,15', NOW(), NOW() ),
( '26.4','25,16,15', NOW(), NOW() ),
( '26.5','25,16,15', NOW(), NOW() ),
( '26.6','25,16,15', NOW(), NOW() ),
( '26.7','25,16,15', NOW(), NOW() ),
( '26.8','25,16,15', NOW(), NOW() ),
( '26.9','25,16,15', NOW(), NOW() ),
( '27.0','25,16,15', NOW(), NOW() ),
( '27.1','25,16,15', NOW(), NOW() ),
( '27.2','25,16,15', NOW(), NOW() ),
( '27.3','18,13,12', NOW(), NOW() ),
( '27.4','18,13,12', NOW(), NOW() ),
( '27.5','18,13,12', NOW(), NOW() ),
( '27.6','18,13,12', NOW(), NOW() ),
( '27.7','18,13,12', NOW(), NOW() ),
( '27.8','18,13,12', NOW(), NOW() ),
( '27.9','18,13,12', NOW(), NOW() ),
( '28.0','18,13,12', NOW(), NOW() ),
( '28.1','18,13,12', NOW(), NOW() ),
( '28.2','18,13,12', NOW(), NOW() ),
( '28.3','18,13,12', NOW(), NOW() ),
( '28.4','18,13,12', NOW(), NOW() ),
( '28.5','18,13,12', NOW(), NOW() ),
( '28.6','18,13,12', NOW(), NOW() ),
( '28.7','17,13,10', NOW(), NOW() ),
( '28.8','18,13,12', NOW(), NOW() ),
( '28.9','16,11,10', NOW(), NOW() ),
( '29.0','16,11,10', NOW(), NOW() ),
( '29.1','16,11,10', NOW(), NOW() ),
( '29.2','16,11,10', NOW(), NOW() ),
( '29.3','16,11,10', NOW(), NOW() ),
( '29.4','16,11,10', NOW(), NOW() ),
( '29.5','16,11,10', NOW(), NOW() ),
( '29.6','16,11,10', NOW(), NOW() ),
( '29.7','16,11,10', NOW(), NOW() ),
( '29.8','16,11,10', NOW(), NOW() ),
( '29.9','16,11,10', NOW(), NOW() ),
( '30.0','16,11,10', NOW(), NOW() ),
( '30.1','16,11,10', NOW(), NOW() ),
( '30.2','16,11,10', NOW(), NOW() ),
( '30.3','16,11,10', NOW(), NOW() ),
( '30.4','16,11,10', NOW(), NOW() ),
( '30.5','14,9,8', NOW(), NOW() ),
( '30.6','15,10,9', NOW(), NOW() ),
( '30.7','14,9,8', NOW(), NOW() ),
( '30.8','14,9,8', NOW(), NOW() ),
( '30.9','14,9,8', NOW(), NOW() ),
( '31.0','14,9,8', NOW(), NOW() ),
( '31.1','14,9,8', NOW(), NOW() ),
( '31.2','14,9,8', NOW(), NOW() ),
( '31.3','14,9,8', NOW(), NOW() ),
( '31.4','14,9,8', NOW(), NOW() ),
( '31.5','14,9,8', NOW(), NOW() ),
( '31.6','14,9,8', NOW(), NOW() ),
( '31.7','14,9,8', NOW(), NOW() ),
( '31.8','14,9,8', NOW(), NOW() ),
( '31.9','14,9,8', NOW(), NOW() ),
( '32.0','15,11,8', NOW(), NOW() ),
( '32.1','12,9,7', NOW(), NOW() ),
( '32.2','12,9,7', NOW(), NOW() ),
( '32.3','12,9,7', NOW(), NOW() ),
( '32.4','12,9,7', NOW(), NOW() ),
( '32.5','12,9,7', NOW(), NOW() ),
( '32.6','12,9,7', NOW(), NOW() ),
( '32.7','12,9,7', NOW(), NOW() ),
( '32.8','12,9,7', NOW(), NOW() ),
( '32.9','12,9,7', NOW(), NOW() ),
( '33.0','12,9,7', NOW(), NOW() ),
( '33.1','12,9,7', NOW(), NOW() ),
( '33.2','12,9,7', NOW(), NOW() ),
( '33.3','12,9,7', NOW(), NOW() ),
( '33.4','12,9,7', NOW(), NOW() ),
( '33.5','12,9,7', NOW(), NOW() ),
( '33.6','12,9,7', NOW(), NOW() ),
( '33.7','10,7,7', NOW(), NOW() ),
( '33.8','10,7,5', NOW(), NOW() ),
( '33.9','8,7,7', NOW(), NOW() ),
( '34.0','8,7,7', NOW(), NOW() ),
( '34.1','8,7,7', NOW(), NOW() ),
( '34.2','8,7,7', NOW(), NOW() ),
( '34.3','8,7,7', NOW(), NOW() ),
( '34.4','8,7,7', NOW(), NOW() ),
( '34.5','8,7,7', NOW(), NOW() ),
( '34.6','8,7,7', NOW(), NOW() ),
( '34.7','8,7,7', NOW(), NOW() ),
( '34.8','8,7,7', NOW(), NOW() ),
( '34.9','8,7,7', NOW(), NOW() ),
( '35.0','8,7,7', NOW(), NOW() ),
( '35.1','8,7,7', NOW(), NOW() ),
( '35.2','8,7,7', NOW(), NOW() ),
( '35.3','7,6,6', NOW(), NOW() ),
( '35.4','7,6,6', NOW(), NOW() ),
( '35.5','7,6,6', NOW(), NOW() ),
( '35.6','7,6,6', NOW(), NOW() ),
( '35.7','7,6,6', NOW(), NOW() ),
( '35.8','7,6,6', NOW(), NOW() ),
( '35.9','7,6,6', NOW(), NOW() ),
( '36.0','7,6,6', NOW(), NOW() ),
( '36.1','7,6,6', NOW(), NOW() ),
( '36.2','7,6,6', NOW(), NOW() ),
( '36.3','7,6,6', NOW(), NOW() ),
( '36.4','7,6,6', NOW(), NOW() ),
( '36.5','7,6,6', NOW(), NOW() ),
( '36.6','7,6,6', NOW(), NOW() ),
( '36.7','7,7,4', NOW(), NOW() ),
( '36.8','6,6,3', NOW(), NOW() ),
( '36.9','6,5,5', NOW(), NOW() ),
( '37.0','4,5,4', NOW(), NOW() ),
( '37.1','4,5,4', NOW(), NOW() ),
( '37.2','4,5,4', NOW(), NOW() ),
( '37.3','4,5,4', NOW(), NOW() ),
( '37.4','4,5,4', NOW(), NOW() ),
( '37.5','4,5,4', NOW(), NOW() ),
( '37.6','4,5,4', NOW(), NOW() ),
( '37.7','4,5,4', NOW(), NOW() ),
( '37.8','4,5,4', NOW(), NOW() ),
( '37.9','4,5,4', NOW(), NOW() ),
( '38.0','4,5,4', NOW(), NOW() ),
( '38.1','4,5,4', NOW(), NOW() ),
( '38.2','4,5,4', NOW(), NOW() ),
( '38.3','4,5,4', NOW(), NOW() ),
( '38.4','4,5,4', NOW(), NOW() ),
( '38.5','3,4,3', NOW(), NOW() ),
( '38.6','4,5,4', NOW(), NOW() ),
( '38.7','3,4,3', NOW(), NOW() ),
( '38.8','3,4,3', NOW(), NOW() ),
( '38.9','3,4,3', NOW(), NOW() ),
( '39.0','3,4,3', NOW(), NOW() ),
( '39.1','3,4,3', NOW(), NOW() ),
( '39.2','3,4,3', NOW(), NOW() ),
( '39.3','3,4,3', NOW(), NOW() ),
( '39.4','3,4,3', NOW(), NOW() ),
( '39.5','3,4,3', NOW(), NOW() ),
( '39.6','3,4,3', NOW(), NOW() ),
( '39.7','3,4,3', NOW(), NOW() ),
( '39.8','3,4,3', NOW(), NOW() ),
( '39.9','3,4,3', NOW(), NOW() ),
( '40.0','3,4,3', NOW(), NOW() );

-- --------------------------------------------------------

--
-- Create View `vwGetTapsAmountPoured`
--

CREATE OR REPLACE VIEW vwGetTapsAmountPoured
AS
SELECT tapId, SUM(amountPoured) as amountPoured FROM pours GROUP BY tapId;

-- --------------------------------------------------------

--
-- Create View `vwGetActiveTaps`
--

CREATE OR REPLACE VIEW vwGetActiveTaps
AS

SELECT
	t.id,
	b.name,
	bs.name as 'style',
	b.notes,
	t.ogAct,
	t.fgAct,
	t.srmAct,
	t.ibuAct,
	t.startAmount,
	IFNULL(p.amountPoured, 0) as amountPoured,
	t.startAmount - IFNULL(p.amountPoured, 0) as remainAmount,
	t.tapNumber,
	s.rgb as srmRgb
FROM taps t
	LEFT JOIN beers b ON b.id = t.beerId
	LEFT JOIN beerStyles bs ON bs.id = b.beerStyleId
	LEFT JOIN srmRgb s ON s.srm = t.srmAct
	LEFT JOIN vwGetTapsAmountPoured as p ON p.tapId = t.Id
WHERE t.active = true
ORDER BY t.tapNumber;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
