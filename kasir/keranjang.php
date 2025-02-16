<?php
include 'koneksi.php'; // Ensure this file contains the database connection

session_start();

// Debugging: Check if 'id' is set
if (!isset($_GET['id'])) {
    echo "ID produk tidak ditemukan.";
    exit; // Stop further execution
}

$id = $_GET['id']; // Get the product ID from the URL

// Menampilkan pesan error jika ada
if (isset($_SESSION['error'])) {
    echo "<div class='alert alert-danger'>" . $_SESSION['error'] . "</div>";
    unset($_SESSION['error']); // Menghapus pesan setelah ditampilkan
}

// Check if the product is referenced in another table
$check = mysqli_query($koneksi, "SELECT * FROM transaksi WHERE produk_id = '$id'");
if (mysqli_num_rows($check) > 0) {
    // Optionally delete related transactions
    mysqli_query($koneksi, "DELETE FROM transaksi WHERE produk_id = '$id'");
}

// Proceed to delete the product
$result = mysqli_query($koneksi, "DELETE FROM produk WHERE id = '$id'");
if ($result) {
    echo "Produk berhasil dihapus.";
} else {
    echo "Terjadi kesalahan: " . mysqli_error($koneksi);
}
?>
