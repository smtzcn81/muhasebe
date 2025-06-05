<?php
$base = __DIR__ . '/../../../assets/urunler/';
if (!is_dir($base)) mkdir($base, 0777, true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $urun_kodu = $_POST['urun_kodu'];
    $uzanti = strtolower($_POST['uzanti']);
    $allowed = ['pdf','dxf','x_t','xt','jpg','jpeg','png','bmp','webp'];
    if (in_array($uzanti, $allowed) && isset($_FILES['dosya']) && $_FILES['dosya']['error'] === 0) {
        $altklasor = $base . $uzanti . '/';
        if (!is_dir($altklasor)) mkdir($altklasor, 0777, true);
        $dosya_adi = $urun_kodu . '.' . $uzanti;
        $hedef_yol = $altklasor . $dosya_adi;
        move_uploaded_file($_FILES['dosya']['tmp_name'], $hedef_yol);
        header("Location: index.php?yukleme=ok");
        exit;
    }
}
header("Location: index.php?yukleme=no");
exit;
