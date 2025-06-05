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
```

## Modüller

- **Ürünler:** `app/home/urunler/` klasöründeki dosyalar.
- **Müşteriler:** `app/home/customers/` klasöründeki dosyalar.
- **Projeler:** `app/home/projects/` klasöründeki dosyalar.

Bu modüller Bootstrap tabanlı basit CRUD işlemleri gerçekleştirir.
