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

// Fetch distinct students enrolled in ANY of this dosen's courses
$stmt = $pdo->prepare("
    SELECT DISTINCT u.*, 
           (SELECT COUNT(*) FROM course_enrollments ce2 
            JOIN courses c2 ON ce2.course_id = c2.id 
            WHERE ce2.student_id = u.id AND c2.created_by = ?) as courses_enrolled
    FROM users u
    JOIN course_enrollments ce ON u.id = ce.student_id
    JOIN courses c ON ce.course_id = c.id
    WHERE c.created_by = ?
    ORDER BY u.full_name ASC
");
$stmt->execute([$dosen_id, $dosen_id]);
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);


include 'includes/header.php';
?>


             <div class="reveal-element">
                <div class="section-title-group">
                    <div>
                        <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem; color: var(--text-main);">My Students</h1>
                        <p class="text-muted">Students enrolled in your courses.</p>
                    </div>
                </div>

                <?php if (empty($students)): ?>
                    <div class="empty-state">
                        <div style="width: 80px; height: 80px; background: rgba(255,255,255,0.05); border-radius: 50%; margin: 0 auto 1.5rem; display: flex; align-items: center; justify-content: center; color: var(--text-muted); font-size: 2rem;">
                            <i class="ri-group-line"></i>
                        </div>
                        <h3 style="color: var(--text-main); margin-bottom: 0.5rem;">No Students Yet</h3>
                        <p class="text-muted">Once students enroll in your courses, they will appear here.</p>
                    </div>
                <?php else: ?>
                    <div style="background: rgba(30, 41, 59, 0.4); backdrop-filter: blur(12px); border-radius: 20px; padding: 1rem;">
                        <table class="premium-table">
                            <thead>
                                <tr>
                                    <th>Student Name</th>
                                    <th>Email</th>
                                    <th>Courses Enrolled</th>
                                    <th>Department</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($students as $student): ?>
                                    <tr>
                                        <td>
                                            <div style="display: flex; align-items: center; gap: 1rem;">
                                                <div style="width: 32px; height: 32px; border-radius: 50%; background: rgba(59, 130, 246, 0.1); color: #60a5fa; display: flex; align-items: center; justify-content: center;">
                                                    <i class="ri-user-smile-line"></i>
                                                </div>
                                                <div style="font-weight: 600; color: white;"><?php echo htmlspecialchars($student['full_name']); ?></div>
                                            </div>
                                        </td>
                                        <td style="color: var(--text-muted);"><?php echo htmlspecialchars($student['email']); ?></td>
                                        <td>
                                            <span style="background: rgba(168, 85, 247, 0.1); color: #c084fc; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem; font-weight: 600;">
                                                <?php echo $student['courses_enrolled']; ?> Courses
                                            </span>
                                        </td>
                                        <td style="color: var(--text-muted);"><?php echo htmlspecialchars($student['department'] ?? '-'); ?></td>
                                        <td class="text-right">
                                            <button class="btn btn-ghost" style="color: var(--text-muted);"><i class="ri-mail-send-line"></i></button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
             </div>
<?php include 'includes/footer.php'; ?>
