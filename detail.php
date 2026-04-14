<?php
session_start();
include 'db.php';

if (!isset($_GET['product_id'])) die("Produk tidak ditemukan");
$id = intval($_GET['product_id']);

$p = $conn->query("SELECT * FROM products WHERE product_id=$id")->fetch_assoc();
if (!$p) die("Produk tidak ditemukan");

// --- PERBAIKAN LOGIKA GAMBAR ---
$imageName = trim($p['image']); // Menghilangkan spasi tak terlihat
$imagePath = 'img/' . $imageName; 

// Cek apakah file benar-benar ada di folder
$imageExists = (!empty($imageName) && file_exists($imagePath));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($p['name']) ?> - SiTamDeals</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            playfair: ['"Playfair Display"', 'serif'],
            dm: ['"DM Sans"', 'sans-serif']
          },
          colors: { forest: '#1e3a2f', sage: '#4a8c64', mint: '#b8d9c5', cream: '#f7f4ee', gold: '#c9a84c' }
        }
      }
    }
    </script>
    <script>
    function updatePrice() {
      let g = document.getElementById("grade").value;
      let base = <?= $p['price'] ?>;
      let price = base;
      let stockA = <?= (int)$p['stock_A'] ?>;
      let stockB = <?= (int)$p['stock_B'] ?>;
      let stockC = <?= (int)$p['stock_C'] ?>;
      let stock = 0;
      if (g === "A") { price = base * 1; stock = stockA; } 
      else if (g === "B") { price = base * 0.8; stock = stockB; } 
      else if (g === "C") { price = base * 0.6; stock = stockC; }
      document.getElementById("price").innerText = "Rp " + Math.floor(price).toLocaleString('id-ID');
      let stockText = document.getElementById("stockText");
      if (g === "") { stockText.innerText = "-"; return; }
      if (stock <= 0) {
        stockText.innerText = "Habis ❌";
        stockText.classList.add("text-red-500");
      } else {
        stockText.innerText = stock + " tersedia";
        stockText.classList.remove("text-red-500");
      }
    }
    function submitCart() {
      let g = document.getElementById("grade").value;
      if (!g) { alert("Pilih grade dulu!"); return false; }
      document.getElementById("g").value = g;
      return true;
    }
    </script>
</head>
<body class="bg-cream min-h-screen">
<div class="max-w-5xl mx-auto py-6 md:py-16 px-4 md:px-6">
    <a href="index.php" class="text-sage mb-6 inline-flex items-center gap-2 hover:underline font-bold text-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        KEMBALI
    </a>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-16 items-start">
        
        <div class="rounded-3xl overflow-hidden shadow-xl bg-white md:sticky md:top-8 border border-mint/20">
            <?php if ($imageExists): ?>
                <img 
                    src="<?= $imagePath ?>?v=<?= time() ?>" 
                    alt="<?= htmlspecialchars($p['name']) ?>" 
                    class="w-full h-[300px] md:h-[500px] object-cover"
                >
            <?php else: ?>
                <div class="w-full h-[300px] md:h-[500px] flex flex-col items-center justify-center bg-slate-50 text-center p-6">
                    <span class="text-6xl mb-4">🖼️</span>
                    <p class="text-forest font-bold">Gambar Tidak Ditemukan</p>
                    <p class="text-xs text-gray-400 mt-2 italic">
                        Cek file: <span class="text-red-500">C:\xampp\htdocs\SITAMDEALS\<?= $imagePath ?></span>
                    </p>
                </div>
            <?php endif; ?>
        </div>

        <div class="flex flex-col">
            <h1 class="font-playfair text-3xl md:text-5xl font-black text-forest mb-4 leading-tight">
                <?= htmlspecialchars($p['name']) ?>
            </h1>

            <div class="bg-white/60 backdrop-blur-md p-6 rounded-2xl border border-mint/30 mb-8 shadow-sm">
                <div id="price" class="text-3xl font-bold text-sage mb-1">
                    Rp <?= number_format($p['price'],0,',','.') ?>
                </div>
                <div class="flex items-center gap-2 text-sm">
                    <span class="text-gray-400">Stok:</span>
                    <span id="stockText" class="font-bold text-forest">-</span>
                </div>
            </div>

            <div class="space-y-6">
                <div>
                    <label class="block text-xs font-black text-forest mb-2 uppercase tracking-widest">Pilih Kondisi (Grade)</label>
                    <select id="grade" onchange="updatePrice()"
                        class="w-full bg-white border-2 border-mint/20 p-4 rounded-xl focus:ring-4 focus:ring-sage/10 outline-none transition-all cursor-pointer">
                        <option value="">-- Klik untuk memilih --</option>
                        <option value="A">Grade A (Sangat Baik)</option>
                        <option value="B">Grade B (Baik)</option>
                        <option value="C">Grade C (Cukup)</option>
                    </select>
                </div>

                <div class="text-gray-600 text-sm leading-relaxed">
                    <label class="block text-xs font-black text-forest mb-2 uppercase tracking-widest">Deskripsi Produk</label>
                    <p class="bg-white/30 p-4 rounded-xl border border-mint/10 italic">
                        <?= nl2br(htmlspecialchars($p['description'] ?? 'Produk pilihan terbaik dari SiTamDeals.')) ?>
                    </p>
                </div>

                <form action="add_to_cart.php" method="POST" onsubmit="return submitCart()" class="pt-4">
                    <input type="hidden" name="id" value="<?= $p['product_id'] ?>">
                    <input type="hidden" name="name" value="<?= htmlspecialchars($p['name']) ?>">
                    <input type="hidden" name="base_price" value="<?= $p['price'] ?>">
                    <input type="hidden" name="grade" id="g">

                    <button class="bg-forest text-white py-4 rounded-xl hover:bg-gold hover:text-forest transition-all shadow-lg active:scale-95 w-full font-bold text-lg">
                        + Keranjang
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>