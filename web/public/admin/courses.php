<?php
require_once '../../includes/config.php';
require_once '../../includes/admin.php';

include 'includes/header.php';

$search = $_GET['search'] ?? '';

// Handle Delete Request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_course_id'])) {
    $course_delete_id = $_POST['delete_course_id'];
    try {
        // Delete course (Cascade delete will handle enrollments/materials if set up correctly, but let's be safe via admin func or direct query)
        // init.sql has ON DELETE CASCADE for foreign keys, so simple delete from courses table is enough.
        $stmt = $pdo->prepare("DELETE FROM courses WHERE id = ?");
        $stmt->execute([$course_delete_id]);
        $message = "Course deleted successfully.";
        $messageType = "success";
    } catch (PDOException $e) {
        $message = "Error deleting course: " . $e->getMessage();
        $messageType = "error";
    }
}

$courses = getAllCoursesAdmin($search);
?>

<div class="reveal-element">
    <div class="flex justify-between items-end mb-6">
        <div>
            <h1 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-orange-400 to-pink-400 mb-2">Course Management</h1>
            <p class="text-muted">Oversee and audit platform content.</p>
        </div>
    </div>
    
    <!-- Filters -->
    <div class="p-1 mb-8 bg-white/5 rounded-2xl border border-white/5 backdrop-blur-md">
        <form method="GET" class="flex gap-4 p-4">
            <div class="flex-1 relative group">
                <i class="ri-search-line absolute left-4 top-3.5 text-gray-500 group-focus-within:text-orange-400 transition"></i>
                <input type="text" name="search" class="w-full bg-black/20 border border-white/5 rounded-xl py-3 pl-12 pr-4 text-white focus:outline-none focus:border-orange-500/50 transition" placeholder="Search by course name or code..." value="<?php echo htmlspecialchars($search); ?>">
            </div>
            <button type="submit" class="bg-orange-600 hover:bg-orange-500 text-white px-6 rounded-xl font-medium transition shadow-lg shadow-orange-500/25">Search</button>
        </form>
    </div>
    
    <!-- Courses Table -->
    <div class="card-premium overflow-hidden !p-0">
        <div class="overflow-x-auto">
            <table class="premium-table">
                <thead>
                    <tr>
                        <th width="40%">Course Name</th>
                        <th>Instructor</th>
                        <th>Students</th>
                        <th>Created At</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($courses)): ?>
                         <tr><td colspan="5" class="text-center py-12 text-muted">
                            <i class="ri-book-open-line text-4xl mb-4 block opacity-50"></i>
                            No courses available.
                        </td></tr>
                    <?php else: ?>
                        <?php foreach ($courses as $course): ?>
                            <tr>
                                <td>
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-xl bg-orange-500/10 flex items-center justify-center text-orange-400 text-xl">
                                            <i class="ri-book-2-fill"></i>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-white text-lg"><?php echo htmlspecialchars($course['course_name']); ?></div>
                                            <div class="text-xs text-muted font-mono bg-white/5 px-2 py-0.5 rounded inline-block mt-1"><?php echo htmlspecialchars($course['course_code']); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2 text-sm text-gray-300">
                                        <div class="w-6 h-6 rounded-full bg-gray-700 flex items-center justify-center text-[10px]"><?php echo strtoupper(substr($course['instructor_name'], 0, 1)); ?></div>
                                        <?php echo htmlspecialchars($course['instructor_name']); ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge-admin badge-blue">
                                        <i class="ri-group-line mr-1"></i> <?php echo $course['student_count']; ?>
                                    </span>
                                </td>
                                <td class="text-sm text-muted">
                                    <?php echo date('M d, Y', strtotime($course['created_at'])); ?>
                                </td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="../courses/detail.php?id=<?php echo $course['id']; ?>" target="_blank" class="w-9 h-9 rounded-lg bg-blue-500/10 text-blue-500 hover:bg-blue-500 hover:text-white flex items-center justify-center transition" title="View"><i class="ri-external-link-line"></i></a>
                                        <form method="POST" onsubmit="return confirm('Are you sure you want to delete this course? This action cannot be undone.');" style="display:inline;">
                                            <input type="hidden" name="delete_course_id" value="<?php echo $course['id']; ?>">
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
