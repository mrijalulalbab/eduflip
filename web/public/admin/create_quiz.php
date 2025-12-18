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

$course_id = $_GET['course_id'] ?? 0;

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
    
    // Assign created_by to Admin (user_id)
    if ($stmt->execute([$c_id, $title, $description, $duration, $passing_score, $_SESSION['user_id']])) {
        $new_quiz_id = $pdo->lastInsertId();
        header("Location: edit_quiz.php?id=" . $new_quiz_id);
        exit;
    } else {
        $error = "Failed to create quiz.";
    }
}

// Fetch available courses for dropdown (Admin can create for ANY course)
$stmt = $pdo->prepare("SELECT id, course_name FROM courses ORDER BY course_name ASC");
$stmt->execute();
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'includes/header.php';
?>

<div class="reveal-element">
    <div style="max-width: 800px; margin: 0 auto;">
        <div class="card-premium p-8">
            <h2 class="text-2xl font-bold text-white mb-6 text-center">Create New Quiz (Admin)</h2>
            
            <?php if (isset($error)): ?>
                <div class="bg-red-500/20 text-red-500 p-3 rounded mb-4"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-4">
                    <label class="block text-sm font-bold text-muted mb-2">Select Course</label>
                    <select name="course_id" class="w-full bg-black/20 border border-white/10 rounded-lg p-3 text-white focus:border-orange-500 outline-none">
                        <?php foreach ($courses as $c): ?>
                            <option value="<?php echo $c['id']; ?>" <?php echo $c['id'] == $course_id ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($c['course_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-bold text-muted mb-2">Quiz Title</label>
                    <input type="text" name="title" class="w-full bg-black/20 border border-white/10 rounded-lg p-3 text-white focus:border-orange-500 outline-none" placeholder="e.g. Mid-term Exam" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-bold text-muted mb-2">Description</label>
                    <textarea name="description" class="w-full bg-black/20 border border-white/10 rounded-lg p-3 text-white focus:border-orange-500 outline-none" rows="3"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-8">
                    <div>
                        <label class="block text-sm font-bold text-muted mb-2">Duration (Minutes)</label>
                        <input type="number" name="duration" class="w-full bg-black/20 border border-white/10 rounded-lg p-3 text-white focus:border-orange-500 outline-none" value="30">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-muted mb-2">Passing Score (%)</label>
                        <input type="number" name="passing_score" class="w-full bg-black/20 border border-white/10 rounded-lg p-3 text-white focus:border-orange-500 outline-none" value="70">
                    </div>
                </div>

                <div class="flex gap-4">
                    <a href="manage_course.php?id=<?php echo $course_id; ?>&tab=quizzes" class="flex-1 text-center py-3 rounded-lg border border-white/10 text-muted hover:bg-white/5 transition">Cancel</a>
                    <button type="submit" class="flex-1 bg-orange-600 hover:bg-orange-500 text-white py-3 rounded-lg font-bold shadow-lg shadow-orange-500/20 transition">Create & Add Questions</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>
