<?php 
require_once '../../includes/config.php';
require_once '../../includes/auth.php';

requireLogin();
if ($_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo "<div style='padding:50px; text-align:center; font-family:sans-serif;'>
            <h1>Access Denied</h1>
            <p>You are logged in as <strong>" . htmlspecialchars($_SESSION['role']) . "</strong>, but this page is for Admins only.</p>
            <a href='../../logout.php'>Logout</a>
          </div>";
    exit;
}

$quiz_id = $_GET['id'] ?? 0;

// Fetch Quiz (Admin can edit any quiz)
$stmt = $pdo->prepare("
    SELECT q.*, c.course_name 
    FROM quizzes q
    JOIN courses c ON q.course_id = c.id
    WHERE q.id = ?
");
$stmt->execute([$quiz_id]);
$quiz = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$quiz) {
    die("Quiz not found.");
}

$message = '';
$messageType = '';

// Add Question
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_question'])) {
    $question_text = $_POST['question_text'];
    $points = $_POST['points'];
    
    // Options
    $options = [
        $_POST['option1'],
        $_POST['option2'],
        $_POST['option3'],
        $_POST['option4']
    ];
    $correct_index = (int)$_POST['correct_option'] - 1; // 0-based index

    try {
        $pdo->beginTransaction();
        
        // Insert Question
        $stmt = $pdo->prepare("INSERT INTO questions (quiz_id, question_text, points) VALUES (?, ?, ?)");
        $stmt->execute([$quiz_id, $question_text, $points]);
        $question_id = $pdo->lastInsertId();
        
        // Insert Options
        $stmt = $pdo->prepare("INSERT INTO question_options (question_id, option_text, is_correct) VALUES (?, ?, ?)");
        foreach ($options as $index => $opt_text) {
            $is_correct = ($index === $correct_index) ? 1 : 0;
            $stmt->execute([$question_id, $opt_text, $is_correct]);
        }
        
        $pdo->commit();
        $message = "Question added successfully!";
        $messageType = 'success';
    } catch (Exception $e) {
        $pdo->rollBack();
        $message = "Error adding question: " . $e->getMessage();
        $messageType = 'error';
    }
}

// Fetch Questions
$stmt = $pdo->prepare("SELECT * FROM questions WHERE quiz_id = ? ORDER BY id ASC");
$stmt->execute([$quiz_id]);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'includes/header.php';
?>

<div class="reveal-element">
    <div class="flex items-center gap-4 mb-6">
        <a href="manage_course.php?id=<?php echo $quiz['course_id']; ?>&tab=quizzes" class="btn btn-ghost"><i class="ri-arrow-left-line"></i> Back to Course</a>
        <div>
            <h1 class="text-3xl font-bold text-white"><?php echo htmlspecialchars($quiz['title']); ?> (Admin Edit)</h1>
            <p class="text-muted">Course: <?php echo htmlspecialchars($quiz['course_name']); ?></p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Add Question Form -->
        <div class="lg:col-span-1">
            <div class="card-premium p-6 sticky top-6">
                <h3 class="font-bold text-white mb-4">Add Question</h3>
                <?php if ($message): ?>
                    <div class="p-3 rounded mb-4 text-sm <?php echo $messageType == 'success' ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400'; ?>">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST">
                    <input type="hidden" name="add_question" value="1">
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-muted mb-2">Question Text</label>
                        <textarea name="question_text" class="w-full bg-black/20 border border-white/10 rounded-lg p-3 text-white focus:border-orange-500 outline-none" rows="3" required></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-muted mb-2">Points</label>
                        <input type="number" name="points" class="w-full bg-black/20 border border-white/10 rounded-lg p-3 text-white focus:border-orange-500 outline-none" value="10" required>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-muted mb-2">Options (Select Correct)</label>
                        <?php for($i=1; $i<=4; $i++): ?>
                            <div class="flex items-center gap-2 mb-2">
                                <input type="radio" name="correct_option" value="<?php echo $i; ?>" class="accent-orange-500" <?php echo $i==1?'checked':''; ?>>
                                <input type="text" name="option<?php echo $i; ?>" class="w-full bg-black/20 border border-white/10 rounded-lg p-2 text-white text-sm focus:border-orange-500 outline-none" placeholder="Option <?php echo $i; ?>" required>
                            </div>
                        <?php endfor; ?>
                    </div>
                    
                    <button type="submit" class="w-full bg-orange-600 hover:bg-orange-500 text-white py-2 rounded-lg font-bold shadow-lg shadow-orange-500/20 transition">Add Question</button>
                </form>
            </div>
        </div>

        <!-- Questions List -->
        <div class="lg:col-span-2">
             <div class="card-premium !p-0 overflow-hidden">
                <div class="p-4 border-b border-white/10 bg-white/5">
                    <h3 class="font-bold text-white m-0">Quiz Questions</h3>
                </div>
                
                <?php if (empty($questions)): ?>
                    <div class="p-12 text-center text-muted">
                        <p>No questions added yet.</p>
                    </div>
                <?php else: ?>
                    <div class="p-6">
                        <?php foreach ($questions as $index => $q): ?>
                            <div class="mb-6 last:mb-0 p-4 bg-white/5 border border-white/10 rounded-xl relative group">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-bold text-white text-lg pr-12">Q<?php echo $index + 1; ?>: <?php echo htmlspecialchars($q['question_text']); ?></h4>
                                    <span class="text-xs bg-white/10 px-2 py-1 rounded text-muted whitespace-nowrap"><?php echo $q['points']; ?> pts</span>
                                </div>
                                <button class="absolute top-4 right-4 text-red-400 opacity-0 group-hover:opacity-100 transition"><i class="ri-delete-bin-line"></i></button>
                                
                                <!-- Options Display -->
                                <?php 
                                $stmt_opt = $pdo->prepare("SELECT * FROM question_options WHERE question_id = ?");
                                $stmt_opt->execute([$q['id']]);
                                $opts = $stmt_opt->fetchAll(PDO::FETCH_ASSOC);
                                ?>
                                <ul class="pl-4 space-y-1">
                                    <?php foreach ($opts as $opt): ?>
                                        <li class="<?php echo $opt['is_correct'] ? 'text-green-400' : 'text-gray-500'; ?> text-sm flex items-center gap-2">
                                            <?php echo $opt['is_correct'] ? '<i class="ri-check-line"></i>' : '<i class="ri-checkbox-blank-circle-line text-[8px]"></i>'; ?>
                                            <?php echo htmlspecialchars($opt['option_text']); ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>
