<<<<<<< HEAD
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
</head>
<body class="bg-gray-50 min-h-screen flex">
=======
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

>>>>>>> 4fc8bc88fd4aa750a16e596878db46bdc4c67bb4

<?php
session_start();
// Security: Pastikan hanya admin/kasir yang bisa masuk
// if($_SESSION['user']['role'] != 'admin') { header("Location: index.php"); exit; }

include 'db.php';
<<<<<<< HEAD
$data = $conn->query("SELECT o.*, u.name as customer FROM orders o JOIN users u ON o.user_id=u.id ORDER BY o.id DESC");
?>

    <div class="w-64 bg-forest text-white flex flex-col">
        <div class="p-6 text-2xl font-bold border-b border-moss">
            🌿 SiTam Admin
        </div>
        <nav class="flex-1 p-4">
            <a href="#" class="block py-3 px-4 bg-moss rounded-xl mb-2 font-bold">📋 Daftar Pesanan</a>
            <a href="index.php" class="block py-3 px-4 hover:bg-moss/50 rounded-xl transition">🏠 Lihat Toko</a>
        </nav>
        <div class="p-4 border-t border-moss text-xs text-sage">
            Logged in as: <?= $_SESSION['user']['name'] ?? 'Kasir' ?>
        </div>
    </div>

    <div class="flex-1 p-10">
        <div class="flex justify-between items-center mb-10">
            <h1 class="text-3xl font-bold text-dark">Manajemen Pesanan</h1>
            <div class="flex gap-4">
                <div class="bg-white px-6 py-2 rounded-2xl shadow-sm border border-gray-100">
                    <p class="text-xs text-gray-400">Total Pesanan</p>
                    <p class="text-xl font-bold text-forest"><?= $data->num_rows ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="p-5 text-sage font-bold uppercase text-xs">ID Order</th>
                        <th class="p-5 text-sage font-bold uppercase text-xs">Pelanggan</th>
                        <th class="p-5 text-sage font-bold uppercase text-xs">Status</th>
                        <th class="p-5 text-sage font-bold uppercase text-xs">Tanggal</th>
                        <th class="p-5 text-sage font-bold uppercase text-xs text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php while($row = $data->fetch_assoc()): ?>
                    <tr class="hover:bg-mint/5 transition">
                        <td class="p-5 font-mono text-forest">#<?= $row['id'] ?></td>
                        <td class="p-5">
                            <p class="font-bold text-dark"><?= $row['customer'] ?></p>
                        </td>
                        <td class="p-5">
                            <?php 
                                $statusColor = $row['status'] == 'selesai' ? 'bg-green-100 text-green-700' : 'bg-gold/20 text-gold';
                            ?>
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase <?= $statusColor ?>">
                                <?= $row['status'] ?>
                            </span>
                        </td>
                        <td class="p-5 text-gray-400 text-sm">
                            <?= date('d M Y, H:i', strtotime($row['created_at'])) ?>
                        </td>
                        <td class="p-5 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="invoice.php?id=<?= $row['id'] ?>" class="bg-mint text-forest px-4 py-2 rounded-xl text-xs font-bold hover:bg-mint/80 transition">
                                    Detail
                                </a>
                                <button class="bg-forest text-white px-4 py-2 rounded-xl text-xs font-bold hover:bg-dark transition">
                                    Update
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
=======
$data = $conn->query("SELECT o.*,u.name as customer FROM orders o JOIN users u ON o.user_id=u.id");
?>
>>>>>>> 4fc8bc88fd4aa750a16e596878db46bdc4c67bb4
