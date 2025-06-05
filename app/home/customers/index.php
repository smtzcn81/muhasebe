<?php
require_once __DIR__ . '/../../../config/db.php';
require_once __DIR__ . '/../../classes/Customer.php';

$model = new Customer($pdo);
$customers = $model->all();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<title>Müşteri Listesi</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
    <h2>Müşteri Listesi</h2>
    <a href="ekle.php" class="btn btn-success mb-3">+ Yeni Müşteri</a>
    <table class="table table-bordered bg-white">
        <thead>
            <tr>
                <th>ID</th>
                <th>Ad</th>
                <th>Soyad</th>
                <th>Email</th>
                <th>Telefon</th>
                <th>İşlemler</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($customers as $c): ?>
            <tr>
                <td><?= $c['id'] ?></td>
                <td><?= htmlspecialchars($c['ad']) ?></td>
                <td><?= htmlspecialchars($c['soyad']) ?></td>
                <td><?= htmlspecialchars($c['email']) ?></td>
                <td><?= htmlspecialchars($c['telefon']) ?></td>
                <td>
                    <a href="duzenle.php?id=<?= $c['id'] ?>" class="btn btn-primary btn-sm">Düzenle</a>
                    <a href="sil.php?id=<?= $c['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Silmek istediğinize emin misiniz?')">Sil</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
