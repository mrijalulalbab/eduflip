<?php 
require_once '../../includes/config.php';
require_once '../../includes/auth.php';

// Auth Check
if (!isLoggedIn()) { header('Location: ../login.php'); exit; }

$message = '';
$user_id = $_SESSION['user_id'];

// Handle Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $_POST['full_name'];
    // $email = $_POST['email']; // Email updates complex due to uniqueness check, skipping for simple demo
    
    $stmt = $pdo->prepare("UPDATE users SET full_name = ? WHERE id = ?");
    if ($stmt->execute([$full_name, $user_id])) {
        $_SESSION['full_name'] = $full_name; // Update session
        $message = "Profile updated successfully!";
    } else {
        $message = "Failed to update profile.";
    }
}

// Fetch current data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

include 'includes/header.php'; 
?>

<div class="reveal-element student-container">
    <h2 class="section-title">Account Settings ⚙️</h2>

    <?php if ($message): ?>
        <div class="alert bg-green-900 border border-green-700 text-white p-3 rounded mb-4">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <div class="card p-5" style="max-width: 600px;">
        <form method="POST">
            <div class="flex items-center gap-4 mb-5 pb-5 border-b border-white/10">
                <div class="w-16 h-16 rounded-full bg-secondary flex items-center justify-center text-3xl font-bold text-white">
                     <?php echo strtoupper(substr($user['full_name'], 0, 1)); ?>
                </div>
                <div>
                    <h3 class="mb-0"><?php echo htmlspecialchars($user['full_name']); ?></h3>
                    <p class="text-muted"><?php echo htmlspecialchars($user['email']); ?></p>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Full Name</label>
                <input type="text" name="full_name" class="form-control" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                <small class="text-muted">Contact admin to change email.</small>
            </div>

            <div class="mt-5 pt-4 border-t border-white/10">
                 <h4 class="mb-3">Change Password</h4>
                 <div class="form-group">
                    <label class="form-label">New Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current">
                </div>
            </div>

            <div class="flex justify-end mt-4">
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
