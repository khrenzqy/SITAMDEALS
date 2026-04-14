<?php
session_start();
unset($_SESSION['cart']);
echo "Cart sudah dihapus! <br>";
echo "<a href='products.php'>Belanja lagi</a> | ";
echo "<a href='cart.php'>Lihat cart</a>";
?>