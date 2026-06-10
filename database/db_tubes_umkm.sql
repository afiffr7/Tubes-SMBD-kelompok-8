-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: db_tubes
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
-- Table structure for table `jenis`
--

DROP TABLE IF EXISTS `jenis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jenis` (
  `id_jenis` int(11) NOT NULL AUTO_INCREMENT,
  `nama_jenis` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_jenis`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jenis`
--

LOCK TABLES `jenis` WRITE;
/*!40000 ALTER TABLE `jenis` DISABLE KEYS */;
INSERT INTO `jenis` VALUES (1,'Makanan'),(2,'Minuman'),(3,'Topping');
/*!40000 ALTER TABLE `jenis` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `metode_pembayaran`
--

DROP TABLE IF EXISTS `metode_pembayaran`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `metode_pembayaran` (
  `id_metode` int(11) NOT NULL AUTO_INCREMENT,
  `nama_pembayaran` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_metode`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `metode_pembayaran`
--

LOCK TABLES `metode_pembayaran` WRITE;
/*!40000 ALTER TABLE `metode_pembayaran` DISABLE KEYS */;
INSERT INTO `metode_pembayaran` VALUES (1,'Cash'),(2,'Qris'),(3,'Transfer');
/*!40000 ALTER TABLE `metode_pembayaran` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mitra`
--

DROP TABLE IF EXISTS `mitra`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mitra` (
  `id_mitra` int(11) NOT NULL AUTO_INCREMENT,
  `nama_mitra` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_mitra`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mitra`
--

LOCK TABLES `mitra` WRITE;
/*!40000 ALTER TABLE `mitra` DISABLE KEYS */;
INSERT INTO `mitra` VALUES (1,'Gojek'),(2,'Shopee'),(3,'Grab');
/*!40000 ALTER TABLE `mitra` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mitra_umkm`
--

DROP TABLE IF EXISTS `mitra_umkm`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mitra_umkm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_umkm` int(11) NOT NULL,
  `id_mitra` int(11) NOT NULL,
  `link_mitra` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_umkm` (`id_umkm`),
  KEY `id_mitra` (`id_mitra`),
  CONSTRAINT `mitra_umkm_ibfk_1` FOREIGN KEY (`id_umkm`) REFERENCES `umkm` (`id_umkm`) ON UPDATE CASCADE,
  CONSTRAINT `mitra_umkm_ibfk_2` FOREIGN KEY (`id_mitra`) REFERENCES `mitra` (`id_mitra`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mitra_umkm`
--

LOCK TABLES `mitra_umkm` WRITE;
/*!40000 ALTER TABLE `mitra_umkm` DISABLE KEYS */;
INSERT INTO `mitra_umkm` VALUES (1,5,1,'https://gofood.co.id/bandung/restaurant/martabak-super-mas-aji-berlian-raya-9b1949cc-a448-4253-8952-8561967e096b'),(2,5,2,'https://r.grab.com/g/6-20260607_192338_7d104ed8ac394b748145041044deb81f_MEXMPS-6-CZLTN2MBL3AAET'),(3,5,3,'https://shopee.co.id/universal-link/now-food/shop/20075924?deep_and_deferred=1&shareChannel=copy_link'),(4,6,1,'https://food.grab.com/id/id/restaurant/ice-cream-pisang-ijo-mas-ang-jl-permata-raya-cimahi-delivery/6-C4MEUFEUTTE3RA'),(5,6,3,'https://gofood.co.id/bandung/restaurant/es-pisang-ijo-permata-tani-mulya-c60e34f7-08cd-46ec-b44f-67df4074b16c'),(6,7,1,'https://gofood.co.id/bandung/restaurant/kebab-sultan-h-gofur-2ea13579-1c5c-413e-8f39-33e3ce408632'),(7,7,2,'https://r.grab.com/g/6-20260607_200005_7d104ed8ac394b748145041044deb81f_MEXMPS-6-C22URALHLCKTTT'),(8,7,3,'https://shopee.co.id/universal-link/now-food/shop/20113279?deep_and_deferred=1&shareChannel=copy_link'),(9,8,1,'https://gofood.co.id/bandung/restaurant/gehu-pedas-permata-dan-soto-ayam-permata-raya-86411e42-3090-4adc-b5cb-0486d4b1e247'),(10,9,1,'https://gofood.co.id/bandung/restaurant/ayam-ceria-komp-permata-3b5af64c-9a67-416a-b918-b1bcf0098c86'),(11,9,2,'https://food.grab.com/id/en/restaurant/ayam-ceria-tanimulya-delivery/6-C6D2FAKWJ7NZEJ?sourceID=20260607_193608_7d104ed8ac394b748145041044deb81f_MEXMPS'),(12,9,3,'https://shopee.co.id/universal-link/now-food/shop/21519050?deep_and_deferred=1&shareChannel=copy_link'),(13,10,1,'https://gofood.co.id/bandung/restaurant/nasi-goreng-mas-dado-permata-raya-d33f147a-4146-4c65-b8b7-108c5022d82e'),(14,10,2,NULL),(15,10,3,NULL),(16,11,1,'https://gofood.co.id/bandung/restaurant/mie-koplo-permata-cimahi-ef43b2ae-9998-49c0-ac86-312e4b03e0c8'),(17,11,3,'https://r.grab.com/g/6-20260607_193948_7d104ed8ac394b748145041044deb81f_MEXMPS-6-C251GPWWVT4XET'),(18,12,1,'https://gofood.co.id/bandung/restaurant/ayam-gepuk-pak-gembus-cimahi-4884d18f-8fe1-4ef2-be64-b04cd77c01ea'),(19,12,2,'https://food.grab.com/id/id/restaurant/ayam-gepuk-pak-gembus-permata-cimahi-delivery/6-C4J3TEBBNKVKGT'),(20,12,3,'https://shopee.co.id/universal-link/now-food/shop/21310295?deep_and_deferred=1&shareChannel=copy_link'),(21,13,1,'https://gofood.co.id/bandung/restaurant/sate-padang-ajo-manih-cimahi-9956336f-e79c-4be4-aad1-6e099a5c7d0e'),(22,13,2,'https://r.grab.com/g/6-20260607_194641_7d104ed8ac394b748145041044deb81f_MEXMPS-IDGFSTI000016s5'),(23,13,3,'https://shopee.co.id/universal-link/now-food/shop/1251068?deep_and_deferred=1&shareChannel=copy_link'),(24,14,1,'https://gofood.co.id/bandung/restaurant/baso-mas-ipin-5-jln-sadarmanah-cimahi-cimahi-5de60597-cfea-439f-8a74-a7eebf08b995'),(25,14,2,'https://food.grab.com/id/id/restaurant/mie-baso-mas-ipin-permata-cimahi-tanimulya-delivery/6-C3KEDBJUBCAGJ6'),(26,14,3,'https://shopee.co.id/universal-link/now-food/shop/20360858?deep_and_deferred=1&shareChannel=copy_link'),(27,15,1,'https://food.grab.com/id/id/restaurant/mie-baso-mas-ipin-permata-cimahi-tanimulya-delivery/6-C3KEDBJUBCAGJ6'),(28,15,2,NULL),(29,16,1,'https://gofood.co.id/bandung/restaurant/bondon-crispy-permata-cimahi-33b0e78b-9cd2-4e54-be15-87b7312c061d'),(30,17,1,'https://gofood.co.id/bandung/restaurant/nasi-bakar-permata-jl-kecubung-no-56-sukamenak-4673b26b-4613-4537-a921-ae926b4aa5a7'),(31,17,2,NULL),(32,18,1,'https://gofood.co.id/bandung/restaurant/cilor-sahati-permata-cimahi-e57916d6-e19e-40d3-ba42-6ff68b28455d'),(33,19,1,'https://gofood.co.id/bandung/restaurant/milkqu-cimahi-28870054-f46b-45c5-a19a-c640b5bf763d'),(34,19,2,NULL),(35,20,1,'https://gofood.co.id/bandung/restaurant/bakmi-bang-jo-permata-raya-1-blok-f1-no7-60a1aa2a-7ccc-489d-902f-cd44804d5181'),(36,20,2,NULL),(37,23,1,'https://gofood.co.id/bandung/restaurant/nasi-goreng-mas-lim-permata-9e430b79-c459-4f09-998c-53dbc8f0e7da'),(38,24,1,'https://gofood.co.id/bandung/restaurant/bakso-permata-permata-biru-3689a929-3b5c-4f13-9260-ac8866bc8d2c'),(39,25,1,'https://gofood.co.id/bandung/restaurant/tempe-mendoan-plat-r-perum-permata-c44c80e5-096a-417c-89f9-862b24ba67f9'),(40,26,1,'https://gofood.co.id/bandung/restaurant/ketoprak-jakarta-zi-permata-17836556-0db3-49d5-999e-8bd9adf7085d'),(41,27,1,'https://gofood.co.id/bandung/restaurant/lontong-sayur-padang-uni-rika-4e9b9fc4-9a30-4795-b9ff-e5047d112aa5'),(42,29,1,'https://gofood.co.id/bandung/restaurant/ketoprak-om-deden-permata-1010f526-8994-4fbe-9cb3-8a94ebc3cbd4'),(43,29,3,'https://food.grab.com/id/id/restaurant/rmh-ketoprak-om-deden-tanimulya-delivery/6-C7XWEPACDFUEE6');
/*!40000 ALTER TABLE `mitra_umkm` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pembayaran_umkm`
--

DROP TABLE IF EXISTS `pembayaran_umkm`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pembayaran_umkm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_umkm` int(11) NOT NULL,
  `id_metode` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_umkm` (`id_umkm`),
  KEY `id_metode` (`id_metode`),
  CONSTRAINT `pembayaran_umkm_ibfk_1` FOREIGN KEY (`id_umkm`) REFERENCES `umkm` (`id_umkm`) ON UPDATE CASCADE,
  CONSTRAINT `pembayaran_umkm_ibfk_2` FOREIGN KEY (`id_metode`) REFERENCES `metode_pembayaran` (`id_metode`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pembayaran_umkm`
--

LOCK TABLES `pembayaran_umkm` WRITE;
/*!40000 ALTER TABLE `pembayaran_umkm` DISABLE KEYS */;
INSERT INTO `pembayaran_umkm` VALUES (1,1,1),(2,1,2),(3,1,3),(4,2,1),(5,2,2),(6,3,1),(7,3,2),(8,4,1),(9,4,2),(10,5,1),(11,5,2),(12,5,3),(13,6,1),(14,6,2),(15,7,1),(16,7,2),(17,7,3),(18,8,1),(19,8,2),(20,9,1),(21,10,1),(22,10,2),(23,10,3),(24,11,1),(25,11,2),(26,12,1),(27,12,2),(28,12,3),(29,13,1),(30,13,2),(31,14,1),(32,14,2),(33,14,3),(34,15,1),(35,15,2),(36,15,3),(37,16,1),(38,16,2),(39,17,1),(40,17,2),(41,18,1),(42,18,2),(43,19,1),(44,19,2),(45,20,1),(46,20,2),(47,21,1),(48,22,1),(49,23,1),(50,23,2),(51,24,1),(52,24,2),(53,25,1),(54,25,2),(55,26,1),(56,26,2),(57,27,1),(58,27,2),(59,28,1),(60,29,1),(61,29,2),(62,29,3),(63,30,1),(64,30,2),(65,30,3);
/*!40000 ALTER TABLE `pembayaran_umkm` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `produk`
--

DROP TABLE IF EXISTS `produk`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `produk` (
  `id_produk` int(11) NOT NULL AUTO_INCREMENT,
  `id_umkm` int(11) NOT NULL,
  `nama_produk` varchar(255) DEFAULT NULL,
  `id_jenis` int(11) NOT NULL,
  `harga` decimal(11,2) DEFAULT NULL,
  `id_varian` int(11) NOT NULL,
  PRIMARY KEY (`id_produk`),
  KEY `id_umkm` (`id_umkm`),
  KEY `produk_ibfk2` (`id_jenis`),
  KEY `produk_ibfk3` (`id_varian`),
  CONSTRAINT `produk_ibfk2` FOREIGN KEY (`id_jenis`) REFERENCES `jenis` (`id_jenis`) ON UPDATE CASCADE,
  CONSTRAINT `produk_ibfk3` FOREIGN KEY (`id_varian`) REFERENCES `varian_rasa` (`id_rasa`) ON UPDATE CASCADE,
  CONSTRAINT `produk_ibfk_1` FOREIGN KEY (`id_umkm`) REFERENCES `umkm` (`id_umkm`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=572 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `produk`
--

LOCK TABLES `produk` WRITE;
/*!40000 ALTER TABLE `produk` DISABLE KEYS */;
INSERT INTO `produk` VALUES (1,1,'Bakpao ayam',1,7000.00,1),(2,1,'Bakpao Coklat',1,5000.00,1),(3,1,'Bakpao Keju',1,5000.00,1),(4,1,'Bakpao Kacang ijo',1,5000.00,1),(5,1,'Bakpao Kacang tanah',1,5000.00,1),(6,1,'Bakpao Kelapa',1,5000.00,1),(7,1,'Bakpao Ketan item',1,5000.00,1),(8,1,'Bakpao Durian',1,5000.00,1),(9,1,'Bakpao Strawberry',1,5000.00,1),(10,1,'Bakpao Blueberry',1,5000.00,1),(11,4,'cireng sapi',1,3000.00,2),(12,4,'cireng ayam',1,3000.00,2),(13,4,'cireng kornet',1,2500.00,2),(14,4,'cireng abon',1,2500.00,2),(15,4,'cireng keju',1,2500.00,1),(16,8,'Gehu Pedas (pcs)',1,7000.00,3),(17,8,'Paket Tahu Isi Pedas',1,50000.00,3),(18,8,'Pisang Aroma',1,3000.00,1),(19,8,'Sweets Roll Chesee Sweet Roll Cheese',1,3000.00,1),(20,8,'Soto Ayam Kampung Permata',1,15000.00,2),(21,8,'Nasi Putih',1,5000.00,6),(22,8,'Paket Hemat Mendoan Sambal Merah Tempe Sambal',1,50000.00,3),(23,8,'Paket Oplosan Tahu Mendoan Tahu Tempe',1,50000.00,2),(24,8,'Tempe Mendoan Antri',1,5000.00,2),(25,9,'Pepes Usus',1,4500.00,2),(26,9,'Sate Usus',1,2000.00,2),(27,9,'1 Ekor Bakar Ayam Pejantan',1,86000.00,2),(28,9,'Dada Bakar Ayam Pejantan',1,21500.00,2),(29,9,'Paha Bakar Ayam Pejantan',1,21500.00,2),(30,9,'1 Ekor Ayam Pejantan (Goreng)',1,86000.00,2),(31,9,'Dada Goreng Ayam Pejantan',1,21500.00,2),(32,9,'Paha Goreng Ayam Pejantan',1,21500.00,2),(33,9,'Sate Kulit',1,3000.00,2),(34,9,'Ati Ampela',1,3500.00,2),(35,9,'Kepala Ayam',1,2500.00,2),(36,9,'Nasi Timbel',1,6000.00,6),(37,9,'Tahu (Bacem)',1,2000.00,2),(38,9,'Tempe Bacem',1,1500.00,2),(39,11,'Mie Ayam Rempah Biduan',1,13000.00,2),(40,11,'Mie Ayam Rempah Biduan + Bakso Ayam Bakso',1,18500.00,2),(41,11,'Mie Ayam Jamur Sawer',1,17500.00,2),(42,11,'Mie Ayam Jamur Sawer + Bakso Jamur Bakso',1,23500.00,2),(43,11,'Mie Ayam Rica Hot',1,17500.00,3),(44,11,'Mie Ayam Rica Hot + Bakso Ayam Bakso',1,23500.00,3),(45,11,'Mie Ayam Rempah Biduan + Pangsit Ayam Pangsit',1,18500.00,2),(46,11,'Mie Ayam Jamur Sawer + Pangsit Jamur Pangsit',1,23500.00,2),(47,11,'Bakso Sapi Spesial Daging Sapi',1,7000.00,2),(48,11,'Lumpia Kulit Tahu',1,13000.00,2),(49,11,'Siomay Ayam',1,13000.00,2),(50,11,'Siomay Mentai',1,15000.00,2),(51,11,'Pangsit Goreng',1,7000.00,2),(52,11,'Pangsit Rebus',1,7000.00,2),(53,11,'Kumis Naga Ayam',1,13000.00,2),(54,11,'Siomay Mozza Ayam',1,15000.00,2),(55,11,'Siomay Mozza Keju',1,15000.00,1),(56,11,'Gyoza Ayam',1,15000.00,2),(57,11,'Air Mineral',2,5000.00,6),(58,11,'Es Teh Manis',2,7000.00,1),(59,12,'Telur Dadar',1,6000.00,2),(60,12,'Sate Kulit',1,4000.00,2),(61,12,'Sate Usus',1,4000.00,2),(62,12,'Kerupuk Aci Tepung',3,1000.00,6),(63,12,'Tahu Goreng',1,3000.00,2),(64,12,'Tempe Goreng',1,3000.00,2),(65,12,'Nasi Putih',1,5000.00,6),(66,12,'Ayam Sambal Gepuk',1,18000.00,3),(67,12,'Sate Sosis',1,5500.00,2),(68,12,'Paket 1 + Sosis Jumbo Ayam Sosis',1,29500.00,2),(69,12,'Paket Komplit Ayam Usus Kulit',1,34000.00,2),(70,12,'Paket 2 Ayam Tahu Tempe',1,26000.00,2),(71,12,'Mie Goreng Ayam Gepuk',1,24000.00,2),(72,12,'Paket 1 Ayam',1,22000.00,2),(73,12,'Combi 1 (tahu) Ayam Tahu',1,23500.00,2),(74,12,'Combi 2 (tempe) Ayam Tempe',1,23500.00,2),(75,12,'Paket 1 (Tempe Kulit) Ayam Kulit',1,27500.00,2),(76,12,'Paket 1 (Tahu Usus) Ayam Usus',1,27500.00,2),(77,12,'Paket 1 (Tahu Kulit) Ayam Kulit',1,27500.00,2),(78,12,'Le-Minerale 600ml',2,4500.00,6),(79,12,'Teh Pucuk',2,5000.00,1),(80,12,'Puding Seruput / Sedot Coklat Susu Cokelat',1,10500.00,1),(81,12,'Le Minerale 330ml',2,3500.00,6),(82,13,'Paket Kenyang 20 Tusuk+kupat Daging Sapi',1,57000.00,2),(83,13,'Paket Kenyang 30 Tusuk+kupat Daging Sapi',1,77500.00,2),(84,13,'Paket Super Kenyang 50 Tusuk + Kupat Daging Sapi',1,127500.00,2),(85,13,'Paket Mukbang 100 Tusuk + Kupat Daging Sapi',1,245000.00,2),(86,13,'Sate Padang Jantung Sapi',1,29500.00,2),(87,13,'Sate Padang Daging Sapi (10 Tusuk)',1,29500.00,2),(88,13,'Sate Padang Lidah Sapi (10 Tusuk)',1,29500.00,2),(89,13,'15tusuk Daging Sapi+Kupat',1,39500.00,2),(90,13,'Sate Padang Lidah Sapi 15 + Kupat',1,38500.00,2),(91,13,'1/2porsi Daging Sapi+Kupat',1,18000.00,2),(92,13,'Ketupat',1,5000.00,6),(93,13,'Keripik Balado Singkong',3,5000.00,3),(94,13,'Kerupuk kulit',3,5000.00,2),(95,7,'Beef Kebab Small Original',1,15500.00,2),(96,7,'Beef Kebab Small Bolognese',1,17000.00,2),(97,7,'Beef Kebab Small Blackpaper',1,18000.00,3),(98,7,'Beef Kebab Small CreamyCheese',1,18000.00,1),(99,7,'Beef Kebab Small Mozarella Cheese',1,21500.00,1),(100,7,'Beef Kebab Small Spicy Barbeque',1,18000.00,3),(101,7,'Beef Kebab Big Original',1,21500.00,2),(102,7,'Beef Kebab Big Bolognese',1,23000.00,2),(103,7,'Beef Kebab Big Blackpaper',1,24000.00,3),(104,7,'Beef Kebab Big CreamyCheese',1,24000.00,1),(105,7,'Beef Kebab Big Mozarella Cheese',1,27500.00,1),(106,7,'Beef Kebab Big Spicy Barbeque',1,24000.00,3),(107,7,'Chicken Kebab Small Bolognese',1,17000.00,2),(108,7,'Chicken Kebab Small Blackpaper',1,18000.00,3),(109,7,'Chicken Kebab Small CreamyCheese',1,18000.00,1),(110,7,'Chicken Kebab Small Mozarella Cheese',1,21500.00,1),(111,7,'Chicken Kebab Big Original',1,16500.00,2),(112,7,'Chicken Kebab Big Bolognese',1,23000.00,2),(113,21,'Cireng Cireng Isi',1,3000.00,2),(114,21,'Cireng Cireng Rujak',1,5000.00,4),(115,3,'Teh Tarik Jodi',2,19000.00,1),(116,3,'Coklat Kotjok',2,22000.00,1),(117,3,'Kopi Tiam Susu',2,22000.00,5),(118,3,'Matcha Kotjok',2,22000.00,1),(119,3,'Kopi O',2,22000.00,5),(120,23,'Nasi Goreng',1,22000.00,6),(121,23,'Nasi Goreng Sosis',1,24000.00,2),(122,23,'Nasi Goreng Bakso',1,24000.00,2),(123,23,'Nasi Goreng Bakso Sosis',1,26000.00,2),(124,23,'Nasi Goreng Sepesial',1,28000.00,2),(125,23,'Nasi Mawut Biasa',1,24000.00,2),(126,23,'Nasi Mawut Sosis',1,26000.00,2),(127,23,'Nasi Mawut Baso',1,26000.00,2),(128,23,'Mie Goreng Biasa',1,23000.00,2),(129,23,'Mie Goreng Sosis',1,25000.00,2),(130,23,'Mie Goreng Baso',1,25000.00,2),(131,23,'Mie Goreng Baso Sosis',1,27000.00,2),(132,23,'Mie Goreng Spesial',1,29000.00,2),(133,23,'Mie Kuah Biasa',1,23000.00,2),(134,23,'Mie Kuah Sosis',1,25000.00,2),(135,23,'Mie Kuah Baso',1,25000.00,2),(136,23,'Mie Kuah Baso Sosis',1,27000.00,2),(137,23,'Mie Kuah Spesial',1,30000.00,2),(138,23,'Kewetiow Goreng Biasa',1,23000.00,2),(139,23,'Kewetiow Goreng Sosis',1,25000.00,2),(140,23,'Kewetiow Goreng Baso',1,25000.00,2),(141,23,'Kewetiow Goreng Baso Sosis',1,27000.00,2),(142,23,'Kewetiow Goreng Spesial',1,30000.00,2),(143,23,'Capcay Goreng Biasa',1,22000.00,2),(144,23,'Capcay Goreng Sosis',1,24000.00,2),(145,23,'Capcay Goreng Baso',1,24000.00,2),(146,23,'Capcay Goreng Baso Sosis',1,26000.00,2),(147,23,'Capcay Goreng Spesial',1,28000.00,2),(148,23,'Bihun Goreng Biasa',1,23000.00,2),(149,23,'Bihun Goreng Baso',1,25000.00,2),(150,23,'Bihun Goreng Baso Sosis',1,27000.00,2),(151,23,'Bihun Goreng Spesial',1,30000.00,2),(152,23,'Capcay Kuah Biasa',1,22000.00,2),(153,23,'Capcay Kuah Sosis',1,24000.00,2),(154,23,'Capcay Kuah Baso',1,24000.00,2),(155,23,'Capcay Kuah Baso Sosis',1,26000.00,2),(156,23,'Capcay Kuah Spesial',1,28000.00,2),(157,2,'Choco',2,12000.00,1),(158,2,'Choco Choco cheese',2,12000.00,1),(159,2,'Choco choco hazelnut',2,12000.00,1),(160,2,'Choco choco banana',2,12000.00,1),(161,2,'Choco choco oreo',2,12000.00,1),(162,2,'Fruity Strawberry',2,12000.00,1),(163,2,'fruity Manggo',2,12000.00,1),(164,2,'fruity Banana',2,12000.00,1),(165,2,'fruity Melon',2,12000.00,1),(166,2,'fruity Grape',2,12000.00,1),(167,2,'fruity Avocado',2,12000.00,1),(168,2,'Smoothies Cheesecake',2,12000.00,1),(169,2,'Smoothies Banana cheese',2,12000.00,1),(170,2,'Smoothies Taro cheese',2,12000.00,1),(171,2,'Smoothies red velvet',2,12000.00,1),(172,2,'Smoothies tiramisu',2,12000.00,1),(173,2,'Smoothies bubble gum',2,12000.00,1),(174,2,'Smoothies brown sugar latte',2,12000.00,1),(175,2,'Smoothies blackforest',2,12000.00,1),(176,2,'Coffee cappucino',2,12000.00,1),(177,2,'Coffee vanilla latte',2,12000.00,1),(178,2,'Coffee moccacino',2,12000.00,1),(179,2,'Tea thai tea',2,12000.00,1),(180,2,'Tea matcha',2,12000.00,1),(181,2,'Tea milktea',2,12000.00,1),(182,15,'Mie Baso Baso Biasa Kumplit',1,22000.00,2),(183,15,'Mie Baso Baso 1/2 Porsi',1,14000.00,2),(184,15,'Mie Baso Baso Jumbo Kumplit',1,28000.00,2),(185,15,'Mie Baso Baso Jumbo',1,24000.00,2),(186,15,'Mie Baso Baso Biasa',1,16000.00,2),(187,15,'Mie Baso Baso Iga Kumplit',1,32000.00,2),(188,15,'Mie Baso Baso Iga',1,26000.00,2),(189,15,'Mie Baso Yamin Manis Baso Biasa',1,22000.00,1),(190,15,'Mie Baso Yamin Manis Baso Jumbo',1,28000.00,1),(191,15,'Mie Ayam Biasa',1,14000.00,2),(192,15,'Mie Ayam Baso Kecil',1,20000.00,2),(193,15,'Mie Ayam Baso Jumbo',1,30000.00,2),(194,15,'Mie Ayam Baso Besar',1,25000.00,2),(195,15,'Mie Ayam Baso Iga',1,32000.00,2),(196,15,'Es Campur',2,12000.00,1),(197,15,'Es Jeruk',2,10000.00,4),(198,15,'Tebs',2,7000.00,1),(199,15,'Jus Alpukat',2,15000.00,1),(200,15,'Fruit Tea',2,9000.00,4),(201,15,'Teh Botol Sosro',2,9000.00,1),(202,15,'Pop Ice',2,8000.00,1),(203,15,'Jus Buah Naga',2,16000.00,4),(204,16,'Perkedel original',1,5000.00,2),(205,16,'Sambal Cocolan',3,0.00,3),(206,17,'Nasi Bakar Ayam Suir',1,24300.00,2),(207,17,'Nasi Bakar Cumi',1,24300.00,2),(208,17,'Nasi Bakar Tongkol Suir',1,24300.00,2),(209,17,'Nasi Liwet Sederhana',1,16200.00,2),(210,17,'Nasi Liwet Telur Dadar',1,21600.00,2),(211,17,'Nasi Liwet Ayam Goreng Paha Bawah',1,27000.00,2),(212,17,'Nasi Liwet Ayam Goreng Sayap',1,27000.00,2),(213,17,'Nasi Liwet Ayam Bakar Paha Bawah',1,27000.00,2),(214,17,'Nasi Liwet Ayam Bakar Sayap',1,27000.00,2),(215,17,'Es Teh Botol Sosro',2,8000.00,1),(216,17,'Fruit Tea',2,8000.00,4),(217,17,'Es Nutrisari Jeruk',2,8000.00,4),(218,17,'Air Mineral Botol',2,6000.00,6),(219,17,'Es Teh Manis',2,7000.00,1),(220,18,'Cilor Isi 5',1,9000.00,2),(221,18,'Cilor Isi 10',1,14000.00,2),(222,18,'Cilor Isi 15',1,19000.00,2),(223,18,'Telur Gulung Telur Isi 4',1,9000.00,2),(224,18,'Sempol Ayam Ayam Isi 5',1,9000.00,2),(225,18,'Sempol Ayam Ayam Isi 10',1,14000.00,2),(226,18,'Sempol Ayam Ayam Isi 15',1,19000.00,2),(227,26,'Ketoprak Original',1,15000.00,2),(228,26,'Ketoprak + Telor Dadar',1,21000.00,2),(229,27,'Lontong Sayur Sayur Tauco + Telor',1,25000.00,4),(230,27,'Lontong Sayur Sayur Campur Nangka + Telor',1,26000.00,2),(231,27,'Lontong Sayur Sayur Nangka',1,23500.00,2),(232,27,'Lontong Sayur Sayur Tauco',1,23500.00,4),(233,27,'Lontong Sayur Kari Ayam',1,27500.00,2),(234,27,'Lontong Sayur Kari Ayam + Telur',1,31500.00,2),(235,27,'Kerupuk Keripik Singkong Balado',3,6500.00,3),(236,27,'Kerupuk Peyek Kacang',3,7500.00,1),(237,27,'Kerupuk Peyek Udang',3,7500.00,2),(238,27,'Kerupuk Gorengan',3,4000.00,1),(239,30,'Bola Ubi Ubi 10 pcs',1,15000.00,1),(240,14,'Martabak Bangka Pisang',1,37000.00,1),(241,14,'Martabak Bangka KEJU COKLAT KACANG',1,36000.00,1),(242,14,'Martabak Bangka COKLAT',1,28000.00,1),(243,14,'Martabak Keju Coklat Keju',1,35000.00,1),(244,14,'Martabak Bangka Coklat Kacang',1,30000.00,1),(245,14,'Martabak Bangka KACANG',1,30000.00,1),(246,14,'Martabak Pisang Coklat',1,30000.00,1),(247,14,'Martabak Bangka Keju Kacang',1,37000.00,1),(248,14,'Martabak Bangka Double Keju',1,45000.00,1),(249,14,'Martabak Bangka Keju Ketan',1,35000.00,1),(250,14,'Martabak Pandan Pisang',1,30000.00,1),(251,14,'Martabak Pandan Kacang',1,35000.00,1),(252,14,'Martabak Pandan Coklat',1,32000.00,1),(253,14,'Martabak Pandan ketan',1,35000.00,1),(254,14,'Martabak Pandan Kismis',1,45000.00,1),(255,14,'Martabak Pandan Keju',1,35000.00,1),(256,14,'Martabak Pandan Double Keju',1,45000.00,1),(257,14,'Martabak Pandan Coklat Pisang',1,35000.00,1),(258,14,'Black Sweet Pisang',1,32000.00,1),(259,14,'Black Sweet Kacang',1,30000.00,1),(260,14,'Black Sweet Coklat',1,38000.00,1),(261,14,'Black Sweet Keju',1,38000.00,1),(262,14,'Black Sweet Pisang Kacang',1,35000.00,1),(263,14,'Black Sweet Coklat Kacang',1,32000.00,1),(264,14,'Black Sweet Keju Kacang',1,35000.00,1),(265,14,'Black Sweet Double Keju',1,45000.00,1),(266,14,'Black Sweet Keju Kismis',1,35000.00,1),(267,14,'Martabak telur Bebek Spesial 3 Telor',1,40000.00,2),(268,14,'Martabak telur Bebek Biasa 2 Telor',1,35000.00,2),(269,14,'Martabak telur Bebek Istimewa 4 Telor',1,45000.00,1),(270,14,'Martabak telur Bebek Super',1,50000.00,1),(271,14,'Martabak telur Ayam Spesial 3 Telor',1,35000.00,2),(272,14,'Martabak telur Ayam Biasa 2 Telor',1,32000.00,2),(273,14,'Martabak telur Ayam Istimewa 4 Telor',1,37000.00,2),(274,14,'Martabak telur Ayam Super',1,45000.00,2),(275,28,'Thai Tea Original Medium',2,6500.00,1),(276,28,'Thai Tea Original Large',2,10000.00,1),(277,28,'Green Tea Original Medium',2,6500.00,1),(278,28,'Green Tea Original Large',2,10000.00,1),(279,28,'Thai Lemon Tea Original Medium',2,6500.00,4),(280,28,'Thai Lemon Tea Original Large',2,10000.00,4),(281,28,'Thai Ovaltine Original Medium',2,9000.00,1),(282,28,'Thai Ovaltine Original Large',2,12000.00,1),(283,28,'Thai Milo Original Medium',2,10000.00,1),(284,28,'Thai Milo Original Large',2,13000.00,1),(285,28,'Choco Oreo Original Medium',2,9000.00,1),(286,28,'Choco Oreo Original Large',2,12000.00,1),(287,28,'Red Velvet Original Medium',2,9000.00,1),(288,28,'Red Velvet Original Large',2,12000.00,1),(289,28,'Vanilla Original Medium',2,9000.00,1),(290,28,'Vanilla Original Large',2,12000.00,1),(291,28,'Chocolate Original Medium',2,9000.00,1),(292,28,'Chocolate Original Large',2,12000.00,1),(293,28,'Chocolate Hazelnut Original Medium',2,9000.00,1),(294,28,'Chocolate Hazelnut Original Large',2,12000.00,1),(295,28,'Taro Original Medium',2,9000.00,1),(296,28,'Taro Original Large',2,12000.00,1),(297,28,'Dark Choco Original Medium',2,9000.00,1),(298,28,'Dark Choco Original Large',2,12000.00,1),(299,28,'Yakult Lemon',2,13000.00,4),(300,28,'Yakult Mango',2,13000.00,4),(301,28,'Yakult Lychee',2,13000.00,4),(302,28,'Thai Coffee Original Medium',2,6500.00,1),(303,28,'Thai Coffee Original Large',2,10000.00,1),(304,28,'Thai Coffee Caramel Medium',2,9000.00,1),(305,28,'Thai Coffee Caramel Large',2,12000.00,1),(306,28,'Boba Brown Sugar Original',2,13000.00,1),(307,10,'Nasi Goreng Ayam',1,28000.00,2),(308,10,'Nasi Goreng Seafood',1,31000.00,2),(309,10,'Nasi Goreng Hongkong Ayam',1,29000.00,2),(310,10,'Nasi Goreng Sapi Blackpaper',1,39600.00,3),(311,10,'Nasi Goreng Biasa',1,23000.00,2),(312,10,'Nasi Goreng Ati Ampela',1,27000.00,2),(313,10,'Nasi Goreng Special',1,31000.00,2),(314,10,'Nasi Goreng Super Kumplit',1,35000.00,2),(315,10,'Nasi Goreng Hongkong Seafood',1,44000.00,2),(316,10,'Nasi Goreng Daging Sapi',1,35000.00,2),(317,10,'Nasi Goreng Chicken Katsu',1,35000.00,2),(318,10,'Nasi Goreng Kambing',1,35000.00,2),(319,10,'Nasi Goreng Mawut Ayam',1,30000.00,2),(320,10,'Nasi Goreng Mawut Seafood',1,35000.00,2),(321,10,'Nasi Goreng Bistik Ayam',1,37000.00,2),(322,10,'Nasi Goreng Bistik Sapi',1,41000.00,2),(323,10,'Nasi Goreng Mawut Ati Ampela',1,30000.00,2),(324,10,'Capcay Kuah Capcay Kuah Ayam',1,32000.00,2),(325,10,'Capcay Kuah Capcay Kuah Seafood',1,33000.00,2),(326,10,'Capcay Kuah Capcay Kuah Kumplit',1,35000.00,2),(327,10,'Capcay Goreng Capcay Goreng Ayam',1,32000.00,2),(328,10,'Capcay Goreng Capcay Goreng Seafood',1,33000.00,2),(329,10,'Capcay Goreng Capcay Goreng Kumplit',1,35000.00,2),(330,10,'Sapo Tahu Ayam',1,36000.00,2),(331,10,'Sapo Tahu Seafood',1,40000.00,2),(332,10,'Tumis Jamur Kuping Ayam',1,28000.00,2),(333,10,'Tumis Jamur Kuping Seafood',1,33000.00,2),(334,10,'Brukoli Cah Ayam',1,27000.00,2),(335,10,'Brukoli Cah Sapi',1,30000.00,2),(336,10,'Kwetiaw Goreng Biasa',1,24000.00,2),(337,10,'Kwetiaw Goreng Ayam',1,28000.00,2),(338,10,'Kwetiaw Goreng Ati Ampela',1,28000.00,2),(339,10,'Kwetiaw Goreng Spesial',1,31000.00,2),(340,10,'Kwetiaw Goreng Seafood',1,33000.00,2),(341,10,'Kwetiaw Goreng Super Kumplit',1,33000.00,2),(342,10,'Kwetiaw Goreng Daging Sapi',1,35000.00,2),(343,10,'Kwetiaw Kuah Ayam',1,29000.00,2),(344,10,'Kwetiaw Kuah Spesial',1,31000.00,2),(345,10,'Kwetiaw Kuah Seafood',1,35000.00,2),(346,10,'Kwetiaw Siram Original',1,33000.00,2),(347,10,'Kwetiaw Sechuan Ayam',1,37000.00,3),(348,10,'Kwetiaw Sechuan Seafood',1,43000.00,3),(349,10,'Mie Goreng Biasa',1,24000.00,2),(350,10,'Mie Goreng Ayam',1,28000.00,2),(351,10,'Mie Goreng Ati Ampela',1,28000.00,2),(352,10,'Mie Goreng Spesial',1,31000.00,2),(353,10,'Mie Goreng Super Kumplit',1,35000.00,2),(354,10,'Mie Goreng Seafood',1,33000.00,2),(355,10,'Mie Goreng Daging Sapi',1,33000.00,2),(356,10,'Mie Kuah Ayam',1,30000.00,2),(357,10,'Mie Kuah Spesial',1,32000.00,2),(358,10,'Mie Kuah Seafood',1,32000.00,2),(359,10,'Mie Sechuan Ayam',1,37000.00,3),(360,10,'Mie Sechuan Seafood',1,43000.00,3),(361,10,'I Fu Mei Spesial',1,33000.00,2),(362,10,'I Fu Mei Seafood',1,35000.00,2),(363,10,'Bihun Goreng Ayam',1,29000.00,2),(364,10,'Bihun Goreng Ati Ampela',1,29000.00,2),(365,10,'Bihun Goreng Seafood',1,33000.00,2),(366,10,'Bihun Kuah Ayam',1,31000.00,2),(367,10,'Bihun Kuah Spesial',1,31000.00,2),(368,10,'Bihun Kuah Seafood',1,36000.00,2),(369,10,'Paket Nasi Sapi Black Pepper Capcay',1,44000.00,3),(370,10,'Paket Nasi Ayam Kuluyuk Capcay',1,38000.00,2),(371,10,'Paket Nasi Ayam Mentega Capcay',1,47000.00,2),(372,10,'Paket Nasi Cumi Mentega Capcay',1,38000.00,2),(373,10,'Paket Nasi Cumi Asam Manis Capcay',1,38000.00,4),(374,10,'Paket Nasi Udang Mentega Capcay',1,38000.00,2),(375,10,'Paket Nasi Udang Asam Manis Capcay',1,38000.00,4),(376,10,'Paket Nasi Bistik Ayam Capcay',1,47000.00,2),(377,10,'Paket Nasi Bistik Sapi Capcay',1,40000.00,2),(378,10,'Sapi Black Pepper',1,47000.00,3),(379,10,'Ayam Kuluyuk',1,42000.00,2),(380,10,'Ayam Saus Mentega',1,42000.00,2),(381,10,'Ayam Asam Pedas',1,42000.00,3),(382,10,'Cumi Saus Tiram',1,42000.00,2),(383,10,'Cumi Saus Mentega',1,42000.00,2),(384,10,'Cumi Asam Manis',1,42000.00,4),(385,10,'Cumi Asam Pedas',1,42000.00,3),(386,10,'Cumi Goreng Tepung',1,40000.00,6),(387,10,'Udang Goreng Mentega',1,42000.00,2),(388,10,'Udang Asam Manis',1,42000.00,4),(389,10,'Udang Asam Pedas',1,42000.00,3),(390,10,'Udang Goreng Tepung',1,40000.00,6),(391,10,'Kerapu Goreng Kering',1,32000.00,2),(392,10,'Kerapu Saus Tiram',1,34000.00,2),(393,10,'Kerapu Asam Manis',1,34000.00,4),(394,10,'Kerapu Asam Pedas',1,34000.00,3),(395,10,'Gurame Goreng Kering',1,39000.00,2),(396,10,'Gurame Saus Tiram',1,45000.00,2),(397,10,'Gurame Saus Mentega',1,45000.00,2),(398,10,'Gurame Asam Manis',1,45000.00,4),(399,10,'Gurame Asam Pedas',1,45000.00,3),(400,10,'Kerang Laut Saus Tiram',1,36000.00,2),(401,10,'Kerang Laut Asam Manis',1,36000.00,4),(402,10,'Kerang Laut Asam Pedas',1,36000.00,3),(403,10,'Kerang Hijau Saus Tiram',1,34000.00,2),(404,10,'Kerang Hijau Asam Manis',1,34000.00,4),(405,10,'Kerang Hijau Asam Pedas',1,34000.00,3),(406,10,'Fu Yung Hai Ayam',1,35000.00,2),(407,10,'Fu Yung Hai Seafood',1,39000.00,2),(408,10,'Ekstra Telur Dadar Telur Mata Sapi',1,6000.00,2),(409,10,'Ekstra Nasi Putih',1,10000.00,6),(410,5,'Martabak Manis Original Kacang',1,27500.00,1),(411,5,'Martabak Manis Original Pisang',1,27500.00,1),(412,5,'Martabak Manis Original Coklat',1,27500.00,1),(413,5,'Martabak Manis Original Kismis',1,27500.00,1),(414,5,'Martabak Manis Original Coklat Kacang',1,32000.00,1),(415,5,'Martabak Manis Original Coklat Pisang',1,32000.00,1),(416,5,'Martabak Manis Original Coklat Kismis',1,32000.00,1),(417,5,'Martabak Manis Original Keju',1,35000.00,1),(418,5,'Martabak Manis Original Keju Kacang',1,38000.00,1),(419,5,'Martabak Manis Original Keju Coklat',1,38000.00,1),(420,5,'Martabak Manis Original Keju Kismis',1,38000.00,1),(421,5,'Martabak Manis Original Keju Coklat Kacang',1,40000.00,1),(422,5,'Martabak Manis Original Keju Coklat Pisang',1,40000.00,1),(423,5,'Martabak Manis Original Keju Coklat Kismis',1,40000.00,1),(424,5,'Martabak Manis Original Kombinasi',1,40000.00,1),(425,5,'Martabak Manis Original Keju Double',1,40000.00,1),(426,5,'Martabak Ketan Original',1,27500.00,2),(427,5,'Martabak Manis Original Keju Pisang',1,38000.00,1),(428,5,'Martabak Telor Ayam Biasa Daging Sapi',1,27500.00,2),(429,5,'Martabak Telor Ayam Spesial Daging Sapi',1,35000.00,2),(430,5,'Martabak Telor Ayam Super Daging Sapi',1,55000.00,2),(431,5,'Martabak Telor Ayam Double',1,60000.00,2),(432,5,'Martabak Telor Bebek Biasa Daging Sapi',1,27500.00,2),(433,5,'Martabak Telor Bebek Spesial Daging Sapi',1,38000.00,2),(434,5,'Martabak Telor Bebek Super Daging Sapi',1,60000.00,2),(435,5,'Martabak Telor Bebek Double Daging Sapi',1,65000.00,2),(436,5,'Martabak Telor Ayam Istimewa Daging Sapi',1,45000.00,2),(437,5,'Martabak Telor Bebek Istimewa Daging Sapi',1,50000.00,2),(438,5,'Martabak Manis Red Velvet Kacang',1,27500.00,1),(439,5,'Martabak Manis Red Velvet Pisang',1,27500.00,1),(440,5,'Martabak Manis Red Velvet Coklat Pisang',1,30000.00,1),(441,5,'Martabak Manis Red Velvet Coklat Kacang',1,32000.00,1),(442,5,'Martabak Manis Red Velvet Coklat Kismis',1,32000.00,1),(443,5,'Martabak Manis Red Velvet Keju',1,35000.00,1),(444,5,'Martabak Manis Red Velvet Keju Kacang',1,38000.00,1),(445,5,'Martabak Manis Red Velvet Keju Coklat',1,38000.00,1),(446,5,'Martabak Manis Red Velvet Keju Pisang',1,38000.00,1),(447,5,'Martabak Manis Red Velvet Keju Kismis',1,38000.00,1),(448,5,'Martabak Manis Red Velvet Keju Coklat Kacang',1,40000.00,1),(449,5,'Martabak Manis Red Velvet Keju Coklat Pisang',1,40000.00,1),(450,5,'Martabak Manis Red Velvet Keju Coklat Kismis',1,40000.00,1),(451,5,'Martabak Manis Red Velvet Kombinasi',1,40000.00,1),(452,5,'Martabak Manis Red Velvet Keju Double',1,40000.00,1),(453,5,'Martabak Manis Red Velvet Coklat',1,27500.00,1),(454,5,'Martabak Manis Pandan Kacang',1,27500.00,1),(455,5,'Martabak Manis Pandan Kismis',1,28000.00,1),(456,5,'Martabak Manis Pandan Coklat Kacang',1,32000.00,1),(457,5,'Martabak Manis Pandan Pisang Coklat',1,32000.00,1),(458,5,'Martabak Manis Pandan Kismis Coklat',1,32000.00,1),(459,5,'Martabak Manis Pandan Keju',1,35000.00,1),(460,5,'Martabak Manis Pandan Keju Coklat',1,38000.00,1),(461,5,'Martabak Manis Pandan Keju Pisang',1,38000.00,1),(462,5,'Martabak Manis Pandan Keju Kismis',1,37000.00,1),(463,5,'Martabak Manis Pandan Keju Coklat Kacang',1,40000.00,1),(464,5,'Martabak Manis Pandan Keju Coklat Pisang',1,40000.00,1),(465,5,'Martabak Manis Pandan Keju Coklat Kismis',1,40000.00,1),(466,5,'Martabak Manis Pandan Keju Double',1,40000.00,1),(467,5,'Martabak Manis Pandan Kombinasi',1,40000.00,1),(468,5,'Martabak Brownis Kacang',1,27500.00,1),(469,5,'Martabak Brownis Coklat',1,27500.00,1),(470,5,'Martabak Brownis Pisang',1,27500.00,1),(471,5,'Martabak Brownis Kismis',1,27500.00,1),(472,5,'Martabak Brownis Coklat Kacang',1,32000.00,1),(473,5,'Martabak Brownis Coklat Pisang',1,32000.00,1),(474,5,'Martabak Brownis Coklat Kismis',1,32000.00,1),(475,5,'Martabak Brownis Keju',1,35000.00,1),(476,5,'Martabak Brownis Keju Coklat',1,37000.00,1),(477,5,'Martabak Brownis Keju Pisang',1,37000.00,1),(478,5,'Martabak Brownis Keju Kacang',1,37000.00,1),(479,5,'Martabak Brownis Keju Kacang Coklat',1,40000.00,1),(480,5,'Martabak Brownis Keju Coklat Pisang',1,40000.00,1),(481,5,'Martabak Brownis Keju Coklat Kismis',1,40000.00,1),(482,5,'Martabak Brownis Double Keju',1,40000.00,1),(483,5,'Martabak Brownis Kombinasi Campur',1,40000.00,1),(484,19,'Banana Milkshake (Tidak Termasuk Topping)',2,9000.00,1),(485,19,'Blackcurent (Tidak Termasuk Topping)',2,9000.00,1),(486,19,'Bubblegum (Tidak Termasuk Topping)',2,9000.00,1),(487,19,'Cappucino (Tidak Termasuk Topping)',2,9000.00,1),(488,19,'Choco Caramel (Tidak Termasuk Topping)',2,9000.00,1),(489,19,'Choco Oreo (Tidak Termasuk Topping)',2,9000.00,1),(490,19,'Green Tea (Tidak Termasuk Topping)',2,9000.00,1),(491,19,'Hazelnut (Tidak Termasuk Topping)',2,9000.00,1),(492,19,'Matcha (Tidak Termasuk Topping)',2,9000.00,1),(493,19,'Red Velvet (Tidak Termasuk Topping)',2,9000.00,1),(494,19,'Strawberry (Tidak Termasuk Topping)',2,9000.00,1),(495,19,'Taro (Tidak Termasuk Topping)',2,9000.00,1),(496,19,'Thai Tea (Tidak Termasuk Topping)',2,9000.00,1),(497,19,'Tiramisu (Tidak Termasuk Topping)',2,9000.00,1),(498,19,'Vanilla (Tidak Termasuk Topping)',2,9000.00,1),(499,19,'Milk Cereal (Honey Star/Koko Crunch/Milo)',2,11000.00,1),(500,19,'Bubble',3,2000.00,1),(501,19,'Choco Chip',3,2000.00,1),(502,19,'Jelly',3,2000.00,1),(503,19,'Oreo',3,2000.00,1),(504,19,'Honey Star',3,3000.00,1),(505,19,'Koko Crunch',3,3000.00,1),(506,19,'Marshmallow',3,3000.00,1),(507,19,'Milo Cereal',3,3000.00,1),(508,19,'Pudding Puyo',3,3000.00,1),(509,6,'es pisag ijo original',1,15000.00,2),(510,6,'es pisang ijo susu coklat',1,15000.00,1),(511,6,'es pisang ijo vanila',1,15000.00,1),(512,25,'tempe mendoan',1,5000.00,2),(513,25,'gehu pedas',1,5000.00,3),(514,25,'pisang goreng',1,5000.00,1),(515,24,'Mie Ayam',1,10000.00,2),(516,24,'Mie Ayam Baso',1,16000.00,2),(517,24,'Mie Baso Kecil',1,14000.00,2),(518,24,'Yamin Baso Kecil',1,16000.00,2),(519,24,'Mie Kuah Baso Urat',1,18000.00,2),(520,24,'Yamin Baso Urat',1,20000.00,2),(521,29,'Ketoprak Original',1,21800.00,2),(522,29,'Kupat Tahu Petis',1,22900.00,2),(523,29,'Ketoprak Tahu Tanpa Lontong',1,22900.00,2),(524,29,'Kupat Tahu Spesial Khas Om Deden',1,22900.00,2),(525,29,'Ketoprak + Telor Dadar',1,26500.00,2),(526,29,'Ketoprak + Telor Rebus',1,26500.00,2),(527,29,'Paket Botram Ber2 Ketoprak Special + telor Ayam Dadar',1,52000.00,2),(528,29,'Paket Botram Ber2 Ketoprak Special + telor Ayam Rebus',1,52000.00,2),(529,29,'Paket Botram Ber4 Ketoprak Special PakeTelor Rebus',1,103000.00,2),(530,29,'Telor Ceplok',1,6500.00,1),(531,29,'Lontong',1,9000.00,2),(532,29,'Telor Rebus',1,6500.00,1),(533,29,'Telor Ayam Dadar',1,6500.00,2),(534,29,'Teh Pucuk',2,7000.00,1),(535,29,'Le Minerale 600Ml',2,6500.00,6),(536,20,'Bakso rica',1,22000.00,3),(537,20,'Bakso Rica 1/2 porsi',1,16000.00,3),(538,20,'Bakso 1 porsi',1,21000.00,2),(539,20,'Bakso 1/2 porsi',1,15000.00,2),(540,20,'Bakmi Rica Pedas',1,22000.00,3),(541,20,'Bakmi kecil',1,21000.00,2),(542,20,'Bakmi lebar',1,21000.00,2),(543,20,'Bakmi keriting',1,21000.00,2),(544,20,'yamin pangsit',1,22000.00,2),(545,20,'yamin pangsit 1/2 porsi',1,16000.00,2),(546,20,'pangsit rebus pangsit rebut 1 porsi',1,21000.00,2),(547,20,'kulit pangsit goreng',1,2000.00,2),(548,20,'Bakmi ayam siap rebus',1,21000.00,2),(549,20,'Bakmi rica siap rebus',1,22000.00,3),(550,20,'Yamin pangsit siap rebus',1,22000.00,2),(551,20,'Teh pucuk',2,5500.00,6),(552,20,'Air mineral',2,4000.00,6),(553,22,'Bakso cincang tetelan',1,13000.00,2),(554,22,'Bakso Teluyr tetelan',1,13000.00,2),(555,22,'Bakso Urat Tetelan',1,16000.00,2),(556,22,'Bakso mercon Tetelan',1,18000.00,3),(557,22,'Bakso rusuk tetelan',1,23000.00,2),(558,22,'Bakso Jumbo Tetetan',1,33000.00,2),(559,22,'Bakso 1/2 porsi',1,7000.00,2),(560,22,'Bakso cincang',1,10000.00,2),(561,22,'Bakso telur',1,10000.00,2),(562,22,'Bakso Urat',1,13000.00,2),(563,22,'Bakso mercon',1,15000.00,3),(564,22,'Bakso rusuk',1,20000.00,2),(565,22,'Bakso Jumbo',1,30000.00,2),(566,22,'Bakso lava',1,75000.00,3),(567,22,'Es Teh manis',2,4000.00,1),(568,22,'Teh manis panas',2,4000.00,1),(569,22,'Es jeruk',2,5000.00,4),(570,22,'Pop ice',2,4000.00,1),(571,22,'jeruk panas',2,5000.00,4);
/*!40000 ALTER TABLE `produk` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `umkm`
--

DROP TABLE IF EXISTS `umkm`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `umkm` (
  `id_umkm` int(11) NOT NULL AUTO_INCREMENT,
  `nama_umkm` varchar(255) DEFAULT NULL,
  `jam_buka` time DEFAULT NULL,
  `jam_tutup` time DEFAULT NULL,
  `kontak_umkm` varchar(255) DEFAULT NULL,
  `sertifikasi_halal` varchar(255) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `link_gmaps` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_umkm`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `umkm`
--

LOCK TABLES `umkm` WRITE;
/*!40000 ALTER TABLE `umkm` DISABLE KEYS */;
INSERT INTO `umkm` VALUES (1,'Bakpao vanessha','16:00:00','22:00:00','082114824050','Sudah','https://drive.google.com/file/d/1AS9mwyWLiItHL6TcBbAstDNS6Fj0ksyH/view?usp=drive_link','Permata cimahi','https://maps.app.goo.gl/vRTtZ1JQRa8UCQhx9'),(2,'winmilk','15:00:00','21:00:00',NULL,'belum','https://drive.google.com/file/d/1QQIsoTNQde-fOAYcskFY2eyjxmYlUkzl/view?usp=sharing','Permata cimahi','https://maps.app.goo.gl/SxpnoxFU4RMhrQoUA'),(3,'Teh tarik jodi','13:00:00','21:00:00','087875678829','Sudah',NULL,'Jalan permata','https://maps.app.goo.gl/yBZe7xkPhgAioSxL9'),(4,'Cireng persib','13:00:00','22:00:00',NULL,'belum',NULL,'ci','https://maps.app.goo.gl/d4E3yAitzaUyYKzp6'),(5,'Martabak Super Mas Aji','14:00:00','23:59:00','0857-2412-5645','Sudah','https://drive.google.com/file/d/1H7e8mLl_Bo07ig71kCUmbVLalzkFO2fO/view?usp=sharing','Jalan permata raya','https://maps.app.goo.gl/1xLds9pqqfUF9ot69'),(6,'es pisang ijo mas ang permata tani mulya','11:00:00','20:00:00','0851-5860-0250','Sudah','https://drive.google.com/file/d/1zWm2OWstHwbTvcyYuHEhfWDu_MCSIPtv/view?usp=drive_link','Jalan permata','https://maps.app.goo.gl/5akNZzCDRjNuuFwX6'),(7,'kebab sulltan','10:00:00','22:00:00',NULL,'Sudah','https://drive.google.com/file/d/1JmpZoJMacrqhQKudSTkAShTD-ucw3tUt/view?usp=sharing','jalan permata cimahi','https://maps.app.goo.gl/A8qEB448uzp8BkEJ8'),(8,'Gehu Pedas Dan Soto Ayam, Permata Raya','16:00:00','21:00:00',NULL,'belum','https://drive.google.com/file/d/1MTtJW8z0RASSxPs8_HRlOg1vm6zRVk6z/view?usp=drive_link','jalan permata cimahi','https://maps.app.goo.gl/tb3b9gc36L7qimkS7'),(9,'Ayam Ceria','15:00:00','22:00:00','0857-9799-0091','Sudah','https://drive.google.com/file/d/1iWori-teB0fNjegC8A71WFPhSdgd8202/view?usp=drive_link','jalan permata cimahi','https://maps.app.goo.gl/Knh6vELXgNx6X2rC7'),(10,'nasi goreng mas dado','10:00:00','23:00:00',NULL,'Sudah',NULL,'jalan permata cimahi',NULL),(11,'mie koplo','09:00:00','20:30:00',NULL,'belum','https://drive.google.com/file/d/1wjvjwj2kDT4peHX1h4JHlApTExq618QB/view?usp=drive_link','jalan permata cimahi','https://maps.app.goo.gl/KmkpXhhEkn1bSzN38'),(12,'ayam gepuk pa gembus','10:30:00','21:00:00','0821-2100-2800','Sudah','https://drive.google.com/file/d/1GlPdd4ZJiPyyd77OkST-73uNh60oxqtk/view?usp=drive_link','jalan permata cimahi','https://maps.app.goo.gl/WLrUwRbsxuyULF6J7'),(13,'sate padang ajo manih','16:00:00','22:00:00','+62 813-2018-5488.','belum','https://drive.google.com/file/d/1s06jHvkgnxXZahBkiiHoyvWaxgfZtHU1/view?usp=drive_link','jalan permata cimahi','https://maps.app.goo.gl/TNzPtxkw4tDWRzPs9'),(14,'martabak tiara','14:00:00','22:00:00',NULL,'Sudah','https://drive.google.com/file/d/1ZLGvnA6q_1N50nO03GsH2eqVQ0azkFL-/view?usp=drive_link','jalan permata cimahi','https://maps.app.goo.gl/fZVVedL97jS3JXPo9'),(15,'Bakso Mas Ipin','10:00:00','21:00:00',NULL,'Sudah','https://drive.google.com/file/d/1VvHnda3QU5j1qXVgwl6pJnVBMrtUlS6L/view?usp=drive_link','Komplek Permata Cimahi Rt.07/12 Blok V5 No 19 Tani Mulya Kec. Ngamprah','https://maps.app.goo.gl/yLXqDbv2knWWJXsp8'),(16,'Bondon Crispy','07:00:00','20:30:00',NULL,'Sudah',NULL,'Komplek Permata Cimahi, Jl.Permata Raya, Block V5 No 35, Ngamprah, Bandung','https://maps.app.goo.gl/gnq1LU1XReVDvf9g7'),(17,'Nasi Bakar Dan Nasi Liwet Permata','07:00:00','20:00:00',NULL,'belum',NULL,'Jl. Permata Raya 5 1 No 15 Ngampraah Bandung','https://maps.app.goo.gl/SCrGZEebuz3o6n9h7'),(18,'Cilor Sahati','10:00:00','20:30:00',NULL,'belum','https://drive.google.com/file/d/1eD4LgL9wi5NZrkLN-BPTZqj00-IAXjoT/view?usp=drive_link','Jl. Permata Raya No.18, Ngamprah','https://maps.app.goo.gl/Zke97zR4v6CTY2AW7'),(19,'MilkQU','11:00:00','20:00:00',NULL,'belum',NULL,'Komplek Permata Cimahi ( Depan Borma Toserba ), Ngamprah',NULL),(20,'bakmi bang jo','10:00:00','20:00:00','087875243331','Sudah',NULL,'jalan permata raya','https://maps.app.goo.gl/fn1wL1Peut6Dkjbd9'),(21,'Cireng isi AA GENDUT','15:00:00','20:20:00','081285083252','belum','https://drive.google.com/open?id=1O_yMyMdmUbGShcbvPffwf559Z9kLNgBe','Jl. Permata Raya, Tanimulya, Kec. Ngamprah, Kabupaten Bandung Barat, Jawa Barat','https://maps.app.goo.gl/Xm9dqoLP8rpmCj8t7'),(22,'Bakso Mas Sons','09:00:00','23:00:00','082127712835','Sudah','https://drive.google.com/file/d/12Okt4fQNsnoUwDtNL8T8oJXL4JJ45avg/view?usp=drive_link','Jalan Permata Raya','https://maps.app.goo.gl/2PnpSYvBrUgQ8tMk8'),(23,'Nasi goreng mas Lim','18:00:00','01:00:00','081320240708','belum','https://drive.google.com/file/d/1-G_hv4tuN8GRfpjwftNMqsbJEF8x138f/view?usp=drive_link','Jalan permata','https://maps.app.goo.gl/LoMrKsz268SKifKHA'),(24,'Bakso Malang Permata','00:00:00','20:00:00','081322295484','Sudah',NULL,'jalan permata raya','https://maps.app.goo.gl/CvFyrME9eZ6xKotf8'),(25,'Tempe Mendoan Plat R','15:00:00','23:00:00',NULL,'belum',NULL,'jalan permata raya','https://maps.app.goo.gl/VPRcStTLmWe9Epwc9'),(26,'Ketoprak Jakarta Zi','07:30:00','07:30:00',NULL,'belum','https://drive.google.com/open?id=1wYtUgToyn5h2GPi7zNrGePRW1lSy7G1q','Jl. Permata Raya No.1, Tanimulya, Kec. Ngamprah, Kabupaten Bandung Barat,','https://maps.app.goo.gl/btFAhY6ZiwAeKPeN8'),(27,'Lontong sayur Padang uni rika','05:30:00','11:00:00','082128968963','belum','https://drive.google.com/open?id=1rk7jFIR6Fh4ZPf_ztUj2nqku-VN_Eg0f','Perumahan permata Cimahi, Jl. Permata Raya No.25 blok v 5, Tanimulya, Kec. Ngamprah, Kabupaten Bandung Barat,','https://maps.app.goo.gl/9AHJcaqBwWBpXAjK9'),(28,'dondon thai tea','11:00:00','20:00:00',NULL,'belum','https://drive.google.com/open?id=15kKRKQ8iJJ6NmqPmqbQsLi0vL6Kcv6ZR','Jl. Permata Raya No.13, Tanimulya, Kec. Ngamprah, Kabupaten Bandung Barat','https://maps.app.goo.gl/1ycmfK5FUts7sD1b6'),(29,'Ketoprak Om Deden','06:15:00','12:00:00','08997018821','Sudah','https://drive.google.com/open?id=1W578loXXBsjFD0bpBFGA1Nx6jp4JVZ7B','Komplek Permata Cimahi P8 20, Jl. Permata Raya, Tanimulya, Kec. Ngamprah, Kabupaten Bandung Barat','https://maps.app.goo.gl/dKuEPv7Q1KTRF7Wf7'),(30,'Bola Ubi Permata','09:00:00','21:00:00',NULL,'Sudah','https://drive.google.com/open?id=1YAIMkt88-Lu3hV0zPYN9aiq82vaFq0mx','Jalan permata raya','https://maps.app.goo.gl/7eKMp7dnYhk1LFoVA');
/*!40000 ALTER TABLE `umkm` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `varian_rasa`
--

DROP TABLE IF EXISTS `varian_rasa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `varian_rasa` (
  `id_rasa` int(11) NOT NULL AUTO_INCREMENT,
  `nama_rasa` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_rasa`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `varian_rasa`
--

LOCK TABLES `varian_rasa` WRITE;
/*!40000 ALTER TABLE `varian_rasa` DISABLE KEYS */;
INSERT INTO `varian_rasa` VALUES (1,'Manis'),(2,'Asin'),(3,'Pedas'),(4,'Asam'),(5,'Pahit'),(6,'Netral');
/*!40000 ALTER TABLE `varian_rasa` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-06-10 15:58:49
