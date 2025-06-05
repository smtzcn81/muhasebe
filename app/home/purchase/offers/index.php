<?php
require_once __DIR__ . '/../../../config/db.php';
require_once __DIR__ . '/../../classes/PurchaseOffer.php';

$model = new PurchaseOffer($pdo);
$records = $model->all();
$title = 'Satınalma Teklifleri';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<title>Satınalma Teklifleri</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
    <h2>Satınalma Teklifleri</h2>
    <a href="ekle.php" class="btn btn-success mb-3">+ Yeni Teklif</a>
    <table class="table table-bordered bg-white">
        <thead>
            <tr>
                <th>ID</th>
                <th>Başlık</th>
                <th>Tarih</th>
                <th>Tutar</th>
                <th>İşlemler</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($records as $r): ?>
            <tr>
                <td><?= $r['id'] ?></td>
                <td><?= htmlspecialchars($r['baslik']) ?></td>
                <td><?= htmlspecialchars($r['tarih']) ?></td>
                <td><?= htmlspecialchars($r['tutar']) ?></td>
                <td>
                    <a href="duzenle.php?id=<?= $r['id'] ?>" class="btn btn-primary btn-sm">Düzenle</a>
                    <a href="sil.php?id=<?= $r['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Silmek istediğinize emin misiniz?')">Sil</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <a href="../" class="btn btn-secondary">Geri</a>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
