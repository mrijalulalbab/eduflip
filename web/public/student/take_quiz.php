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

// --- 1. Check Previous Attempts & Remedial Logic ---
$stmt = $pdo->prepare("SELECT MAX(score) as best_score FROM quiz_attempts WHERE quiz_id = ? AND student_id = ?");
$stmt->execute([$quiz_id, $_SESSION['user_id']]);
$best_score = $stmt->fetchColumn();

// If passed, show "Passed" screen with optional retake if policy allows (assuming always allow retake for now to improve score)
$already_passed = ($best_score !== false && $best_score >= $quiz['passing_score']);

// --- 2. Timer Logic (Session Based) ---
$session_key = 'quiz_start_' . $quiz_id;
$duration_seconds = $quiz['duration'] * 60;

// Reset timer if requested (e.g. Remedial or New Attempt)
if (isset($_GET['retake']) && $_GET['retake'] == 'true') {
    unset($_SESSION[$session_key]);
    header("Location: take_quiz.php?id=$quiz_id");
    exit;
}

if (!isset($_SESSION[$session_key])) {
    $_SESSION[$session_key] = time();
}

$elapsed = time() - $_SESSION[$session_key];
$remaining_seconds = max(0, $duration_seconds - $elapsed);

// Auto-submit if time expired (server-side check)
$time_expired = ($remaining_seconds <= 0);


// Enable Error Reporting for Debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Handle Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Clear timer session on submit
        unset($_SESSION[$session_key]);

        $attempt_id = startQuizAttempt($_SESSION['user_id'], $quiz_id);
        $answers = $_POST['answers'] ?? []; // Array of question_id => option_id
        
        if (submitQuiz($attempt_id, $answers)) {
            header("Location: quiz_result.php?attempt_id=" . $attempt_id);
            exit;
        } else {
            $error = "Failed to submit quiz logic returned false.";
        }
    } catch (Throwable $e) {
        $error = "System Error: " . $e->getMessage();
    }
}

// Fetch Questions
$questions = getQuizQuestions($quiz_id);

// --- 3. Randomization Logic ---
if (!empty($questions)) {
    shuffle($questions); // Randomize Question Order
    
    foreach ($questions as &$q) {
        if (!empty($q['options'])) {
            shuffle($q['options']); // Randomize Option Order
        }
    }
}
unset($q); // Break reference
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
        
        .timer-badge {
            background: rgba(234, 88, 12, 0.1);
            color: #ea580c;
            border: 1px solid rgba(234, 88, 12, 0.3);
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-family: monospace;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .timer-badge.urgent {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border-color: rgba(239, 68, 68, 0.3);
            animation: pulse 1s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
    </style>
</head>
<body>

<div class="quiz-container">

    <!-- Passed State check -->
    <?php if ($already_passed && !isset($_GET['retake'])): ?>
        <div class="card p-8 text-center reveal-element">
            <div style="font-size: 4rem; color: #22c55e; margin-bottom: 1rem;"><i class="ri-checkbox-circle-fill"></i></div>
            <h1 class="text-2xl font-bold mb-2">You have passed this quiz!</h1>
            <p class="text-muted mb-6">Your best score: <strong class="text-green-400"><?php echo $best_score; ?>/100</strong></p>
            
            <div class="flex justify-center gap-4">
                <a href="index.php" class="btn btn-ghost">Back to Dashboard</a>
                <a href="take_quiz.php?id=<?php echo $quiz_id; ?>&retake=true" class="btn btn-primary">Retake to Improve Score</a>
            </div>
        </div>
        <?php exit; ?>
    <?php endif; ?>


    <?php if (isset($error)): ?>
        <div class="bg-red-500/10 border border-red-500/20 text-red-500 p-4 rounded-lg mb-6">
            <h3 class="font-bold mb-1"><i class="ri-error-warning-line"></i> Error Occurred</h3>
            <p><?php echo htmlspecialchars($error); ?></p>
        </div>
    <?php endif; ?>

    <form method="POST" id="quizForm">
        <div style="position: sticky; top: 1rem; z-index: 50; background: rgba(15, 23, 42, 0.9); backdrop-filter: blur(8px); padding: 1rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05); margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
            <div>
                <h1 class="text-lg font-bold truncate" style="max-width: 300px;"><?php echo htmlspecialchars($quiz['title']); ?></h1>
                <p class="text-xs text-muted">Total Questions: <?php echo count($questions); ?></p>
            </div>
            <div>
                 <div id="timerBadge" class="timer-badge">
                    <i class="ri-timer-line"></i> <span id="timerDisplay">--:--</span>
                 </div>
            </div>
        </div>

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
            <a href="index.php" class="btn btn-ghost" onclick="return confirm('Are you sure? Progress will be lost.');">Cancel</a>
            <button type="submit" class="btn btn-primary btn-lg">Submit Assessment</button>
        </div>
    </form>
</div>

<script>
// Timer Logic
let remainingSeconds = <?php echo $remaining_seconds; ?>;
const timerDisplay = document.getElementById('timerDisplay');
const timerBadge = document.getElementById('timerBadge');
const quizForm = document.getElementById('quizForm');

function updateTimer() {
    if (remainingSeconds <= 0) {
        clearInterval(timerInterval);
        timerDisplay.innerText = "00:00";
        alert("Time is up! Submitting your answers automatically.");
        quizForm.submit();
        return;
    }

    const minutes = Math.floor(remainingSeconds / 60);
    const seconds = remainingSeconds % 60;
    
    timerDisplay.innerText = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    
    // Urgent styling
    if (remainingSeconds < 60) {
        timerBadge.classList.add('urgent');
    }

    remainingSeconds--;
}

// Start Timer
const timerInterval = setInterval(updateTimer, 1000);
updateTimer(); // Initial call

// Prevent accidental navigation
window.onbeforeunload = function() {
    return "Are you sure you want to leave? Your progress will be lost.";
};

// Allow submit without warning
quizForm.onsubmit = function() {
    window.onbeforeunload = null;
};
</script>

</body>
</html>
