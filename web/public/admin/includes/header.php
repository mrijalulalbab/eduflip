<?php
require_once '../../includes/config.php';

// NO AUTH - Direct Access Admin Panel
// Assets path logic - Corrected for admin directory
$assets_path = '../assets/';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - EduFlip</title>
    
    <!-- Tailwind CSS (Required for layout) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        dark: '#0f172a',
                        primary: '#0ea5e9',
                        secondary: '#6366f1'
                    }
                }
            }
        }
    </script>
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    
    <!-- Styles -->
    <!-- Styles -->
    <link rel="stylesheet" href="<?php echo $assets_path; ?>css/main.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo $assets_path; ?>css/admin.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="dashboard-container">
        <?php include 'sidebar.php'; ?>
        
        <div class="dashboard-main">
            <!-- Top Header -->
            <header class="admin-header">
                <div>
                     <h2 class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-white to-gray-400">Welcome Back, Admin</h2>
                     <p class="text-muted text-sm">Here's what's happening today.</p>
                </div>
                
                <div class="flex items-center gap-4">
                    <button class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-gray-400 hover:text-white hover:bg-white/10 transition">
                        <i class="ri-notification-3-line"></i>
                    </button>
                    <div class="flex items-center gap-3 pl-4 border-l border-white/10">
                        <div class="text-right">
                            <div class="text-sm font-semibold text-white">System Admin</div>
                            <div class="text-xs text-muted">Super User</div>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white shadow-lg shadow-purple-500/20">
                            <i class="ri-shield-star-fill"></i>
                        </div>
                    </div>
                </div>
            </header>

