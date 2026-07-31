<?php
session_start();

// Jika pengguna sudah login, arahkan langsung ke dashboard
if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
    header("Location: dashboard.php");
    exit;
} else {
    // Jika belum login, arahkan ke halaman login
    header("Location: login.php");
    exit;
}
?>