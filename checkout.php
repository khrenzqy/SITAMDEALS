<?php
session_start();
include 'db.php';

// VALIDASI
if (empty($_SESSION['cart'])) die("Keranjang kosong");
if (!isset($_SESSION['user'])) die("Harus login");

$user = $_SESSION['user'];

// INSERT ORDER
$conn->query("
INSERT INTO orders(user_id,status)
VALUES({$user['id']},'diproses')
");

$order_id = $conn->insert_id;

// LOOP CART
foreach ($_SESSION['cart'] as $c) {

  // VALIDASI DATA
  if (!isset($c['product_id'])) {
    die("Cart error: product_id tidak ada");
  }

  $product_id = (int)$c['product_id'];

  // AMBIL PRODUK
  $res = $conn->query("SELECT * FROM products WHERE id=$product_id");

  if ($res->num_rows == 0) continue;

  $p = $res->fetch_assoc();

  // HITUNG HARGA
  $price = $p['price'];

  if ($c['grade'] == 'A') $price *= 0.85;
  elseif ($c['grade'] == 'B') $price *= 0.7;
  elseif ($c['grade'] == 'C') $price *= 0.5;

  $price = floor($price);
  $qty = isset($c['qty']) ? (int)$c['qty'] : 1;

  // INSERT ITEM (SUDAH FIX)
  $conn->query("
  INSERT INTO order_items(order_id,product_id,grade,price,qty)
  VALUES($order_id,$product_id,'{$c['grade']}',$price,$qty)
  ");
}

// KOSONGKAN CART
unset($_SESSION['cart']);

// REDIRECT
header("Location: invoice.php?id=$order_id");
exit;
?>