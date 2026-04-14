<?php
include 'db.php';

// Pastikan hanya menerima request POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['order_id'] ?? null;
    $status = $_POST['status'] ?? null;

    if ($id && $status) {
        // Gunakan Prepared Statement untuk keamanan
        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $id);

        if ($stmt->execute()) {
            // Memberikan respon bersih tanpa spasi tambahan
            echo "OK";
        } else {
            echo "Error Database: " . $conn->error;
        }
        $stmt->close();
    } else {
        echo "Data tidak lengkap";
    }
} else {
    echo "Metode request tidak valid";
}
?>