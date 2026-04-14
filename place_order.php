<?php
session_start();
include 'db.php';

if (empty($_SESSION['cart'])) die("Keranjang kosong");
if (!isset($_SESSION['user'])) die("Harus login");

$user = $_SESSION['user'];

// INSERT order dulu dengan total 0, di-UPDATE setelah loop selesai
$conn->query("
    INSERT INTO orders(user_id, status, total_price)
    VALUES({$user['id']}, 'pending', 0)
");

$order_id    = $conn->insert_id;
$grand_total = 0; // Akumulator total

foreach ($_SESSION['cart'] as $c) {

    $product_id = (int)$c['product_id'];
    $grade      = $c['grade'];
    $qty        = (int)$c['qty'];

    $res = $conn->query("SELECT * FROM products WHERE product_id = $product_id");
    if (!$res || $res->num_rows == 0) continue;

    $p = $res->fetch_assoc();

    $stock_field     = 'stock_' . strtoupper($grade);
    $available_stock = $p[$stock_field];

    if ($available_stock < $qty) {
        // Hapus order yang sudah terbuat sebelum berhenti
        $conn->query("DELETE FROM orders WHERE id = $order_id");
        die("Stok tidak cukup untuk produk: " . htmlspecialchars($p['name']));
    }

    // Hitung harga sesuai grade
    $price = (float)$p['price'];
    if ($grade == 'A')     $price *= 0.85;
    elseif ($grade == 'B') $price *= 0.70;
    elseif ($grade == 'C') $price *= 0.50;
    $price = (int) floor($price);

    // Simpan ke order_items
    $conn->query("
        INSERT INTO order_items(order_id, product_id, grade, price, qty)
        VALUES($order_id, $product_id, '$grade', $price, $qty)
    ");

    // Tambahkan ke grand total
    $grand_total += $price * $qty;

    // Kurangi stok produk
    $conn->query("
        UPDATE products
        SET $stock_field = $stock_field - $qty
        WHERE product_id = $product_id
    ");
}

// Simpan grand total ke tabel orders
$conn->query("
    UPDATE orders SET total_price = $grand_total WHERE id = $order_id
");

unset($_SESSION['cart']);

header("Location: order_status.php?id=$order_id");
exit;
?>