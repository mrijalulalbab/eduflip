<?php 
require_once '../../includes/config.php';
require_once '../../includes/courses.php'; // Ensure this is loaded before header if possible, or inside header
// Actually header loads auth which is fine. We need courses functions.


$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle Thumbnail Upload
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

include 'includes/header.php';
?>

<div class="reveal-element">
    <h2 class="section-title">Create New Course</h2>
    
    <?php if ($message): ?>
        <div class="alert alert-danger mb-4 p-3 bg-red-900 border border-red-700 rounded text-white">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <div class="card p-5" style="max-width: 800px;">
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label class="form-label">Course Name</label>
                <input type="text" name="course_name" class="form-control" placeholder="e.g. Advanced Web Development" required>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div class="form-group pb-2">
                    <label class="form-label mb-2 block">Course Category</label>
                    <div class="flex gap-4">
                        <label class="cursor-pointer flex items-center gap-2 p-3 border border-gray-700 rounded-lg hover:bg-white/5 transition flex-1">
                            <input type="radio" name="category" value="general" checked class="accent-orange-500">
                            <div>
                                <strong class="text-white block">General Course</strong>
                                <span class="text-xs text-muted">Materi bebas / tambahan</span>
                            </div>
                        </label>
                        <label class="cursor-pointer flex items-center gap-2 p-3 border border-gray-700 rounded-lg hover:bg-white/5 transition flex-1">
                            <input type="radio" name="category" value="curriculum" class="accent-blue-500">
                            <div>
                                <strong class="text-white block">Informatics UII</strong>
                                <span class="text-xs text-muted">Materi wajib kurikulum</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Course Code</label>
                    <input type="text" name="course_code" class="form-control" placeholder="e.g. WEB201" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Cover Image</label>
                    <input type="file" name="thumbnail" class="form-control" accept="image/*">
                    <p class="text-xs text-muted mt-1">Recommended: 16:9 ratio, JPG/PNG</p>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="5" placeholder="What will students learn?" required></textarea>
            </div>
            
            <div class="flex justify-end gap-2 mt-4">
                <a href="index.php" class="btn btn-ghost">Cancel</a>
                <button type="submit" class="btn btn-primary">Create Course</button>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
