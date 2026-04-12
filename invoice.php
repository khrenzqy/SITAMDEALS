<<<<<<< HEAD
<?php
include 'db.php';
if(!isset($_GET['id'])) die("ID Order tidak ditemukan");
$id = $_GET['id'];

// Query data order
$order = $conn->query("SELECT o.*, u.name as customer, k.name as kasir 
                       FROM orders o 
                       JOIN users u ON o.user_id=u.id 
                       LEFT JOIN users k ON o.kasir_id=k.id 
                       WHERE o.id=$id")->fetch_assoc();

if(!$order) die("Data order tidak ditemukan");

// Query data item (Jangan di-fetch dulu di sini)
$items = $conn->query("SELECT oi.*, p.name 
                       FROM order_items oi 
                       JOIN products p ON oi.product_id=p.id 
                       WHERE order_id=$id");
$total = 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            forest: '#1e3a2f', moss: '#2e5c42', sage: '#4a8c64',
            mint: '#b8d9c5', cream: '#f7f4ee', gold: '#c9a84c', dark: '#111a15'
          }
        }
      }
    }
    </script>
    <style>
        @media print { 
            .no-print { display: none; } 
            body { background-color: white; }
            .print-border { border: 1px solid #e5e7eb; }
        }
    </style>
</head>
<body class="bg-cream p-10">

<div class="max-w-2xl mx-auto bg-white p-8 rounded-3xl shadow-xl print-border">
    <div class="flex justify-between items-start border-b-2 border-mint pb-6 mb-6">
        <div>
            <h1 class="text-3xl font-bold text-forest">🌿 SiTamDeals</h1>
            <p class="text-sage text-sm">Invoice ID: #<?= $order['id'] ?></p>
            <p class="text-gray-400 text-xs"><?= $order['created_at'] ?></p>
        </div>
        <div class="text-right">
            <span class="px-4 py-1 bg-mint/30 text-moss rounded-full text-xs font-bold uppercase">
                <?= $order['status'] ?>
            </span>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4 mb-8">
        <div>
            <p class="text-gray-400 text-xs uppercase font-bold tracking-widest">Pelanggan</p>
            <p class="text-dark font-bold text-lg"><?= $order['customer'] ?></p>
        </div>
        <div class="text-right">
            <p class="text-gray-400 text-xs uppercase font-bold tracking-widest">Kasir</p>
            <p class="text-dark font-medium"><?= $order['kasir'] ?? 'Sistem Otomatis' ?></p>
        </div>
    </div>

    <table class="w-full mb-8">
        <thead>
            <tr class="text-left border-b border-gray-100">
                <th class="py-3 text-sage font-bold">Item</th>
                <th class="py-3 text-sage font-bold">Grade</th>
                <th class="py-3 text-right text-sage font-bold">Harga</th>
            </tr>
        </thead>
        <tbody>
            <?php while($i = $items->fetch_assoc()): 
                $total += $i['price'];
            ?>
            <tr class="border-b border-gray-50">
                <td class="py-4 text-dark font-medium"><?= $i['name'] ?></td>
                <td class="py-4 text-gray-500 italic"><?= $i['grade'] ?></td>
                <td class="py-4 text-right text-forest font-bold">
                    Rp <?= number_format($i['price'], 0, ',', '.') ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div class="bg-mint/10 p-6 rounded-2xl flex justify-between items-center">
        <h2 class="text-xl font-bold text-forest">Total Pembayaran</h2>
        <h2 class="text-2xl font-black text-forest">Rp <?= number_format($total, 0, ',', '.') ?></h2>
    </div>

    <div class="mt-10 flex gap-4 no-print">
        <button onclick="window.print()" class="flex-1 bg-moss text-white font-bold py-3 rounded-xl hover:bg-forest transition shadow-lg shadow-moss/20">
            🖨️ Cetak Invoice
        </button>
        <a href="index.php" class="flex-1 border-2 border-moss text-moss font-bold py-3 rounded-xl text-center hover:bg-mint/20 transition">
            Kembali Ke Beranda
        </a>
    </div>

    <p class="text-center text-gray-300 text-[10px] mt-8 italic uppercase tracking-tighter">
        Terima kasih telah berbelanja di SiTamDeals Surabaya
    </p>
</div>

</body>
</html>
=======
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
        dark: '#111a15'
      }
    }
  }
}
</script>

<?php
include 'db.php';
$id = $_GET['id'];

$order = $conn->query("
SELECT o.*, u.name as customer, k.name as kasir 
FROM orders o 
JOIN users u ON o.user_id=u.id 
LEFT JOIN users k ON o.kasir_id=k.id 
WHERE o.id=$id
")->fetch_assoc();

$items = $conn->query("
SELECT oi.*, p.name 
FROM order_items oi 
JOIN products p ON oi.product_id=p.id 
WHERE order_id=$id
");

$total = 0;
$dataItems = [];

while ($i = $items->fetch_assoc()) {
  $total += $i['price'];
  $dataItems[] = $i;
}
?>

<style>
body {
  font-family: 'DM Sans', sans-serif;
}

@media print {
  button {
    display: none;
  }
  body {
    background: white;
  }
}
</style>

<body class="bg-cream flex justify-center py-10">

<div class="w-full max-w-md bg-white rounded-2xl shadow-xl border border-gray-100 p-6">

  <!-- HEADER -->
  <div class="text-center mb-6">
    <h1 class="font-playfair text-2xl font-black text-forest">
      SiTam<span class="text-gold">Deals</span>
    </h1>
    <p class="text-xs text-gray-400 mt-1">Struk Pembelian</p>
  </div>

  <!-- INFO -->
  <div class="text-sm text-gray-600 mb-4 space-y-1">
    <p><span class="font-semibold text-forest">Customer:</span> <?= $order['customer'] ?></p>
    <p><span class="font-semibold text-forest">Kasir:</span> <?= $order['kasir'] ?? '-' ?></p>
    <p><span class="font-semibold text-forest">Tanggal:</span> <?= date('d M Y H:i') ?></p>
  </div>

  <!-- DIVIDER -->
  <div class="border-t border-dashed border-gray-300 my-4"></div>

  <!-- ITEMS -->
  <div class="space-y-3 text-sm">
    <?php foreach ($dataItems as $item): ?>
      <div class="flex justify-between">
        <span class="text-gray-700"><?= $item['name'] ?></span>
        <span class="text-forest font-medium">Rp <?= number_format($item['price'],0,',','.') ?></span>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- DIVIDER -->
  <div class="border-t border-dashed border-gray-300 my-4"></div>

  <!-- TOTAL -->
  <div class="flex justify-between items-center">
    <span class="font-semibold text-forest">Total</span>
    <span class="font-playfair text-xl font-black text-gold">
      Rp <?= number_format($total,0,',','.') ?>
    </span>
  </div>

  <!-- FOOTER -->
  <div class="text-center text-xs text-gray-400 mt-6">
    Terima kasih telah berbelanja 💚
  </div>

  <!-- BUTTON -->
  <button onclick="window.print()"
    class="mt-6 w-full bg-forest text-white py-2 rounded-xl hover:bg-moss transition">
    Print Struk
  </button>

</div>

</body>
>>>>>>> 4fc8bc88fd4aa750a16e596878db46bdc4c67bb4
