<?php
require_once '../../includes/config.php';
require_once '../../includes/auth.php';
require_once '../../includes/courses.php';

// Auth Check
if (!isLoggedIn() || $_SESSION['role'] !== 'mahasiswa') {
    header('Location: ../login.php');
    exit;
}

$base_url = '..';

$course_id = $_GET['course_id'] ?? 0;
$material_id = $_GET['material_id'] ?? 0;

// Verify Enrollment
if (!isEnrolled($_SESSION['user_id'], $course_id)) {
    die("You are not enrolled in this course.");
}

$course = getCourseById($course_id);
$materials = getCourseMaterials($course_id);

// If no material specified, default to first
if (!$material_id && !empty($materials)) {
    $current_material = $materials[0];
    $material_id = $current_material['id'];
} elseif ($material_id) {
    $current_material = getMaterial($material_id);
    // Security check: ensure material belongs to course
    if ($current_material['course_id'] != $course_id) {
        die("Invalid material.");
    }
} else {
    $current_material = null;
}

include 'includes/header.php'; 
?>

<style>
    /* Specific styles for the learning interface */
    .classroom-grid {
        display: grid;
        grid-template-columns: 1fr 350px;
        gap: 2rem;
        height: calc(100vh - 140px); /* Adjust based on header/padding */
    }

    .video-player-container {
        background: black;
        border-radius: 16px;
        overflow: hidden;
        width: 100%;
        height: 100%;
        max-height: 70vh;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 20px 50px rgba(0,0,0,0.5);
    }
    
    .content-area {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .playlist-container {
        background: var(--bg-card);
        border: 1px solid rgba(255,255,255,0.05);
        border-radius: 16px;
        display: flex;
        flex-direction: column;
        height: 100%;
        max-height: 80vh;
        overflow: hidden;
    }

    .playlist-header {
        padding: 1.5rem;
        border-bottom: 1px solid rgba(255,255,255,0.05);
        background: rgba(15, 23, 42, 0.5);
    }

    .playlist-items {
        overflow-y: auto;
        flex: 1;
    }

    .playlist-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid rgba(255,255,255,0.02);
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        color: var(--text-muted);
    }

    .playlist-item:hover {
        background: rgba(32, 178, 170, 0.05);
        color: var(--text-main);
    }

    .playlist-item.active {
        background: rgba(32, 178, 170, 0.1);
        color: var(--secondary);
        border-left: 3px solid var(--secondary);
    }

    .item-icon {
        width: 24px;
        text-align: center;
    }

    @media (max-width: 1024px) {
        .classroom-grid {
            grid-template-columns: 1fr;
            height: auto;
        }
        .video-player-container {
            max-height: 50vh;
        }
    }
</style>

<div class="reveal-element">
    <div class="flex items-center gap-4 mb-4">
        <a href="courses.php" class="btn btn-ghost"><i class="ri-arrow-left-line"></i> Back</a>
        <h2 style="margin: 0; font-size: 1.5rem;"><?php echo htmlspecialchars($course['course_name']); ?></h2>
    </div>

    <div class="classroom-grid">
        <!-- Main Content (Player) -->
        <div class="content-area">
            <?php if ($current_material): ?>
                <div class="video-player-container">
                    <?php if ($current_material['file_type'] == 'video'): ?>
                         <!-- Placeholder for video player -->
                         <div class="text-center">
                            <i class="ri-play-circle-fill" style="font-size: 4rem; color: var(--secondary); opacity: 0.8;"></i>
                            <p class="text-muted mt-2">Video Player Placeholder</p>
                            <small class="text-muted"><?php echo htmlspecialchars($current_material['file_path']); ?></small>
                         </div>
                    <?php else: ?>
                        <!-- PDF/Text Placeholder -->
                         <div class="text-center" style="padding: 2rem;">
                            <i class="ri-file-text-line" style="font-size: 4rem; color: white; opacity: 0.5;"></i>
                            <h3 class="mt-4"><?php echo htmlspecialchars($current_material['title']); ?></h3>
                            <a href="<?php echo htmlspecialchars($current_material['file_path']); ?>" target="_blank" class="btn btn-primary mt-4">Download/View File</a>
                         </div>
                    <?php endif; ?>
                </div>

                <div>
                    <h1 style="font-size: 1.8rem; margin-bottom: 0.5rem;"><?php echo htmlspecialchars($current_material['title']); ?></h1>
                    <p class="text-muted">Module <?php echo array_search($current_material, $materials) + 1; ?></p>
                </div>
            <?php else: ?>
                <div class="card text-center p-5">
                    <h3>No content selected</h3>
                </div>
            <?php endif; ?>
        </div>

        <!-- Right Playlist -->
        <div class="playlist-container">
            <div class="playlist-header">
                <h4 style="margin: 0;">Course Content</h4>
                <div class="text-muted" style="font-size: 0.85rem; margin-top: 0.25rem;"><?php echo count($materials); ?> Lessons</div>
            </div>
            <div class="playlist-items">
                <?php foreach ($materials as $index => $material): ?>
                    <a href="learn.php?course_id=<?php echo $course_id; ?>&material_id=<?php echo $material['id']; ?>" class="playlist-item <?php echo ($current_material && $current_material['id'] == $material['id']) ? 'active' : ''; ?>">
                        <div class="item-icon">
                            <?php if ($material['file_type'] == 'video'): ?>
                                <i class="ri-play-circle-line"></i>
                            <?php else: ?>
                                <i class="ri-file-text-line"></i>
                            <?php endif; ?>
                        </div>
                        <div style="flex: 1;">
                            <div style="font-weight: 500; font-size: 0.95rem; line-height: 1.3; margin-bottom: 0.2rem;"><?php echo htmlspecialchars($material['title']); ?></div>
                            <span style="font-size: 0.75rem; opacity: 0.7;">10 min</span>
                        </div>
                        <?php if ($current_material && $current_material['id'] == $material['id']): ?>
                            <i class="ri-bar-chart-fill text-secondary"></i>
                        <?php endif; ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
