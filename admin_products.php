<?php
include 'admin_only.php';
include 'db.php';

$data = $conn->query("SELECT * FROM products");
?>

<h2>Produk</h2>

<a href="add_product.php">+ Tambah</a>

<?php while($p=$data->fetch_assoc()): ?>

<div>
<?= $p['name'] ?> - Rp <?= number_format($p['price']) ?>

<a href="delete_product.php?id=<?= $p['id'] ?>">Hapus</a>
</div>

<?php endwhile; ?>