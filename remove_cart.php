<?php
session_start();

$index = $_GET['index'] ?? null;

if ($index !== null && isset($_SESSION['cart'][$index])) {
    unset($_SESSION['cart'][$index]);
    $_SESSION['cart'] = array_values($_SESSION['cart']); // Re-index array
}

// Redirect kembali ke cart
header("Location: cart_view.php");
exit;
?>