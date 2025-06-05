--
-- Tablo yapıları
--

CREATE TABLE IF NOT EXISTS `urunler` (
  `id` int NOT NULL AUTO_INCREMENT,
  `urun_kodu` varchar(100) NOT NULL,
  `urun_adi` varchar(255) DEFAULT NULL,
  `barkod` varchar(100) DEFAULT NULL,
  `gtip_no` varchar(50) DEFAULT NULL,
  `kategori` varchar(100) DEFAULT NULL,
  `alt_kategori` varchar(100) DEFAULT NULL,
  `marka` varchar(100) DEFAULT NULL,
  `model` varchar(100) DEFAULT NULL,
  `olcu_birimi` varchar(50) DEFAULT NULL,
  `renk` varchar(50) DEFAULT NULL,
  `malzeme` varchar(100) DEFAULT NULL,
  `net_agirlik` decimal(10,3) DEFAULT NULL,
  `brut_agirlik` decimal(10,3) DEFAULT NULL,
  `paket_agirlik` decimal(10,3) DEFAULT NULL,
  `boyut_en` decimal(10,2) DEFAULT NULL,
  `boyut_boy` decimal(10,2) DEFAULT NULL,
  `boyut_yukseklik` decimal(10,2) DEFAULT NULL,
  `boyut_cap` decimal(10,2) DEFAULT NULL,
  `boyut_hacim` decimal(10,2) DEFAULT NULL,
  `mensei` varchar(100) DEFAULT NULL,
  `tedarikci` varchar(100) DEFAULT NULL,
  `alis_fiyati` decimal(10,2) DEFAULT NULL,
  `satis_fiyati` decimal(10,2) DEFAULT NULL,
  `para_birimi` varchar(10) DEFAULT NULL,
  `kdv_orani` decimal(5,2) DEFAULT NULL,
  `otv_orani` decimal(5,2) DEFAULT NULL,
  `diger_vergi_orani` decimal(5,2) DEFAULT NULL,
  `min_stok` int DEFAULT NULL,
  `max_stok` int DEFAULT NULL,
  `raf_yeri` varchar(100) DEFAULT NULL,
  `seri_no` varchar(100) DEFAULT NULL,
  `lot_no` varchar(100) DEFAULT NULL,
  `son_kullanma_tarihi` date DEFAULT NULL,
  `son_alis_tarihi` date DEFAULT NULL,
  `son_satis_tarihi` date DEFAULT NULL,
  `aciklama` text,
  `resim` varchar(255) DEFAULT NULL,
  `teknik_cizim` varchar(255) DEFAULT NULL,
  `sertifika` varchar(255) DEFAULT NULL,
  `kullanim_kilavuzu` varchar(255) DEFAULT NULL,
  `bilgi_foyu` varchar(255) DEFAULT NULL,
  `ozel_kod1` varchar(100) DEFAULT NULL,
  `ozel_kod2` varchar(100) DEFAULT NULL,
  `ozel_kod3` varchar(100) DEFAULT NULL,
  `ozel_kod4` varchar(100) DEFAULT NULL,
  `ozel_kod5` varchar(100) DEFAULT NULL,
  `ozel_kod6` varchar(100) DEFAULT NULL,
  `ozel_kod7` varchar(100) DEFAULT NULL,
  `ozel_kod8` varchar(100) DEFAULT NULL,
  `ozel_kod9` varchar(100) DEFAULT NULL,
  `ozel_kod10` varchar(100) DEFAULT NULL,
  `tedarikci_adi` varchar(255) DEFAULT NULL,
  `tedarikci_kodu` varchar(100) DEFAULT NULL,
  `musteri_adi` varchar(255) DEFAULT NULL,
  `musteri_kodu` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `urun_kodu` (`urun_kodu`)
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

