<?php
// data_barang.php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Ambil seluruh data dari tbl_barang
$sql = "SELECT * FROM tbl_barang ORDER BY id_barang DESC";
$hasil = mysqli_query($koneksi, $sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Barang - Warung AJOPP</title>
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

        .btn-back {
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

        .btn-back:hover {
            background-color: var(--primary-light);
        }

        /* Container Main */
        .container {
            max-width: 1000px;
            margin: 32px auto;
            padding: 0 24px;
        }

        /* Card Frame Table */
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

        .card-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-main);
        }

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

        .btn-add:hover {
            opacity: 0.9;
        }

        /* Modern Table Styling */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 13px;
        }

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

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover td {
            background-color: #fdfdfd;
        }

        /* Badges & Action Buttons */
        .badge-stok {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 12px;
            background-color: var(--primary-light);
            color: var(--text-main);
        }

        .badge-low {
            background-color: var(--warning-bg);
            color: var(--warning-text);
        }

        .action-group {
            display: flex;
            gap: 8px;
        }

        .btn-edit {
            padding: 6px 12px;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            color: var(--text-main);
            text-decoration: none;
            font-size: 12px;
            font-weight: 500;
            transition: background-color 0.15s;
        }

        .btn-edit:hover {
            background-color: var(--primary-light);
        }

        .btn-delete {
            padding: 6px 12px;
            border: 1px solid var(--danger-border);
            border-radius: 6px;
            color: var(--danger-text);
            text-decoration: none;
            font-size: 12px;
            font-weight: 500;
            background-color: var(--danger-bg);
            transition: opacity 0.15s;
        }

        .btn-delete:hover {
            opacity: 0.8;
        }

        @media (max-width: 640px) {
            .navbar { padding: 0 16px; }
            .container { margin: 16px auto; padding: 0 16px; }
        }
    </style>
</head>
<body>

<!-- Navbar Navigasi -->
<nav class="navbar">
    <div class="navbar-brand">
        Warung AJOPP
        <span class="navbar-brand-badge">Data Barang</span>
    </div>
    <a href="dashboard.php" class="btn-back">← Kembali ke Dashboard</a>
</nav>

<!-- Main Container -->
<div class="container">
    <div class="card-table">
        <div class="card-header">
            <div class="card-title">Katalog & Stok Barang</div>
            <a href="tambah_barang.php" class="btn-add">+ Tambah Barang Baru</a>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Nama Barang</th>
                        <th>Harga Jual</th>
                        <th>Stok</th>
                        <th style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    if (mysqli_num_rows($hasil) > 0) {
                        while ($row = mysqli_fetch_assoc($hasil)) { 
                            // Deteksi kolom harga secara otomatis agar tidak error lagi
                            $harga = $row['harga_jual'] ?? $row['harga'] ?? 0;
                            $stok  = $row['stok'] ?? 0;
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><strong><?php echo htmlspecialchars($row['nama_barang']); ?></strong></td>
                        <td>Rp <?php echo number_format($harga, 0, ',', '.'); ?></td>
                        <td>
                            <span class="badge-stok <?php echo ($stok <= 5) ? 'badge-low' : ''; ?>">
                                <?php echo $stok; ?> unit
                            </span>
                        </td>
                        <td>
                            <div class="action-group">
                                <a href="edit_barang.php?id=<?php echo $row['id_barang']; ?>" class="btn-edit">Edit</a>
                                <a href="hapus_barang.php?id=<?php echo $row['id_barang']; ?>" class="btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus barang ini?')">Hapus</a>
                            </div>
                        </td>
                    </tr>
                    <?php 
                        } 
                    } else {
                    ?>
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 24px;">Belum ada data barang tersedia.</td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>