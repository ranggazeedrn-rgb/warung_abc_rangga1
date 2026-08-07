<?php
// proses_login.php
session_start();
include 'config/koneksi.php';

$username = mysqli_real_escape_string($koneksi, $_POST['username']);
$password = $_POST['password'];

$sql = "SELECT * FROM tbl_user WHERE username = '$username'";
$hasil = mysqli_query($koneksi, $sql);

if (mysqli_num_rows($hasil) == 1) {
    $data = mysqli_fetch_assoc($hasil);

    if (password_verify($password, $data['password'])) {
        // Password cocok, buat session
        $_SESSION['login']        = true;
        $_SESSION['id_user']      = $data['id_user'];
        $_SESSION['nama_lengkap'] = $data['nama_lengkap'];
        $_SESSION['role']         = $data['role'];

        // Catat aktivitas ke tbl_log
        $id_user = $data['id_user'];
        $waktu   = date('Y-m-d H:i:s');
        $log     = "INSERT INTO tbl_log (id_user, aktivitas, waktu)";
        $log    .= " VALUES ('$id_user', 'login', '$waktu')";
        mysqli_query($koneksi, $log);

        // Pengalihan halaman berdasarkan Role
        if ($data['role'] === 'kasir') {
            header('Location: kasir.php');
        } elseif ($data['role'] === 'gudang') {
            header('Location: gudang.php'); // Direct ke gudang.php jika role gudang
        } else {
            header('Location: dashboard.php'); // Default untuk admin
        }
        exit;

    } else {
        $_SESSION['pesan_error'] = 'Password salah!';
        header('Location: login.php');
        exit;
    }
} else {
    $_SESSION['pesan_error'] = 'Username tidak ditemukan!';
    header('Location: login.php');
    exit;
}
?>