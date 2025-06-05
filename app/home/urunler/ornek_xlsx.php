<?php
require_once __DIR__ . '/../../../config/db.php';
require_once __DIR__ . '/../../../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

// Alanlar ve açıklamalar
$kolonlar = [
    'urun_adi','urun_kodu','barkod','gtip_no','kategori','alt_kategori','marka','model','olcu_birimi','renk','malzeme',
    'net_agirlik','brut_agirlik','paket_agirlik','boyut_en','boyut_boy','boyut_yukseklik','boyut_cap','boyut_hacim',
    'mensei','tedarikci','alis_fiyati','satis_fiyati','para_birimi','kdv_orani','otv_orani','diger_vergi_orani',
    'min_stok','max_stok','raf_yeri','seri_no','lot_no','son_kullanma_tarihi','son_alis_tarihi','son_satis_tarihi',
    'aciklama','resim','teknik_cizim','sertifika','kullanim_kilavuzu','bilgi_foyu',
    'ozel_kod1','ozel_kod2','ozel_kod3','ozel_kod4','ozel_kod5','ozel_kod6','ozel_kod7','ozel_kod8','ozel_kod9','ozel_kod10',
    'tedarikci_adi','tedarikci_kodu','musteri_adi','musteri_kodu'
];

$aciklamalar = [
    'ÜRÜN ADI (zorunlu)', 'ÜRÜN KODU (zorunlu, benzersiz)', 'BARKOD (isteğe bağlı)', 'GTIP NO (isteğe bağlı)', 
    'KATEGORİ (isteğe bağlı)', 'ALT KATEGORİ (isteğe bağlı)', 'MARKA (isteğe bağlı)', 'MODEL (isteğe bağlı)', 
    'ÖLÇÜ BİRİMİ (ör: adet, kg, m)', 'RENK (isteğe bağlı)', 'MALZEME (isteğe bağlı)',
    'NET AĞIRLIK (kg)', 'BRÜT AĞIRLIK (kg)', 'PAKET AĞIRLIK (kg)', 'EN (mm)', 'BOY (mm)', 'YÜKSEKLİK (mm)', 
    'ÇAP (mm)', 'HACİM (cm³)', 'MENŞEİ (isteğe bağlı)', 'TEDARİKÇİ (isteğe bağlı)', 
    'ALIŞ FİYATI', 'SATIŞ FİYATI', 'PARA BİRİMİ', 'KDV ORANI (%)', 'ÖTV ORANI (%)', 'DİĞER VERGİ ORANI (%)',
    'MİNİMUM STOK', 'MAKSİMUM STOK', 'RAF YERİ', 'SERİ NO', 'LOT NO', 'SON KULLANMA TARİHİ', 
    'SON ALIŞ TARİHİ', 'SON SATIŞ TARİHİ', 'AÇIKLAMA', 'RESİM (dosya adı/URL)', 'TEKNİK ÇİZİM (dosya adı/URL)', 
    'SERTİFİKA (dosya adı/URL)', 'KULLANIM KILAVUZU (dosya adı/URL)', 'BİLGİ FÖYÜ (dosya adı/URL)',
    'ÖZEL KOD 1', 'ÖZEL KOD 2', 'ÖZEL KOD 3', 'ÖZEL KOD 4', 'ÖZEL KOD 5', 
    'ÖZEL KOD 6', 'ÖZEL KOD 7', 'ÖZEL KOD 8', 'ÖZEL KOD 9', 'ÖZEL KOD 10',
    'TEDARİKÇİ ADI', 'TEDARİKÇİ KODU', 'MÜŞTERİ ADI', 'MÜŞTERİ KODU'
];

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// 1. Satır: Açıklamalar
foreach ($aciklamalar as $i => $aciklama) {
    $colLetter = Coordinate::stringFromColumnIndex($i + 1);
    $sheet->setCellValue($colLetter . '1', $aciklama);
}
// 2. Satır: Başlıklar
foreach ($kolonlar as $i => $col) {
    $colLetter = Coordinate::stringFromColumnIndex($i + 1);
    $sheet->setCellValue($colLetter . '2', $col);
}

// Veritabanından ürünleri çek ve 3. satırdan itibaren yaz
$stmt = $pdo->query("SELECT " . implode(",", $kolonlar) . " FROM urunler ORDER BY id DESC");
$urunler = $stmt->fetchAll(PDO::FETCH_ASSOC);

$rowNum = 3; // 1: açıklama, 2: başlık, 3+ : ürünler
foreach ($urunler as $urun) {
    foreach ($kolonlar as $k => $col) {
        $colLetter = Coordinate::stringFromColumnIndex($k + 1);
        $sheet->setCellValue($colLetter . $rowNum, $urun[$col]);
    }
    $rowNum++;
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="ornek_urunler.xlsx"');
header('Cache-Control: max-age=0');
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
