<?php
// proses_simpan_transaksi.php
session_start();
include 'includes/cek_session.php';
include 'config/koneksi.php';

if (empty($_SESSION['keranjang'])) {
    $_SESSION['pesan_error'] = 'Keranjang masih kosong!';
    header('Location: kasir.php');
    exit;
}

$id_kasir     = $_SESSION['id_user'];
// 1. Generate nomor transaksi otomatis
$no_transaksi = 'TRX-' . date('YmdHis'); 
$tanggal      = date('Y-m-d H:i:s');

$total = 0;
foreach ($_SESSION['keranjang'] as $item) {
    $total += $item['subtotal'];
}

// 2. Masukkan no_transaksi ke dalam query INSERT
$sql = "INSERT INTO tbl_transaksi (no_transaksi, tanggal, id_kasir, id_pelanggan, total_bayar)";
$sql .= " VALUES ('$no_transaksi', '$tanggal', '$id_kasir', NULL, '$total')";
mysqli_query($koneksi, $sql);

$id_transaksi = mysqli_insert_id($koneksi);

foreach ($_SESSION['keranjang'] as $id_barang => $item) {
    $jumlah   = $item['jumlah'];
    $subtotal = $item['subtotal'];

    $detail = "INSERT INTO tbl_detail_transaksi (id_transaksi, id_barang, jumlah, subtotal)";
    $detail .= " VALUES ('$id_transaksi', '$id_barang', '$jumlah', '$subtotal')";
    mysqli_query($koneksi, $detail);

    $update_stok = "UPDATE tbl_barang SET stok = stok - $jumlah WHERE id_barang = '$id_barang'";
    mysqli_query($koneksi, $update_stok);
}

$waktu     = date('Y-m-d H:i:s');
$aktivitas = "transaksi: $no_transaksi";
$log = "INSERT INTO tbl_log (id_user, aktivitas, waktu) VALUES ('$id_kasir', '$aktivitas', '$waktu')";
mysqli_query($koneksi, $log);

unset($_SESSION['keranjang']);

header('Location: riwayat_transaksi.php');
exit;
?>