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
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `epc`.`addresses`
--
INSERT INTO `epc`.`addresses` (`id`,`user_id`,`nickname`,`companyName`,`streetLines`,`city`,`stateOrProvinceCode`,`postalCode`,`phoneNumber`,`default`) VALUES 
 (1,7,'Address 1','','9 Morris Rd.','Stanhope','NJ','07874','2152083549',0x31),
 (2,7,'Address 2','','143 Penns Grant Dr.','Morrisville','PA','19067','2157362235',0x30),
 (3,8,'Home','','437 Musket Dr.','Morrisville','PA','19067','2152956493',0x31),
 (4,15,'default',NULL,'OhOhOh St.','Stanhope','NJ','07874','2121212121',0x31),
 (5,16,'default',NULL,'10 Beef St.','Beverly Hills','CA','90210','2121212121',0x31);

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

--
-- Definition of table `epc`.`tickets`
--

DROP TABLE IF EXISTS `epc`.`tickets`;
CREATE TABLE  `epc`.`tickets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `service` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `media` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `megabytes` int(11) NOT NULL,
  `carrier` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'FedEx',
  `weight` int(11) DEFAULT NULL,
  `length` int(11) DEFAULT NULL,
  `width` int(11) DEFAULT NULL,
  `height` int(11) DEFAULT NULL,
  `shipping_cost` decimal(8,2) DEFAULT NULL,
  `comments` text COLLATE utf8_unicode_ci,
  `status` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'Pending Review',
  `labelpath` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `rtc` datetime DEFAULT NULL,
  `etc` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `epc`.`tickets`
--

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
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `epc`.`users`
--
INSERT INTO `epc`.`users` (`id`,`email`,`crypted_password`,`created_at`,`updated_at`,`first_name`,`last_name`) VALUES 
 (7,'jaradd@gmail.com','$1$tVhwKGxm$CltqPKJ0pqpwsVIph8OhB.','2010-03-23 18:02:38','2010-03-23 18:02:38','jarad','delorenzo'),
 (8,'sheep@gmail.com','$1$XoeECqkb$FkEIFj5RREvckuESrTbZZ1','2010-03-23 18:11:37','2010-03-23 18:11:37','sheep','man'),
 (9,'sheep1@gmail.com','$1$bHXaMCEg$DHmmKvMeHezLXQ2RXvqqh0','2010-03-23 18:11:53','2010-03-23 18:11:53','sheep','man'),
 (10,'sheep2@gmail.com','$1$6bH.mrl2$VUGt8FeT/iqEcUOYpejMQ/','2010-03-23 18:12:26','2010-03-23 18:12:26','sheep','man'),
 (11,'beef@sheep.com','$1$zOPbPcHP$v/qfuqPuVj/9JzvBNJt5i0','2010-03-23 18:19:38','2010-03-23 18:19:38','jarad','man'),
 (12,'sheep@meadow.com','$1$qRSzWJF0$4HsrteiK7TRjSsND3dyhK1','2010-05-20 14:29:31','2010-05-20 14:29:31','jk','kj'),
 (13,'s1heep@meadow.com','$1$pXti2gLi$0Yaz76YtLLRlu92njqkiD.','2010-05-20 14:31:18','2010-05-20 14:31:18','lklk','lklk'),
 (14,'s12heep@meadow.com','$1$3.S6MAo7$DD/zyZhTy0Gkv0hM7VEu6.','2010-05-20 14:36:25','2010-05-20 14:36:25','Lklk','Lklk'),
 (15,'boots@man.man','$1$ajQpFfgE$TxoUu2CLkY6xTF/66/tyV0','2010-05-21 15:13:01','2010-05-21 15:13:01','Boot','Man'),
 (16,'toop@test.com','$1$c04Tp0Wt$r6A3HZ3rHoT1vgTKTlXrr.','2010-05-25 15:53:15','2010-05-25 15:53:15','Rock','Solid');



/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
