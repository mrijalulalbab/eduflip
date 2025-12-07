<?php 
require_once '../../includes/config.php';
require_once '../../includes/auth.php';

requireLogin();
if ($_SESSION['role'] !== 'dosen') {
    header('Location: ../login.php');
    exit;
}

$course_id = $_GET['id'] ?? 0;
$dosen_id = $_SESSION['user_id'];
$dosen_name = $_SESSION['full_name'];

// Fetch Course & Verify Ownership
$stmt = $pdo->prepare("SELECT * FROM courses WHERE id = ? AND created_by = ?");
$stmt->execute([$course_id, $dosen_id]);
$course = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$course) {
    die("Access denied or course not found.");
}

$message = '';
$messageType = '';

// Handle File Upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_material'])) {
    $title = trim($_POST['title']);
    $type = $_POST['type'];
    
    // File Upload Logic
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $allowed = ['pdf' => 'application/pdf', 'video' => 'video/mp4'];
        $fileTmp = $_FILES['file']['tmp_name'];
        $fileName = $_FILES['file']['name'];
        $fileSize = $_FILES['file']['size'];
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        // Log for debugging
        error_log("Uploading file: $fileName, Type: $type, Ext: $fileExt");

        // Validate type matches extension (loose check)
        if (($type == 'pdf' && $fileExt != 'pdf') || ($type == 'video' && $fileExt != 'mp4')) {
             $message = "File type mismatch. Please upload a valid .$type file.";
             $messageType = "error";
        } else {
             $uploadDir = '../../public/assets/uploads/materials/';
             if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
             
             $newFileName = uniqid('mat_') . '.' . $fileExt;
             $destination = $uploadDir . $newFileName;
             
             if (move_uploaded_file($fileTmp, $destination)) {
                 // Insert into DB
                 $stmt = $pdo->prepare("
                    INSERT INTO materials (course_id, title, description, file_path, file_type, file_size, uploaded_by)
                    VALUES (?, ?, ?, ?, ?, ?, ?)
                 ");
                 $webPath = 'assets/uploads/materials/' . $newFileName;
                 if ($stmt->execute([$course_id, $title, 'Uploaded via Dosen Portal', $webPath, $type, $fileSize, $dosen_id])) {
                     $message = "Material uploaded successfully!";
                     $messageType = "success";
                 } else {
                     $message = "Database error.";
                     $messageType = "error";
                 }
             } else {
                 $message = "Failed to move uploaded file.";
                 $messageType = "error";
             }
        }
    } else {
        $message = "No file selected or upload error.";
        $messageType = "error";
    }
}

// Fetch Materials
$stmt = $pdo->prepare("SELECT * FROM materials WHERE course_id = ? ORDER BY created_at DESC");
$stmt->execute([$course_id]);
$materials = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch Quizzes (for Quizzes tab)
$stmt = $pdo->prepare("SELECT * FROM quizzes WHERE course_id = ? ORDER BY created_at DESC");
$stmt->execute([$course_id]);
$quizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch Students (for Students tab)
$stmt = $pdo->prepare("
    SELECT u.*, ce.enrolled_at 
    FROM course_enrollments ce
    JOIN users u ON ce.student_id = u.id
    WHERE ce.course_id = ?
    ORDER BY u.full_name ASC
");
$stmt->execute([$course_id]);
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

$activeTab = $_GET['tab'] ?? 'materials'; // 'materials', 'quizzes', 'students'

include 'includes/header.php';
?>


             <div class="reveal-element">
                <!-- Header -->
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 2rem;">
                    <a href="my_courses.php" class="btn btn-ghost" style="padding: 0.5rem;"><i class="ri-arrow-left-line"></i></a>
                    <div>
                        <h1 style="font-size: 1.75rem; font-weight: 700; margin: 0; color: white;"><?php echo htmlspecialchars($course['course_name']); ?></h1>
                        <p class="text-muted" style="margin: 0; font-size: 0.9rem;"><?php echo htmlspecialchars($course['course_code']); ?> &bull; Manage Content</p>
                    </div>
                </div>

                <!-- Tabs -->
                <div style="border-bottom: 1px solid rgba(255,255,255,0.1); margin-bottom: 2rem; display: flex; gap: 2rem;">
                    <a href="?id=<?php echo $course_id; ?>&tab=materials" style="padding-bottom: 0.75rem; border-bottom: 2px solid <?php echo $activeTab == 'materials' ? '#20B2AA' : 'transparent'; ?>; color: <?php echo $activeTab == 'materials' ? '#20B2AA' : 'var(--text-muted)'; ?>; font-weight: 600; text-decoration: none; transition: all 0.2s;">
                        Materials
                    </a>
                    <a href="?id=<?php echo $course_id; ?>&tab=quizzes" style="padding-bottom: 0.75rem; border-bottom: 2px solid <?php echo $activeTab == 'quizzes' ? '#20B2AA' : 'transparent'; ?>; color: <?php echo $activeTab == 'quizzes' ? '#20B2AA' : 'var(--text-muted)'; ?>; font-weight: 600; text-decoration: none; transition: all 0.2s;">
                        Quizzes
                    </a>
                    <a href="?id=<?php echo $course_id; ?>&tab=students" style="padding-bottom: 0.75rem; border-bottom: 2px solid <?php echo $activeTab == 'students' ? '#20B2AA' : 'transparent'; ?>; color: <?php echo $activeTab == 'students' ? '#20B2AA' : 'var(--text-muted)'; ?>; font-weight: 600; text-decoration: none; transition: all 0.2s;">
                        Enrolled Students
                    </a>
                </div>

                <?php if ($message): ?>
                    <div style="margin-bottom: 1.5rem; padding: 1rem; border-radius: 8px; background: <?php echo $messageType == 'success' ? 'rgba(16, 185, 129, 0.1)' : 'rgba(239, 68, 68, 0.1)'; ?>; border: 1px solid <?php echo $messageType == 'success' ? 'rgba(16, 185, 129, 0.2)' : 'rgba(239, 68, 68, 0.2)'; ?>; color: <?php echo $messageType == 'success' ? '#6ee7b7' : '#fca5a5'; ?>;">
                        <i class="<?php echo $messageType == 'success' ? 'ri-checkbox-circle-line' : 'ri-error-warning-line'; ?>"></i>
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>

                <?php if ($activeTab == 'materials'): ?>
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Upload Form -->
                        <div class="lg:col-span-1">
                            <div style="background: rgba(30, 41, 59, 0.4); backdrop-filter: blur(12px); border-radius: 16px; padding: 1.5rem; border: 1px solid rgba(255,255,255,0.05);">
                                <h3 style="color: white; font-weight: 700; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;"><i class="ri-upload-cloud-2-line text-blue-400"></i> Upload Material</h3>
                                <form method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="upload_material" value="1">
                                    <div style="margin-bottom: 1rem;">
                                        <label style="display: block; color: var(--text-muted); font-size: 0.75rem; font-weight: 700; text-transform: uppercase; margin-bottom: 0.5rem;">Title</label>
                                        <input type="text" name="title" style="width: 100%; background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.1); padding: 0.75rem; border-radius: 8px; color: white; outline: none;" placeholder="e.g. Lecture 1 Slides" required>
                                    </div>
                                    <div style="margin-bottom: 1rem;">
                                        <label style="display: block; color: var(--text-muted); font-size: 0.75rem; font-weight: 700; text-transform: uppercase; margin-bottom: 0.5rem;">Type</label>
                                        <select name="type" style="width: 100%; background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.1); padding: 0.75rem; border-radius: 8px; color: white; outline: none;">
                                            <option value="pdf" style="background:#1e293b;">PDF Document</option>
                                            <option value="video" style="background:#1e293b;">Video (MP4)</option>
                                        </select>
                                    </div>
                                    <div style="margin-bottom: 1.5rem;">
                                        <label style="display: block; color: var(--text-muted); font-size: 0.75rem; font-weight: 700; text-transform: uppercase; margin-bottom: 0.5rem;">File</label>
                                        <input type="file" name="file" class="text-gray-400 text-sm" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary" style="width: 100%;">Upload Material</button>
                                </form>
                            </div>
                        </div>

                        <!-- Materials List -->
                        <div class="lg:col-span-2">
                            <div style="background: rgba(30, 41, 59, 0.4); backdrop-filter: blur(12px); border-radius: 16px; border: 1px solid rgba(255,255,255,0.05); overflow: hidden;">
                                <div style="padding: 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.05); background: rgba(0,0,0,0.1);">
                                    <h3 style="color: white; font-weight: 700; margin: 0;">Course Content</h3>
                                </div>
                                <?php if (empty($materials)): ?>
                                    <div style="padding: 3rem; text-align: center; color: var(--text-muted);">
                                        <i class="ri-archive-line" style="font-size: 2.5rem; opacity: 0.5; display: block; margin-bottom: 1rem;"></i>
                                        No materials uploaded yet.
                                    </div>
                                <?php else: ?>
                                    <div>
                                        <?php foreach ($materials as $mat): ?>
                                            <div style="padding: 1rem 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.05); display: flex; justify-content: space-between; align-items: center;">
                                                <div style="display: flex; align-items: center; gap: 1rem;">
                                                    <div style="width: 40px; height: 40px; border-radius: 8px; background: rgba(255,255,255,0.05); display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                                                        <?php if ($mat['file_type'] == 'video'): ?>
                                                            <i class="ri-video-line text-purple-400"></i>
                                                        <?php else: ?>
                                                            <i class="ri-file-text-line text-red-400"></i>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div>
                                                        <div style="font-weight: 500; color: white;"><?php echo htmlspecialchars($mat['title']); ?></div>
                                                        <div style="font-size: 0.75rem; text-transform: uppercase; color: var(--text-muted);"><?php echo $mat['file_type']; ?> &bull; <?php echo date('M d, Y', strtotime($mat['created_at'])); ?></div>
                                                    </div>
                                                </div>
                                                <div style="display: flex; gap: 0.5rem;">
                                                    <a href="../../<?php echo $mat['file_path']; ?>" target="_blank" class="btn btn-ghost" style="color: #60a5fa;"><i class="ri-eye-line"></i></a>
                                                    <button class="btn btn-ghost" style="color: #f87171;"><i class="ri-delete-bin-line"></i></button>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                <?php elseif ($activeTab == 'quizzes'): ?>
                    <!-- Quizzes Tab Content -->
                    <div style="background: rgba(30, 41, 59, 0.4); backdrop-filter: blur(12px); border-radius: 16px; padding: 1.5rem; border: 1px solid rgba(255,255,255,0.05);">
                         <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                            <h3 style="font-weight: 700; color: white;">Quizzes for this Course</h3>
                            <a href="create_quiz.php?course_id=<?php echo $course_id; ?>" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.85rem;">
                                <i class="ri-add-line"></i> Add Quiz
                            </a>
                        </div>
                         <?php if (empty($quizzes)): ?>
                            <p class="text-muted" style="text-align: center; padding: 2rem;">No quizzes created for this course yet.</p>
                        <?php else: ?>
                            <ul style="list-style: none; padding: 0;">
                            <?php foreach ($quizzes as $quiz): ?>
                                <li style="padding: 1rem 0; border-bottom: 1px solid rgba(255,255,255,0.05); display: flex; justify-content: space-between; align-items: center;">
                                    <span style="color: white;"><?php echo htmlspecialchars($quiz['title']); ?></span>
                                    <a href="edit_quiz.php?id=<?php echo $quiz['id']; ?>" style="color: #60a5fa; text-decoration: none; font-size: 0.9rem;">Edit</a>
                                </li>
                            <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>

                <?php elseif ($activeTab == 'students'): ?>
                    <!-- Students Tab Content -->
                     <div style="background: rgba(30, 41, 59, 0.4); backdrop-filter: blur(12px); border-radius: 16px; padding: 1rem; border: 1px solid rgba(255,255,255,0.05);">
                        <table class="premium-table">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Enrolled At</th>
                                    <th>Progress</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($students as $student): ?>
                                    <tr>
                                        <td style="color: white; font-weight: 600;"><?php echo htmlspecialchars($student['full_name']); ?></td>
                                        <td style="color: var(--text-muted);"><?php echo date('M d, Y', strtotime($student['enrolled_at'])); ?></td>
                                        <td><div style="width: 100px; height: 6px; background: rgba(255,255,255,0.1); border-radius: 3px;"><div style="height: 100%; width: 35%; background: #34d399; border-radius: 3px;"></div></div></td> 
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                     </div>
                <?php endif; ?>
<?php include 'includes/footer.php'; ?>
