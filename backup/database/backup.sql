-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: dairy
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `actions`
--

DROP TABLE IF EXISTS `actions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `actions` (
  `ac_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `action_name` varchar(100) NOT NULL,
  `action_url` varchar(100) NOT NULL,
  `section_list_id` int(10) unsigned NOT NULL,
  `section_id` int(10) unsigned NOT NULL,
  `modules_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`ac_id`),
  KEY `actions_section_id_foreign` (`section_id`),
  KEY `actions_modules_id_foreign` (`modules_id`),
  KEY `actions_section_list_id_foreign` (`section_list_id`),
  KEY `actions_users_id_foreign` (`user_id`),
  CONSTRAINT `actions_modules_id_foreign` FOREIGN KEY (`modules_id`) REFERENCES `modules` (`mid`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `actions_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `sections` (`sid`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `actions_section_list_id_foreign` FOREIGN KEY (`section_list_id`) REFERENCES `section_lists` (`sl_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `actions_users_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `actions`
--

LOCK TABLES `actions` WRITE;
/*!40000 ALTER TABLE `actions` DISABLE KEYS */;
INSERT INTO `actions` VALUES (1,'view','view_url',1,1,2,1),(2,'edit','edit_url',2,2,2,1),(3,'delete','delete_url',3,3,2,1);
/*!40000 ALTER TABLE `actions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (7,'2024_08_29_095127_create_modules_table',1),(8,'2024_08_29_095140_create_sections_table',1),(9,'2024_08_29_095154_create_section_lists_table',1),(10,'2024_08_29_095230_create_actions_table',1),(11,'2024_08_29_095240_create_users_table',1),(12,'2024_08_29_095251_create_users_permissions_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `modules`
--

DROP TABLE IF EXISTS `modules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `modules` (
  `mid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `module_name` varchar(100) NOT NULL,
  `module_url` varchar(100) NOT NULL,
  PRIMARY KEY (`mid`),
  UNIQUE KEY `modules_module_url_unique` (`module_url`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modules`
--

LOCK TABLES `modules` WRITE;
/*!40000 ALTER TABLE `modules` DISABLE KEYS */;
INSERT INTO `modules` VALUES (1,'vendor','vendor'),(2,'users','users'),(3,'admin','admin'),(4,'Super Admin','super_admin');
/*!40000 ALTER TABLE `modules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `section_lists`
--

DROP TABLE IF EXISTS `section_lists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `section_lists` (
  `sl_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `list_name` varchar(100) NOT NULL,
  PRIMARY KEY (`sl_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `section_lists`
--

LOCK TABLES `section_lists` WRITE;
/*!40000 ALTER TABLE `section_lists` DISABLE KEYS */;
INSERT INTO `section_lists` VALUES (1,'list1'),(2,'list2'),(3,'list3'),(4,'list4'),(5,'list5'),(6,'list6'),(7,'list7'),(8,'list8'),(9,'list9'),(10,'list10'),(11,'list11'),(12,'list11'),(13,'list12'),(14,'list13'),(15,'list14');
/*!40000 ALTER TABLE `section_lists` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sections`
--

DROP TABLE IF EXISTS `sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sections` (
  `sid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `section_name` varchar(100) NOT NULL,
  `section_url` varchar(100) NOT NULL,
  `module_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`sid`),
  KEY `sections_module_id_foreign` (`module_id`),
  CONSTRAINT `sections_module_id_foreign` FOREIGN KEY (`module_id`) REFERENCES `modules` (`mid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sections`
--

LOCK TABLES `sections` WRITE;
/*!40000 ALTER TABLE `sections` DISABLE KEYS */;
INSERT INTO `sections` VALUES (1,'dashboard','dashboard',1),(2,'dashboard','dashboard',2),(3,'dashboard','dashboard',3),(4,'master','master',2),(5,'master','master',3),(6,'transection','transection',2),(7,'transection','transection',3),(8,'report','report',1),(9,'report','report',2),(10,'report','report',3);
/*!40000 ALTER TABLE `sections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `title` varchar(50) NOT NULL,
  `language` varchar(20) NOT NULL,
  `permalink` tinyint(11) NOT NULL,
  `captcha` tinyint(11) NOT NULL,
  `name` tinyint(4) NOT NULL DEFAULT 15,
  `uname` tinyint(4) NOT NULL DEFAULT 15,
  `pincode` tinyint(6) NOT NULL DEFAULT 6,
  `locality` tinyint(100) NOT NULL DEFAULT 100,
  `aperip` int(11) NOT NULL DEFAULT 1,
  `time_zone_status` tinyint(3) unsigned DEFAULT 0,
  `time_zone` varchar(35) NOT NULL,
  `d_rate_minimum` int(11) NOT NULL DEFAULT 20,
  `d_rate_maximum` int(11) NOT NULL DEFAULT 200,
  `f_rate_minimum` float NOT NULL,
  `f_rate_maximum` float NOT NULL,
  `mf_minimum` float NOT NULL,
  `mf_maximum` float NOT NULL,
  `weight_minimum` float NOT NULL DEFAULT 0.01,
  `weight_maximum` float NOT NULL DEFAULT 500,
  `record_per_page` int(50) NOT NULL,
  `mail` int(11) NOT NULL,
  `smtp_email` int(11) NOT NULL,
  `smtp_host` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `smtp_port` int(11) NOT NULL,
  `smtp_secure` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `smtp_auth` int(11) NOT NULL,
  `smtp_username` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `smtp_password` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_provider` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES ('DoodhBazar','english',0,0,15,15,6,50,1,0,'Asia/Kolkata',20,200,1,20,1,12,0.01,500,10,0,0,'',0,'',0,'','','');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone_no` varchar(20) NOT NULL,
  `employee_id` varchar(100) DEFAULT NULL,
  `password` varchar(1000) DEFAULT NULL,
  `login_token` varchar(100) DEFAULT NULL,
  `joined` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`uid`),
  UNIQUE KEY `users_username_unique` (`username`),
  UNIQUE KEY `users_phone_no_unique` (`phone_no`),
  UNIQUE KEY `users_employee_id_unique` (`employee_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'test','test',NULL,'123454345','123234','$2y$12$pZG7I35k2LyyW65VUQgnEuhVXbI0dvZkkkA9ep0qCRoh3Y92KqT1i','sflsfjldfj84394893ruo3n34cu34u985u3945uncv','2024-08-29 16:55:48');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_permissions`
--

DROP TABLE IF EXISTS `users_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_permissions` (
  `pid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `modules_id` int(10) unsigned NOT NULL,
  `section_id` int(10) unsigned NOT NULL,
  `action_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`pid`),
  KEY `users_permissions_uid_foreign` (`uid`),
  KEY `users_permissions_modules_id_foreign` (`modules_id`),
  KEY `users_permissions_section_id_foreign` (`section_id`),
  KEY `users_permissions_action_id_foreign` (`action_id`),
  CONSTRAINT `users_permissions_action_id_foreign` FOREIGN KEY (`action_id`) REFERENCES `actions` (`ac_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `users_permissions_modules_id_foreign` FOREIGN KEY (`modules_id`) REFERENCES `modules` (`mid`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `users_permissions_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `sections` (`sid`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `users_permissions_uid_foreign` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_permissions`
--

LOCK TABLES `users_permissions` WRITE;
/*!40000 ALTER TABLE `users_permissions` DISABLE KEYS */;
INSERT INTO `users_permissions` VALUES (1,1,1,1,1),(2,1,2,2,2),(3,1,3,3,3);
/*!40000 ALTER TABLE `users_permissions` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-09-01 12:44:39
