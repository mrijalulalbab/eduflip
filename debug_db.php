<?php
require_once 'web/includes/config.php';
try {
    $stmt = $pdo->query("SELECT count(*) FROM courses");
    echo "Courses count: " . $stmt->fetchColumn() . "\n";
    
    $stmt = $pdo->query("SELECT * FROM courses");
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
    
    $stmt = $pdo->query("SELECT count(*) FROM users");
    echo "Users count: " . $stmt->fetchColumn() . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
