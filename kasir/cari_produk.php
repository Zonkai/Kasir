<?php
session_start();
include 'koneksi.php';

if (isset($_POST['kode_produk'])) {
    $kode_produk = mysqli_real_escape_string($koneksi, $_POST['kode_produk']);
    $query = "SELECT * FROM produk WHERE kode_produk = '$kode_produk'";
    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) > 0) {
        $produk = mysqli_fetch_assoc($result);

        $_SESSION['keranjang'][$produk['id']] = [
            'id' => $produk['id'],
            'kode_produk' => $produk['kode_produk'],
            'nama_produk' => $produk['nama_produk'],
            'harga' => $produk['harga'],
            'jumlah' => 1,
            'subtotal' => $produk['harga']
        ];

        echo json_encode($produk);
    } else {
        echo json_encode(null);
    }
}
?>
