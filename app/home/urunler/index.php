<?php
require_once __DIR__ . '/../../../config/db.php';

// Tüm tablo başlıklarını db'den al:
$stmt = $pdo->query("SHOW COLUMNS FROM urunler");
$db_kolonlar = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $db_kolonlar[] = $row['Field'];
}

// Eğer db'de zaten 'resim' varsa tekrar eklenmesin
$db_kolonlar_noresim = array_filter($db_kolonlar, fn($k)=>$k!=='resim');

// Sıralanabilirler (isteğe göre genişletebilirsin)
$siralama_kolonlari = array_combine($db_kolonlar, $db_kolonlar);
$varsayilan_sirala = "id";
$varsayilan_yon = "desc";
$sirala = isset($_GET['sirala']) && isset($siralama_kolonlari[$_GET['sirala']]) ? $_GET['sirala'] : $varsayilan_sirala;
$yon = isset($_GET['yon']) && in_array(strtolower($_GET['yon']), ['asc','desc']) ? strtolower($_GET['yon']) : $varsayilan_yon;

// Sıralama için fonksiyon
function url_siralama($kolon, $simdiSiralama, $simdiYon) {
    $nextYon = ($simdiSiralama === $kolon && $simdiYon === "asc") ? "desc" : "asc";
    $q = $_GET;
    $q['sirala'] = $kolon;
    $q['yon'] = $nextYon;
    $query = http_build_query($q);
    return "index.php?$query";
}

function dosya_var_klasor($kod, $uzanti) {
    $path = $_SERVER['DOCUMENT_ROOT'] . "/muhasebe/assets/urunler/$uzanti/$kod.$uzanti";
    return file_exists($path);
}
function dosya_link_klasor($kod, $uzanti) {
    return "/muhasebe/assets/urunler/$uzanti/$kod.$uzanti";
}
function ilk_bulunan_resim_klasor($kod) {
    $uzantilar = ["jpg", "jpeg", "png", "bmp", "webp"];
    foreach ($uzantilar as $uzanti) {
        $path = $_SERVER['DOCUMENT_ROOT'] . "/muhasebe/assets/urunler/$uzanti/$kod.$uzanti";
        if (file_exists($path)) {
            return "/muhasebe/assets/urunler/$uzanti/$kod.$uzanti";
        }
    }
    return false;
}

// Kolonlar: Resim + db başlıkları (resim hariç) + işlemler/pdf/dxf/x_t
$kolonlar = array_merge(
    [["Resim", "resim"]],
    array_map(function($k){ return [strtoupper(str_replace('_',' ',$k)), $k]; }, $db_kolonlar_noresim),
    [["İşlemler", "islemler"], ["PDF", "pdf"], ["DXF", "dxf"], ["X_T", "x_t"]]
);

// Sayfalama
$perPage = 100;
$tumu = isset($_GET['tumu']) && $_GET['tumu'] == "1";
$page = isset($_GET['sayfa']) && is_numeric($_GET['sayfa']) && $_GET['sayfa'] > 0 ? (int)$_GET['sayfa'] : 1;
$stmtCount = $pdo->query("SELECT COUNT(*) FROM urunler");
$toplamUrun = $stmtCount->fetchColumn();
$orderBy = "$sirala $yon";
if ($tumu) {
    $stmt = $pdo->query("SELECT * FROM urunler ORDER BY $orderBy");
    $urunler = $stmt->fetchAll();
    $toplamSayfa = 1;
    $page = 1;
} else {
    $offset = ($page - 1) * $perPage;
    $stmt = $pdo->prepare("SELECT * FROM urunler ORDER BY $orderBy LIMIT :offset, :limit");
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
    $stmt->execute();
    $urunler = $stmt->fetchAll();
    $toplamSayfa = ceil($toplamUrun / $perPage);
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Ürün Listesi (Dinamik Sütun + Özel Sütunlar)</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #fafbfc;}
        .table-responsive { overflow-x: auto !important; }
        table#urunTablo { width: auto; min-width: 100%; }
        th, td { width:180px; min-width: 100px; max-width: 800px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .img-thumb { width: 48px; height: 48px; object-fit: contain; border-radius: 4px; border: 1px solid #e0e0e0; background: #fff; }
        .col-filter { width: 95%; font-size: 11px; }
        .btn { font-weight: 500;}
        .pagination { justify-content: center; }
        .sortable { cursor: pointer; }
        .sorted-asc:after { content: " ▲"; font-size:10px;}
        .sorted-desc:after { content: " ▼"; font-size:10px;}
        .dropdown-check-list { display: inline-block;}
        .dropdown-check-list .anchor {
            cursor: pointer; display: inline-block; padding: 6px 12px; border: 1px solid #ddd;
            background: #fff; border-radius: 5px;
        }
        .dropdown-check-list ul.items {
            padding: 0; display: none; margin: 0; border: 1px solid #ddd; border-top: none;
            position: absolute; background: #fff; z-index: 99;
        }
        .dropdown-check-list ul.items li { list-style: none; padding: 4px 12px; }
        .dropdown-check-list.visible .anchor { border-bottom-left-radius: 0; border-bottom-right-radius: 0; }
        .dropdown-check-list.visible ul.items { display: block;}
    </style>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/colresizable/colResizable-1.6.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tabledragger@1.0.2/dist/table-dragger.min.js"></script>
</head>
<body class="bg-light">
<div class="container mt-4 mb-5">
    <h2 class="mb-4">Ürün Listesi</h2>
    <div class="d-flex gap-2 mb-2 flex-wrap">
        <a href="ekle.php" class="btn btn-success">+ Yeni Ürün Ekle</a>
        <a href="csv_yukle.php" class="btn btn-info">Toplu Ürün Yükle (CSV)</a>
        <a href="xlsx_yukle.php" class="btn btn-info">Toplu Ürün Yükle (Excel)</a>
        <a href="excel_disari_aktar.php" class="btn btn-warning">Ürünleri Excel’e Aktar</a>
        <a href="toplu_dosya_yukle.php" class="btn btn-dark">Toplu Dosya Yükle (Çoklu Seç)</a>
        <?php if(!$tumu): ?>
            <a href="index.php?tumu=1" class="btn btn-outline-primary">Tümünü Göster</a>
        <?php else: ?>
            <a href="index.php" class="btn btn-outline-primary">Sayfalandırmalı Göster</a>
        <?php endif; ?>
        <!-- Sütun gizle/göster -->
        <div class="dropdown-check-list ms-2" tabindex="1" id="sutunMenu">
            <span class="anchor">Sütunlar ▼</span>
            <ul class="items">
            <?php foreach ($kolonlar as $i => $k): ?>
                <li>
                    <label>
                        <input type="checkbox" class="sutun-checkbox" data-idx="<?= $i ?>" checked> <?= $k[0] ?>
                    </label>
                </li>
            <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <div class="table-responsive">
    <table class="table table-bordered table-sm align-middle bg-white" id="urunTablo">
        <thead class="table-light">
            <tr>
                <?php foreach ($kolonlar as $i => $k):
                    $colkey = $k[1];
                    $isSortable = isset($siralama_kolonlari[$colkey]);
                    $th_class = $isSortable ? (($sirala === $colkey) ? ("sorted-" . $yon) : "sortable") : '';
                    $url = $isSortable ? url_siralama($colkey, $sirala, $yon) : "#";
                    echo '<th data-kolon="'.$colkey.'" class="'.$th_class.'" style="width:180px;min-width:120px;max-width:600px;">';
                    if ($isSortable)
                        echo '<a href="'.$url.'" style="color:inherit;text-decoration:none;display:block">'.$k[0].'</a>';
                    else
                        echo $k[0];
                    echo '</th>';
                endforeach; ?>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <?php foreach ($kolonlar as $k): ?>
                    <th style="width:180px;min-width:120px;max-width:600px;">
                    <?php if(in_array($k[1], $db_kolonlar)): ?>
                        <input type="text" class="form-control form-control-sm col-filter" placeholder="<?= $k[0] ?> ara">
                    <?php endif; ?>
                    </th>
                <?php endforeach; ?>
            </tr>
        </tfoot>
        <tbody>
        <?php foreach ($urunler as $urun): ?>
            <tr>
            <?php foreach ($kolonlar as $k):
                $col = $k[1];
                if ($col == "resim") {
                    $kod = $urun['urun_kodu'] ?? '';
                    $resim_url = ilk_bulunan_resim_klasor($kod);
                    echo '<td>';
                    if($resim_url){
                        echo '<a href="'.$resim_url.'" target="_blank"><img src="'.$resim_url.'" class="img-thumb" alt=""></a>';
                    } else {
                        echo '<span class="badge bg-danger yok-badge" data-urun="'.$kod.'" data-uzanti="jpg" style="cursor:pointer;">Yok</span>';
                    }
                    echo '</td>';
                } elseif ($col == "islemler") {
                    echo '<td>
                    <a href="hareketler.php?urun_id='.$urun['id'].'" class="btn btn-warning btn-sm">Hareketler</a>
                    <a href="duzenle.php?id='.$urun['id'].'" class="btn btn-primary btn-sm">Düzenle</a>
                    <a href="dosya_yonet.php?urun_kodu='.urlencode($urun['urun_kodu']).'" class="btn btn-secondary btn-sm">Dosyalar</a>
                    <a href="sil.php?id='.$urun['id'].'" class="btn btn-danger btn-sm" onclick="return confirm(\'Silmek istediğine emin misin?\')">Sil</a>
                    </td>';
                } elseif (in_array($col, ["pdf","dxf","x_t"])) {
                    $uzanti = $col;
                    $kod = $urun['urun_kodu'] ?? '';
                    echo '<td>';
                    if($kod && dosya_var_klasor($kod, $uzanti)){
                        echo '<a href="'.dosya_link_klasor($kod, $uzanti).'" class="badge bg-success" download>Var</a>';
                    } else {
                        echo '<span class="badge bg-danger yok-badge" data-urun="'.$kod.'" data-uzanti="'.$uzanti.'" style="cursor:pointer;">Yok</span>';
                    }
                    echo '</td>';
                } else {
                    echo '<td>'.htmlspecialchars($urun[$col] ?? '').'</td>';
                }
            endforeach; ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    <?php if(!$tumu && $toplamSayfa > 1): ?>
        <nav>
            <ul class="pagination mt-3">
                <li class="page-item<?= $page == 1 ? ' disabled' : '' ?>">
                    <a class="page-link" href="index.php?<?= http_build_query(array_merge($_GET, ['sayfa' => max(1, $page - 1)])) ?>">Önceki</a>
                </li>
                <?php
                $gosterim = 2;
                $sayfalar = [];
                for ($i = 1; $i <= min(2, $toplamSayfa); $i++) $sayfalar[] = $i;
                for ($i = $page - $gosterim; $i <= $page + $gosterim; $i++)
                    if ($i > 2 && $i < $toplamSayfa - 1) $sayfalar[] = $i;
                for ($i = max($toplamSayfa - 1, 1); $i <= $toplamSayfa; $i++) $sayfalar[] = $i;
                $sayfalar = array_unique(array_filter($sayfalar, function($x) use ($toplamSayfa) { return $x >= 1 && $x <= $toplamSayfa; }));
                sort($sayfalar);
                $onceki = 0;
                foreach ($sayfalar as $i) {
                    if ($onceki && $i > $onceki + 1) echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                    $linkQ = $_GET; $linkQ['sayfa'] = $i;
                    echo '<li class="page-item'.($page == $i ? ' active' : '').'"><a class="page-link" href="index.php?'.http_build_query($linkQ).'">'.$i.'</a></li>';
                    $onceki = $i;
                }
                ?>
                <li class="page-item<?= $page == $toplamSayfa ? ' disabled' : '' ?>">
                    <a class="page-link" href="index.php?<?= http_build_query(array_merge($_GET, ['sayfa' => min($toplamSayfa, $page + 1)])) ?>">Sonraki</a>
                </li>
            </ul>
        </nav>
    <?php endif; ?>
</div>

<script>
$(document).ready(function() {
    // TABLODA SÜTUN FİLTRELEME
    document.querySelectorAll('tfoot .col-filter').forEach(function(input, colIdx) {
        input.addEventListener('keyup', function() {
            var rows = document.querySelectorAll('#urunTablo tbody tr');
            rows.forEach(function(row) {
                var show = true;
                var filters = document.querySelectorAll('tfoot .col-filter');
                filters.forEach(function(flt, idx){
                    if(flt.value && row.cells[idx] && row.cells[idx].textContent.toLowerCase().indexOf(flt.value.toLowerCase()) === -1) {
                        show = false;
                    }
                });
                row.style.display = show ? '' : 'none';
            });
        });
    });

    // SÜTUN GİZLE/GÖSTER MENÜSÜ
    $(".dropdown-check-list .anchor").on("click", function () {
        $(this).parent().toggleClass("visible");
    });
    $(document).on('click', function(event) {
        if(!$(event.target).closest('.dropdown-check-list').length) {
            $(".dropdown-check-list").removeClass("visible");
        }
    });
    $(".sutun-checkbox").on("change", function () {
        var idx = $(this).data("idx");
        var show = $(this).is(":checked");
        $("#urunTablo").find("tr").each(function() {
            if (show) {
                $(this).find("th,td").eq(idx).show();
            } else {
                $(this).find("th,td").eq(idx).hide();
            }
        });
        var checks = [];
        $(".sutun-checkbox").each(function(i, el){ checks.push($(el).is(":checked")); });
        localStorage.setItem("sutunChecks", JSON.stringify(checks));
    });
    $(function(){
        var checks = localStorage.getItem("sutunChecks");
        if (checks) {
            try {
                checks = JSON.parse(checks);
                $(".sutun-checkbox").each(function(i, el){
                    $(el).prop("checked", checks[i]);
                    if(!checks[i]) {
                        $("#urunTablo").find("tr").each(function(){
                            $(this).find("th,td").eq(i).hide();
                        });
                    }
                });
            }catch(e){}
        }
    });

    // TableDragger: sürükle-bırak kolon
    let table = document.getElementById('urunTablo');
    if(table) {
        tableDragger(table, {
            mode: 'column',
            onlyBody: false,
            dragHandler: null,
            animation: 150
        });
    }

    // Sütun genişliklerini colResizable ile ayarla ve kalıcı hale getir
    var storageKey = 'urunTabloColWidths';
    var thCount = $("#urunTablo thead tr:first th").length;
    var savedWidths = localStorage.getItem(storageKey);
    if(savedWidths) {
        savedWidths = JSON.parse(savedWidths);
        $('#urunTablo').find('th').each(function(i, th){
            if(savedWidths[i]) $(th).css('width', savedWidths[i]);
        });
        $('#urunTablo').find('td').each(function(i, td){
            var colIdx = i % thCount;
            if(savedWidths[colIdx]) $(td).css('width', savedWidths[colIdx]);
        });
    }
    $('#urunTablo').colResizable({
        liveDrag: true,
        minWidth: 60,
        draggingClass: "dragging",
        resizeMode: 'overflow',
        onResize: function(e){
            var wArr = [];
            $('#urunTablo').find('th').each(function(i, th){
                wArr.push($(th).css('width'));
            });
            localStorage.setItem(storageKey, JSON.stringify(wArr));
            $('#urunTablo tbody tr').each(function(){
                $(this).find('td').each(function(j, td){
                    if(wArr[j]) $(td).css('width', wArr[j]);
                });
            });
        }
    });
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
