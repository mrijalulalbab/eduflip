<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?> - Transform Your Learning</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    
    <!-- CSS -->
<?php
    require_once __DIR__ . '/auth.php';
    $assets_path = isset($assets_path) ? $assets_path : 'assets/';
    $link_prefix = str_replace('assets/', '', $assets_path);
    ?>
    <link rel="stylesheet" href="<?php echo $assets_path; ?>css/main.css?v=<?php echo time(); ?>">
</head>
<body>
    <nav class="navbar">
        <div class="container nav-container">
            <a href="<?php echo $link_prefix; ?>index.php" class="nav-logo">
                <div class="logo-icon">
                    <i class="ri-book-open-fill"></i>
                </div>
                <span>EduFlip</span>
            </a>
            
            <!-- Mobile Toggle -->
            <button class="mobile-toggle" onclick="toggleMobileMenu()">
                <i class="ri-menu-3-line"></i>
            </button>

            <!-- Desktop Menu -->
            <ul class="nav-menu desktop-menu">
                <li><a href="<?php echo $link_prefix; ?>index.php" class="nav-link">Home</a></li>
                <li><a href="<?php echo $link_prefix; ?>courses/index.php" class="nav-link">Courses</a></li>
                <li><a href="<?php echo $link_prefix; ?>pricing.php" class="nav-link">Pricing</a></li>
                <li><a href="<?php echo $link_prefix; ?>about.php" class="nav-link">About</a></li>
            </ul>
            
            <div class="flex items-center gap-2 desktop-auth">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="<?php echo $link_prefix . getDashboardUrl($_SESSION['role']); ?>" class="btn btn-primary">Dashboard</a>
                    <a href="<?php echo $link_prefix; ?>logout.php" class="btn btn-ghost">Logout</a>
                <?php else: ?>
                    <a href="<?php echo $link_prefix; ?>login.php" class="btn btn-ghost">Login</a>
                    <a href="<?php echo $link_prefix; ?>register.php" class="btn btn-primary">Get Started</a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Mobile Menu Overlay -->
        <div id="mobile-menu" class="mobile-menu">
            <div class="mobile-menu-header">
                <div class="nav-logo">
                    <div class="logo-icon"><i class="ri-book-open-fill"></i></div>
                    <span>EduFlip</span>
                </div>
                <button onclick="toggleMobileMenu()" style="background:none; border:none; color:white; font-size:1.5rem;"><i class="ri-close-line"></i></button>
            </div>
            <ul class="mobile-nav-links">
                <li><a href="<?php echo $link_prefix; ?>index.php">Home</a></li>
                <li><a href="<?php echo $link_prefix; ?>courses/index.php">Courses</a></li>
                <li><a href="<?php echo $link_prefix; ?>pricing.php">Pricing</a></li>
                <li><a href="<?php echo $link_prefix; ?>about.php">About</a></li>
            </ul>
            <div class="mobile-auth-buttons">
                 <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="<?php echo $link_prefix . getDashboardUrl($_SESSION['role']); ?>" class="btn btn-primary" style="width:100%; justify-content:center;">Dashboard</a>
                    <a href="<?php echo $link_prefix; ?>logout.php" class="btn btn-outline" style="width:100%; justify-content:center;">Logout</a>
                <?php else: ?>
                    <a href="<?php echo $link_prefix; ?>login.php" class="btn btn-outline" style="width:100%; justify-content:center;">Login</a>
                    <a href="<?php echo $link_prefix; ?>register.php" class="btn btn-primary" style="width:100%; justify-content:center;">Get Started</a>
                <?php endif; ?>
            </div>
        </div>

        <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('active');
        }
        </script>
    </nav>
