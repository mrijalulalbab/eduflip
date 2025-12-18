<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<aside class="dashboard-sidebar">
    <a href="../index.php" class="sidebar-brand">
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
        <a href="my_courses.php" class="nav-item <?php echo ($current_page == 'my_courses.php' || $current_page == 'manage_course.php') ? 'active' : ''; ?>">
            <i class="ri-book-open-line"></i>
            <span>My Courses</span>
        </a>
        <a href="students.php" class="nav-item <?php echo $current_page == 'students.php' ? 'active' : ''; ?>">
            <i class="ri-group-line"></i>
            <span>Students</span>
        </a>
        <a href="quizzes.php" class="nav-item <?php echo $current_page == 'quizzes.php' ? 'active' : ''; ?>">
            <i class="ri-questionnaire-line"></i>
            <span>Quizzes</span>
        </a>
        <a href="forums.php" class="nav-item <?php echo (basename($current_page) == 'forums.php' || basename($current_page) == 'forum_detail.php') ? 'active' : ''; ?>">
             <i class="ri-discuss-line"></i>
             <span>Forums</span>
        </a>
        <a href="analytics.php" class="nav-item <?php echo basename($current_page) == 'analytics.php' ? 'active' : ''; ?>">
             <i class="ri-bar-chart-2-line"></i>
             <span>Analytics</span>
        </a>
        
        <div style="flex:1"></div>
        
        <a href="../logout.php" class="nav-item" style="color: #ef4444;">
            <i class="ri-logout-box-r-line"></i>
            <span>Logout</span>
        </a>
    </nav>
    

</aside>
