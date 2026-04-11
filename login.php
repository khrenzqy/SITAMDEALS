<?php
session_start();
include 'db.php';
$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $pass = $_POST['password'];

    $res = $conn->query("SELECT * FROM users WHERE email='$email'");
    if ($res->num_rows > 0) {
        $user = $res->fetch_assoc();
        // Catatan: Gunakan password_hash() saat register agar password_verify() ini bekerja
        if (password_verify($pass, $user['password'])) {
            $_SESSION['user'] = $user;
            header("Location: index.php");
            exit;
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Email tidak ditemukan!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - SiTamDeals</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#f7f4ee] flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded-3xl shadow-xl w-96 border-t-8 border-[#1e3a2f]">
        <h1 class="text-3xl font-bold text-[#1e3a2f] mb-6 text-center">🌿 SiTamDeals</h1>
        <?php if($error): ?>
            <div class="bg-red-100 text-red-600 p-3 rounded-lg mb-4 text-sm"><?= $error ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-4">
                <label class="block text-sm font-bold mb-1 text-gray-600">Email</label>
                <input type="email" name="email" class="w-full border-2 border-gray-100 p-3 rounded-xl focus:border-[#4a8c64] outline-none transition" required>
            </div>
            <div class="mb-6">
                <label class="block text-sm font-bold mb-1 text-gray-600">Password</label>
                <input type="password" name="password" class="w-full border-2 border-gray-100 p-3 rounded-xl focus:border-[#4a8c64] outline-none transition" required>
            </div>
            <button type="submit" class="w-full bg-[#2e5c42] hover:bg-[#1e3a2f] text-white font-bold py-3 rounded-xl shadow-lg transition duration-300">Masuk</button>
        </form>
    </div>
</body>
</html>