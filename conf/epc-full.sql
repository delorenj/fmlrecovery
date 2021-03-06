-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	5.1.41-3ubuntu12.3


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


--
-- Create schema fmladmin_epc
--

CREATE DATABASE IF NOT EXISTS fmladmin_epc;
USE fmladmin_epc;

--
-- Definition of table `fmladmin_epc`.`addresses`
--

DROP TABLE IF EXISTS `fmladmin_epc`.`addresses`;
CREATE TABLE  `fmladmin_epc`.`addresses` (
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
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `fmladmin_epc`.`addresses`
--
INSERT INTO `fmladmin_epc`.`addresses` (`id`,`user_id`,`nickname`,`companyName`,`streetLines`,`city`,`stateOrProvinceCode`,`postalCode`,`phoneNumber`,`default`) VALUES 
 (1,1,'default',NULL,'9 Morris Rd.','Stanhope','NJ','07874','2152083549',0x31),
 (2,2,'default',NULL,'10 Man Ln.','Morrisville','PA','19067','2152223333',0x31),
 (3,191144,'default',NULL,'150 Parish Dr','Wayne','NJ','07470','9733052261',0x31);

--
-- Definition of table `fmladmin_epc`.`schema_migrations`
--

DROP TABLE IF EXISTS `fmladmin_epc`.`schema_migrations`;
CREATE TABLE  `fmladmin_epc`.`schema_migrations` (
  `version` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  UNIQUE KEY `unique_schema_migrations` (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `fmladmin_epc`.`schema_migrations`
--

--
-- Definition of table `fmladmin_epc`.`services`
--

DROP TABLE IF EXISTS `fmladmin_epc`.`services`;
CREATE TABLE  `fmladmin_epc`.`services` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `fmladmin_epc`.`services`
--
INSERT INTO `fmladmin_epc`.`services` (`id`,`name`,`description`) VALUES 
 (0,'Media Recovery','I want to recover some, or all of my personal media, including photos, music, and documents.'),
 (1,'Full Recovery','I want to recover all of the data on my damaged media.');

--
-- Definition of table `fmladmin_epc`.`ticket_comments`
--

DROP TABLE IF EXISTS `fmladmin_epc`.`ticket_comments`;
CREATE TABLE  `fmladmin_epc`.`ticket_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `fmladmin_epc`.`ticket_comments`
--
INSERT INTO `fmladmin_epc`.`ticket_comments` (`id`,`ticket_id`,`comment`,`admin`,`created_at`) VALUES 
 (1,2,'Where\'s the clam?',0,'2010-06-07 13:41:04'),
 (2,2,'Where\'s the clam?',1,'2010-06-07 13:44:01'),
 (3,2,'Where\'s the clam?',0,'2010-06-07 13:44:35'),
 (4,2,'Where\'s the clam?',1,'2010-06-07 13:45:10'),
 (5,2,'Where\'s the clam?',0,'2010-06-07 13:45:43'),
 (6,2,'Where\'s the clam?',1,'2010-06-07 13:46:54'),
 (7,2,'Where\'s the clam?',0,'2010-06-07 13:48:16'),
 (8,2,'in your horse! holmes...',0,'2010-06-07 13:48:33'),
 (9,2,'GWATS!',1,'2010-06-07 14:08:52'),
 (10,2,'Sheep men notwithstanding',1,'2010-06-07 16:27:18'),
 (11,1,'Where\'s my shizz?',1,'2010-06-07 16:35:05'),
 (12,3,'When will it be done?',0,'2010-06-08 10:36:32'),
 (13,1,'in my ass',1,'2010-06-08 13:29:55'),
 (14,3,'sheep?',0,'2010-06-08 13:30:29'),
 (15,3,'Never',1,'2010-06-09 16:29:14'),
 (16,4,'Huh?',1,'2010-06-10 15:13:32'),
 (17,4,'Gwats.',1,'2010-06-10 15:13:51');

--
-- Definition of table `fmladmin_epc`.`tickets`
--

DROP TABLE IF EXISTS `fmladmin_epc`.`tickets`;
CREATE TABLE  `fmladmin_epc`.`tickets` (
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
  `rtc` datetime DEFAULT NULL,
  `etc` datetime DEFAULT NULL,
  `state` enum('OPEN','CLOSED') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'OPEN',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `fmladmin_epc`.`tickets`
--
INSERT INTO `fmladmin_epc`.`tickets` (`id`,`user_id`,`service_id`,`media`,`megabytes`,`carrier`,`weight`,`length`,`width`,`height`,`service_fee`,`shipping_cost`,`comments`,`status`,`rtc`,`etc`,`state`,`created_at`,`updated_at`) VALUES 
 (1,1,'0','Flash Card',16,'FedEx',2,5,2,5,199,'10.00','fileTypes=pictures,videos,ppt|specificFiles=','Media is not yet shipped',NULL,NULL,'OPEN','2010-06-04 15:36:17','2010-06-04 15:36:17'),
 (2,1,'1','External Hard Drive',250,'FedEx',2,5,2,5,199,'10.00','fileTypes=|specificFiles=','Media is not yet shipped',NULL,NULL,'OPEN','2010-06-04 15:39:06','2010-06-04 15:39:06'),
 (3,2,'1','External Hard Drive',90,'FedEx',2,5,2,5,NULL,'10.00','fileTypes=|specificFiles=','Media is not yet shipped',NULL,NULL,'OPEN','2010-06-08 10:35:58','2010-06-08 10:35:58'),
 (4,1,'0','External Hard Drive',90,'FedEx',2,5,2,5,NULL,'10.00','fileTypes=|specificFiles=','Media is not yet shipped',NULL,NULL,'OPEN','2010-06-10 15:08:20','2010-06-10 15:08:20'),
 (5,1,'0','idk',88,'FedEx',2,5,2,5,NULL,'10.00','fileTypes=music,documents,pictures,videos,archives|specificFiles=','Media is not yet shipped',NULL,NULL,'OPEN','2010-07-01 13:57:07','2010-07-01 13:57:07'),
 (6,1,'0','External Hard Drive',90,'FedEx',2,5,2,5,NULL,'10.00','fileTypes=music,documents,pictures,videos,archives|specificFiles=','Media is not yet shipped',NULL,NULL,'OPEN','2010-07-01 16:14:23','2010-07-01 16:14:23'),
 (7,1,'0','Desktop Hard Drive',99,'FedEx',2,5,2,5,NULL,'10.00','fileTypes=music,documents,pictures,videos,archives|specificFiles=','Media is not yet shipped',NULL,NULL,'OPEN','2010-07-02 10:33:24','2010-07-02 10:33:24'),
 (8,1,'0','External Hard Drive',90,'FedEx',2,5,2,5,NULL,'10.00','fileTypes=music,documents,pictures,videos,archives|specificFiles=','Media is not yet shipped',NULL,NULL,'OPEN','2010-07-02 11:04:23','2010-07-02 11:04:23'),
 (9,1,'0','idk',90,'FedEx',2,5,2,5,NULL,'10.00','fileTypes=music,documents,pictures,videos,archives|specificFiles=','Media is not yet shipped',NULL,NULL,'OPEN','2010-07-02 13:19:29','2010-07-02 13:19:29'),
 (10,1,'0','External Hard Drive',90,'FedEx',2,5,2,5,NULL,'10.00','fileTypes=music,documents,pictures,videos,archives|specificFiles=','Media is not yet shipped',NULL,NULL,'OPEN','2010-07-02 13:52:34','2010-07-02 13:52:34'),
 (11,1,'0','External Hard Drive',90,'FedEx',2,5,2,5,NULL,'10.00','fileTypes=music,documents,pictures,videos,archives|specificFiles=','Media is not yet shipped',NULL,NULL,'OPEN','2010-07-02 14:44:01','2010-07-02 14:44:01'),
 (12,1,'0','Desktop Hard Drive',90,'FedEx',2,5,2,5,NULL,'10.00','fileTypes=music,documents,pictures,videos,archives|specificFiles=','Media is not yet shipped',NULL,NULL,'OPEN','2010-07-02 15:04:32','2010-07-02 15:04:32'),
 (13,1,'0','External Hard Drive',90,'FedEx',2,5,2,5,NULL,'10.00','fileTypes=music,documents,pictures,videos,archives|specificFiles=','Media is not yet shipped',NULL,NULL,'OPEN','2010-07-02 15:51:20','2010-07-02 15:51:20'),
 (14,1,'0','External Hard Drive',90,'FedEx',2,5,2,5,NULL,'10.00','fileTypes=music,documents,pictures,videos,archives|specificFiles=','Media is not yet shipped',NULL,NULL,'OPEN','2010-07-02 16:25:20','2010-07-02 16:25:20'),
 (15,1,'0','Laptop Hard Drive',90,'FedEx',2,5,2,5,NULL,'10.00','fileTypes=music,documents,pictures,videos,archives|specificFiles=','Media is not yet shipped',NULL,NULL,'OPEN','2010-07-06 15:30:38','2010-07-06 15:30:38'),
 (16,1,'0','External Hard Drive',90,'FedEx',2,5,2,5,NULL,'10.00','fileTypes=music,documents,pictures,videos,archives|specificFiles=','Media is not yet shipped',NULL,NULL,'OPEN','2010-07-06 15:40:04','2010-07-06 15:40:04'),
 (17,1,'0','External Hard Drive',90,'FedEx',2,5,2,5,NULL,'10.00','fileTypes=music,documents,pictures,videos,archives|specificFiles=','Media is not yet shipped',NULL,NULL,'OPEN','2010-07-06 15:45:23','2010-07-06 15:45:23'),
 (18,1,'0','External Hard Drive',90,'FedEx',2,5,2,5,NULL,'10.00','fileTypes=music,documents,pictures,videos,archives|specificFiles=','Media is not yet shipped',NULL,NULL,'OPEN','2010-07-07 10:26:47','2010-07-07 10:26:47'),
 (19,1,'0','External Hard Drive',90,'FedEx',2,5,2,5,NULL,'10.00','fileTypes=music,documents,pictures,videos,archives|specificFiles=','Media is not yet shipped',NULL,NULL,'OPEN','2010-07-08 15:21:30','2010-07-08 15:21:30'),
 (20,1,'0','USB Stick',90,'FedEx',2,5,2,5,NULL,'10.00','fileTypes=music,documents,pictures,videos,archives|specificFiles=','Media is not yet shipped',NULL,NULL,'OPEN','2010-07-08 15:22:51','2010-07-08 15:22:51'),
 (21,1,'0','External Hard Drive',90,'FedEx',2,5,2,5,NULL,'10.00','fileTypes=music,documents,pictures,videos,archives|specificFiles=','Media is not yet shipped',NULL,NULL,'OPEN','2010-07-08 15:24:58','2010-07-08 15:24:58'),
 (22,1,'0','Desktop Hard Drive',90,'FedEx',2,5,2,5,NULL,'10.00','fileTypes=music,documents,pictures,videos,archives|specificFiles=','Media is not yet shipped',NULL,NULL,'OPEN','2010-07-08 15:25:45','2010-07-08 15:25:45'),
 (23,1,'0','Laptop Hard Drive',90,'FedEx',2,5,2,5,NULL,'10.00','fileTypes=music,documents,pictures,videos,archives|specificFiles=','Media is not yet shipped',NULL,NULL,'OPEN','2010-07-08 15:27:20','2010-07-08 15:27:20');

--
-- Definition of table `fmladmin_epc`.`users`
--

DROP TABLE IF EXISTS `fmladmin_epc`.`users`;
CREATE TABLE  `fmladmin_epc`.`users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `crypted_password` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=191145 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `fmladmin_epc`.`users`
--
INSERT INTO `fmladmin_epc`.`users` (`id`,`email`,`crypted_password`,`created_at`,`updated_at`,`first_name`,`last_name`,`admin`) VALUES 
 (1,'jaradd@gmail.com','$1$.J/EoDxq$FcWY/U72RLL5AuBBIpomK1','2010-06-04 15:36:15','2010-06-04 15:36:15','Jarad','DeLorenzo',1),
 (2,'test@test.com','$1$sgjCQwL9$E7blwM/r5LfOkRK92Gx8C0','2010-06-08 10:35:55','2010-06-08 10:35:55','Joe','Test',0),
 (191144,'jarad.delorenzo@baesystems.com','$1$Tryws2z6$In2B0gW9Gfouz8mSQQ2kU1','2010-06-24 15:14:35','2010-06-24 15:14:35','Jarad','DeLorenzo',0);



/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
