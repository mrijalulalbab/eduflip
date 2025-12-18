<?php
require_once '../../includes/config.php';
require_once '../../includes/auth.php';
require_once '../../includes/courses.php';

// Auth Check
if (!isLoggedIn() || $_SESSION['role'] !== 'mahasiswa') {
    header('Location: ../login.php');
    exit;
}

$base_url = '..';

// Fetch ALL Enrolled Courses
function __getAllEnrolled($student_id) {
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
$courses = __getAllEnrolled($_SESSION['user_id']);

include 'includes/header.php'; 
?>

<div class="section-title-group reveal-element">
    <div>
        <h2 style="font-size: 2rem; margin-bottom: 0.5rem;">My Courses</h2>
        <p class="text-muted">Manage your learning and track progress.</p>
    </div>
    <a href="../courses/index.php" class="btn btn-primary"><i class="ri-add-line"></i> Enroll New</a>
</div>


<!-- Tabs Navigation -->
<div class="tabs-nav">
    <button class="tab-btn active" onclick="switchTab('curriculum', this)">
        Informatics UII Curriculum
    </button>
    <button class="tab-btn" onclick="switchTab('general', this)">
        General Courses
    </button>
</div>

<!-- Curriculum Courses -->
<div id="curriculum-content" class="course-grid tab-content">
    <?php 
    $hasCurriculum = false;
    foreach ($courses as $course) {
        if (($course['category'] ?? 'general') === 'curriculum') {
            $hasCurriculum = true;
            renderCourseCard($course);
        }
    }
    if (!$hasCurriculum): ?>
        <div class="empty-state w-full col-span-full">
            <div style="font-size: 3rem; color: var(--text-muted); margin-bottom: 1rem;"><i class="ri-folder-open-line"></i></div>
            <h3>No curriculum courses found</h3>
            <p class="text-muted">You are not enrolled in any mandatory courses yet.</p>
        </div>
    <?php endif; ?>
</div>

<!-- General Courses -->
<div id="general-content" class="course-grid tab-content hidden" style="display: none;">
    <?php 
    $hasGeneral = false;
    foreach ($courses as $course) {
        if (($course['category'] ?? 'general') === 'general') {
            $hasGeneral = true;
            renderCourseCard($course);
        }
    }
    if (!$hasGeneral): ?>
         <div class="empty-state w-full col-span-full">
            <div style="font-size: 3rem; color: var(--text-muted); margin-bottom: 1rem;"><i class="ri-function-line"></i></div>
            <h3>No general courses found</h3>
            <p class="text-muted">Explore our library for extra learning materials.</p>
        </div>
    <?php endif; ?>
</div>

<script>
function switchTab(type, btn) {
    // Hide all contents
    document.querySelectorAll('.tab-content').forEach(el => el.style.display = 'none');
    // Show selected
    document.getElementById(type + '-content').style.display = 'grid'; // grid for course-grid
    
    // Update buttons
    document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
    btn.classList.add('active');
}
</script>


<?php
function renderCourseCard($course) {
    $progress = calculateCourseProgress($_SESSION['user_id'], $course['id']);
?>
    <div class="dashboard-course-card fade-in">
        <img src="<?php echo !empty($course['thumbnail']) ? $course['thumbnail'] : 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&q=80&w=400'; ?>" class="card-img-top" alt="Course Bg">
        <div class="card-body">
            <div>
                <span class="card-badge <?php echo ($course['category'] ?? 'general') === 'curriculum' ? 'badge-blue' : ''; ?>">
                    <?php echo htmlspecialchars($course['course_code']); ?>
                </span>
                <h3 class="card-title"><?php echo htmlspecialchars($course['course_name']); ?></h3>
                <div class="card-instructor">
                    <i class="ri-user-3-line"></i> <?php echo htmlspecialchars($course['instructor_name']); ?>
                </div>
            </div>
            
            <div class="card-actions">
                    <div class="flex justify-between text-muted" style="font-size: 0.8rem; margin-bottom: 0.5rem;">
                    <span>Progress</span>
                    <span><?php echo $progress; ?>%</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: <?php echo $progress; ?>%"></div>
                </div>
                <a href="learn.php?course_id=<?php echo $course['id']; ?>" class="btn btn-outline" style="width: 100%;">
                    <?php echo $progress > 0 ? 'Continue Learning' : 'Start Learning'; ?>
                </a>
            </div>
        </div>
    </div>
<?php } ?>

<?php include 'includes/footer.php'; ?>
