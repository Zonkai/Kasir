<?php
session_start();
require 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_toko = trim($_POST['nama_toko']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if ($nama_toko === '' || $username === '' || $password === '') {
        header('Location: Pengaturan.php?error=Nama toko, username, dan password wajib diisi');
        exit();
    }

    // Hash password baru
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Ambil user id dari session (atau sesuaikan dengan sistem login Anda)
    // Contoh: $_SESSION['user_id']
    $user_id = $_SESSION['user_id'] ?? 1; // fallback ke 1 jika tidak ada session

    // Update username dan password di database
    $query = "UPDATE pengguna SET username = ?, password = ? WHERE id = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, 'ssi', $username, $password_hash, $user_id);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt); // Tambahkan ini untuk memastikan statement ditutup

    // Update nama toko di tabel pengaturan (selalu id=1)
    $query_toko = "UPDATE pengaturan SET nama_toko = ? WHERE id = 1";
    $stmt_toko = mysqli_prepare($koneksi, $query_toko);
    mysqli_stmt_bind_param($stmt_toko, 's', $nama_toko);
    $success_toko = mysqli_stmt_execute($stmt_toko);
    mysqli_stmt_close($stmt_toko); // Tambahkan ini juga

    if ($success && $success_toko) {
        // Jika username diubah, update session
        $_SESSION['username'] = $username;
        header('Location: Pengaturan.php?message=Berhasil update akun & nama toko');
    } else {
        header('Location: Pengaturan.php?error=Gagal update akun/nama toko');
    }
    exit();
} else {
    header('Location: Pengaturan.php');
    exit();
}
