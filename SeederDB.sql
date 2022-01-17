-- MySQL dump 10.13  Distrib 8.0.26, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: fisiopask
-- ------------------------------------------------------
-- Server version	5.7.33

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Dumping data for table `affected_areas`
--

LOCK TABLES `affected_areas` WRITE;
/*!40000 ALTER TABLE `affected_areas` DISABLE KEYS */;
INSERT INTO `affected_areas` VALUES (1,'Superior','Cabeza','2022-01-12 04:14:37','2022-01-12 04:14:37'),(2,'Superior','Espalda','2022-01-12 04:14:44','2022-01-12 04:14:44'),(3,'Inferior','Rodilla','2022-01-12 04:14:50','2022-01-12 04:14:50');
/*!40000 ALTER TABLE `affected_areas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `analyses`
--

LOCK TABLES `analyses` WRITE;
/*!40000 ALTER TABLE `analyses` DISABLE KEYS */;
INSERT INTO `analyses` VALUES (1,'Hemoglobina','Analisis de Sangre','2022-01-12 04:14:04','2022-01-12 04:14:04'),(2,'Rayos X','Resonancia bla bla','2022-01-12 04:14:21','2022-01-12 04:14:21');
/*!40000 ALTER TABLE `analyses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `appointments`
--

LOCK TABLES `appointments` WRITE;
/*!40000 ALTER TABLE `appointments` DISABLE KEYS */;
/*!40000 ALTER TABLE `appointments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `assistants`
--

LOCK TABLES `assistants` WRITE;
/*!40000 ALTER TABLE `assistants` DISABLE KEYS */;
/*!40000 ALTER TABLE `assistants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `diagnostics`
--

LOCK TABLES `diagnostics` WRITE;
/*!40000 ALTER TABLE `diagnostics` DISABLE KEYS */;
INSERT INTO `diagnostics` VALUES (1,'CIE-10','Dermatitis','2022-01-12 04:18:11','2022-01-12 04:18:11'),(2,'CIE-11','Escoleosis','2022-01-12 04:18:23','2022-01-12 04:18:23');
/*!40000 ALTER TABLE `diagnostics` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `doctor_doctor_specialty`
--

LOCK TABLES `doctor_doctor_specialty` WRITE;
/*!40000 ALTER TABLE `doctor_doctor_specialty` DISABLE KEYS */;
INSERT INTO `doctor_doctor_specialty` VALUES (1,1);
/*!40000 ALTER TABLE `doctor_doctor_specialty` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `doctor_specialties`
--

LOCK TABLES `doctor_specialties` WRITE;
/*!40000 ALTER TABLE `doctor_specialties` DISABLE KEYS */;
INSERT INTO `doctor_specialties` VALUES (1,'Traumatología','2022-01-12 04:06:23','2022-01-12 04:06:23'),(2,'Masaje','2022-01-12 04:06:23','2022-01-12 04:06:23'),(3,'Radiofrecuencia','2022-01-12 04:06:23','2022-01-12 04:06:23');
/*!40000 ALTER TABLE `doctor_specialties` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `doctors`
--

LOCK TABLES `doctors` WRITE;
/*!40000 ALTER TABLE `doctors` DISABLE KEYS */;
INSERT INTO `doctors` VALUES (1,2,'Fabian','Del Castillo','2000-12-31','M','934094501','1','77035606',1,NULL,'2022-01-12 04:07:36','2022-01-12 04:10:09');
/*!40000 ALTER TABLE `doctors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `families`
--

LOCK TABLES `families` WRITE;
/*!40000 ALTER TABLE `families` DISABLE KEYS */;
INSERT INTO `families` VALUES (1,'Terapia Física','2022-01-12 04:11:24','2022-01-12 04:11:24'),(2,'Estética','2022-01-12 04:11:29','2022-01-12 04:11:29');
/*!40000 ALTER TABLE `families` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `history_groups`
--

LOCK TABLES `history_groups` WRITE;
/*!40000 ALTER TABLE `history_groups` DISABLE KEYS */;
/*!40000 ALTER TABLE `history_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `history_treatments`
--

LOCK TABLES `history_treatments` WRITE;
/*!40000 ALTER TABLE `history_treatments` DISABLE KEYS */;
/*!40000 ALTER TABLE `history_treatments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `medical_histories`
--

LOCK TABLES `medical_histories` WRITE;
/*!40000 ALTER TABLE `medical_histories` DISABLE KEYS */;
/*!40000 ALTER TABLE `medical_histories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `medical_revisions`
--

LOCK TABLES `medical_revisions` WRITE;
/*!40000 ALTER TABLE `medical_revisions` DISABLE KEYS */;
/*!40000 ALTER TABLE `medical_revisions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_resets_table',1),(3,'2014_10_12_200000_add_two_factor_columns_to_users_table',1),(4,'2019_08_19_000000_create_failed_jobs_table',1),(5,'2019_12_14_000001_create_personal_access_tokens_table',1),(6,'2021_08_26_002923_create_sessions_table',1),(7,'2021_08_28_191534_create_doctors_table',1),(8,'2021_08_30_185316_create_permission_tables',1),(9,'2021_08_31_142741_create_schedules_table',1),(10,'2021_09_01_130523_create_patients_table',1),(11,'2021_09_02_114248_create_offices_table',1),(12,'2021_09_03_181328_create_doctor_specialties_table',1),(13,'2021_09_17_144238_create_appointments_table',1),(14,'2021_10_27_122547_create_patient_histories_table',1),(15,'2021_10_29_104121_create_payment_methods_table',1),(16,'2021_10_29_113551_create_families_table',1),(17,'2021_11_03_115240_create_subfamilies_table',1),(18,'2021_11_03_121819_create_schedule_freezes_table',1),(19,'2021_11_03_122321_create_rates_table',1),(20,'2021_11_03_202005_create_patient_rates_table',1),(21,'2021_11_03_211520_create_patient_payments_table',1),(22,'2021_11_10_132148_create_patient_payment_requests_table',1),(23,'2021_12_11_153730_create_workspaces_table',1),(24,'2021_12_12_112421_create_surveys_table',1),(25,'2021_12_19_113230_create_diagnostics_table',1),(26,'2021_12_19_115147_create_treatments_table',1),(27,'2021_12_19_115210_create_analysis_table',1),(28,'2021_12_19_115244_create_affected_areas_table',1),(29,'2021_12_20_104206_create_medical_histories_table',1),(30,'2021_12_20_120607_create_history_groups_table',1),(31,'2021_12_20_121342_create_medical_revisions_table',1),(32,'2021_12_24_163333_create_assistants_table',1),(33,'2021_12_26_183812_create_recommendations_table',1),(34,'2022_01_06_115544_create_history_treatments_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `model_has_permissions`
--

LOCK TABLES `model_has_permissions` WRITE;
/*!40000 ALTER TABLE `model_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `model_has_roles`
--

LOCK TABLES `model_has_roles` WRITE;
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;
INSERT INTO `model_has_roles` VALUES (1,'App\\Models\\User',1),(2,'App\\Models\\User',2);
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `offices`
--

LOCK TABLES `offices` WRITE;
/*!40000 ALTER TABLE `offices` DISABLE KEYS */;
INSERT INTO `offices` VALUES (1,'San Isidro','Av. José Galvez Barnechea 248',NULL,'2022-01-12 04:06:23','2022-01-12 04:07:49'),(2,'Primavera','Av. Primavera 256',NULL,'2022-01-12 04:07:58','2022-01-12 04:07:58');
/*!40000 ALTER TABLE `offices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `patient_histories`
--

LOCK TABLES `patient_histories` WRITE;
/*!40000 ALTER TABLE `patient_histories` DISABLE KEYS */;
/*!40000 ALTER TABLE `patient_histories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `patient_payment_requests`
--

LOCK TABLES `patient_payment_requests` WRITE;
/*!40000 ALTER TABLE `patient_payment_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `patient_payment_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `patient_payments`
--

LOCK TABLES `patient_payments` WRITE;
/*!40000 ALTER TABLE `patient_payments` DISABLE KEYS */;
/*!40000 ALTER TABLE `patient_payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `patient_rates`
--

LOCK TABLES `patient_rates` WRITE;
/*!40000 ALTER TABLE `patient_rates` DISABLE KEYS */;
/*!40000 ALTER TABLE `patient_rates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `patients`
--

LOCK TABLES `patients` WRITE;
/*!40000 ALTER TABLE `patients` DISABLE KEYS */;
/*!40000 ALTER TABLE `patients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `payment_methods`
--

LOCK TABLES `payment_methods` WRITE;
/*!40000 ALTER TABLE `payment_methods` DISABLE KEYS */;
INSERT INTO `payment_methods` VALUES (1,'Efectivo',1,'2022-01-12 04:06:23','2022-01-12 04:06:23'),(2,'Tarjeta crédito / débito',1,'2022-01-12 04:06:23','2022-01-12 04:06:23'),(3,'Transferencia Bancaria',1,'2022-01-12 04:06:23','2022-01-12 04:06:23');
/*!40000 ALTER TABLE `payment_methods` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `rates`
--

LOCK TABLES `rates` WRITE;
/*!40000 ALTER TABLE `rates` DISABLE KEYS */;
INSERT INTO `rates` VALUES (1,'10 sesiones terapia de rehabilitación x',1500.00,0,10,1,NULL,'2022-01-12 04:12:14','2022-01-12 04:12:14'),(2,'10 sesiones de inyección de peptonas',500.00,0,10,2,NULL,'2022-01-12 04:12:44','2022-01-12 04:12:44');
/*!40000 ALTER TABLE `rates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `recommendations`
--

LOCK TABLES `recommendations` WRITE;
/*!40000 ALTER TABLE `recommendations` DISABLE KEYS */;
INSERT INTO `recommendations` VALUES (1,'Facebook','2022-01-12 04:10:34','2022-01-12 04:10:55',NULL),(2,'Familia/Amigo de Socio','2022-01-12 04:10:42','2022-01-12 04:10:42',NULL),(3,'Busqueda en Google','2022-01-12 04:10:49','2022-01-12 04:10:49',NULL),(4,'Instagram','2022-01-12 04:10:59','2022-01-12 04:10:59',NULL);
/*!40000 ALTER TABLE `recommendations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `role_has_permissions`
--

LOCK TABLES `role_has_permissions` WRITE;
/*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'admin','web','2022-01-12 04:06:23','2022-01-12 04:06:23'),(2,'doctor','web','2022-01-12 04:06:23','2022-01-12 04:06:23'),(3,'assistant','web','2022-01-12 04:06:23','2022-01-12 04:06:23'),(4,'patient','web','2022-01-12 04:06:23','2022-01-12 04:06:23');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `schedule_freezes`
--

LOCK TABLES `schedule_freezes` WRITE;
/*!40000 ALTER TABLE `schedule_freezes` DISABLE KEYS */;
/*!40000 ALTER TABLE `schedule_freezes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `schedules`
--

LOCK TABLES `schedules` WRITE;
/*!40000 ALTER TABLE `schedules` DISABLE KEYS */;
INSERT INTO `schedules` VALUES (1,1,1,'09:00','10:30','1','2022-01-12 04:09:04','2022-01-12 04:09:04'),(2,1,1,'09:00','10:30','3','2022-01-12 04:09:04','2022-01-12 04:09:04'),(3,1,1,'09:00','10:30','5','2022-01-12 04:09:04','2022-01-12 04:09:04'),(4,1,1,'09:45','11:15','1','2022-01-12 04:09:25','2022-01-12 04:09:25'),(5,1,1,'09:45','11:15','3','2022-01-12 04:09:25','2022-01-12 04:09:25'),(6,1,1,'09:45','11:15','5','2022-01-12 04:09:25','2022-01-12 04:09:25'),(7,1,2,'09:00','10:30','2','2022-01-12 04:09:39','2022-01-12 04:09:39'),(8,1,2,'09:00','10:30','4','2022-01-12 04:09:39','2022-01-12 04:09:39'),(9,1,2,'09:45','11:15','2','2022-01-12 04:09:50','2022-01-12 04:09:50'),(10,1,2,'09:45','11:15','4','2022-01-12 04:09:50','2022-01-12 04:09:50');
/*!40000 ALTER TABLE `schedules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('L1ElgbwAJtaA20JzfPtPo2bjJhPUWPVJttcnHfEo',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.71 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiWVYwdkV2RjhFQjNrcWNBNGhWSXNiZkVCUGo0Q2ZZdlpjUDFNNFlTbiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0NzoiaHR0cDovL2Zpc2lvcGFzay50ZXN0L2Rhc2hib2FyZC9hbmFseXNpcy9jcmVhdGUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1641982432),('UVt7PF0SIInvjA4MqF9DBn0QTnyJIaqUKl1bK34M',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.71 Safari/537.36','YTo4OntzOjY6Il90b2tlbiI7czo0MDoiaFRIUHBSMnZ5b2Y5MlhQQjBBWVZBeklHeG4xcUdkTVNCaVZJWWhVcyI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI3OiJodHRwOi8vZmlzaW9wYXNrLnRlc3QvbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjU6ImZsYXNoIjthOjA6e31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6MTc6InBhc3N3b3JkX2hhc2hfd2ViIjtzOjYwOiIkMnkkMTAkSzlDNGJyUjFIaFpSbjVXREVrQTVxZUdjVjYxOFh0TWZ5ZVhtdERxeGg1Mzg2blFGY2hab3UiO3M6MjE6InBhc3N3b3JkX2hhc2hfc2FuY3R1bSI7czo2MDoiJDJ5JDEwJEs5QzRiclIxSGhaUm41V0RFa0E1cWVHY1Y2MThYdE1meWVYbXREcXhoNTM4Nm5RRmNoWm91Ijt9',1641982838);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `subfamilies`
--

LOCK TABLES `subfamilies` WRITE;
/*!40000 ALTER TABLE `subfamilies` DISABLE KEYS */;
INSERT INTO `subfamilies` VALUES (1,'Paquete Multisesiones',1,'2022-01-12 04:11:41','2022-01-12 04:11:41'),(2,'Paquete Multisesiones',2,'2022-01-12 04:11:50','2022-01-12 04:11:50');
/*!40000 ALTER TABLE `subfamilies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `surveys`
--

LOCK TABLES `surveys` WRITE;
/*!40000 ALTER TABLE `surveys` DISABLE KEYS */;
/*!40000 ALTER TABLE `surveys` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `treatments`
--

LOCK TABLES `treatments` WRITE;
/*!40000 ALTER TABLE `treatments` DISABLE KEYS */;
INSERT INTO `treatments` VALUES (1,'Electroterapia','Bla bla bla bla','2022-01-12 04:19:39','2022-01-12 04:19:45'),(2,'Ultrasonido','Bla bla bla','2022-01-12 04:19:52','2022-01-12 04:19:52'),(3,'Magneto Terapia','Bla bla bla','2022-01-12 04:20:01','2022-01-12 04:20:01');
/*!40000 ALTER TABLE `treatments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Administrador Principal','admin@admin.com',NULL,'$2y$10$K9C4brR1HhZRn5WDEkA5qeGcV618XtMfyeXmtDqxh5386nQFchZou',NULL,NULL,NULL,NULL,NULL,NULL,'2022-01-12 04:06:23','2022-01-12 04:06:23'),(2,'Fabian','fabian@gmail.com',NULL,'$2y$10$UZAOCjUrqXZyAe49eyBwlecq4tDdrNt6MzbOBIevycjODzI8e0md.',NULL,NULL,NULL,NULL,NULL,NULL,'2022-01-12 04:07:36','2022-01-12 04:07:36');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `workspaces`
--

LOCK TABLES `workspaces` WRITE;
/*!40000 ALTER TABLE `workspaces` DISABLE KEYS */;
INSERT INTO `workspaces` VALUES (1,'Gimnasio','Atrás de la casa de fabian',1,NULL,NULL,'2022-01-12 04:08:18','2022-01-12 04:08:18'),(2,'Cubiculo 2','Delante de la casa de fabian, dando vuelta en la izquierda',2,NULL,NULL,'2022-01-12 04:08:46','2022-01-12 04:08:46');
/*!40000 ALTER TABLE `workspaces` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-01-12 11:22:10
