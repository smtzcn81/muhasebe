<?php
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=urunler_sablon.csv');

$columns = [
    "urun_adi","urun_kodu","kategori","marka","mensei","gtip_no",
    "min_stok","max_stok","aciklama"
];

$output = fopen('php://output', 'w');
fputcsv($output, $columns);
fclose($output);
exit;
