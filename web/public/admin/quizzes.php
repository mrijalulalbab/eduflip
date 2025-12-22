<?php
require_once '../../includes/config.php';
require_once '../../includes/admin.php';
require_once '../../includes/auth.php';

requireLogin();
if ($_SESSION['role'] !== 'admin') {
    // Prevent redirect loop if already logged in as non-admin
    http_response_code(403);
    echo "<div style='padding:50px; text-align:center; font-family:sans-serif;'>
            <h1>403 Access Denied</h1>
            <p>You are currently logged in as <strong>" . htmlspecialchars($_SESSION['role']) . "</strong>.</p>
            <p>This page requires <strong>admin</strong> privileges.</p>
            <p><a href='../../logout.php' style='color:blue; text-decoration:underline;'>Click here to Logout</a> and sign in as an Admin.</p>
          </div>";
    exit;
}

include 'includes/header.php';

$search = $_GET['search'] ?? '';

// Handle Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_quiz_id'])) {
    $quiz_id_del = $_POST['delete_quiz_id'];
    try {
        $stmt = $pdo->prepare("DELETE FROM quizzes WHERE id = ?");
        $stmt->execute([$quiz_id_del]);
        $message = "Quiz deleted successfully.";
        $messageType = "success";
    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
        $messageType = "error";
    }
}

// Fetch All Quizzes with Course Name and Creator Name
$sql = "
    SELECT q.*, c.course_name, u.full_name as creator_name
    FROM quizzes q
    JOIN courses c ON q.course_id = c.id
    JOIN users u ON c.created_by = u.id
    WHERE q.title LIKE ? OR c.course_name LIKE ?
    ORDER BY q.created_at DESC
";
$stmt = $pdo->prepare($sql);
$stmt->execute(["%$search%", "%$search%"]);
$quizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="reveal-element">
    <div class="flex justify-between items-end mb-6">
        <div>
            <h1 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-indigo-400 mb-2">Quiz Management</h1>
            <p class="text-muted">Manage all quizzes across the platform.</p>
        </div>
        <a href="create_quiz.php" class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-lg font-bold shadow-lg shadow-blue-500/20 transition flex items-center gap-2">
            <i class="ri-add-line"></i> Create New Quiz
        </a>
    </div>

    <!-- Filters -->
    <div class="p-1 mb-8 bg-white/5 rounded-2xl border border-white/5 backdrop-blur-md">
        <form method="GET" class="flex gap-4 p-4">
            <div class="flex-1 relative group">
                <i class="ri-search-line absolute left-4 top-3.5 text-gray-500 group-focus-within:text-blue-400 transition"></i>
                <input type="text" name="search" class="w-full bg-black/20 border border-white/5 rounded-xl py-3 pl-12 pr-4 text-white focus:outline-none focus:border-blue-500/50 transition" placeholder="Search by quiz title or course..." value="<?php echo htmlspecialchars($search); ?>">
            </div>
            <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white px-6 rounded-xl font-medium transition shadow-lg shadow-blue-500/25">Search</button>
        </form>
    </div>

    <?php if (isset($message)): ?>
        <div class="p-3 mb-6 rounded border <?php echo $messageType == 'success' ? 'bg-green-900 border-green-700 text-green-300' : 'bg-red-900 border-red-700 text-red-300'; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <!-- Quizzes Table -->
    <div class="card-premium overflow-hidden !p-0">
        <div class="overflow-x-auto">
            <table class="premium-table">
                <thead>
                    <tr>
                        <th width="35%">Quiz Title</th>
                        <th>Course</th>
                        <th>Properties</th>
                        <th>Created By</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($quizzes)): ?>
                         <tr><td colspan="5" class="text-center py-12 text-muted">
                            <i class="ri-file-list-3-line text-4xl mb-4 block opacity-50"></i>
                            No quizzes found.
                        </td></tr>
                    <?php else: ?>
                        <?php foreach ($quizzes as $quiz): ?>
                            <tr>
                                <td>
                                    <div class="font-semibold text-white text-lg"><?php echo htmlspecialchars($quiz['title']); ?></div>
                                    <div class="text-xs text-muted mt-1 truncate max-w-[200px]"><?php echo htmlspecialchars($quiz['description']); ?></div>
                                </td>
                                <td>
                                    <div class="text-sm text-gray-300">
                                        <i class="ri-book-2-line text-orange-400 mr-1"></i> <?php echo htmlspecialchars($quiz['course_name']); ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex flex-col gap-1 text-xs text-muted">
                                        <span><i class="ri-timer-line"></i> <?php echo $quiz['duration']; ?>m</span>
                                        <span><i class="ri-check-double-line"></i> Pass: <?php echo $quiz['passing_score']; ?>%</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-sm text-gray-400">
                                        <?php echo htmlspecialchars($quiz['creator_name']); ?>
                                    </div>
                                    <div class="text-[10px] text-muted"><?php echo date('M d, Y', strtotime($quiz['created_at'])); ?></div>
                                </td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="edit_quiz.php?id=<?php echo $quiz['id']; ?>" class="w-9 h-9 rounded-lg bg-blue-500/10 text-blue-500 hover:bg-blue-500 hover:text-white flex items-center justify-center transition" title="Edit"><i class="ri-edit-line"></i></a>
                                        <form method="POST" onsubmit="return confirm('Are you sure you want to delete this quiz?');" style="display:inline;">
                                            <input type="hidden" name="delete_quiz_id" value="<?php echo $quiz['id']; ?>">
                                            <button type="submit" class="w-9 h-9 rounded-lg bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white flex items-center justify-center transition" title="Delete"><i class="ri-delete-bin-line"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
