<?php
// index.php
session_start();

// Jika pengguna sudah login, arahkan sesuai role masing-masing
if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
    if ($_SESSION['role'] === 'kasir') {
        header("Location: kasir.php");
    } elseif ($_SESSION['role'] === 'gudang') {
        header("Location: gudang.php");
    } else {
        header("Location: dashboard.php");
    }
    exit;
} else {
    // Jika belum login, arahkan ke halaman login
    header("Location: login.php");
    exit;
}
?>