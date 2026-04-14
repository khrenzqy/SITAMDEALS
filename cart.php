<?php
session_start();
include 'db.php';

// Ambil data keranjang dari session
$cart = $_SESSION['cart'] ?? [];
$total = 0;

// Jika keranjang kosong, tampilkan pesan informatif
if (empty($cart)) {
  ?>
  <!DOCTYPE html>
  <html lang="id">

  <head>
    <meta charset="UTF-8">
    <title>Keranjang Kosong - SiTamDeals</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;700&display=swap" rel="stylesheet" />
  </head>

  <body class="bg-[#f7f4ee] font-['DM_Sans'] flex items-center justify-center min-h-screen">
    <div class="text-center bg-white p-10 rounded-3xl shadow-xl border border-gray-100">
      <div class="text-7xl mb-6">🛒</div>
      <h2 class="text-2xl font-bold text-[#1e3a2f] mb-2">Keranjangmu masih kosong</h2>
      <p class="text-gray-400 mb-8">Sepertinya kamu belum memilih produk hemat kami.</p>
      <a href="products.php"
        class="inline-block bg-[#c9a84c] text-[#1e3a2f] px-10 py-4 rounded-xl font-bold hover:bg-[#e8c96a] transition-all">
        Mulai Belanja Sekarang
      </a>
    </div>
  </body>

  </html>
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
  <script src="https://cdn.tailwindcss.com"></script>
  <link
    href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap"
    rel="stylesheet" />
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            forest: '#1e3a2f', moss: '#2e5c42', sage: '#4a8c64',
            leaf: '#72b88a', mint: '#b8d9c5', cream: '#f7f4ee',
            gold: '#c9a84c', dark: '#111a15'
          }
        }
      }
    }
  </script>
  <style>
    body {
      font-family: 'DM Sans', sans-serif;
    }

    .playfair {
      font-family: 'Playfair Display', serif;
    }
  </style>
</head>

<body class="bg-cream">

  <div class="max-w-6xl mx-auto py-12 px-6">
    <div class="flex justify-between items-end mb-10 border-b border-forest/10 pb-6">
      <div>
        <h1 class="playfair text-4xl font-black text-forest">Keranjang Belanja</h1>
        <p class="text-sage text-sm mt-1">SiTamDeals — Solusi Belanja Hemat & Mudah</p>
      </div>
      <a href="products.php" class="text-sage hover:text-forest font-bold flex items-center gap-2 transition-colors">
        <span class="text-xl">←</span> Kembali ke Katalog
      </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

      <div class="lg:col-span-2 space-y-5">
        <?php foreach ($cart as $index => $c):
          // FIX: Tambahkan ?? 0 untuk mencegah error Undefined Key jika data session rusak
          $price = $c['price'] ?? 0;
          $qty = $c['qty'] ?? 0;
          $sub = $price * $qty;
          $total += $sub;
          $img = !empty($c['image']) ? 'img/' . $c['image'] : null;
          ?>
          <div
            class="bg-white p-6 rounded-2xl shadow-sm border border-gray-50 flex flex-col md:flex-row items-center gap-6 group hover:shadow-md transition-all">

            <div class="w-24 h-24 rounded-xl bg-gray-100 overflow-hidden flex-shrink-0 border border-gray-100">
              <?php if ($img && file_exists($img)): ?>
                <img src="<?= $img ?>" class="w-full h-full object-cover" alt="Produk">
              <?php else: ?>
                <div class="w-full h-full flex items-center justify-center text-3xl">🛒</div>
              <?php endif; ?>
            </div>

            <div class="flex-1 text-center md:text-left">
              <h3 class="font-bold text-lg text-forest"><?= htmlspecialchars($c['name']) ?></h3>
              <div class="flex flex-wrap justify-center md:justify-start items-center gap-3 mt-2">
                <span class="bg-gold/10 text-gold text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-wider">
                  Grade <?= htmlspecialchars($c['grade']) ?>
                </span>
                <span class="text-sm text-gray-400">
                  @ Rp <?= number_format($price, 0, ',', '.') ?>
                </span>
              </div>
            </div>

            <div class="flex items-center gap-3 bg-cream/50 p-2 rounded-xl border border-gray-100">
              <a href="update_cart.php?index=<?= $index ?>&action=decrease"
                class="w-8 h-8 bg-white rounded-lg flex items-center justify-center shadow-sm hover:bg-red-50 hover:text-red-500 transition-all font-bold">−</a>
              <span class="w-8 text-center font-bold text-forest"><?= $qty ?></span>
              <a href="update_cart.php?index=<?= $index ?>&action=increase"
                class="w-8 h-8 bg-forest text-white rounded-lg flex items-center justify-center shadow-md hover:bg-gold hover:text-forest transition-all font-bold">+</a>
            </div>

            <div class="text-right min-w-[120px]">
              <p class="text-xs text-gray-400 uppercase font-bold tracking-widest mb-1">Subtotal</p>
              <p class="text-xl font-black text-sage">Rp <?= number_format($sub, 0, ',', '.') ?></p>
            </div>

            <a href="remove_cart.php?index=<?= $index ?>" onclick="return confirm('Hapus item ini?')"
              class="text-gray-300 hover:text-red-500 transition-colors p-2 text-2xl">&times;</a>
          </div>
        <?php endforeach; ?>
      </div>

      <div class="lg:col-span-1">
        <div class="bg-forest p-8 rounded-3xl text-white shadow-2xl sticky top-28">
          <h2 class="playfair text-2xl font-bold mb-8 border-b border-white/10 pb-4">Ringkasan</h2>

          <div class="space-y-4 mb-8">
            <div class="flex justify-between text-white/60">
              <span>Total Barang</span>
              <span class="font-bold text-white"><?= array_sum(array_column($cart, 'qty')) ?> pcs</span>
            </div>
            <div class="flex justify-between text-white/60 border-b border-white/5 pb-4">
              <span>Pajak (PPN)</span>
              <span class="font-bold text-white">Termasuk</span>
            </div>
            <div class="flex justify-between items-center pt-2">
              <span class="text-sm font-medium text-gold uppercase tracking-[2px]">Total Bayar</span>
              <span class="text-3xl font-black text-white">Rp <?= number_format($total, 0, ',', '.') ?></span>
            </div>
          </div>

          <a href="checkout.php"
            class="block w-full bg-gold text-forest text-center py-4 rounded-xl font-black hover:bg-white hover:scale-[1.02] transition-all shadow-lg">
            Lanjutkan Pembayaran
          </a>

          <p class="text-[10px] text-center text-white/30 mt-6 uppercase tracking-widest">
            SitamDeals &bull; Surabaya, 2026
          </p>
        </div>
      </div>

    </div>
  </div>

</body>

</html>