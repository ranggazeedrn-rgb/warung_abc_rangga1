<?php
// proses_edit_barang.php
session_start();
include 'includes/cek_session.php';
include 'config/koneksi.php';

$id    = $_POST['id_barang'];
$kode  = mysqli_real_escape_string($koneksi, $_POST['kode_barang']);
$nama  = mysqli_real_escape_string($koneksi, $_POST['nama_barang']);
$harga = $_POST['harga_satuan'];
$stok  = $_POST['stok'];
$exp   = $_POST['tanggal_kadaluarsa'];
$exp_sql = $exp === '' ? 'NULL' : "'$exp'";

$sql = "UPDATE tbl_barang SET kode_barang='$kode', nama_barang='$nama', ";
$sql .= "harga_satuan='$harga', stok='$stok', tanggal_kadaluarsa=$exp_sql ";
$sql .= "WHERE id_barang = '$id'";

if (mysqli_query($koneksi, $sql)) {
    $id_user   = $_SESSION['id_user'];
    $waktu     = date('Y-m-d H:i:s');
    $aktivitas = "edit barang: $nama";
    $log = "INSERT INTO tbl_log (id_user, aktivitas, waktu) VALUES ('$id_user', '$aktivitas',
'$waktu')";
    mysqli_query($koneksi, $log);

    header('Location: data_barang.php');
    exit;
} else {
    echo 'Gagal mengubah data: ' . mysqli_error($koneksi);
}
?>