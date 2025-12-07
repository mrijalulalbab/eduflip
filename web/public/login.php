<?php
require_once '../includes/auth.php';

$error = '';

if (isLoggedIn()) {
    header('Location: ' . getDashboardUrl($_SESSION['role']));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $result = loginUser($email, $password);
    
    if ($result['success']) {
        header('Location: ' . getDashboardUrl($result['user']['role']));
        exit;
    } else {
        $error = $result['message'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?php echo APP_NAME; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/main.css">
    <style>
        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: radial-gradient(circle at top right, rgba(32, 178, 170, 0.1), transparent 40%),
                        radial-gradient(circle at bottom left, rgba(15, 82, 186, 0.1), transparent 40%);
        }
        .auth-card {
            width: 100%;
            max-width: 450px;
            background: var(--bg-card);
            padding: 2.5rem;
            border-radius: var(--radius-lg);
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-main);
            font-weight: 500;
        }
        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: var(--radius-md);
            color: var(--text-main);
            font-family: inherit;
            transition: var(--transition);
        }
        .form-control:focus {
            outline: none;
            border-color: var(--secondary);
            background: rgba(255, 255, 255, 0.08);
        }
        .auth-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .auth-logo {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
            color: var(--text-main);
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <a href="index.php" class="auth-logo">
                    <div class="logo-icon" style="width: 32px; height: 32px;">
                        <i class="ri-book-open-fill"></i>
                    </div>
                    <?php echo APP_NAME; ?>
                </a>
                <h2>Welcome Back!</h2>
                <p class="text-muted">Sign in to continue your learning journey</p>
            </div>
            
            <?php if ($error): ?>
                <div class="alert" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); color: #fca5a5; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
                    <i class="ri-error-warning-fill"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label class="form-label" for="email">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="you@example.com" required>
                </div>
                
                <div class="form-group">
                    <div class="flex justify-between items-center" style="margin-bottom: 0.5rem;">
                        <label class="form-label" for="password" style="margin-bottom: 0;">Password</label>
                        <a href="#" class="text-secondary" style="font-size: 0.875rem;">Forgot password?</a>
                    </div>
                    <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%;">Sign In</button>
            </form>
            
            <div class="text-center" style="margin-top: 2rem;">
                <p class="text-muted">Don't have an account? <a href="register.php" class="text-secondary">Create one</a></p>
            </div>
        </div>
    </div>
</body>
</html>
