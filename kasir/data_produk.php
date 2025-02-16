<?php
include 'koneksi.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Produk</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Data Produk</h2>

        <div class="d-flex justify-content-between mt-3">
            <a href="dashboard.php" class="btn btn-primary">Kembali ke Dashboard</a>
            
            <div class="ms-auto">
                <button class="btn btn-success" onclick="window.print()">Cetak Produk</button>
            </div>
        </div>
        
        <div class="mt-4">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Produk</th>
                        <th>Nama Produk</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Gambar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Menghindari SQL Injection dengan mysqli_real_escape_string
                    $query = mysqli_query($koneksi, "SELECT * FROM produk");
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($query)) {
                        // Menampilkan gambar dengan fallback jika gambar tidak ada
                        $gambar = !empty($row['gambar']) ? "assets/{$row['gambar']}" : "assets/default.jpg"; // fallback gambar
                        
                        echo "<tr>
                            <td>{$no}</td>
                            <td>{$row['kode_produk']}</td>
                            <td>{$row['nama_produk']}</td>
                            <td>Rp " . number_format($row['harga'], 0, ',', '.') . "</td>
                            <td>{$row['stok']}</td>
                            <td><img src='{$gambar}' width='50' alt='Gambar Produk'></td>
                            <td>
                                <a href='edit_produk.php?id={$row['id']}' class='btn btn-success btn-sm'>Edit</a>
                                <a href='hapus_produk.php?id={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Yakin ingin menghapus?\")'>Hapus</a>
                            </td>
                        </tr>";
                        $no++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
