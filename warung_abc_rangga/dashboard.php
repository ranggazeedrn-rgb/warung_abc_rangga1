<?php
include 'includes/cek_session.php';
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
        }

        .welcome-card {
            background: var(--card-bg);
            padding: 36px;
            border-radius: 16px;
            border: 1px solid var(--border-color);
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.02), 0 10px 15px -3px rgba(0, 0, 0, 0.03);
            margin-bottom: 24px;
        }

        .welcome-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
        }

        .welcome-card h1 {
            font-size: 24px;
            font-weight: 700;
            letter-spacing: -0.02em;
            color: var(--text-main);
        }

        .role-badge {
            display: inline-block;
            background-color: #f1f5f9;
            color: #334155;
            font-size: 12px;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 9999px;
            border: 1px solid #e2e8f0;
            text-transform: capitalize;
        }

        .welcome-card p {
            color: var(--text-muted);
            font-size: 14px;
            line-height: 1.6;
        }

        /* Grid ringkasan opsional */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 16px;
        }

        .summary-card {
            background: var(--card-bg);
            padding: 20px 24px;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .summary-card .title {
            font-size: 12px;
            font-weight: 500;
            color: var(--text-muted);
        }

        .summary-card .value {
            font-size: 15px;
            font-weight: 600;
            color: var(--text-main);
        }

        @media (max-width: 640px) {
            .navbar {
                padding: 0 16px;
            }
            .container {
                margin: 20px auto;
                padding: 0 16px;
            }
            .welcome-card {
                padding: 24px;
            }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar">
    <div class="navbar-brand">
        Warung AJOPP
        <span class="navbar-brand-badge">POS</span>
    </div>
    <div class="user-profile">
        <div class="user-info">
            <div class="user-name"><?php echo htmlspecialchars($_SESSION['nama_lengkap']); ?></div>
            <div class="user-role"><?php echo htmlspecialchars($_SESSION['role']); ?></div>
        </div>
        <a href="logout.php" class="btn-logout">Keluar</a>
    </div>
</nav>

<!-- Konten Utama -->
<div class="container">
    <div class="welcome-card">
        <div class="welcome-header">
            <h1>Selamat Datang Kembali!</h1>
            <span class="role-badge"><?php echo htmlspecialchars($_SESSION['role']); ?></span>
        </div>
        <p>
            Halo <strong><?php echo htmlspecialchars($_SESSION['nama_lengkap']); ?></strong>, Anda berhasil masuk ke panel kendali. Gunakan menu navigasi untuk mengelola data transaksi, stok barang, serta operasional harian warung.
        </p>
    </div>

    <!-- Panel Status / Navigasi Ringkas -->
    <div class="dashboard-grid">
        <div class="summary-card">
            <span class="title">Status Sistem</span>
            <span class="value" style="color: #10b981;">● Online & Aktif</span>
        </div>
        <div class="summary-card">
            <span class="title">Sesi Login</span>
            <span class="value"><?php echo date('d M Y, H:i'); ?> WIB</span>
        </div>
    </div>
</div>

</body>
</html>