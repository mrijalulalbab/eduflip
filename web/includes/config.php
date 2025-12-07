<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', ''); // Default XAMPP password is empty
define('DB_NAME', 'eduflip');

// Application Configuration
define('APP_NAME', 'EduFlip');
define('APP_URL', 'http://localhost/eduflipp/web/public');

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("set names utf8mb4");
} catch(PDOException $e) {
    die("ERROR: Could not connect. " . $e->getMessage());
}

// Start Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
