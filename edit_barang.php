<?php
// edit_barang.php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$id = $_GET['id'];
$sql = "SELECT * FROM tbl_barang WHERE id_barang = '$id'";
$hasil = mysqli_query($koneksi, $sql);
$data = mysqli_fetch_assoc($hasil);

// Jika data tidak ditemukan, kembalikan ke gudang / data_barang
if (!$data) {
    header("Location: gudang.php");
    exit;
}

// Tentukan arah tombol 'Kembali' sesuai role
$redirect_back = ($_SESSION['role'] === 'gudang') ? 'gudang.php' : 'data_barang.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Barang - Warung ABC</title>
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
            --primary-hover: #1e293b;
            --primary-light: #f1f5f9;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --border-focus: #94a3b8;
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

        /* Main Form Container */
        .container {
            max-width: 600px;
            margin: 40px auto;
            padding: 0 24px;
        }

        .card-form {
            background: var(--card-bg);
            border-radius: 16px;
            border: 1px solid var(--border-color);
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.02), 0 10px 15px -3px rgba(0, 0, 0, 0.03);
            overflow: hidden;
            padding: 32px;
        }

        .form-header {
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--border-color);
        }

        .form-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--text-main);
        }

        .form-subtitle {
            font-size: 13px;
            color: var(--text-muted);
            margin-top: 4px;
        }

        /* Group Elements */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group.full-width {
            grid-column: span 2;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--text-main);
        }

        .form-group input {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 14px;
            color: var(--text-main);
            outline: none;
            transition: all 0.15s ease-in-out;
        }

        .form-group input:focus {
            border-color: var(--border-focus);
            box-shadow: 0 0 0 3px rgba(148, 163, 184, 0.15);
        }

        /* Action Buttons */
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 8px;
            padding-top: 16px;
            border-top: 1px solid var(--border-color);
        }

        .btn-submit {
            padding: 10px 20px;
            background-color: var(--primary);
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s ease-in-out;
        }

        .btn-submit:hover {
            background-color: var(--primary-hover);
        }

        .btn-cancel {
            padding: 10px 20px;
            background-color: transparent;
            border: 1px solid var(--border-color);
            color: var(--text-main);
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.15s ease-in-out;
            text-align: center;
        }

        .btn-cancel:hover {
            background-color: var(--primary-light);
        }

        @media (max-width: 640px) {
            .form-grid { grid-template-columns: 1fr; }
            .form-group.full-width { grid-column: span 1; }
            .navbar { padding: 0 16px; }
            .container { margin: 20px auto; padding: 0 16px; }
        }
    </style>
</head>
<body>

<!-- Navbar Navigasi -->
<nav class="navbar">
    <div class="navbar-brand">
        Warung ABC
        <span class="navbar-brand-badge">Edit Data</span>
    </div>
    <a href="<?php echo $redirect_back; ?>" class="btn-back">← Batal</a>
</nav>

<!-- Container Form -->
<div class="container">
    <div class="card-form">
        <div class="form-header">
            <h1 class="form-title">Edit & Restok Barang</h1>
            <p class="form-subtitle">Perbarui detail atau stok informasi barang di bawah ini.</p>
        </div>

        <form action="proses_edit_barang.php" method="POST">
            <!-- ID Barang (Hidden) -->
            <input type="hidden" name="id_barang" value="<?php echo htmlspecialchars($data['id_barang']); ?>">

            <div class="form-grid">
                <!-- Kode Barang -->
                <div class="form-group">
                    <label for="kode_barang">Kode Barang</label>
                    <input type="text" id="kode_barang" name="kode_barang" 
                           value="<?php echo htmlspecialchars($data['kode_barang']); ?>" required>
                </div>

                <!-- Nama Barang -->
                <div class="form-group">
                    <label for="nama_barang">Nama Barang</label>
                    <input type="text" id="nama_barang" name="nama_barang" 
                           value="<?php echo htmlspecialchars($data['nama_barang']); ?>" required>
                </div>

                <!-- Harga Satuan -->
                <div class="form-group">
                    <label for="harga_satuan">Harga Satuan (Rp)</label>
                    <input type="number" id="harga_satuan" name="harga_satuan" step="0.01" 
                           value="<?php echo htmlspecialchars($data['harga_satuan']); ?>" required>
                </div>

                <!-- Jumlah Stok -->
                <div class="form-group">
                    <label for="stok">Jumlah Stok</label>
                    <input type="number" id="stok" name="stok" 
                           value="<?php echo htmlspecialchars($data['stok']); ?>" required>
                </div>

                <!-- Tanggal Kadaluarsa -->
                <div class="form-group full-width">
                    <label for="tanggal_kadaluarsa">Tanggal Kadaluarsa (Opsional)</label>
                    <input type="date" id="tanggal_kadaluarsa" name="tanggal_kadaluarsa" 
                           value="<?php echo htmlspecialchars($data['tanggal_kadaluarsa']); ?>">
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="form-actions">
                <a href="<?php echo $redirect_back; ?>" class="btn-cancel">Batal</a>
                <button type="submit" class="btn-submit">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>