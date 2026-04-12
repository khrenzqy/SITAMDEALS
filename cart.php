<?php
session_start();
$cart = $_SESSION['cart'] ?? [];
$total = 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Keranjang</title>

<script src="https://cdn.tailwindcss.com"></script>

<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>

<script>
tailwind.config = {
  theme: {
    extend: {
      fontFamily: {
        playfair: ['"Playfair Display"', 'serif'],
        dm: ['"DM Sans"', 'sans-serif']
      },
      colors: {
        forest: '#1e3a2f',
        sage: '#4a8c64',
        cream: '#f7f4ee',
        gold: '#c9a84c'
      }
    }
  }
}
</script>

<style>
body { font-family: 'DM Sans', sans-serif; }
</style>

</head>

<body class="bg-cream">

<div class="max-w-5xl mx-auto py-16 px-6">

<h1 class="font-playfair text-3xl font-black text-forest mb-10">
  Keranjang Belanja
</h1>

<?php if ($cart): ?>

<div class="space-y-4">

<?php foreach ($cart as $c): 
  $sub = $c['price'] * $c['qty'];
  $total += $sub;
?>

<div class="bg-white p-5 rounded-2xl shadow flex justify-between items-center">

  <div>
    <div class="font-semibold"><?= $c['name'] ?></div>
    <div class="text-sm text-gray-400">Grade <?= $c['grade'] ?></div>
  </div>

  <div class="text-center">
    <div class="text-sm">Qty</div>
    <div class="font-bold"><?= $c['qty'] ?></div>
  </div>

  <div class="text-right">
    <div class="text-sage font-bold">
      Rp <?= number_format($sub,0,',','.') ?>
    </div>
  </div>

</div>

<?php endforeach; ?>

</div>

<!-- TOTAL -->
<div class="mt-10 text-right">
  <div class="text-lg">Total</div>
  <div class="text-3xl font-bold text-sage">
    Rp <?= number_format($total,0,',','.') ?>
  </div>
</div>

<!-- CHECKOUT BUTTON -->
<a href="checkout.php"
class="mt-6 inline-block bg-gold text-forest px-6 py-3 rounded-xl font-bold hover:opacity-90">
Checkout
</a>

<?php else: ?>

<p class="text-gray-400">Keranjang kosong</p>

<?php endif; ?>

</div>

</body>
</html>