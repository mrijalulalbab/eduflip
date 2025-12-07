<?php 
require_once '../../includes/config.php';
require_once '../../includes/auth.php';

requireLogin();
if ($_SESSION['role'] !== 'dosen') {
    header('Location: ../login.php');
    exit;
}

$dosen_name = $_SESSION['full_name'];
$course_id = $_GET['course_id'] ?? 0;

// Verify course ownership if course_id is provided
if ($course_id) {
    $stmt = $pdo->prepare("SELECT id FROM courses WHERE id = ? AND created_by = ?");
    $stmt->execute([$course_id, $_SESSION['user_id']]);
    if (!$stmt->fetch()) {
        die("Access denied.");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $duration = $_POST['duration'];
    $passing_score = $_POST['passing_score'];
    $c_id = $_POST['course_id'];

    $stmt = $pdo->prepare("
        INSERT INTO quizzes (course_id, title, description, duration, passing_score, created_by)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    
    if ($stmt->execute([$c_id, $title, $description, $duration, $passing_score, $_SESSION['user_id']])) {
        $new_quiz_id = $pdo->lastInsertId();
        header("Location: edit_quiz.php?id=" . $new_quiz_id);
        exit;
    } else {
        $error = "Failed to create quiz.";
    }
}

// Fetch courses for dropdown
$stmt = $pdo->prepare("SELECT id, course_name FROM courses WHERE created_by = ?");
$stmt->execute([$_SESSION['user_id']]);
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'includes/header.php';
?>

            <div class="reveal-element">
                <div style="max-width: 800px;">
                <div style="background: rgba(30, 41, 59, 0.4); backdrop-filter: blur(12px); border-radius: 16px; padding: 2rem; border: 1px solid rgba(255,255,255,0.05);">
                    <h2 style="font-size: 1.5rem; font-weight: 700; color: white; margin-bottom: 1.5rem; text-align: center;">Create New Quiz</h2>
                    
                    <?php if (isset($error)): ?>
                        <div class="bg-red-500/20 text-red-500 p-3 rounded mb-4"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div style="margin-bottom: 1rem;">
                            <label class="form-label">Select Course</label>
                            <select name="course_id" class="form-control" required style="background: #1e293b;">
                                <?php foreach ($courses as $c): ?>
                                    <option value="<?php echo $c['id']; ?>" <?php echo $c['id'] == $course_id ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($c['course_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div style="margin-bottom: 1rem;">
                            <label class="form-label">Quiz Title</label>
                            <input type="text" name="title" class="form-control" placeholder="e.g. Mid-term Exam" required>
                        </div>

                        <div style="margin-bottom: 1rem;">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 2rem;">
                            <div>
                                <label class="form-label">Duration (Minutes)</label>
                                <input type="number" name="duration" class="form-control" value="30">
                            </div>
                            <div>
                                <label class="form-label">Passing Score (%)</label>
                                <input type="number" name="passing_score" class="form-control" value="70">
                            </div>
                        </div>

                        <div style="display: flex; gap: 1rem;">
                            <a href="quizzes.php" style="flex:1; padding: 0.75rem; border-radius: 8px; text-align: center; color: var(--text-muted); cursor: pointer; text-decoration: none; border: 1px solid rgba(255,255,255,0.1);">Cancel</a>
                            <button type="submit" class="btn btn-primary" style="flex:1;">Create & Add Questions</button>
                        </div>
                    </form>
                </div>
                </div>
            </div>
<?php include 'includes/footer.php'; ?>
