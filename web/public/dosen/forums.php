<?php 
require_once '../../includes/config.php';
require_once '../../includes/auth.php';
require_once '../../includes/forums.php';

// Auth Check (Dosen Only)
requireLogin();
if ($_SESSION['role'] !== 'dosen') {
    header('Location: ../login.php');
    exit;
}

$search = $_GET['search'] ?? '';
$threads = getInstructorThreads($_SESSION['user_id'], $search);

$current_page = 'forums.php'; 
// Note: We need to set this for Sidebar active state manually or ensure sidebar detects it
// But sidebar logic is basename(PHP_SELF), so it will work if filename is forums.php, 
// BUT sidebar link is pointing to ../student/forum/index.php currently.
// I will update sidebar later to point here: `forums.php`.

include 'includes/header.php'; 
?>

<div class="reveal-element">
    <div class="section-title-group" style="margin-bottom: 2rem;">
        <div>
            <h1 style="font-size: 2rem; margin-bottom: 0.5rem;">Course Discussions 💬</h1>
            <p class="text-muted">Monitor and participate in student discussions across your courses.</p>
        </div>
        <!-- Dosen typically doesn't start threads, but they can if needed. For now, let's just View. -->
    </div>

    <!-- Search Tool -->
    <div class="card reveal-element" style="padding: 1.5rem; margin-bottom: 2rem;">
        <form method="GET" style="display: flex; gap: 1rem;">
            <div style="position: relative; flex: 1;">
                <i class="ri-search-line" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                <input type="text" name="search" class="form-control" style="padding-left: 2.5rem;" placeholder="Search threads by title or content..." value="<?php echo htmlspecialchars($search); ?>">
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
    </div>

    <!-- Thread List -->
    <div>
        <?php if (empty($threads)): ?>
            <div class="empty-state">
                <div style="font-size: 3rem; color: var(--text-muted); margin-bottom: 1rem;"><i class="ri-chat-off-line"></i></div>
                <h3 style="margin-bottom: 0.5rem;">No active discussions</h3>
                <p class="text-muted">Your students haven't started any discussions yet.</p>
            </div>
        <?php else: ?>
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <?php foreach ($threads as $thread): ?>
                    <div class="card" style="padding: 1.5rem; transition: var(--transition); border-left: 4px solid <?php echo $thread['reply_count'] > 0 ? 'var(--success)' : 'var(--warning)'; ?>;">
                        <div style="display: flex; gap: 1rem; align-items: flex-start;">
                            <!-- Avatar -->
                            <div style="width: 48px; height: 48px; border-radius: 50%; background: rgba(255,255,255,0.05); display: flex; align-items: center; justify-content: center; font-weight: bold; color: var(--text-muted);">
                                <?php echo strtoupper(substr($thread['author_name'], 0, 1)); ?>
                            </div>
                            
                            <!-- Content -->
                            <div style="flex: 1;">
                                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem;">
                                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                                        <span class="badge" style="background: rgba(37, 99, 235, 0.1); color: #60a5fa;"><?php echo htmlspecialchars($thread['course_code']); ?></span>
                                        <span class="text-muted" style="font-size: 0.85rem;"><?php echo date('M d, Y', strtotime($thread['created_at'])); ?></span>
                                    </div>
                                    <?php if ($thread['reply_count'] == 0): ?>
                                        <span style="font-size: 0.75rem; color: #fb923c; font-weight: 600;">Needs Reply</span>
                                    <?php endif; ?>
                                </div>
                                
                                <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 0.5rem;">
                                    <a href="forum_detail.php?id=<?php echo $thread['id']; ?>" style="color: var(--text-main); text-decoration: none;">
                                        <?php echo htmlspecialchars($thread['title']); ?>
                                    </a>
                                </h3>
                                
                                <p class="text-muted" style="font-size: 0.95rem; margin-bottom: 1rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    <?php echo strip_tags($thread['content']); ?>
                                </p>
                                
                                <div style="display: flex; gap: 1.5rem; color: var(--text-muted); font-size: 0.85rem;">
                                    <span><i class="ri-user-line"></i> <?php echo htmlspecialchars($thread['author_name']); ?></span>
                                    <span><i class="ri-message-3-line"></i> <?php echo $thread['reply_count']; ?> Replies</span>
                                    <span><i class="ri-eye-line"></i> <?php echo $thread['view_count']; ?> Views</span>
                                </div>
                            </div>
                            
                            <a href="forum_detail.php?id=<?php echo $thread['id']; ?>" class="btn btn-outline" style="align-self: center;">View</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
