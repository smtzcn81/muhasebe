<?php
require_once __DIR__ . '/../../../config/db.php';
require_once __DIR__ . '/../../classes/PurchaseInvoice.php';

$model = new PurchaseInvoice($pdo);
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if($id){
    $model->delete($id);
}
header('Location: index.php');
exit;
?>
