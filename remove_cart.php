/*
<script src="https://cdn.tailwindcss.com"></script>
<script>
tailwind.config = {
  theme: {
    extend: {
      colors: {
        forest: '#1e3a2f',
        moss: '#2e5c42',
        sage: '#4a8c64',
        leaf: '#72b88a',
        mint: '#b8d9c5',
        cream: '#f7f4ee',
        gold: '#c9a84c',
        dark: '#111a15'
      }
    }
  }
}
</script>
*/
<?php
session_start();
unset($_SESSION['cart'][$_GET['i']]);
$_SESSION['cart']=array_values($_SESSION['cart']);
header("Location: cart_view.php");
?>