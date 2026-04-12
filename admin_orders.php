<?php
include 'kasir_only.php';
include 'db.php';

$orders = $conn->query("SELECT * FROM orders ORDER BY id DESC");
?>

<h2>Orders</h2>

<?php while($o=$orders->fetch_assoc()): ?>

<div>
ID <?= $o['id'] ?> | <?= $o['status'] ?>

<a href="update_status.php?id=<?= $o['id'] ?>&status=diproses">Proses</a>
<a href="update_status.php?id=<?= $o['id'] ?>&status=siap_diambil">Siap</a>
<a href="update_status.php?id=<?= $o['id'] ?>&status=selesai">Selesai</a>

</div>

<?php endwhile; ?>