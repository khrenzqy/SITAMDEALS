<?php
session_start();
include 'db.php';

$cart = $_SESSION['cart'] ?? [];
if (!$cart) die("Cart kosong");

$user_id = $_SESSION['user']['id'] ?? 1;

$total = 0;
foreach ($cart as $c) {
  $total += $c['price'] * $c['qty'];
}

$conn->query("
INSERT INTO orders (user_id,total,status)
VALUES ($user_id,$total,'pending')
");

$order_id = $conn->insert_id;

foreach ($cart as $c) {
  $conn->query("
  INSERT INTO order_items (order_id,product_id,price,qty,grade)
  VALUES (
    {$order_id},
    {$c['id']},
    {$c['price']},
    {$c['qty']},
    '{$c['grade']}'
  )
  ");
}

unset($_SESSION['cart']);

header("Location: status.php?id=$order_id");
exit;