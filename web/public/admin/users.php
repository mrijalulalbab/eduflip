<?php
require_once '../../includes/config.php';
require_once '../../includes/admin.php';

include 'includes/header.php';

$search = $_GET['search'] ?? '';
$role = $_GET['role'] ?? '';

// Handle actions (e.g., suspend) in a real app, typically POST request
if (isset($_POST['action']) && isset($_POST['user_id'])) {
    if ($_POST['action'] === 'activate') {
        updateUserStatus($_POST['user_id'], 'active');
    } elseif ($_POST['action'] === 'suspend') {
        updateUserStatus($_POST['user_id'], 'suspended');
    }
}

$users = getAllUsers($search, $role);
?>

<div class="reveal-element">
    <div class="flex justify-between items-end mb-6">
        <div>
            <h1 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-purple-400 mb-2">User Directory</h1>
            <p class="text-muted">Manage system access and roles.</p>
        </div>
    </div>
    
    <!-- Filters -->
    <div class="p-1 mb-8 bg-white/5 rounded-2xl border border-white/5 backdrop-blur-md">
        <form method="GET" class="flex gap-4 p-4">
            <div class="flex-1">
                <div class="relative group">
                    <i class="ri-search-line absolute left-4 top-3.5 text-gray-500 group-focus-within:text-blue-400 transition"></i>
                    <input type="text" name="search" class="w-full bg-black/20 border border-white/5 rounded-xl py-3 pl-12 pr-4 text-white focus:outline-none focus:border-blue-500/50 transition" placeholder="Search users by name or email..." value="<?php echo htmlspecialchars($search); ?>">
                </div>
            </div>
            
            <div class="w-48 relative">
                 <select name="role" class="w-full bg-black/20 border border-white/5 rounded-xl py-3 px-4 text-white appearance-none focus:outline-none focus:border-blue-500/50 cursor-pointer">
                     <option value="">All Roles</option>
                     <option value="mahasiswa" <?php echo $role === 'mahasiswa' ? 'selected' : ''; ?>>Student</option>
                     <option value="dosen" <?php echo $role === 'dosen' ? 'selected' : ''; ?>>Lecturer</option>
                     <option value="admin" <?php echo $role === 'admin' ? 'selected' : ''; ?>>Admin</option>
                 </select>
                 <i class="ri-arrow-down-s-line absolute right-4 top-3.5 text-gray-500 pointer-events-none"></i>
            </div>
            
            <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white px-6 rounded-xl font-medium transition shadow-lg shadow-blue-500/25">Filter</button>
            <?php if($search || $role): ?>
                <a href="users.php" class="px-6 flex items-center justify-center text-gray-400 hover:text-white transition">Reset</a>
            <?php endif; ?>
        </form>
    </div>
    
    <!-- Users Table -->
    <div class="card-premium overflow-hidden !p-0">
        <div class="overflow-x-auto">
            <table class="premium-table">
                <thead>
                    <tr>
                        <th>User Profile</th>
                        <th>System Role</th>
                        <th>Account Status</th>
                        <th>Joined Date</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)): ?>
                        <tr><td colspan="5" class="text-center py-12 text-muted">
                            <i class="ri-user-unfollow-line text-4xl mb-4 block opacity-50"></i>
                            No users found matching your criteria.
                        </td></tr>
                    <?php else: ?>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td>
                                    <div class="user-cell">
                                        <div class="user-avatar-small">
                                            <?php echo strtoupper(substr($user['full_name'], 0, 1)); ?>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-white"><?php echo htmlspecialchars($user['full_name']); ?></div>
                                            <div class="text-xs text-muted"><?php echo htmlspecialchars($user['email']); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php 
                                    $roleBadge = match($user['role']) {
                                        'admin' => 'badge-purple',
                                        'dosen' => 'badge-blue',
                                        default => 'badge-admin bg-white/5 border-white/10 text-gray-400'
                                    };
                                    ?>
                                    <span class="badge-admin <?php echo $roleBadge; ?>"><?php echo ucfirst($user['role']); ?></span>
                                </td>
                                <td>
                                    <?php 
                                    $statusBadge = match($user['status']) {
                                        'active' => 'badge-green',
                                        'suspended' => 'badge-red',
                                        'pending' => 'badge-blue',
                                        default => 'badge-admin'
                                    };
                                    ?>
                                    <span class="badge-admin <?php echo $statusBadge; ?>">
                                        <?php echo ucfirst($user['status']); ?>
                                    </span>
                                </td>
                                <td class="text-sm text-muted">
                                    <?php echo date('M d, Y', strtotime($user['created_at'])); ?>
                                </td>
                                <td class="text-right">
                                <td class="text-right">
                                    <div style="display: flex; align-items: center; justify-content: flex-end; gap: 0.5rem;">
                                        <!-- Actions Form -->
                                        <form method="POST" style="display:inline; margin: 0;">
                                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                            <?php if ($user['status'] === 'suspended' || $user['status'] === 'pending'): ?>
                                                <button type="submit" name="action" value="activate" 
                                                    style="width: 36px; height: 36px; border-radius: 8px; background: rgba(34, 197, 94, 0.2); color: #16a34a; border: 1px solid rgba(34, 197, 94, 0.3); cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s; padding: 0;"
                                                    onmouseover="this.style.background='#16a34a'; this.style.color='white';"
                                                    onmouseout="this.style.background='rgba(34, 197, 94, 0.2)'; this.style.color='#16a34a';"
                                                    title="Activate User" 
                                                    onclick="return confirm('Activate this user?')">
                                                    <i class="ri-check-line" style="font-size: 1.25rem; line-height: 1;"></i>
                                                </button>
                                            <?php else: ?>
                                                <button type="submit" name="action" value="suspend" 
                                                    style="width: 36px; height: 36px; border-radius: 8px; background: rgba(239, 68, 68, 0.2); color: #dc2626; border: 1px solid rgba(239, 68, 68, 0.3); cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s; padding: 0;"
                                                    onmouseover="this.style.background='#dc2626'; this.style.color='white';"
                                                    onmouseout="this.style.background='rgba(239, 68, 68, 0.2)'; this.style.color='#dc2626';"
                                                    title="Suspend User" 
                                                    onclick="return confirm('Suspend this user?')">
                                                    <i class="ri-prohibited-line" style="font-size: 1.25rem; line-height: 1;"></i>
                                                </button>
                                            <?php endif; ?>
                                        </form>
                                        
                                        <!-- Restore Edit Button -->
                                        <a href="#" 
                                           style="width: 36px; height: 36px; border-radius: 8px; background: rgba(255, 255, 255, 0.05); color: #9ca3af; border: 1px solid rgba(255, 255, 255, 0.1); display: flex; align-items: center; justify-content: center; text-decoration: none; transition: all 0.2s;"
                                           onmouseover="this.style.background='rgba(255, 255, 255, 0.1)'; this.style.color='white';"
                                           onmouseout="this.style.background='rgba(255, 255, 255, 0.05)'; this.style.color='#9ca3af';"
                                           title="Edit">
                                            <i class="ri-pencil-line" style="font-size: 1.2rem; line-height: 1;"></i>
                                        </a>
                                    </div>
                                </td>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
