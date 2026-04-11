
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
if(!isset($_SESSION['user'])) header("Location: login.php");
include 'db.php';
$data=$conn->query("SELECT * FROM products");
?>
<!DOCTYPE html>
<html>
<head>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-cream">

<div class="max-w-6xl mx-auto p-6">
<h1 class="text-3xl font-bold text-forest mb-6">🌿 SiTamDeals</h1>

<div class="grid md:grid-cols-3 gap-6">
<?php if($data->num_rows==0){ ?>
<p class="text-center col-span-3 text-sage">Produk kosong 😢</p>
<?php } ?>

<?php while($p=$data->fetch_assoc()){ ?>
<div class="bg-white p-4 rounded-2xl shadow hover:shadow-xl hover:-translate-y-1 transition">
<h2 class="font-bold text-dark"><?= $p['name'] ?></h2>
<p class="text-gold font-bold">Rp <?= $p['price'] ?></p>
<a href="detail.php?id=<?= $p['id'] ?>" class="block mt-3 bg-moss text-white text-center py-2 rounded-xl">Detail</a>
</div>
<?php } ?>
</div>
</div>

</body>
</html>
