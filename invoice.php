<?php
session_start();
include 'db.php';

if (!isset($_GET['id'])) die("ID Order tidak ditemukan");
$id = (int)$_GET['id'];

// ======================
// AMBIL ORDER
// ======================
$order = $conn->query("
SELECT o.*, u.name as customer, k.name as kasir 
FROM orders o 
JOIN users u ON o.user_id = u.id 
LEFT JOIN users k ON o.kasir_id = k.id 
WHERE o.id = $id
")->fetch_assoc();

if (!$order) die("Data order tidak ditemukan");

// ======================
// AMBIL ITEMS (FIX DI SINI)
// ======================
$items = $conn->query("
SELECT oi.*, p.name 
FROM order_items oi 
JOIN products p ON oi.product_id = p.product_id
WHERE oi.order_id = $id
");

$total = 0;
?>