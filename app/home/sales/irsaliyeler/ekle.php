<?php
require_once __DIR__ . '/../../../config/db.php';
require_once __DIR__ . '/../../classes/SalesIrsaliye.php';

$model = new SalesIrsaliye($pdo);
if($_SERVER['REQUEST_METHOD']==='POST'){
    $data=[
        'baslik'=>$_POST['baslik']??'',
        'tarih'=>$_POST['tarih']??'',
        'aciklama'=>$_POST['aciklama']??''
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
<title>İrsaliye Ekle</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
    <h2>Yeni İrsaliye</h2>
    <form method="post">
        <div class="mb-2">
            <label class="form-label">Başlık</label>
            <input type="text" name="baslik" class="form-control" required>
        </div>
        <div class="mb-2">
            <label class="form-label">Tarih</label>
            <input type="date" name="tarih" class="form-control">
        </div>
        <div class="mb-2">
            <label class="form-label">Açıklama</label>
            <textarea name="aciklama" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Kaydet</button>
        <a href="index.php" class="btn btn-secondary">İptal</a>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
