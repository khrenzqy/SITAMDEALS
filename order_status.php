<?php
include 'db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header("Location: index.php");
    exit;
}

$stmt = $conn->prepare("
    SELECT o.*, u.name AS user_name
    FROM orders o
    LEFT JOIN users u ON o.user_id = u.id
    WHERE o.id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$order) {
    header("Location: index.php");
    exit;
}
print_r($order);
$status     = $order['status'];
$userName   = htmlspecialchars($order['user_name'] ?? 'User');
$isRejected = ($status === 'ditolak');

$steps = ['pending','diterima','diproses','siap_diambil','selesai'];
$currentIdx = array_search($status, $steps); // false jika ditolak

$stepMeta = [
    'pending'      => ['🕒', 'Pending',      'Pesanan kamu sedang menunggu konfirmasi kasir', false],
    'diterima'     => ['📋', 'Diterima',     'Pesanan kamu telah diterima oleh kasir', false],
    'diproses'     => ['🔄', 'Diproses',     'Pesanan kamu sedang disiapkan', false],
    'siap_diambil' => ['📦', 'Siap Diambil', 'Pesanan kamu sudah siap, silakan ambil di kasir!', true],
    'selesai'      => ['✅', 'Selesai',      'Pesanan kamu selesai. Terima kasih sudah belanja! 🎉', true],
];

$badgeMap = [
    'pending'      => ['bg-yellow-50 text-yellow-700 border border-yellow-200', '⏳ Pending'],
    'diterima'     => ['bg-green-50 text-green-700 border border-green-200',    '✅ Diterima'],
    'diproses'     => ['bg-blue-50 text-blue-700 border border-blue-200',       '⚙️ Diproses'],
    'siap_diambil' => ['bg-purple-50 text-purple-700 border border-purple-200', '📦 Siap Diambil'],
    'selesai'      => ['bg-emerald-50 text-emerald-700 border border-emerald-200','🎉 Selesai'],
    'ditolak'      => ['bg-red-50 text-red-700 border border-red-200',           '✕ Ditolak'],
];
$badge = $badgeMap[$status] ?? $badgeMap['pending'];

// Format total dari kolom total_price yang sudah tersimpan di DB
$total = number_format($order['total_price']);
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Status Pesanan #<?= $id ?> – SiTamDeals</title>

  <?php if (!$isRejected && $status !== 'selesai'): ?>
  <meta http-equiv="refresh" content="10;url=order_status.php?id=<?= $id ?>">
  <?php endif; ?>

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
          },
          keyframes: {
            fadeUp:  { from:{opacity:'0',transform:'translateY(14px)'}, to:{opacity:'1',transform:'translateY(0)'} },
            pulse2:  { '0%,100%':{boxShadow:'0 0 0 0 rgba(74,140,100,0.4)'}, '50%':{boxShadow:'0 0 0 10px rgba(74,140,100,0)'} }
          },
          animation: {
            'fade-up':'fadeUp 0.5s ease both',
            'pulse2': 'pulse2 2s ease-in-out infinite',
          }
        }
      }
    }
  </script>
  <style>
    body { font-family:'DM Sans',sans-serif; }
    .leaf-bg {
      background-image: radial-gradient(rgba(114,184,138,.06) 1px, transparent 1px);
      background-size: 24px 24px;
    }
    .dot-done    { background:linear-gradient(135deg,#1e3a2f,#2e5c42); border-color:#d1fae5; color:#fff; }
    .dot-active  { background:#4a8c64; border-color:#e8f5e9; color:#fff; animation:pulse2 2s ease-in-out infinite; }
    .dot-pending { background:#f1f5f2; border-color:#e2e8e4; color:#aab8b0; }
    @keyframes pulse2 {
      0%,100%{ box-shadow:0 0 0 0 rgba(74,140,100,.4); }
      50%{ box-shadow:0 0 0 10px rgba(74,140,100,0); }
    }
    <?php if (!$isRejected && $status !== 'selesai'): ?>
    .refresh-bar { animation: shrink 10s linear forwards; }
    @keyframes shrink { from{width:100%} to{width:0%} }
    <?php endif; ?>
  </style>
</head>
<body class="min-h-screen flex flex-col leaf-bg" style="background-color:#f0f4ee;">

  <!-- NAVBAR -->
  <nav class="fixed top-0 left-0 right-0 z-50 flex justify-between items-center px-[6%] h-[68px] backdrop-blur-md border-b border-leaf/10"
       style="background:rgba(30,58,47,0.97)">
    <a href="index.php" class="font-playfair text-xl font-black text-cream">SiTam<span class="text-gold">Deals</span></a>
    <div class="flex items-center gap-3">
      <?php if (!$isRejected && $status !== 'selesai'): ?>
      <div class="flex items-center gap-1.5 text-cream/50 text-xs" title="Halaman refresh otomatis tiap 10 detik">
        <span class="w-2 h-2 rounded-full bg-green-400 block" style="animation:pulse2 1.5s ease-in-out infinite"></span>
        Auto-refresh
      </div>
      <?php endif; ?>
      <a href="index.php" class="text-cream/60 hover:text-cream text-xs tracking-widest uppercase font-medium transition-colors">← Beranda</a>
    </div>
  </nav>

  <?php if (!$isRejected && $status !== 'selesai'): ?>
  <div class="fixed top-[68px] left-0 right-0 h-0.5 bg-sage/10 z-40">
    <div class="refresh-bar h-full bg-sage/50 origin-left"></div>
  </div>
  <?php endif; ?>

  <div class="flex-1 flex items-center justify-center px-4 pt-24 pb-16">
    <div class="w-full max-w-lg animate-fade-up">

      <?php if ($isRejected): ?>
      <!-- ── DITOLAK ── -->
      <div class="bg-white rounded-3xl shadow-lg overflow-hidden">
        <div class="h-1.5" style="background:linear-gradient(90deg,#dc2626,#ef4444)"></div>
        <div class="p-10 text-center">
          <div class="text-6xl mb-4">😔</div>
          <h1 class="font-playfair text-2xl font-black text-red-600 mb-2">Pesanan Ditolak</h1>
          <p class="text-gray-400 text-sm mb-6">
            Maaf, pesanan <strong>#<?= $id ?></strong> tidak dapat diproses.<br>
            Silakan hubungi kasir untuk informasi lebih lanjut.
          </p>
          <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="index.php" class="px-6 py-3 rounded-xl text-sm font-bold text-white" style="background:linear-gradient(135deg,#1e3a2f,#2e5c42)">🛒 Belanja Lagi</a>
            <a href="contact.php" class="px-6 py-3 rounded-xl text-sm font-bold text-forest bg-cream border border-forest/15">💬 Hubungi Kasir</a>
          </div>
        </div>
      </div>

      <?php else: ?>

      <!-- ── HEADER CARD ── -->
      <div class="bg-white rounded-3xl shadow-lg shadow-forest/8 overflow-hidden mb-5">
        <div class="h-1.5 w-full" style="background:linear-gradient(90deg,#1e3a2f,#4a8c64,#c9a84c)"></div>
        <div class="p-8 pb-6">
          <div class="flex items-center gap-2 text-gray-400 text-xs mb-6">
            <a href="index.php" class="hover:text-sage transition-colors">Beranda</a>
            <span>›</span><span>Pesanan Saya</span>
            <span>›</span><span class="text-forest font-semibold">#<?= $id ?></span>
          </div>

          <div class="flex justify-between items-start mb-2">
            <div>
              <p class="text-[0.7rem] font-bold tracking-[2.5px] uppercase text-sage mb-1">Nomor Pesanan</p>
              <h1 class="font-playfair text-3xl font-black text-forest">#<?= $id ?></h1>
            </div>
            <span class="inline-flex items-center gap-1.5 text-xs font-bold tracking-wide uppercase px-3 py-1.5 rounded-full <?= $badge[0] ?>">
              <?= $badge[1] ?>
            </span>
          </div>

          <div class="flex flex-wrap gap-4 mt-4 pt-4 border-t border-gray-50 text-sm text-gray-500">
            <div class="flex items-center gap-1.5">👤 <?= $userName ?></div>
            <div class="flex items-center gap-1.5">🕐 <?= htmlspecialchars($order['created_at'] ?? '') ?></div>
            <?php if (!empty($order['payment_method'])): ?>
            <div class="flex items-center gap-1.5">💳 <?= htmlspecialchars($order['payment_method']) ?></div>
            <?php endif; ?>
            <div class="flex items-center gap-1.5">💰 <strong class="text-forest">Rp <?= $total ?></strong></div>
          </div>
        </div>
      </div>

      <!-- ── STATUS CARD (atas timeline) ── -->
      <?php
        $currentStatus = $order['status'];
        $currentMeta   = $stepMeta[$currentStatus] ?? $stepMeta['pending'];
      ?>
      <div class="p-4 border rounded-xl bg-white shadow-sm mb-5">
          <div class="text-2xl"><?= $currentMeta[0] ?></div>
          <h3 class="font-bold text-forest"><?= $currentMeta[1] ?></h3>
          <p class="text-sm text-gray-500 mb-4"><?= $currentMeta[2] ?></p>
          <?php if ($currentMeta[3] === true): ?>
              <a href="invoice.php?id=<?= $order['id'] ?>"
                 class="inline-flex items-center gap-2 bg-forest text-cream px-4 py-2 rounded-lg text-sm font-bold hover:bg-moss transition-all">
                  <span>📄</span> Cetak Invoice
              </a>
          <?php endif; ?>
      </div>

      <!-- ── TIMELINE ── -->
      <div class="bg-white rounded-3xl shadow-lg shadow-forest/8 p-8">
        <div class="flex items-center justify-between mb-8">
          <div class="flex items-center gap-2 text-[0.7rem] font-bold tracking-[2.5px] uppercase text-sage">
            <span class="block w-4 h-0.5 bg-sage"></span>
            Tracking Pesanan
          </div>
          <?php if ($status !== 'selesai'): ?>
          <span class="text-[0.65rem] text-gray-300">Refresh otomatis tiap 10 detik</span>
          <?php endif; ?>
        </div>

        <div class="flex flex-col">
          <?php foreach ($steps as $i => $step):
            $meta     = $stepMeta[$step];
            $isDone   = ($currentIdx !== false && $i < $currentIdx);
            $isActive = ($currentIdx !== false && $i === $currentIdx);
            $isLast   = ($i === count($steps) - 1);

            $dotClass = $isDone ? 'dot-done' : ($isActive ? 'dot-active' : 'dot-pending');
            $titleCls = ($isDone || $isActive) ? 'text-forest' : 'text-gray-300';
            $subCls   = $isActive ? 'text-sage font-semibold' : ($isDone ? 'text-gray-400' : 'text-gray-300');
            $lineCls  = $isDone ? 'bg-gradient-to-b from-sage/60 to-sage/20' : 'bg-gray-100';
          ?>
          <div class="flex gap-5 <?= !$isLast ? 'pb-8' : '' ?>">
            <div class="flex flex-col items-center">
              <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-xl border-4 z-10 transition-all duration-500 <?= $dotClass ?>">
                <?= $meta[0] ?>
              </div>
              <?php if (!$isLast): ?>
              <div class="flex-1 w-0.5 mt-2 min-h-[40px] <?= $lineCls ?>"></div>
              <?php endif; ?>
            </div>
            <div class="pt-1 <?= !$isLast ? 'pb-6' : '' ?>">
              <p class="font-bold <?= $titleCls ?> text-sm uppercase tracking-wide"><?= $meta[1] ?></p>
              <p class="text-xs <?= $subCls ?> mt-0.5">
                <?= $isActive ? 'Sedang berlangsung...' : ($isDone ? 'Selesai ✓' : 'Menunggu...') ?>
              </p>
              <?php if ($isActive || ($step === 'selesai' && $isDone)): ?>
              <div class="mt-3 bg-green-50 border border-green-100 rounded-xl px-4 py-2.5 text-xs text-green-700">
                ✓ <?= $meta[2] ?>
              </div>
              <?php endif; ?>
            </div>
          </div>
          <?php endforeach; ?>
        </div>

        <div class="mt-8 pt-6 border-t border-gray-50 flex flex-col sm:flex-row gap-3">
          <a href="index.php"
             class="flex-1 py-3 rounded-xl text-sm font-bold text-white text-center hover:-translate-y-0.5 hover:shadow-md transition-all"
             style="background:linear-gradient(135deg,#1e3a2f,#2e5c42)">
            🛒 Lanjut Belanja
          </a>
          <a href="order_status.php?id=<?= $id ?>"
             class="flex-1 py-3 rounded-xl text-sm font-bold text-forest bg-cream border border-forest/15 hover:bg-mint/30 transition-all text-center">
            🔄 Refresh Status
          </a>
        </div>
      </div>

      <?php if (!in_array($status, ['selesai','ditolak'])): ?>
      <div class="mt-5 bg-gold/10 border border-gold/25 rounded-2xl p-5 flex items-center gap-4">
        <div class="w-10 h-10 rounded-xl bg-gold/20 flex items-center justify-center text-xl min-w-[40px]">⏱️</div>
        <div>
          <p class="font-semibold text-forest text-sm">Estimasi Waktu</p>
          <p class="text-xs text-gray-500 mt-0.5">Pesanan Anda diperkirakan siap dalam <strong class="text-forest">15–20 menit</strong></p>
        </div>
      </div>
      <?php endif; ?>

      <?php if (!in_array($status, ['selesai','ditolak'])): ?>
      <p class="text-center text-gray-400 text-xs mt-4">
        Halaman ini otomatis refresh setiap 10 detik.<br>
        Atau tekan <strong class="text-forest">Refresh Status</strong> untuk cek sekarang.
      </p>
      <?php endif; ?>

      <?php endif; // end !isRejected ?>

    </div>
  </div>

</body>
</html>