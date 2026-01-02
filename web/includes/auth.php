<?php
require_once 'config.php';

/**
 * Register a new user
 */
function registerUser($data) {
    global $pdo;
    
    // Validate required fields
    if (empty($data['email']) || empty($data['password']) || empty($data['full_name'])) {
        return ['success' => false, 'message' => 'Please fill in all required fields.'];
    }
    
    // Check if email already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$data['email']]);
    if ($stmt->fetch()) {
        return ['success' => false, 'message' => 'Email is already registered.'];
    }
    
    // Hash password
    $passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);
    
    // Default role is mahasiswa (student) if not specified
    // Validate Email Domain
    $role = $data['role'] ?? 'mahasiswa';
    
    if ($role === 'dosen') {
        if (!preg_match('/@uii\.ac\.id$/', $data['email'])) {
            return ['success' => false, 'message' => 'Registration failed. Lecturers must use an @uii.ac.id email address.'];
        }
        $status = 'pending'; // Dosen accounts need approval
        $successMsg = 'Registration successful! Your Lecturer account is pending Admin approval.';
    } else {
        // Enforce student email for students
        if (!preg_match('/@students\.uii\.ac\.id$/', $data['email'])) {
            return ['success' => false, 'message' => 'Registration failed. Students must use an @students.uii.ac.id email address.'];
        }
        $status = 'active'; // Students are auto-active
        $successMsg = 'Registration successful! You can now login.';
    }
    
    try {
        $stmt = $pdo->prepare("
            INSERT INTO users (email, password_hash, full_name, role, status) 
            VALUES (?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $data['email'], 
            $passwordHash, 
            $data['full_name'], 
            $role,
            $status
        ]);
        
        return ['success' => true, 'message' => $successMsg];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
    }
}

/**
 * Authenticate user
 */
function loginUser($email, $password) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($password, $user['password_hash'])) {
        if ($user['status'] !== 'active') {
            return ['success' => false, 'message' => 'Your account is pending approval or suspended.'];
        }
        
        // Login success
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['email'] = $user['email'];
        
        // Update last login
        $update = $pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
        $update->execute([$user['id']]);
        
        return ['success' => true, 'user' => $user];
    }
    
    return ['success' => false, 'message' => 'Invalid email or password.'];
}

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Require login middleware
 */
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ../public/login.php');
        exit;
    }
}

/**
 * Redirect based on role
 */
function getDashboardUrl($role) {
    switch ($role) {
        case 'admin': return 'admin/index.php';
        case 'dosen': return 'dosen/index.php';
        case 'mahasiswa': return 'student/index.php';
        default: return 'index.php';
    }
}

/**
 * Get user by ID
 */
function getUserById($userId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT id, full_name, email, role, status, gemini_api_key, last_login, created_at FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Update user profile
 */
function updateProfile($userId, $data) {
    global $pdo;
    
    // Validate
    if (empty($data['full_name'])) {
        return ['success' => false, 'message' => 'Name is required.'];
    }
    
    try {
        $stmt = $pdo->prepare("UPDATE users SET full_name = ? WHERE id = ?");
        $stmt->execute([$data['full_name'], $userId]);
        
        // Update session
        $_SESSION['full_name'] = $data['full_name'];
        
        return ['success' => true, 'message' => 'Profile updated successfully!'];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
    }
}

/**
 * Update API Key
 */
function updateApiKey($userId, $apiKey) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("UPDATE users SET gemini_api_key = ? WHERE id = ?");
        $stmt->execute([$apiKey, $userId]);
        
        return ['success' => true, 'message' => 'API Key updated successfully!'];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
    }
}

/**
 * Change password
 */
function changePassword($userId, $currentPassword, $newPassword, $confirmPassword) {
    global $pdo;
    
    // Validate inputs
    if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
        return ['success' => false, 'message' => 'Please fill in all password fields.'];
    }
    
    if ($newPassword !== $confirmPassword) {
        return ['success' => false, 'message' => 'New passwords do not match.'];
    }
    
    if (strlen($newPassword) < 6) {
        return ['success' => false, 'message' => 'Password must be at least 6 characters.'];
    }
    
    // Verify current password
    $stmt = $pdo->prepare("SELECT password_hash FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user || !password_verify($currentPassword, $user['password_hash'])) {
        return ['success' => false, 'message' => 'Current password is incorrect.'];
    }
    
    // Update password
    try {
        $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
        $stmt->execute([$newHash, $userId]);
        
        return ['success' => true, 'message' => 'Password changed successfully!'];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
    }
}

