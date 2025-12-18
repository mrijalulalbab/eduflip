<?php 
require_once '../../includes/config.php';
require_once '../../includes/auth.php';
require_once '../../includes/forums.php';

// Auth Check
requireLogin();
if ($_SESSION['role'] !== 'dosen') {
    header('Location: ../login.php');
    exit;
}

$id = $_GET['id'] ?? 0;
// We should probably check if this thread belongs to a course owned by the dosen, but for MVP let's assume if they have the ID, they can view it.
// Ideally: getThread($id) -> check course_id -> check created_by.
$thread = getThread($id);

if (!$thread) die("Discussion not found.");

// Basic authorization: Dosen can see any public thread, but logically should be their relevant course.
// Skip deeper check for now to ensure visibility.

// Handle Reply
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = $_POST['content'];
    if (!empty($content)) {
        createReply($id, $_SESSION['user_id'], $content);
        header("Location: forum_detail.php?id=$id");
        exit;
    }
}

$replies = getReplies($id);

include 'includes/header.php'; 
?>

<div class="reveal-element">
    <div style="margin-bottom: 2rem;">
        <a href="forums.php" style="color: var(--text-muted); display: inline-flex; align-items: center; gap: 0.5rem;"><i class="ri-arrow-left-line"></i> Back to Course Discussions</a>
    </div>

    <!-- Original Post -->
    <div class="card" style="padding: 2rem; margin-bottom: 3rem; border-left: 4px solid var(--primary);">
        <div style="display: flex; gap: 1rem; margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.05);">
            <div style="width: 48px; height: 48px; border-radius: 50%; background: var(--primary); display: flex; align-items: center; justify-content: center; font-weight: bold; color: white; font-size: 1.25rem;">
                <?php echo strtoupper(substr($thread['author_name'], 0, 1)); ?>
            </div>
            <div>
                <h1 style="font-size: 1.5rem; margin-bottom: 0.25rem;"><?php echo htmlspecialchars($thread['title']); ?></h1>
                <div class="text-muted" style="font-size: 0.9rem;">
                    <span>By <?php echo htmlspecialchars($thread['author_name']); ?></span> • 
                    <span><?php echo date('M d, Y H:i', strtotime($thread['created_at'])); ?></span> •
                    <span class="badge" style="font-size: 0.75rem; padding: 0.2rem 0.6rem;"><?php echo htmlspecialchars($thread['course_code']); ?></span>
                </div>
            </div>
        </div>
        <div style="color: var(--text-main); line-height: 1.7;">
            <?php echo nl2br(htmlspecialchars($thread['content'])); ?>
        </div>
    </div>

    <!-- Replies -->
    <h3 style="margin-bottom: 1.5rem; font-size: 1.25rem; font-weight: 600;"><?php echo count($replies); ?> Replies</h3>
    
    <div style="display: flex; flex-direction: column; gap: 1rem; margin-bottom: 3rem;">
        <?php foreach ($replies as $reply): ?>
            <?php 
                $isLecturer = $reply['author_role'] == 'dosen';
                $borderColor = $isLecturer ? 'var(--secondary)' : 'transparent';
                $bgColor = $isLecturer ? 'rgba(32, 178, 170, 0.05)' : 'var(--bg-card)';
            ?>
            <div class="card" style="padding: 1.5rem; background: <?php echo $bgColor; ?>; border: 1px solid <?php echo $borderColor; ?>;">
                <div style="display: flex; gap: 1rem;">
                    <div style="width: 40px; height: 40px; border-radius: 50%; background: rgba(255,255,255,0.1); display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 0.9rem;">
                        <?php echo strtoupper(substr($reply['author_name'], 0, 1)); ?>
                    </div>
                    <div style="flex: 1;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                            <span style="font-weight: 600; <?php echo $isLecturer ? 'color: var(--secondary);' : ''; ?>">
                                <?php echo htmlspecialchars($reply['author_name']); ?>
                                <?php if ($isLecturer) echo '<i class="ri-verified-badge-fill" style="margin-left: 4px;" title="Lecturer"></i>'; ?>
                            </span>
                            <span class="text-muted" style="font-size: 0.8rem;"><?php echo date('M d, H:i', strtotime($reply['created_at'])); ?></span>
                        </div>
                        <p style="color: var(--text-main); font-size: 0.95rem; line-height: 1.6; margin: 0;"><?php echo nl2br(htmlspecialchars($reply['content'])); ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Reply Form -->
    <div class="card" style="padding: 1.5rem; position: sticky; bottom: 1rem; box-shadow: 0 -5px 20px rgba(0,0,0,0.3); border-top: 1px solid rgba(32, 178, 170, 0.3);">
        <form method="POST" style="display: flex; gap: 1rem;">
            <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--secondary); display: flex; align-items: center; justify-content: center; font-weight: bold; color: white;">
                <?php echo strtoupper(substr($_SESSION['full_name'], 0, 1)); ?>
            </div>
            <div style="flex: 1;">
                <div class="text-xs text-secondary mb-1 font-bold uppercase tracking-wider">Replying as Instructor</div>
                <textarea name="content" class="form-control" rows="2" placeholder="Write a reply..." required style="margin-bottom: 0.75rem;"></textarea>
                <div style="text-align: right;">
                    <button type="submit" class="btn btn-primary" style="font-size: 0.9rem; padding: 0.5rem 1rem;">Post Reply <i class="ri-send-plane-fill"></i></button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
