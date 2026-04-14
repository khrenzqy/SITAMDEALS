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

<?php
session_start();
include 'db.php';

$cart = $_SESSION['cart'] ?? [];
$total = 0;

if (empty($cart)) {
  ?>
  <div class="min-h-screen bg-cream flex items-center justify-center">
    <div class="text-center">
      <div class="text-6xl mb-4">🛒</div>
      <p class="text-gray-400 text-xl mb-6">Keranjang kosong 😢</p>
      <a href="index.php" class="inline-block bg-gold text-forest px-8 py-4 rounded-xl font-bold hover:opacity-90">
        Mulai Belanja
      </a>
    </div>
  </div>
  <?php
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Keranjang Belanja - SiTamDeals</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>
  <style>
    body { font-family: 'DM Sans', sans-serif; }
    .playfair { font-family: 'Playfair Display', serif; }
  </style>
</head>

<body class="bg-cream">

<div class="max-w-6xl mx-auto py-10 px-6">

  <!-- Header -->
  <div class="flex justify-between items-center mb-8">
    <h1 class="playfair text-4xl font-black text-forest">
      Keranjang Belanja
    </h1>
    <a href="index.php" class="text-sage hover:text-forest font-medium flex items-center gap-2">
      <span>←</span> Lanjut Belanja
    </a>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Cart Items (Left) -->
    <div class="lg:col-span-2 space-y-4">
      
      <?php foreach ($cart as $index => $c): 
        $sub = $c['price'] * $c['qty'];
        $total += $sub;
      ?>

      <div class="bg-white p-6 rounded-2xl shadow-sm hover:shadow-md transition-shadow">
        
        <div class="flex items-center gap-6">
          
          <!-- Product Info -->
          <div class="flex-1">
            <h3 class="font-bold text-lg text-forest mb-1">
              <?= htmlspecialchars($c['name']) ?>
            </h3>
            <div class="flex items-center gap-3 text-sm text-gray-500">
              <span class="bg-sage/10 text-sage px-3 py-1 rounded-full font-medium">
                Grade <?= htmlspecialchars($c['grade']) ?>
              </span>
              <span>
                @ Rp <?= number_format($c['price'], 0, ',', '.') ?>
              </span>
            </div>
          </div>

          <!-- Quantity Controls -->
          <div class="flex items-center gap-2 bg-gray-50 rounded-xl p-2">
            <a href="update_cart.php?index=<?= $index ?>&action=decrease" 
               class="w-9 h-9 bg-white hover:bg-gray-100 border border-gray-200 rounded-lg flex items-center justify-center text-lg font-bold text-gray-600 transition">
              −
            </a>
            
            <div class="w-16 text-center">
              <div class="font-bold text-lg text-forest"><?= $c['qty'] ?></div>
            </div>
            
            <a href="update_cart.php?index=<?= $index ?>&action=increase" 
               class="w-9 h-9 bg-sage hover:bg-moss text-white rounded-lg flex items-center justify-center text-lg font-bold transition">
              +
            </a>
          </div>

          <!-- Subtotal -->
          <div class="text-right min-w-[140px]">
            <div class="font-bold text-xl text-sage">
              Rp <?= number_format($sub, 0, ',', '.') ?>
            </div>
          </div>

          <!-- Remove Button -->
          <a href="remove_cart.php?index=<?= $index ?>" 
             onclick="return confirm('Yakin hapus item ini dari keranjang?')"
             class="w-10 h-10 bg-red-50 hover:bg-red-100 text-red-500 hover:text-red-700 rounded-lg flex items-center justify-center text-2xl font-bold transition"
             title="Hapus">
            ×
          </a>

        </div>

      </div>

      <?php endforeach; ?>

    </div>

    <!-- Summary (Right) -->
    <div class="lg:col-span-1">
      
      <div class="bg-white p-6 rounded-2xl shadow-sm sticky top-6">
        
        <h2 class="playfair text-2xl font-bold text-forest mb-6">
          Ringkasan Belanja
        </h2>

        <!-- Items Count -->
        <div class="flex justify-between text-gray-600 mb-3">
          <span>Total Item</span>
          <span class="font-semibold"><?= count($cart) ?> produk</span>
        </div>

        <!-- Total Quantity -->
        <div class="flex justify-between text-gray-600 mb-6 pb-6 border-b border-gray-200">
          <span>Total Barang</span>
          <span class="font-semibold">
            <?= array_sum(array_column($cart, 'qty')) ?> pcs
          </span>
        </div>

        <!-- Total Price -->
        <div class="flex justify-between items-center mb-6">
          <span class="text-lg font-semibold text-forest">Total Pembayaran</span>
          <div class="text-right">
            <div class="text-3xl font-bold text-sage">
              Rp <?= number_format($total, 0, ',', '.') ?>
            </div>
          </div>
        </div>

        <!-- Checkout Button -->
        <a href="checkout.php" 
           class="block w-full bg-gold hover:bg-gold/90 text-forest text-center px-6 py-4 rounded-xl font-bold text-lg transition shadow-md hover:shadow-lg">
          Checkout Sekarang
        </a>

        <!-- Continue Shopping Link -->
        <a href="products.php" 
           class="block w-full text-center text-sage hover:text-forest font-medium mt-4">
          Tambah Produk Lagi
        </a>

      </div>

    </div>

  </div>

</div>

</body>
</html>