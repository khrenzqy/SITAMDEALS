<?php
session_start();
include 'db.php';
$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $pass = $_POST['password'];
    // echo $email;
    // echo $pass;

    $res = $conn->query("SELECT * FROM users WHERE email='$email'");
    // echo $res->;
    if ($res->num_rows > 0) {
        // echo "ada";
        // Catatan: Gunakan password_hash() saat register agar password_verify() ini bekerja
        $user = $res->fetch_assoc();
        // if (password_verify($pass, $user['password'])) {
        if ($pass === $user['password']) { // Sementara pakai ini dulu, ganti ke password_verify() setelah register pakai password_hash()
            // return $user;
            $_SESSION['user'] = $user;
            header("Location: index.php");
            exit;
        } else {
            $error = "salah kocak!";
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