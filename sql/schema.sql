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

LOAD DATA INFILE './data/beerStyles2018BJCPProvisional.csv'
INTO TABLE `beerStyles`
FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '"'
IGNORE 1 ROWS
(name, catNum, category, ogMin, ogMax, fgMin, fgMax, abvMin, abvMax, ibuMin, ibuMax, srmMin, srmMax)
SET beerStyleGuidelineId = 2018, createdDate = NOW(), modifiedDate = NOW();

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
	`untappdId` int(11),
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
( 'useFlowMeter','0','Use Flow Monitoring', '1', NOW(),NOW() ),
( 'untappdBreweryId','','Untappd Brewery Id', '0', NOW(),NOW() );


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

LOAD DATA INFILE './data/srmRgb.csv'
INTO TABLE `srmRgb`
FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '"'
IGNORE 1 ROWS
(srm, rgb)
SET createdDate = NOW(), modifiedDate = NOW();
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
	b.untappdId,
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
