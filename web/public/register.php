<?php
require_once '../includes/auth.php';

$error = '';
$success = '';

if (isLoggedIn()) {
    header('Location: ' . getDashboardUrl($_SESSION['role']));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Basic validation and registration
    $data = [
        'full_name' => $_POST['full_name'] ?? '',
        'email' => $_POST['email'] ?? '',
        'password' => $_POST['password'] ?? '',
        'role' => $_POST['role'] ?? 'mahasiswa'
    ];
    
    // Check password match
    if ($_POST['password'] !== $_POST['confirm_password']) {
        $error = 'Passwords do not match.';
    } else {
        $result = registerUser($data);
        if ($result['success']) {
            $success = $result['message'];
        } else {
            $error = $result['message'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - <?php echo APP_NAME; ?></title>
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
            max-width: 500px;
            background: var(--bg-card);
            padding: 2.5rem;
            border-radius: var(--radius-lg);
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        .form-group { margin-bottom: 1.25rem; }
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
        .role-selector {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        .role-option {
            cursor: pointer;
            position: relative;
        }
        .role-option input {
            position: absolute;
            opacity: 0;
        }
        .role-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 1rem;
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: var(--radius-md);
            transition: var(--transition);
            text-align: center;
        }
        .role-option input:checked + .role-card {
            border-color: var(--secondary);
            background: rgba(32, 178, 170, 0.1);
        }
        .role-icon {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: var(--text-muted);
        }
        .role-option input:checked + .role-card .role-icon {
            color: var(--secondary);
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <a href="index.php" style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; font-size: 1.5rem; font-weight: 800; margin-bottom: 1rem; color: var(--text-main);">
                    <div class="logo-icon" style="width: 32px; height: 32px; background: linear-gradient(135deg, var(--secondary), var(--primary)); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; color: white;">
                        <i class="ri-book-open-fill"></i>
                    </div>
                    <?php echo APP_NAME; ?>
                </a>
                <h2 class="text-center">Create Account</h2>
                <p class="text-center text-muted">Join our learning community today</p>
            </div>
            
            <?php if ($error): ?>
                <div class="alert" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); color: #fca5a5; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
                    <i class="ri-error-warning-fill"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert" style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); color: #6ee7b7; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
                    <i class="ri-checkbox-circle-fill"></i> <?php echo $success; ?>
                    <a href="login.php" style="color: inherit; text-decoration: underline; font-weight: 600;">Login Now</a>
                </div>
            <?php else: ?>
            
            <form method="POST" action="">
                <label class="form-label">I am a:</label>
                <div class="role-selector">
                    <label class="role-option">
                        <input type="radio" name="role" value="mahasiswa" checked>
                        <div class="role-card">
                            <i class="ri-user-smile-line role-icon"></i>
                            <span style="font-weight: 600;">Student</span>
                        </div>
                    </label>
                    <label class="role-option">
                        <input type="radio" name="role" value="dosen">
                        <div class="role-card">
                            <i class="ri-presentation-line role-icon"></i>
                            <span style="font-weight: 600;">Lecturer</span>
                        </div>
                    </label>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="full_name">Full Name</label>
                    <input type="text" id="full_name" name="full_name" class="form-control" placeholder="John Doe" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="email">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="you@university.ac.id" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Min. 8 characters" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Repeat password" required>
                </div>
                
                <div class="form-group" style="margin-top: 1rem;">
                    <button type="submit" class="btn btn-primary" style="width: 100%;">Create Account</button>
                </div>
            </form>
            <?php endif; ?>
            
            <div class="text-center" style="margin-top: 2rem;">
                <p class="text-muted">Already have an account? <a href="login.php" class="text-secondary">Sign In</a></p>
            </div>
        </div>
    </div>
</body>
</html>
