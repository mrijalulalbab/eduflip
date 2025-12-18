<?php
if (!isset($base_url)) {
    $base_url = '../../';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - EduFlip</title>
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    
    <!-- Styles -->
    <link rel="stylesheet" href="../assets/css/main.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../assets/css/student.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="dashboard-container">
        <?php include 'includes/sidebar.php'; ?>
        
        <div class="dashboard-main">
            <!-- Top Header Component -->
            <header class="dashboard-header">
                <div style="display: flex; align-items: center; gap: 1rem;">
                     <!-- Mobile Sidebar Toggle -->
                     <button class="sidebar-toggle" onclick="toggleSidebar()">
                        <i class="ri-menu-2-line"></i>
                    </button>
                     <!-- Breadcrumb or Title handled by page -->
                </div>
                
                <div class="user-snippet">
                    <div class="user-info">
                        <div class="user-name"><?php echo htmlspecialchars($_SESSION['full_name']); ?></div>
                        <div class="user-role">Student</div>
                    </div>
                    <div class="user-avatar">
                        <?php echo strtoupper(substr($_SESSION['full_name'], 0, 1)); ?>
                    </div>
                </div>
            </header>

            <!-- Sidebar Overlay for Mobile -->
            <div id="sidebar-overlay" class="sidebar-overlay" onclick="toggleSidebar()"></div>

            <script>
            function toggleSidebar() {
                document.querySelector('.dashboard-sidebar').classList.toggle('active');
                document.getElementById('sidebar-overlay').classList.toggle('active');
                document.body.style.overflow = document.body.style.overflow === 'hidden' ? '' : 'hidden'; // Prevent background scrolling
            }
            </script>
