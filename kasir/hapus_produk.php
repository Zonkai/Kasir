<?php
session_start();

// Menampilkan error untuk debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'koneksi.php';

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    
    // Hapus entri terkait di tabel detail_transaksi
    $deleteDetailQuery = "DELETE FROM detail_transaksi WHERE produk_id = '$id'";
    mysqli_query($koneksi, $deleteDetailQuery);
    
    // Query untuk menghapus produk berdasarkan ID
    $query = "DELETE FROM produk WHERE id = '$id'";
    
    if (mysqli_query($koneksi, $query)) {
        header("Location: data_produk.php");
    } else {
        echo "Gagal menghapus produk!";
    }
} else {
    header("Location: data_produk.php");
}
?>
