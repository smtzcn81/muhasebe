<?php
require_once __DIR__ . '/../../../config/db.php';
require_once __DIR__ . '/../../../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

// Alanlar (başlık sırası veri tabanı ile aynı olmalı)
$kolonlar = [
    'urun_adi','urun_kodu','barkod','gtip_no','kategori','alt_kategori','marka','model','olcu_birimi','renk','malzeme',
    'net_agirlik','brut_agirlik','paket_agirlik','boyut_en','boyut_boy','boyut_yukseklik','boyut_cap','boyut_hacim',
    'mensei','tedarikci','alis_fiyati','satis_fiyati','para_birimi','kdv_orani','otv_orani','diger_vergi_orani',
    'min_stok','max_stok','raf_yeri','seri_no','lot_no','son_kullanma_tarihi','son_alis_tarihi','son_satis_tarihi',
    'aciklama','resim','teknik_cizim','sertifika','kullanim_kilavuzu','bilgi_foyu',
    'ozel_kod1','ozel_kod2','ozel_kod3','ozel_kod4','ozel_kod5','ozel_kod6','ozel_kod7','ozel_kod8','ozel_kod9','ozel_kod10',
    'tedarikci_adi','tedarikci_kodu','musteri_adi','musteri_kodu'
];

$stmt = $pdo->query("SELECT " . implode(",", $kolonlar) . " FROM urunler ORDER BY id DESC");
$urunler = $stmt->fetchAll(PDO::FETCH_ASSOC);

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// 1. Satır: Başlıklar
foreach ($kolonlar as $i => $col) {
    $colLetter = Coordinate::stringFromColumnIndex($i + 1);
    $sheet->setCellValue($colLetter . '1', $col);
}

// 2. satırdan itibaren ürünler
$rowNum = 2;
foreach ($urunler as $urun) {
    foreach ($kolonlar as $k => $col) {
        $colLetter = Coordinate::stringFromColumnIndex($k + 1);
        $sheet->setCellValue($colLetter . $rowNum, $urun[$col]);
    }
    $rowNum++;
}

// Dosya adında tarih-saat-dakika (ör: urunler_20240527_2122.xlsx)
$filename = "urunler_" . date("Ymd_His") . ".xlsx";

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
