<?php 
require_once '../../includes/config.php';
require_once '../../includes/admin.php';
require_once '../../includes/courses.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle Thumbnail
    $thumbnailPath = '';
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $fileName = $_FILES['thumbnail']['name'];
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        if (in_array($fileExt, $allowed)) {
             $uploadDir = '../../public/assets/uploads/thumbnails/';
             if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
             
             $newFileName = uniqid('cover_') . '.' . $fileExt;
             if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $uploadDir . $newFileName)) {
                 $thumbnailPath = 'assets/uploads/thumbnails/' . $newFileName;
             }
        }
    }

    $data = [
        'course_name' => $_POST['course_name'],
        'course_code' => $_POST['course_code'],
        'description' => $_POST['description'],
        'category' => $_POST['category'] ?? 'general',
        'thumbnail' => $thumbnailPath
    ];
    
    $result = createCourse($_SESSION['user_id'], $data);
    
    if ($result['success']) {
        header('Location: manage_course.php?id=' . $result['course_id']);
        exit;
    } else {
        $message = $result['message'];
    }
}

// Include header AFTER redirect logic to avoid "headers already sent" error
include 'includes/header.php'; 
?>

<div class="reveal-element">
    <div class="flex items-center gap-4 mb-6">
        <a href="courses.php" class="btn btn-ghost"><i class="ri-arrow-left-line"></i> Back</a>
        <h1 class="text-3xl font-bold text-white">Create New Course (Admin)</h1>
    </div>
    
    <?php if ($message): ?>
        <div class="alert alert-danger mb-4 p-3 bg-red-900 border border-red-700 rounded text-white">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <div class="card-premium p-6" style="max-width: 800px;">
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-4">
                <label class="block text-sm font-bold text-muted mb-2">Course Name</label>
                <input type="text" name="course_name" class="w-full bg-black/20 border border-white/10 rounded-lg p-3 text-white focus:border-orange-500 outline-none" placeholder="e.g. Advanced Web Development" required>
            </div>
            
            <div class="grid grid-cols-2 gap-6 mb-4">
                <div>
                     <label class="block text-sm font-bold text-muted mb-2">Category</label>
                     <div class="flex gap-4">
                        <label class="cursor-pointer flex items-center gap-2 p-3 border border-white/10 rounded-lg bg-white/5 hover:bg-white/10 transition flex-1">
                            <input type="radio" name="category" value="general" checked class="accent-orange-500">
                            <div>
                                <strong class="text-white block text-sm">General</strong>
                                <span class="text-[10px] text-muted">Standard Course</span>
                            </div>
                        </label>
                        <label class="cursor-pointer flex items-center gap-2 p-3 border border-white/10 rounded-lg bg-white/5 hover:bg-white/10 transition flex-1">
                            <input type="radio" name="category" value="curriculum" class="accent-blue-500">
                            <div>
                                <strong class="text-white block text-sm">Curriculum</strong>
                                <span class="text-[10px] text-muted">Official Subject</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-muted mb-2">Course Code</label>
                    <input type="text" name="course_code" class="w-full bg-black/20 border border-white/10 rounded-lg p-3 text-white focus:border-orange-500 outline-none" placeholder="e.g. CS101" required>
                </div>
            </div>
            
             <div class="mb-4">
                <label class="block text-sm font-bold text-muted mb-2">Cover Image</label>
                <input type="file" name="thumbnail" class="w-full bg-black/20 border border-white/10 rounded-lg p-2 text-gray-400 text-sm" accept="image/*">
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-bold text-muted mb-2">Description</label>
                <textarea name="description" class="w-full bg-black/20 border border-white/10 rounded-lg p-3 text-white focus:border-orange-500 outline-none" rows="5" placeholder="Course details..." required></textarea>
            </div>
            
            <div class="flex justify-end gap-2">
                <button type="submit" class="bg-orange-600 hover:bg-orange-500 text-white px-8 py-3 rounded-lg font-bold shadow-lg shadow-orange-500/20 transition">Create Course</button>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
