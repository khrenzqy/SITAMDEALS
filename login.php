<?php
session_start();
include 'db.php';
$error="";

if($_SERVER['REQUEST_METHOD']=="POST"){
  $email=$_POST['email'];
  $pass=$_POST['password'];

  $res=$conn->query("SELECT * FROM users WHERE email='$email'");

  if($res->num_rows>0){
    $user=$res->fetch_assoc();

    if($pass === $user['password']){
      $_SESSION['user']=$user;

      if($user['role']=="admin"){
        header("Location: admin_dashboard.php");
      } elseif($user['role']=="kasir"){
        header("Location: admin_orders.php");
      } else {
        header("Location: index.php");
      }
      exit;
    } else $error="Password salah!";
  } else $error="Email tidak ditemukan!";
}
?>

<!DOCTYPE html>
<html>
<head>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[#f7f4ee] flex items-center justify-center h-screen">

<div class="bg-white p-8 rounded-2xl shadow w-96">

<h1 class="text-2xl font-bold mb-6 text-center">Login</h1>

<?php if($error): ?>
<div class="bg-red-100 text-red-600 p-2 mb-4"><?= $error ?></div>
<?php endif; ?>

<form method="POST">
<input name="email" placeholder="Email" class="w-full mb-3 p-2 border rounded">
<input type="password" name="password" placeholder="Password" class="w-full mb-4 p-2 border rounded">

<button class="w-full bg-[#1e3a2f] text-white p-2 rounded">
Masuk
</button>
</form>

</div>

</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Login - SiTamDeals</title>

<script src="https://cdn.tailwindcss.com"></script>

<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>

<script>
tailwind.config = {
  theme: {
    extend: {
      fontFamily: {
        playfair: ['"Playfair Display"', 'serif'],
        dm: ['"DM Sans"', 'sans-serif']
      },
      colors: {
        forest: '#1e3a2f',
        moss: '#2e5c42',
        sage: '#4a8c64',
        leaf: '#72b88a',
        mint: '#b8d9c5',
        cream: '#f7f4ee',
        gold: '#c9a84c',
        'gold-light': '#e8c96a',
        dark: '#111a15'
      }
    }
  }
}
</script>

<style>
body {
  font-family: 'DM Sans', sans-serif;
}
</style>

</head>

<body class="min-h-screen flex items-center justify-center bg-cream">

<!-- BACKGROUND HERO STYLE -->
<div class="absolute inset-0"
style="background:
linear-gradient(135deg, rgba(18,38,28,.9), rgba(46,92,66,.7)),
url('https://images.unsplash.com/photo-1578916171728-46686eac8d58?w=1600&q=80') center/cover no-repeat;">
</div>

<!-- LOGIN CARD -->
<div class="relative z-10 w-full max-w-md p-8 rounded-3xl backdrop-blur-xl bg-white/10 border border-white/20 shadow-2xl">

    <!-- TITLE -->
    <h1 class="font-playfair text-3xl font-black text-cream text-center mb-2">
        SiTam<span class="text-gold">Deals</span>
    </h1>
    <p class="text-center text-cream/70 text-sm mb-6">
        Masuk untuk melanjutkan
    </p>

    <!-- ERROR -->
    <?php if($error): ?>
        <div class="bg-red-400/20 border border-red-400 text-red-200 p-3 rounded-xl mb-4 text-sm text-center">
            <?= $error ?>
        </div>
    <?php endif; ?>

    <!-- FORM -->
    <form method="POST" class="space-y-5">

        <div>
            <label class="text-sm text-cream/70">Email</label>
            <input type="email" name="email"
            class="w-full mt-1 px-4 py-3 rounded-xl bg-white/20 text-cream placeholder:text-cream/50 border border-white/20 focus:outline-none focus:ring-2 focus:ring-gold transition"
            placeholder="Masukkan email"
            required>
        </div>

        <div>
            <label class="text-sm text-cream/70">Password</label>
            <input type="password" name="password"
            class="w-full mt-1 px-4 py-3 rounded-xl bg-white/20 text-cream placeholder:text-cream/50 border border-white/20 focus:outline-none focus:ring-2 focus:ring-gold transition"
            placeholder="Masukkan password"
            required>
        </div>

        <button type="submit"
        class="w-full bg-gold text-forest font-bold py-3 rounded-xl shadow-lg hover:bg-gold-light hover:-translate-y-1 transition-all">
            Masuk
        </button>

    </form>

    <!-- FOOTER -->
    <p class="text-center text-xs text-cream/50 mt-6">
        &copy; 2026 SiTamDeals
    </p>

</div>

</body>
</html>