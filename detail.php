
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
if(!isset($_GET['id'])) die("Produk tidak ditemukan");
$id=$_GET['id'];
$p=$conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();
?>

<script>
function updatePrice(){
let g=document.getElementById("grade").value;
let base=<?= $p['price'] ?>;
let price=base;
if(g=="A") price=base*0.85;
if(g=="B") price=base*0.7;
if(g=="C") price=base*0.5;

let el=document.getElementById("price");
el.innerText="Rp "+Math.floor(price);
el.classList.add("scale-110");
setTimeout(()=>el.classList.remove("scale-110"),200);
}

function submitCart(){
let g=document.getElementById("grade").value;
if(!g){ alert("Pilih grade dulu!"); return false; }
document.getElementById("g").value=g;
return true;
}
</script>

