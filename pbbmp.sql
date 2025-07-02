-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: pbb
-- ------------------------------------------------------
-- Server version	8.0.30

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admins` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admins_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admins`
--

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
INSERT INTO `admins` VALUES (1,'Super Admin','grm@gmail.com','$2y$12$QwPaH2gt2u2T9WQwkI54C.JHtmrlle2B8Vn2m5P0lQU0Jp9hJ9vzm',1,1,NULL,'2025-06-30 23:34:27','2025-06-30 23:34:27'),(2,'Admin 2','mda@gmail.com','$2y$12$iyP6dnvjjsjnyqzu2dphIeet1Uie/Ibej5QqvsfdxriNeXukJNvg.',1,1,NULL,'2025-06-30 23:34:28','2025-06-30 23:34:28');
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bookings`
--

DROP TABLE IF EXISTS `bookings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bookings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `room_id` bigint unsigned NOT NULL,
  `booking_code` varchar(255) NOT NULL,
  `check_in` date NOT NULL,
  `check_out` date NOT NULL,
  `guests` int NOT NULL,
  `total_cost` decimal(12,2) NOT NULL,
  `extra_bed` tinyint(1) NOT NULL DEFAULT '0',
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `midtrans_order_id` varchar(255) DEFAULT NULL,
  `midtrans_transaction_id` varchar(255) DEFAULT NULL,
  `snap_token` varchar(255) DEFAULT NULL,
  `order_id` varchar(255) DEFAULT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `payment_type` varchar(255) DEFAULT NULL,
  `payment_time` timestamp NULL DEFAULT NULL,
  `notes` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bookings_booking_code_unique` (`booking_code`),
  KEY `bookings_user_id_foreign` (`user_id`),
  KEY `bookings_room_id_foreign` (`room_id`),
  CONSTRAINT `bookings_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bookings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bookings`
--

LOCK TABLES `bookings` WRITE;
/*!40000 ALTER TABLE `bookings` DISABLE KEYS */;
INSERT INTO `bookings` VALUES (1,2,1,'BK686382673D297','2025-07-02','2025-07-03',1,150000.00,1,'pending',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-06-30 23:38:31','2025-06-30 23:38:31');
/*!40000 ALTER TABLE `bookings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('4e4c56a74959cbb0223d9c83d79bb7daad4950b4','i:2;',1751351940);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `faqs`
--

DROP TABLE IF EXISTS `faqs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `faqs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `question` varchar(500) NOT NULL,
  `answer` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faqs`
--

LOCK TABLES `faqs` WRITE;
/*!40000 ALTER TABLE `faqs` DISABLE KEYS */;
INSERT INTO `faqs` VALUES (1,'Jam berapa check-in dan check-out?','Waktu check-in adalah pukul 14:00 dan check-out pukul 12:00. Check-in lebih awal atau check-out lebih lambat dapat tersedia atas permintaan dan tergantung ketersediaan.','2025-06-30 23:34:29','2025-06-30 23:34:29'),(2,'Apakah menyediakan Wi-Fi gratis?','Ya, kami menyediakan akses internet Wi-Fi berkecepatan tinggi gratis di seluruh area hotel, termasuk semua kamar tamu dan area umum.','2025-06-30 23:34:29','2025-06-30 23:34:29'),(3,'Apakah tersedia tempat parkir?','Ya, kami menyediakan parkir gratis untuk tamu kami. Area parkir aman dan dipantau 24/7 untuk ketenangan pikiran Anda.','2025-06-30 23:34:29','2025-06-30 23:34:29'),(4,'Bagaimana kebijakan pembatalan?','Reservasi dapat dibatalkan hingga 24 jam sebelum tanggal check-in tanpa penalti. Pembatalan yang dilakukan dalam 24 jam sebelum check-in akan dikenakan biaya untuk malam pertama.','2025-06-30 23:34:29','2025-06-30 23:34:29'),(5,'Apakah boleh membawa hewan peliharaan?','Maaf, kami tidak mengizinkan hewan peliharaan di hotel kami, kecuali hewan pemandu yang bersertifikat untuk tamu dengan disabilitas.','2025-06-30 23:34:29','2025-06-30 23:34:29'),(6,'Fasilitas apa saja yang tersedia di kamar?','Semua kamar dilengkapi dengan AC, TV layar datar, kamar mandi pribadi dengan air panas, perlengkapan mandi gratis, dan layanan kebersihan harian.','2025-06-30 23:34:29','2025-06-30 23:34:29');
/*!40000 ALTER TABLE `faqs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2025_06_28_031724_create_admins_table',1),(2,'2025_06_28_032145_create_users_table',1),(3,'2025_06_28_034710_create_sessions_table',1),(4,'2025_06_28_235456_create_rooms_table',1),(5,'2025_06_28_235528_create_faqs_table',1),(6,'2025_06_29_003140_create_bookings_table',1),(7,'2025_06_29_022407_create_password_reset_tokens_table',1),(8,'2025_06_29_add_quantity_to_rooms_table',1),(9,'2025_06_30_021054_create_owners_table',1),(10,'2025_06_30_021237_add_owner_relation_to_rooms_table',1),(11,'2025_06_30_054233_create_security_logs_table',1),(12,'2025_06_30_063120_create_cache_table',1),(13,'2025_06_30_063237_create_jobs_table',1),(14,'2025_06_30_085141_add_payment_fields_to_bookings_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `owners`
--

DROP TABLE IF EXISTS `owners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `owners` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `owners_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `owners`
--

LOCK TABLES `owners` WRITE;
/*!40000 ALTER TABLE `owners` DISABLE KEYS */;
INSERT INTO `owners` VALUES (1,'Super Owner','owner@bayangbrothers.com','$2y$12$Hm3GSt4F7.6Q7iJfD/jb3.PaQlWV.tiqwizb70RhQhA34iJXygiMS',1,NULL,'2025-06-30 23:34:26','2025-06-30 23:34:26'),(2,'Owner 2','owner2@bayangbrothers.com','$2y$12$REfrobNi12W9VfUsBPlCEu65nJMY.EDoKaqTwLcfBX6uR1rWrUlW6',1,NULL,'2025-06-30 23:34:27','2025-06-30 23:34:27');
/*!40000 ALTER TABLE `owners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_reset_tokens_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rooms`
--

DROP TABLE IF EXISTS `rooms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rooms` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price_weekday` decimal(10,2) NOT NULL,
  `price_weekend` decimal(10,2) NOT NULL,
  `extra_bed_price` decimal(10,2) NOT NULL,
  `max_guests` int NOT NULL DEFAULT '2',
  `total_quantity` int NOT NULL DEFAULT '1',
  `available_quantity` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rooms_owner_id_foreign` (`owner_id`),
  CONSTRAINT `rooms_owner_id_foreign` FOREIGN KEY (`owner_id`) REFERENCES `owners` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rooms`
--

LOCK TABLES `rooms` WRITE;
/*!40000 ALTER TABLE `rooms` DISABLE KEYS */;
INSERT INTO `rooms` VALUES (1,NULL,'Standard Room','Comfortable standard room with basic amenities including AC, TV, and private bathroom. Perfect for budget travelers.',150000.00,180000.00,70000.00,2,12,12,'2025-06-30 23:34:29','2025-06-30 23:34:29');
/*!40000 ALTER TABLE `rooms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `security_logs`
--

DROP TABLE IF EXISTS `security_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `security_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `event_type` varchar(255) NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  `user_agent` text,
  `url` text,
  `user_id` bigint unsigned DEFAULT NULL,
  `user_type` varchar(255) DEFAULT NULL,
  `details` json DEFAULT NULL,
  `severity` enum('low','medium','high','critical') NOT NULL DEFAULT 'medium',
  `status` enum('detected','blocked','resolved') NOT NULL DEFAULT 'detected',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `security_logs_event_type_created_at_index` (`event_type`,`created_at`),
  KEY `security_logs_ip_address_created_at_index` (`ip_address`,`created_at`),
  KEY `security_logs_severity_created_at_index` (`severity`,`created_at`),
  KEY `security_logs_user_id_user_type_index` (`user_id`,`user_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `security_logs`
--

LOCK TABLES `security_logs` WRITE;
/*!40000 ALTER TABLE `security_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `security_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text,
  `payload` longtext NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('Ejt0dfE6uXzBEnkGqOp1BDQGs9r5uzqMT9XvhJM8',2,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiSzBXT0xKbTFVVnBlSW5BcXZzSTdtWXhKQ0NacnlnU3VqU1F6Q1pOZSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wYXltZW50LzEiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO30=',1751352574);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT 'User Name',
  `contact` varchar(255) NOT NULL COMMENT 'User Contact',
  `email` varchar(255) NOT NULL COMMENT 'User Email',
  `email_verified_at` timestamp NULL DEFAULT NULL COMMENT 'Email Verification Time',
  `password` varchar(255) NOT NULL COMMENT 'User Password',
  `remember_token` varchar(100) DEFAULT NULL COMMENT 'Remember Token for User',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'User','08123456789','user@gmail.com',NULL,'$2y$12$wBtaAZ3EagFcb5VyXChk2.URbyKHpn0wVFYCblD/LPRGvX7i1iBoq',NULL,NULL,NULL),(2,'Akun Coba Coba','012345678900','acc@gmail.com',NULL,'$2y$12$cJ6LijgaOF/Im5EHvpyuBenqIbLz.hgyLovxlVkTo7LNZfcvyuGea',NULL,NULL,NULL),(4,'John Doe','081234567893','john.doe@gmail.com','2025-06-30 23:46:22','$2y$12$3aLCEiWw/k49tWkX/E/NoOBayJ.xF1ryt0Asvu5lX4/Z7dGNc21PO',NULL,NULL,NULL),(5,'Jane Smith','081234567894','jane.smith@gmail.com','2025-06-30 23:46:22','$2y$12$GGUct1TJ3JsyvZ3/dMebGuhgRHhOnbo2RRS6DcD8CcZFG4Xj0PLvq',NULL,NULL,NULL),(6,'Ahmad Wijaya','081234567895','ahmad.wijaya@gmail.com','2025-06-30 23:46:23','$2y$12$bLOfea1NDPLN8QF1hbBktOYcBwqoYdNnREYbQAa04L6Wjcd0I.H3a',NULL,NULL,NULL),(7,'Sari Dewi','081234567896','sari.dewi@gmail.com','2025-06-30 23:46:24','$2y$12$opKYJBlt8jCObNXy3VGUOeNvBVXO/s7We59YFgXZ1.Yd7sXLradCO',NULL,NULL,NULL),(8,'Budi Santoso','081234567897','budi.santoso@gmail.com','2025-06-30 23:46:24','$2y$12$8yUdR6dTT4AakihgdEmoB.yu0K0lwWTR.u/7Kyko4MO4AkhqcwWzi',NULL,NULL,NULL),(9,'Lisa Margareta','081234567898','lisa.margareta@gmail.com','2025-06-30 23:46:25','$2y$12$us6WnfgF6h85L6ir/Gfq7OCRTrBFs1WepZkjXOR6qQ1B3goUiR0iC',NULL,NULL,NULL),(10,'Rizky Pratama','081234567899','rizky.pratama@gmail.com','2025-06-30 23:46:26','$2y$12$D/dbYaJy8ZA0n3zHqI7aouyLz1eespp.3Xa5RSLEc30FfBQpxCooC',NULL,NULL,NULL),(11,'Maya Sari','081234567800','maya.sari@gmail.com','2025-06-30 23:46:27','$2y$12$sToc82qva3sxmWUr/OJwsOUY5.An.nBpHyg1iuJbpFossxeyPlXJK',NULL,NULL,NULL),(12,'Test User 1','081234567803','test1@example.com',NULL,'$2y$12$2PFBxVSbkkZb5hxWSgzpbeGoe09/N5UEZ2lqWOtoshUT4PcTBUSGe',NULL,NULL,NULL),(13,'Test User 2','081234567804','test2@example.com',NULL,'$2y$12$OVsKeur7hrrJGtoY7HNJ4eU/MC2E1mw6YR8RkjWMoz6L/16/TWZdm',NULL,NULL,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-07-01 18:44:43
