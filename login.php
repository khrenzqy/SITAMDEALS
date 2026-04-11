
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
session_start();
include 'db.php';
$error="";

if($_POST){
    if(empty($_POST['email']) || empty($_POST['password'])){
        $error="Email & password wajib diisi";
    } else {
        $email=$_POST['email'];
        $pass=$_POST['password'];

        $res=$conn->query("SELECT * FROM users WHERE email='$email'");
        if($res->num_rows){
            $user=$res->fetch_assoc();
            if(password_verify($pass,$user['password'])){
                $_SESSION['user']=$user;
                header("Location:index.php");
            } else $error="Password salah";
        } else $error="Email tidak ditemukan";
    }
}
?>