<?php
require_once __DIR__ . '/../../../config/db.php';
$title = 'Satınalma Yönetimi';
ob_start();
?>
<h3>Invoices</h3>
<p>Bu sayfa henüz hazırlanmadı.</p>
<?php
$content = ob_get_clean();
include __DIR__ . '/../../../views/layout.php';
?>
