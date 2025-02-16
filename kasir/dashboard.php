<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kasir</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<?php include 'sidebar.php'; ?>


    <div class="container mt-4">
        <h3><b>Selamat Datang di Sistem Kasir</b></h3>
        
        <div class="row">
            <div class="col-md-4">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h5>Total Produk</h5>
                        <?php 
                            $result = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM produk");
                            if ($result) {
                                $data = mysqli_fetch_assoc($result);
                                echo "<h3>{$data['total']}</h3>";
                            } else {
                                echo "<h3>Error: " . mysqli_error($koneksi) . "</h3>";
                            }
                        ?>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5>Total Transaksi</h5>
                        <?php 
                            $result = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM transaksi");
                            if ($result) {
                                $data = mysqli_fetch_assoc($result);
                                echo "<h3>{$data['total']}</h3>";
                            } else {
                                echo "<h3>Error: " . mysqli_error($koneksi) . "</h3>";
                            }
                        ?>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5>Pendapatan Hari Ini</h5>
                        <?php 
                            $result = mysqli_query($koneksi, "SELECT SUM(total_harga) AS total FROM transaksi WHERE DATE(tanggal) = CURDATE()");
                            if ($result) {
                                $data = mysqli_fetch_assoc($result);
                                echo "<h3>Rp " . number_format($data['total'], 0, ',', '.') . "</h3>";
                            } else {
                                echo "<h3>Error: " . mysqli_error($koneksi) . "</h3>";
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
