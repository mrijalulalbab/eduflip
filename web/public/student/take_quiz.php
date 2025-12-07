<?php 
require_once '../../includes/config.php';
require_once '../../includes/auth.php';
require_once '../../includes/quizzes.php';

// Auth Check
if (!isLoggedIn() || $_SESSION['role'] !== 'mahasiswa') {
    header('Location: ../login.php');
    exit;
}

$quiz_id = $_GET['id'] ?? 0;
$quiz = getQuiz($quiz_id);

if (!$quiz) {
    die("Quiz not found.");
}

// Handle Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $attempt_id = startQuizAttempt($_SESSION['user_id'], $quiz_id);
    $answers = $_POST['answers'] ?? []; // Array of question_id => option_id
    
    if (submitQuiz($attempt_id, $answers)) {
        header("Location: quiz_result.php?attempt_id=" . $attempt_id);
        exit;
    } else {
        $error = "Failed to submit quiz.";
    }
}

$questions = getQuizQuestions($quiz_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam: <?php echo htmlspecialchars($quiz['title']); ?></title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <!-- CSS -->
    <link rel="stylesheet" href="../assets/css/main.css"> <!-- Base styles -->
    <style>
        body { background: var(--bg-dark); }
        .quiz-container { max-width: 800px; margin: 0 auto; padding: 2rem; }
        .question-card {
            background: var(--bg-card);
            border: 1px solid rgba(255,255,255,0.05);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .option-label {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 8px;
            margin-bottom: 0.5rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        .option-label:hover { background: rgba(255,255,255,0.05); }
        input[type="radio"]:checked + span { color: var(--secondary); font-weight: bold; }
    </style>
</head>
<body>

<div class="quiz-container">
    <div class="flex justify-between items-center mb-5">
        <div>
            <h1 class="text-2xl font-bold mb-1"><?php echo htmlspecialchars($quiz['title']); ?></h1>
            <p class="text-muted">Total Questions: <?php echo count($questions); ?></p>
        </div>
        <div class="text-right">
             <!-- Timer placeholder -->
             <div class="text-xl font-mono text-secondary"><i class="ri-timer-line"></i> <span id="timer">00:00</span></div>
        </div>
    </div>

    <form method="POST">
        <?php foreach ($questions as $index => $q): ?>
            <div class="question-card">
                <h3 class="text-lg mb-4"><span class="text-muted mr-2"><?php echo $index + 1; ?>.</span> <?php echo htmlspecialchars($q['question_text']); ?></h3>
                
                <div class="options-list">
                    <?php foreach ($q['options'] as $opt): ?>
                        <label class="option-label">
                            <input type="radio" name="answers[<?php echo $q['id']; ?>]" value="<?php echo $opt['id']; ?>" required>
                            <span><?php echo htmlspecialchars($opt['option_text']); ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="flex justify-end gap-3 mt-5 pb-10">
            <a href="quizzes.php" class="btn btn-ghost">Cancel</a>
            <button type="submit" class="btn btn-primary btn-lg">Submit Assessment</button>
        </div>
    </form>
</div>

</body>
</html>
