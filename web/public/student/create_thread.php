<?php 
require_once '../../includes/config.php';
require_once '../../includes/auth.php';
require_once '../../includes/forums.php';

if (!isLoggedIn()) { header('Location: ../login.php'); exit; }

// Fetch enrolled courses for selection
$stmt = $pdo->prepare("SELECT c.id, c.course_name FROM courses c JOIN course_enrollments e ON c.id = e.course_id WHERE e.student_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $course_id = $_POST['course_id'];
    $category = $_POST['category'];
    
    if (createThread($course_id, $_SESSION['user_id'], $title, $content, $category)) {
        header("Location: forums.php");
        exit;
    } else {
        $error = "Failed to create discussion.";
    }
}

include 'includes/header.php'; 
?>

<div class="reveal-element">
    <div class="section-title-group">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <a href="forums.php" class="btn btn-outline" style="padding: 0.5rem 1rem;"><i class="ri-arrow-left-line"></i></a>
            <div>
                <h1 style="font-size: 2rem; margin: 0;">Start New Discussion</h1>
                <p class="text-muted" style="margin: 0;">Ask a question or share knowledge.</p>
            </div>
        </div>
    </div>

    <div class="card" style="max-width: 800px; padding: 2rem;">
        <form method="POST">
            <div class="form-group">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control" placeholder="What's on your mind?" required>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.25rem;">
                <div class="form-group" style="margin: 0;">
                    <label class="form-label">Related Course</label>
                    <select name="course_id" class="form-control" required>
                        <option value="">Select Course...</option>
                        <?php foreach ($courses as $c): ?>
                            <option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['course_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group" style="margin: 0;">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-control">
                        <option value="question">Question ❓</option>
                        <option value="discussion">General Discussion 🗣️</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Content</label>
                <textarea name="content" class="form-control" rows="8" placeholder="Describe your question or topic..." required></textarea>
            </div>
            
            <div style="display: flex; justify-content: flex-end; margin-top: 2rem;">
                <button type="submit" class="btn btn-primary" style="padding-left: 2rem; padding-right: 2rem;">Post Discussion</button>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
