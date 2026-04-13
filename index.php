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
if (!isset($_SESSION['user']))
  header("Location: login.php");
include 'db.php';
$data = $conn->query("SELECT * FROM products");
?>

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>SiTamDeals – Beranda</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link
    href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap"
    rel="stylesheet" />
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: { playfair: ['"Playfair Display"', 'serif'], dm: ['"DM Sans"', 'sans-serif'] },
          colors: {
            forest: '#1e3a2f', moss: '#2e5c42', sage: '#4a8c64',
            leaf: '#72b88a', mint: '#b8d9c5', cream: '#f7f4ee',
            gold: '#c9a84c', 'gold-light': '#e8c96a', dark: '#111a15'
          },
          animation: {
            'pulse-ring': 'pulseRing 6s ease-in-out infinite',
            'bounce-down': 'bounceDown 2s ease infinite',
            'fade-up': 'fadeUp 0.8s ease both',
          },
          keyframes: {
            pulseRing: { '0%,100%': { transform: 'scale(1)', opacity: '1' }, '50%': { transform: 'scale(1.05)', opacity: '0.6' } },
            bounceDown: { '0%,100%': { transform: 'translateX(-50%) translateY(0)' }, '50%': { transform: 'translateX(-50%) translateY(8px)' } },
            fadeUp: { from: { opacity: '0', transform: 'translateY(30px)' }, to: { opacity: '1', transform: 'translateY(0)' } },
          }
        }
      }
    }
  </script>
  <style>
    body {
      font-family: 'DM Sans', sans-serif;
    }

    .nav-link::after {
      content: '';
      position: absolute;
      bottom: -4px;
      left: 0;
      width: 0;
      height: 2px;
      background: #c9a84c;
      transition: width .3s;
    }

    .nav-link:hover::after,
    .nav-link.active::after {
      width: 100%;
    }

    .cat-card::before {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(135deg, #1e3a2f, #2e5c42);
      opacity: 0;
      transition: opacity .35s;
      border-radius: 20px;
    }

    .cat-card:hover::before {
      opacity: 1;
    }

    @keyframes pingMap {
      0% {
        box-shadow: 0 0 0 0 rgba(201, 168, 76, 0.6)
      }

      70% {
        box-shadow: 0 0 0 20px rgba(201, 168, 76, 0)
      }

      100% {
        box-shadow: 0 0 0 0 rgba(201, 168, 76, 0)
      }
    }

    .map-ping {
      animation: pingMap 2s ease-in-out infinite;
    }
  </style>
</head>

<body class="bg-cream text-forest overflow-x-hidden">

  <!-- NAVBAR -->
  <nav
    class="fixed top-0 left-0 right-0 z-50 flex justify-between items-center px-[6%] h-[72px] bg-forest/95 backdrop-blur-md border-b border-leaf/10">
    <a href="beranda.html" class="font-playfair text-xl font-black text-cream tracking-wide">SiTam<span
        class="text-gold">Deals</span></a>
    <ul class="flex gap-8 list-none">
      <li><a href="beranda.html"
          class="nav-link active relative text-gold text-sm font-medium tracking-widest uppercase transition-colors">Home</a>
      </li>
      <li><a href="profil.html"
          class="nav-link relative text-cream/75 hover:text-cream text-sm font-medium tracking-widest uppercase transition-colors">Tentang
          Kami</a></li>
      <li><a href="contact.html"
          class="nav-link relative text-cream/75 hover:text-cream text-sm font-medium tracking-widest uppercase transition-colors">Kontak</a>
      </li>
      <li><a href="contact.html"
          class="bg-gold text-forest text-sm font-semibold px-5 py-2 rounded-full hover:bg-gold-light transition-colors">Hubungi
          Kami</a></li>
    </ul>
  </nav>

  <!-- HERO -->
  <section class="relative min-h-screen flex items-center px-[6%] overflow-hidden"
    style="background: linear-gradient(135deg,rgba(18,38,28,.88) 0%,rgba(46,92,66,.7) 60%,rgba(114,184,138,.3) 100%), url('https://images.unsplash.com/photo-1578916171728-46686eac8d58?w=1600&q=80') center/cover no-repeat;">
    <!-- deco rings -->
    <div class="absolute -top-24 -right-24 w-[600px] h-[600px] rounded-full border border-gold/10 animate-pulse-ring">
    </div>
    <div class="absolute -top-12 -right-12 w-[400px] h-[400px] rounded-full border border-gold/15"
      style="animation:pulseRing 6s ease-in-out 1.5s infinite"></div>

    <div class="relative z-10 max-w-2xl" style="animation:fadeUp .8s ease both">
      <div
        class="inline-flex items-center gap-2 bg-gold/15 border border-gold/40 text-gold-light text-xs font-semibold tracking-[2px] uppercase px-4 py-1.5 rounded-full mb-7">
        ✦ Swalayan Tambah Jaya
      </div>
      <h1 class="font-playfair text-5xl lg:text-6xl font-black text-cream leading-tight mb-6">
        Belanja Lebih <em class="not-italic text-gold">Mudah,</em><br>
        Hidup Lebih <em class="not-italic text-gold">Hemat</em>
      </h1>
      <p class="text-cream/78 text-lg leading-relaxed max-w-lg mb-10 font-light">
        Temukan ribuan produk berkualitas dengan harga yang transparan dan terjangkau. Dari kebutuhan dapur hingga
        perawatan diri — semua ada di Tambah Jaya Market.
      </p>
      <div class="flex gap-4 flex-wrap">
        <a href="#produk"
          class="bg-gold text-forest font-bold px-8 py-3.5 rounded-full shadow-lg shadow-gold/30 hover:bg-gold-light hover:-translate-y-1 transition-all">Lihat
          Produk</a>
        <a href="profil.html"
          class="border border-cream/40 text-cream font-semibold px-8 py-3.5 rounded-full hover:border-mint hover:text-mint hover:-translate-y-1 transition-all">Tentang
          Kami</a>
      </div>
    </div>

    <div
      class="absolute bottom-9 left-1/2 flex flex-col items-center gap-2 text-cream/50 text-xs tracking-[3px] uppercase"
      style="animation:bounceDown 2s ease infinite; transform:translateX(-50%)">
      Scroll <span class="text-lg">↓</span>
    </div>
  </section>

  <!-- STATS STRIP -->
  <div class="bg-forest flex justify-center py-7 px-[6%]">
    <div class="flex w-full max-w-3xl">
      <div class="flex-1 text-center px-6 border-r border-white/8">
        <div class="font-playfair text-3xl font-black text-gold leading-none mb-1">1000+</div>
        <div class="text-xs text-white/50 tracking-widest uppercase">Produk</div>
      </div>
      <div class="flex-1 text-center px-6 border-r border-white/8">
        <div class="font-playfair text-3xl font-black text-gold leading-none mb-1">10K+</div>
        <div class="text-xs text-white/50 tracking-widest uppercase">Pelanggan</div>
      </div>
      <div class="flex-1 text-center px-6 border-r border-white/8">
        <div class="font-playfair text-3xl font-black text-gold leading-none mb-1">5+</div>
        <div class="text-xs text-white/50 tracking-widest uppercase">Tahun Berdiri</div>
      </div>
      <div class="flex-1 text-center px-6">
        <div class="font-playfair text-3xl font-black text-gold leading-none mb-1">4.9★</div>
        <div class="text-xs text-white/50 tracking-widest uppercase">Rating</div>
      </div>
    </div>
  </div>

  <!-- CATEGORIES -->
  <section class="py-24 px-[6%] bg-cream" id="kategori">
    <div class="text-center mb-14">
      <div class="text-xs font-semibold tracking-[3px] uppercase text-sage mb-3">Jelajahi Kategori</div>
      <h2 class="font-playfair text-4xl font-black text-forest mb-4">Semua Ada di Sini</h2>
      <p class="text-gray-500 max-w-md mx-auto leading-relaxed">Kami menyediakan berbagai kebutuhan sehari-hari dalam
        satu tempat yang nyaman dan terpercaya.</p>
    </div>
      <div
        class="cat-card relative bg-white rounded-2xl p-9 text-center cursor-pointer hover:-translate-y-1.5 hover:shadow-xl transition-all overflow-hidden group">
        <span class="relative z-10 text-4xl mb-4 block group-hover:scale-110 transition-transform">🧀</span>
        <div class="relative z-10 text-sm font-semibold text-forest group-hover:text-white transition-colors">Susu &
          Olahan</div>
      </div>
      <div
        class="cat-card relative bg-white rounded-2xl p-9 text-center cursor-pointer hover:-translate-y-1.5 hover:shadow-xl transition-all overflow-hidden group">
        <span class="relative z-10 text-4xl mb-4 block group-hover:scale-110 transition-transform">🍜</span>
        <div class="relative z-10 text-sm font-semibold text-forest group-hover:text-white transition-colors">Bumbu &
          Rempah</div>
      </div>
      <div
        class="cat-card relative bg-white rounded-2xl p-9 text-center cursor-pointer hover:-translate-y-1.5 hover:shadow-xl transition-all overflow-hidden group">
        <span class="relative z-10 text-4xl mb-4 block group-hover:scale-110 transition-transform">🧴</span>
        <div class="relative z-10 text-sm font-semibold text-forest group-hover:text-white transition-colors">Perawatan
          Diri</div>
      </div>
      <div
        class="cat-card relative bg-white rounded-2xl p-9 text-center cursor-pointer hover:-translate-y-1.5 hover:shadow-xl transition-all overflow-hidden group">
        <span class="relative z-10 text-4xl mb-4 block group-hover:scale-110 transition-transform">🧹</span>
        <div class="relative z-10 text-sm font-semibold text-forest group-hover:text-white transition-colors">Kebersihan
          Rumah</div>
      </div>
      <div
        class="cat-card relative bg-white rounded-2xl p-9 text-center cursor-pointer hover:-translate-y-1.5 hover:shadow-xl transition-all overflow-hidden group">
        <span class="relative z-10 text-4xl mb-4 block group-hover:scale-110 transition-transform">🍪</span>
        <div class="relative z-10 text-sm font-semibold text-forest group-hover:text-white transition-colors">Camilan &
          Minuman</div>
      </div>
      <div
        class="cat-card relative bg-white rounded-2xl p-9 text-center cursor-pointer hover:-translate-y-1.5 hover:shadow-xl transition-all overflow-hidden group">
        <span class="relative z-10 text-4xl mb-4 block group-hover:scale-110 transition-transform">👶</span>
        <div class="relative z-10 text-sm font-semibold text-forest group-hover:text-white transition-colors">Kebutuhan
          Bayi</div>
      </div>
    </div>
  </section>

<!-- FEATURED PRODUCTS -->
<section class="py-24 px-[6%] bg-[#f0f4ee]" id="produk">
  <div class="flex justify-between items-end mb-12 max-w-5xl mx-auto">
    <div>
      <div class="text-xs font-semibold tracking-[3px] uppercase text-sage mb-2">Pilihan Unggulan</div>
      <h2 class="font-playfair text-4xl font-black text-forest">Produk Terlaris</h2>
    </div>
    <a href=""
      class="text-sage text-sm font-semibold border-b border-sage pb-0.5 hover:text-forest transition-colors">
      Lihat Semua →
    </a>
  </div>

  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 max-w-5xl mx-auto">

  <?php if ($data && $data->num_rows > 0): ?>

    <?php while ($row = $data->fetch_assoc()) { ?>
    

      <!-- CARD PRODUK -->
<a href="detail.php?product_id=<?php echo $row['product_id']; ?>" class="group">
      <div class="bg-white rounded-2xl overflow-hidden hover:-translate-y-2 hover:shadow-2xl transition-all">

        <!-- IMAGE / ICON -->
        <div class="h-48 flex items-center justify-center text-5xl relative"
          style="background:linear-gradient(135deg,#b8d9c5,#72b88a)">
          
          🛒
          
          <span class="absolute top-3 left-3 bg-gold text-forest text-xs font-bold px-2 py-1 rounded-full">
            PRODUK
          </span>
        </div>

        <!-- CONTENT -->
        <div class="p-5">

          <!-- NAMA -->
          <div class="font-semibold text-forest text-sm mb-1">
            <?= $row['name'] ?>
          </div>

          <!-- HARGA -->
          <div class="font-playfair text-xl font-bold text-sage mb-3">
            Rp <?= number_format($row['price'], 0, ',', '.') ?>
          </div>

          <!-- BUTTON -->
          <button
            class="w-8 h-8 bg-forest text-white rounded-full text-lg hover:bg-gold hover:text-forest transition-colors flex items-center justify-center">
            +
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

  <!-- WHY US -->
  <section class="py-24 px-[6%] bg-forest relative overflow-hidden">
    <div class="absolute -top-48 -left-48 w-[600px] h-[600px] rounded-full"
      style="background:radial-gradient(circle,rgba(114,184,138,.07) 0%,transparent 70%)"></div>
    <div class="max-w-5xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-20 items-center relative z-10">
      <div>
        <div class="text-xs font-semibold tracking-[3px] uppercase text-leaf mb-3">Keunggulan Kami</div>
        <h2 class="font-playfair text-4xl font-black text-cream mb-5">Mengapa Memilih Tambah Jaya?</h2>
        <p class="text-cream/65 leading-relaxed text-sm mb-9">Kami bukan sekadar swalayan biasa. Sejak berdiri, kami
          berkomitmen memberikan pengalaman belanja yang menyenangkan, harga jujur, dan pelayanan yang hangat.</p>
        <div class="flex flex-col gap-5">
          <div class="flex gap-4 items-start">
            <div
              class="w-11 h-11 min-w-[44px] bg-gold/12 border border-gold/25 rounded-xl flex items-center justify-center text-xl">
              🏷️</div>
            <div>
              <h4 class="text-cream font-semibold text-sm mb-1">Harga Transparan</h4>
              <p class="text-cream/50 text-xs leading-relaxed">Tidak ada biaya tersembunyi. Harga yang tertera adalah
                harga yang Anda bayar.</p>
            </div>
          </div>
          <div class="flex gap-4 items-start">
            <div
              class="w-11 h-11 min-w-[44px] bg-gold/12 border border-gold/25 rounded-xl flex items-center justify-center text-xl">
              ✅</div>
            <div>
              <h4 class="text-cream font-semibold text-sm mb-1">Produk Terjamin Kualitasnya</h4>
              <p class="text-cream/50 text-xs leading-relaxed">Setiap produk melalui seleksi ketat sebelum sampai ke
                tangan Anda.</p>
            </div>
          </div>
          <div class="flex gap-4 items-start">
            <div
              class="w-11 h-11 min-w-[44px] bg-gold/12 border border-gold/25 rounded-xl flex items-center justify-center text-xl">
              💬</div>
            <div>
              <h4 class="text-cream font-semibold text-sm mb-1">Pelayanan Ramah</h4>
              <p class="text-cream/50 text-xs leading-relaxed">Tim kami siap membantu Anda dengan sepenuh hati setiap
                hari.</p>
            </div>
          </div>
        </div>
      </div>
      <div class="grid grid-cols-2 gap-4">
        <div
          class="col-span-2 bg-gold/8 border border-gold/20 rounded-2xl p-7 text-center hover:border-gold/40 transition-colors">
          <div class="text-3xl mb-3">🏪</div>
          <h3 class="font-playfair text-xl text-cream mb-1">Toko Lokal Terpercaya</h3>
          <p class="text-cream/50 text-xs">Melayani warga Surabaya selama lebih dari 5 tahun</p>
        </div>
        <div
          class="bg-white/4 border border-white/8 rounded-2xl p-7 text-center hover:bg-white/7 hover:border-gold/25 transition-all">
          <div class="text-3xl mb-3">🚚</div>
          <h3 class="font-playfair text-lg text-cream mb-1">Ada di Marketplace</h3>
          <p class="text-cream/50 text-xs">Shopee & Tokopedia</p>
        </div>
        <div
          class="bg-white/4 border border-white/8 rounded-2xl p-7 text-center hover:bg-white/7 hover:border-gold/25 transition-all">
          <div class="text-3xl mb-3">💳</div>
          <h3 class="font-playfair text-lg text-cream mb-1">Pembayaran Mudah</h3>
          <p class="text-cream/50 text-xs">Cash, transfer & QRIS</p>
        </div>
      </div>
    </div>
  </section>

  <!-- FOOTER -->
  <footer class="bg-dark text-white/40 text-center py-8 text-sm tracking-wide">
    &copy; 2026 <span class="text-gold">SiTamDeals</span> — Proyek Website UPN "Veteran" Jawa Timur
  </footer>

</body>

</html>