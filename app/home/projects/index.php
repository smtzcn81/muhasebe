<?php
require_once __DIR__ . '/../../../config/db.php';
require_once __DIR__ . '/../../classes/Project.php';

$model = new Project($pdo);
$projects = $model->all();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<title>Proje Listesi</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
    <h2>Proje Listesi</h2>
    <a href="ekle.php" class="btn btn-success mb-3">+ Yeni Proje</a>
    <table class="table table-bordered bg-white">
        <thead>
            <tr>
                <th>ID</th>
                <th>Ad</th>
                <th>Başlangıç</th>
                <th>Bitiş</th>
                <th>İşlemler</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($projects as $p): ?>
            <tr>
                <td><?= $p['id'] ?></td>
                <td><?= htmlspecialchars($p['ad']) ?></td>
                <td><?= htmlspecialchars($p['baslangic_tarihi']) ?></td>
                <td><?= htmlspecialchars($p['bitis_tarihi']) ?></td>
                <td>
                    <a href="duzenle.php?id=<?= $p['id'] ?>" class="btn btn-primary btn-sm">Düzenle</a>
                    <a href="sil.php?id=<?= $p['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Silmek istediğinize emin misiniz?')">Sil</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
