<?php
require_once __DIR__ . '/../../../config/db.php';
require_once __DIR__ . '/../../classes/SalesOffer.php';

$model = new SalesOffer($pdo);
if($_SERVER['REQUEST_METHOD']==='POST'){
    $data=[
        'baslik'=>$_POST['baslik']??'',
        'aciklama'=>$_POST['aciklama']??'',
        'tarih'=>$_POST['tarih']??'',
        'tutar'=>$_POST['tutar']??''
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
<title>Teklif Ekle</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
    <h2>Yeni Teklif</h2>
    <form method="post">
        <div class="mb-2">
            <label class="form-label">Başlık</label>
            <input type="text" name="baslik" class="form-control" required>
        </div>
        <div class="mb-2">
            <label class="form-label">Açıklama</label>
            <textarea name="aciklama" class="form-control"></textarea>
        </div>
        <div class="mb-2">
            <label class="form-label">Tarih</label>
            <input type="date" name="tarih" class="form-control">
        </div>
        <div class="mb-2">
            <label class="form-label">Tutar</label>
            <input type="number" step="0.01" name="tutar" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Kaydet</button>
        <a href="index.php" class="btn btn-secondary">İptal</a>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
