<?php
require_once __DIR__ . '/../../../config/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id) { echo "Geçersiz ürün!"; exit; }

// Mevcut ürün verisini çek
$stmt = $pdo->prepare("SELECT * FROM urunler WHERE id=?");
$stmt->execute([$id]);
$urun = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$urun) { echo "Ürün bulunamadı!"; exit; }

$basarili = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fields = [
        'urun_adi','urun_kodu','barkod','gtip_no','kategori','alt_kategori','marka','model','olcu_birimi','renk','malzeme',
        'net_agirlik','brut_agirlik','paket_agirlik','boyut_en','boyut_boy','boyut_yukseklik','boyut_cap','boyut_hacim',
        'mensei','tedarikci','alis_fiyati','satis_fiyati','para_birimi','kdv_orani','otv_orani','diger_vergi_orani',
        'min_stok','max_stok','raf_yeri','seri_no','lot_no','son_kullanma_tarihi','son_alis_tarihi','son_satis_tarihi',
        'aciklama','resim','teknik_cizim','sertifika','kullanim_kilavuzu','bilgi_foyu',
        'ozel_kod1','ozel_kod2','ozel_kod3','ozel_kod4','ozel_kod5','ozel_kod6','ozel_kod7','ozel_kod8','ozel_kod9','ozel_kod10',
        'tedarikci_adi','tedarikci_kodu','musteri_adi','musteri_kodu'
    ];
    $data = [];
    foreach($fields as $f) {
        $data[$f] = isset($_POST[$f]) ? trim($_POST[$f]) : null;
    }
    $sql = "UPDATE urunler SET " . implode('=?, ', $fields) . "=? WHERE id=?";
    $stmt = $pdo->prepare($sql);
    $params = array_values($data);
    $params[] = $id;
    $basarili = $stmt->execute($params);

    // Son değişiklikleri tekrar çek
    if ($basarili) {
        $stmt = $pdo->prepare("SELECT * FROM urunler WHERE id=?");
        $stmt->execute([$id]);
        $urun = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Ürün Düzenle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #fafbfc;}
        .form-label { font-weight: 500; }
        .form-section { background:#fff; border-radius:12px; box-shadow:0 0 8px #eee; padding:30px 22px 18px 22px;}
        .form-control, .form-select { font-size:14px;}
        .mb-2 { margin-bottom: .8rem !important;}
    </style>
</head>
<body>
<div class="container my-5">
    <div class="form-section mx-auto" style="max-width:680px;">
        <h3 class="mb-3">Ürün Düzenle</h3>
        <?php if($basarili): ?>
            <div class="alert alert-success">Ürün başarıyla güncellendi!</div>
        <?php endif; ?>
        <form method="post" autocomplete="off">
            <div class="row mb-2">
                <div class="col-md-6">
                    <label class="form-label">Ürün Kodu *</label>
                    <input type="text" name="urun_kodu" class="form-control" required value="<?= htmlspecialchars($urun['urun_kodu']) ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Ürün Adı *</label>
                    <input type="text" name="urun_adi" class="form-control" required value="<?= htmlspecialchars($urun['urun_adi']) ?>">
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-6"><label class="form-label">Barkod</label><input type="text" name="barkod" class="form-control" value="<?= htmlspecialchars($urun['barkod']) ?>"></div>
                <div class="col-md-6"><label class="form-label">GTIP No</label><input type="text" name="gtip_no" class="form-control" value="<?= htmlspecialchars($urun['gtip_no']) ?>"></div>
            </div>
            <div class="row mb-2">
                <div class="col-md-6"><label class="form-label">Kategori</label><input type="text" name="kategori" class="form-control" value="<?= htmlspecialchars($urun['kategori']) ?>"></div>
                <div class="col-md-6"><label class="form-label">Alt Kategori</label><input type="text" name="alt_kategori" class="form-control" value="<?= htmlspecialchars($urun['alt_kategori']) ?>"></div>
            </div>
            <div class="row mb-2">
                <div class="col-md-6"><label class="form-label">Marka</label><input type="text" name="marka" class="form-control" value="<?= htmlspecialchars($urun['marka']) ?>"></div>
                <div class="col-md-6"><label class="form-label">Model</label><input type="text" name="model" class="form-control" value="<?= htmlspecialchars($urun['model']) ?>"></div>
            </div>
            <div class="row mb-2">
                <div class="col-md-6"><label class="form-label">Ölçü Birimi</label><input type="text" name="olcu_birimi" class="form-control" value="<?= htmlspecialchars($urun['olcu_birimi']) ?>"></div>
                <div class="col-md-6"><label class="form-label">Renk</label><input type="text" name="renk" class="form-control" value="<?= htmlspecialchars($urun['renk']) ?>"></div>
            </div>
            <div class="row mb-2">
                <div class="col-md-6"><label class="form-label">Malzeme</label><input type="text" name="malzeme" class="form-control" value="<?= htmlspecialchars($urun['malzeme']) ?>"></div>
                <div class="col-md-6"><label class="form-label">Menşei</label><input type="text" name="mensei" class="form-control" value="<?= htmlspecialchars($urun['mensei']) ?>"></div>
            </div>
            <div class="row mb-2">
                <div class="col-md-6"><label class="form-label">Tedarikçi</label><input type="text" name="tedarikci" class="form-control" value="<?= htmlspecialchars($urun['tedarikci']) ?>"></div>
                <div class="col-md-6"><label class="form-label">Tedarikçi Adı</label><input type="text" name="tedarikci_adi" class="form-control" value="<?= htmlspecialchars($urun['tedarikci_adi']) ?>"></div>
            </div>
            <div class="row mb-2">
                <div class="col-md-6"><label class="form-label">Tedarikçi Kodu</label><input type="text" name="tedarikci_kodu" class="form-control" value="<?= htmlspecialchars($urun['tedarikci_kodu']) ?>"></div>
                <div class="col-md-6"><label class="form-label">Müşteri Adı</label><input type="text" name="musteri_adi" class="form-control" value="<?= htmlspecialchars($urun['musteri_adi']) ?>"></div>
            </div>
            <div class="row mb-2">
                <div class="col-md-6"><label class="form-label">Müşteri Kodu</label><input type="text" name="musteri_kodu" class="form-control" value="<?= htmlspecialchars($urun['musteri_kodu']) ?>"></div>
            </div>
            <div class="row mb-2">
                <div class="col-md-6"><label class="form-label">Satış Fiyatı</label><input type="number" step="0.01" name="satis_fiyati" class="form-control" value="<?= htmlspecialchars($urun['satis_fiyati']) ?>"></div>
                <div class="col-md-6"><label class="form-label">Alış Fiyatı</label><input type="number" step="0.01" name="alis_fiyati" class="form-control" value="<?= htmlspecialchars($urun['alis_fiyati']) ?>"></div>
            </div>
            <div class="row mb-2">
                <div class="col-md-6"><label class="form-label">Para Birimi</label>
                    <select name="para_birimi" class="form-select">
                        <option value="">Seçiniz</option>
                        <option<?= $urun['para_birimi']=='TL'?' selected':''; ?>>TL</option>
                        <option<?= $urun['para_birimi']=='USD'?' selected':''; ?>>USD</option>
                        <option<?= $urun['para_birimi']=='EUR'?' selected':''; ?>>EUR</option>
                        <option<?= $urun['para_birimi']=='GBP'?' selected':''; ?>>GBP</option>
                    </select>
                </div>
                <div class="col-md-6"><label class="form-label">KDV Oranı (%)</label><input type="number" step="0.01" name="kdv_orani" class="form-control" value="<?= htmlspecialchars($urun['kdv_orani']) ?>"></div>
            </div>
            <div class="row mb-2">
                <div class="col-md-6"><label class="form-label">ÖTV Oranı (%)</label><input type="number" step="0.01" name="otv_orani" class="form-control" value="<?= htmlspecialchars($urun['otv_orani']) ?>"></div>
                <div class="col-md-6"><label class="form-label">Diğer Vergi Oranı (%)</label><input type="number" step="0.01" name="diger_vergi_orani" class="form-control" value="<?= htmlspecialchars($urun['diger_vergi_orani']) ?>"></div>
            </div>
            <div class="row mb-2">
                <div class="col-md-6"><label class="form-label">Min Stok</label><input type="number" name="min_stok" class="form-control" value="<?= htmlspecialchars($urun['min_stok']) ?>"></div>
                <div class="col-md-6"><label class="form-label">Max Stok</label><input type="number" name="max_stok" class="form-control" value="<?= htmlspecialchars($urun['max_stok']) ?>"></div>
            </div>
            <div class="row mb-2">
                <div class="col-md-6"><label class="form-label">Raf Yeri</label><input type="text" name="raf_yeri" class="form-control" value="<?= htmlspecialchars($urun['raf_yeri']) ?>"></div>
                <div class="col-md-6"><label class="form-label">Seri No</label><input type="text" name="seri_no" class="form-control" value="<?= htmlspecialchars($urun['seri_no']) ?>"></div>
            </div>
            <div class="row mb-2">
                <div class="col-md-6"><label class="form-label">Lot No</label><input type="text" name="lot_no" class="form-control" value="<?= htmlspecialchars($urun['lot_no']) ?>"></div>
                <div class="col-md-6"><label class="form-label">Son Kullanma Tarihi</label><input type="date" name="son_kullanma_tarihi" class="form-control" value="<?= htmlspecialchars($urun['son_kullanma_tarihi']) ?>"></div>
            </div>
            <div class="row mb-2">
                <div class="col-md-6"><label class="form-label">Son Alış Tarihi</label><input type="date" name="son_alis_tarihi" class="form-control" value="<?= htmlspecialchars($urun['son_alis_tarihi']) ?>"></div>
                <div class="col-md-6"><label class="form-label">Son Satış Tarihi</label><input type="date" name="son_satis_tarihi" class="form-control" value="<?= htmlspecialchars($urun['son_satis_tarihi']) ?>"></div>
            </div>
            <div class="row mb-2">
                <div class="col-md-3"><label class="form-label">Net Ağırlık (kg)</label><input type="number" step="0.001" name="net_agirlik" class="form-control" value="<?= htmlspecialchars($urun['net_agirlik']) ?>"></div>
                <div class="col-md-3"><label class="form-label">Brüt Ağırlık (kg)</label><input type="number" step="0.001" name="brut_agirlik" class="form-control" value="<?= htmlspecialchars($urun['brut_agirlik']) ?>"></div>
                <div class="col-md-3"><label class="form-label">Paket Ağırlık (kg)</label><input type="number" step="0.001" name="paket_agirlik" class="form-control" value="<?= htmlspecialchars($urun['paket_agirlik']) ?>"></div>
            </div>
            <div class="row mb-2">
                <div class="col-md-3"><label class="form-label">En (mm)</label><input type="number" step="0.01" name="boyut_en" class="form-control" value="<?= htmlspecialchars($urun['boyut_en']) ?>"></div>
                <div class="col-md-3"><label class="form-label">Boy (mm)</label><input type="number" step="0.01" name="boyut_boy" class="form-control" value="<?= htmlspecialchars($urun['boyut_boy']) ?>"></div>
                <div class="col-md-3"><label class="form-label">Yükseklik (mm)</label><input type="number" step="0.01" name="boyut_yukseklik" class="form-control" value="<?= htmlspecialchars($urun['boyut_yukseklik']) ?>"></div>
                <div class="col-md-3"><label class="form-label">Çap (mm)</label><input type="number" step="0.01" name="boyut_cap" class="form-control" value="<?= htmlspecialchars($urun['boyut_cap']) ?>"></div>
            </div>
            <div class="row mb-2">
                <div class="col-md-6"><label class="form-label">Hacim (cm³)</label><input type="number" step="0.01" name="boyut_hacim" class="form-control" value="<?= htmlspecialchars($urun['boyut_hacim']) ?>"></div>
            </div>
            <div class="row mb-2">
                <div class="col-md-6"><label class="form-label">Resim Dosya Adı</label><input type="text" name="resim" class="form-control" value="<?= htmlspecialchars($urun['resim']) ?>"></div>
                <div class="col-md-6"><label class="form-label">Teknik Çizim Dosya Adı</label><input type="text" name="teknik_cizim" class="form-control" value="<?= htmlspecialchars($urun['teknik_cizim']) ?>"></div>
            </div>
            <div class="row mb-2">
                <div class="col-md-6"><label class="form-label">Sertifika Dosya Adı</label><input type="text" name="sertifika" class="form-control" value="<?= htmlspecialchars($urun['sertifika']) ?>"></div>
                <div class="col-md-6"><label class="form-label">Kullanım Kılavuzu Dosya Adı</label><input type="text" name="kullanim_kilavuzu" class="form-control" value="<?= htmlspecialchars($urun['kullanim_kilavuzu']) ?>"></div>
            </div>
            <div class="row mb-2">
                <div class="col-md-6"><label class="form-label">Bilgi Föyü Dosya Adı</label><input type="text" name="bilgi_foyu" class="form-control" value="<?= htmlspecialchars($urun['bilgi_foyu']) ?>"></div>
                <div class="col-md-6"><label class="form-label">Barkod</label><input type="text" name="barkod" class="form-control" value="<?= htmlspecialchars($urun['barkod']) ?>"></div>
            </div>
            <!-- Özel Kodlar -->
            <div class="row mb-2">
                <div class="col-md-2"><label class="form-label">Özel Kod 1</label><input type="text" name="ozel_kod1" class="form-control" value="<?= htmlspecialchars($urun['ozel_kod1']) ?>"></div>
                <div class="col-md-2"><label class="form-label">Özel Kod 2</label><input type="text" name="ozel_kod2" class="form-control" value="<?= htmlspecialchars($urun['ozel_kod2']) ?>"></div>
                <div class="col-md-2"><label class="form-label">Özel Kod 3</label><input type="text" name="ozel_kod3" class="form-control" value="<?= htmlspecialchars($urun['ozel_kod3']) ?>"></div>
                <div class="col-md-2"><label class="form-label">Özel Kod 4</label><input type="text" name="ozel_kod4" class="form-control" value="<?= htmlspecialchars($urun['ozel_kod4']) ?>"></div>
                <div class="col-md-2"><label class="form-label">Özel Kod 5</label><input type="text" name="ozel_kod5" class="form-control" value="<?= htmlspecialchars($urun['ozel_kod5']) ?>"></div>
            </div>
            <div class="row mb-2">
                <div class="col-md-2"><label class="form-label">Özel Kod 6</label><input type="text" name="ozel_kod6" class="form-control" value="<?= htmlspecialchars($urun['ozel_kod6']) ?>"></div>
                <div class="col-md-2"><label class="form-label">Özel Kod 7</label><input type="text" name="ozel_kod7" class="form-control" value="<?= htmlspecialchars($urun['ozel_kod7']) ?>"></div>
                <div class="col-md-2"><label class="form-label">Özel Kod 8</label><input type="text" name="ozel_kod8" class="form-control" value="<?= htmlspecialchars($urun['ozel_kod8']) ?>"></div>
                <div class="col-md-2"><label class="form-label">Özel Kod 9</label><input type="text" name="ozel_kod9" class="form-control" value="<?= htmlspecialchars($urun['ozel_kod9']) ?>"></div>
                <div class="col-md-2"><label class="form-label">Özel Kod 10</label><input type="text" name="ozel_kod10" class="form-control" value="<?= htmlspecialchars($urun['ozel_kod10']) ?>"></div>
            </div>
            <div class="row mb-2">
                <div class="col-12">
                    <label class="form-label">Açıklama</label>
                    <textarea name="aciklama" class="form-control" rows="2"><?= htmlspecialchars($urun['aciklama']) ?></textarea>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Kaydet</button>
                    <a href="index.php" class="btn btn-secondary ms-2">İptal</a>
                </div>
            </div>
        </form>
    </div>
</div>
</body>
</html>
