<?php
require_once 'web/includes/config.php';

try {
    echo "Seeding Quiz Data...\n";
    
    // 1. Create a dummy test account if not exists for the instructor
    // We assume ID 1 exists (admin/dosen). If not, we'll use the first available user.
    $instructor_id = 1;

    // 2. Create the Quiz
    echo "Creating Quiz...\n";
    $stmt = $pdo->prepare("INSERT INTO quizzes (course_id, title, description, duration, passing_score, created_by, created_at) 
                          VALUES (?, ?, ?, ?, ?, ?, NOW())");
    
    // Assuming Course ID 1 exists (from previous setup). If not, create a dummy course.
    // Check if course 1 exists
    $c_check = $pdo->query("SELECT id FROM courses LIMIT 1")->fetch();
    if (!$c_check) {
        $pdo->exec("INSERT INTO courses (course_code, course_name, description, created_by, status) 
                   VALUES ('WEB101', 'Web Development Fundamentals', 'Learn the basics.', $instructor_id, 'published')");
        $course_id = $pdo->lastInsertId();
    } else {
        $course_id = $c_check['id'];
    }

    $stmt->execute([
        $course_id,
        "Web Dev Basics Quiz",
        "Test your knowledge of HTML, CSS, and general web concepts.",
        30, // 30 minutes
        70, // 70% passing
        $instructor_id
    ]);
    
    $quiz_id = $pdo->lastInsertId();
    echo "Quiz Created: ID $quiz_id\n";

    // 3. Create Questions
    echo "Adding Questions...\n";
    $questions = [
        [
            'text' => 'What does HTML stand for?',
            'type' => 'mcq_single',
            'points' => 10,
            'options' => [
                ['text' => 'Hyper Text Markup Language', 'correct' => 1],
                ['text' => 'Home Tool Markup Language', 'correct' => 0],
                ['text' => 'Hyperlinks and Text Markup Language', 'correct' => 0]
            ]
        ],
        [
            'text' => 'Which tag is used for the largest heading in HTML?',
            'type' => 'mcq_single',
            'points' => 10,
            'options' => [
                ['text' => '<h6>', 'correct' => 0],
                ['text' => '<h1>', 'correct' => 1],
                ['text' => '<head>', 'correct' => 0]
            ]
        ],
        [
            'text' => 'CSS stands for Cascading Style Sheets.',
            'type' => 'true_false', // simplified as mcq_single in UI
            'points' => 10,
            'options' => [
                ['text' => 'True', 'correct' => 1],
                ['text' => 'False', 'correct' => 0]
            ]
        ]
    ];

    foreach ($questions as $i => $q) {
        $stmt_q = $pdo->prepare("INSERT INTO questions (quiz_id, question_text, question_type, points, order_num) VALUES (?, ?, ?, ?, ?)");
        $stmt_q->execute([$quiz_id, $q['text'], $q['type'], $q['points'], $i + 1]);
        $question_id = $pdo->lastInsertId();
        
        foreach ($q['options'] as $opt) {
            $stmt_o = $pdo->prepare("INSERT INTO question_options (question_id, option_text, is_correct) VALUES (?, ?, ?)");
            $stmt_o->execute([$question_id, $opt['text'], $opt['correct']]);
        }
    }

    echo "Seeding Completed Successfully!\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
