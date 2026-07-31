<?php
include 'includes/cek_session.php';

// Proteksi Tambahan: Pastikan hanya role 'kasir' yang bisa mengakses halaman ini
if ($_SESSION['role'] !== 'kasir') {
    // Jika admin atau gudang mencoba akses, kembalikan ke dashboard
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Kasir - Warung ABC</title>
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
            --success-bg: #f0fdf4;
            --success-text: #16a34a;
            --success-border: #bbf7d0;
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

        /* Layout Grid */
        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 24px;
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 24px;
        }

        .card {
            background: var(--card-bg);
            padding: 24px;
            border-radius: 16px;
            border: 1px solid var(--border-color);
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.02), 0 10px 15px -3px rgba(0, 0, 0, 0.03);
        }

        .card-header {
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--text-main);
        }

        /* Form & Table Inputs */
        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 6px;
            color: var(--text-main);
        }

        .form-group input, .form-group select {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 14px;
            outline: none;
        }

        .btn-primary {
            width: 100%;
            padding: 12px;
            background-color: var(--primary);
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s ease-in-out;
        }

        .btn-primary:hover {
            opacity: 0.9;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }

        th {
            background-color: var(--primary-light);
            color: var(--text-muted);
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
        }

        .total-box {
            background-color: var(--primary-light);
            padding: 16px;
            border-radius: 8px;
            margin-top: 20px;
            text-align: right;
        }

        .total-label {
            font-size: 12px;
            color: var(--text-muted);
            text-transform: uppercase;
            font-weight: 600;
        }

        .total-amount {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-main);
        }

        @media (max-width: 900px) {
            .container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<!-- Navbar Navbar -->
<nav class="navbar">
    <div class="navbar-brand">
        Warung ABC
        <span class="navbar-brand-badge">Area Kasir</span>
    </div>
    <div class="user-profile">
        <div class="user-info">
            <div class="user-name"><?php echo htmlspecialchars($_SESSION['nama_lengkap']); ?></div>
            <div class="user-role"><?php echo htmlspecialchars($_SESSION['role']); ?></div>
        </div>
        <a href="logout.php" class="btn-logout">Keluar</a>
    </div>
</nav>

<!-- Container Utama -->
<div class="container">

    <!-- Kolom Kiri: Form Transaksi & Keranjang -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Transaksi Kasir</h2>
            <span style="font-size: 12px; color: var(--text-muted);"><?php echo date('d M Y'); ?></span>
        </div>

        <!-- Form Tambah Barang / Scan Barcode -->
        <form action="#" method="POST" style="margin-bottom: 24px;">
            <div style="display: flex; gap: 12px;">
                <div class="form-group" style="flex: 2; margin: 0;">
                    <label for="kode_barang">Pilih / Scan Barang</label>
                    <input type="text" id="kode_barang" placeholder="Masukkan nama atau barcode barang..." required>
                </div>
                <div class="form-group" style="flex: 1; margin: 0;">
                    <label for="jumlah">Jumlah</label>
                    <input type="number" id="jumlah" value="1" min="1" required>
                </div>
                <div style="align-self: flex-end;">
                    <button type="submit" class="btn-primary" style="padding: 10px 20px;">Tambah</button>
                </div>
            </div>
        </form>

        <!-- Daftar Belanjaan (Daftar Item) -->
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Harga</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <!-- Contoh Data Kosong / Keranjang -->
                <tr>
                    <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 24px;">
                        Belum ada item yang ditambahkan ke keranjang.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Kolom Kanan: Ringkasan & Pembayaran -->
    <div class="card" style="height: fit-content;">
        <div class="card-header">
            <h2 class="card-title">Pembayaran</h2>
        </div>

        <div class="total-box">
            <div class="total-label">Total Tagihan</div>
            <div class="total-amount">Rp 0</div>
        </div>

        <form action="#" method="POST" style="margin-top: 20px;">
            <div class="form-group">
                <label for="bayar">Uang Dibayar (Rp)</label>
                <input type="number" id="bayar" placeholder="0" required>
            </div>

            <div class="form-group">
                <label for="kembali">Kembalian (Rp)</label>
                <input type="number" id="kembali" placeholder="0" readonly style="background-color: var(--primary-light);">
            </div>

            <button type="submit" class="btn-primary" style="margin-top: 10px;">Selesaikan Transaksi</button>
        </form>
    </div>

</div>

</body>
</html>