<?php
require_once __DIR__ . '/config.php';

/**
 * Check if a student is eligible for a certificate in a course
 * and generate it if they are.
 */
function checkAndGenerateCertificate($student_id, $course_id) {
    global $pdo;

    // 1. Check if already has certificate
    $stmt = $pdo->prepare("SELECT * FROM certificates WHERE student_id = ? AND course_id = ?");
    $stmt->execute([$student_id, $course_id]);
    $existing = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing) {
        return $existing; // Already issued
    }

    // 2. Check if all quizzes passed
    // Get all quizzes for this course
    $stmt_q = $pdo->prepare("SELECT id, passing_score FROM quizzes WHERE course_id = ?");
    $stmt_q->execute([$course_id]);
    $quizzes = $stmt_q->fetchAll(PDO::FETCH_ASSOC);

    foreach ($quizzes as $quiz) {
        $stmt_score = $pdo->prepare("SELECT MAX(score) as best_score FROM quiz_attempts WHERE quiz_id = ? AND student_id = ?");
        $stmt_score->execute([$quiz['id'], $student_id]);
        $score = $stmt_score->fetch(PDO::FETCH_ASSOC);

        if (!$score || $score['best_score'] === null || $score['best_score'] < $quiz['passing_score']) {
            return false; // Not passed all quizzes
        }
    }

    // 3. Check if all materials completed (Optional, depending on policy. Let's assume progress tracking is key)
    // For now, let's stick to Quiz based passing for simplicity, or we can check progress table.
    // Let's add material check:
    $stmt_m = $pdo->prepare("SELECT id FROM materials WHERE course_id = ?");
    $stmt_m->execute([$course_id]);
    $materials = $stmt_m->fetchAll(PDO::FETCH_ASSOC);
    
    // Check progress table
    foreach($materials as $mat) {
         $stmt_p = $pdo->prepare("SELECT status FROM student_material_progress WHERE material_id = ? AND student_id = ?");
         $stmt_p->execute([$mat['id'], $student_id]);
         $prog = $stmt_p->fetch(PDO::FETCH_ASSOC);
         
         // If material exists but no progress or not completed
         if (!$prog || $prog['status'] != 'completed') {
             // Strict mode: Must complete all materials
             // return false; 
             // Relaxed mode: Just quizzes are mandatory for certificate
         }
    }

    // If we reached here, eligible!
    return generateCertificate($student_id, $course_id);
}

function generateCertificate($student_id, $course_id) {
    global $pdo;
    
    $code = 'CERT-' . date('Ym') . '-' . strtoupper(substr(uniqid(), -5));
    
    $stmt = $pdo->prepare("
        INSERT INTO certificates (student_id, course_id, certificate_code)
        VALUES (?, ?, ?)
    ");
    
    try {
        $stmt->execute([$student_id, $course_id, $code]);
        return [
            'certificate_code' => $code,
            'issued_at' => date('Y-m-d H:i:s')
        ];
    } catch (PDOException $e) {
        return false;
    }
}

function getStudentCertificates($student_id) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT cert.*, c.course_name, c.course_code 
        FROM certificates cert
        JOIN courses c ON cert.course_id = c.id
        WHERE cert.student_id = ?
        ORDER BY cert.issued_at DESC
    ");
    $stmt->execute([$student_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
