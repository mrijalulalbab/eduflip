<?php
require_once 'config.php';

// Fetch quizzes available for a student (based on enrolled courses)
function getStudentQuizzes($student_id) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT q.*, c.course_name, c.course_code,
        (SELECT MAX(score) FROM quiz_attempts WHERE quiz_id = q.id AND student_id = ?) as best_score,
        (SELECT status FROM quiz_attempts WHERE quiz_id = q.id AND student_id = ? ORDER BY started_at DESC LIMIT 1) as latest_status
        FROM quizzes q
        JOIN course_enrollments e ON q.course_id = e.course_id
        JOIN courses c ON q.course_id = c.id
        WHERE e.student_id = ?
        ORDER BY q.created_at DESC
    ");
    $stmt->execute([$student_id, $student_id, $student_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get single quiz details
function getQuiz($quiz_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM quizzes WHERE id = ?");
    $stmt->execute([$quiz_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Get questions for a quiz
function getQuizQuestions($quiz_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM questions WHERE quiz_id = ? ORDER BY order_num ASC");
    $stmt->execute([$quiz_id]);
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Attach options to questions
    foreach ($questions as &$q) {
        $stmt_opt = $pdo->prepare("SELECT id, option_text FROM question_options WHERE question_id = ? ORDER BY order_num ASC");
        $stmt_opt->execute([$q['id']]);
        $q['options'] = $stmt_opt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    return $questions;
}

// Start a quiz attempt
function startQuizAttempt($student_id, $quiz_id) {
    global $pdo;
    
    // Calculate total points
    $stmt = $pdo->prepare("SELECT SUM(points) as total FROM questions WHERE quiz_id = ?");
    $stmt->execute([$quiz_id]);
    $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
    
    $stmt = $pdo->prepare("
        INSERT INTO quiz_attempts (quiz_id, student_id, total_points, started_at, status)
        VALUES (?, ?, ?, NOW(), 'in_progress')
    ");
    $stmt->execute([$quiz_id, $student_id, $total]);
    return $pdo->lastInsertId();
}

// Submit quiz answers
function submitQuiz($attempt_id, $answers) {
    global $pdo;
    
    // Get attempt details
    $stmt = $pdo->prepare("SELECT * FROM quiz_attempts WHERE id = ?");
    $stmt->execute([$attempt_id]);
    $attempt = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$attempt) return false;
    
    $earned_points = 0;
    
    // Process each answer
    foreach ($answers as $question_id => $selected_option_id) {
        // Validation: verify question belongs to quiz
        // Get correct option
        $stmt_q = $pdo->prepare("SELECT * FROM questions WHERE id = ?");
        $stmt_q->execute([$question_id]);
        $question = $stmt_q->fetch(PDO::FETCH_ASSOC);
        
        $stmt_opt = $pdo->prepare("SELECT * FROM question_options WHERE id = ?");
        $stmt_opt->execute([$selected_option_id]);
        $option = $stmt_opt->fetch(PDO::FETCH_ASSOC);
        
        $is_correct = ($option && $option['is_correct']) ? 1 : 0;
        $points = $is_correct ? $question['points'] : 0;
        
        $earned_points += $points;
        
        // Save answer
        $stmt_save = $pdo->prepare("
            INSERT INTO student_answers (attempt_id, question_id, selected_option_id, is_correct, points_earned)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt_save->execute([$attempt_id, $question_id, $selected_option_id, $is_correct, $points]);
    }
    
    // Calculate Score (Percent)
    $score_percent = ($attempt['total_points'] > 0) ? ($earned_points / $attempt['total_points']) * 100 : 0;
    
    // Update attempt
    $stmt_update = $pdo->prepare("
        UPDATE quiz_attempts 
        SET earned_points = ?, score = ?, status = 'completed', submitted_at = NOW() 
        WHERE id = ?
    ");
    $stmt_update->execute([$earned_points, $score_percent, $attempt_id]);
    
    return true;
}

// Get attempt results
function getAttemptResult($attempt_id) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT qa.*, q.title as quiz_title, q.passing_score
        FROM quiz_attempts qa
        JOIN quizzes q ON qa.quiz_id = q.id
        WHERE qa.id = ?
    ");
    $stmt->execute([$attempt_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
