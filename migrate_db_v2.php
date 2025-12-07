<?php
// Set absolute path to avoid relative include issues
$configPath = __DIR__ . '/web/includes/config.php';
if (!file_exists($configPath)) {
    die("Error: Config file not found at $configPath\n");
}
require_once $configPath;

try {
    // Check if column exists first to avoid error on repeated runs
    $check = $pdo->query("SHOW COLUMNS FROM courses LIKE 'category'");
    if ($check->rowCount() > 0) {
        echo "Column 'category' already exists. Skipping.\n";
    } else {
        $pdo->exec("ALTER TABLE courses ADD COLUMN category ENUM('general','curriculum') DEFAULT 'general' AFTER created_by");
        echo "Success: Added 'category' column to courses table.\n";
    }
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage() . "\n";
    exit(1);
} catch (Exception $e) {
    echo "General Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
