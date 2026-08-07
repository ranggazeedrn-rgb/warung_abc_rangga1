<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Warung AJOPP</title>
    <!-- Import Font Inter untuk kesan modern -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg-color: #f8fafc;
            --card-bg: #ffffff;
            --primary: #0f172a; /* Slate Gelap untuk kesan eksklusif & minimalis */
            --primary-hover: #1e293b;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --border-focus: #94a3b8;
            --error-bg: #fef2f2;
            --error-text: #ef4444;
            --error-border: #fecaca;
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
            background-size: 16px 16px; /* Dot pattern halus di background */
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: var(--text-main);
            padding: 20px;
        }

        .login-container {
            width: 100%;
            max-width: 380px;
        }

        .login-card {
            background: var(--card-bg);
            padding: 40px 32px;
            border-radius: 16px;
            border: 1px solid var(--border-color);
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.02), 0 10px 15px -3px rgba(0, 0, 0, 0.03);
        }

        .brand-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .brand-title {
            font-size: 22px;
            font-weight: 700;
            letter-spacing: -0.02em;
            color: var(--text-main);
            margin-bottom: 6px;
        }

        .brand-subtitle {
            font-size: 13px;
            color: var(--text-muted);
            font-weight: 400;
        }

        .alert-box {
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 24px;
            text-align: center;
        }

        .alert-box.error {
            background-color: var(--error-bg);
            color: var(--error-text);
            border: 1px solid var(--error-border);
        }

        .alert-box.success {
            background-color: var(--success-bg);
            color: var(--success-text);
            border: 1px solid var(--success-border);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 500;
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
            background-color: #ffffff;
            transition: all 0.15s ease-in-out;
            outline: none;
        }

        .form-group input::placeholder {
            color: #94a3b8;
        }

        .form-group input:focus {
            border-color: var(--border-focus);
            box-shadow: 0 0 0 3px rgba(148, 163, 184, 0.15);
        }

        .btn-login {
            width: 100%;
            padding: 11px;
            background-color: var(--primary);
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s ease-in-out;
            margin-top: 8px;
        }

        .btn-login:hover {
            background-color: var(--primary-hover);
            transform: translateY(-1px);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .auth-footer {
            margin-top: 24px;
            text-align: center;
            font-size: 13px;
            color: var(--text-muted);
        }

        .auth-footer a {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
        }

        .auth-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="login-card">
        <div class="brand-header">
            <h1 class="brand-title">Warung AJOPP</h1>
            <p class="brand-subtitle">Masukkan detail akun untuk melanjutkan</p>
        </div>

        <!-- Menampilkan Pesan Error Jika Ada -->
        <?php if (isset($_SESSION['pesan_error'])): ?>
            <div class="alert-box error">
                <?php 
                echo $_SESSION['pesan_error']; 
                unset($_SESSION['pesan_error']);
                ?>
            </div>
        <?php endif; ?>

        <!-- Menampilkan Pesan Sukses Jika Ada (Setel Registrasi) -->
        <?php if (isset($_SESSION['pesan_sukses'])): ?>
            <div class="alert-box success">
                <?php 
                echo $_SESSION['pesan_sukses']; 
                unset($_SESSION['pesan_sukses']);
                ?>
            </div>
        <?php endif; ?>

        <form action="proses_login.php" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Masukkan username" required autocomplete="off">
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn-login">Masuk</button>
        </form>

        <div class="auth-footer">
            Belum memiliki akun? <a href="register.php">Buat Akun Baru</a>
        </div>
    </div>
</div>

</body>
</html>