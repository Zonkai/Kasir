<?php
require 'koneksi.php';
// Ambil nama toko terbaru dari database
$nama_toko = 'Toko Gurwan';
$result = mysqli_query($koneksi, "SELECT nama_toko FROM pengaturan WHERE id=1 LIMIT 1");
if ($result && $row = mysqli_fetch_assoc($result)) {
    $nama_toko = $row['nama_toko'];
}
?>
<div class="sidebar" style="position: fixed; left: 0; top: 0;">
    <h2><?php echo htmlspecialchars($nama_toko); ?></h2>
    <ul style="text-align: right;">
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="data_produk.php">Data Produk</a></li>
        <li><a href="transaksi.php">Transaksi</a></li>
        <li><a href="laporan.php">Laporan</a></li>
        <li><a href="pengaturan.php">Pengaturan</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</div>
