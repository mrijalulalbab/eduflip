<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<aside class="dashboard-sidebar">
    <div class="sidebar-header">
        <a href="../index.php" class="sidebar-brand">
            <div class="logo-icon">
                <i class="ri-book-open-fill"></i>
            </div>
            <span>EduFlip</span>
        </a>
    </div>

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
        
        <div style="flex:1"></div>
        
        <a href="../logout.php" class="nav-item" style="color: #ef4444;">
            <i class="ri-logout-box-r-line"></i>
            <span>Logout</span>
        </a>
    </nav>
    

</aside>
