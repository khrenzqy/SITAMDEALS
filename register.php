<?php
session_start();
include 'db.php';

$error = "";
$success = "";

// ======================
// JIKA SUDAH LOGIN → REDIRECT
// ======================
if (isset($_SESSION['user'])) {
  header("Location: index.php");
  exit;
}

// ======================
// PROSES REGISTER
// ======================
if ($_SERVER['REQUEST_METHOD'] == "POST") {

  $name   = mysqli_real_escape_string($conn, $_POST['name']);
  $email  = mysqli_real_escape_string($conn, $_POST['email']);
  $pass   = $_POST['password'];

  // cek email sudah ada
  $check = $conn->query("SELECT * FROM users WHERE email='$email'");

  if ($check && $check->num_rows > 0) {
    $error = "Email sudah terdaftar!";
  } else {

    // default role = user
    $sql = "INSERT INTO users (name, email, password, role) 
            VALUES ('$name', '$email', '$pass', 'user')";

    if ($conn->query($sql)) {
      $success = "Akun berhasil dibuat! Silakan login.";
      header("refresh:2;url=login.php");
    } else {
      $error = "Gagal membuat akun!";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Daftar – SiTamDeals</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet" />
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: { playfair:['"Playfair Display"','serif'], dm:['"DM Sans"','sans-serif'] },
          colors: {
            forest:'#1e3a2f', moss:'#2e5c42', sage:'#4a8c64',
            leaf:'#72b88a', mint:'#b8d9c5', cream:'#f7f4ee',
            gold:'#c9a84c', 'gold-light':'#e8c96a', dark:'#111a15'
          },
          keyframes: {
            fadeUp:{ from:{opacity:'0',transform:'translateY(20px)'}, to:{opacity:'1',transform:'translateY(0)'} }
          },
          animation: {
            'fade-up':'fadeUp 0.6s ease both',
            'fade-up-1':'fadeUp 0.6s ease 0.1s both',
            'fade-up-2':'fadeUp 0.6s ease 0.2s both',
            'fade-up-3':'fadeUp 0.6s ease 0.3s both',
          }
        }
      }
    }
  </script>
  <style>
    body { font-family:'DM Sans',sans-serif; }
    .input-field {
      width:100%; padding:14px 18px;
      border:1.5px solid #e2e8e4;
      border-radius:14px;
      background:#f8faf9;
      font-family:'DM Sans',sans-serif;
      font-size:0.9rem; color:#1e3a2f;
      outline:none; transition:all 0.25s;
    }
    .input-field:focus {
      border-color:#4a8c64; background:#fff;
      box-shadow:0 0 0 4px rgba(74,140,100,0.1);
    }
    .input-field::placeholder { color:#aab8b0; }
    .leaf-pattern {
      background-image: radial-gradient(rgba(114,184,138,0.07) 1px, transparent 1px);
      background-size: 28px 28px;
    }
    .strength-bar { transition: width 0.3s ease; }
  </style>
</head>
<body class="min-h-screen flex items-center justify-center p-6 leaf-pattern" style="background-color:#1a3529;">

  <!-- Deco blobs -->
  <div class="fixed top-0 left-0 w-[400px] h-[400px] rounded-full pointer-events-none" style="background:radial-gradient(circle,rgba(114,184,138,.1) 0%,transparent 70%)"></div>
  <div class="fixed bottom-0 right-0 w-[350px] h-[350px] rounded-full pointer-events-none" style="background:radial-gradient(circle,rgba(201,168,76,.08) 0%,transparent 70%)"></div>

  <div class="w-full max-w-md relative z-10">
    <!-- Card -->
    <div class="bg-white rounded-3xl shadow-2xl shadow-dark/40 p-9 lg:p-11 animate-fade-up">

      <!-- Header -->
      <div class="text-center mb-8 animate-fade-up-1">
        <div class="inline-flex items-center gap-2 mb-5">
          <div class="w-10 h-10 rounded-xl bg-forest/8 flex items-center justify-center text-xl">🛒</div>
          <span class="font-playfair text-xl font-black text-forest">SiTam<span class="text-gold">Deals</span></span>
        </div>
        <h1 class="font-playfair text-2xl font-black text-forest mb-1.5">Buat Akun Baru ✨</h1>
        <p class="text-gray-400 text-sm">Daftarkan diri kamu dan mulai belanja</p>
      </div>

      <!-- Progress steps -->
      <div class="flex items-center justify-center gap-2 mb-8 animate-fade-up-1">
        <div class="flex items-center gap-2">
          <div class="w-7 h-7 rounded-full bg-forest text-white text-xs font-bold flex items-center justify-center">1</div>
          <span class="text-xs font-semibold text-forest">Data Diri</span>
        </div>
        <div class="flex-1 h-px bg-gray-200 max-w-[40px]"></div>
        <div class="flex items-center gap-2">
          <div class="w-7 h-7 rounded-full bg-gray-100 text-gray-400 text-xs font-bold flex items-center justify-center">2</div>
          <span class="text-xs text-gray-400">Verifikasi</span>
        </div>
        <div class="flex-1 h-px bg-gray-200 max-w-[40px]"></div>
        <div class="flex items-center gap-2">
          <div class="w-7 h-7 rounded-full bg-gray-100 text-gray-400 text-xs font-bold flex items-center justify-center">3</div>
          <span class="text-xs text-gray-400">Selesai</span>
        </div>
      </div>

      <!-- Form -->
      <div class="space-y-4 animate-fade-up-2">
        <div>
          <label class="block text-[0.75rem] font-bold text-forest/70 tracking-wide uppercase mb-2">Nama Lengkap</label>
          <input type="text" class="input-field" placeholder="Nama lengkap kamu" />
        </div>
        <div>
          <label class="block text-[0.75rem] font-bold text-forest/70 tracking-wide uppercase mb-2">Email</label>
          <input type="email" class="input-field" placeholder="contoh@email.com" />
        </div>
        <div>
          <label class="block text-[0.75rem] font-bold text-forest/70 tracking-wide uppercase mb-2">No. WhatsApp</label>
          <input type="tel" class="input-field" placeholder="+62 8xx-xxxx-xxxx" />
        </div>
        <div>
          <label class="block text-[0.75rem] font-bold text-forest/70 tracking-wide uppercase mb-2">Password</label>
          <input type="password" id="pwdInput" class="input-field" placeholder="Min. 8 karakter" oninput="checkStrength(this.value)" />
          <!-- Strength indicator -->
          <div class="mt-2 flex gap-1.5">
            <div class="h-1 flex-1 rounded-full bg-gray-100 overflow-hidden">
              <div id="s1" class="strength-bar h-full w-0 rounded-full bg-red-400"></div>
            </div>
            <div class="h-1 flex-1 rounded-full bg-gray-100 overflow-hidden">
              <div id="s2" class="strength-bar h-full w-0 rounded-full bg-yellow-400"></div>
            </div>
            <div class="h-1 flex-1 rounded-full bg-gray-100 overflow-hidden">
              <div id="s3" class="strength-bar h-full w-0 rounded-full bg-sage"></div>
            </div>
          </div>
          <div id="strengthLabel" class="text-[0.7rem] text-gray-400 mt-1"></div>
        </div>
        <div>
          <label class="block text-[0.75rem] font-bold text-forest/70 tracking-wide uppercase mb-2">Konfirmasi Password</label>
          <input type="password" class="input-field" placeholder="Ulangi password" />
        </div>

        <!-- Terms -->
        <label class="flex items-start gap-3 cursor-pointer group">
          <div class="relative mt-0.5">
            <input type="checkbox" id="terms" class="sr-only peer" />
            <div class="w-5 h-5 rounded-[6px] border-2 border-gray-200 peer-checked:bg-forest peer-checked:border-forest transition-all flex items-center justify-center">
              <svg class="w-3 h-3 text-white hidden peer-checked:block" fill="none" viewBox="0 0 12 12"><path d="M2 6l3 3 5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </div>
          </div>
          <span class="text-xs text-gray-500 leading-relaxed">Saya menyetujui <a href="#" class="font-semibold text-forest hover:text-sage">Syarat & Ketentuan</a> serta <a href="#" class="font-semibold text-forest hover:text-sage">Kebijakan Privasi</a> SiTamDeals.</span>
        </label>
      </div>

      <!-- Submit -->
      <div class="mt-7 space-y-4 animate-fade-up-3">
        <button onclick="handleRegister()" class="w-full py-4 rounded-xl font-bold text-sm text-white tracking-wide transition-all hover:-translate-y-0.5 hover:shadow-lg hover:shadow-forest/25" style="background:linear-gradient(135deg,#1e3a2f,#2e5c42)">
          Daftar Sekarang →
        </button>
        <p class="text-center text-sm text-gray-400">
          Sudah punya akun? <a href="login.html" class="font-bold text-forest hover:text-sage transition-colors">Login</a>
        </p>
      </div>

      <div id="registerMsg" class="hidden mt-4 px-4 py-3 rounded-xl text-sm text-center"></div>
    </div>

    <div class="text-center mt-5">
      <a href="beranda.html" class="text-cream/40 hover:text-cream/70 text-xs transition-colors">← Kembali ke Beranda</a>
    </div>
  </div>

  <script>
    function checkStrength(val) {
      const len = val.length;
      const s1 = document.getElementById('s1');
      const s2 = document.getElementById('s2');
      const s3 = document.getElementById('s3');
      const lbl = document.getElementById('strengthLabel');
      s1.style.width = '0%'; s2.style.width = '0%'; s3.style.width = '0%';
      if (len === 0) { lbl.textContent = ''; return; }
      if (len < 6) { s1.style.width='100%'; lbl.textContent='Lemah'; lbl.className='text-[0.7rem] text-red-400 mt-1'; }
      else if (len < 10) { s1.style.width='100%'; s2.style.width='100%'; lbl.textContent='Sedang'; lbl.className='text-[0.7rem] text-yellow-500 mt-1'; }
      else { s1.style.width='100%'; s2.style.width='100%'; s3.style.width='100%'; lbl.textContent='Kuat ✓'; lbl.className='text-[0.7rem] text-sage mt-1'; }
    }
    function handleRegister() {
      const msg = document.getElementById('registerMsg');
      msg.className = 'mt-4 px-4 py-3 rounded-xl text-sm text-center bg-green-50 border border-green-200 text-green-700';
      msg.textContent = '✅ Akun berhasil dibuat! Silakan login.';
      setTimeout(() => { msg.className += ' hidden'; }, 3000);
    }
  </script>
</body>
</html>