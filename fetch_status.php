<?php
include 'db.php';

$id = intval($_GET['id']);

$data = $conn->query("
SELECT status FROM orders WHERE id=$id
")->fetch_assoc();

echo json_encode($data);