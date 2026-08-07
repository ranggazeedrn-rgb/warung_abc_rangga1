<?php
// riwayat_transaksi.php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Menyesuaikan query: mengambil transaksi lengkap
$sql = "SELECT t.id_transaksi, t.tanggal, t.total_bayar, u.nama_lengkap AS nama_kasir
        FROM tbl_transaksi t
        JOIN tbl_user u ON t.id_kasir = u.id_user
        ORDER BY t.tanggal DESC";
$hasil = mysqli_query($koneksi, $sql);

// Hitung ringkasan statistik laporan
$total_omset = 0;
$total_transaksi = 0;

if ($hasil) {
    $total_transaksi = mysqli_num_rows($hasil);
    $data_rows = [];
    while ($row = mysqli_fetch_assoc($hasil)) {
        $total_omset += $row['total_bayar'];
        $data_rows[] = $row;
    }
}
$rata_rata = $total_transaksi > 0 ? ($total_omset / $total_transaksi) : 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi - Warung AJOPP</title>
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
            --badge-bg: #dbeafe;
            --badge-text: #1e40af;
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
            background: var(--badge-bg);
            color: var(--badge-text);
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

        /* Kop Surat/Header Laporan Khusus Cetak */
        .print-header {
            display: none;
            margin-bottom: 24px;
            border-bottom: 2px solid #000;
            padding-bottom: 12px;
        }

        .print-header h1 {
            font-size: 20px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .print-header p {
            font-size: 12px;
            color: #555;
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

        .card-subtitle {
            font-size: 13px;
            color: var(--text-muted);
            margin-top: 2px;
        }

        /* Ringkasan Laporan Grid */
        .summary-box {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            padding: 20px 24px;
            background-color: #fafafa;
            border-bottom: 1px solid var(--border-color);
        }

        .summary-item {
            display: flex;
            flex-direction: column;
        }

        .summary-item .label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            color: var(--text-muted);
            letter-spacing: 0.05em;
        }

        .summary-item .value {
            font-size: 16px;
            font-weight: 700;
            color: var(--text-main);
            margin-top: 2px;
        }

        /* Single Print Button Utama */
        .btn-print-main {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
            background-color: var(--primary);
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: opacity 0.2s;
        }

        .btn-print-main:hover {
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
            padding: 14px 20px;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-main);
        }

        tr:last-child td {
            border-bottom: none;
        }

        .trx-id {
            font-family: monospace;
            font-weight: 600;
            background-color: var(--primary-light);
            padding: 3px 8px;
            border-radius: 6px;
            color: var(--primary);
            font-size: 12px;
        }

        .total-amount {
            font-weight: 700;
            color: var(--text-main);
        }

        /* Footer Tanda Tangan Khusus Mode Cetak */
        .print-footer {
            display: none;
            margin-top: 40px;
            justify-content: flex-end;
        }

        .signature-box {
            text-align: center;
            width: 200px;
        }

        .signature-space {
            height: 60px;
        }

        /* =================================================== */
        /* STYLES KHUSUS MODE PRINT (CETAK KERTAS / PDF)      */
        /* =================================================== */
        @media print {
            body {
                background: white !important;
                color: #000 !important;
            }
            .navbar, .btn-back, .btn-print-main {
                display: none !important;
            }
            .container {
                max-width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            .card-table {
                border: none !important;
                box-shadow: none !important;
            }
            .print-header {
                display: block !important;
            }
            .print-footer {
                display: flex !important;
            }
            .summary-box {
                background-color: #f1f1f1 !important;
                border: 1px solid #ccc !important;
                margin-bottom: 20px;
            }
            th {
                background-color: #eee !important;
                color: #000 !important;
                border-bottom: 2px solid #000 !important;
            }
            td {
                border-bottom: 1px solid #ddd !important;
            }
            .trx-id {
                background: none !important;
                padding: 0 !important;
            }
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
        <span class="navbar-brand-badge">Laporan</span>
    </div>
    <a href="dashboard.php" class="btn-back">← Kembali ke Dashboard</a>
</nav>

<!-- Main Container -->
<div class="container">

    <!-- Header Laporan Khusus Hasil Print -->
    <div class="print-header">
        <h1>WARUNG AJOPP</h1>
        <p>Laporan Detail Transaksi Penjualan</p>
        <p>Dicetak Pada: <?php echo date('d F Y - H:i'); ?> WIB</p>
    </div>

    <div class="card-table">
        <div class="card-header">
            <div>
                <div class="card-title">Riwayat Transaksi Penjualan</div>
                <div class="card-subtitle">Rekapan seluruh aktivitas penjualan toko.</div>
            </div>
            <!-- Satu Tombol Utama untuk Cetak Laporan -->
            <button onclick="window.print()" class="btn-print-main">
                🖨️ Cetak Laporan Detail
            </button>
        </div>

        <!-- Ringkasan Statistik Laporan -->
        <div class="summary-box">
            <div class="summary-item">
                <span class="label">Total Transaksi</span>
                <span class="value"><?php echo number_format($total_transaksi); ?> Transaksi</span>
            </div>
            <div class="summary-item">
                <span class="label">Total Omset</span>
                <span class="value">Rp <?php echo number_format($total_omset, 0, ',', '.'); ?></span>
            </div>
            <div class="summary-item">
                <span class="label">Rata-rata Transaksi</span>
                <span class="value">Rp <?php echo number_format($rata_rata, 0, ',', '.'); ?></span>
            </div>
        </div>

        <!-- Tabel Detail Transaksi -->
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th style="width: 150px;">ID Transaksi</th>
                        <th>Tanggal & Waktu</th>
                        <th>Kasir</th>
                        <th style="text-align: right;">Total Bayar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if (!empty($data_rows)) {
                        $no = 1;
                        foreach ($data_rows as $row) { 
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><span class="trx-id">#<?php echo htmlspecialchars($row['id_transaksi']); ?></span></td>
                        <td><?php echo date('d M Y - H:i', strtotime($row['tanggal'])); ?></td>
                        <td><strong><?php echo htmlspecialchars($row['nama_kasir']); ?></strong></td>
                        <td style="text-align: right;" class="total-amount">
                            Rp <?php echo number_format($row['total_bayar'], 0, ',', '.'); ?>
                        </td>
                    </tr>
                    <?php 
                        } 
                    } else {
                    ?>
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 24px;">
                            Belum ada data transaksi recorded.
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tanda Tangan Laporan Khusus Mode Print -->
    <div class="print-footer">
        <div class="signature-box">
            <p>Penanggung Jawab,</p>
            <div class="signature-space"></div>
            <p><strong>( <?php echo htmlspecialchars($_SESSION['nama_lengkap'] ?? 'Pemilik Toko'); ?> )</strong></p>
        </div>
    </div>

</div>

</body>
</html>