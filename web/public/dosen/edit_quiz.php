<?php 
require_once '../../includes/config.php';
require_once '../../includes/auth.php';

requireLogin();
if ($_SESSION['role'] !== 'dosen') {
    header('Location: ../login.php');
    exit;
}

$quiz_id = $_GET['id'] ?? 0;
$dosen_id = $_SESSION['user_id'];
$dosen_name = $_SESSION['full_name'];

// Verify Quiz Ownership
$stmt = $pdo->prepare("
    SELECT q.*, c.course_name 
    FROM quizzes q
    JOIN courses c ON q.course_id = c.id
    WHERE q.id = ? AND c.created_by = ?
");
$stmt->execute([$quiz_id, $dosen_id]);
$quiz = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$quiz) {
    die("Quiz not found or access denied.");
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
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 2rem;">
                    <a href="quizzes.php" class="btn btn-ghost"><i class="ri-arrow-left-line"></i></a>
                    <div>
                        <h1 style="font-size: 1.75rem; font-weight: 700; color: white; margin: 0;"><?php echo htmlspecialchars($quiz['title']); ?></h1>
                        <p class="text-muted" style="margin: 0; font-size: 0.9rem;">Course: <?php echo htmlspecialchars($quiz['course_name']); ?></p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Add Question Form -->
                    <div class="lg:col-span-1">
                        <div style="background: rgba(30, 41, 59, 0.4); backdrop-filter: blur(12px); border-radius: 16px; padding: 1.5rem; border: 1px solid rgba(255,255,255,0.05); position: sticky; top: 1.5rem;">
                            <h3 style="font-weight: 700; color: white; margin-bottom: 1rem;">Add Question</h3>
                            <?php if ($message): ?>
                                <div class="p-3 rounded mb-4 text-sm <?php echo $messageType == 'success' ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400'; ?>">
                                    <?php echo $message; ?>
                                </div>
                            <?php endif; ?>
                            
                            <form method="POST">
                                <input type="hidden" name="add_question" value="1">
                                <div style="margin-bottom: 1rem;">
                                    <label class="form-label">Question Text</label>
                                    <textarea name="question_text" class="form-control" rows="3" required></textarea>
                                </div>
                                <div style="margin-bottom: 1rem;">
                                    <label class="form-label">Points</label>
                                    <input type="number" name="points" class="form-control" value="10" required>
                                </div>
                                
                                <div style="margin-bottom: 1rem;">
                                    <label class="form-label">Options (Select Correct)</label>
                                    <?php for($i=1; $i<=4; $i++): ?>
                                        <div class="option-row">
                                            <input type="radio" name="correct_option" value="<?php echo $i; ?>" class="option-radio" <?php echo $i==1?'checked':''; ?>>
                                            <input type="text" name="option<?php echo $i; ?>" class="form-control" placeholder="Option <?php echo $i; ?>" required>
                                        </div>
                                    <?php endfor; ?>
                                </div>
                                
                                <button type="submit" class="btn btn-primary" style="width: 100%;">Add Question</button>
                            </form>
                        </div>
                    </div>

                    <!-- Questions List -->
                    <div class="lg:col-span-2">
                         <div style="background: rgba(30, 41, 59, 0.4); backdrop-filter: blur(12px); border-radius: 16px; border: 1px solid rgba(255,255,255,0.05);">
                            <div style="padding: 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.05);">
                                <h3 style="color: white; font-weight: 700; margin: 0;">Quiz Questions</h3>
                            </div>
                            
                            <?php if (empty($questions)): ?>
                                <div style="padding: 3rem; text-align: center; color: var(--text-muted);">
                                    <p>No questions added yet.</p>
                                </div>
                            <?php else: ?>
                                <div style="padding: 1.5rem;">
                                    <?php foreach ($questions as $index => $q): ?>
                                        <div style="margin-bottom: 1.5rem; padding: 1rem; background: rgba(0,0,0,0.2); border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
                                            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                                <h4 style="font-weight: 600; color: white;">Q<?php echo $index + 1; ?>: <?php echo htmlspecialchars($q['question_text']); ?></h4>
                                                <span style="font-size: 0.8rem; background: rgba(255,255,255,0.1); padding: 0.25rem 0.5rem; border-radius: 4px;"><?php echo $q['points']; ?> pts</span>
                                            </div>
                                            
                                            <!-- Options Display -->
                                            <?php 
                                            $stmt_opt = $pdo->prepare("SELECT * FROM question_options WHERE question_id = ?");
                                            $stmt_opt->execute([$q['id']]);
                                            $opts = $stmt_opt->fetchAll(PDO::FETCH_ASSOC);
                                            ?>
                                            <ul style="padding-left: 1rem; margin: 0;">
                                                <?php foreach ($opts as $opt): ?>
                                                    <li style="color: <?php echo $opt['is_correct'] ? '#4ade80' : 'var(--text-muted)'; ?>; font-size: 0.9rem; margin-bottom: 0.25rem;">
                                                        <?php echo $opt['is_correct'] ? '<i class="ri-check-line"></i>' : '<i class="ri-checkbox-blank-circle-line" style="font-size: 0.5rem; vertical-align: middle;"></i>'; ?>
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
