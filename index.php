<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 max-w-5xl mx-auto">

<?php while ($row = $data->fetch_assoc()) { ?>

  <div class="bg-white rounded-2xl overflow-hidden hover:-translate-y-2 hover:shadow-2xl transition-all">

    <!-- IMAGE / ICON -->
    <div class="h-48 flex items-center justify-center text-5xl relative"
      style="background:linear-gradient(135deg,#b8d9c5,#72b88a)">
      
      🛒
      
      <span class="absolute top-3 left-3 bg-gold text-forest text-xs font-bold px-2 py-1 rounded-full">
        PRODUK
      </span>
    </div>

    <!-- CONTENT -->
    <div class="p-5 space-y-2">

      <!-- NAMA -->
      <div class="font-semibold text-forest text-sm">
        <?= $row['name'] ?>
      </div>

      <!-- HARGA (kalau ada di DB) -->
      <div class="font-playfair text-lg font-bold text-sage">
        Rp <?= number_format($row['price'] ?? 0,0,',','.') ?>
      </div>

      <!-- BUTTON -->
      <button
        class="w-full mt-3 bg-forest text-white py-2 rounded-xl text-sm font-semibold hover:bg-gold hover:text-forest transition-all">
        + Tambah ke Keranjang
      </button>

    </div>

  </div>

<?php } ?>

</div>