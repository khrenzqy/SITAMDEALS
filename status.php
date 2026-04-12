<?php $id = $_GET['id']; ?>

<!DOCTYPE html>
<html>
<head>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[#f7f4ee] flex items-center justify-center min-h-screen">

<div class="bg-white p-10 rounded-2xl shadow w-[400px] text-center">

<h2 class="text-xl font-bold mb-6">Status Pesanan</h2>

<div class="flex justify-between mb-8">
  <div id="s1">●</div>
  <div id="s2">●</div>
  <div id="s3">●</div>
  <div id="s4">●</div>
</div>

<p id="text"></p>

<a id="btn" class="hidden mt-4 block bg-green-600 text-white p-2 rounded">
Lihat Struk
</a>

</div>

<script>
let id = <?= $id ?>;

function update(s){
  let map = {
    pending:1,
    diproses:2,
    siap_diambil:3,
    selesai:4
  };

  let step = map[s];

  for(let i=1;i<=4;i++){
    document.getElementById("s"+i).style.color =
      i<=step ? "green" : "gray";
  }

  document.getElementById("text").innerText = s;

  if(s=="selesai"){
    let b = document.getElementById("btn");
    b.href = "struk.php?id="+id;
    b.classList.remove("hidden");
  }
}

setInterval(()=>{
  fetch("fetch_status.php?id="+id)
  .then(r=>r.json())
  .then(d=>update(d.status));
},2000);
</script>

</body>
</html>