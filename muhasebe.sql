--
-- Tablo yapıları
--

CREATE TABLE IF NOT EXISTS `urunler` (
  `id` int NOT NULL AUTO_INCREMENT,
  `urun_kodu` varchar(100) NOT NULL,
  `urun_adi` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `customers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ad` varchar(100) NOT NULL,
  `soyad` varchar(100) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `telefon` varchar(20) DEFAULT NULL,
  `adres` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `projects` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ad` varchar(255) NOT NULL,
  `aciklama` text,
  `baslangic_tarihi` date DEFAULT NULL,
  `bitis_tarihi` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

