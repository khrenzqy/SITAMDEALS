<?php
session_start();

// 1. Pastikan tidak ada spasi atau HTML di atas tag PHP ini!

// 2. Cek apakah ada parameter index yang dikirim (?i=...)
if (isset($_GET['i'])) {
    $index = $_GET['i'];
    
    // 3. Hapus barang spesifik dari session
    unset($_SESSION['cart'][$index]);
    
    // 4. Reset urutan array supaya tidak ada index yang loncat/kosong
    $_SESSION['cart'] = array_values($_SESSION['cart']);
}

// 5. Kembali ke halaman keranjang
header("Location: cart_view.php");
exit;
?>
