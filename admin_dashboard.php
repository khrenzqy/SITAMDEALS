<?php
include 'admin_only.php';
include 'db.php';

$total_orders = $conn->query("SELECT COUNT(*) as t FROM orders")->fetch_assoc()['t'];
$total_users  = $conn->query("SELECT COUNT(*) as t FROM users")->fetch_assoc()['t'];
$total_sales  = $conn->query("SELECT SUM(total) as t FROM orders WHERE status='selesai'")->fetch_assoc()['t'];
?>

<!DOCTYPE html>
<html>
<head>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<div class="flex">

<!-- SIDEBAR -->
<div class="w-64 bg-[#1e3a2f] text-white min-h-screen p-6">
<h2 class="text-xl font-bold mb-6">Admin</h2>

<a href="admin_dashboard.php" class="block mb-3">Dashboard</a>
<a href="admin_orders.php" class="block mb-3">Orders</a>
<a href="admin_products.php" class="block mb-3">Produk</a>
<a href="logout.php" class="block mt-10 text-red-300">Logout</a>
</div>

<!-- CONTENT -->
<div class="flex-1 p-10">

<h1 class="text-2xl font-bold mb-6">Dashboard</h1>

<div class="grid grid-cols-3 gap-6">

<div class="bg-white p-6 rounded-xl shadow">
<h3>Total Orders</h3>
<p class="text-2xl font-bold"><?= $total_orders ?></p>
</div>

<div class="bg-white p-6 rounded-xl shadow">
<h3>Total Users</h3>
<p class="text-2xl font-bold"><?= $total_users ?></p>
</div>

<div class="bg-white p-6 rounded-xl shadow">
<h3>Total Sales</h3>
<p class="text-2xl font-bold">Rp <?= number_format($total_sales) ?></p>
</div>

</div>

</div>
</div>

</body>
</html>