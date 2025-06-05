<?php
require_once __DIR__ . '/../../../config/db.php';

// Başarılı ekleme mesajı için
$basarili = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [];
    // Kolonları belirle
    $fields = [
        'urun_adi','urun_kodu','barkod','gtip_no','kategori','alt_kategori','marka','model','olcu_birimi','renk','malzeme',
        'net_agirlik','brut_agirlik','paket_agirlik','boyut_en','boyut_boy','boyut_yukseklik','boyut_cap','boyut_hacim',
        'mensei','tedarikci','alis_fiyati','satis_fiyati','para_birimi','kdv_orani','otv_orani','diger_vergi_orani',
        'min_stok','max_stok','raf_yeri','seri_no','lot_no','son_kullanma_tarihi','son_alis_tarihi','son_satis_tarihi',
        'aciklama','resim','teknik_cizim','sertifika','kullanim_kilavuzu','bilgi_foyu',
        'ozel_kod1','ozel_kod2','ozel_kod3','ozel_kod4','ozel_kod5','ozel_kod6','ozel_kod7','ozel_kod8','ozel_kod9','ozel_kod10',
        'tedarikci_adi','tedarikci_kodu','musteri_adi','musteri_kodu'
    ];
    foreach($fields as $f) {
        $data[$f] = isset($_POST[$f]) ? trim($_POST[$f]) : null;
    }
    // SQL Sorgusu hazırla
    $sql = "INSERT INTO urunler (" . implode(",", $fields) . ") VALUES (" . implode(",", array_fill(0, count($fields), "?")) . ")";
    $stmt = $pdo->prepare($sql);
    $basarili = $stmt->execute(array_values($data));
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Yeni Ürün Ekle</title>
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
        <h3 class="mb-3">Yeni Ürün Ekle</h3>
        <?php if($basarili): ?>
            <div class="alert alert-success">Ürün başarıyla eklendi!</div>
        <?php endif; ?>
        <form method="post" autocomplete="off">
            <div class="row mb-2">
                <div class="col-md-6">
                    <label class="form-label">Ürün Kodu *</label>
                    <input type="text" name="urun_kodu" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Ürün Adı *</label>
                    <input type="text" name="urun_adi" class="form-control" required>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-6"><label class="form-label">Barkod</label><input type="text" name="barkod" class="form-control"></div>
                <div class="col-md-6"><label class="form-label">GTIP No</label><input type="text" name="gtip_no" class="form-control"></div>
            </div>
            <div class="row mb-2">
                <div class="col-md-6"><label class="form-label">Kategori</label><input type="text" name="kategori" class="form-control"></div>
                <div class="col-md-6"><label class="form-label">Alt Kategori</label><input type="text" name="alt_kategori" class="form-control"></div>
            </div>
            <div class="row mb-2">
                <div class="col-md-6"><label class="form-label">Marka</label><input type="text" name="marka" class="form-control"></div>
                <div class="col-md-6"><label class="form-label">Model</label><input type="text" name="model" class="form-control"></div>
            </div>
            <div class="row mb-2">
                <div class="col-md-6"><label class="form-label">Ölçü Birimi</label><input type="text" name="olcu_birimi" class="form-control"></div>
                <div class="col-md-6"><label class="form-label">Renk</label><input type="text" name="renk" class="form-control"></div>
            </div>
            <div class="row mb-2">
                <div class="col-md-6"><label class="form-label">Malzeme</label><input type="text" name="malzeme" class="form-control"></div>
                <div class="col-md-6"><label class="form-label">Menşei</label><input type="text" name="mensei" class="form-control"></div>
            </div>
            <div class="row mb-2">
                <div class="col-md-6"><label class="form-label">Tedarikçi</label><input type="text" name="tedarikci" class="form-control"></div>
                <div class="col-md-6"><label class="form-label">Tedarikçi Adı</label><input type="text" name="tedarikci_adi" class="form-control"></div>
            </div>
            <div class="row mb-2">
                <div class="col-md-6"><label class="form-label">Tedarikçi Kodu</label><input type="text" name="tedarikci_kodu" class="form-control"></div>
                <div class="col-md-6"><label class="form-label">Müşteri Adı</label><input type="text" name="musteri_adi" class="form-control"></div>
            </div>
            <div class="row mb-2">
                <div class="col-md-6"><label class="form-label">Müşteri Kodu</label><input type="text" name="musteri_kodu" class="form-control"></div>
                <div class="col-md-6"><label class="form-label">Birim</label><input type="text" name="birim" class="form-control"></div>
            </div>
            <div class="row mb-2">
                <div class="col-md-6"><label class="form-label">Satış Fiyatı</label><input type="number" step="0.01" name="satis_fiyati" class="form-control"></div>
                <div class="col-md-6"><label class="form-label">Alış Fiyatı</label><input type="number" step="0.01" name="alis_fiyati" class="form-control"></div>
            </div>
            <div class="row mb-2">
                <div class="col-md-6"><label class="form-label">Para Birimi</label>
                    <select name="para_birimi" class="form-select">
                        <option value="">Seçiniz</option>
                        <option>TL</option>
                        <option>USD</option>
                        <option>EUR</option>
                        <option>GBP</option>
                    </select>
                </div>
                <div class="col-md-6"><label class="form-label">KDV Oranı (%)</label><input type="number" step="0.01" name="kdv_orani" class="form-control"></div>
            </div>
            <div class="row mb-2">
                <div class="col-md-6"><label class="form-label">ÖTV Oranı (%)</label><input type="number" step="0.01" name="otv_orani" class="form-control"></div>
                <div class="col-md-6"><label class="form-label">Diğer Vergi Oranı (%)</label><input type="number" step="0.01" name="diger_vergi_orani" class="form-control"></div>
            </div>
            <div class="row mb-2">
                <div class="col-md-6"><label class="form-label">Min Stok</label><input type="number" name="min_stok" class="form-control"></div>
                <div class="col-md-6"><label class="form-label">Max Stok</label><input type="number" name="max_stok" class="form-control"></div>
            </div>
            <div class="row mb-2">
                <div class="col-md-6"><label class="form-label">Raf Yeri</label><input type="text" name="raf_yeri" class="form-control"></div>
                <div class="col-md-6"><label class="form-label">Seri No</label><input type="text" name="seri_no" class="form-control"></div>
            </div>
            <div class="row mb-2">
                <div class="col-md-6"><label class="form-label">Lot No</label><input type="text" name="lot_no" class="form-control"></div>
                <div class="col-md-6"><label class="form-label">Son Kullanma Tarihi</label><input type="date" name="son_kullanma_tarihi" class="form-control"></div>
            </div>
            <div class="row mb-2">
                <div class="col-md-6"><label class="form-label">Son Alış Tarihi</label><input type="date" name="son_alis_tarihi" class="form-control"></div>
                <div class="col-md-6"><label class="form-label">Son Satış Tarihi</label><input type="date" name="son_satis_tarihi" class="form-control"></div>
            </div>
            <div class="row mb-2">
                <div class="col-md-3"><label class="form-label">Net Ağırlık (kg)</label><input type="number" step="0.001" name="net_agirlik" class="form-control"></div>
                <div class="col-md-3"><label class="form-label">Brüt Ağırlık (kg)</label><input type="number" step="0.001" name="brut_agirlik" class="form-control"></div>
                <div class="col-md-3"><label class="form-label">Paket Ağırlık (kg)</label><input type="number" step="0.001" name="paket_agirlik" class="form-control"></div>
                <div class="col-md-3"><label class="form-label">Ağırlık (gr)</label><input type="number" step="1" name="agirlik" class="form-control"></div>
            </div>
            <div class="row mb-2">
                <div class="col-md-3"><label class="form-label">En (mm)</label><input type="number" step="0.01" name="boyut_en" class="form-control"></div>
                <div class="col-md-3"><label class="form-label">Boy (mm)</label><input type="number" step="0.01" name="boyut_boy" class="form-control"></div>
                <div class="col-md-3"><label class="form-label">Yükseklik (mm)</label><input type="number" step="0.01" name="boyut_yukseklik" class="form-control"></div>
                <div class="col-md-3"><label class="form-label">Çap (mm)</label><input type="number" step="0.01" name="boyut_cap" class="form-control"></div>
            </div>
            <div class="row mb-2">
                <div class="col-md-6"><label class="form-label">Hacim (cm³)</label><input type="number" step="0.01" name="boyut_hacim" class="form-control"></div>
                <div class="col-md-6"><label class="form-label">Parça Boyut (mm)</label><input type="number" step="0.01" name="parca_boyut" class="form-control"></div>
            </div>
            <div class="row mb-2">
                <div class="col-md-6"><label class="form-label">Resim Dosya Adı</label><input type="text" name="resim" class="form-control" placeholder="Otomatik yükleme ile geliyorsa doldurmayın"></div>
                <div class="col-md-6"><label class="form-label">Teknik Çizim Dosya Adı</label><input type="text" name="teknik_cizim" class="form-control"></div>
            </div>
            <div class="row mb-2">
                <div class="col-md-6"><label class="form-label">Sertifika Dosya Adı</label><input type="text" name="sertifika" class="form-control"></div>
                <div class="col-md-6"><label class="form-label">Kullanım Kılavuzu Dosya Adı</label><input type="text" name="kullanim_kilavuzu" class="form-control"></div>
            </div>
            <div class="row mb-2">
                <div class="col-md-6"><label class="form-label">Bilgi Föyü Dosya Adı</label><input type="text" name="bilgi_foyu" class="form-control"></div>
                <div class="col-md-6"><label class="form-label">Barkod</label><input type="text" name="barkod" class="form-control"></div>
            </div>
            <!-- Özel Kodlar -->
            <div class="row mb-2">
                <div class="col-md-2"><label class="form-label">Özel Kod 1</label><input type="text" name="ozel_kod1" class="form-control"></div>
                <div class="col-md-2"><label class="form-label">Özel Kod 2</label><input type="text" name="ozel_kod2" class="form-control"></div>
                <div class="col-md-2"><label class="form-label">Özel Kod 3</label><input type="text" name="ozel_kod3" class="form-control"></div>
                <div class="col-md-2"><label class="form-label">Özel Kod 4</label><input type="text" name="ozel_kod4" class="form-control"></div>
                <div class="col-md-2"><label class="form-label">Özel Kod 5</label><input type="text" name="ozel_kod5" class="form-control"></div>
            </div>
            <div class="row mb-2">
                <div class="col-md-2"><label class="form-label">Özel Kod 6</label><input type="text" name="ozel_kod6" class="form-control"></div>
                <div class="col-md-2"><label class="form-label">Özel Kod 7</label><input type="text" name="ozel_kod7" class="form-control"></div>
                <div class="col-md-2"><label class="form-label">Özel Kod 8</label><input type="text" name="ozel_kod8" class="form-control"></div>
                <div class="col-md-2"><label class="form-label">Özel Kod 9</label><input type="text" name="ozel_kod9" class="form-control"></div>
                <div class="col-md-2"><label class="form-label">Özel Kod 10</label><input type="text" name="ozel_kod10" class="form-control"></div>
            </div>
            <div class="row mb-2">
                <div class="col-12">
                    <label class="form-label">Açıklama</label>
                    <textarea name="aciklama" class="form-control" rows="2"></textarea>
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
