<?php
// includes/cek_session.php

// Cek apakah session sudah aktif sebelum memanggil session_start()
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header('Location: login.php');
    exit;
}
?>