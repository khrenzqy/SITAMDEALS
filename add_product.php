<?php
include 'admin_only.php';
include 'db.php';

if($_POST){
  $name=$_POST['name'];
  $price=$_POST['price'];

  $conn->query("
  INSERT INTO products (name,price)
  VALUES ('$name',$price)
  ");

  header("Location: admin_products.php");
}
?>

<form method="POST">
<input name="name" placeholder="Nama">
<input name="price" placeholder="Harga">
<button>Tambah</button>
</form>