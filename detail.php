<?php
session_start();
include 'db.php';

if (!isset($_GET['id'])) die("Produk tidak ditemukan");
$id = intval($_GET['id']);

$p = $conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();
if (!$p) die("Produk tidak ditemukan");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Detail Produk</title>

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
        mint: '#b8d9c5',
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

<script>
function updatePrice() {
  let g = document.getElementById("grade").value;
  let base = <?= $p['price'] ?>;
  let price = base;

  if (g === "A") price = base * 0.85;
  if (g === "B") price = base * 0.7;
  if (g === "C") price = base * 0.5;

  document.getElementById("price").innerText =
    "Rp " + Math.floor(price).toLocaleString('id-ID');
}

function submitCart() {
  let g = document.getElementById("grade").value;
  if (!g) {
    alert("Pilih grade dulu!");
    return false;
  }
  document.getElementById("g").value = g;
  return true;
}
</script>

</head>

<body class="bg-cream">

<div class="max-w-5xl mx-auto py-16 px-6">

  <a href="index.php" class="text-sage mb-6 inline-block hover:underline">← Kembali</a>

  <div class="grid md:grid-cols-2 gap-12 items-center">

    <!-- IMAGE -->
    <div class="rounded-3xl overflow-hidden shadow-lg">
      <img src="<?= $p['image'] ?>" class="w-full h-[350px] object-cover">
    </div>

    <!-- DETAIL -->
    <div>

      <h1 class="font-playfair text-3xl font-black text-forest mb-3">
        <?= $p['name'] ?>
      </h1>

      <div id="price" class="text-3xl font-bold text-sage mb-2">
        Rp <?= number_format($p['price'],0,',','.') ?>
      </div>

      <div class="text-sm text-gray-400 mb-6">
        Harga Layak: Rp <?= number_format($p['price'],0,',','.') ?>
      </div>

      <!-- GRADE -->
      <div class="mb-6">
        <label class="block text-sm font-semibold mb-2">Pilih Grade</label>
        <select id="grade" onchange="updatePrice()"
          class="w-full border border-mint p-3 rounded-xl focus:ring-2 focus:ring-sage">
          <option value="">-- Pilih Grade --</option>
          <option value="A">Grade A (Diskon 15%)</option>
          <option value="B">Grade B (Diskon 30%)</option>
          <option value="C">Grade C (Diskon 50%)</option>
        </select>
      </div>

      <p class="text-gray-600 mb-6">
        <?= $p['description'] ?? 'Produk berkualitas SiTamDeals.' ?>
      </p>

      <!-- FORM CART -->
      <form action="add_to_cart.php" method="POST" onsubmit="return submitCart()">
        <input type="hidden" name="id" value="<?= $p['id'] ?>">
        <input type="hidden" name="name" value="<?= $p['name'] ?>">
        <input type="hidden" name="base_price" value="<?= $p['price'] ?>">
        <input type="hidden" name="grade" id="g">

        <button class="bg-forest text-white px-6 py-3 rounded-xl hover:bg-gold hover:text-forest transition shadow w-full">
          + Tambah ke Keranjang
        </button>
      </form>

    </div>

  </div>

</div>

</body>
</html>