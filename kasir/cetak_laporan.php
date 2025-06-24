<?php
session_start();
require 'koneksi.php';

// Ambil data transaksi dari database
$query_transaksi = "SELECT * FROM transaksi ORDER BY tanggal DESC";
$result_transaksi = mysqli_query($koneksi, $query_transaksi);

if (!$result_transaksi) {
    die("Error fetching transactions: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Transaksi</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
        }
        .table th, .table td {
            text-align: left;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h3 class="text-center">Laporan Transaksi</h3>
        <p class="text-center">Tanggal Cetak: <?= date('d-m-Y H:i'); ?></p>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Kode Transaksi</th>
                    <th>Tanggal</th>
                    <th>Total Harga</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($transaksi = mysqli_fetch_assoc($result_transaksi)): ?>
                    <tr>
                        <td><?= $transaksi['kode_transaksi']; ?></td>
                        <td><?= date('d-m-Y h:i A', strtotime($transaksi['tanggal'])); ?></td>
                        <td>Rp <?= number_format($transaksi['total_harga'], 0, ',', '.'); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script>
        // Cetak halaman secara otomatis saat dibuka
        window.print();
    </script>
</body>
</html>