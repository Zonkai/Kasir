<?php
session_start();
include 'koneksi.php';

// Ambil data produk dari database
$query_produk = "SELECT * FROM produk";
$result_produk = mysqli_query($koneksi, $query_produk);

if (!$result_produk) {
    die("Error fetching products: " . mysqli_error($koneksi));
}

// Menambah produk ke keranjang
if (isset($_POST['tambah_ke_keranjang'])) {
    $produk_id = $_POST['produk_id'];
    $jumlah = $_POST['jumlah'];

    // Ambil detail produk dari database menggunakan prepared statement
    $query_detail_produk = "SELECT * FROM produk WHERE id = ?";
    $stmt = mysqli_prepare($koneksi, $query_detail_produk);
    mysqli_stmt_bind_param($stmt, "i", $produk_id);
    mysqli_stmt_execute($stmt);
    $result_detail = mysqli_stmt_get_result($stmt);
    $produk = mysqli_fetch_assoc($result_detail);

    if (!$produk) {
        die("Produk tidak ditemukan.");
    }

    // Hitung subtotal
    $subtotal = $produk['harga'] * $jumlah;

    // Simpan ke keranjang
    $produk_keranjang = array(
        'id' => $produk['id'],
        'nama' => $produk['nama_produk'], // Pastikan nama kolom sesuai dengan database
        'harga' => $produk['harga'],
        'jumlah' => $jumlah,
        'subtotal' => $subtotal
    );

    // Jika keranjang sudah ada, tambahkan produk
    if (isset($_SESSION['keranjang'])) {
        $_SESSION['keranjang'][] = $produk_keranjang;
    } else {
        $_SESSION['keranjang'] = array($produk_keranjang);
    }

    // Update total price
    $_SESSION['total_harga'] = isset($_SESSION['total_harga']) ? $_SESSION['total_harga'] + $subtotal : $subtotal;

    echo "<script>alert('Produk berhasil ditambahkan ke keranjang.'); window.location='transaksi.php';</script>";
}

// Handle removing product from cart
if (isset($_POST['action']) && $_POST['action'] === 'remove') {
    $produk_id = $_POST['produk_id'];

    // Check if keranjang exists and is an array
    if (isset($_SESSION['keranjang']) && is_array($_SESSION['keranjang'])) {
        // Loop through the cart to find the product
        foreach ($_SESSION['keranjang'] as $index => $produk) {
            if ($produk['id'] == $produk_id) {
                // Remove product from cart
                unset($_SESSION['keranjang'][$index]);
                break;
            }
        }

        // Recalculate total price
        $_SESSION['total_harga'] = array_sum(array_column($_SESSION['keranjang'], 'subtotal'));
    }

    echo "<script>alert('Produk berhasil dihapus dari keranjang.'); window.location='transaksi.php';</script>";
}

// Jika tombol simpan ditekan
if (isset($_POST['simpan'])) {
    if (!isset($_SESSION['keranjang']) || empty($_SESSION['keranjang'])) {
        echo "<script>alert('Keranjang masih kosong!'); window.location='transaksi.php';</script>";
        exit();
    }

    $total_harga = 0;
    foreach ($_SESSION['keranjang'] as $produk) {
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

        // Simpan detail transaksi dari session
        foreach ($_SESSION['keranjang'] as $produk) {
            $produk_id = mysqli_real_escape_string($koneksi, $produk['id']);
            $jumlah = mysqli_real_escape_string($koneksi, $produk['jumlah']);
            $subtotal = mysqli_real_escape_string($koneksi, $produk['subtotal']);

            $query_detail = "INSERT INTO detail_transaksi (transaksi_id, produk_id, jumlah, subtotal) 
                             VALUES ('$transaksi_id', '$produk_id', '$jumlah', '$subtotal')";
            mysqli_query($koneksi, $query_detail);
        }

        // Kosongkan keranjang setelah transaksi berhasil
        unset($_SESSION['keranjang']);

        // Reset total price after saving
        $_SESSION['total_harga'] = 0; // Reset total price

        echo "<script>alert('Transaksi Berhasil!'); window.location='transaksi.php';</script>";
    } else {
        echo "<script>alert('Gagal menyimpan transaksi.'); window.location='transaksi.php';</script>";
    }
}

// Ensure total price is set for display
$total_harga = isset($_SESSION['total_harga']) ? $_SESSION['total_harga'] : 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        .text-white {
            color: white;
            font-weight: bold;
            font-size: 1.2em;
            background-color: #333;
            padding: 5px;
            border-radius: 5px;
        }

        /* Media print untuk tampilan cetak */
        @media print {
            .btn, #totalHarga, form {
                display: none; /* Sembunyikan tombol dan form pada saat cetak */
            }
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h3>Transaksi</h3>

        <!-- Menampilkan daftar produk -->
        <h4>Daftar Produk</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama Produk</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($produk = mysqli_fetch_assoc($result_produk)): ?>
                    <tr>
                        <td><?= $produk['nama_produk']; ?></td>
                        <td>Rp <?= number_format($produk['harga'], 0, ',', '.'); ?></td>
                        <td>
                            <form method="POST" action="transaksi.php">
                                <input type="number" name="jumlah" value="1" min="1" class="form-control" required>
                                <input type="hidden" name="produk_id" value="<?= $produk['id']; ?>">
                        </td>
                        <td>
                            <button type="submit" name="tambah_ke_keranjang" class="btn btn-success">Tambah ke Keranjang</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Keranjang Belanja -->
        <h4>Keranjang Belanja</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama Produk</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($_SESSION['keranjang'])): ?>
                    <?php foreach ($_SESSION['keranjang'] as $index => $produk): ?>
                        <tr>
                            <td><?= $produk['nama']; ?></td>
                            <td>Rp <?= number_format($produk['harga'], 0, ',', '.'); ?></td>
                            <td><?= $produk['jumlah']; ?></td>
                            <td>Rp <?= number_format($produk['subtotal'], 0, ',', '.'); ?></td>
                            <td>
                                <form method="POST" action="transaksi.php" style="display:inline;">
                                    <input type="hidden" name="produk_id" value="<?= $produk['id']; ?>">
                                    <input type="hidden" name="action" value="remove">
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Total Harga -->
        <div class="mt-4">
            <span>Total Harga: </span>
            <span id="totalHarga" class="text-white"><?= number_format($total_harga, 0, ',', '.'); ?></span>
        </div>

        <!-- Tombol Aksi -->
        <div class="d-flex justify-content-between mt-3">
            <a href="dashboard.php" class="btn btn-warning">Kembali ke Dashboard</a>
            
            <div class="mx-auto">
                <button class="btn btn-primary" onclick="window.print()">Cetak Transaksi</button>
            </div>
            
            <form method="POST" action="transaksi.php" class="mt-3">
            <button type="submit" name="simpan" class="btn btn-primary">Simpan Transaksi</button>
        </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
