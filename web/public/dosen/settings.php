<?php
require_once '../../includes/config.php';
require_once '../../includes/auth.php';

// Auth Check - Dosen only
if (!isLoggedIn() || $_SESSION['role'] !== 'dosen') {
    header('Location: ../login.php');
    exit;
}

$base_url = '..';
$user = getUserById($_SESSION['user_id']);

// Handle form submissions
$profileMsg = null;
$passwordMsg = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        $result = updateProfile($_SESSION['user_id'], [
            'full_name' => $_POST['full_name'] ?? ''
        ]);
        $profileMsg = $result;
    }
    
    if (isset($_POST['change_password'])) {
        $result = changePassword(
            $_SESSION['user_id'],
            $_POST['current_password'] ?? '',
            $_POST['new_password'] ?? '',
            $_POST['confirm_password'] ?? ''
        );
        $passwordMsg = $result;
    }
}

// Refresh user data after update
$user = getUserById($_SESSION['user_id']);

include 'includes/header.php';
?>

<style>
    .settings-container {
        max-width: 800px;
        margin: 0 auto;
    }
    
    .settings-card {
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .settings-card h3 {
        font-size: 1.1rem;
        font-weight: 700;
        margin: 0 0 1rem 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: white;
    }
    
    .settings-card h3 i {
        color: var(--color-primary, #3b82f6);
    }
    
    .form-group {
        margin-bottom: 1rem;
    }
    
    .form-group label {
        display: block;
        font-size: 0.85rem;
        color: #9ca3af;
        margin-bottom: 0.5rem;
        font-weight: 500;
    }
    
    .form-group input {
        width: 100%;
        padding: 0.75rem 1rem;
        background: rgba(0,0,0,0.3);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 10px;
        color: white;
        font-size: 0.95rem;
        transition: all 0.2s;
    }
    
    .form-group input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
    }
    
    .form-group input:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
    
    .btn-save {
        padding: 0.75rem 1.5rem;
        background: linear-gradient(135deg, #3b82f6, #6366f1);
        border: none;
        border-radius: 10px;
        color: white;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s;
    }
    
    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(99, 102, 241, 0.3);
    }
    
    .alert {
        padding: 0.75rem 1rem;
        border-radius: 10px;
        margin-bottom: 1rem;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .alert-success {
        background: rgba(34, 197, 94, 0.15);
        border: 1px solid rgba(34, 197, 94, 0.3);
        color: #22c55e;
    }
    
    .alert-error {
        background: rgba(239, 68, 68, 0.15);
        border: 1px solid rgba(239, 68, 68, 0.3);
        color: #ef4444;
    }
    
    .profile-header {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .profile-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #8b5cf6, #a855f7);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: white;
        font-weight: 700;
        flex-shrink: 0;
    }
    
    .profile-info h2 {
        margin: 0 0 0.25rem 0;
        font-size: 1.5rem;
        color: white;
    }
    
    .profile-info p {
        margin: 0;
        color: #9ca3af;
        font-size: 0.9rem;
    }
    
    .profile-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        background: rgba(139, 92, 246, 0.15);
        border: 1px solid rgba(139, 92, 246, 0.3);
        border-radius: 20px;
        font-size: 0.75rem;
        color: #a855f7;
        font-weight: 600;
        margin-top: 0.5rem;
    }
    
    .account-meta {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        padding: 1rem;
        background: rgba(0,0,0,0.2);
        border-radius: 10px;
        margin-top: 1rem;
    }
    
    .meta-item {
        text-align: center;
    }
    
    .meta-item .label {
        font-size: 0.75rem;
        color: #9ca3af;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    
    .meta-item .value {
        font-size: 0.9rem;
        color: white;
        margin-top: 0.25rem;
    }
</style>

<div class="settings-container">
    <div style="margin-bottom: 2rem;">
        <h1 style="font-size: 1.75rem; font-weight: 700; margin: 0 0 0.5rem 0;">Account Settings</h1>
        <p style="color: #9ca3af; margin: 0;">Manage your profile and security preferences</p>
    </div>
    
    <!-- Profile Overview Card -->
    <div class="settings-card">
        <div class="profile-header">
            <div class="profile-avatar">
                <?php echo strtoupper(substr($user['full_name'], 0, 1)); ?>
            </div>
            <div class="profile-info">
                <h2><?php echo htmlspecialchars($user['full_name']); ?></h2>
                <p><?php echo htmlspecialchars($user['email']); ?></p>
                <span class="profile-badge">
                    <i class="ri-user-star-fill"></i> Lecturer
                </span>
            </div>
        </div>
        
        <div class="account-meta">
            <div class="meta-item">
                <div class="label">Account Status</div>
                <div class="value" style="color: <?php echo $user['status'] === 'active' ? '#22c55e' : '#f59e0b'; ?>;">
                    <?php echo ucfirst($user['status']); ?>
                </div>
            </div>
            <div class="meta-item">
                <div class="label">Member Since</div>
                <div class="value"><?php echo date('M d, Y', strtotime($user['created_at'])); ?></div>
            </div>
        </div>
    </div>
    
    <!-- Edit Profile Card -->
    <div class="settings-card">
        <h3><i class="ri-user-line"></i> Edit Profile</h3>
        
        <?php if ($profileMsg): ?>
            <div class="alert <?php echo $profileMsg['success'] ? 'alert-success' : 'alert-error'; ?>">
                <i class="<?php echo $profileMsg['success'] ? 'ri-checkbox-circle-fill' : 'ri-error-warning-fill'; ?>"></i>
                <?php echo $profileMsg['message']; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                <small style="color: #6b7280; font-size: 0.75rem; margin-top: 0.25rem; display: block;">Email cannot be changed</small>
            </div>
            
            <button type="submit" name="update_profile" class="btn-save">
                <i class="ri-save-line"></i> Save Changes
            </button>
        </form>
    </div>
    
    <!-- Change Password Card -->
    <div class="settings-card">
        <h3><i class="ri-lock-line"></i> Change Password</h3>
        
        <?php if ($passwordMsg): ?>
            <div class="alert <?php echo $passwordMsg['success'] ? 'alert-success' : 'alert-error'; ?>">
                <i class="<?php echo $passwordMsg['success'] ? 'ri-checkbox-circle-fill' : 'ri-error-warning-fill'; ?>"></i>
                <?php echo $passwordMsg['message']; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>Current Password</label>
                <input type="password" name="current_password" placeholder="Enter current password" required>
            </div>
            
            <div class="form-group">
                <label>New Password</label>
                <input type="password" name="new_password" placeholder="Enter new password (min 6 characters)" required>
            </div>
            
            <div class="form-group">
                <label>Confirm New Password</label>
                <input type="password" name="confirm_password" placeholder="Confirm new password" required>
            </div>
            
            <button type="submit" name="change_password" class="btn-save">
                <i class="ri-key-line"></i> Update Password
            </button>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
