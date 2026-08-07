<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// --- AMBIL DATA STATISTIK DARI DATABASE ---

// 1. Total Barang & Stok Menipis (stok <= 5)
$q_barang = mysqli_query($koneksi, "SELECT COUNT(*) as total_item, SUM(CASE WHEN stok <= 5 THEN 1 ELSE 0 END) as stok_nipis FROM tbl_barang");
$d_barang = mysqli_fetch_assoc($q_barang);
$total_barang = $d_barang['total_item'] ?? 0;
$stok_menipis = $d_barang['stok_nipis'] ?? 0;

// 2. Transaksi Hari Ini & Total Omset
$today = date('Y-m-d');
$q_transaksi = mysqli_query($koneksi, "SELECT COUNT(*) as total_trx, SUM(total_bayar) as total_omset FROM tbl_transaksi WHERE DATE(tanggal) = '$today'");
$d_transaksi = mysqli_fetch_assoc($q_transaksi);
$total_trx_hari_ini = $d_transaksi['total_trx'] ?? 0;
$omset_hari_ini     = $d_transaksi['total_omset'] ?? 0;

// 3. Total Pengguna / User
$q_user = mysqli_query($koneksi, "SELECT COUNT(*) as total_user FROM tbl_user");
$d_user = mysqli_fetch_assoc($q_user);
$total_user = $d_user['total_user'] ?? 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Warung AJOPP</title>
    <!-- Import Google Font Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-color: #f8fafc;
            --navbar-bg: #ffffff;
            --card-bg: #ffffff;
            --primary: #0f172a; /* Slate 900 */
            --primary-light: #f1f5f9;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --danger-bg: #fef2f2;
            --danger-text: #ef4444;
            --danger-border: #fecaca;
            --warning-bg: #fffbeb;
            --warning-text: #b45309;
            --warning-border: #fde68a;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        body {
            background-color: var(--bg-color);
            background-image: radial-gradient(#e2e8f0 0.8px, transparent 0.8px);
            background-size: 16px 16px;
            color: var(--text-main);
            min-height: 100vh;
        }

        /* Top Navigation */
        .navbar {
            background-color: var(--navbar-bg);
            height: 64px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 32px;
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .navbar-brand {
            font-size: 18px;
            font-weight: 700;
            letter-spacing: -0.02em;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .navbar-brand-badge {
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            background: #dbeafe;
            color: #1e40af;
            padding: 2px 6px;
            border-radius: 4px;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .user-info {
            text-align: right;
        }

        .user-name {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-main);
            line-height: 1.2;
        }

        .user-role {
            font-size: 11px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.03em;
            font-weight: 500;
        }

        .btn-logout {
            padding: 7px 14px;
            background-color: transparent;
            border: 1px solid var(--border-color);
            color: var(--text-main);
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.15s ease-in-out;
        }

        .btn-logout:hover {
            background-color: var(--danger-bg);
            color: var(--danger-text);
            border-color: var(--danger-border);
        }

        /* Main Container */
        .container {
            max-width: 1100px;
            margin: 32px auto;
            padding: 0 24px;
        }

        /* Header Welcome Card */
        .welcome-card {
            background: var(--card-bg);
            padding: 28px 32px;
            border-radius: 16px;
            border: 1px solid var(--border-color);
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.02), 0 10px 15px -3px rgba(0, 0, 0, 0.03);
            margin-bottom: 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }

        .welcome-title {
            font-size: 22px;
            font-weight: 700;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .role-badge {
            font-size: 11px;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 9999px;
            background-color: var(--primary-light);
            color: var(--primary);
            text-transform: uppercase;
            border: 1px solid var(--border-color);
        }

        .welcome-sub {
            color: var(--text-muted);
            font-size: 13px;
            margin-top: 4px;
        }

        .date-chip {
            background-color: var(--primary-light);
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-muted);
        }

        /* Alert Warning Stok */
        .alert-warning-box {
            background-color: var(--warning-bg);
            border: 1px solid var(--warning-border);
            color: var(--warning-text);
            padding: 12px 18px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* Metrics / Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: var(--card-bg);
            padding: 20px;
            border-radius: 14px;
            border: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background-color: var(--primary-light);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .stat-content .label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            color: var(--text-muted);
            letter-spacing: 0.02em;
        }

        .stat-content .value {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-main);
            margin-top: 2px;
        }

        /* Section Navigation Grid */
        .section-header {
            font-size: 15px;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 16px;
        }

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 16px;
        }

        .menu-card {
            background: var(--card-bg);
            padding: 22px;
            border-radius: 14px;
            border: 1px solid var(--border-color);
            text-decoration: none;
            color: var(--text-main);
            transition: all 0.2s ease-in-out;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .menu-card:hover {
            border-color: var(--primary);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.04);
        }

        .menu-icon-box {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background-color: var(--primary-light);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .menu-title {
            font-size: 15px;
            font-weight: 600;
        }

        .menu-desc {
            font-size: 12px;
            color: var(--text-muted);
            line-height: 1.5;
        }

        @media (max-width: 640px) {
            .navbar { padding: 0 16px; }
            .container { margin: 16px auto; padding: 0 16px; }
            .welcome-card { padding: 20px; }
        }
    </style>
</head>
<body>

<!-- Navbar Utama -->
<nav class="navbar">
    <div class="navbar-brand">
        Warung AJOPP
        <span class="navbar-brand-badge">POS Panel</span>
    </div>
    <div class="user-profile">
        <div class="user-info">
            <div class="user-name"><?php echo htmlspecialchars($_SESSION['nama_lengkap']); ?></div>
            <div class="user-role"><?php echo htmlspecialchars($_SESSION['role']); ?></div>
        </div>
        <!-- Link Logout -->
        <a href="logout.php" class="btn-logout">Keluar</a>
    </div>
</nav>

<!-- Main Container -->
<div class="container">

    <!-- Banner Ucapan Selamat Datang -->
    <div class="welcome-card">
        <div>
            <div class="welcome-title">
                Selamat Datang, <?php echo htmlspecialchars($_SESSION['nama_lengkap']); ?>!
                <span class="role-badge"><?php echo htmlspecialchars($_SESSION['role']); ?></span>
            </div>
            <div class="welcome-sub">
                Akses ringkasan data dan kelola modul aplikasi warung dari panel ini.
            </div>
        </div>
        <div class="date-chip">
            📅 <?php echo date('d F Y'); ?>
        </div>
    </div>

    <!-- Alert Stok Menipis -->
    <?php if ($stok_menipis > 0): ?>
        <div class="alert-warning-box">
            <span>⚠️ Perhatian: Terdapat <strong><?php echo $stok_menipis; ?> produk</strong> dengan stok <= 5 unit!</span>
            <a href="data_barang.php" style="color: var(--warning-text); font-weight: 700; text-decoration: underline;">Periksa Barang</a>
        </div>
    <?php endif; ?>

    <!-- Realtime Summary Statistics -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">📦</div>
            <div class="stat-content">
                <div class="label">Total Produk</div>
                <div class="value"><?php echo number_format($total_barang); ?> Item</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">🛒</div>
            <div class="stat-content">
                <div class="label">Transaksi Hari Ini</div>
                <div class="value"><?php echo number_format($total_trx_hari_ini); ?> TRX</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">💰</div>
            <div class="stat-content">
                <div class="label">Omset Hari Ini</div>
                <div class="value">Rp <?php echo number_format($omset_hari_ini, 0, ',', '.'); ?></div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">👥</div>
            <div class="stat-content">
                <div class="label">Pengguna Aktif</div>
                <div class="value"><?php echo number_format($total_user); ?> User</div>
            </div>
        </div>
    </div>

    <!-- Modul Akses yang Disesuaikan dengan File di Folder Project -->
    <div class="section-header">Menu Akses & Modul Sistem</div>
    <div class="menu-grid">

        <!-- 1. Menu Kasir -->
        <a href="kasir.php" class="menu-card">
            <div class="menu-icon-box">💳</div>
            <div class="menu-title">Panel Kasir</div>
            <div class="menu-desc">Form transaksi penjualan dan pengelolaan keranjang belanja kasir.</div>
        </a>

        <!-- 2. Menu Master Data Barang -->
        <a href="data_barang.php" class="menu-card">
            <div class="menu-icon-box">📦</div>
            <div class="menu-title">Data Barang</div>
            <div class="menu-desc">Katalog master barang, melihat harga, stok, serta aksi edit & hapus.</div>
        </a>

        <!-- 3. Menu Tambah Barang -->
        <a href="tambah_barang.php" class="menu-card">
            <div class="menu-icon-box">➕</div>
            <div class="menu-title">Tambah Barang</div>
            <div class="menu-desc">Form input untuk menambahkan item/produk baru ke dalam database.</div>
        </a>

        <!-- 4. Menu Transaksi -->
        <a href="transaksi.php" class="menu-card">
            <div class="menu-icon-box">📊</div>
            <div class="menu-title">Daftar Transaksi</div>
            <div class="menu-desc">Melihat data aktivitas seluruh transaksi toko.</div>
        </a>

        <!-- 5. Menu Riwayat Transaksi -->
        <a href="riwayat_transaksi.php" class="menu-card">
            <div class="menu-icon-box">📑</div>
            <div class="menu-title">Riwayat Transaksi</div>
            <div class="menu-desc">Laporan detail riwayat belanja beserta nama kasir dan total bayar.</div>
        </a>

        <!-- 6. Menu Registrasi User Baru -->
        <a href="register.php" class="menu-card">
            <div class="menu-icon-box">👤</div>
            <div class="menu-title">Registrasi Akun</div>
            <div class="menu-desc">Daftarkan akun pengguna atau kasir baru ke dalam sistem.</div>
        </a>

    </div>

</div>

</body>
</html>