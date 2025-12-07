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
    $role = $data['role'] ?? 'mahasiswa';
    $status = 'active'; // Auto-activate for demo purposes, PRD says pending/active config
    
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
        
        return ['success' => true, 'message' => 'Registration successful! You can now login.'];
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
?>
