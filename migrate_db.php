<?php
require_once 'web/includes/config.php';

try {
    $pdo->exec("ALTER TABLE courses ADD COLUMN category ENUM('general','curriculum') DEFAULT 'general' AFTER created_by;");
    echo "Migration successful: Added category column.";
} catch (PDOException $e) {
    echo "Migration failed (or column already exists): " . $e->getMessage();
}
?>
