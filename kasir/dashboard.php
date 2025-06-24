<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kasir</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/style.css">
    <style>
        body {
            padding-left: 250px; /* Sesuaikan dengan lebar sidebar */
        }
    </style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="container mt-4">
    <h3><b>Selamat Datang di Sistem Kasir</b></h3>
    
    <div class="row">
        <!-- Total Produk -->
        <div class="col-md-4">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5>Total Produk</h5>
                    <?php 
                        $result = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM produk");
                        if ($result) {
                            $data = mysqli_fetch_assoc($result);
                            $total_produk = $data['total'] ?? 0; // Jika NULL, set ke 0
                            echo "<h3>{$total_produk}</h3>";
                        } else {
                            echo "<h3>Error: " . mysqli_error($koneksi) . "</h3>";
                        }
                    ?>
                </div>
            </div>
        </div>

        <!-- Total Transaksi -->
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5>Total Transaksi</h5>
                    <?php 
                        $result = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM transaksi");
                        if ($result) {
                            $data = mysqli_fetch_assoc($result);
                            $total_transaksi = $data['total'] ?? 0; // Jika NULL, set ke 0
                            echo "<h3>{$total_transaksi}</h3>";
                        } else {
                            echo "<h3>Error: " . mysqli_error($koneksi) . "</h3>";
                        }
                    ?>
                </div>
            </div>
        </div>

        <!-- Pendapatan Hari Ini -->
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5>Pendapatan Hari Ini</h5>
                    <?php 
                        $result = mysqli_query($koneksi, "SELECT SUM(total_harga) AS total FROM transaksi WHERE DATE(tanggal) = CURDATE()");
                        if ($result) {
                            $data = mysqli_fetch_assoc($result);
                            $pendapatan_hari_ini = $data['total'] ?? 0; // Jika NULL, set ke 0
                            echo "<h3>Rp " . number_format($pendapatan_hari_ini, 0, ',', '.') . "</h3>";
                        } else {
                            echo "<h3>Error: " . mysqli_error($koneksi) . "</h3>";
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Riwayat Pembayaran -->
    <div class="mt-5">
        <h4>Riwayat Pembayaran Terbaru</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Kode Transaksi</th>
                    <th>Tanggal</th>
                    <th>Total Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Ambil 5 transaksi terbaru dari database
                $query_history = "SELECT * FROM transaksi ORDER BY tanggal DESC LIMIT 5";
                $result_history = mysqli_query($koneksi, $query_history);

                if ($result_history && mysqli_num_rows($result_history) > 0) {
                    while ($row = mysqli_fetch_assoc($result_history)) {
                        echo "<tr>
                                <td>{$row['kode_transaksi']}</td>
                                <td>" . date('d-m-Y H:i', strtotime($row['tanggal'])) . "</td>
                                <td>Rp " . number_format($row['total_harga'], 0, ',', '.') . "</td>
                                <td>
                                    <a href='detail_transaksi.php?id={$row['id']}' class='btn btn-primary btn-sm'>Detail</a>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' class='text-center'>Belum ada transaksi</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>