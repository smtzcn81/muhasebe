<?php
require_once __DIR__ . '/../../../config/db.php';

$hata = '';
$basari = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csv_file'])) {
    $file = $_FILES['csv_file']['tmp_name'];
    if (($handle = fopen($file, "r")) !== FALSE) {
        // İlk satırı başlık kabul ediyoruz
        $header = fgetcsv($handle, 10000, ",");
        $columns = [
            "urun_adi","urun_kodu","kategori","marka","mensei","gtip_no",
            "min_stok","max_stok","aciklama"
        ];
        $required_cols = array_slice($columns, 0, 3); // En az adı, kodu, kategori zorunlu
        if (count(array_intersect($required_cols, $header)) < count($required_cols)) {
            $hata = "CSV başlıkları eksik veya uyumsuz!";
        } else {
            $eklenen = 0;
            while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {
                // Sütun isimlerini sıraya oturt
                $row = array_combine($header, $data);
                // Sadece ana alanlar
                $stmt = $pdo->prepare("INSERT INTO urunler 
                (urun_adi, urun_kodu, kategori, marka, mensei, gtip_no, min_stok, max_stok, aciklama)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    $row["urun_adi"] ?? '',
                    $row["urun_kodu"] ?? '',
                    $row["kategori"] ?? '',
                    $row["marka"] ?? '',
                    $row["mensei"] ?? '',
                    $row["gtip_no"] ?? '',
                    $row["min_stok"] ?? 0,
                    $row["max_stok"] ?? 0,
                    $row["aciklama"] ?? '',
                ]);
                $eklenen++;
            }
            fclose($handle);
            $basari = "$eklenen ürün başarıyla eklendi!";
        }
    } else {
        $hata = "CSV dosyası açılamadı!";
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Toplu Ürün Yükle (CSV)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h3>Toplu Ürün Yükle (.csv)</h3>
        <a href="index.php" class="btn btn-secondary btn-sm mb-2">&laquo; Listeye Dön</a>
        <a href="ornek_csv.php" class="btn btn-outline-info mb-3">Örnek Excel/CSV Şablonu İndir</a>
        <?php if($hata): ?>
            <div class="alert alert-danger"><?= $hata ?></div>
        <?php elseif($basari): ?>
            <div class="alert alert-success"><?= $basari ?></div>
        <?php endif; ?>
        <form method="post" enctype="multipart/form-data" class="border p-3 rounded bg-white">
            <div class="mb-2">
                <input type="file" name="csv_file" accept=".csv" required class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Yükle</button>
        </form>
        <hr>
        <div class="alert alert-info mt-4">
            <b>Örnek CSV başlıkları (ilk satır):</b><br>
            urun_adi,urun_kodu,kategori,marka,mensei,gtip_no,min_stok,max_stok,aciklama
        </div>
    </div>
</body>
</html>
