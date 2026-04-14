<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
if ($_SESSION['user']['role'] !== 'kasir') {
    header("Location: index.php");
    exit;
}

// ── PROSES FORM POST (tombol ubah status) ──
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    $orderId   = (int)$_POST['order_id'];
    $newStatus = $_POST['status'];

    $allowed = ['pending','diterima','diproses','siap_diambil','selesai','ditolak'];
    if ($orderId > 0 && in_array($newStatus, $allowed)) {
        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $newStatus, $orderId);
        $stmt->execute();
        $stmt->close();
    }

    // Redirect supaya tidak double-submit saat refresh
    header("Location: admin_orders.php?updated=" . $orderId);
    exit;
}

$userName    = $_SESSION['user']['name'] ?? 'Kasir';
$userInitial = strtoupper(substr($userName, 0, 1));

// Ambil semua pesanan dari DB
$orders = $conn->query("
    SELECT o.*, u.name AS user_name
    FROM orders o
    LEFT JOIN users u ON o.user_id = u.id
    ORDER BY o.id DESC
");

$totalOrders  = 0;
$pendingCount = 0;
$aktifCount   = 0;
$allOrders    = [];
while ($row = $orders->fetch_assoc()) {
    $allOrders[] = $row;
    $totalOrders++;
    if ($row['status'] === 'pending') $pendingCount++;
    if (in_array($row['status'], ['diterima','diproses','siap_diambil'])) $aktifCount++;
}

?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Kasir Dashboard – SiTamDeals</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet" />
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: { playfair:['"Playfair Display"','serif'] },
          colors: {
            forest:'#1e3a2f', moss:'#2e5c42', sage:'#4a8c64',
            leaf:'#72b88a', mint:'#b8d9c5', cream:'#f7f4ee',
            gold:'#c9a84c', dark:'#111a15'
          }
        }
      }
    }
  </script>
  <style>
    body { font-family:'DM Sans',sans-serif; }
    .status-badge {
      display:inline-flex; align-items:center; gap:5px;
      padding:4px 12px; border-radius:20px;
      font-size:0.72rem; font-weight:700; letter-spacing:0.8px; text-transform:uppercase;
    }
    .s-pending    { background:#fff8e1; color:#b45309; border:1px solid #fcd34d; }
    .s-diterima   { background:#e8f5e9; color:#2e7d32; border:1px solid #86efac; }
    .s-diproses   { background:#e3f2fd; color:#1565c0; border:1px solid #93c5fd; }
    .s-siap       { background:#f3e8ff; color:#7c3aed; border:1px solid #c4b5fd; }
    .s-selesai    { background:#ecfdf5; color:#065f46; border:1px solid #6ee7b7; }
    .s-ditolak    { background:#fef2f2; color:#dc2626; border:1px solid #fca5a5; }

    .action-btn {
      padding:7px 14px; border-radius:8px; font-size:0.78rem; font-weight:600;
      border:1.5px solid; cursor:pointer; transition:all 0.2s;
    }
    .action-btn:hover { transform:translateY(-1px); }
    .btn-pending  { background:#fffbeb; color:#92400e; border-color:#fcd34d; }
    .btn-pending:hover  { background:#fef3c7; }
    .btn-diterima { background:#f0fdf4; color:#166534; border-color:#86efac; }
    .btn-diterima:hover { background:#dcfce7; }
    .btn-diproses { background:#eff6ff; color:#1d4ed8; border-color:#93c5fd; }
    .btn-diproses:hover { background:#dbeafe; }
    .btn-siap     { background:#faf5ff; color:#6d28d9; border-color:#c4b5fd; }
    .btn-siap:hover     { background:#ede9fe; }
    .btn-selesai  { background:#ecfdf5; color:#065f46; border-color:#6ee7b7; }
    .btn-selesai:hover  { background:#d1fae5; }
    .btn-tolak    { background:#fef2f2; color:#b91c1c; border-color:#fca5a5; }
    .btn-tolak:hover    { background:#fee2e2; }

    /* highlight tombol yang merupakan status aktif saat ini */
    .btn-current  { outline:3px solid #1e3a2f; outline-offset:2px; }

    .order-card { transition:all 0.25s; }
    .order-card:hover { transform:translateY(-2px); box-shadow:0 8px 30px rgba(30,58,47,.1); }
    @keyframes slideIn { from{opacity:0;transform:translateY(10px)} to{opacity:1;transform:translateY(0)} }
    .order-card { animation:slideIn 0.35s ease both; }
  </style>
</head>
<body class="bg-cream min-h-screen">

  <!-- NAVBAR -->
  <nav class="fixed top-0 left-0 right-0 z-50 h-[68px] flex items-center justify-between px-6 lg:px-10 border-b border-leaf/10 backdrop-blur-md"
       style="background:rgba(30,58,47,0.97)">
    <div class="flex items-center gap-4">
      <button class="lg:hidden w-9 h-9 flex flex-col gap-1.5 items-center justify-center" onclick="toggleSidebar()">
        <span class="w-5 h-0.5 bg-cream/70 block"></span>
        <span class="w-5 h-0.5 bg-cream/70 block"></span>
        <span class="w-5 h-0.5 bg-cream/70 block"></span>
      </button>
      <a href="beranda.html" class="font-playfair text-xl font-black text-cream">SiTam<span class="text-gold">Deals</span></a>
      <div class="hidden lg:block w-px h-5 bg-cream/15"></div>
      <span class="hidden lg:block text-cream/40 text-xs tracking-widest uppercase">Kasir Dashboard</span>
    </div>
    <div class="flex items-center gap-3">
      <div class="flex items-center gap-2 bg-cream/8 border border-cream/10 rounded-full pl-1.5 pr-4 py-1">
        <div class="w-7 h-7 rounded-full bg-gold/80 flex items-center justify-center text-sm font-black text-forest"><?= $userInitial ?></div>
        <span class="text-cream/80 text-sm hidden sm:block"><?= htmlspecialchars($userName) ?></span>
      </div>
      <a href="logout.php" class="bg-red-500/90 hover:bg-red-500 text-white text-xs font-bold px-4 py-2 rounded-full transition-colors">Logout</a>
    </div>
  </nav>

  <div class="flex pt-[68px]">

    <!-- SIDEBAR -->
    <aside id="sidebar"
      class="fixed lg:sticky top-[68px] left-0 h-[calc(100vh-68px)] w-60 bg-forest border-r border-leaf/8 z-40 -translate-x-full lg:translate-x-0 transition-transform duration-300 overflow-y-auto">
      <div class="p-5 flex flex-col gap-1">
        <p class="text-[0.6rem] font-bold tracking-[2.5px] uppercase text-cream/25 px-3 mb-2 mt-2">Menu Kasir</p>
        <a href="admin_orders.php"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl bg-gold/12 border border-gold/20 text-gold font-semibold text-sm">
          📋 Semua Pesanan
          <?php if ($pendingCount > 0): ?>
          <span class="ml-auto bg-yellow-400/20 text-yellow-400 text-[0.6rem] font-bold px-2 py-0.5 rounded-full"><?= $pendingCount ?></span>
          <?php endif; ?>
        </a>
        <p class="text-[0.6rem] font-bold tracking-[2.5px] uppercase text-cream/25 px-3 mb-2 mt-4">Navigasi</p>
        <a href="beranda.html" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-cream/60 hover:bg-cream/6 hover:text-cream text-sm transition-colors">🏠 Beranda</a>
        <a href="logout.php"   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-red-400/70 hover:bg-red-500/10 hover:text-red-400 text-sm transition-colors">🚪 Logout</a>
      </div>
    </aside>

    <div id="overlay" class="fixed inset-0 bg-dark/50 z-30 hidden lg:hidden" onclick="toggleSidebar()"></div>

    <!-- MAIN -->
    <main class="flex-1 min-w-0 p-6 lg:p-8">

      <!-- Header + stats -->
      <div class="flex flex-wrap justify-between items-start gap-4 mb-8">
        <div>
          <p class="text-[0.7rem] font-bold tracking-[2.5px] uppercase text-sage mb-1">Manajemen Pesanan</p>
          <h1 class="font-playfair text-2xl lg:text-3xl font-black text-forest">Kasir Dashboard</h1>
        </div>
        <div class="flex gap-3">
          <div class="bg-white rounded-2xl px-5 py-3 shadow-sm text-center min-w-[74px]">
            <div class="font-playfair text-xl font-black text-yellow-500"><?= $pendingCount ?></div>
            <div class="text-[0.6rem] text-gray-400 uppercase tracking-wide">Pending</div>
          </div>
          <div class="bg-white rounded-2xl px-5 py-3 shadow-sm text-center min-w-[74px]">
            <div class="font-playfair text-xl font-black text-sage"><?= $aktifCount ?></div>
            <div class="text-[0.6rem] text-gray-400 uppercase tracking-wide">Aktif</div>
          </div>
          <div class="bg-white rounded-2xl px-5 py-3 shadow-sm text-center min-w-[74px]">
            <div class="font-playfair text-xl font-black text-forest"><?= $totalOrders ?></div>
            <div class="text-[0.6rem] text-gray-400 uppercase tracking-wide">Total</div>
          </div>
        </div>
      </div>

      <!-- Toast sukses setelah redirect -->
      <?php if (isset($_GET['updated'])): ?>
      <div id="toastMsg" class="mb-6 flex items-center gap-3 bg-white border border-green-200 rounded-2xl px-5 py-4 shadow-sm">
        <span class="text-2xl">✅</span>
        <div>
          <p class="font-semibold text-forest text-sm">Status berhasil diperbarui!</p>
          <p class="text-xs text-gray-400">Order #<?= (int)$_GET['updated'] ?> sudah tersimpan. Halaman user akan menampilkan status baru.</p>
        </div>
        <button onclick="this.parentElement.remove()" class="ml-auto text-gray-300 hover:text-gray-500 text-lg leading-none">✕</button>
      </div>
      <script>setTimeout(()=>{const t=document.getElementById('toastMsg');if(t)t.remove();},5000);</script>
      <?php endif; ?>

      <!-- Filter tabs -->
      <div class="flex gap-2 flex-wrap mb-6">
        <?php
        $filters = [
          'semua'        => 'Semua',
          'pending'      => '🕐 Pending',
          'diterima'     => '✅ Diterima',
          'diproses'     => '⚙️ Diproses',
          'siap_diambil' => '📦 Siap Diambil',
          'selesai'      => '🎉 Selesai',
          'ditolak'      => '✕ Ditolak',
        ];
        foreach ($filters as $key => $lbl): ?>
        <button onclick="filterOrders('<?= $key ?>',this)"
                class="filter-btn px-4 py-2 rounded-full text-xs font-bold tracking-wide border transition-all
                       <?= $key==='semua' ? 'bg-forest text-cream border-forest' : 'bg-white text-gray-500 border-gray-100 hover:border-forest hover:text-forest' ?>">
          <?= $lbl ?>
        </button>
        <?php endforeach; ?>
      </div>

      <!-- Order cards -->
      <div class="flex flex-col gap-4">

        <?php if (empty($allOrders)): ?>
        <div class="py-20 text-center text-gray-400">
          <div class="text-5xl mb-4">📭</div>
          <p class="font-semibold text-forest">Belum ada pesanan masuk</p>
        </div>
        <?php endif; ?>

        <?php
        $iconMap  = [
          'pending'      => ['🕐','bg-yellow-50', 'border-yellow-200'],
          'diterima'     => ['✅','bg-green-50',  'border-green-200'],
          'diproses'     => ['⚙️','bg-blue-50',   'border-blue-200'],
          'siap_diambil' => ['📦','bg-purple-50', 'border-purple-200'],
          'selesai'      => ['🎉','bg-emerald-50','border-emerald-200'],
          'ditolak'      => ['✕', 'bg-red-50',    'border-red-200'],
        ];
        $badgeMap = [
          'pending'      => ['s-pending',  '⏳ Pending'],
          'diterima'     => ['s-diterima', '✅ Diterima'],
          'diproses'     => ['s-diproses', '⚙️ Diproses'],
          'siap_diambil' => ['s-siap',     '📦 Siap Diambil'],
          'selesai'      => ['s-selesai',  '🎉 Selesai'],
          'ditolak'      => ['s-ditolak',  '✕ Ditolak'],
        ];
        $statusButtons = [
          ['pending',      'btn-pending',  '⏳ Pending'],
          ['diterima',     'btn-diterima', '✅ Diterima'],
          ['diproses',     'btn-diproses', '⚙️ Diproses'],
          ['siap_diambil', 'btn-siap',     '📦 Siap Diambil'],
          ['selesai',      'btn-selesai',  '🎉 Selesai'],
          ['ditolak',      'btn-tolak',    '✕ Tolak'],
        ];

        foreach ($allOrders as $idx => $o):
          $st    = $o['status'];
          $oid   = (int)$o['id'];
          $icon  = $iconMap[$st]  ?? $iconMap['pending'];
          $badge = $badgeMap[$st] ?? $badgeMap['pending'];
          $uname = htmlspecialchars($o['user_name'] ?? 'User');
          $tgl   = htmlspecialchars($o['created_at'] ?? '-');
          $total = number_format($o['total_price'] ?? 0, 0, ',', '.');
        ?>
        <div class="order-card bg-white rounded-2xl p-6 shadow-sm border border-gray-50"
             data-status="<?= $st ?>"
             style="animation-delay:<?= $idx * 0.04 ?>s">

          <!-- Header -->
          <div class="flex flex-wrap justify-between items-start gap-4 mb-4">
            <div class="flex items-start gap-4">
              <div class="w-11 h-11 rounded-xl <?= $icon[1] ?> border <?= $icon[2] ?> flex items-center justify-center text-xl shrink-0">
                <?= $icon[0] ?>
              </div>
              <div>
                <h3 class="font-bold text-forest text-base">Order #<?= $oid ?></h3>
                <div class="flex flex-wrap gap-x-2 mt-0.5">
                  <span class="text-gray-400 text-sm">👤 <?= $uname ?></span>
                  <span class="text-gray-400 text-xs">• <?= $tgl ?></span>
                </div>
              </div>
            </div>
            <span class="status-badge <?= $badge[0] ?>"><?= $badge[1] ?></span>
          </div>

          <!-- Ringkasan -->
          <div class="bg-[#f8faf9] rounded-xl px-4 py-3 mb-5 flex items-center gap-2 text-sm text-gray-500">
            🛒
            <?php if (!empty($o['total_items'])): ?>
            <span><?= (int)$o['total_items'] ?> item ·</span>
            <?php endif; ?>
            <strong class="text-forest">Rp <?= $total ?></strong>
            <?php if (!empty($o['payment_method'])): ?>
            <span class="ml-auto text-xs text-gray-400"><?= htmlspecialchars($o['payment_method']) ?></span>
            <?php endif; ?>
          </div>

          <!--
            ════════════════════════════════════════════
            TOMBOL STATUS — pakai <form> POST biasa.
            Tidak perlu JavaScript / fetch / API.
            Cara kerja:
              1. Kasir klik tombol → form POST ke admin_orders.php
              2. PHP update DB → redirect balik ke admin_orders.php
              3. Halaman reload dengan data terbaru dari DB
              4. Saat user buka order_status.php → PHP baca DB
                 yang sudah terupdate → tampilan sudah benar
            ════════════════════════════════════════════
          -->
          <div>
            <p class="text-[0.65rem] font-bold tracking-[2px] uppercase text-gray-300 mb-3">Ubah Status</p>
            <div class="flex flex-wrap gap-2">
              <?php foreach ($statusButtons as [$val, $cls, $lbl]): ?>
              <form method="POST" action="admin_orders.php" style="display:inline">
                <input type="hidden" name="order_id" value="<?= $oid ?>">
                <input type="hidden" name="status"   value="<?= $val ?>">
                <button type="submit"
                        class="action-btn <?= $cls ?> <?= ($val === $st) ? 'btn-current' : '' ?>"
                        <?= ($val === $st) ? 'title="Status saat ini"' : '' ?>>
                  <?= $lbl ?>
                </button>
              </form>
              <?php endforeach; ?>
            </div>
          </div>

        </div>
        <?php endforeach; ?>
      </div>

      <!-- Empty state filter -->
      <div id="emptyFilter" class="hidden py-20 text-center text-gray-400">
        <div class="text-5xl mb-4">🔍</div>
        <p class="font-semibold text-forest">Tidak ada pesanan dengan status ini</p>
        <button onclick="filterOrders('semua',document.querySelector('.filter-btn'))" class="mt-3 text-sm text-sage hover:text-forest transition-colors">Tampilkan semua →</button>
      </div>

    </main>
  </div>

  <script>
    function filterOrders(status, btn) {
      const cards = document.querySelectorAll('.order-card');
      const empty = document.getElementById('emptyFilter');
      let visible = 0;
      cards.forEach(card => {
        const show = status === 'semua' || card.dataset.status === status;
        card.style.display = show ? '' : 'none';
        if (show) visible++;
      });
      empty.style.display = visible === 0 ? '' : 'none';

      document.querySelectorAll('.filter-btn').forEach(b => {
        b.classList.remove('bg-forest','text-cream','border-forest');
        b.classList.add('bg-white','text-gray-500','border-gray-100');
      });
      if (btn) {
        btn.classList.add('bg-forest','text-cream','border-forest');
        btn.classList.remove('bg-white','text-gray-500','border-gray-100');
      }
    }

    function toggleSidebar() {
      const s = document.getElementById('sidebar');
      const o = document.getElementById('overlay');
      const isOpen = !s.classList.contains('-translate-x-full');
      s.classList.toggle('-translate-x-full', isOpen);
      o.classList.toggle('hidden', isOpen);
    }
  </script>
</body>
</html>