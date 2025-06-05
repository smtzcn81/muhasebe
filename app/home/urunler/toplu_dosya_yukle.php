<?php
$base = __DIR__ . '/../../../assets/urunler/';

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['dosyalar'])) {
    $dosyalar = $_FILES['dosyalar'];
    $basari = 0; $hata = 0;
    for ($i=0; $i < count($dosyalar['name']); $i++) {
        if ($dosyalar['error'][$i] === 0) {
            $isim = basename($dosyalar['name'][$i]);
            if (preg_match('/^([a-zA-Z0-9_-]+)\.([a-zA-Z0-9]+)$/', $isim, $m)) {
                $urun_kodu = $m[1];
                $uzanti = strtolower($m[2]);
                $hedef_klasor = $base . $uzanti . '/';
                if (!is_dir($hedef_klasor)) mkdir($hedef_klasor, 0777, true);
                $hedef_yol = $hedef_klasor . $urun_kodu . '.' . $uzanti;
                if (move_uploaded_file($dosyalar['tmp_name'][$i], $hedef_yol)) {
                    $basari++;
                } else {
                    $hata++;
                }
            } else {
                $hata++;
            }
        } else {
            $hata++;
        }
    }
    $msg = '<div class="alert alert-success">'.$basari.' dosya yüklendi. ';
    if($hata>0) $msg .= $hata.' dosya yüklenemedi!';
    $msg .= '</div>';
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Toplu Dosya Yükle (Çoklu Seçim)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h3>Toplu Dosya Yükle (Çoklu Seçim)</h3>
        <a href="index.php" class="btn btn-secondary btn-sm mb-2">&laquo; Ürün Listesine Dön</a>
        <?= $msg ?>
        <form method="post" enctype="multipart/form-data" class="border p-3 rounded bg-white">
            <input type="hidden" name="MAX_FILE_SIZE" value="256000000">
            <div class="mb-3">
                <input type="file" name="dosyalar[]" multiple required class="form-control">
                <small class="form-text text-muted">
                    Birden fazla dosya seçebilirsin (örn: Ctrl veya Shift ile). <br>
                    Dosya adları tam olarak ürün kodu ve uzantısı şeklinde olmalı!<br>
                    <b>Ör:</b> <code>123.pdf</code>, <code>123.jpg</code>, <code>234.dxf</code>, ...
                </small>
            </div>
            <button type="submit" class="btn btn-primary">Toplu Yükle</button>
        </form>
    </div>
</body>
</html>
