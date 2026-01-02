<?php
/**
 * Gamification System - Learning Streaks & Badges
 * 
 * This module handles:
 * - Daily learning activity tracking
 * - Streak calculation (consecutive days)
 * - Badge/achievement awarding
 */

require_once __DIR__ . '/config.php';

// ========================================
// BADGE DEFINITIONS
// ========================================

function getBadgeDefinitions() {
    return [
        'first_login' => [
            'name' => 'First Steps',
            'description' => 'Complete your first login',
            'icon' => 'ri-rocket-line',
            'color' => '#22c55e'
        ],
        'first_material' => [
            'name' => 'Curious Mind',
            'description' => 'View your first learning material',
            'icon' => 'ri-book-open-line',
            'color' => '#3b82f6'
        ],
        'material_5' => [
            'name' => 'Knowledge Seeker',
            'description' => 'Complete 5 learning materials',
            'icon' => 'ri-bookmark-3-line',
            'color' => '#8b5cf6'
        ],
        'material_20' => [
            'name' => 'Scholar',
            'description' => 'Complete 20 learning materials',
            'icon' => 'ri-graduation-cap-line',
            'color' => '#f59e0b'
        ],
        'quiz_pass_1' => [
            'name' => 'Quiz Crusher',
            'description' => 'Pass your first quiz',
            'icon' => 'ri-checkbox-circle-line',
            'color' => '#10b981'
        ],
        'quiz_pass_10' => [
            'name' => 'Quiz Master',
            'description' => 'Pass 10 quizzes',
            'icon' => 'ri-trophy-line',
            'color' => '#eab308'
        ],
        'streak_7' => [
            'name' => 'Week Warrior',
            'description' => '7-day learning streak',
            'icon' => 'ri-fire-line',
            'color' => '#ef4444'
        ],
        'streak_30' => [
            'name' => 'Dedicated Learner',
            'description' => '30-day learning streak',
            'icon' => 'ri-medal-line',
            'color' => '#f97316'
        ]
    ];
}

// ========================================
// ACTIVITY TRACKING
// ========================================

/**
 * Record daily learning activity
 * @param int $studentId
 * @param string $type - 'material', 'quiz', 'forum'
 */
function recordDailyActivity($studentId, $type) {
    global $pdo;
    
    $today = date('Y-m-d');
    
    // Check if record exists for today
    $stmt = $pdo->prepare("SELECT id, materials_viewed, quizzes_taken, forum_posts FROM learning_activity WHERE student_id = ? AND activity_date = ?");
    $stmt->execute([$studentId, $today]);
    $existing = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($existing) {
        // Update existing record
        $column = match($type) {
            'material' => 'materials_viewed',
            'quiz' => 'quizzes_taken',
            'forum' => 'forum_posts',
            default => 'materials_viewed'
        };
        
        $stmt = $pdo->prepare("UPDATE learning_activity SET $column = $column + 1 WHERE id = ?");
        $stmt->execute([$existing['id']]);
    } else {
        // Create new record for today
        $materials = ($type === 'material') ? 1 : 0;
        $quizzes = ($type === 'quiz') ? 1 : 0;
        $forums = ($type === 'forum') ? 1 : 0;
        
        $stmt = $pdo->prepare("INSERT INTO learning_activity (student_id, activity_date, materials_viewed, quizzes_taken, forum_posts) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$studentId, $today, $materials, $quizzes, $forums]);
    }
    
    // Check and award badges after activity
    checkAndAwardBadges($studentId);
}

// ========================================
// STREAK CALCULATION
// ========================================

/**
 * Calculate current learning streak (consecutive days)
 * @param int $studentId
 * @return int Number of consecutive days
 */
function calculateStreak($studentId) {
    global $pdo;
    
    // Get all activity dates, ordered descending
    $stmt = $pdo->prepare("
        SELECT activity_date 
        FROM learning_activity 
        WHERE student_id = ? 
        ORDER BY activity_date DESC
    ");
    $stmt->execute([$studentId]);
    $dates = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (empty($dates)) {
        return 0;
    }
    
    $streak = 0;
    $today = new DateTime();
    $yesterday = (clone $today)->modify('-1 day');
    
    // Check if most recent activity is today or yesterday
    $lastActivity = new DateTime($dates[0]);
    
    if ($lastActivity->format('Y-m-d') !== $today->format('Y-m-d') && 
        $lastActivity->format('Y-m-d') !== $yesterday->format('Y-m-d')) {
        return 0; // Streak broken
    }
    
    // Count consecutive days
    $expectedDate = $lastActivity;
    foreach ($dates as $dateStr) {
        $date = new DateTime($dateStr);
        
        if ($date->format('Y-m-d') === $expectedDate->format('Y-m-d')) {
            $streak++;
            $expectedDate->modify('-1 day');
        } else {
            break; // Gap found, streak ends
        }
    }
    
    return $streak;
}

// ========================================
// BADGE AWARDING
// ========================================

/**
 * Check conditions and award badges
 * @param int $studentId
 */
function checkAndAwardBadges($studentId) {
    global $pdo;
    
    // Get current stats
    $stats = getStudentStats($studentId);
    $earnedBadges = getStudentBadges($studentId);
    $earnedCodes = array_column($earnedBadges, 'badge_code');
    
    $badgesToAward = [];
    
    // Check each badge condition
    if (!in_array('first_login', $earnedCodes)) {
        $badgesToAward[] = 'first_login'; // Always award on first check
    }
    
    if (!in_array('first_material', $earnedCodes) && $stats['materials_completed'] >= 1) {
        $badgesToAward[] = 'first_material';
    }
    
    if (!in_array('material_5', $earnedCodes) && $stats['materials_completed'] >= 5) {
        $badgesToAward[] = 'material_5';
    }
    
    if (!in_array('material_20', $earnedCodes) && $stats['materials_completed'] >= 20) {
        $badgesToAward[] = 'material_20';
    }
    
    if (!in_array('quiz_pass_1', $earnedCodes) && $stats['quizzes_passed'] >= 1) {
        $badgesToAward[] = 'quiz_pass_1';
    }
    
    if (!in_array('quiz_pass_10', $earnedCodes) && $stats['quizzes_passed'] >= 10) {
        $badgesToAward[] = 'quiz_pass_10';
    }
    
    $streak = calculateStreak($studentId);
    
    if (!in_array('streak_7', $earnedCodes) && $streak >= 7) {
        $badgesToAward[] = 'streak_7';
    }
    
    if (!in_array('streak_30', $earnedCodes) && $streak >= 30) {
        $badgesToAward[] = 'streak_30';
    }
    
    // Award badges
    foreach ($badgesToAward as $badgeCode) {
        awardBadge($studentId, $badgeCode);
    }
}

/**
 * Award a badge to student
 */
function awardBadge($studentId, $badgeCode) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("INSERT IGNORE INTO student_badges (student_id, badge_code) VALUES (?, ?)");
        $stmt->execute([$studentId, $badgeCode]);
    } catch (PDOException $e) {
        // Badge already exists, ignore
    }
}

/**
 * Get all badges earned by student
 */
function getStudentBadges($studentId) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT badge_code, earned_at FROM student_badges WHERE student_id = ? ORDER BY earned_at DESC");
    $stmt->execute([$studentId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Get student statistics for badge checking
 */
function getStudentStats($studentId) {
    global $pdo;
    
    // Materials completed
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM student_material_progress WHERE student_id = ? AND status = 'completed'");
    $stmt->execute([$studentId]);
    $materialsCompleted = $stmt->fetchColumn();
    
    // Quizzes passed
    $stmt = $pdo->prepare("
        SELECT COUNT(*) FROM quiz_attempts qa
        JOIN quizzes q ON qa.quiz_id = q.id
        WHERE qa.student_id = ? AND qa.status = 'completed' AND qa.score >= q.passing_score
    ");
    $stmt->execute([$studentId]);
    $quizzesPassed = $stmt->fetchColumn();
    
    return [
        'materials_completed' => (int) $materialsCompleted,
        'quizzes_passed' => (int) $quizzesPassed
    ];
}

/**
 * Get gamification data for dashboard display
 */
function getGamificationData($studentId) {
    return [
        'streak' => calculateStreak($studentId),
        'badges' => getStudentBadges($studentId),
        'stats' => getStudentStats($studentId),
        'badge_definitions' => getBadgeDefinitions()
    ];
}
?>
