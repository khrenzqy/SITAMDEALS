<?php
session_start();
include 'db.php';

// ======================
// VALIDASI AWAL
// ======================
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
  die("Keranjang kosong");
}

if (!isset($_SESSION['user'])) {
  die("Harus login");
}

$user = $_SESSION['user'];

// ======================
// INSERT ORDER
// ======================
$conn->query("
  INSERT INTO orders(user_id, status)
  VALUES({$user['id']}, 'diproses')
");

$order_id = $conn->insert_id;

// ======================
// LOOP CART
// ======================
foreach ($_SESSION['cart'] as $c) {

  // ======================
  // AMBIL PRODUCT ID (ANTI ERROR)
  // ======================
  if (isset($c['product_id'])) {
    $product_id = (int)$c['product_id'];
  } elseif (isset($c['id'])) {
    $product_id = (int)$c['id']; // fallback data lama
  } else {
    continue; // skip item rusak
  }

  // ======================
  // AMBIL DATA PRODUK
  // ======================
  $res = $conn->query("SELECT * FROM products WHERE id = $product_id");

  if (!$res || $res->num_rows == 0) {
    continue; // skip kalau produk tidak ada
  }

  $p = $res->fetch_assoc();

  // ======================
  // HITUNG HARGA BERDASARKAN GRADE
  // ======================
  $price = (int)$p['price'];

  $grade = isset($c['grade']) ? $c['grade'] : 'A';

  if ($grade == 'A') {
    $price *= 0.85;
  } elseif ($grade == 'B') {
    $price *= 0.7;
  } elseif ($grade == 'C') {
    $price *= 0.5;
  }

  $price = floor($price);

  // ======================
  // QTY
  // ======================
  $qty = isset($c['qty']) ? (int)$c['qty'] : 1;

  // ======================
  // INSERT KE ORDER ITEMS
  // ======================
  $conn->query("
    INSERT INTO order_items(order_id, product_id, grade, price, qty)
    VALUES($order_id, $product_id, '$grade', $price, $qty)
  ");
}

// ======================
// KOSONGKAN CART
// ======================
unset($_SESSION['cart']);

// ======================
// REDIRECT KE INVOICE
// ======================
header("Location: invoice.php?id=$order_id");
exit;
?>