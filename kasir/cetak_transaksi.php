<?php
session_start();
require 'koneksi.php';

// Pastikan keranjang tidak kosong
if (!isset($_SESSION['keranjang']) || empty($_SESSION['keranjang'])) {
    echo "<script>alert('Keranjang masih kosong!'); window.close();</script>";
    exit();
}

// Simpan data keranjang sementara sebelum menghapusnya
$keranjang_sementara = $_SESSION['keranjang'];

// Simpan transaksi ke database
$total_harga = 0;
foreach ($keranjang_sementara as $produk) {
    $total_harga += $produk['subtotal'];
}

$total_harga = mysqli_real_escape_string($koneksi, $total_harga);
$tanggal = date('Y-m-d H:i:s');

// Buat kode transaksi otomatis berdasarkan ID terakhir
$result = mysqli_query($koneksi, "SELECT MAX(id) AS last_id FROM transaksi");
$row = mysqli_fetch_assoc($result);
$last_id = $row['last_id'] ? $row['last_id'] + 1 : 1;

$kode_transaksi = "TRX-" . date("Ymd") . "-" . str_pad($last_id, 4, "0", STR_PAD_LEFT);

// Simpan transaksi ke database
$query_transaksi = "INSERT INTO transaksi (kode_transaksi, total_harga, tanggal) VALUES ('$kode_transaksi', '$total_harga', '$tanggal')";

if (mysqli_query($koneksi, $query_transaksi)) {
    $transaksi_id = mysqli_insert_id($koneksi);

    // Simpan detail transaksi dari keranjang sementara
    foreach ($keranjang_sementara as $produk) {
        $produk_id = mysqli_real_escape_string($koneksi, $produk['id']);
        $jumlah = mysqli_real_escape_string($koneksi, $produk['jumlah']);
        $subtotal = mysqli_real_escape_string($koneksi, $produk['subtotal']);

        $query_detail = "INSERT INTO detail_transaksi (transaksi_id, produk_id, jumlah, subtotal) 
                         VALUES ('$transaksi_id', '$produk_id', '$jumlah', '$subtotal')";
        mysqli_query($koneksi, $query_detail);

        // Kurangi stok produk
        $query_update_stok = "UPDATE produk SET stok = stok - '$jumlah' WHERE id = '$produk_id'";
        mysqli_query($koneksi, $query_update_stok);
    }
} else {
    echo "<script>alert('Gagal menyimpan transaksi.'); window.close();</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Transaksi</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
        }
        .table th, .table td {
            text-align: left;
        }
        .total {
            font-weight: bold;
            font-size: 1.2em;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="container">
        <h3 class="text-center">Struk Transaksi</h3>
        <p><strong>Kode Transaksi:</strong> <?php echo $kode_transaksi; ?></p>
        <p><strong>Tanggal:</strong> <?php echo date('d-m-Y H:i', strtotime($tanggal)); ?></p>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama Produk</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Gunakan data dari keranjang sementara untuk menampilkan struk
                foreach ($keranjang_sementara as $produk) {
                    echo "<tr>
                            <td>" . htmlspecialchars($produk['nama']) . "</td>
                            <td>Rp " . number_format($produk['harga'], 0, ',', '.') . "</td>
                            <td>" . $produk['jumlah'] . "</td>
                            <td>Rp " . number_format($produk['subtotal'], 0, ',', '.') . "</td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>

        <p class="total">Total Harga: Rp <?php echo number_format($total_harga, 0, ',', '.'); ?></p>
    </div>
    <script>
      // Otomatis buka dialog print ketika halaman struk transaksi terbuka
      window.onload = function() {
        window.print();
      };
    </script>
</body>
</html>

<?php
// Kosongkan keranjang setelah transaksi berhasil dan struk ditampilkan
unset($_SESSION['keranjang']);
$_SESSION['total_harga'] = 0; // Reset total price
?>
