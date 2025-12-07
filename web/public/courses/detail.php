<?php
require_once '../../includes/config.php';
require_once '../../includes/auth.php';
require_once '../../includes/courses.php';

$course_id = $_GET['id'] ?? 0;
$course = getCourseById($course_id);

if (!$course) {
    header('Location: index.php');
    exit;
}

$materials = getCourseMaterials($course_id);
$is_enrolled = isLoggedIn() ? isEnrolled($_SESSION['user_id'], $course_id) : false;

// Handle Enrollment
$enroll_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enroll'])) {
    if (!isLoggedIn()) {
        header('Location: ../login.php?redirect=' . urlencode('courses/detail.php?id=' . $course_id));
        exit;
    }
    
    $result = enrollUser($_SESSION['user_id'], $course_id);
    if ($result['success']) {
        $is_enrolled = true;
        $enroll_message = $result['message'];
    } else {
        $enroll_message = $result['message'];
    }
}
?>
<?php 
$assets_path = '../assets/';
include '../../includes/header.php'; 
?>

<section class="section" style="padding-top: 150px; background: linear-gradient(to bottom, rgba(15, 23, 42, 0.9), var(--bg-dark));">
    <div class="container grid grid-cols-2 gap-4 items-center">
        <div class="reveal-element">
            <a href="index.php" class="text-muted" style="margin-bottom: 2rem; display: inline-block;"><i class="ri-arrow-left-line"></i> Back to Courses</a>
            <h1 style="font-size: 3rem; margin-bottom: 1rem;"><?php echo htmlspecialchars($course['course_name']); ?></h1>
            <div class="flex items-center gap-2 text-muted" style="margin-bottom: 1.5rem;">
                <span class="badge" style="margin: 0;"><?php echo htmlspecialchars($course['course_code']); ?></span>
                <span><i class="ri-user-line"></i> <?php echo htmlspecialchars($course['instructor_name']); ?></span>
            </div>
            <p style="font-size: 1.1rem; line-height: 1.8; color: var(--text-muted); margin-bottom: 2rem;">
                <?php echo nl2br(htmlspecialchars($course['description'])); ?>
            </p>
            
            <?php if ($enroll_message): ?>
                <div style="padding: 1rem; background: rgba(32, 178, 170, 0.1); border: 1px solid var(--secondary); color: var(--secondary); border-radius: 0.5rem; margin-bottom: 1rem;">
                    <?php echo htmlspecialchars($enroll_message); ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <?php if ($is_enrolled): ?>
                    <a href="#" class="btn btn-primary" style="background: var(--success); cursor: default;">Enrolled <i class="ri-check-line"></i></a>
                    <a href="../student/learn.php?course_id=<?php echo $course['id']; ?>" class="btn btn-outline">Go to Class <i class="ri-arrow-right-line"></i></a>
                <?php else: ?>
                    <button type="submit" name="enroll" class="btn btn-primary animate-pulse-glow" style="padding: 1rem 2.5rem; font-size: 1.2rem;">Enroll Now <i class="ri-login-box-line" style="margin-left: 10px;"></i></button>
                    <!-- <button type="button" class="btn btn-outline" style="padding: 1rem 2.5rem;">Preview</button> -->
                <?php endif; ?>
            </form>
        </div>
        
        <div class="reveal-element">
             <div class="card" style="padding: 0; overflow: hidden; border: 1px solid rgba(255,255,255,0.1);">
                <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&q=80&w=600" style="width: 100%; height: 300px; object-fit: cover;">
                <div style="padding: 2rem;">
                    <h3 style="margin-bottom: 1rem;">What you'll learn</h3>
                    <ul style="list-style: none; padding: 0;">
                        <?php if (empty($materials)): ?>
                            <li class="text-muted"><i>No materials uploaded yet.</i></li>
                        <?php else: ?>
                            <?php foreach ($materials as $index => $material): ?>
                                <li style="padding: 0.8rem 0; border-bottom: 1px solid rgba(255,255,255,0.05); display: flex; align-items: center; gap: 1rem;">
                                    <div style="width: 30px; height: 30px; background: rgba(255,255,255,0.05); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.8rem;">
                                        <?php echo $index + 1; ?>
                                    </div>
                                    <div style="flex: 1;">
                                        <div style="font-weight: 500;"><?php echo htmlspecialchars($material['title']); ?></div>
                                        <small class="text-muted"><?php echo ucfirst($material['file_type']); ?></small>
                                    </div>
                                    <?php if ($is_enrolled): ?>
                                        <i class="ri-lock-unlock-line text-success"></i>
                                    <?php else: ?>
                                        <i class="ri-lock-line text-muted"></i>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include '../../includes/footer.php'; ?>
