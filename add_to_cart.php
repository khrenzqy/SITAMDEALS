<?php
session_start();

$id = $_POST['id'];
$name = $_POST['name'];
$base = $_POST['base_price'];
$grade = $_POST['grade'];

// HITUNG HARGA
$price = $base;
if ($grade == "A") $price = $base * 0.85;
if ($grade == "B") $price = $base * 0.7;
if ($grade == "C") $price = $base * 0.5;

$item = [
  'id' => $id,
  'name' => $name,
  'price' => (int)$price,
  'grade' => $grade,
  'qty' => 1
];

if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// CEK DUPLIKAT
$found = false;
foreach ($_SESSION['cart'] as &$c) {
  if ($c['id'] == $id && $c['grade'] == $grade) {
    $c['qty']++;
    $found = true;
    break;
  }
}

if (!$found) {
  $_SESSION['cart'][] = $item;
}

header("Location: cart.php");
exit;