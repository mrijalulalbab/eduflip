<?php 
require_once '../../includes/config.php';
require_once '../../includes/auth.php';

// Ensure user is logged in
requireLogin();
if ($_SESSION['role'] !== 'dosen') {
    header('Location: ../login.php');
    exit;
}

$dosen_id = $_SESSION['user_id'];
$dosen_name = $_SESSION['full_name'];

// Fetch Stats
$stats = [
    'courses' => 0,
    'students' => 0
];

// 1. Total Courses
$stmt = $pdo->prepare("SELECT COUNT(*) FROM courses WHERE created_by = ?");
$stmt->execute([$dosen_id]);
$stats['courses'] = $stmt->fetchColumn();

// 2. Total Students
$stmt = $pdo->prepare("
    SELECT COUNT(DISTINCT ce.student_id) 
    FROM course_enrollments ce
    JOIN courses c ON ce.course_id = c.id
    WHERE c.created_by = ?
");
$stmt->execute([$dosen_id]);
$stats['students'] = $stmt->fetchColumn();

// 3. Course Discussions
$stmt = $pdo->prepare("
    SELECT COUNT(ft.id) 
    FROM forum_threads ft
    JOIN courses c ON ft.course_id = c.id
    WHERE c.created_by = ?
");
$stmt->execute([$dosen_id]);
$stats['discussions'] = $stmt->fetchColumn();

// Fetch Recent Courses
$stmt = $pdo->prepare("SELECT * FROM courses WHERE created_by = ? ORDER BY created_at DESC LIMIT 3");
$stmt->execute([$dosen_id]);
$recent_courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'includes/header.php';



$dosen_id = $_SESSION['user_id'];
$dosen_name = $_SESSION['full_name'];
// Stats queries match existing...
// (Keeping existing logic but removing layout wrapper since header handles it)

?>

        <div class="reveal-element">
            <!-- Welcome Hero -->
            <div class="welcome-text reveal-element">
                <h1 style="line-height: 1.2;">Welcome back, <br><span class="text-secondary"><?php echo htmlspecialchars(explode(' ', $dosen_name)[0]); ?>!</span> 👨‍🏫</h1>
                <p class="text-muted">You have <strong><?php echo $stats['courses']; ?></strong> active courses. Ready to inspire?</p>
            </div>

            <!-- Stats Grid -->
            <div class="stats-container reveal-element" style="margin-top: 2rem;">
                <div class="stat-card">
                    <div class="stat-icon blue">
                        <i class="ri-book-open-line"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $stats['courses']; ?></h3>
                        <p>Active Courses</p>
                    </div>
                </div>
                
                 <div class="stat-card">
                    <div class="stat-icon orange">
                        <i class="ri-group-line"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $stats['students']; ?></h3>
                        <p>Total Students</p>
                    </div>
                </div>
                
                 <div class="stat-card" onclick="window.location.href='forums.php'" style="cursor: pointer;">
                    <div class="stat-icon green">
                        <i class="ri-discuss-line"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $stats['discussions']; ?></h3>
                        <p>Course Discussions</p>
                    </div>
                </div>
            </div>

            <!-- Action Section / Recent Courses -->
            <div class="current-courses reveal-element">
                <div class="section-title-group">
                    <h2>Recent Courses</h2>
                    <div style="display: flex; gap: 1rem;">
                        <a href="create_course.php" class="btn btn-primary"><i class="ri-add-line"></i> Create Course</a>
                        <a href="my_courses.php" class="btn btn-ghost">View All <i class="ri-arrow-right-line"></i></a>
                    </div>
                </div>

                <?php if (empty($recent_courses)): ?>
                    <div class="empty-state">
                        <div style="width: 80px; height: 80px; background: rgba(255,255,255,0.05); border-radius: 50%; margin: 0 auto 1.5rem; display: flex; align-items: center; justify-content: center; color: var(--text-muted); font-size: 2rem;">
                            <i class="ri-slideshow-line"></i>
                        </div>
                        <h3 style="color: var(--text-main); margin-bottom: 0.5rem;">No Courses Yet</h3>
                        <p class="text-muted" style="max-width: 400px; margin: 0 auto 2rem;">Start your teaching journey by creating your first course material.</p>
                        <a href="create_course.php" class="btn btn-primary animate-pulse-glow">Create Course</a>
                    </div>
                <?php else: ?>
                    <div class="course-grid">
                        <?php foreach ($recent_courses as $course): ?>
                            <div class="dashboard-course-card">
                                <img src="<?php echo !empty($course['thumbnail']) ? $course['thumbnail'] : 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&q=80&w=400'; ?>" class="card-img-top" alt="Course Cover">
                                <div class="card-body">
                                    <div>
                                        <span class="card-badge"><?php echo htmlspecialchars($course['course_code']); ?></span>
                                        <h3 class="card-title"><?php echo htmlspecialchars($course['course_name']); ?></h3>
                                        <div class="card-instructor">
                                            <i class="ri-calendar-line"></i> Created <?php echo date('M d, Y', strtotime($course['created_at'])); ?>
                                        </div>
                                    </div>
                                    
                                    <div class="card-actions">
                                        <a href="manage_course.php?id=<?php echo $course['id']; ?>" class="btn btn-outline" style="width: 100%; text-align: center; display: block; padding: 0.75rem; border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: white; text-decoration: none; transition: all 0.2s;">Manage Course</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
<?php include 'includes/footer.php'; ?>
