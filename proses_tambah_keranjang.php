<?php
// proses_tambah_keranjang.php
session_start();
include 'includes/cek_session.php';
include 'config/koneksi.php';

if (!isset($_SESSION['keranjang'])) {
    $_SESSION['keranjang'] = array();
}

$id_barang = $_POST['id_barang'];
$jumlah    = (int) $_POST['jumlah'];

$sql = "SELECT * FROM tbl_barang WHERE id_barang = '$id_barang'";
$hasil = mysqli_query($koneksi, $sql);
$barang = mysqli_fetch_assoc($hasil);

if ($barang && $jumlah > 0 && $jumlah <= $barang['stok']) {
    $subtotal = $barang['harga_satuan'] * $jumlah;

    $_SESSION['keranjang'][$id_barang] = array(
        'nama_barang' => $barang['nama_barang'],
        'harga'       => $barang['harga_satuan'],
        'jumlah'      => $jumlah,
        'subtotal'    => $subtotal,
    );
} else {
    $_SESSION['pesan_error'] = 'Jumlah melebihi stok atau barang tidak ditemukan!';
}

header('Location: transaksi.php');
exit;
?>