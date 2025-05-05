-- MySQL dump 10.13  Distrib 8.0.19, for Win64 (x86_64)
--
-- Host: localhost    Database: dental_clinic
-- ------------------------------------------------------
-- Server version	8.4.0

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
-- Table structure for table `appointments`
--

DROP TABLE IF EXISTS `appointments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `appointments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `patient_id` int DEFAULT NULL,
  `doctor_id` int DEFAULT NULL,
  `service_id` int DEFAULT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `status` enum('free','scheduled','completed','unavailable') NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `patient_id` (`patient_id`),
  KEY `doctor_id` (`doctor_id`),
  KEY `service_id` (`service_id`),
  CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `users` (`id`),
  CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`doctor_id`) REFERENCES `users` (`id`),
  CONSTRAINT `appointments_ibfk_3` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `appointments`
--

LOCK TABLES `appointments` WRITE;
/*!40000 ALTER TABLE `appointments` DISABLE KEYS */;
INSERT INTO `appointments` VALUES (21,1,6,1,'2025-04-05','09:00:00','scheduled','2025-04-02 07:33:42'),(22,4,7,2,'2025-04-06','10:00:00','scheduled','2025-04-02 07:33:42'),(23,3,8,3,'2025-04-07','11:00:00','scheduled','2025-04-02 07:33:42'),(24,4,9,4,'2025-04-08','12:00:00','scheduled','2025-04-02 07:33:42'),(25,5,10,5,'2025-04-09','13:00:00','scheduled','2025-04-02 07:33:42'),(26,NULL,6,6,'2025-04-10','14:00:00','free','2025-04-02 07:33:42'),(27,NULL,7,7,'2025-04-11','15:00:00','free','2025-04-02 07:33:42'),(28,3,8,8,'2025-04-12','16:00:00','completed','2025-04-02 07:33:42'),(29,4,9,9,'2025-04-13','17:00:00','completed','2025-04-02 07:33:42'),(30,5,10,10,'2025-04-14','18:00:00','completed','2025-04-02 07:33:42'),(31,1,1,1,'2025-05-01','10:00:00','scheduled','2025-04-29 11:57:09'),(32,1,1,1,'2025-05-01','10:00:00','scheduled','2025-04-29 11:58:48'),(33,1,1,1,'2025-05-01','10:00:00','scheduled','2025-04-29 12:03:14');
/*!40000 ALTER TABLE `appointments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `medical_history`
--

DROP TABLE IF EXISTS `medical_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `medical_history` (
  `id` int NOT NULL AUTO_INCREMENT,
  `appointment_id` int NOT NULL,
  `diagnosis` text,
  `prescription` text,
  `prognosis` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `medical_history_ibfk_appointment` (`appointment_id`),
  CONSTRAINT `medical_history_ibfk_appointment` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `medical_history`
--

LOCK TABLES `medical_history` WRITE;
/*!40000 ALTER TABLE `medical_history` DISABLE KEYS */;
INSERT INTO `medical_history` VALUES (21,28,'Tooth Decay','Fluoride toothpaste, regular flossing','Good with treatment','2025-04-02 07:37:49'),(22,29,'Gingivitis','Antibacterial mouthwash','Requires follow-up','2025-04-02 07:37:49'),(23,30,'Root Canal Infection','Antibiotics, painkillers','Moderate recovery','2025-04-02 07:37:49');
/*!40000 ALTER TABLE `medical_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reviews` (
  `id` int NOT NULL AUTO_INCREMENT,
  `history_id` int NOT NULL,
  `rating` tinyint NOT NULL,
  `comment` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_history_review` (`history_id`),
  CONSTRAINT `reviews_ibfk_history` FOREIGN KEY (`history_id`) REFERENCES `medical_history` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reviews_chk_1` CHECK ((`rating` between 1 and 5))
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reviews`
--

LOCK TABLES `reviews` WRITE;
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
INSERT INTO `reviews` VALUES (1,21,5,'Great treatment! My tooth feels much better.','2025-04-02 08:08:21'),(2,22,4,'Good advice, but the mouthwash is strong.','2025-04-02 08:08:21'),(3,23,3,'Pain relief was slow, but overall okay.','2025-04-02 08:08:21');
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `services` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` text,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `services`
--

LOCK TABLES `services` WRITE;
/*!40000 ALTER TABLE `services` DISABLE KEYS */;
INSERT INTO `services` VALUES (1,'Teeth Cleaning','Updated Description','cleaning.jpg','2025-04-02 07:29:03'),(2,'Cavity Filling','Fill cavities with composite resin or amalgam.','filling.jpg','2025-04-02 07:29:03'),(3,'Tooth Extraction','Removal of severely damaged teeth.','extraction.jpg','2025-04-02 07:29:03'),(4,'Braces Installation','Orthodontic treatment for misaligned teeth.','braces.jpg','2025-04-02 07:29:03'),(5,'Root Canal','Treatment for infected tooth pulp.','root_canal.jpg','2025-04-02 07:29:03'),(6,'Dental Implants','Surgical placement of artificial teeth.','implants.jpg','2025-04-02 07:29:03'),(7,'Teeth Whitening','Cosmetic procedure to whiten teeth.','whitening.jpg','2025-04-02 07:29:03'),(8,'Gum Disease Treatment','Treatment for periodontitis and gingivitis.','gum_disease.jpg','2025-04-02 07:29:03'),(9,'Pediatric Checkup','Routine dental examination for children.','pediatric.jpg','2025-04-02 07:29:03'),(10,'Veneers','Thin porcelain covers for discolored or damaged teeth.','veneers.jpg','2025-04-02 07:29:03'),(11,'Test Service','Test Description','test.jpg','2025-04-29 11:57:09'),(12,'Test Service','Test Description','test.jpg','2025-04-29 11:58:48'),(13,'Test Service','Test Description','test.jpg','2025-04-29 12:03:14');
/*!40000 ALTER TABLE `services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `role` enum('patient','doctor') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'patient',
  `specialization` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'edina','jamakovic','edina.jamakovic@stu.ibu.edu.ba','1234','+1112223333','patient',NULL,'2025-03-26 09:13:24'),(3,'John','Doe','john.doe@example.com','hashedpass1','1234567890','patient',NULL,'2025-04-02 07:28:59'),(4,'Jane','Smith','jane.smith@example.com','hashedpass2','2345678901','patient',NULL,'2025-04-02 07:28:59'),(5,'Alice','Brown','alice.brown@example.com','hashedpass3','3456789012','patient',NULL,'2025-04-02 07:28:59'),(6,'Bob','White','bob.white@example.com','hashedpass4','4567890123','patient',NULL,'2025-04-02 07:28:59'),(7,'Emma','Davis','emma.davis@example.com','hashedpass5','5678901234','patient',NULL,'2025-04-02 07:28:59'),(8,'Dr. Mark','Taylor','mark.taylor@example.com','hashedpass6','6789012345','doctor','Orthodontist','2025-04-02 07:28:59'),(9,'Dr. Olivia','Wilson','olivia.wilson@example.com','hashedpass7','7890123456','doctor','Pediatric Dentist','2025-04-02 07:28:59'),(10,'Dr. Henry','Moore','henry.moore@example.com','hashedpass8','8901234567','doctor','Endodontist','2025-04-02 07:28:59'),(22,'Test','Patient','test.patient@example.com','$2y$10$oJiQO7hk.3qjZxKAWEIWNOTb2IRVA033S75LhO/nfztEUuIGH0ySO','+1234567890','patient',NULL,'2025-04-29 12:03:14'),(23,'Test','Doctor','test.doctor@example.com','$2y$10$lQ602fs73dTqwTkGdLnPhOYpRuaaw43pv2GFXqQns3xJX.src1VWK','+1987654321','doctor','General Dentistry','2025-04-29 12:03:14');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'dental_clinic'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-04-29 14:09:29
