<?php
require_once '../includes/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Pending - <?php echo APP_NAME; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/main.css">
    <style>
        .pending-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: radial-gradient(circle at top right, rgba(251, 191, 36, 0.1), transparent 40%),
                        radial-gradient(circle at bottom left, rgba(15, 82, 186, 0.1), transparent 40%);
        }
        .pending-card {
            width: 100%;
            max-width: 500px;
            background: var(--bg-card);
            padding: 3rem;
            border-radius: var(--radius-lg);
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            text-align: center;
        }
        .pending-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            background: linear-gradient(135deg, rgba(251, 191, 36, 0.2), rgba(251, 191, 36, 0.1));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: #fbbf24;
        }
        .pending-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 0.75rem;
        }
        .pending-message {
            color: var(--text-muted);
            line-height: 1.6;
            margin-bottom: 2rem;
        }
        .pending-steps {
            background: rgba(255, 255, 255, 0.03);
            border-radius: var(--radius-md);
            padding: 1.5rem;
            text-align: left;
            margin-bottom: 2rem;
        }
        .pending-steps h4 {
            color: var(--text-main);
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }
        .pending-steps ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .pending-steps li {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-bottom: 0.75rem;
        }
        .pending-steps li:last-child {
            margin-bottom: 0;
        }
        .pending-steps li i {
            color: var(--secondary);
            margin-top: 0.15rem;
        }
    </style>
</head>
<body>
    <div class="pending-container">
        <div class="pending-card">
            <div class="pending-icon">
                <i class="ri-time-line"></i>
            </div>
            <h1 class="pending-title">Akun Menunggu Verifikasi</h1>
            <p class="pending-message">
                Terima kasih telah mendaftar sebagai <strong>Dosen</strong> di <?php echo APP_NAME; ?>. 
                Akun Anda saat ini sedang dalam proses verifikasi oleh Admin.
            </p>
            
            <div class="pending-steps">
                <h4><i class="ri-information-line"></i> Apa yang terjadi selanjutnya?</h4>
                <ul>
                    <li>
                        <i class="ri-checkbox-circle-line"></i>
                        <span>Tim Admin akan memverifikasi identitas Anda sebagai dosen UII.</span>
                    </li>
                    <li>
                        <i class="ri-checkbox-circle-line"></i>
                        <span>Setelah diverifikasi, status akun akan diubah menjadi <strong>Aktif</strong>.</span>
                    </li>
                    <li>
                        <i class="ri-checkbox-circle-line"></i>
                        <span>Anda akan dapat login dan mengakses dashboard dosen.</span>
                    </li>
                </ul>
            </div>
            
            <p class="text-muted" style="font-size: 0.85rem; margin-bottom: 1.5rem;">
                Proses verifikasi biasanya memakan waktu 1-2 hari kerja.
            </p>
            
            <a href="index.php" class="btn btn-secondary" style="margin-right: 0.5rem;">
                <i class="ri-home-line"></i> Kembali ke Beranda
            </a>
            <a href="login.php" class="btn btn-primary">
                <i class="ri-login-box-line"></i> Coba Login Lagi
            </a>
        </div>
    </div>
</body>
</html>
