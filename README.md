# muhasebe

Basit bir ERP uygulaması başlangıcı. Ürünler ve müşteriler modülleri örnek olarak eklenmiştir.

## Veritabanı

`config/db.php` dosyasında bağlantı bilgileri yer alır. Aşağıdaki tabloların oluşturulması gerekir:

```sql
CREATE TABLE `urunler` (
  `id` int NOT NULL AUTO_INCREMENT,
  `urun_kodu` varchar(100) NOT NULL,
  `urun_adi` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `customers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ad` varchar(100) NOT NULL,
  `soyad` varchar(100) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `telefon` varchar(20) DEFAULT NULL,
  `adres` text,
  PRIMARY KEY (`id`)
);

CREATE TABLE `projects` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ad` varchar(255) NOT NULL,
  `aciklama` text,
  `baslangic_tarihi` date DEFAULT NULL,
  `bitis_tarihi` date DEFAULT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `stok_hareketleri` (
  `id` int NOT NULL AUTO_INCREMENT,
  `urun_id` int NOT NULL,
  `hareket_tarihi` datetime DEFAULT NULL,
  `hareket_tipi` varchar(20) DEFAULT NULL,
  `miktar` decimal(10,2) DEFAULT NULL,
  `birim` varchar(20) DEFAULT NULL,
  `belge_no` varchar(50) DEFAULT NULL,
  `kullanici` varchar(50) DEFAULT NULL,
  `aciklama` text,
  PRIMARY KEY (`id`),
  KEY `urun_id` (`urun_id`),
  CONSTRAINT `stok_hareketleri_ibfk_1` FOREIGN KEY (`urun_id`) REFERENCES `urunler` (`id`)
);
```

## Modüller

- **Ürünler:** `app/home/urunler/` klasöründeki dosyalar.
- **Müşteriler:** `app/home/customers/` klasöründeki dosyalar.
- **Projeler:** `app/home/projects/` klasöründeki dosyalar.
- **Satış Yönetimi:** `app/home/sales/` altında `offers`, `orders`, `irsaliyeler` ve `invoices` modülleri.
- **Satınalma Yönetimi:** `app/home/purchase/` altında `offers`, `orders` ve `invoices` modülleri.

Bu modüller Bootstrap tabanlı basit CRUD işlemleri gerçekleştirir.
