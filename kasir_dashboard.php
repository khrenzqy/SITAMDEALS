<?php
session_start();
include 'db.php';

// OPTIONAL: proteksi role
// if($_SESSION['user']['role'] != 'admin'){
//   header("Location: index.php");
//   exit;
//}

$data = $conn->query("
  SELECT o.*, u.name as customer 
  FROM orders o 
  JOIN users u ON o.user_id = u.id 
  ORDER BY o.id DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Admin - SiTamDeals</title>

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

<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>

<style>
body {
  font-family: 'DM Sans', sans-serif;
}
</style>

</head>

<body class="bg-cream min-h-screen flex">

<!-- SIDEBAR -->
<div class="w-64 bg-forest text-white flex flex-col">
  
  <div class="p-6 text-2xl font-playfair font-black border-b border-moss">
    🌿 SiTam Admin
  </div>

  <nav class="flex-1 p-4">
    <a href="#" class="block py-3 px-4 bg-moss rounded-xl mb-2 font-bold">
      📋 Daftar Pesanan
    </a>

    <a href="index.php" class="block py-3 px-4 hover:bg-moss/50 rounded-xl transition">
      🏠 Lihat Toko
    </a>
  </nav>

  <div class="p-4 border-t border-moss text-xs text-sage">
    Login: <?= $_SESSION['user']['name'] ?? 'Admin' ?>
  </div>

</div>

<!-- CONTENT -->
<div class="flex-1 p-10">

  <!-- HEADER -->
  <div class="flex justify-between items-center mb-10">
    <h1 class="text-3xl font-playfair font-black text-forest">
      Manajemen Pesanan
    </h1>

    <div class="bg-white px-6 py-3 rounded-2xl shadow border">
      <p class="text-xs text-gray-400">Total Pesanan</p>
      <p class="text-xl font-bold text-forest">
        <?= $data->num_rows ?>
      </p>
    </div>
  </div>

  <!-- TABLE -->
  <div class="bg-white rounded-3xl shadow-xl overflow-hidden border">

    <table class="w-full text-left">

      <!-- HEAD -->
      <thead class="bg-forest text-white text-xs uppercase">
        <tr>
          <th class="p-5">ID</th>
          <th class="p-5">Customer</th>
          <th class="p-5">Status</th>
          <th class="p-5">Tanggal</th>
          <th class="p-5 text-center">Aksi</th>
        </tr>
      </thead>

      <!-- BODY -->
      <tbody class="divide-y">

      <?php while($row = $data->fetch_assoc()): ?>

        <?php
          // warna status
          switch($row['status']){
            case 'diproses':
              $color = 'bg-yellow-100 text-yellow-700';
              break;
            case 'siap diambil':
              $color = 'bg-blue-100 text-blue-700';
              break;
            case 'diterima':
              $color = 'bg-purple-100 text-purple-700';
              break;
            case 'selesai':
              $color = 'bg-green-100 text-green-700';
              break;
            default:
              $color = 'bg-gray-100 text-gray-700';
          }
        ?>

        <tr class="hover:bg-mint/10 transition">

          <td class="p-5 font-mono text-forest">
            #<?= $row['id'] ?>
          </td>

          <td class="p-5 font-semibold">
            <?= $row['customer'] ?>
          </td>

          <td class="p-5">
            <span class="px-3 py-1 rounded-full text-xs font-bold <?= $color ?>">
              <?= $row['status'] ?>
            </span>
          </td>

          <td class="p-5 text-sm text-gray-400">
            <?= date('d M Y H:i', strtotime($row['created_at'])) ?>
          </td>

          <td class="p-5 text-center space-x-2">

            <!-- DETAIL -->
            <a href="invoice.php?id=<?= $row['id'] ?>"
              class="bg-mint text-forest px-4 py-2 rounded-xl text-xs font-bold hover:opacity-80">
              Detail
            </a>

            <!-- UPDATE STATUS -->
            <a href="update_status.php?id=<?= $row['id'] ?>"
              class="bg-forest text-white px-4 py-2 rounded-xl text-xs font-bold hover:bg-dark">
              Update
            </a>

          </td>

        </tr>

      <?php endwhile; ?>

      </tbody>

    </table>

  </div>

</div>

</body>
</html>