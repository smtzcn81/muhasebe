<?php
require_once __DIR__ . '/../../../config/db.php';
require_once __DIR__ . '/../../classes/Project.php';

$model = new Project($pdo);
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $data = [
        'ad' => $_POST['ad'] ?? '',
        'aciklama' => $_POST['aciklama'] ?? '',
        'baslangic_tarihi' => $_POST['baslangic_tarihi'] ?? '',
        'bitis_tarihi' => $_POST['bitis_tarihi'] ?? ''
    ];
    $model->create($data);
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<title>Proje Ekle</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
    <h2>Yeni Proje</h2>
    <form method="post">
        <div class="mb-2">
            <label class="form-label">Ad</label>
            <input type="text" name="ad" class="form-control" required>
        </div>
        <div class="mb-2">
            <label class="form-label">Açıklama</label>
            <textarea name="aciklama" class="form-control"></textarea>
        </div>
        <div class="mb-2">
            <label class="form-label">Başlangıç Tarihi</label>
            <input type="date" name="baslangic_tarihi" class="form-control">
        </div>
        <div class="mb-2">
            <label class="form-label">Bitiş Tarihi</label>
            <input type="date" name="bitis_tarihi" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Kaydet</button>
        <a href="index.php" class="btn btn-secondary">İptal</a>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
