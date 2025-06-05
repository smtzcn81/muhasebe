<?php
require_once __DIR__ . '/../../../config/db.php';

$urun_id = isset($_GET['urun_id']) ? intval($_GET['urun_id']) : 0;
if (!$urun_id) { echo "Ürün seçilmedi."; exit; }

// Ürün adı
$stmt = $pdo->prepare("SELECT urun_adi FROM urunler WHERE id=?");
$stmt->execute([$urun_id]);
$urun = $stmt->fetch();
if (!$urun) { echo "Ürün bulunamadı."; exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hareket_tipi = $_POST['hareket_tipi'];
    $miktar = floatval($_POST['miktar']);
    $birim = $_POST['birim'];
    $aciklama = $_POST['aciklama'];
    $belge_no = $_POST['belge_no'];
    $kullanici = "admin"; // giriş sistemi olursa dinamik yapılabilir

    $stmt = $pdo->prepare("INSERT INTO stok_hareketleri 
    (urun_id, hareket_tarihi, hareket_tipi, miktar, birim, aciklama, belge_no, kullanici)
    VALUES (?, NOW(), ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$urun_id, $hareket_tipi, $miktar, $birim, $aciklama, $belge_no, $kullanici]);
    header("Location: hareketler.php?urun_id=$urun_id");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM stok_hareketleri WHERE urun_id=? ORDER BY hareket_tarihi DESC");
$stmt->execute([$urun_id]);
$hareketler = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Stok Hareketleri - <?= htmlspecialchars($urun['urun_adi']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4 mb-5">
    <h3><?= htmlspecialchars($urun['urun_adi']) ?> — Stok Hareketleri</h3>
    <a href="index.php" class="btn btn-secondary btn-sm mb-2">&laquo; Ürün Listesine Dön</a>
    <form method="post" class="row g-2 border rounded bg-white p-2 mb-3">
        <div class="col-md-2">
            <select name="hareket_tipi" class="form-select form-select-sm" required>
                <option value="">Hareket Tipi</option>
                <option value="giriş">Giriş</option>
                <option value="çıkış">Çıkış</option>
                <option value="düzeltme">Düzeltme</option>
                <option value="iade">İade</option>
                <option value="üretim">Üretim</option>
            </select>
        </div>
        <div class="col-md-2"><input type="number" name="miktar" step="0.01" class="form-control form-control-sm" placeholder="Miktar" required></div>
        <div class="col-md-2"><input type="text" name="birim" class="form-control form-control-sm" placeholder="Birim (adet, kg, mt...)"></div>
        <div class="col-md-2"><input type="text" name="belge_no" class="form-control form-control-sm" placeholder="Belge No"></div>
        <div class="col-md-3"><input type="text" name="aciklama" class="form-control form-control-sm" placeholder="Açıklama"></div>
        <div class="col-md-1"><button type="submit" class="btn btn-primary btn-sm w-100">Ekle</button></div>
    </form>
    <div class="table-responsive">
    <table class="table table-bordered table-sm bg-white">
        <thead class="table-light">
            <tr>
                <th>Tarih</th>
                <th>Tip</th>
                <th>Miktar</th>
                <th>Birim</th>
                <th>Belge No</th>
                <th>Kullanıcı</th>
                <th>Açıklama</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($hareketler as $h): ?>
            <tr>
                <td><?= htmlspecialchars($h['hareket_tarihi']) ?></td>
                <td><?= htmlspecialchars($h['hareket_tipi']) ?></td>
                <td><?= htmlspecialchars($h['miktar']) ?></td>
                <td><?= htmlspecialchars($h['birim']) ?></td>
                <td><?= htmlspecialchars($h['belge_no']) ?></td>
                <td><?= htmlspecialchars($h['kullanici']) ?></td>
                <td><?= htmlspecialchars($h['aciklama']) ?></td>
            </tr>
            <?php endforeach; ?>
            <?php if(!$hareketler): ?>
            <tr><td colspan="7" class="text-center text-muted">Henüz hareket yok</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    </div>
</div>
</body>
</html>
