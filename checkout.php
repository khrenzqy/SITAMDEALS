<?php
session_start();
include 'db.php';
if(empty($_SESSION['cart'])) die("Keranjang kosong");

$user=$_SESSION['user'];
$conn->query("INSERT INTO orders(user_id,status) VALUES(".$user['id'].",'diproses')");
$order_id=$conn->insert_id;

foreach($_SESSION['cart'] as $c){
$p=$conn->query("SELECT * FROM products WHERE id=".$c['product_id'])->fetch_assoc();
$price=$p['price'];
if($c['grade']=='A') $price*=0.85;
if($c['grade']=='B') $price*=0.7;
if($c['grade']=='C') $price*=0.5;

$conn->query("INSERT INTO order_items(order_id,product_id,grade,price,qty)
VALUES($order_id,".$c['product_id'].",'".$c['grade'].",$price,1)");
}

unset($_SESSION['cart']);
header("Location: invoice.php?id=$order_id");
?>
