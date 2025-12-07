<?php
require_once '../../includes/config.php';
require_once '../../includes/auth.php';

// Ensure user is logged in and is a lecturer
if (!isLoggedIn() || $_SESSION['role'] !== 'dosen') {
    header('Location: ../login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lecturer Dashboard - <?php echo APP_NAME; ?></title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    
    <!-- CSS -->
    <link rel="stylesheet" href="../assets/css/student.css"> <!-- Reuse student dashboard styles -->
    <link rel="stylesheet" href="../assets/css/main.css">
</head>
<body>
    <div class="dashboard-container">
        <?php include 'sidebar.php'; ?>
        
        <main class="dashboard-main">
            <header class="dashboard-header">
                <div>
                     <!-- Left side empty like Student (or breadcrumbs) -->
                </div>
                <div class="user-snippet">
                    <div class="user-info">
                        <div class="user-name"><?php echo htmlspecialchars($_SESSION['full_name'] ?? 'Lecturer'); ?></div>
                        <div class="user-role">Lecturer</div>
                    </div>
                    <div class="user-avatar">
                         <?php echo strtoupper(substr($_SESSION['full_name'] ?? 'L', 0, 1)); ?>
                    </div>
                </div>
            </header>
            
            <div class="dashboard-content">
