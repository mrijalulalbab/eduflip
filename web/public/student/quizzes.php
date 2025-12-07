<?php 
require_once '../../includes/config.php';
require_once '../../includes/auth.php';
require_once '../../includes/quizzes.php';

// Auth Check
if (!isLoggedIn() || $_SESSION['role'] !== 'mahasiswa') {
    header('Location: ../login.php');
    exit;
}

$quizzes = getStudentQuizzes($_SESSION['user_id']);

include 'includes/header.php'; 
?>

<!-- Title Section -->
<div class="section-title-group reveal-element">
    <div>
        <h1 style="font-size: 2rem; margin-bottom: 0.5rem;">My Quizzes 📝</h1>
        <p class="text-muted">Take assessments to test your knowledge.</p>
    </div>
</div>

<!-- Stats -->
<div class="stats-container reveal-element" style="margin-bottom: 3rem;">
    <div class="stat-card">
        <div class="stat-icon blue">
            <i class="ri-file-list-3-line"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo count($quizzes); ?></h3>
            <p>Total Quizzes</p>
        </div>
    </div>
    
     <div class="stat-card">
        <div class="stat-icon green">
            <i class="ri-check-double-line"></i>
        </div>
        <div class="stat-info">
             <?php
            $passed = array_filter($quizzes, function($q) {
                return $q['latest_status'] == 'completed' && $q['best_score'] >= $q['passing_score'];
            });
            ?>
            <h3><?php echo count($passed); ?></h3>
            <p>Passed</p>
        </div>
    </div>
</div>

<!-- Quiz Grid -->
<div class="reveal-element">
    <?php if (empty($quizzes)): ?>
        <div class="empty-state">
            <div style="width: 80px; height: 80px; background: rgba(255,255,255,0.05); border-radius: 50%; margin: 0 auto 1.5rem; display: flex; align-items: center; justify-content: center; color: var(--text-muted); font-size: 2rem;">
                <i class="ri-task-line"></i>
            </div>
            <h3 style="color: var(--text-main); margin-bottom: 0.5rem;">No Quizzes Available</h3>
            <p class="text-muted">Your instructors haven't posted any quizzes yet.</p>
        </div>
    <?php else: ?>
        <div class="course-grid">
            <?php foreach ($quizzes as $quiz): ?>
                <div class="dashboard-course-card" style="padding: 1.5rem;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                        <span class="card-badge"><?php echo htmlspecialchars($quiz['course_code']); ?></span>
                        <?php if ($quiz['latest_status'] == 'completed'): ?>
                            <?php if ($quiz['best_score'] >= $quiz['passing_score']): ?>
                                <span style="color: var(--success); font-weight: 700; font-size: 0.9rem;"><i class="ri-checkbox-circle-line"></i> Passed (<?php echo number_format($quiz['best_score'],0); ?>%)</span>
                            <?php else: ?>
                                <span style="color: var(--danger); font-weight: 700; font-size: 0.9rem;"><i class="ri-close-circle-line"></i> Failed (<?php echo number_format($quiz['best_score'],0); ?>%)</span>
                            <?php endif; ?>
                        <?php else: ?>
                            <span style="color: var(--warning); font-weight: 700; font-size: 0.9rem;"><i class="ri-time-line"></i> Pending</span>
                        <?php endif; ?>
                    </div>
                    
                    <h3 class="card-title" style="margin-bottom: 0.5rem;"><?php echo htmlspecialchars($quiz['title']); ?></h3>
                    <p class="text-muted" style="font-size: 0.9rem; margin-bottom: 1.5rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;"><?php echo htmlspecialchars($quiz['description']); ?></p>
                    
                    <div class="flex items-center gap-4 text-muted" style="margin-bottom: 1.5rem; font-size: 0.9rem;">
                        <span><i class="ri-timer-line"></i> <?php echo $quiz['duration'] > 0 ? $quiz['duration'].' min' : 'Unlimited'; ?></span>
                        <span><i class="ri-question-answer-line"></i> Questions</span>
                    </div>
                    
                    <a href="take_quiz.php?id=<?php echo $quiz['id']; ?>" class="btn btn-primary" style="width: 100%;">
                        <?php echo ($quiz['latest_status'] == 'completed') ? 'Retake Quiz' : 'Start Quiz'; ?>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
