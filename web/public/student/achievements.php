<?php 
require_once '../../includes/config.php';
require_once '../../includes/auth.php';

if (!isLoggedIn()) { header('Location: ../login.php'); exit; }

include 'includes/header.php'; 

// 1. Fetch Stats
$user_id = $_SESSION['user_id'];

// Quiz Master: Check for perfect score
$stmt = $pdo->prepare("SELECT COUNT(*) FROM quiz_attempts WHERE student_id = ? AND score >= 100");
$stmt->execute([$user_id]);
$perfect_quizzes = $stmt->fetchColumn();

// Social Butterfly: Count forum posts (threads + replies)
$stmt = $pdo->prepare("
    SELECT 
    (SELECT COUNT(*) FROM forum_threads WHERE author_id = ?) + 
    (SELECT COUNT(*) FROM forum_replies WHERE author_id = ?)
");
$stmt->execute([$user_id, $user_id]);
$forum_posts = $stmt->fetchColumn();

// Course Champion: Completed courses
$stmt = $pdo->prepare("SELECT COUNT(*) FROM course_enrollments WHERE student_id = ? AND status = 'completed'");
$stmt->execute([$user_id]);
$completed_courses = $stmt->fetchColumn();

// First Steps: Enrolled in at least one course
$stmt = $pdo->prepare("SELECT COUNT(*) FROM course_enrollments WHERE student_id = ?");
$stmt->execute([$user_id]);
$enrolled_count = $stmt->fetchColumn();


$achievements = [
    [
        'title' => 'Early Bird',
        'desc' => 'Joined within the first month',
        'icon' => 'ri-alarm-line',
        'unlocked' => true, // Everyone is early bird in beta
        'date' => '2024-12-01'
    ],
    [
        'title' => 'First Steps',
        'desc' => 'Enroll in your first course',
        'icon' => 'ri-footprint-line',
        'unlocked' => $enrolled_count > 0,
        'date' => date('Y-m-d') // Approximate
    ],
    [
        'title' => 'Quiz Master',
        'desc' => 'Score 100% on a quiz',
        'icon' => 'ri-trophy-line',
        'unlocked' => $perfect_quizzes > 0,
        'date' => $perfect_quizzes > 0 ? date('Y-m-d') : null
    ],
    [
        'title' => 'Social Butterfly',
        'desc' => 'Post 5 times in the forum',
        'icon' => 'ri-chat-smile-line',
        'unlocked' => $forum_posts >= 5,
        'date' => $forum_posts >= 5 ? date('Y-m-d') : null
    ],
    [
        'title' => 'Dedicated Learner',
        'desc' => 'Login 7 days in a row',
        'icon' => 'ri-calendar-check-line',
        'unlocked' => false, // Requires login log table (future feature)
        'date' => null
    ],
    [
        'title' => 'Course Champion',
        'desc' => 'Complete an entire course',
        'icon' => 'ri-medal-fill',
        'unlocked' => $completed_courses > 0,
        'date' => $completed_courses > 0 ? date('Y-m-d') : null
    ]
];
?>

<div class="reveal-element">
    <div class="section-title-group" style="padding-bottom: 1.5rem; margin-bottom: 2rem; border-bottom: 1px solid rgba(255,255,255,0.05);">
        <div>
            <h1 style="font-size: 2rem; margin-bottom: 0.5rem;">My Achievements 🏆</h1>
            <p class="text-muted">Unlock badges as you progress your learning journey.</p>
        </div>
    </div>

    <div class="achievements-grid">
        <?php foreach ($achievements as $badge): ?>
            <div class="achievement-card reveal-element <?php echo !$badge['unlocked'] ? 'is-locked' : ''; ?>">
                <div class="achievement-icon-wrapper <?php echo $badge['unlocked'] ? 'unlocked' : 'locked'; ?>">
                    <i class="<?php echo $badge['unlocked'] ? $badge['icon'] : 'ri-lock-2-line'; ?>"></i>
                </div>
                
                <h3 class="achievement-title"><?php echo htmlspecialchars($badge['title']); ?></h3>
                <p class="achievement-desc"><?php echo htmlspecialchars($badge['desc']); ?></p>
                
                <?php if ($badge['unlocked']): ?>
                    <div class="achievement-status status-unlocked">
                        <i class="ri-check-double-line"></i> Unlocked <?php echo date('M d', strtotime($badge['date'])); ?>
                    </div>
                <?php else: ?>
                    <div class="achievement-status status-locked">
                        <i class="ri-lock-line"></i> Locked
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
