-- MySQL dump 10.13  Distrib 8.0.38, for macos14 (arm64)
--
-- Host: 127.0.0.1    Database: movierental
-- ------------------------------------------------------
-- Server version	9.2.0

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
-- Table structure for table `copies`
--

DROP TABLE IF EXISTS `copies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `copies` (
  `id` int NOT NULL AUTO_INCREMENT,
  `copy_number` varchar(45) COLLATE utf8mb3_czech_ci DEFAULT NULL,
  `status` enum('dostupné','zapůjčené','ztraceno','údržba') COLLATE utf8mb3_czech_ci NOT NULL DEFAULT 'dostupné',
  `movie_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uq_copies_number` (`copy_number`),
  KEY `idx_copies_movie` (`movie_id`),
  CONSTRAINT `fk_copies_movies` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_czech_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `copies`
--

LOCK TABLES `copies` WRITE;
/*!40000 ALTER TABLE `copies` DISABLE KEYS */;
INSERT INTO `copies` VALUES (1,'1-C1','zapůjčené',1),(2,'2-C1','dostupné',2),(3,'3-C1','dostupné',3),(4,'4-C1','dostupné',4),(5,'5-C1','dostupné',5),(6,'6-C1','dostupné',6),(7,'7-C1','dostupné',7),(8,'8-C1','dostupné',8),(9,'9-C1','dostupné',9),(10,'10-C1','dostupné',10),(11,'11-C1','dostupné',11),(12,'12-C1','dostupné',12),(13,'13-C1','dostupné',13),(14,'14-C1','dostupné',14),(15,'15-C1','dostupné',15),(16,'16-C1','dostupné',16),(17,'17-C1','dostupné',17),(18,'18-C1','dostupné',18),(19,'19-C1','dostupné',19),(20,'20-C1','dostupné',20),(21,'1-C2','dostupné',1),(22,'2-C2','dostupné',2),(23,'3-C2','dostupné',3),(24,'4-C2','dostupné',4),(25,'5-C2','dostupné',5),(26,'6-C2','dostupné',6),(27,'7-C2','dostupné',7),(28,'8-C2','dostupné',8),(29,'9-C2','dostupné',9),(30,'10-C2','zapůjčené',10),(31,'11-C2','dostupné',11),(32,'12-C2','dostupné',12),(33,'13-C2','zapůjčené',13),(34,'14-C2','dostupné',14),(35,'15-C2','dostupné',15),(36,'16-C2','dostupné',16),(37,'17-C2','dostupné',17),(38,'18-C2','zapůjčené',18),(39,'19-C2','dostupné',19),(40,'20-C2','dostupné',20),(41,'1-C3','dostupné',1),(42,'2-C3','dostupné',2),(43,'3-C3','dostupné',3),(44,'4-C3','dostupné',4),(45,'5-C3','dostupné',5),(46,'6-C3','dostupné',6),(47,'7-C3','dostupné',7),(48,'8-C3','dostupné',8),(49,'9-C3','dostupné',9),(50,'10-C3','dostupné',10),(51,'11-C3','dostupné',11),(52,'12-C3','dostupné',12),(53,'13-C3','dostupné',13),(54,'14-C3','dostupné',14),(55,'15-C3','dostupné',15),(56,'16-C3','dostupné',16),(57,'17-C3','dostupné',17),(58,'18-C3','dostupné',18),(59,'19-C3','dostupné',19),(60,'20-C3','dostupné',20);
/*!40000 ALTER TABLE `copies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `genres`
--

DROP TABLE IF EXISTS `genres`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `genres` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb3_czech_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_genres_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_czech_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `genres`
--

LOCK TABLES `genres` WRITE;
/*!40000 ALTER TABLE `genres` DISABLE KEYS */;
INSERT INTO `genres` VALUES (1,'Akční'),(8,'Animovaný'),(9,'Dokumentární'),(3,'Drama'),(10,'Fantasy'),(5,'Horor'),(2,'Komedie'),(6,'Romantický'),(4,'Sci-Fi'),(7,'Thriller');
/*!40000 ALTER TABLE `genres` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `movies`
--

DROP TABLE IF EXISTS `movies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `movies` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb3_czech_ci NOT NULL,
  `description` text COLLATE utf8mb3_czech_ci,
  `release_year` year DEFAULT NULL,
  `rental_rate` decimal(6,2) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `genre_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_movies_genre` (`genre_id`),
  CONSTRAINT `fk_movies_genres` FOREIGN KEY (`genre_id`) REFERENCES `genres` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_czech_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `movies`
--

LOCK TABLES `movies` WRITE;
/*!40000 ALTER TABLE `movies` DISABLE KEYS */;
INSERT INTO `movies` VALUES (1,'Rychle a zběsile','Bryan O\'Conner a Dom Toretto se opět setkávají v tomto napínavém pokračování, kde se rychlá auta, drsné honičky a silné přátelské vazby prolínají v neúprosném závodě proti času i nepřátelům.',2001,49.90,'2025-06-17 12:37:47',1),(2,'Smrtonosná past','John McClane, policista na dovolené, musí zachránit budovu Nakatomi Plaza před teroristy. Napínavé scény, ikonické hlášky a vánoční atmosféra dělají z „Smrtonosné pasti“ jeden z největších akčních klasik.',1988,49.90,'2025-06-17 12:37:47',1),(3,'Velký Lebowski','Jeff \"The Dude\" Lebowski se nechce zapojit do žádných dramat, ale když je omylem označen za bohatého jmenovce, ocitá se uvíznutý v absurdním řetězci událostí plných bowlingu, čaje a bizarních postav.',1998,49.90,'2025-06-17 12:37:47',2),(4,'Dopisy pro Julii','Mladá Sophie objevuje starý dopis adresovaný Julii z Itálie. Společně s místním průvodcem vyráží do malebných uliček Verony, aby našla ženu, která před lety oplakala svou dávnou lásku.',2010,49.90,'2025-06-17 12:37:47',2),(5,'Zelená míle','Paul Edgecomb, vězeňský dozorce, se setkává s nadpřirozenou silou, když přiváží na bloc Green Mile černošského obviněného Johna Coffeyho. Dotýká se tu nevinnosti, víry a zázraků v temném světě věznice.',1999,49.90,'2025-06-17 12:37:47',3),(6,'Kmotr','Klasický příběh rodiny Corleoneových, kde se moci, rodinné cti a zrady prolínají v dramatu o vzestupu a pádu mafiánského impéria vedeného ikonickou postavou Dona Vita Corleona.',1972,49.90,'2025-06-17 12:37:47',3),(7,'Interstellar','Tým badatelů v čele s Josephem Cooprem cestuje hvězdnými bránami, aby našel nový domov pro lidstvo. „Interstellar“ kombinuje emocionální příběh rodiny s vědecky podloženým dobrodružstvím mezi hvězdami.',2014,49.90,'2025-06-17 12:37:47',4),(8,'Blade Runner 2049','Padesát let po událostech prvního filmu lovec replikantů K ve společnosti bývalého detektiva Deckarda odhaluje tajemství, které ohrožuje samotnou existenci lidstva ve futuristickém Los Angeles.',2017,49.90,'2025-06-17 12:37:47',4),(9,'V zajetí démonů','Mladá Rosemary odhaluje temné tajemství své bytosti i okolí, když ztratí kontrolu nad svým vlastním životem a stane se součástí ďábelského plánu, který ji přivede na pokraj šílenství.',1973,49.90,'2025-06-17 12:37:47',5),(10,'Křik','Skupina středoškoláků čelí sériovému vrahovi v malém městečku. Napětí roste s každým zazvoněním telefonu a každý podezřelý může ukrývat krutého vraha v ikonické masce Ghostface.',1996,49.90,'2025-06-17 12:37:47',5),(11,'Notebook','Noah a Allie prožívají letní romanci plnou vášně i překážek, když jejich rodiny bojují proti jejich vztahu. „Notebook“ je dojemným příběhem, který vzdoruje času i společenským rozdílům.',2004,49.90,'2025-06-17 12:37:47',6),(12,'Pýcha a předsudek','Elizabeth Bennetová, bystrá a nezávislá mladá žena, se střetává s hrdým panem Darcym. Jejich počáteční odpor se mění ve vášnivou a nadčasovou lásku, která překoná pýchu i předsudky.',2005,49.90,'2025-06-17 12:37:47',6),(13,'Sedm','Detektiv Somerset a jeho partner Mills pronásledují sériového vraha, jehož zvrácené motivy připomínají sedm smrtelných hříchů. Každý den přináší nové hrůzné objevy, které testují jejich morální hranice.',1995,49.90,'2025-06-17 12:37:47',7),(14,'Muž na laně','V roce 1974 je Philippe Petit posedlý suprováním chůzí po laně mezi newyorskými dvojčaty. Jeho neuvěřitelný čin nejen definuje odvahu, ale ukazuje, co všechno je člověk ochoten obětovat pro svůj sen.',2008,49.90,'2025-06-17 12:37:47',7),(15,'Toy Story','Hračky v pokojíčku Andyho ožívají, když nejsou lidé přítomni. Woody, Buzz a jejich přátelé se musí naučit spolupracovat, aby ochránili své místo v srdci svého malého pána.',1995,49.90,'2025-06-17 12:37:47',8),(16,'Ledové království','Princezna Elsa ukrývá moc nad ledem, která může zničit jejich království. Její sestra Anna se vydává na nebezpečnou cestu, aby našla Elsu a zachránila zemi před věčnou zimy.',2013,49.90,'2025-06-17 12:37:47',8),(17,'Planeta Země','Tato epická série zkoumá nejkrásnější a nejdrsnější kouty naší planety. Každá epizoda odhaluje jedinečné ekosystémy a tvory, kteří dokážou přežít v extrémních podmínkách.',2006,49.90,'2025-06-17 12:37:47',9),(18,'Kód Enigmy','Sledování příběhu průlomového kódu přináší fascinující pohled na matematickou genialitu, odvahu a tajné mise, které změnily průběh druhé světové války.',2014,49.90,'2025-06-17 12:37:47',9),(19,'Pán prstenů: Společenstvo prstenu','Skupina hrdinů vyrazí na nebezpečnou cestu, aby zničila Prsten moci. Na cestě čelí drakům, orlům i temným silám, v této epické sáze o přátelství, odvaze a oběti.',2001,49.90,'2025-06-17 12:37:47',10),(20,'Harry Potter a Kámen mudrců','Mladý čaroděj Harry Potter objevuje svůj osud, když zjistí, že je slavný for his survival of a Dark Lord. Na kouzelnické škole v Bradavicích čelí přátelům i nepřátelům, kteří testují jeho statečnost.',2001,49.90,'2025-06-17 12:37:47',10);
/*!40000 ALTER TABLE `movies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `amount` decimal(7,2) DEFAULT NULL,
  `method` enum('hotově','kartou','paypal') COLLATE utf8mb3_czech_ci DEFAULT NULL,
  `payment_date` datetime DEFAULT NULL,
  `rental_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_payments_rental` (`rental_id`),
  CONSTRAINT `fk_payments_rentals` FOREIGN KEY (`rental_id`) REFERENCES `rentals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_czech_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
INSERT INTO `payments` VALUES (1,199.60,'kartou','2025-06-18 04:03:42',1);
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rentals`
--

DROP TABLE IF EXISTS `rentals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rentals` (
  `id` int NOT NULL AUTO_INCREMENT,
  `rental_date` datetime NOT NULL,
  `due_date` datetime NOT NULL,
  `return_date` datetime DEFAULT NULL,
  `rental_fee` decimal(6,2) DEFAULT NULL,
  `late_fee` decimal(6,2) DEFAULT NULL,
  `status` enum('probíhá','vráceno','po splatnosti','zrušeno') COLLATE utf8mb3_czech_ci DEFAULT 'probíhá',
  `user_id` int NOT NULL,
  `copy_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_rentals_user` (`user_id`),
  KEY `idx_rentals_copy` (`copy_id`),
  CONSTRAINT `fk_rentals_copies` FOREIGN KEY (`copy_id`) REFERENCES `copies` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_rentals_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_czech_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rentals`
--

LOCK TABLES `rentals` WRITE;
/*!40000 ALTER TABLE `rentals` DISABLE KEYS */;
INSERT INTO `rentals` VALUES (1,'2025-06-17 16:32:34','2025-06-22 23:59:59','2025-06-18 04:03:48',49.90,0.00,'vráceno',1,3),(2,'2025-06-18 04:15:29','2025-06-21 23:59:59',NULL,99.80,NULL,'probíhá',1,1),(3,'2025-06-18 04:15:41','2025-06-26 23:59:59',NULL,349.30,NULL,'probíhá',1,30),(4,'2025-06-18 04:15:54','2025-06-30 23:59:59',NULL,548.90,NULL,'probíhá',1,33),(5,'2025-06-18 04:16:21','2025-06-26 23:59:59',NULL,349.30,NULL,'probíhá',1,38);
/*!40000 ALTER TABLE `rentals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8mb3_czech_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb3_czech_ci NOT NULL,
  `login` varchar(255) COLLATE utf8mb3_czech_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb3_czech_ci NOT NULL,
  `surname` varchar(100) COLLATE utf8mb3_czech_ci NOT NULL,
  `phone` varchar(45) COLLATE utf8mb3_czech_ci NOT NULL,
  `role` enum('user','admin') COLLATE utf8mb3_czech_ci NOT NULL DEFAULT 'user',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_users_email` (`email`),
  UNIQUE KEY `uq_users_login` (`login`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_czech_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'temp@temp.cz','$2y$10$IoctB4ThRP2vqk4W.Rkt6OOJ3glKuYk2q.oHIEI46TxUkKjR3ikc2','rybaad','Adam','Ryba','789675456','user','2025-06-17 14:31:41'),(2,'admin@admin.com','$2y$10$2qz4/mvQdev45I/XhdK2IuwaAdwY84PzgC7Uhj/7n8q8BeH5WQuh2','admin','Pepik','Ruzicka','456789980','admin','2025-06-17 21:18:43');
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

-- Dump completed on 2025-06-18  5:12:22
