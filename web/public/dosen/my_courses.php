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

// Fetch courses
$stmt = $pdo->prepare("
    SELECT c.*, 
           (SELECT COUNT(*) FROM course_enrollments WHERE course_id = c.id) as student_count 
    FROM courses c 
    WHERE created_by = ? 
    ORDER BY created_at DESC
");
$stmt->execute([$dosen_id]);
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);



include 'includes/header.php';
?>



             <div class="reveal-element">
                <div class="section-title-group" style="align-items: center;">
                    <div>
                        <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem; color: var(--text-main);">My Courses</h1>
                        <p class="text-muted">Manage your teaching materials and settings.</p>
                    </div>
                    <a href="create_course.php" class="btn btn-primary"><i class="ri-add-line"></i> Create New Course</a>
                </div>

                <?php if (empty($courses)): ?>
                    <div class="empty-state">
                        <div style="width: 80px; height: 80px; background: rgba(255,255,255,0.05); border-radius: 50%; margin: 0 auto 1.5rem; display: flex; align-items: center; justify-content: center; color: var(--text-muted); font-size: 2rem;">
                            <i class="ri-folder-open-line"></i>
                        </div>
                        <h3 style="color: var(--text-main); margin-bottom: 0.5rem;">No Courses Found</h3>
                        <p class="text-muted" style="max-width: 400px; margin: 0 auto 2rem;">Create a course to get started.</p>
                        <a href="create_course.php" class="btn btn-primary">Create Course</a>
                    </div>
                <?php else: ?>
                    <div class="course-grid">
                        <?php foreach ($courses as $course): ?>
                            <div class="dashboard-course-card">
                                <img src="<?php echo !empty($course['thumbnail']) ? $course['thumbnail'] : 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&q=80&w=400'; ?>" class="card-img-top" alt="Course Cover">
                                <div class="card-body">
                                    <div>
                                        <span class="card-badge"><?php echo htmlspecialchars($course['course_code']); ?></span>
                                        <h3 class="card-title"><?php echo htmlspecialchars($course['course_name']); ?></h3>
                                        <div class="card-instructor">
                                            <i class="ri-user-line"></i> <?php echo $course['student_count']; ?> Students Enrolled
                                        </div>
                                    </div>
                                    
                                    <div class="card-actions">
                                        <div class="progress-bar">
                                            <div class="progress-fill" style="width: 100%"></div> <!-- Full width for instructor visual -->
                                        </div>
                                        <a href="manage_course.php?id=<?php echo $course['id']; ?>" class="btn btn-primary" style="width: 100%;">
                                            <i class="ri-upload-cloud-2-line"></i> Upload Materials & Manage
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
             </div>
<?php include 'includes/footer.php'; ?>
