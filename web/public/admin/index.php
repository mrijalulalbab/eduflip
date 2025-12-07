<?php
require_once '../../includes/config.php';
require_once '../../includes/admin.php';

include 'includes/header.php';

$stats = getAdminStats();
?>

<div class="reveal-element">
    
    <!-- Stats Grid -->
    <div class="admin-grid">
        <div class="stat-card-premium">
            <div class="stat-icon-wrapper" style="background: rgba(59, 130, 246, 0.1); color: #60a5fa;">
                <i class="ri-user-follow-line"></i>
            </div>
            <div class="stat-value"><?php echo number_format($stats['total_users']); ?></div>
            <div class="stat-label">Total Users</div>
        </div>
        
        <div class="stat-card-premium">
            <div class="stat-icon-wrapper" style="background: rgba(16, 185, 129, 0.1); color: #34d399;">
                <i class="ri-graduation-cap-line"></i>
            </div>
             <div class="stat-value"><?php echo number_format($stats['total_students']); ?></div>
            <div class="stat-label">Active Students</div>
        </div>
        
        <div class="stat-card-premium">
            <div class="stat-icon-wrapper" style="background: rgba(168, 85, 247, 0.1); color: #c084fc;">
                <i class="ri-user-star-line"></i>
            </div>
             <div class="stat-value"><?php echo number_format($stats['total_lecturers']); ?></div>
            <div class="stat-label">Lecturers</div>
        </div>
        
        <div class="stat-card-premium">
            <div class="stat-icon-wrapper" style="background: rgba(249, 115, 22, 0.1); color: #fb923c;">
                <i class="ri-book-open-line"></i>
            </div>
             <div class="stat-value"><?php echo number_format($stats['total_courses']); ?></div>
            <div class="stat-label">Total Courses</div>
        </div>
    </div>
    
    <!-- Quick Actions / Recent -->
    <div class="grid grid-cols-2 gap-6">
        <div class="card-premium">
            <h3 class="text-xl font-bold mb-6 text-white flex items-center gap-2">
                <i class="ri-pulse-line text-green-400"></i> System Status
            </h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 rounded-xl bg-white/5 border border-white/5">
                    <div class="flex items-center gap-3">
                        <div class="w-2 h-2 rounded-full bg-green-400 shadow-[0_0_10px_rgba(74,222,128,0.5)]"></div>
                        <span class="text-sm font-medium">Database Connection</span>
                    </div>
                    <span class="badge-green">Operational</span>
                </div>
                <div class="flex items-center justify-between p-3 rounded-xl bg-white/5 border border-white/5">
                    <div class="flex items-center gap-3">
                         <div class="w-2 h-2 rounded-full bg-green-400 shadow-[0_0_10px_rgba(74,222,128,0.5)]"></div>
                        <span class="text-sm font-medium">Storage Service</span>
                    </div>
                    <span class="badge-green">Normal</span>
                </div>
                <div class="flex items-center justify-between p-3 rounded-xl bg-white/5 border border-white/5">
                    <div class="flex items-center gap-3">
                         <div class="w-2 h-2 rounded-full bg-blue-400 shadow-[0_0_10px_rgba(96,165,250,0.5)]"></div>
                        <span class="text-sm font-medium">Email Gateway</span>
                    </div>
                    <span class="badge-blue">Idle</span>
                </div>
            </div>
        </div>
        
        <div class="card-premium relative overflow-hidden">
             <!-- decorative glow -->
             <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: var(--admin-accent); filter: blur(100px); opacity: 0.15; pointer-events: none;"></div>
             
            <h3 class="text-xl font-bold mb-2 text-white">Quick Actions</h3>
            <p class="text-muted text-sm mb-6">Common administrative tasks available to you.</p>
            
            <div class="flex flex-col gap-3">
                <a href="users.php" class="flex items-center justify-between p-4 rounded-xl bg-gradient-to-r from-blue-600/20 to-blue-600/10 border border-blue-500/20 hover:border-blue-500/40 transition group">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-blue-500/20 flex items-center justify-center text-blue-400 group-hover:scale-110 transition">
                            <i class="ri-user-settings-line"></i>
                        </div>
                        <div class="text-left">
                            <div class="text-white font-semibold text-sm">Manage Users</div>
                            <div class="text-xs text-muted">View, edit, or suspend accounts</div>
                        </div>
                    </div>
                    <i class="ri-arrow-right-line text-blue-400 opacity-0 group-hover:opacity-100 transition-all transform group-hover:translate-x-1"></i>
                </a>
                
                 <a href="courses.php" class="flex items-center justify-between p-4 rounded-xl bg-gradient-to-r from-purple-600/20 to-purple-600/10 border border-purple-500/20 hover:border-purple-500/40 transition group">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-purple-500/20 flex items-center justify-center text-purple-400 group-hover:scale-110 transition">
                            <i class="ri-book-read-line"></i>
                        </div>
                        <div class="text-left">
                            <div class="text-white font-semibold text-sm">Review Courses</div>
                            <div class="text-xs text-muted">Audit content and materials</div>
                        </div>
                    </div>
                     <i class="ri-arrow-right-line text-purple-400 opacity-0 group-hover:opacity-100 transition-all transform group-hover:translate-x-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
