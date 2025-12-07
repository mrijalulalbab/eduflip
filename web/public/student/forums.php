<?php 
require_once '../../includes/config.php';
require_once '../../includes/auth.php';
require_once '../../includes/forums.php';

// Auth Check
if (!isLoggedIn()) { header('Location: ../login.php'); exit; }

$search = $_GET['search'] ?? '';
$threads = getThreads(null, $search);

include 'includes/header.php'; 
?>

<!-- Header Section -->
<div class="section-title-group reveal-element">
    <div>
        <h1 style="font-size: 2rem; margin-bottom: 0.5rem;">Discussion Forum 💬</h1>
        <p class="text-muted">Connect with peers and instructors.</p>
    </div>
    <a href="create_thread.php" class="btn btn-primary"><i class="ri-chat-new-line"></i> New Discussion</a>
</div>

<!-- Search Tool -->
<div class="card reveal-element" style="padding: 1.5rem; margin-bottom: 2rem;">
    <form method="GET" style="display: flex; gap: 1rem;">
        <div style="position: relative; flex: 1;">
            <i class="ri-search-line" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
            <input type="text" name="search" class="form-control" style="padding-left: 2.5rem;" placeholder="Search discussions..." value="<?php echo htmlspecialchars($search); ?>">
        </div>
        <button type="submit" class="btn btn-primary">Search</button>
    </form>
</div>

<!-- Thread List -->
<div class="reveal-element">
    <?php if (empty($threads)): ?>
        <div class="empty-state">
            <div style="font-size: 3rem; color: var(--text-muted); margin-bottom: 1rem;"><i class="ri-chat-off-line"></i></div>
            <h3 style="margin-bottom: 0.5rem;">No discussions found</h3>
            <p class="text-muted">Be the first to start a conversation!</p>
        </div>
    <?php else: ?>
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <?php foreach ($threads as $thread): ?>
                <div class="card" style="padding: 1.5rem; transition: var(--transition);">
                    <div style="display: flex; gap: 1rem; align-items: flex-start;">
                        <!-- Avatar -->
                        <div style="width: 48px; height: 48px; border-radius: 50%; background: to rgba(255,255,255,0.05); display: flex; align-items: center; justify-content: center; font-weight: bold; background: rgba(32, 178, 170, 0.1); color: var(--secondary);">
                            <?php echo strtoupper(substr($thread['author_name'], 0, 1)); ?>
                        </div>
                        
                        <!-- Content -->
                        <div style="flex: 1;">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem;">
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <span class="badge" style="margin: 0;"><?php echo htmlspecialchars($thread['course_code'] ?? 'General'); ?></span>
                                    <span class="text-muted" style="font-size: 0.85rem;"><?php echo date('M d, Y', strtotime($thread['created_at'])); ?></span>
                                </div>
                                <?php if ($thread['is_pinned']): ?>
                                    <div class="text-secondary"><i class="ri-pushpin-fill"></i></div>
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
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
