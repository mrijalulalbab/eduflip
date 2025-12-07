<?php 
require_once '../../includes/config.php';
require_once '../../includes/auth.php';

requireLogin();
if ($_SESSION['role'] !== 'dosen') {
    header('Location: ../login.php');
    exit;
}

$dosen_id = $_SESSION['user_id'];
$dosen_name = $_SESSION['full_name'];

// Fetch quizzes
$stmt = $pdo->prepare("
    SELECT q.*, c.course_name 
    FROM quizzes q
    JOIN courses c ON q.course_id = c.id
    WHERE c.created_by = ?
    ORDER BY q.created_at DESC
");
$stmt->execute([$dosen_id]);
$quizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);


include 'includes/header.php';
?>


             <div class="reveal-element">
                <div class="section-title-group" style="align-items: flex-end;">
                    <div>
                        <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem; color: var(--text-main);">Quiz Management</h1>
                        <p class="text-muted">Develop assessments to test student knowledge.</p>
                    </div>
                    <a href="create_quiz.php" class="btn btn-primary"><i class="ri-add-line"></i> Create New Quiz</a>
                </div>

                <?php if (empty($quizzes)): ?>
                    <div class="empty-state">
                        <div style="width: 80px; height: 80px; background: rgba(255,255,255,0.05); border-radius: 50%; margin: 0 auto 1.5rem; display: flex; align-items: center; justify-content: center; color: var(--text-muted); font-size: 2rem;">
                            <i class="ri-questionnaire-line"></i>
                        </div>
                        <h3 style="color: var(--text-main); margin-bottom: 0.5rem;">No Quizzes Created</h3>
                        <p class="text-muted">Create quizzes to challenge your students.</p>
                        <a href="create_quiz.php" class="btn btn-primary">Create Quiz</a>
                    </div>
                <?php else: ?>
                    <div style="background: rgba(30, 41, 59, 0.4); backdrop-filter: blur(12px); border-radius: 20px; padding: 1rem;">
                        <table class="premium-table">
                            <thead>
                                <tr>
                                    <th>Quiz Title</th>
                                    <th>Course</th>
                                    <th>Duration</th>
                                    <th>Passing Score</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($quizzes as $quiz): ?>
                                    <tr>
                                        <td style="font-weight: 600; color: white;"><?php echo htmlspecialchars($quiz['title']); ?></td>
                                        <td style="color: var(--text-muted);"><?php echo htmlspecialchars($quiz['course_name']); ?></td>
                                        <td><?php echo $quiz['duration']; ?> mins</td>
                                        <td><span style="background: rgba(59, 130, 246, 0.1); color: #60a5fa; padding: 0.25rem 0.5rem; border-radius: 4px; font-weight: 600; font-size: 0.8rem;"><?php echo $quiz['passing_score']; ?>%</span></td>
                                        <td class="text-right">
                                            <a href="edit_quiz.php?id=<?php echo $quiz['id']; ?>" class="btn btn-ghost" style="color: var(--text-muted);"><i class="ri-pencil-line"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
             </div>
<?php include 'includes/footer.php'; ?>
