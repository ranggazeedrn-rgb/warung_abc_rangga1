<?php 
// tambah_barang.php
include 'includes/cek_session.php'; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Barang - Warung AJOPP</title>
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
            --input-focus: #2563eb;
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

        /* Main Container */
        .container {
            max-width: 600px;
            margin: 40px auto;
            padding: 0 24px;
        }

        /* Card Form Container */
        .card-form {
            background: var(--card-bg);
            border-radius: 16px;
            border: 1px solid var(--border-color);
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.02), 0 10px 15px -3px rgba(0, 0, 0, 0.03);
            padding: 32px;
        }

        .card-header {
            margin-bottom: 24px;
        }

        .card-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--text-main);
            letter-spacing: -0.02em;
        }

        .card-subtitle {
            font-size: 13px;
            color: var(--text-muted);
            margin-top: 4px;
        }

        /* Form Controls */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-main);
            margin-bottom: 6px;
        }

        .form-control {
            width: 100%;
            padding: 10px 14px;
            font-size: 14px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background-color: #ffffff;
            color: var(--text-main);
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(15, 23, 42, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        /* Submit Button */
        .btn-submit {
            width: 100%;
            padding: 12px;
            background-color: var(--primary);
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: opacity 0.2s ease;
            margin-top: 8px;
        }

        .btn-submit:hover {
            opacity: 0.9;
        }

        @media (max-width: 640px) {
            .navbar { padding: 0 16px; }
            .container { margin: 20px auto; padding: 0 16px; }
            .card-form { padding: 24px; }
            .form-row { grid-template-columns: 1fr; gap: 0; }
        }
    </style>
</head>
<body>

<!-- Navbar Navigasi -->
<nav class="navbar">
    <div class="navbar-brand">
        Warung AJOPP
        <span class="navbar-brand-badge">Tambah Barang</span>
    </div>
    <a href="data_barang.php" class="btn-back">← Kembali</a>
</nav>

<!-- Main Container -->
<div class="container">
    <div class="card-form">
        <div class="card-header">
            <h1 class="card-title">Tambah Barang Baru</h1>
            <p class="card-subtitle">Isi formulir berikut untuk memasukkan produk baru ke katalog database.</p>
        </div>

        <form action="proses_tambah_barang.php" method="POST">
            <!-- Kode Barang -->
            <div class="form-group">
                <label class="form-label">Kode Barang</label>
                <input type="text" name="kode_barang" class="form-control" placeholder="Contoh: BRG-001" required>
            </div>

            <!-- Nama Barang -->
            <div class="form-group">
                <label class="form-label">Nama Barang</label>
                <input type="text" name="nama_barang" class="form-control" placeholder="Masukkan nama produk..." required>
            </div>

            <!-- Harga & Stok (Grid Row) -->
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Harga Satuan (Rp)</label>
                    <input type="number" name="harga_satuan" step="0.01" class="form-control" placeholder="0" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Stok Awal</label>
                    <input type="number" name="stok" class="form-control" placeholder="0" required>
                </div>
            </div>

            <!-- Tanggal Kadaluarsa -->
            <div class="form-group">
                <label class="form-label">Tanggal Kadaluarsa (Opsional)</label>
                <input type="date" name="tanggal_kadaluarsa" class="form-control">
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn-submit">Simpan Data Barang</button>
        </form>
    </div>
</div>

</body>
</html>