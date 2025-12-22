<?php
require_once '../../includes/config.php';
require_once '../../includes/auth.php';
require_once '../../includes/courses.php';
require_once '../../includes/quizzes.php';
require_once '../../includes/certificates.php';

// Auth Check
if (!isLoggedIn() || $_SESSION['role'] !== 'mahasiswa') {
    header('Location: ../login.php');
    exit;
}

$base_url = '..'; // For sidebar links

// Fetch Data
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

// Auto-Check Certificate Eligibility for all enrolled courses
foreach ($enrolled_courses as $course) {
    checkAndGenerateCertificate($_SESSION['user_id'], $course['id']);
}
$my_certificates = getStudentCertificates($_SESSION['user_id']);

// Calculate Pending Tasks (Unfinished Quizzes)
$all_quizzes = getStudentQuizzes($_SESSION['user_id']);
$pending_tasks = 0;
$pending_list = [];

foreach ($all_quizzes as $q) {
    // If never passed (best_score is null OR best_score < passing_score)
    if ($q['best_score'] === null || $q['best_score'] < $q['passing_score']) {
        $pending_tasks++;
        $pending_list[] = $q;
    }
}

// Achievements Logic (Added for Dashboard Widget)
// Calculate Perfect Quizzes for "Quiz Master"
$stmt = $pdo->prepare("SELECT COUNT(*) FROM quiz_attempts WHERE student_id = ? AND score >= 100");
$stmt->execute([$_SESSION['user_id']]);
$perfect_quizzes = $stmt->fetchColumn();

// Calculate Completed Courses
$completed_courses_count = 0;
foreach($enrolled_courses as $c) {
    if($c['enrollment_status'] == 'completed') $completed_courses_count++;
}

// Prepare Achievements Data (Subset for Dashboard)
$achievements_widget = [
    [
        'title' => 'Early Bird',
        'desc' => 'Joined Early Access',
        'icon' => 'ri-alarm-line',
        'color' => 'from-pink-500 to-rose-500',
        'unlocked' => true
    ],
    [
        'title' => 'First Steps',
        'desc' => 'Enrolled in a course',
        'icon' => 'ri-footprint-line',
        'color' => 'from-blue-400 to-cyan-400',
        'unlocked' => count($enrolled_courses) > 0
    ],
    [
        'title' => 'Quiz Master',
        'desc' => 'Score 100% on a quiz',
        'icon' => 'ri-trophy-line',
        'color' => 'from-yellow-400 to-orange-500',
        'unlocked' => $perfect_quizzes > 0
    ],
    [
        'title' => 'Course Champion',
        'desc' => 'Complete a course',
        'icon' => 'ri-medal-fill',
        'color' => 'from-emerald-400 to-green-500',
        'unlocked' => $completed_courses_count > 0
    ]
];

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
    
     <!-- Pending Tasks Card (Clickable) -->
     <div class="stat-card hover:bg-white/5 cursor-pointer transition" onclick="openPendingModal()">
        <div class="stat-icon orange">
            <i class="ri-time-line"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo $pending_tasks; ?></h3>
            <p>Pending Tasks <i class="ri-arrow-right-s-line ml-1 text-xs"></i></p>
        </div>
    </div>
    
     <div class="stat-card hover:bg-white/5 cursor-pointer transition" onclick="openCertModal()">
        <div class="stat-icon green">
            <i class="ri-medal-line"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo count($my_certificates); ?></h3>
            <p>Certificates <i class="ri-arrow-right-s-line ml-1 text-xs"></i></p>
        </div>
    </div>
</div>

<!-- Achievements Banner -->
<div class="reveal-element" style="margin-top: 2rem;">
    <h2 style="font-size: 1.2rem; margin-bottom: 1rem; display: flex; align-items: center; justify-content: space-between;">
        <span>Recent Achievements 🏆</span>
        <a href="achievements.php" style="font-size: 0.9rem; color: var(--color-primary); text-decoration: none;">View All</a>
    </h2>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
        <?php foreach($achievements_widget as $badge): ?>
            <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); border-radius: 16px; padding: 1.25rem; display: flex; align-items: center; gap: 1rem; position: relative; overflow: hidden;" class="<?php echo $badge['unlocked'] ? '' : 'opacity-50 grayscale'; ?>">
                <!-- Glow Effect -->
                <?php if($badge['unlocked']): ?>
                    <div style="position: absolute; top: -50%; left: -50%; width: 200%; height: 200%; background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 70%); pointer-events: none;"></div>
                <?php endif; ?>

                <div class="bg-gradient-to-br <?php echo $badge['color']; ?>" style="width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(0,0,0,0.2); flex-shrink: 0;">
                    <i class="<?php echo $badge['icon']; ?>" style="font-size: 1.5rem; color: white;"></i>
                </div>
                <div>
                    <h4 style="font-weight: 700; font-size: 0.95rem; margin-bottom: 0.2rem; color: <?php echo $badge['unlocked'] ? 'white' : '#9ca3af'; ?>"><?php echo $badge['title']; ?></h4>
                    <p style="font-size: 0.75rem; color: #9ca3af; line-height: 1.2;"><?php echo $badge['desc']; ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Certificate Modal (Inline Styles) -->
<div id="certModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 10000;">
    <div onclick="closeCertModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); backdrop-filter: blur(4px);"></div>
    <div style="position: relative; z-index: 10001; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; pointer-events: none;">
        <div style="background: #1e293b; border: 1px solid rgba(255,255,255,0.1); border-radius: 16px; width: 90%; max-width: 600px; max-height: 80vh; overflow: hidden; pointer-events: auto; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
            <div style="padding: 20px 24px; border-bottom: 1px solid rgba(255,255,255,0.05); display: flex; justify-content: space-between; align-items: center; background: rgba(255,255,255,0.02);">
                <h3 style="margin: 0; font-size: 1.25rem; font-weight: 700; color: white; display: flex; align-items: center; gap: 8px;">
                    <i class="ri-medal-line" style="color: #22c55e;"></i> My Certificates
                </h3>
                <button type="button" onclick="closeCertModal()" style="background: none; border: none; color: #9ca3af; cursor: pointer; font-size: 1.5rem; display: flex; align-items: center; justify-content: center;">
                    <i class="ri-close-line"></i>
                </button>
            </div>
            <div style="padding: 24px; overflow-y: auto; max-height: calc(80vh - 80px);">
                <?php if (empty($my_certificates)): ?>
                    <div style="text-align: center; padding: 32px 0; color: #9ca3af;">
                        <i class="ri-honor-of-kings-line" style="font-size: 2.25rem; margin-bottom: 12px; display: block; color: rgba(34, 197, 94, 0.2);"></i>
                        <p style="margin: 0;">No certificates earned yet.</p>
                        <p style="font-size: 0.8rem; margin-top: 8px; opacity: 0.6;">Complete all quizzes in a course to unlock.</p>
                    </div>
                <?php else: ?>
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        <?php foreach ($my_certificates as $cert): ?>
                            <div style="background: rgba(0,0,0,0.2); border-radius: 12px; padding: 16px; border: 1px solid rgba(255,255,255,0.05); display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <div style="font-size: 0.75rem; color: #22c55e; font-weight: 500; font-family: monospace; margin-bottom: 4px;"><?php echo htmlspecialchars($cert['course_code']); ?></div>
                                    <h4 style="margin: 0; font-weight: 700; color: white; font-size: 1.1rem;"><?php echo htmlspecialchars($cert['course_name']); ?></h4>
                                    <div style="font-size: 0.7rem; color: #9ca3af; margin-top: 4px;">Issued: <?php echo date('d M Y', strtotime($cert['issued_at'])); ?></div>
                                </div>
                                <a href="certificate.php?code=<?php echo $cert['certificate_code']; ?>" target="_blank" style="background: #22c55e; color: #fff; padding: 8px 16px; border-radius: 8px; font-size: 0.8rem; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 6px;">
                                    <i class="ri-download-line"></i> View
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
function openCertModal() {
    const modal = document.getElementById('certModal');
    if(modal) {
        if(modal.parentNode !== document.body) { document.body.appendChild(modal); }
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }
}
function closeCertModal() {
    const modal = document.getElementById('certModal');
    if(modal) {
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }
}
</script>

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

            <?php foreach ($enrolled_courses as $course): 
                // Calculate real progress
                $progress = calculateCourseProgress($_SESSION['user_id'], $course['id']);
            ?>
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
                             <div class="flex justify-between text-muted" style="font-size: 0.8rem; margin-bottom: 0.5rem;">
                                <span>Progress</span>
                                <span><?php echo $progress; ?>%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: <?php echo $progress; ?>%"></div>
                            </div>
                            <a href="../courses/detail.php?id=<?php echo $course['id']; ?>" class="btn btn-primary" style="width: 100%;">
                                <?php echo $progress > 0 ? 'Continue Learning' : 'Start Learning'; ?>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    <?php endif; ?>
</div>

<!-- Pending Tasks Modal -->
<div id="pendingModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 10000;">
    <!-- Backdrop -->
    <div onclick="closePendingModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); backdrop-filter: blur(4px);"></div>
    
    <!-- Modal Dialog -->
    <div style="position: relative; z-index: 10001; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; pointer-events: none;">
        <div style="background: #1e293b; border: 1px solid rgba(255,255,255,0.1); border-radius: 16px; width: 90%; max-width: 500px; max-height: 80vh; overflow: hidden; pointer-events: auto; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
            
            <!-- Header -->
            <div style="padding: 20px 24px; border-bottom: 1px solid rgba(255,255,255,0.05); display: flex; justify-content: space-between; align-items: center; background: rgba(255,255,255,0.02);">
                <h3 style="margin: 0; font-size: 1.25rem; font-weight: 700; color: white; display: flex; align-items: center; gap: 8px;">
                    <i class="ri-history-line" style="color: #f97316;"></i> Pending Tasks
                </h3>
                <button type="button" onclick="closePendingModal()" style="background: none; border: none; color: #9ca3af; cursor: pointer; font-size: 1.5rem; display: flex; align-items: center; justify-content: center;">
                    <i class="ri-close-line"></i>
                </button>
            </div>

            <!-- Content -->
            <div style="padding: 24px; overflow-y: auto; max-height: calc(80vh - 80px);">
                <?php if (empty($pending_list)): ?>
                    <div style="text-align: center; padding: 32px 0; color: #9ca3af;">
                        <i class="ri-check-double-line" style="font-size: 2.25rem; margin-bottom: 12px; display: block; color: rgba(34, 197, 94, 0.5);"></i>
                        <p style="margin: 0;">You're all caught up! No pending tasks.</p>
                    </div>
                <?php else: ?>
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        <?php foreach ($pending_list as $task): ?>
                            <div style="background: rgba(0,0,0,0.2); border-radius: 12px; padding: 16px; border: 1px solid rgba(255,255,255,0.05);">
                                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 8px;">
                                    <div>
                                        <div style="font-size: 0.75rem; color: #fb923c; font-weight: 500; font-family: monospace; margin-bottom: 4px;"><?php echo htmlspecialchars($task['course_code']); ?></div>
                                        <h4 style="margin: 0; font-weight: 700; color: white; font-size: 1rem;"><?php echo htmlspecialchars($task['title']); ?></h4>
                                    </div>
                                    <span style="padding: 2px 8px; border-radius: 4px; font-size: 0.65rem; font-weight: 700; background: rgba(255,255,255,0.1); color: #9ca3af;">
                                        <?php echo $task['latest_status'] ? strtoupper(str_replace('_', ' ', $task['latest_status'])) : 'NOT STARTED'; ?>
                                    </span>
                                </div>
                                <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 12px; padding-top: 12px; border-top: 1px solid rgba(255,255,255,0.05);">
                                    <div style="font-size: 0.75rem; color: #9ca3af; display: flex; gap: 12px;">
                                        <span><i class="ri-timer-line"></i> <?php echo $task['duration']; ?>m</span>
                                        <span><i class="ri-trophy-line"></i> Pass: <?php echo $task['passing_score']; ?>%</span>
                                    </div>
                                    <a href="take_quiz.php?id=<?php echo $task['id']; ?>" style="background: #ea580c; color: white; padding: 6px 12px; border-radius: 6px; font-size: 0.75rem; font-weight: 700; text-decoration: none; display: inline-block;">
                                        <?php echo $task['latest_status'] == 'in_progress' ? 'Resume' : 'Start Now'; ?>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
function openPendingModal() {
    console.log('Opening Pending Modal...');
    const modal = document.getElementById('pendingModal');
    if(modal) {
        // Move to body to avoid z-index stacking context issues from parent containers
        if(modal.parentNode !== document.body) {
            document.body.appendChild(modal);
        }
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    } else {
        console.error('Modal element not found!');
        alert('Error: Modal component not loaded.');
    }
}

function closePendingModal() {
    const modal = document.getElementById('pendingModal');
    if(modal) {
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }
}
</script>

<?php include 'includes/footer.php'; ?>
