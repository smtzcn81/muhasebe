<?php
require_once __DIR__ . '/../../../config/db.php';
require_once __DIR__ . '/../../../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

// Alanlar
$kolonlar = [
    'urun_adi','urun_kodu','barkod','gtip_no','kategori','alt_kategori','marka','model','olcu_birimi','renk','malzeme',
    'net_agirlik','brut_agirlik','paket_agirlik','boyut_en','boyut_boy','boyut_yukseklik','boyut_cap','boyut_hacim',
    'mensei','tedarikci','alis_fiyati','satis_fiyati','para_birimi','kdv_orani','otv_orani','diger_vergi_orani',
    'min_stok','max_stok','raf_yeri','seri_no','lot_no','son_kullanma_tarihi','son_alis_tarihi','son_satis_tarihi',
    'aciklama','resim','teknik_cizim','sertifika','kullanim_kilavuzu','bilgi_foyu',
    'ozel_kod1','ozel_kod2','ozel_kod3','ozel_kod4','ozel_kod5','ozel_kod6','ozel_kod7','ozel_kod8','ozel_kod9','ozel_kod10',
    'tedarikci_adi','tedarikci_kodu','musteri_adi','musteri_kodu'
];

$mesajlar = [];
$basarili = 0; $hatalar = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['dosya']) && $_FILES['dosya']['error'] === 0) {
    $tmp = $_FILES['dosya']['tmp_name'];
    $spreadsheet = IOFactory::load($tmp);
    $sheet = $spreadsheet->getActiveSheet();
    $rows = $sheet->toArray(null, true, true, true);

    // 2. satır: başlıklar (ilk satır açıklama olduğu için)
    $header = [];
    foreach ($rows[2] as $k => $v) {
        $header[$k] = trim($v);
    }

    // Kolon eşleşmesi
    $indeks_map = [];
    foreach ($kolonlar as $kolon) {
        $found = array_search($kolon, $header);
        if ($found !== false) {
            $indeks_map[$kolon] = $found;
        }
    }

    if (!isset($indeks_map['urun_kodu'])) {
        $hatalar[] = '"urun_kodu" başlığı zorunludur!';
    } else {
        // 3. satırdan itibaren ürünler
        for ($i = 3; $i <= count($rows); $i++) {
            $row = $rows[$i] ?? null;
            if (!$row) continue;
            // urun_kodu boş ise satırı atla
            $urun_kodu_cell = $indeks_map['urun_kodu'];
            if (!isset($row[$urun_kodu_cell]) || trim($row[$urun_kodu_cell]) === '') continue;

            $insert = [];
            foreach ($kolonlar as $k) {
                $cell = isset($indeks_map[$k]) ? $row[$indeks_map[$k]] ?? null : null;
                // Ürün adı boşsa otomatik "-" ata
                if ($k === 'urun_adi') {
                    $val = (is_null($cell) || trim($cell) === '') ? '-' : trim($cell);
                    $insert[] = $val;
                } else {
                    $insert[] = is_null($cell) ? null : trim($cell);
                }
            }
            try {
                $sql = "INSERT INTO urunler (" . implode(",", $kolonlar) . ") VALUES (" . implode(",", array_fill(0, count($kolonlar), "?")) . ")";
                $stmt = $pdo->prepare($sql);
                $stmt->execute($insert);
                $basarili++;
            } catch (PDOException $e) {
                $hatalar[] = "Satır $i: " . $e->getMessage();
            }
        }
        if ($basarili > 0) $mesajlar[] = "<div class='alert alert-success'>$basarili ürün başarıyla eklendi.</div>";
        if ($hatalar) $mesajlar[] = "<div class='alert alert-danger'>" . implode('<br>', $hatalar) . "</div>";
    }
}

?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Toplu Ürün Yükle (Excel XLSX)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body { background:#f7fafd;}
    .upload-box { background:#fff; border-radius:10px; box-shadow:0 0 8px #eee; padding:32px; max-width:470px; margin:auto;}
    </style>
</head>
<body>
<div class="container my-5">
    <div class="upload-box">
        <h3 class="mb-3">Toplu Ürün Yükle (Excel)</h3>
        <a href="index.php" class="btn btn-secondary btn-sm mb-3">&laquo; Listeye Dön</a>
        <?php if($mesajlar) foreach($mesajlar as $msg) echo $msg; ?>
        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Excel dosyası (.xlsx) seçiniz:</label>
                <input type="file" name="dosya" accept=".xlsx,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" required class="form-control">
                <small class="text-muted">
                    <a href="ornek_xlsx.php" class="btn btn-outline-info btn-sm mt-2">Örnek Excel Şablonu İndir</a>
                </small>
            </div>
            <button type="submit" class="btn btn-primary">Yüklemeye Başla</button>
        </form>
        <div class="mt-3 text-secondary" style="font-size:13px;">
            <b>Notlar:</b>
            <ul>
                <li><b>urun_kodu</b> alanı zorunludur ve benzersiz olmalıdır.</li>
                <li>Başlıklar <a href="ornek_xlsx.php" target="_blank">örnek Excel</a> dosyası ile aynı olmalıdır.</li>
                <li>İlk satır açıklama, ikinci satır başlık, üçüncü satırdan itibaren ürünler gelmelidir.</li>
                <li>Yüklenen her ürün <b>yeni kayıt</b> olarak eklenir.</li>
                <li>Ürün adı boş ise otomatik olarak <b>-</b> olarak kaydedilir.</li>
            </ul>
        </div>
    </div>
</div>
</body>
</html>
