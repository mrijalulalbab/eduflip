<?php
// COPY OF dosen/manage_course.php but tailored for ADMIN context (Back links etc.)
require_once '../../includes/config.php';
require_once '../../includes/auth.php';

requireLogin();
if ($_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

$course_id = $_GET['id'] ?? 0;
$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['role'];

// For Admin: Fetch ANY course
$stmt = $pdo->prepare("SELECT * FROM courses WHERE id = ?");
$stmt->execute([$course_id]);
$course = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$course) {
    die("Course not found.");
}

$message = '';
$messageType = '';

// Handle File Upload (Identical logic)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_material'])) {
    $meeting = (int)$_POST['meeting'];
    $topic = trim($_POST['topic']);
    $title = "Pertemuan $meeting: $topic";
    $type = $_POST['type'];
    
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $fileTmp = $_FILES['file']['tmp_name'];
        $fileName = $_FILES['file']['name'];
        $fileSize = $_FILES['file']['size'];
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        $uploadDir = '../../public/assets/uploads/materials/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        
        $newFileName = uniqid('mat_') . '.' . $fileExt;
        $destination = $uploadDir . $newFileName;
        
        if (move_uploaded_file($fileTmp, $destination)) {
             $stmt = $pdo->prepare("
                INSERT INTO materials (course_id, title, description, file_path, file_type, file_size, uploaded_by, order_sequence)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
             ");
             $webPath = 'assets/uploads/materials/' . $newFileName;
             if ($stmt->execute([$course_id, $title, $topic, $webPath, $type, $fileSize, $user_id, $meeting])) {
                 $message = "Material uploaded successfully!";
                 $messageType = "success";
             } else {
                 $message = "Database error.";
                 $messageType = "error";
             }
        } else {
             $message = "Failed to move file.";
             $messageType = "error";
        }
    } else {
        $message = "No file selected.";
        $messageType = "error";
    }
}

// Fetch Materials
$stmt = $pdo->prepare("SELECT * FROM materials WHERE course_id = ? ORDER BY order_sequence ASC, created_at DESC");
$stmt->execute([$course_id]);
$materials = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch Quizzes
$stmt = $pdo->prepare("SELECT * FROM quizzes WHERE course_id = ? ORDER BY created_at DESC");
$stmt->execute([$course_id]);
$quizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch Students
$stmt = $pdo->prepare("SELECT u.* FROM course_enrollments ce JOIN users u ON ce.student_id = u.id WHERE ce.course_id = ?");
$stmt->execute([$course_id]);
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

$activeTab = $_GET['tab'] ?? 'materials';

include 'includes/header.php';
?>

<div class="reveal-element">
    <!-- Header -->
    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 2rem;">
        <a href="courses.php" class="btn btn-ghost" style="padding: 0.5rem;"><i class="ri-arrow-left-line"></i></a>
        <div>
            <h1 style="font-size: 1.75rem; font-weight: 700; margin: 0; color: white;">Manage: <?php echo htmlspecialchars($course['course_name']); ?></h1>
            <p class="text-muted" style="margin: 0; font-size: 0.9rem;">Admin Oveview</p>
        </div>
    </div>

    <!-- Tabs -->
    <div style="border-bottom: 1px solid rgba(255,255,255,0.1); margin-bottom: 2rem; display: flex; gap: 2rem;">
        <a href="?id=<?php echo $course_id; ?>&tab=materials" style="padding-bottom: 0.75rem; border-bottom: 2px solid <?php echo $activeTab == 'materials' ? '#20B2AA' : 'transparent'; ?>; color: <?php echo $activeTab == 'materials' ? '#20B2AA' : 'var(--text-muted)'; ?>; font-weight: 600; text-decoration: none;">Materials</a>
        <a href="?id=<?php echo $course_id; ?>&tab=quizzes" style="padding-bottom: 0.75rem; border-bottom: 2px solid <?php echo $activeTab == 'quizzes' ? '#20B2AA' : 'transparent'; ?>; color: <?php echo $activeTab == 'quizzes' ? '#20B2AA' : 'var(--text-muted)'; ?>; font-weight: 600; text-decoration: none;">Quizzes</a>
        <a href="?id=<?php echo $course_id; ?>&tab=students" style="padding-bottom: 0.75rem; border-bottom: 2px solid <?php echo $activeTab == 'students' ? '#20B2AA' : 'transparent'; ?>; color: <?php echo $activeTab == 'students' ? '#20B2AA' : 'var(--text-muted)'; ?>; font-weight: 600; text-decoration: none;">Enrolled Students</a>
    </div>

    <?php if ($message): ?>
        <div class="p-3 mb-4 rounded border <?php echo $messageType == 'success' ? 'bg-green-900 border-green-700 text-green-300' : 'bg-red-900 border-red-700 text-red-300'; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <?php if ($activeTab == 'materials'): ?>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-1">
                <div class="card p-4 bg-white/5 border border-white/10 rounded-xl">
                    <h3 class="font-bold text-white mb-4"><i class="ri-upload-cloud-2-line"></i> Upload Material (Admin)</h3>
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="upload_material" value="1">
                        <div class="mb-3">
                            <label class="block text-xs uppercase font-bold text-muted mb-1">Pertemuan Ke-</label>
                            <input type="number" name="meeting" value="<?php echo count($materials) + 1; ?>" class="w-full bg-black/20 border border-white/10 rounded p-2 text-white">
                        </div>
                        <div class="mb-3">
                            <label class="block text-xs uppercase font-bold text-muted mb-1">Type</label>
                            <select name="type" class="w-full bg-black/20 border border-white/10 rounded p-2 text-white">
                                <option value="pdf">PDF</option>
                                <option value="video">Video</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="block text-xs uppercase font-bold text-muted mb-1">Topic</label>
                            <input type="text" name="topic" placeholder="e.g. Intro" required class="w-full bg-black/20 border border-white/10 rounded p-2 text-white">
                        </div>
                        <div class="mb-4">
                            <input type="file" name="file" required class="text-sm text-gray-400">
                        </div>
                        <button type="submit" class="w-full bg-orange-600 hover:bg-orange-500 text-white rounded p-2 font-bold">Upload</button>
                    </form>
                </div>
            </div>
            
            <div class="lg:col-span-2">
                 <div class="card bg-white/5 border border-white/10 rounded-xl overflow-hidden">
                    <div class="p-4 border-b border-white/10 bg-white/5 font-bold text-white">All Content</div>
                    <?php foreach ($materials as $mat): ?>
                        <div class="p-4 border-b border-white/5 flex justify-between items-center text-white">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded bg-white/5 flex items-center justify-center">
                                    <i class="<?php echo $mat['file_type'] == 'video' ? 'ri-video-line text-purple-400' : 'ri-file-text-line text-red-400'; ?>"></i>
                                </div>
                                <div>
                                    <div class="font-medium"><?php echo htmlspecialchars($mat['title']); ?></div>
                                    <div class="text-xs text-muted">Seq: <?php echo $mat['order_sequence']; ?></div>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <a href="../../<?php echo $mat['file_path']; ?>" target="_blank" class="text-blue-400 hover:text-white"><i class="ri-eye-line"></i></a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <?php if (empty($materials)): ?>
                        <div class="p-8 text-center text-muted">No materials found.</div>
                    <?php endif; ?>
                 </div>
            </div>
        </div>
    <?php elseif ($activeTab == 'quizzes'): ?>
        <!-- Quizzes Tab Content -->
        <div class="card p-6 bg-white/5 border border-white/10 rounded-xl">
             <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-white text-xl">Quizzes for this Course</h3>
                <a href="create_quiz.php?course_id=<?php echo $course_id; ?>" class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-lg font-bold flex items-center gap-2 transition shadow-lg shadow-blue-500/20">
                    <i class="ri-add-line"></i> Add New Quiz
                </a>
            </div>
             <?php if (empty($quizzes)): ?>
                <div class="text-center py-12 text-muted border border-dashed border-white/10 rounded-xl">
                    <i class="ri-file-list-3-line text-4xl mb-3 block opacity-30"></i>
                    No quizzes created for this course yet.
                </div>
            <?php else: ?>
                <div class="space-y-3">
                <?php foreach ($quizzes as $quiz): ?>
                    <div class="p-4 bg-black/20 border border-white/5 rounded-xl flex justify-between items-center hover:border-white/10 transition">
                        <div>
                            <div class="font-bold text-white text-lg"><?php echo htmlspecialchars($quiz['title']); ?></div>
                            <div class="text-xs text-muted flex gap-3 mt-1">
                                <span><i class="ri-timer-line"></i> <?php echo $quiz['duration']; ?> mins</span>
                                <span><i class="ri-check-double-line"></i> Pass: <?php echo $quiz['passing_score']; ?>%</span>
                            </div>
                        </div>
                        <a href="edit_quiz.php?id=<?php echo $quiz['id']; ?>" class="text-blue-400 hover:text-white font-medium flex items-center gap-1 transition">
                            Edit <i class="ri-arrow-right-line"></i>
                        </a>
                    </div>
                <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

    <?php elseif ($activeTab == 'students'): ?>
        <!-- Students Tab Content -->
         <div class="card p-4 bg-white/5 border border-white/10 rounded-xl">
            <table class="premium-table w-full text-left">
                <thead>
                    <tr class="text-muted text-sm border-b border-white/10">
                        <th class="p-3">Student</th>
                        <th class="p-3">Enrolled At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                        <tr class="border-b border-white/5 last:border-0 hover:bg-white/5 transition">
                            <td class="p-3">
                                <div class="font-bold text-white"><?php echo htmlspecialchars($student['full_name']); ?></div>
                                <div class="text-xs text-muted"><?php echo htmlspecialchars($student['email']); ?></div>
                            </td>
                            <td class="p-3 text-muted"><?php echo date('M d, Y', strtotime($student['created_at'])); ?></td> <!-- joined user date, simplified -->
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
         </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
