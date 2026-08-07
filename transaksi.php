<?php
// transaksi.php
session_start();
include 'includes/cek_session.php';
include 'config/koneksi.php';

if (!isset($_SESSION['keranjang'])) {
    $_SESSION['keranjang'] = array();
}

$daftar_barang = mysqli_query($koneksi, "SELECT * FROM tbl_barang WHERE stok > 0");
$total = 0;
foreach ($_SESSION['keranjang'] as $item) {
    $total += $item['subtotal'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi Penjualan - Warung AJOPP</title>
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
            --success-bg: #ecfdf5;
            --success-text: #059669;
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
            background: var(--primary-light);
            color: var(--text-muted);
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

        .btn-nav {
            padding: 7px 14px;
            background-color: transparent;
            border: 1px solid var(--border-color);
            color: var(--text-main);
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.15s ease-in-out;
            display: inline-block;
        }

        .btn-nav:hover {
            background-color: var(--primary-light);
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

        /* Main Content Container */
        .container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 24px;
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        /* Card Component */
        .main-card {
            background: var(--card-bg);
            padding: 32px;
            border-radius: 16px;
            border: 1px solid var(--border-color);
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.02), 0 10px 15px -3px rgba(0, 0, 0, 0.03);
        }

        .card-header {
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-header h1 {
            font-size: 20px;
            font-weight: 700;
            letter-spacing: -0.02em;
            color: var(--text-main);
        }

        .section-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-main);
            margin-bottom: 12px;
        }

        /* Alert styling */
        .alert-error {
            background-color: var(--danger-bg);
            color: var(--danger-text);
            border: 1px solid var(--danger-border);
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 20px;
        }

        /* Form Controls */
        .add-form-wrapper {
            background-color: var(--primary-light);
            padding: 20px;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            margin-bottom: 28px;
            display: grid;
            grid-template-columns: 2fr 1fr auto;
            gap: 16px;
            align-items: flex-end;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .form-group label {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-main);
        }

        .form-control {
            width: 100%;
            padding: 9px 12px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 13px;
            color: var(--text-main);
            background-color: #ffffff;
            outline: none;
            transition: all 0.15s ease-in-out;
        }

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2064748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 16px;
            padding-right: 32px;
            cursor: pointer;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(15, 23, 42, 0.05);
        }

        /* Buttons */
        .btn-primary {
            padding: 9px 18px;
            background-color: var(--primary);
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s ease;
            white-space: nowrap;
        }

        .btn-primary:hover {
            opacity: 0.9;
        }

        .btn-success {
            padding: 10px 20px;
            background-color: #10b981;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .btn-success:hover {
            background-color: #059669;
        }

        .btn-danger-link {
            color: var(--danger-text);
            text-decoration: none;
            font-weight: 500;
            font-size: 12px;
            padding: 4px 8px;
            border-radius: 4px;
            transition: background-color 0.15s ease;
        }

        .btn-danger-link:hover {
            background-color: var(--danger-bg);
        }

        /* Table Styling */
        .table-container {
            overflow-x: auto;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            margin-bottom: 24px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        th {
            background-color: var(--primary-light);
            color: var(--text-muted);
            font-weight: 600;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            padding: 12px 16px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }

        td {
            padding: 12px 16px;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-main);
        }

        tr:last-child td {
            border-bottom: none;
        }

        .total-row {
            background-color: #fafafa;
            font-weight: 700;
        }

        /* Footer Actions */
        .action-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        @media (max-width: 640px) {
            .navbar { padding: 0 16px; }
            .container { margin: 20px auto; padding: 0 16px; }
            .main-card { padding: 20px; }
            .add-form-wrapper { grid-template-columns: 1fr; }
            .action-footer { flex-direction: column-reverse; gap: 12px; align-items: stretch; }
            .action-footer form, .btn-success { width: 100%; text-align: center; }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar">
    <div class="navbar-brand">
        Warung AJOPP
        <span class="navbar-brand-badge">Transaksi</span>
    </div>
    <div class="user-profile">
        <?php if (isset($_SESSION['nama_lengkap'])): ?>
            <div class="user-info">
                <div class="user-name"><?php echo htmlspecialchars($_SESSION['nama_lengkap']); ?></div>
                <div class="user-role"><?php echo htmlspecialchars($_SESSION['role']); ?></div>
            </div>
        <?php endif; ?>
        <a href="dashboard.php" class="btn-nav">Dashboard</a>
        <a href="logout.php" class="btn-logout">Keluar</a>
    </div>
</nav>

<!-- Main Container -->
<div class="container">
    <div class="main-card">
        <div class="card-header">
            <h1>Transaksi Penjualan</h1>
            <span style="font-size: 12px; color: var(--text-muted); font-weight: 500;"><?php echo date('d M Y'); ?></span>
        </div>

        <!-- Pesan Error -->
        <?php if (isset($_SESSION['pesan_error'])) { ?>
            <div class="alert-error">
                <?php echo htmlspecialchars($_SESSION['pesan_error']); ?>
            </div>
            <?php unset($_SESSION['pesan_error']); ?>
        <?php } ?>

        <!-- Form Tambah Barang -->
        <div class="section-title">Pilih Barang</div>
        <form action="proses_tambah_keranjang.php" method="POST">
            <div class="add-form-wrapper">
                <div class="form-group">
                    <label for="id_barang">Nama Barang</label>
                    <select name="id_barang" id="id_barang" class="form-control" required>
                        <option value="" disabled selected>-- Pilih Barang --</option>
                        <?php while ($b = mysqli_fetch_assoc($daftar_barang)) { ?>
                            <option value="<?php echo $b['id_barang']; ?>">
                                <?php echo htmlspecialchars($b['nama_barang']) . ' (Stok: ' . $b['stok'] . ')'; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="jumlah">Jumlah</label>
                    <input type="number" id="jumlah" name="jumlah" class="form-control" min="1" value="1" required>
                </div>

                <div>
                    <input type="submit" class="btn-primary" value="Tambah ke Keranjang">
                </div>
            </div>
        </form>

        <!-- Tabel Keranjang -->
        <div class="section-title">Keranjang Belanja</div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Nama Barang</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($_SESSION['keranjang'])): ?>
                        <?php foreach ($_SESSION['keranjang'] as $id_barang => $item) { ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($item['nama_barang']); ?></strong></td>
                            <td>Rp <?php echo number_format($item['harga'], 0, ',', '.'); ?></td>
                            <td><?php echo $item['jumlah']; ?></td>
                            <td>Rp <?php echo number_format($item['subtotal'], 0, ',', '.'); ?></td>
                            <td style="text-align: center;">
                                <a href="hapus_keranjang.php?id=<?php echo $id_barang; ?>" class="btn-danger-link" onclick="return confirm('Hapus barang ini?')">Hapus</a>
                            </td>
                        </tr>
                        <?php } ?>
                        <tr class="total-row">
                            <td colspan="3" style="text-align: right;">Total</td>
                            <td colspan="2" style="color: var(--primary); font-size: 15px;">Rp <?php echo number_format($total, 0, ',', '.'); ?></td>
                        </tr>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 20px;">
                                Keranjang masih kosong.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Action Footer -->
        <div class="action-footer">
            <a href="dashboard.php" class="btn-nav">&larr; Kembali ke Dashboard</a>

            <form action="proses_simpan_transaksi.php" method="POST">
                <input type="submit" class="btn-success" value="Simpan Transaksi" <?php echo empty($_SESSION['keranjang']) ? 'disabled style="opacity: 0.5; cursor: not-allowed;"' : ''; ?>>
            </form>
        </div>

    </div>
</div>

</body>
</html>