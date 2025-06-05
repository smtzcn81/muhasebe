<?php
require_once __DIR__ . '/../../../config/db.php';
require_once __DIR__ . '/../../classes/PurchaseOrder.php';

$model = new PurchaseOrder($pdo);
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$record = $model->find($id);
if(!$record){
    echo 'Kayıt bulunamadı';
    exit;
}
if($_SERVER['REQUEST_METHOD']==='POST'){
    $data=[
        'baslik'=>$_POST['baslik']??'',
        'tarih'=>$_POST['tarih']??'',
        'durum'=>$_POST['durum']??'',
        'tutar'=>$_POST['tutar']??''
    ];
    $model->update($id,$data);
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<title>Sipariş Düzenle</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
    <h2>Sipariş Düzenle</h2>
    <form method="post">
        <div class="mb-2">
            <label class="form-label">Başlık</label>
            <input type="text" name="baslik" class="form-control" value="<?= htmlspecialchars($record['baslik']) ?>" required>
        </div>
        <div class="mb-2">
            <label class="form-label">Tarih</label>
            <input type="date" name="tarih" class="form-control" value="<?= htmlspecialchars($record['tarih']) ?>">
        </div>
        <div class="mb-2">
            <label class="form-label">Durum</label>
            <input type="text" name="durum" class="form-control" value="<?= htmlspecialchars($record['durum']) ?>">
        </div>
        <div class="mb-2">
            <label class="form-label">Tutar</label>
            <input type="number" step="0.01" name="tutar" class="form-control" value="<?= htmlspecialchars($record['tutar']) ?>">
        </div>
        <button type="submit" class="btn btn-primary">Kaydet</button>
        <a href="index.php" class="btn btn-secondary">İptal</a>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
