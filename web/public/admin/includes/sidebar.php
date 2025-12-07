<aside class="dashboard-sidebar">
    <a href="../index.php" class="sidebar-brand">
        <div class="brand-icon">
            <i class="ri-shield-keyhole-line"></i>
        </div>
        <div class="brand-text">EduFlip<span style="color: var(--secondary);">.Admin</span></div>
    </a>
    
    <nav class="sidebar-nav">
        <a href="index.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
            <i class="ri-dashboard-line"></i>
            <span>Dashboard</span>
        </a>
        
        <a href="users.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'users.php' ? 'active' : ''; ?>">
            <i class="ri-user-settings-line"></i>
            <span>User Management</span>
        </a>
        
        <a href="courses.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'courses.php' ? 'active' : ''; ?>">
            <i class="ri-book-read-line"></i>
            <span>All Courses</span>
        </a>
        
        <div style="margin-top: auto; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 1rem;">
             <a href="../../index.php" class="nav-item">
                <i class="ri-home-line"></i>
                <span>Back to Main Site</span>
            </a>
        </div>
    </nav>
</aside>
