<?php 
require_once '../../includes/config.php';
require_once '../../includes/auth.php';
require_once '../../includes/quizzes.php';

// Auth Check
if (!isLoggedIn()) { header('Location: ../login.php'); exit; }

$attempt_id = $_GET['attempt_id'] ?? 0;
$result = getAttemptResult($attempt_id);

if (!$result || $result['student_id'] != $_SESSION['user_id']) {
    die("Result not found.");
}

$score = $result['score'];
$passed = $score >= $result['passing_score'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Result: <?php echo htmlspecialchars($result['quiz_title']); ?></title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <!-- CSS -->
    <link rel="stylesheet" href="../assets/css/main.css">
</head>
<body class="flex items-center justify-center min-h-screen" style="background: var(--bg-dark); padding: 2rem;">

<div class="card p-5 text-center reveal-element" style="max-width: 500px; width: 100%;">
    <div class="mb-4">
        <?php if ($passed): ?>
            <div class="w-20 h-20 rounded-full bg-green-500/10 text-green-500 flex items-center justify-center text-4xl mx-auto mb-3">
                <i class="ri-trophy-line"></i>
            </div>
            <h1 class="text-3xl font-bold text-success">Congratulations!</h1>
            <p class="text-muted">You passed the assessment.</p>
        <?php else: ?>
             <div class="w-20 h-20 rounded-full bg-red-500/10 text-red-500 flex items-center justify-center text-4xl mx-auto mb-3">
                <i class="ri-close-circle-line"></i>
            </div>
            <h1 class="text-3xl font-bold text-danger">Try Again</h1>
            <p class="text-muted">You didn't reach the passing score.</p>
        <?php endif; ?>
    </div>

    <div class="bg-white/5 rounded-lg p-4 mb-5 grid grid-cols-2 gap-4">
        <div>
            <span class="block text-muted text-sm">Your Score</span>
            <span class="block text-2xl font-bold"><?php echo number_format($score, 0); ?>%</span>
        </div>
        <div>
            <span class="block text-muted text-sm">Passing Score</span>
            <span class="block text-2xl font-bold"><?php echo $result['passing_score']; ?>%</span>
        </div>
    </div>

    <a href="quizzes.php" class="btn btn-primary w-full">Back to Quizzes</a>
</div>

</body>
</html>
