<?php
session_start();

// VALIDASI DATA POST
if (
  !isset($_POST['id']) ||
  !isset($_POST['name']) ||
  !isset($_POST['base_price']) ||
  !isset($_POST['grade'])
) {
  die("Data tidak lengkap");
}

$product_id = (int)$_POST['id'];
$name = $_POST['name'];
$base_price = (int)$_POST['base_price'];
$grade = $_POST['grade'];

// VALIDASI GRADE
if (!in_array($grade, ['A', 'B', 'C'])) {
  die("Grade tidak valid");
}

// INIT CART
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// CEK APAKAH PRODUK SUDAH ADA DI CART
$found = false;

foreach ($_SESSION['cart'] as &$item) {
  if ($item['product_id'] == $product_id && $item['grade'] == $grade) {
    $item['qty'] += 1; 
    $found = true;
    break;
  }
}
unset($item); 


if (!$found) {
  $_SESSION['cart'][] = [
    'product_id' => $product_id, // 🔥 FIX UTAMA
    'name' => $name,
    'base_price' => $base_price,
    'grade' => $grade,
    'qty' => 1
  ];
}

header("Location: index.php");
exit;
?>