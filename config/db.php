<?php
// config/db.php

$host = 'localhost';
$db   = 'muhasebe';   // phpMyAdmin'de oluşturduğun veritabanı adı
$user = 'root';       // XAMPP'da varsayılan kullanıcı
$pass = '';           // Şifre genelde boştur, değiştirmediyseniz boş bırak
$charset = 'utf8mb4';

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO("mysql:host=$host;dbname=$db;charset=$charset", $user, $pass, $options);
} catch (\PDOException $e) {
     die('Veritabanı bağlantı hatası: ' . $e->getMessage());
}
?>
