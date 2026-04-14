<?php
session_start();

$index = $_GET['index'] ?? null;
$action = $_GET['action'] ?? null;

if ($index !== null && isset($_SESSION['cart'][$index])) {
    
    if ($action === 'increase') {
        $_SESSION['cart'][$index]['qty']++;
    } 
    elseif ($action === 'decrease') {
        $_SESSION['cart'][$index]['qty']--;
        
        // Hapus jika qty jadi 0
        if ($_SESSION['cart'][$index]['qty'] <= 0) {
            unset($_SESSION['cart'][$index]);
            $_SESSION['cart'] = array_values($_SESSION['cart']); // Re-index
        }
    }
}

// Redirect kembali ke cart
header("Location: cart_view.php");
exit;
?>