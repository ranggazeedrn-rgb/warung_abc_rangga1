<?php
// gudang.php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Proteksi Tambahan: Pastikan role admin / gudang yang dapat mengakses
if (isset($_SESSION['role']) && $_SESSION['role'] === 'kasir') {
    header("Location: kasir.php");
    exit;
}

// 1. Ambil Ringkasan Statistik Gudang
$q_total = mysqli_query($koneksi, "SELECT COUNT(*) as total_item, SUM(stok) as total_stok FROM tbl_barang");
$d_total = mysqli_fetch_assoc($q_total);
$total_item = $d_total['total_item'] ?? 0;
$total_stok = $d_total['total_stok'] ?? 0;

// 2. Ambil Barang Stok Menipis (Stok <= 5)
$q_warning = mysqli_query($koneksi, "SELECT COUNT(*) as stok_nipis FROM tbl_barang WHERE stok <= 5");
$d_warning = mysqli_fetch_assoc($q_warning);
$stok_menipis = $d_warning['stok_nipis'] ?? 0;

// 3. Ambil Daftar Semua Barang di Gudang
$sql_barang = "SELECT * FROM tbl_barang ORDER BY stok ASC, nama_barang ASC";
$res_barang = mysqli_query($koneksi, $sql_barang);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Gudang & Stok - Warung ABC</title>
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
            --warning-bg: #fffbeb;
            --warning-text: #b45309;
            --warning-border: #fde68a;
            --danger-bg: #fef2f2;
            --danger-text: #ef4444;
            --success-bg: #f0fdf4;
            --success-text: #16a34a;
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
            background: #fef3c7;
            color: #d97706;
            padding: 2px 6px;
            border-radius: 4px;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .user-info { text-align: right; }
        .user-name { font-size: 13px; font-weight: 600; color: var(--text-main); }
        .user-role { font-size: 11px; color: var(--text-muted); text-transform: uppercase; }

        .btn-back {
            padding: 7px 14px;
            background-color: transparent;
            border: 1px solid var(--border-color);
            color: var(--text-main);
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.15s;
        }

        .btn-back:hover { background-color: var(--primary-light); }

        /* Container Main */
        .container {
            max-width: 1100px;
            margin: 32px auto;
            padding: 0 24px;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
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
        }

        .stat-content .value {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-main);
            margin-top: 2px;
        }

        /* Table Card Frame */
        .card-table {
            background: var(--card-bg);
            border-radius: 16px;
            border: 1px solid var(--border-color);
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.02), 0 10px 15px -3px rgba(0, 0, 0, 0.03);
            overflow: hidden;
        }

        .card-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .card-title { font-size: 18px; font-weight: 700; color: var(--text-main); }

        .btn-add {
            background-color: var(--primary);
            color: #ffffff;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: opacity 0.2s;
        }

        .btn-add:hover { opacity: 0.9; }

        /* Table Styling */
        .table-responsive { width: 100%; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; text-align: left; font-size: 13px; }

        th {
            background-color: var(--primary-light);
            color: var(--text-muted);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.05em;
            padding: 14px 20px;
            border-bottom: 1px solid var(--border-color);
        }

        td {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-main);
        }

        tr:last-child td { border-bottom: none; }
        tr:hover td { background-color: #fdfdfd; }

        /* Stok Badges */
        .badge-stok {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 12px;
            background-color: var(--success-bg);
            color: var(--success-text);
        }

        .badge-low {
            background-color: var(--warning-bg);
            color: var(--warning-text);
            border: 1px solid var(--warning-border);
        }

        .badge-empty {
            background-color: var(--danger-bg);
            color: var(--danger-text);
        }

        /* Update CSS di gudang.php */
.btn-action {
    display: inline-block;
    padding: 6px 14px;
    border: 1px solid var(--border-color);
    border-radius: 6px;
    color: var(--text-main);
    text-decoration: none;
    font-size: 12px;
    font-weight: 600;
    white-space: nowrap; /* Mencegah teks terpotong / turun ke bawah */
    transition: all 0.15s ease-in-out;
}

.btn-action:hover {
    background-color: var(--primary-light);
    border-color: #cbd5e1;
}

        @media (max-width: 640px) {
            .navbar { padding: 0 16px; }
            .container { margin: 16px auto; padding: 0 16px; }
        }
    </style>
</head>
<body>

<!-- Top Navbar -->
<nav class="navbar">
    <div class="navbar-brand">
        Warung ABC
        <span class="navbar-brand-badge">Manajemen Gudang</span>
    </div>
    <div class="user-profile">
        <div class="user-info">
            <div class="user-name"><?php echo htmlspecialchars($_SESSION['nama_lengkap']); ?></div>
            <div class="user-role"><?php echo htmlspecialchars($_SESSION['role']); ?></div>
        </div>
        <a href="dashboard.php" class="btn-back">← Dashboard</a>
    </div>
</nav>

<!-- Main Container -->
<div class="container">

    <!-- Metrics Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">🏬</div>
            <div class="stat-content">
                <div class="label">Jenis Item</div>
                <div class="value"><?php echo number_format($total_item); ?> Produk</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">📦</div>
            <div class="stat-content">
                <div class="label">Total Stok Fisik</div>
                <div class="value"><?php echo number_format($total_stok); ?> Unit</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">⚠️</div>
            <div class="stat-content">
                <div class="label">Stok Perlu Restok</div>
                <div class="value" style="color: <?php echo ($stok_menipis > 0) ? 'var(--warning-text)' : 'inherit'; ?>;">
                    <?php echo number_format($stok_menipis); ?> Item
                </div>
            </div>
        </div>
    </div>

    <!-- Table Inventory Card -->
    <div class="card-table">
        <div class="card-header">
            <div>
                <div class="card-title">Katalog & Monitoring Stok Gudang</div>
            </div>
            <a href="tambah_barang.php" class="btn-add">+ Input Barang Baru</a>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Kode</th>
                        <th>Nama Barang</th>
                        <th>Harga Satuan</th>
                        <th>Jumlah Stok</th>
                        <th>Status Restok</th>
                        <th style="width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if ($res_barang && mysqli_num_rows($res_barang) > 0) {
                        $no = 1;
                        while ($row = mysqli_fetch_assoc($res_barang)) { 
                            $stok  = $row['stok'] ?? 0;
                            $harga = $row['harga_satuan'] ?? $row['harga_jual'] ?? $row['harga'] ?? 0;
                            $kode  = $row['kode_barang'] ?? ('BRG-' . $row['id_barang']);
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><code><?php echo htmlspecialchars($kode); ?></code></td>
                        <td><strong><?php echo htmlspecialchars($row['nama_barang']); ?></strong></td>
                        <td>Rp <?php echo number_format($harga, 0, ',', '.'); ?></td>
                        <td><strong><?php echo $stok; ?></strong> unit</td>
                        <td>
                            <?php if ($stok == 0): ?>
                                <span class="badge-stok badge-empty">Habis (0)</span>
                            <?php elseif ($stok <= 5): ?>
                                <span class="badge-stok badge-low">Menipis (<= 5)</span>
                            <?php else: ?>
                                <span class="badge-stok">Aman</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="edit_barang.php?id=<?php echo $row['id_barang']; ?>" class="btn-action">Edit / Restok</a>
                        </td>
                    </tr>
                    <?php 
                        } 
                    } else {
                    ?>
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 24px;">
                            Belum ada item barang tercatat di gudang.
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

</body>
</html>