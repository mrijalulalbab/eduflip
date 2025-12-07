<?php
// Get current page to set active class
$current_page = basename($_SERVER['PHP_SELF']);
?>
<aside class="dashboard-sidebar">
    <a href="<?php echo $base_url; ?>/index.php" class="sidebar-brand">
        <div class="brand-icon">
            <i class="ri-book-open-fill"></i>
        </div>
        <span class="brand-text">EduFlip</span>
    </a>
    
    <nav class="sidebar-nav">
        <a href="index.php" class="nav-item <?php echo $current_page == 'index.php' ? 'active' : ''; ?>">
            <i class="ri-dashboard-3-line"></i>
            <span>Dashboard</span>
        </a>
        <a href="courses.php" class="nav-item <?php echo $current_page == 'courses.php' ? 'active' : ''; ?>">
            <i class="ri-book-read-line"></i>
            <span>My Courses</span>
        </a>
        <a href="quizzes.php" class="nav-item <?php echo $current_page == 'quizzes.php' ? 'active' : ''; ?>">
            <i class="ri-task-line"></i>
            <span>My Quizzes</span>
        </a>
        <a href="forums.php" class="nav-item <?php echo $current_page == 'forums.php' ? 'active' : ''; ?>">
            <i class="ri-discuss-line"></i>
            <span>Forums</span>
        </a>
        <a href="achievements.php" class="nav-item <?php echo $current_page == 'achievements.php' ? 'active' : ''; ?>">
            <i class="ri-medal-line"></i>
            <span>Achievements</span>
        </a>
        
        <div style="flex: 1;"></div> <!-- Spacer -->
        
        <div style="padding-top: 1.5rem; border-top: 1px solid rgba(255,255,255,0.05); display: flex; flex-direction: column; gap: 0.5rem;">
            <a href="settings.php" class="nav-item <?php echo $current_page == 'settings.php' ? 'active' : ''; ?>">
                <i class="ri-settings-4-line"></i>
                <span>Settings</span>
            </a>
            <a href="../logout.php" class="nav-item" style="color: #ef4444;">
                <i class="ri-logout-box-r-line"></i>
                <span>Logout</span>
            </a>
        </div>
    </nav>
</aside>
