<?php
session_start();
if (isset($_SESSION['id_user'])) {
    include 'config/koneksi.php';
    $id_user = $_SESSION['id_user'];
    $waktu   = date('Y-m-d H:i:s');
    $log     = "INSERT INTO tbl_log (id_user, aktivitas, waktu)";
    $log    .= " VALUES ('$id_user', 'logout', '$waktu')";
    mysqli_query($koneksi, $log);
}

session_unset();
session_destroy();

header('Location: login.php');
exit;
?>
