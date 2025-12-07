<?php
require_once '../../includes/config.php';
require_once '../../includes/auth.php';
require_once '../../includes/courses.php';

$search = $_GET['search'] ?? '';
$courses = getCourses($search);

$assets_path = '../assets/';
include '../../includes/header.php'; 
?>

<section class="section header-center" style="padding-top: 150px; padding-bottom: 50px;">
    <div class="container">
        <div class="hero-content text-center" style="max-width: 800px; margin: 0 auto;">
            <span class="badge animate-pulse-glow">Explore Learning</span>
            <h1 style="font-size: 3rem; margin-bottom: 1rem;">Browse Our <span class="text-secondary">Courses</span></h1>
            <p class="text-muted" style="font-size: 1.2rem;">Discover updated materials, master new skills, and join the flipped classroom revolution.</p>
        
            <!-- Search Bar -->
            <form action="" method="GET" style="margin-top: 2rem; position: relative; max-width: 500px; margin-left: auto; margin-right: auto;">
                <input type="text" name="search" class="form-control" placeholder="Search for Python, Data Science, etc..." value="<?php echo htmlspecialchars($search); ?>" style="padding-right: 3rem; border-radius: 2rem; padding-left: 1.5rem;">
                <button type="submit" style="position: absolute; right: 5px; top: 5px; height: calc(100% - 10px); width: 40px; border-radius: 50%; border: none; background: var(--secondary); color: var(--bg-darker); cursor: pointer; display: flex; align-items: center; justify-content: center;">
                    <i class="ri-search-line"></i>
                </button>
            </form>
        </div>
    </div>
</section>

<section class="section" style="padding-top: 0;">
    <div class="container">
        <?php if (empty($courses)): ?>
            <div class="text-center" style="padding: 4rem; background: rgba(255,255,255,0.02); border-radius: 1rem; border: 1px dashed rgba(255,255,255,0.1);">
                <i class="ri-book-open-line" style="font-size: 3rem; color: var(--text-muted); opacity: 0.5;"></i>
                <h3 style="margin-top: 1rem; color: var(--text-muted);">No courses found.</h3>
                <p class="text-muted">Try adjusting your search terms or check back later.</p>
                <?php if (!empty($search)): ?>
                    <a href="index.php" class="btn btn-outline" style="margin-top: 1rem;">Clear Search</a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-3 gap-4">
                <?php foreach ($courses as $course): ?>
                    <div class="card course-card reveal-element">
                        <div class="course-image">
                            <!-- Placeholder image based on course ID or random -->
<img src="<?php echo !empty($course['thumbnail']) ? $course['thumbnail'] : 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&q=80&w=400'; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($course['course_name']); ?>">
                            <span style="position: absolute; top: 10px; left: 10px; background: var(--secondary); color: var(--bg-darker); padding: 4px 12px; border-radius: 4px; font-size: 0.8rem; font-weight: 600;">Course</span>
                        </div>
                        <div class="course-content">
                            <small class="text-secondary"><?php echo htmlspecialchars($course['course_code']); ?></small>
                            <h3 style="margin-bottom: 0.5rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?php echo htmlspecialchars($course['course_name']); ?></h3>
                            <p class="text-muted" style="margin-bottom: 1rem; font-size: 0.9rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;"><?php echo htmlspecialchars(substr($course['description'], 0, 100)) . '...'; ?></p>
                            
                            <div class="course-meta">
                                <span><i class="ri-user-line"></i> <?php echo htmlspecialchars($course['instructor_name']); ?></span>
                            </div>
                            
                            <a href="detail.php?id=<?php echo $course['id']; ?>" class="btn btn-primary" style="width: 100%;">View Details</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include '../../includes/footer.php'; ?>
