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
$data=$conn->query("SELECT o.*,u.name as customer FROM orders o JOIN users u ON o.user_id=u.id");
?>