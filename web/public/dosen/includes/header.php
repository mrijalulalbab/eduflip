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
    <link rel="stylesheet" href="../assets/css/student.css?v=<?php echo time(); ?>"> <!-- Reuse student dashboard styles -->
    <link rel="stylesheet" href="../assets/css/main.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="dashboard-container">
        <?php include 'sidebar.php'; ?>
        
        <main class="dashboard-main">
            <header class="dashboard-header">
                <!-- Mobile Sidebar Toggle -->
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <button class="sidebar-toggle" onclick="toggleSidebar()">
                        <i class="ri-menu-2-line"></i>
                    </button>
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

            <!-- Sidebar Overlay for Mobile -->
            <div id="sidebar-overlay" class="sidebar-overlay" onclick="toggleSidebar()"></div>

            <script>
            function toggleSidebar() {
                // Select sidebar - Dosen dashboard might use same class or check sidebar.php
                const sidebar = document.querySelector('.dashboard-sidebar');
                if(sidebar) sidebar.classList.toggle('active');
                
                const overlay = document.getElementById('sidebar-overlay');
                if(overlay) overlay.classList.toggle('active');
                
                document.body.style.overflow = document.body.style.overflow === 'hidden' ? '' : 'hidden'; 
            }
            </script>
            
            <div class="dashboard-content">
