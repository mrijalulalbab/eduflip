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
$progress = getStudentProgress($_SESSION['user_id'], $course_id);

// If no material specified, find the first available one or the next incomplete one
if (!$material_id && !empty($materials)) {
    foreach ($materials as $m) {
        if (isMaterialUnlocked($m['id'], $_SESSION['user_id'], $progress)) {
            $material_id = $m['id'];
            if (!isset($progress[$m['id']]) || $progress[$m['id']]['status'] !== 'completed') {
                break; // Stop at first incomplete but unlocked item
            }
        }
    }
    // Fallback to first if all locked (shouldn't happen) or all completed
    if (!$material_id) $material_id = $materials[0]['id'];
    
    header("Location: learn.php?course_id=$course_id&material_id=$material_id");
    exit;
} elseif ($material_id) {
    $current_material = getMaterial($material_id);
    if ($current_material['course_id'] != $course_id) {
        die("Invalid material.");
    }
    
    // Check Access
    if (!isMaterialUnlocked($material_id, $_SESSION['user_id'], $progress)) {
        header("Location: learn.php?course_id=$course_id"); // Simplified redirect for locked content
        exit;
    }
    
    markMaterialComplete($_SESSION['user_id'], $material_id);
    $progress[$material_id]['status'] = 'completed';
} else {
    $current_material = null;
}

include 'includes/header.php'; 
?>


<style>
    /* 
     * PREMIUM CLASSROOM THEME
     * Refined color palette and typography for a top-tier learning experience.
     */
    :root {
        --c-bg-main: #0b1120;       /* Very dark blue/slate */
        --c-bg-card: #1e293b;       /* Card background */
        --c-bg-side: #0f172a;       /* Sidebar background */
        --c-text-primary: #f8fafc;  /* Main text */
        --c-text-secondary: #94a3b8;/* Muted text */
        --c-accent: #38bdf8;        /* Primary Blue */
        --c-accent-hover: #0ea5e9;
        --c-border: rgba(255,255,255,0.08);
    }

    .classroom-container {
        height: calc(100vh - 90px); /* Fill screen below header */
        display: grid;
        grid-template-columns: 1fr 320px;
        gap: 0;
        background: var(--c-bg-main);
        overflow: hidden;
    }

    /* --- LEFT COLUMN: CONTENT --- */
    .content-column {
        display: flex;
        flex-direction: column;
        overflow-y: auto;
        position: relative;
        /* Scrollbar Styling */
        scrollbar-width: thin;
        scrollbar-color: var(--c-bg-card) var(--c-bg-main);
    }
    
    .content-column::-webkit-scrollbar { width: 8px; }
    .content-column::-webkit-scrollbar-track { background: var(--c-bg-main); }
    .content-column::-webkit-scrollbar-thumb { background: var(--c-bg-card); border-radius: 4px; }

    /* Video View */
    .video-view {
        padding: 3rem;
        max-width: 1100px;
        margin: 0 auto;
        width: 100%;
        animation: fadeIn 0.5s ease;
    }
    
    .video-wrapper {
        width: 100%;
        aspect-ratio: 16/9;
        background: black;
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.5);
        border: 1px solid var(--c-border);
    }

    /* Article View */
    .article-view {
        min-height: 100%;
        display: flex;
        flex-direction: column;
        background: var(--c-bg-main);
        animation: fadeIn 0.5s ease;
    }

    .article-header {
        padding: 4rem 3rem 2rem 3rem;
        background: linear-gradient(180deg, rgba(30, 41, 59, 0.5) 0%, rgba(11, 17, 32, 0) 100%);
        border-bottom: 1px solid var(--c-border);
        text-align: center;
    }

    .article-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: rgba(56, 189, 248, 0.1);
        color: var(--c-accent);
        border: 1px solid rgba(56, 189, 248, 0.2);
        border-radius: 100px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 1.5rem;
    }

    .article-title {
        font-size: 2.75rem;
        font-weight: 800;
        line-height: 1.2;
        color: var(--c-text-primary);
        letter-spacing: -0.025em;
        margin-bottom: 1rem;
        text-shadow: 0 2px 10px rgba(0,0,0,0.3);
    }

    .article-meta {
        color: var(--c-text-secondary);
        font-size: 1rem;
    }

    .article-content-wrapper {
        flex: 1;
        width: 100%;
        max-width: 860px; /* Optimal reading width */
        margin: 0 auto;
        padding: 3rem 2rem;
        font-size: 1.125rem;
        line-height: 1.8;
        color: #cbd5e1; /* Slightly softer than pure white */
    }

    /* Typography Details */
    .article-content-wrapper p { margin-bottom: 1.75rem; }
    
    .article-content-wrapper h2 { 
        font-size: 1.75rem; 
        font-weight: 700; 
        color: var(--c-text-primary); 
        margin-top: 3rem; 
        margin-bottom: 1.25rem; 
        padding-bottom: 0.75rem;
        border-bottom: 1px solid var(--c-border);
    }
    
    .article-content-wrapper h3 { 
        font-size: 1.4rem; 
        font-weight: 600; 
        color: var(--c-accent); 
        margin-top: 2.5rem; 
        margin-bottom: 1rem; 
    }

    .article-content-wrapper ul, .article-content-wrapper ol { 
        margin-bottom: 1.75rem; 
        padding-left: 1.5rem; 
    }
    
    .article-content-wrapper li {
        margin-bottom: 0.5rem;
        padding-left: 0.5rem;
    }

    .article-content-wrapper blockquote {
        margin: 2rem 0;
        padding: 1.5rem 2rem;
        border-left: 4px solid var(--c-accent);
        background: rgba(30, 41, 59, 0.4);
        border-radius: 0 8px 8px 0;
        font-style: italic;
        color: var(--c-text-primary);
    }

    .article-content-wrapper code {
        background: rgba(30, 41, 59, 0.6);
        padding: 0.2rem 0.4rem;
        border-radius: 4px;
        color: #f472b6;
        font-family: 'Fira Code', monospace;
        font-size: 0.9em;
    }

    .article-content-wrapper pre {
        background: #1e293b;
        padding: 1.5rem;
        border-radius: 12px;
        overflow-x: auto;
        margin: 2rem 0;
        border: 1px solid var(--c-border);
    }
    
    .article-content-wrapper pre code {
        background: transparent;
        padding: 0;
        color: #e2e8f0;
    }

    /* Navigation Footer */
    .lesson-footer {
        padding: 2rem 3rem;
        background: var(--c-bg-side);
        border-top: 1px solid var(--c-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* --- RIGHT COLUMN: PLAYLIST --- */
    .playlist-column {
        background: var(--c-bg-side);
        border-left: 1px solid var(--c-border);
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .playlist-header-box {
        padding: 1.5rem; /* Match header height */
        border-bottom: 1px solid var(--c-border);
        background: #131c2e; /* Slightly lighter than side bg */
    }

    .playlist-scroll-area {
        flex: 1;
        overflow-y: auto;
    }

    .playlist-item {
        display: flex;
        padding: 1.25rem 1.5rem;
        gap: 1rem;
        border-bottom: 1px solid rgba(255,255,255,0.02);
        color: var(--c-text-secondary);
        text-decoration: none;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        align-items: flex-start;
    }

    .playlist-item:hover {
        background: rgba(255,255,255,0.03);
        color: var(--c-text-primary);
    }

    .playlist-item.active {
        background: rgba(56, 189, 248, 0.08);
        color: var(--c-accent);
        border-right: 3px solid var(--c-accent); /* Right border indicator looks modern */
    }
    
    .playlist-item .icon-box {
        margin-top: 2px;
        flex-shrink: 0;
    }

    /* Utilities */
    .btn-nav-outline {
        padding: 0.75rem 1.5rem;
        border: 1px solid var(--c-border);
        border-radius: 8px;
        color: var(--c-text-primary);
        text-decoration: none;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 500;
    }
    .btn-nav-outline:hover:not(:disabled) {
        border-color: var(--c-text-secondary);
        background: rgba(255,255,255,0.05);
    }
    .btn-nav-outline:disabled {
        opacity: 0.4;
        cursor: not-allowed;
    }
    
    .btn-nav-primary {
        padding: 0.75rem 1.5rem;
        background: var(--c-accent);
        color: #0f172a;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 700;
        box-shadow: 0 4px 12px rgba(56, 189, 248, 0.3);
    }
    .btn-nav-primary:hover:not(:disabled) {
        background: var(--c-accent-hover);
        transform: translateY(-1px);
        box-shadow: 0 6px 15px rgba(56, 189, 248, 0.4);
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Mobile */
    @media (max-width: 1024px) {
        .classroom-container {
            grid-template-columns: 1fr;
            height: auto;
            overflow: visible;
        }
        .playlist-column {
            border-left: none;
            border-top: 1px solid var(--c-border);
            height: 600px;
        }
        .article-header {
            padding: 3rem 1.5rem 1.5rem 1.5rem;
        }
        .article-content-wrapper {
            padding: 2rem 1.5rem;
        }
    }
</style>


<div class="classroom-container">
    
    <!-- LEFT: Main Learning Area -->
    <main class="content-column">
        <?php if ($current_material): ?>
            
            <!-- --- VIDEO MODE LAYOUT --- -->
            <?php if ($current_material['file_type'] == 'video'): ?>
                <div class="video-view">
                    <!-- Nav Back -->
                    <div>
                        <a href="courses.php" class="text-muted hover:text-white" style="text-decoration:none;">
                            <i class="ri-arrow-left-line"></i> Check other courses
                        </a>
                    </div>

                    <!-- Video Player -->
                    <div class="video-wrapper">
                         <div class="text-center">
                            <i class="ri-play-circle-fill" style="font-size: 5rem; color: #38bdf8; opacity: 0.9;"></i>
                            <p class="text-muted mt-3">Video Player Component</p>
                            <small class="text-muted font-mono"><?php echo htmlspecialchars($current_material['file_path']); ?></small>
                         </div>
                    </div>

                    <!-- Video Meta -->
                    <div>
                        <h1 style="font-size: 2rem; margin-bottom: 0.5rem; color:white; font-weight:700;">
                            <?php echo htmlspecialchars($current_material['title']); ?>
                        </h1>
                        <p class="text-muted">Module <?php echo array_search($current_material, $materials) + 1; ?> • Video Lesson</p>
                    </div>

                    <div style="margin-top:2rem; padding-top:2rem; border-top:1px solid rgba(255,255,255,0.1);">
                        <h3 style="color:white; margin-bottom:1rem;">About this lesson</h3>
                        <div style="color:#94a3b8; line-height:1.6;">
                            <?php echo $current_material['description'] ?? 'No description available for this video lesson.'; ?>
                        </div>
                    </div>
                </div>


            <!-- --- PDF VIEW MODE --- -->
            <?php elseif ($current_material['file_type'] == 'pdf'): ?>
                <div class="video-view" style="max-width: 100%; height: 100%; padding: 2rem; display: flex; flex-direction: column;">
                    <!-- Nav Back -->
                    <div style="margin-bottom: 1rem;">
                        <a href="courses.php" class="text-muted hover:text-white" style="text-decoration:none;">
                            <i class="ri-arrow-left-line"></i> Back to courses
                        </a>
                    </div>
                    
                    <div style="flex: 1; background: #1e293b; border-radius: 16px; overflow: hidden; border: 1px solid var(--c-border); display: flex; flex-direction: column;">
                        <div style="padding: 1rem 1.5rem; background: rgba(0,0,0,0.2); border-bottom: 1px solid var(--c-border); display: flex; justify-content: space-between; align-items: center;">
                            <h2 style="font-size: 1.1rem; color: white; margin: 0; font-weight: 600;">
                                <i class="ri-file-pdf-line text-red-400 mr-2"></i> <?php echo htmlspecialchars($current_material['title']); ?>
                            </h2>
                            <a href="<?php echo htmlspecialchars($base_url . '/' . $current_material['file_path']); ?>" download class="btn-nav-outline" style="padding: 0.4rem 0.8rem; font-size: 0.85rem;">
                                <i class="ri-download-line"></i> Download
                            </a>
                        </div>
                        <iframe src="<?php echo htmlspecialchars($base_url . '/' . $current_material['file_path']); ?>" style="width: 100%; flex: 1; border: none;" type="application/pdf"></iframe>
                    </div>

                    <!-- Navigation Footer (Reused) -->
                    <div class="lesson-footer" style="margin-top: 1rem; border-top: none; background: transparent; padding: 0;">
                        <?php 
                            $currentIndex = array_search($current_material, $materials);
                            $prevMaterial = ($currentIndex > 0) ? $materials[$currentIndex - 1] : null;
                            $nextMaterial = ($currentIndex < count($materials) - 1) ? $materials[$currentIndex + 1] : null;
                        ?>
                        
                        <div>
                            <?php if ($prevMaterial): ?>
                                <a href="learn.php?course_id=<?php echo $course_id; ?>&material_id=<?php echo $prevMaterial['id']; ?>" class="btn-nav-outline">
                                    <i class="ri-arrow-left-line"></i> Previous
                                </a>
                            <?php else: ?>
                                <button class="btn-nav-outline" disabled>Previous</button>
                            <?php endif; ?>
                        </div>

                        <div>
                            <?php if ($nextMaterial): ?>
                                <a href="learn.php?course_id=<?php echo $course_id; ?>&material_id=<?php echo $nextMaterial['id']; ?>" class="btn-nav-primary">
                                    Next Lesson <i class="ri-arrow-right-line"></i>
                                </a>
                            <?php else: ?>
                                <button class="btn-nav-primary" disabled>Finish Course</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            <!-- --- ARTICLE MODE LAYOUT (The Request) --- -->
            <?php else: ?>
                <div class="article-view">
                    <!-- Hero Header -->
                    <div class="article-header">
                        <div class="article-badge">
                            <i class="ri-book-open-line"></i> <?php echo ucfirst($current_material['file_type']); ?> Lesson
                        </div>
                        <h1 class="article-title">
                            <?php echo htmlspecialchars($current_material['title']); ?>
                        </h1>
                        <p class="article-meta">
                            Module <?php echo array_search($current_material, $materials) + 1; ?> of <?php echo count($materials); ?> • <?php echo ($current_material['file_type'] == 'text') ? 'Reading' : 'Resource'; ?>
                        </p>
                    </div>

                    <!-- Readable Content -->
                    <div class="article-content-wrapper">
                        <?php 
                            // For HTML type, load external content
                            if ($current_material['file_type'] == 'html' && !empty($current_material['file_path'])) {
                                $htmlPath = $_SERVER['DOCUMENT_ROOT'] . $current_material['file_path'];
                                if (file_exists($htmlPath)) {
                                    $htmlContent = file_get_contents($htmlPath);
                                    
                                    // Extract and output styles
                                    if (preg_match('/<style[^>]*>(.*?)<\/style>/is', $htmlContent, $styleMatches)) {
                                        $css = $styleMatches[1];
                                        // Scope body styles to the wrapper to prevent breaking main layout
                                        $css = preg_replace('/body\s*\{/', '.article-content-wrapper {', $css);
                                        echo "<style>" . $css . "</style>";
                                    }
                                    
                                    // Extract and output body content
                                    if (preg_match('/<body[^>]*>(.*)<\/body>/is', $htmlContent, $bodyMatches)) {
                                        echo $bodyMatches[1];
                                    } else {
                                        echo '<p class="text-muted">Could not parse content.</p>';
                                    }
                                } else {
                                    echo '<p class="text-muted">Content file not found: ' . htmlspecialchars($htmlPath) . '</p>';
                                }
                            } else {
                                echo $current_material['description'] ?? '<p class="text-muted">No content available for this lesson.</p>';
                            }
                        ?>
                    </div>

                    <!-- Navigation -->
                    <div class="lesson-footer">
                        <?php 
                            $currentIndex = array_search($current_material, $materials);
                            $prevMaterial = ($currentIndex > 0) ? $materials[$currentIndex - 1] : null;
                            $nextMaterial = ($currentIndex < count($materials) - 1) ? $materials[$currentIndex + 1] : null;
                        ?>
                        
                        <div>
                            <?php if ($prevMaterial): ?>
                                <a href="learn.php?course_id=<?php echo $course_id; ?>&material_id=<?php echo $prevMaterial['id']; ?>" class="btn-nav-outline">
                                    <i class="ri-arrow-left-line"></i> Previous
                                </a>
                            <?php else: ?>
                                <button class="btn-nav-outline" disabled>Previous</button>
                            <?php endif; ?>
                        </div>

                        <div>
                            <?php if ($nextMaterial): ?>
                                <a href="learn.php?course_id=<?php echo $course_id; ?>&material_id=<?php echo $nextMaterial['id']; ?>" class="btn-nav-primary">
                                    Next Lesson <i class="ri-arrow-right-line"></i>
                                </a>
                            <?php else: ?>
                                <button class="btn-nav-primary" disabled>Finish Course</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <div style="display:flex; height:100%; align-items:center; justify-content:center; flex-direction:column; color:#64748b;">
                <i class="ri-book-open-line" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                <h3>Select a lesson to start learning</h3>
            </div>
        <?php endif; ?>
    </main>

    <!-- RIGHT: Playlist Sidebar -->
    <aside class="playlist-column">
        <div class="playlist-header-box">
             <div style="font-size: 0.75rem; text-transform:uppercase; letter-spacing: 0.1em; color: var(--c-accent); font-weight:700; margin-bottom: 0.5rem;">Course Content</div>
             <h4 style="margin:0; font-size: 1.1rem; color: white; line-height:1.4;"><?php echo htmlspecialchars($course['course_name']); ?></h4>
        </div>

        <div class="playlist-scroll-area">
             <?php foreach ($materials as $index => $material): 
                    $isUnlocked = isMaterialUnlocked($material['id'], $_SESSION['user_id'], $progress);
                    $isActive = $current_material && $current_material['id'] == $material['id'];
                    $isCompleted = isset($progress[$material['id']]) && $progress[$material['id']]['status'] === 'completed';
                    
                    $itemClass = "playlist-item";
                    if ($isActive) $itemClass .= " active";
                    if (!$isUnlocked) $itemClass .= " locked";
                    
                    $href = $isUnlocked ? "learn.php?course_id=$course_id&material_id={$material['id']}" : "#";
                ?>
                <a href="<?php echo $href; ?>" class="<?php echo $itemClass; ?>" <?php echo $isUnlocked ? '' : 'onclick="return false;"'; ?>>
                    <div class="icon-box">
                        <?php if ($isCompleted): ?>
                             <i class="ri-checkbox-circle-fill" style="color: #10b981; font-size:1.1rem;"></i>
                        <?php elseif (!$isUnlocked): ?>
                             <i class="ri-lock-2-line" style="font-size:1.1rem;"></i>
                        <?php elseif ($material['file_type'] == 'video'): ?>
                             <i class="ri-play-circle-line" style="font-size:1.1rem;"></i>
                        <?php else: ?>
                             <i class="ri-article-line" style="font-size:1.1rem;"></i>
                        <?php endif; ?>
                    </div>
                    
                    <div style="flex: 1;">
                        <div style="font-size: 0.95rem; line-height: 1.4; margin-bottom: 0.25rem; font-weight: <?php echo $isActive ? '600':'400'; ?>;">
                            <?php echo htmlspecialchars($material['title']); ?>
                        </div>
                        <div style="font-size: 0.8rem; color: #64748b;">Module <?php echo $index + 1; ?></div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </aside>


</div>

<?php include 'includes/footer.php'; ?>
