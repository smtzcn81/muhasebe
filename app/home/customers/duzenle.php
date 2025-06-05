<?php
require_once __DIR__ . '/../../../config/db.php';
require_once __DIR__ . '/../../classes/Customer.php';

$model = new Customer($pdo);
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$customer = $model->find($id);
if(!$customer){
    echo 'Müşteri bulunamadı';
    exit;
}
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $data = [
        'ad' => $_POST['ad'] ?? '',
        'soyad' => $_POST['soyad'] ?? '',
        'email' => $_POST['email'] ?? '',
        'telefon' => $_POST['telefon'] ?? '',
        'adres' => $_POST['adres'] ?? ''
    ];
    $model->update($id, $data);
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<title>Müşteri Düzenle</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
    <h2>Müşteri Düzenle</h2>
    <form method="post">
        <div class="mb-2">
            <label class="form-label">Ad</label>
            <input type="text" name="ad" class="form-control" value="<?= htmlspecialchars($customer['ad']) ?>" required>
        </div>
        <div class="mb-2">
            <label class="form-label">Soyad</label>
            <input type="text" name="soyad" class="form-control" value="<?= htmlspecialchars($customer['soyad']) ?>" required>
        </div>
        <div class="mb-2">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($customer['email']) ?>">
        </div>
        <div class="mb-2">
            <label class="form-label">Telefon</label>
            <input type="text" name="telefon" class="form-control" value="<?= htmlspecialchars($customer['telefon']) ?>">
        </div>
        <div class="mb-2">
            <label class="form-label">Adres</label>
            <textarea name="adres" class="form-control"><?= htmlspecialchars($customer['adres']) ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Kaydet</button>
        <a href="index.php" class="btn btn-secondary">İptal</a>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
