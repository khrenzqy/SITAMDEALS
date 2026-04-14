<?php
session_start();
include 'db.php';

if (empty($_SESSION['cart'])) die("Keranjang kosong");
if (!isset($_SESSION['user'])) die("Harus login");

$user = $_SESSION['user'];

// 1. Masukkan data awal ke tabel orders
$conn->query("
    INSERT INTO orders(user_id, status, total_price)
    VALUES({$user['id']}, 'pending', 0)
");
$order_id = $conn->insert_id;

// 2. Inisialisasi total harga mulai dari 0
$grand_total = 0; 

// 3. Proses setiap item di keranjang
foreach ($_SESSION['cart'] as $c) {
    $product_id = $c['product_id'];
    $grade      = $c['grade'];
    $qty        = (int)$c['qty'];
print_r ($c);
    // Ambil data produk
    $res = $conn->query("SELECT * FROM products WHERE product_id = $product_id");
    if (!$res || $res->num_rows == 0) continue;
    
    $p = $res->fetch_assoc();

    // Tentukan kolom stok berdasarkan Grade (A, B, atau C)
    $stock_field     = 'stock_' . strtoupper($grade);
    $available_stock = $p[$stock_field];

    if ($available_stock < $qty) {
        die("Stok grade $grade untuk produk ini tidak cukup!");
    }

    // Hitung harga diskon berdasarkan Grade
    $price = $p['price'];
    if ($grade == 'A')      $price *= 0.85; // Diskon 15%
    elseif ($grade == 'B')  $price *= 0.70; // Diskon 30%
    elseif ($grade == 'C')  $price *= 0.50; // Diskon 50%
    $price = floor($price);

    // Masukkan ke detail item pesanan
    $conn->query("
        INSERT INTO order_items(order_id, product_id, grade, price, qty)
        VALUES($order_id, $product_id, '$grade', $price, $qty)
    ");

    // AKUMULASI: Tambahkan harga barang ini ke total belanja
    $grand_total += ($price * $qty);

    // Update stok di tabel produk
    $conn->query("
        UPDATE products 
        SET $stock_field = $stock_field - $qty
        WHERE product_id = $product_id
    ");
}


print_r($grand_total);

$sql_final = "UPDATE orders SET total_price = $grand_total WHERE id = $order_id";
$conn->query($sql_final);

unset($_SESSION['cart']);
// header("Location: order_status.php?id=$order_id");
exit;
?>