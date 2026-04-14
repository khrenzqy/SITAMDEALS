<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'db.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    die('ID order tidak valid.');
}

$queryOrder = $conn->query("
    SELECT o.*, u.name AS customer, k.name AS kasir
    FROM orders o
    JOIN users u ON o.user_id = u.id
    LEFT JOIN users k ON o.kasir_id = k.id
    WHERE o.id = $id
");

$order = $queryOrder ? $queryOrder->fetch_assoc() : null;
if (!$order) {
    die('Order tidak ditemukan.');
}

// Hitung total langsung dari order_items agar selalu akurat
$items = $conn->query("
    SELECT oi.*, p.name
    FROM order_items oi
    JOIN products p ON oi.product_id = p.product_id
    WHERE oi.order_id = $id
");

if (!$items) {
    die("Gagal mengambil data produk: " . $conn->error);
}

$total     = 0;
$dataItems = [];

while ($i = $items->fetch_assoc()) {
    $subTotal        = (int)$i['price'] * (int)$i['qty'];
    $total          += $subTotal;
    $i['subtotal']   = $subTotal;
    $dataItems[]     = $i;
}

// Sinkronkan total_price di tabel orders jika berbeda (auto-repair)
if ((int)$order['total_price'] !== $total) {
    $conn->query("UPDATE orders SET total_price = $total WHERE id = $id");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembelian #<?= $id ?> - SiTamDeals</title>
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
                        forest: '#1e3a2f', moss: '#2e5c42', sage: '#4a8c64',
                        leaf: '#72b88a', mint: '#b8d9c5', cream: '#f7f4ee',
                        gold: '#c9a84c', dark: '#111a15'
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'DM Sans', sans-serif; }
        @media print {
            button, .no-print { display: none !important; }
            body { background: white; padding: 0; }
        }
    </style>
</head>
<body class="bg-cream flex justify-center py-10 px-4">

<div class="w-full max-w-md bg-white rounded-2xl shadow-xl border border-gray-100 p-8">

    <div class="text-center mb-8">
        <h1 class="font-playfair text-3xl font-black text-forest tracking-tight">
            SiTam<span class="text-gold">Deals</span>
        </h1>
        <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-[2px] font-bold">Struk Pembelian Resmi</p>
    </div>

    <div class="grid grid-cols-2 gap-y-2 text-xs text-gray-600 mb-6">
        <div>
            <p class="text-[10px] uppercase text-gray-400 font-bold">Pelanggan</p>
            <p class="font-semibold text-forest"><?= htmlspecialchars($order['customer']) ?></p>
        </div>
        <div class="text-right">
            <p class="text-[10px] uppercase text-gray-400 font-bold">ID Order</p>
            <p class="font-semibold text-forest">#<?= $id ?></p>
        </div>
        <div>
            <p class="text-[10px] uppercase text-gray-400 font-bold">Waktu</p>
            <p class="font-medium"><?= date('d M Y, H:i', strtotime($order['created_at'])) ?></p>
        </div>
        <div class="text-right">
            <p class="text-[10px] uppercase text-gray-400 font-bold">Kasir</p>
            <p class="font-medium"><?= htmlspecialchars($order['kasir'] ?? 'Sistem') ?></p>
        </div>
    </div>

    <div class="border-t border-dashed border-gray-200 my-6"></div>

    <?php if (empty($dataItems)): ?>
    <p class="text-center text-gray-400 text-sm py-4">Tidak ada item dalam pesanan ini.</p>
    <?php else: ?>
    <div class="space-y-5 mb-8">
        <?php foreach ($dataItems as $item): ?>
        <div class="flex justify-between items-start">
            <div class="max-w-[70%]">
                <p class="text-sm font-bold text-forest leading-tight uppercase"><?= htmlspecialchars($item['name']) ?></p>
                <p class="text-[11px] text-gray-400 mt-0.5 italic">
                    Grade <?= htmlspecialchars($item['grade']) ?> &times; <?= (int)$item['qty'] ?>
                </p>
            </div>
            <div class="text-right">
                <p class="text-sm font-bold text-forest">Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></p>
                <p class="text-[10px] text-gray-400">@<?= number_format($item['price'], 0, ',', '.') ?></p>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <div class="border-t-2 border-forest/5 my-6"></div>

    <div class="flex justify-between items-center mb-8 bg-cream/50 p-4 rounded-xl">
        <div>
            <p class="text-[10px] uppercase text-gray-400 font-black tracking-widest leading-none">Total Bayar</p>
            <p class="text-xs text-forest/60 mt-1 italic">Sudah termasuk pajak</p>
        </div>
        <div class="text-right">
            <span class="font-playfair text-2xl font-black text-gold">
                Rp <?= number_format($total, 0, ',', '.') ?>
            </span>
        </div>
    </div>

    <div class="text-center">
        <div class="inline-block px-4 py-1 border border-leaf/20 rounded-full mb-4">
            <p class="text-[10px] text-sage font-bold uppercase tracking-wider">Terima kasih atas kunjungannya</p>
        </div>
        <p class="text-[9px] text-gray-300">Harap simpan struk ini sebagai bukti pembelian yang sah.</p>
    </div>

    <button onclick="window.print()"
        class="no-print mt-8 w-full bg-forest text-cream font-bold py-3 rounded-xl shadow-lg shadow-forest/20 hover:bg-moss hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 flex items-center justify-center gap-2">
        <span>🖨️</span> Print Struk
    </button>

    <a href="admin_orders.php" class="no-print block text-center mt-4 text-xs font-bold text-sage hover:text-forest transition-colors">
        ← Kembali ke Dashboard
    </a>

</div>

</body>
</html>