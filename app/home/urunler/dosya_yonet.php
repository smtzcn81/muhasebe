<?php
$urun_kodu = isset($_GET['urun_kodu']) ? basename($_GET['urun_kodu']) : '';
if (!$urun_kodu) { echo "Ürün kodu belirtilmedi!"; exit; }

$base_dir = __DIR__ . '/../../../assets/urunler/';
$base_url = '/muhasebe/assets/urunler/';
$uzantilar = ['pdf','dxf','x_t','xt','jpg','jpeg','png','bmp','webp'];

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Çoklu dosya yükleme
    if (isset($_FILES['yeni_dosyalar'])) {
        $count = count($_FILES['yeni_dosyalar']['name']);
        $allowed = ['pdf','dxf','x_t','xt','jpg','jpeg','png','bmp','webp'];
        $basari = 0; $hata = 0;
        for($i=0;$i<$count;$i++) {
            if ($_FILES['yeni_dosyalar']['error'][$i] === 0) {
                $ad = basename($_FILES['yeni_dosyalar']['name'][$i]);
                $ext = strtolower(pathinfo($ad, PATHINFO_EXTENSION));
                if (!in_array($ext, $allowed)) { $hata++; continue; }
                $hedef_klasor = $base_dir . $ext . '/';
                if (!is_dir($hedef_klasor)) mkdir($hedef_klasor, 0777, true);
                $hedef_yol = $hedef_klasor . $urun_kodu . '.' . $ext;
                if (move_uploaded_file($_FILES['yeni_dosyalar']['tmp_name'][$i], $hedef_yol)) {
                    $basari++;
                } else { $hata++; }
            } else { $hata++; }
        }
        header("Location: dosya_yonet.php?urun_kodu=" . urlencode($urun_kodu) . "&islem=ok");
        exit;
    }
    // Dosya silme
    if (isset($_POST['sil_dosya']) && isset($_POST['uzanti'])) {
        $ext = $_POST['uzanti'];
        $ds = $urun_kodu . '.' . $ext;
        @unlink($base_dir . $ext . '/' . $ds);
        header("Location: dosya_yonet.php?urun_kodu=" . urlencode($urun_kodu) . "&islem=sil");
        exit;
    }
}

// Tüm uzantılar için ürün koduna ait dosyaları tara
$dosyalar = [];
foreach ($uzantilar as $u) {
    $klasor = $base_dir . $u . '/';
    if(is_dir($klasor)) {
        $fpath = $klasor . $urun_kodu . '.' . $u;
        if (file_exists($fpath)) {
            $dosyalar[] = [
                'ad' => $urun_kodu . '.' . $u,
                'url' => $base_url . $u . '/' . $urun_kodu . '.' . $u,
                'uzanti' => $u
            ];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($urun_kodu) ?> | Dosyalar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body { background: #fafbfc;}
    .dosya-grid { display:flex; flex-wrap:wrap; gap:24px;}
    .dosya-item { border:1px solid #ddd; border-radius:10px; width:120px; text-align:center; background:#f9f9f9; padding:10px; position:relative;}
    .dosya-item img { width:60px; height:60px; object-fit:contain; margin-bottom:5px;}
    .dosya-sil-btn { position:absolute; top:2px; right:5px; font-size:13px; color:#c00; cursor:pointer; border:none; background:none;}
    .dosya-ad { word-break:break-all; font-size:13px;}
    </style>
</head>
<body class="bg-light">
<div class="container my-4">
    <h4><?= htmlspecialchars($urun_kodu) ?> Ürününün Dosyaları</h4>
    <a href="index.php" class="btn btn-secondary btn-sm mb-3">&laquo; Listeye Dön</a>
    <?php
        if(isset($_GET["islem"]) && $_GET["islem"]=="ok") echo '<div class="alert alert-success">Yükleme başarılı.</div>';
        if(isset($_GET["islem"]) && $_GET["islem"]=="sil") echo '<div class="alert alert-danger">Dosya silindi.</div>';
        echo $msg;
    ?>
    <form method="post" enctype="multipart/form-data" class="mb-4 p-2 border rounded bg-white">
        <div class="mb-2">
            <label for="dosyaSec" class="form-label">Yeni dosyalar ekle (çoklu seçim/sürükle bırak destekler):</label>
            <input type="file" name="yeni_dosyalar[]" id="dosyaSec" class="form-control" multiple required>
        </div>
        <button type="submit" class="btn btn-primary">Yükle</button>
    </form>
    <div class="dosya-grid">
    <?php foreach($dosyalar as $dosya): ?>
        <?php $ext = $dosya['uzanti']; ?>
        <div class="dosya-item">
            <form method="post" style="display:inline">
                <input type="hidden" name="sil_dosya" value="<?= htmlspecialchars($dosya['ad']) ?>">
                <input type="hidden" name="uzanti" value="<?= htmlspecialchars($ext) ?>">
                <button type="submit" class="dosya-sil-btn" title="Sil" onclick="return confirm('Silinsin mi?')">&times;</button>
            </form>
            <?php if(in_array($ext, ["jpg","jpeg","png","bmp","gif","webp"])): ?>
                <a href="<?= $dosya['url'] ?>" target="_blank">
                    <img src="<?= $dosya['url'] ?>" alt="">
                </a>
            <?php elseif($ext == "pdf"): ?>
                <a href="<?= $dosya['url'] ?>" target="_blank">
                    <img src="https://img.icons8.com/?size=48&id=18751&format=png" alt="PDF">
                </a>
            <?php elseif($ext == "dxf" || $ext == "x_t" || $ext == "xt"): ?>
                <a href="<?= $dosya['url'] ?>" download>
                    <img src="https://img.icons8.com/?size=48&id=40660&format=png" alt="DXF">
                </a>
            <?php else: ?>
                <a href="<?= $dosya['url'] ?>" download>
                    <img src="https://img.icons8.com/?size=48&id=20845&format=png" alt="">
                </a>
            <?php endif; ?>
            <div class="dosya-ad"><?= htmlspecialchars($dosya['ad']) ?></div>
        </div>
    <?php endforeach; ?>
    <?php if(!$dosyalar): ?>
        <div class="text-secondary">Henüz dosya yok.</div>
    <?php endif; ?>
    </div>
</div>
</body>
</html>
