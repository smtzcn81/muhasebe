<?php
require_once __DIR__ . '/../../../config/db.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id']);

// Sil
$stmt = $pdo->prepare("DELETE FROM urunler WHERE id = ?");
$stmt->execute([$id]);

header("Location: index.php");
exit;
