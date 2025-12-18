<?php 
// Enable Error Reporting - TOP PRIORITY
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../includes/config.php';
require_once '../../includes/auth.php';
require_once '../../includes/quizzes.php';

// Auth Check
if (!isLoggedIn()) { header('Location: ../login.php'); exit; }

$attempt_id = $_GET['attempt_id'] ?? 0;
$result = getAttemptResult($attempt_id);

if (!$result || $result['student_id'] != $_SESSION['user_id']) {
    die("Result not found. Debug: attempt_id=$attempt_id, session_user=" . $_SESSION['user_id']);
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
    
    <!-- Tailwind CSS (Required) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        dark: '#0f172a',
                        primary: '#0ea5e9',
                        secondary: '#6366f1',
                    }
                }
            }
        }
    </script>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <!-- CSS -->
    <link rel="stylesheet" href="../assets/css/main.css">
</head>
<body class="flex items-center justify-center min-h-screen relative overflow-hidden" style="background: var(--bg-dark);">
    
    <!-- Background Decor -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0 pointer-events-none">
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-blue-600/20 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-purple-600/20 rounded-full blur-[120px]"></div>
    </div>

    <div class="card-premium p-8 relative z-10 w-full max-w-md text-center transform transition-all duration-300 hover:scale-[1.01]">
        
        <?php if ($passed): ?>
            <!-- Success State -->
            <div class="w-24 h-24 rounded-full bg-gradient-to-br from-green-400 to-emerald-600 p-[2px] mx-auto mb-6 shadow-xl shadow-green-500/20">
                <div class="w-full h-full bg-black/40 rounded-full backdrop-blur-sm flex items-center justify-center">
                    <i class="ri-trophy-fill text-4xl text-green-400"></i>
                </div>
            </div>
            
            <h1 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-green-300 to-emerald-400 mb-2">Excellent Work!</h1>
            <p class="text-gray-400 mb-8">You have successfully mastered this material.</p>
        <?php else: ?>
            <!-- Fail State -->
            <div class="w-24 h-24 rounded-full bg-gradient-to-br from-red-400 to-pink-600 p-[2px] mx-auto mb-6 shadow-xl shadow-red-500/20">
                <div class="w-full h-full bg-black/40 rounded-full backdrop-blur-sm flex items-center justify-center">
                    <i class="ri-close-circle-fill text-4xl text-red-400"></i>
                </div>
            </div>
            
            <h1 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-red-300 to-pink-400 mb-2">Keep Trying</h1>
            <p class="text-gray-400 mb-8">Don't give up! Review the material and try again.</p>
        <?php endif; ?>

        <!-- Stat Grid -->
        <div class="grid grid-cols-2 gap-4 mb-8">
            <div class="bg-white/5 border border-white/10 rounded-2xl p-4 flex flex-col items-center justify-center group hover:bg-white/10 transition">
                <span class="text-xs font-bold uppercase tracking-widest text-muted mb-1">Your Score</span>
                <span class="text-3xl font-black <?php echo $passed ? 'text-white' : 'text-red-300'; ?>">
                    <?php echo number_format($score, 0); ?><span class="text-lg text-muted font-normal ml-0.5">%</span>
                </span>
            </div>
            <div class="bg-white/5 border border-white/10 rounded-2xl p-4 flex flex-col items-center justify-center group hover:bg-white/10 transition">
                <span class="text-xs font-bold uppercase tracking-widest text-muted mb-1">Passing Grade</span>
                <span class="text-3xl font-black text-gray-300">
                    <?php echo $result['passing_score']; ?><span class="text-lg text-muted font-normal ml-0.5">%</span>
                </span>
            </div>
        </div>

        <div class="space-y-3">
            <a href="quizzes.php" class="block w-full py-3.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold shadow-lg shadow-blue-500/25 hover:shadow-blue-500/40 transform hover:-translate-y-0.5 transition-all duration-200">
                Back to Quizzes
            </a>
            <a href="../courses.php" class="block w-full py-3.5 rounded-xl border border-white/10 text-gray-400 font-medium hover:bg-white/5 hover:text-white transition-all duration-200">
                Go to Dashboard
            </a>
        </div>
    </div>

</body>
</html>
