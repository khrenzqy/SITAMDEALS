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