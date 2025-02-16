<?php
session_start();

// Menampilkan error untuk debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Mengecek apakah ada parameter kode_produk dalam URL dan session keranjang
if (isset($_GET['kode_produk']) && isset($_SESSION['keranjang'])) {
    $kode_produk = $_GET['kode_produk'];

    // Cek apakah ada produk dengan kode tersebut di keranjang
    $produk_ditemukan = false; // Flag untuk mengecek apakah produk ditemukan
    foreach ($_SESSION['keranjang'] as $key => $produk) {
        if ($produk['kode_produk'] === $kode_produk) {
            // Menghapus produk dari keranjang
            unset($_SESSION['keranjang'][$key]);
            $_SESSION['keranjang'] = array_values($_SESSION['keranjang']); // Reindex array
            $produk_ditemukan = true;
            break; // Keluar dari loop setelah produk ditemukan
        }
    }

    // Jika produk ditemukan, redirect ke halaman keranjang
    if ($produk_ditemukan) {
        header("Location: keranjang.php");
        exit;
    } else {
        // Jika produk tidak ditemukan di keranjang
        $_SESSION['error'] = "Produk tidak ditemukan di keranjang.";
        header("Location: keranjang.php");
        exit;
    }
} else {
    // Jika tidak ada kode produk atau session keranjang tidak ada
    $_SESSION['error'] = "Terjadi kesalahan. Produk tidak dapat dihapus.";
    header("Location: keranjang.php");
    exit;
}
?>
