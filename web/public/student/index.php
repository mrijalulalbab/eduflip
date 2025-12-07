<?php
require_once '../../includes/config.php';
require_once '../../includes/auth.php';
require_once '../../includes/courses.php';

// Auth Check
if (!isLoggedIn() || $_SESSION['role'] !== 'mahasiswa') {
    header('Location: ../login.php');
    exit;
}

$base_url = '..'; // For sidebar links

// Fetch Data
// Re-use logic:
function __getEnrolled($student_id) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT c.*, u.full_name as instructor_name, e.status as enrollment_status, e.enrolled_at 
        FROM courses c 
        JOIN course_enrollments e ON c.id = e.course_id 
        JOIN users u ON c.created_by = u.id 
        WHERE e.student_id = ?
        ORDER BY e.enrolled_at DESC
    ");
    $stmt->execute([$student_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
$enrolled_courses = __getEnrolled($_SESSION['user_id']);

include 'includes/header.php'; 
?>

<div class="welcome-text reveal-element">
    <h1 style="line-height: 1.2;">Welcome back, <br><span class="text-secondary"><?php echo htmlspecialchars(explode(' ', $_SESSION['full_name'])[0]); ?>!</span> 👋</h1>
    <p class="text-muted">You have <strong><?php echo count($enrolled_courses); ?></strong> active courses. Let's keep the momentum going!</p>
</div>

<!-- Stats -->
<div class="stats-container reveal-element" style="margin-top: 2rem;">
    <div class="stat-card">
        <div class="stat-icon blue">
            <i class="ri-book-open-line"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo count($enrolled_courses); ?></h3>
            <p>Enrolled Courses</p>
        </div>
    </div>
    
     <div class="stat-card">
        <div class="stat-icon orange">
            <i class="ri-time-line"></i>
        </div>
        <div class="stat-info">
            <h3>0</h3>
            <p>Pending Tasks</p>
        </div>
    </div>
    
     <div class="stat-card">
        <div class="stat-icon green">
            <i class="ri-medal-line"></i>
        </div>
        <div class="stat-info">
            <h3>0</h3>
            <p>Certificates</p>
        </div>
    </div>
</div>

<!-- Recent Courses -->
<div class="current-courses reveal-element">
    <div class="section-title-group">
        <h2>Continue Learning</h2>
        <a href="courses.php" class="btn btn-ghost">View All <i class="ri-arrow-right-line"></i></a>
    </div>

    <?php if (empty($enrolled_courses)): ?>
        <div class="empty-state">
            <div style="width: 80px; height: 80px; background: rgba(255,255,255,0.05); border-radius: 50%; margin: 0 auto 1.5rem; display: flex; align-items: center; justify-content: center; color: var(--text-muted); font-size: 2rem;">
                <i class="ri-book-2-fill"></i>
            </div>
            <h3 style="color: var(--text-main); margin-bottom: 0.5rem;">Start Your Journey</h3>
            <p class="text-muted" style="max-width: 400px; margin: 0 auto 2rem;">You are not enrolled in any courses yet. Explore our catalog to find your first class.</p>
            <a href="../courses/index.php" class="btn btn-primary animate-pulse-glow">Browse Course Catalog</a>
        </div>
    <?php else: ?>
        <div class="course-grid">
            <?php foreach ($enrolled_courses as $course): ?>
                <div class="dashboard-course-card">
                    <img src="<?php echo !empty($course['thumbnail']) ? $course['thumbnail'] : 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&q=80&w=400'; ?>" class="card-img-top" alt="Course Bg">
                    <div class="card-body">
                        <div>
                            <span class="card-badge"><?php echo htmlspecialchars($course['course_code']); ?></span>
                            <h3 class="card-title"><?php echo htmlspecialchars($course['course_name']); ?></h3>
                            <div class="card-instructor">
                                <i class="ri-user-3-line"></i> <?php echo htmlspecialchars($course['instructor_name']); ?>
                            </div>
                        </div>
                        
                        <div class="card-actions">
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 0%"></div>
                            </div>
                            <a href="../courses/detail.php?id=<?php echo $course['id']; ?>" class="btn btn-primary" style="width: 100%;">Continue Learning</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
