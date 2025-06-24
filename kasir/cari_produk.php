<?php
session_start();
include 'koneksi.php';

if (isset($_POST['kode_produk'])) {
    $kode_produk = mysqli_real_escape_string($koneksi, $_POST['kode_produk']);
    $query = "SELECT * FROM produk WHERE kode_produk = '$kode_produk'";
    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) > 0) {
        $produk = mysqli_fetch_assoc($result);

        // Cek apakah jumlah yang diminta melebihi stok
        $jumlah_dalam_keranjang = isset($_SESSION['keranjang'][$produk['id']]) ? $_SESSION['keranjang'][$produk['id']]['jumlah'] : 0;
        if ($jumlah_dalam_keranjang + 1 > $produk['stok']) {
            echo json_encode(['error' => 'Stok tidak mencukupi']);
            exit;
        }

        $_SESSION['keranjang'][$produk['id']] = [
            'id' => $produk['id'],
            'kode_produk' => $produk['kode_produk'],
            'nama_produk' => $produk['nama_produk'],
            'harga' => $produk['harga'],
            'jumlah' => $jumlah_dalam_keranjang + 1,
            'subtotal' => $produk['harga'] * ($jumlah_dalam_keranjang + 1)
        ];

        echo json_encode($produk);
    } else {
        echo json_encode(null);
    }
}
?>
