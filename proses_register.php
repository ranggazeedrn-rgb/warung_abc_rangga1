<?php
session_start();
include 'config/koneksi.php';

// Ambil input dari form register
$nama_lengkap = mysqli_real_escape_string($koneksi, trim($_POST['nama_lengkap']));
$username     = mysqli_real_escape_string($koneksi, trim($_POST['username']));
$password     = $_POST['password'];
$role         = mysqli_real_escape_string($koneksi, $_POST['role']);

// Validation singkat
if (empty($nama_lengkap) || empty($username) || empty($password) || empty($role)) {
    $_SESSION['pesan_error'] = 'Semua field wajib diisi!';
    header('Location: register.php');
    exit;
}

// Cek apakah username sudah dipakai
$sql_cek = "SELECT id_user FROM tbl_user WHERE username = '$username'";
$query_cek = mysqli_query($koneksi, $sql_cek);

if (mysqli_num_rows($query_cek) > 0) {
    $_SESSION['pesan_error'] = 'Username sudah digunakan, pilih username lain!';
    header('Location: register.php');
    exit;
} else {
    // Hash password menggunakan BCRYPT (kompatibel dengan password_verify di proses_login.php)
    $password_hashed = password_hash($password, PASSWORD_DEFAULT);

    // Insert user baru ke tbl_user
    $sql_insert = "INSERT INTO tbl_user (nama_lengkap, username, password, role) 
                   VALUES ('$nama_lengkap', '$username', '$password_hashed', '$role')";

    if (mysqli_query($koneksi, $sql_insert)) {
        $_SESSION['pesan_sukses'] = 'Akun berhasil dibuat! Silakan login.';
        header('Location: login.php');
        exit;
    } else {
        $_SESSION['pesan_error'] = 'Gagal mendaftarkan akun. Silakan coba lagi.';
        header('Location: register.php');
        exit;
    }
}
?>