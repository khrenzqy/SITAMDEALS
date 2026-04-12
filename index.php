<?php
session_start();
if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit;
}

include 'db.php';

// QUERY DATA PRODUK
$data = $conn->query("SELECT * FROM products");

// HANDLE ERROR QUERY
if (!$data) {
  die("Query Error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SiTamDeals – Beranda</title>

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
        moss: '#2e5c42',
        sage: '#4a8c64',
        leaf: '#72b88a',
        mint: '#b8d9c5',
        cream: '#f7f4ee',
        gold: '#c9a84c',
        'gold-light': '#e8c96a',
        dark: '#111a15'
      }
    }
  }
}
</script>

<style>
body {
  font-family: 'DM Sans', sans-serif;
}
</style>

</head>

<body class="bg-cream text-forest">

<!-- NAVBAR -->
<nav class="flex justify-between items-center px-10 py-4 bg-forest text-cream">
  <h1 class="font-playfair text-xl font-bold">
    SiTam<span class="text-gold">Deals</span>
  </h1>
  <a href="logout.php" class="text-sm hover:text-gold">Logout</a>
</nav>

<!-- HERO -->
<section class="text-center py-16 bg-forest text-cream">
  <h2 class="font-playfair text-4xl font-bold mb-4">
    Belanja Lebih <span class="text-gold">Mudah</span>
  </h2>
  <p class="text-cream/70">
    Temukan produk terbaik dengan harga terjangkau
  </p>
</section>

<!-- PRODUK -->
<section class="py-16 px-10">

  <h2 class="font-playfair text-3xl font-bold mb-10 text-center">
    Produk Kami
  </h2>

  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 max-w-5xl mx-auto">

  <?php if ($data->num_rows > 0): ?>

    <?php while ($row = $data->fetch_assoc()) { ?>

      <div class="bg-white rounded-2xl shadow hover:shadow-xl transition">

        <!-- IMAGE / ICON -->
        <div class="h-40 flex items-center justify-center text-5xl"
        style="background:linear-gradient(135deg,#b8d9c5,#72b88a)">
          🛒
        </div>

        <!-- CONTENT -->
        <div class="p-5">

          <h3 class="font-semibold text-lg">
            <?= $row['name'] ?>
          </h3>

          <p class="text-sage font-bold mt-2">
            Rp <?= number_format($row['price'] ?? 0,0,',','.') ?>
          </p>

          <button class="w-full mt-4 bg-forest text-white py-2 rounded-xl hover:bg-gold hover:text-forest transition">
            + Tambah
          </button>

        </div>

      </div>

    <?php } ?>

  <?php else: ?>

    <p class="col-span-3 text-center text-gray-400">
      Belum ada produk
    </p>

  <?php endif; ?>

  </div>

</section>

<!-- FOOTER -->
<footer class="text-center py-6 bg-dark text-white/50 text-sm">
  &copy; 2026 SiTamDeals
</footer>

</body>
</html>