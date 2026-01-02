<?php
require_once '../../includes/config.php';
require_once '../../includes/auth.php';

// Auth Check
if (!isLoggedIn() || $_SESSION['role'] !== 'mahasiswa') {
    header('Location: ../login.php');
    exit;
}

$base_url = '..';

// Available resources - Expanded with AI and more
require_once 'includes/practice_data.php';

include 'includes/header.php';
?>

<style>
    .practice-header {
        margin-bottom: 2rem;
    }
    
    .practice-header h1 {
        font-size: 1.75rem;
        font-weight: 700;
        margin: 0 0 0.5rem 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .practice-header p {
        color: #9ca3af;
        margin: 0;
    }
    
    .category-section {
        margin-bottom: 2.5rem;
    }
    
    .category-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: white;
        margin: 0 0 1rem 0;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }
    
    .resources-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1rem;
    }
    
    .resource-card {
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 14px;
        padding: 1.25rem;
        text-decoration: none;
        transition: all 0.3s ease;
        display: block;
        position: relative;
        overflow: hidden;
    }
    
    .resource-card:hover {
        transform: translateY(-4px);
        background: rgba(255,255,255,0.06);
        border-color: rgba(255,255,255,0.15);
        box-shadow: 0 12px 24px rgba(0,0,0,0.3);
    }
    
    .resource-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--card-accent);
        opacity: 0;
        transition: opacity 0.3s;
    }
    
    .resource-card:hover::before {
        opacity: 1;
    }
    
    .resource-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.75rem;
        font-size: 1.5rem;
        color: white;
    }
    
    .resource-name {
        font-size: 1rem;
        font-weight: 700;
        color: white;
        margin: 0 0 0.25rem 0;
    }
    
    .resource-desc {
        font-size: 0.8rem;
        color: #9ca3af;
        margin: 0 0 0.75rem 0;
        line-height: 1.4;
    }
    
    .resource-link {
        font-size: 0.75rem;
        color: var(--color-primary, #3b82f6);
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    .info-banner {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.15), rgba(139, 92, 246, 0.1));
        border: 1px solid rgba(59, 130, 246, 0.2);
        border-radius: 16px;
        padding: 1.25rem 1.5rem;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .info-banner i {
        font-size: 2rem;
        color: #3b82f6;
    }
    
    .info-banner .content h3 {
        margin: 0 0 0.25rem 0;
        font-size: 1rem;
        color: white;
    }
    
    .info-banner .content p {
        margin: 0;
        font-size: 0.9rem;
        color: #93c5fd;
    }
    
    .stats-bar {
        display: flex;
        gap: 2rem;
        margin-bottom: 2rem;
        flex-wrap: wrap;
    }
    
    .stat-item {
        text-align: center;
    }
    
    .stat-item .number {
        font-size: 1.5rem;
        font-weight: 800;
        color: white;
    }
    
    .stat-item .label {
        font-size: 0.75rem;
        color: #9ca3af;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
</style>

<div class="practice-header reveal-element">
    <h1>
        <i class="ri-code-s-slash-line" style="color: var(--color-primary, #3b82f6);"></i>
        Practice Coding
    </h1>
    <p>Learn programming with curated tutorials, documentation, and interactive exercises</p>
</div>

<div class="stats-bar reveal-element">
    <div class="stat-item">
        <div class="number"><?php echo array_sum(array_map(fn($c) => count($c['items']), $resources)); ?></div>
        <div class="label">Resources</div>
    </div>
    <div class="stat-item">
        <div class="number"><?php echo count($resources); ?></div>
        <div class="label">Categories</div>
    </div>
    <div class="stat-item">
        <div class="number">100%</div>
        <div class="label">Free</div>
    </div>
</div>

<div class="info-banner reveal-element">
    <i class="ri-lightbulb-flash-line"></i>
    <div class="content">
        <h3>Learn by Doing!</h3>
        <p>Click any card to open tutorials. Most include interactive "Try it Yourself" editors.</p>
    </div>
</div>

<?php foreach ($resources as $category): ?>
<div class="category-section reveal-element">
    <h2 class="category-title"><?php echo $category['category']; ?></h2>
    <div class="resources-grid">
        <?php foreach ($category['items'] as $item): ?>
            <a href="<?php echo $item['url']; ?>" target="_blank" class="resource-card" style="--card-accent: <?php echo $item['color']; ?>;">
                <div class="resource-icon" style="background: <?php echo $item['color']; ?>;">
                    <i class="<?php echo $item['icon']; ?>"></i>
                </div>
                <h3 class="resource-name"><?php echo $item['name']; ?></h3>
                <p class="resource-desc"><?php echo $item['desc']; ?></p>
                <span class="resource-link">
                    Open <i class="ri-external-link-line"></i>
                </span>
            </a>
        <?php endforeach; ?>
    </div>
</div>
<?php endforeach; ?>

<?php include 'includes/footer.php'; ?>
