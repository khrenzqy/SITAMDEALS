<?php
include 'db.php';
$id = $_GET['id'];

$order = $conn->query("
SELECT * FROM orders WHERE id=$id
")->fetch_assoc();

$items = $conn->query("
SELECT oi.*, p.name 
FROM order_items oi
JOIN products p ON oi.product_id=p.id
WHERE order_id=$id
");

$total = 0;
?>

<h2>SiTamDeals</h2>

<?php while($i=$items->fetch_assoc()):
  $sub = $i['price']*$i['qty'];
  $total += $sub;
?>

<p>
<?= $i['name'] ?> 
(<?= $i['grade'] ?> x<?= $i['qty'] ?>)
= Rp <?= number_format($sub) ?>
</p>

<?php endwhile; ?>

<h3>Total: Rp <?= number_format($total) ?></h3>

<button onclick="window.print()">Print</button>