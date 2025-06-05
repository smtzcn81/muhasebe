<?php
require_once __DIR__ . '/../../config/db.php';
$title = 'Satınalma Yönetimi';
ob_start();
?>
<div class="list-group">
    <a href="offers/" class="list-group-item list-group-item-action">Teklifler</a>
    <a href="orders/" class="list-group-item list-group-item-action">Siparişler</a>
    <a href="invoices/" class="list-group-item list-group-item-action">Faturalar</a>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../../views/layout.php';
?>
