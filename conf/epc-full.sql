-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	5.1.41-3ubuntu12.1


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


--
-- Create schema epc
--

CREATE DATABASE IF NOT EXISTS epc;
USE epc;

--
-- Definition of table `epc`.`addresses`
--

DROP TABLE IF EXISTS `epc`.`addresses`;
CREATE TABLE  `epc`.`addresses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `nickname` varchar(40) NOT NULL,
  `companyName` varchar(100) DEFAULT NULL,
  `streetLines` varchar(200) NOT NULL,
  `city` varchar(100) NOT NULL,
  `stateOrProvinceCode` varchar(100) NOT NULL,
  `postalCode` varchar(15) NOT NULL,
  `phoneNumber` varchar(10) NOT NULL,
  `default` binary(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `epc`.`addresses`
--

/*!40000 ALTER TABLE `addresses` DISABLE KEYS */;
LOCK TABLES `addresses` WRITE;
INSERT INTO `epc`.`addresses` VALUES  (1,1,'default',NULL,'9 Morris Rd.','Stanhope','NJ','07874','2152083549',0x31);
UNLOCK TABLES;
/*!40000 ALTER TABLE `addresses` ENABLE KEYS */;


--
-- Definition of table `epc`.`schema_migrations`
--

DROP TABLE IF EXISTS `epc`.`schema_migrations`;
CREATE TABLE  `epc`.`schema_migrations` (
  `version` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  UNIQUE KEY `unique_schema_migrations` (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `epc`.`schema_migrations`
--

/*!40000 ALTER TABLE `schema_migrations` DISABLE KEYS */;
LOCK TABLES `schema_migrations` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `schema_migrations` ENABLE KEYS */;


--
-- Definition of table `epc`.`services`
--

DROP TABLE IF EXISTS `epc`.`services`;
CREATE TABLE  `epc`.`services` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `epc`.`services`
--

/*!40000 ALTER TABLE `services` DISABLE KEYS */;
LOCK TABLES `services` WRITE;
INSERT INTO `epc`.`services` VALUES  (0,'Media Recovery','I want to recover some, or all of my personal media, including photos, music, and documents.'),
 (1,'Full Recovery','I want to recover all of the data on my damaged media.');
UNLOCK TABLES;
/*!40000 ALTER TABLE `services` ENABLE KEYS */;


--
-- Definition of table `epc`.`ticket_comments`
--

DROP TABLE IF EXISTS `epc`.`ticket_comments`;
CREATE TABLE  `epc`.`ticket_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `epc`.`ticket_comments`
--

/*!40000 ALTER TABLE `ticket_comments` DISABLE KEYS */;
LOCK TABLES `ticket_comments` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `ticket_comments` ENABLE KEYS */;


--
-- Definition of table `epc`.`tickets`
--

DROP TABLE IF EXISTS `epc`.`tickets`;
CREATE TABLE  `epc`.`tickets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `service_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `media` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `megabytes` int(11) NOT NULL,
  `carrier` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'FedEx',
  `weight` int(11) DEFAULT NULL,
  `length` int(11) DEFAULT NULL,
  `width` int(11) DEFAULT NULL,
  `height` int(11) DEFAULT NULL,
  `service_fee` float DEFAULT NULL,
  `shipping_cost` decimal(8,2) DEFAULT NULL,
  `comments` text COLLATE utf8_unicode_ci,
  `status` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'Media is not yet shipped',
  `labelpath` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `rtc` datetime DEFAULT NULL,
  `etc` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `epc`.`tickets`
--

/*!40000 ALTER TABLE `tickets` DISABLE KEYS */;
LOCK TABLES `tickets` WRITE;
INSERT INTO `epc`.`tickets` VALUES  (1,1,'0','Flash Card',16,'FedEx',2,5,2,5,199,'10.00','fileTypes=pictures,videos,ppt|specificFiles=','Media is not yet shipped','labels/DeLorenzoJarad1275680177',NULL,NULL,'2010-06-04 15:36:17','2010-06-04 15:36:17'),
 (2,1,'1','External Hard Drive',250,'FedEx',2,5,2,5,199,'10.00','fileTypes=|specificFiles=','Media is not yet shipped','labels/DeLorenzoJarad1275680346',NULL,NULL,'2010-06-04 15:39:06','2010-06-04 15:39:06');
UNLOCK TABLES;
/*!40000 ALTER TABLE `tickets` ENABLE KEYS */;


--
-- Definition of table `epc`.`users`
--

DROP TABLE IF EXISTS `epc`.`users`;
CREATE TABLE  `epc`.`users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `crypted_password` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `epc`.`users`
--

/*!40000 ALTER TABLE `users` DISABLE KEYS */;
LOCK TABLES `users` WRITE;
INSERT INTO `epc`.`users` VALUES  (1,'jaradd@gmail.com','$1$.J/EoDxq$FcWY/U72RLL5AuBBIpomK1','2010-06-04 15:36:15','2010-06-04 15:36:15','Jarad','DeLorenzo');
UNLOCK TABLES;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;




/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
