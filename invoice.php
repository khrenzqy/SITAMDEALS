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
include 'db.php';
$id=$_GET['id'];
$order=$conn->query("SELECT o.*,u.name as customer,k.name as kasir FROM orders o JOIN users u ON o.user_id=u.id LEFT JOIN users k ON o.kasir_id=k.id WHERE o.id=$id")->fetch_assoc();
$items=$conn->query("SELECT oi.*,p.name FROM order_items oi JOIN products p ON oi.product_id=p.id WHERE order_id=$id");
$total=0;
?>

<style>
@media print { button{display:none;} }
</style>

<?php while($i=$items->fetch_assoc()){ $total+=$i['price']; } ?>

<h2>SiTamDeals</h2>
<p><?= $order['customer'] ?></p>
<p><?= $order['kasir'] ?></p>
<h3>Total: <?= $total ?></h3>
<button onclick="window.print()">Print</button>