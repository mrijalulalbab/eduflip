<?php 
require_once '../../includes/config.php';
require_once '../../includes/auth.php';

// Auth Check (Dosen Only)
requireLogin();
if ($_SESSION['role'] !== 'dosen') { header('Location: ../login.php'); exit; }

$dosen_id = $_SESSION['user_id'];

// --- DATA COLLECTION ---

// 1. Overview Stats
// Total Students
$stmt = $pdo->prepare("
    SELECT COUNT(DISTINCT e.student_id) 
    FROM course_enrollments e 
    JOIN courses c ON e.course_id = c.id 
    WHERE c.created_by = ?
");
$stmt->execute([$dosen_id]);
$total_students = $stmt->fetchColumn();

// Avg Quiz Score (weighted by attempts)
$stmt = $pdo->prepare("
    SELECT AVG(qa.score) 
    FROM quiz_attempts qa 
    JOIN quizzes q ON qa.quiz_id = q.id 
    JOIN courses c ON q.course_id = c.id 
    WHERE c.created_by = ?
");
$stmt->execute([$dosen_id]);
$avg_score = round((float)$stmt->fetchColumn(), 1); // FIXED: Cast to float

// Completion Rate
$stmt = $pdo->prepare("
    SELECT 
        (SELECT COUNT(*) FROM course_enrollments e JOIN courses c ON e.course_id = c.id WHERE c.created_by = ? AND e.status = 'completed') 
        / 
        NULLIF((SELECT COUNT(*) FROM course_enrollments e JOIN courses c ON e.course_id = c.id WHERE c.created_by = ?), 0) * 100
");
$stmt->execute([$dosen_id, $dosen_id]);
$completion_rate = round((float)$stmt->fetchColumn(), 1); // FIXED: Cast to float


// 2. Chart Data: Avg Score Per Course
$stmt = $pdo->prepare("
    SELECT c.course_code, AVG(COALESCE(qa.score, 0)) as avg_score
    FROM courses c
    LEFT JOIN quizzes q ON c.id = q.course_id
    LEFT JOIN quiz_attempts qa ON q.id = qa.quiz_id
    WHERE c.created_by = ?
    GROUP BY c.id
    ORDER BY avg_score DESC
    LIMIT 10
");
$stmt->execute([$dosen_id]);
$course_performance = $stmt->fetchAll(PDO::FETCH_ASSOC);

$chart_labels = [];
$chart_data = [];
foreach($course_performance as $row) {
    $chart_labels[] = $row['course_code'];
    $chart_data[] = round((float)$row['avg_score'], 1); // FIXED
}

// 3. Leaderboard: Top 5 Students (by avg quiz score across my courses)
$stmt = $pdo->prepare("
    SELECT u.full_name, AVG(qa.score) as student_avg, COUNT(qa.id) as quizzes_taken
    FROM quiz_attempts qa
    JOIN quizzes q ON qa.quiz_id = q.id
    JOIN courses c ON q.course_id = c.id
    JOIN users u ON qa.student_id = u.id
    WHERE c.created_by = ?
    GROUP BY u.id
    ORDER BY student_avg DESC
    LIMIT 5
");
$stmt->execute([$dosen_id]);
$top_students = $stmt->fetchAll(PDO::FETCH_ASSOC);


include 'includes/header.php'; 
?>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="reveal-element">
    <div class="section-title-group" style="margin-bottom: 2rem;">
        <div>
            <h1 style="font-size: 2rem; margin-bottom: 0.5rem;">Analytics Dashboard 📊</h1>
            <p class="text-muted">Deep dive into your students' performance and course engagement.</p>
        </div>
    </div>

    <!-- Overview Cards -->
    <div class="stats-container" style="margin-bottom: 2.5rem;">
        <div class="stat-card">
            <div class="stat-icon purple">
                <i class="ri-group-fill"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo $total_students; ?></h3>
                <p>Total Students</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon orange">
                <i class="ri-bar-chart-groupped-line"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo $avg_score; ?><span style="font-size: 0.9rem; opacity: 0.7;">/100</span></h3>
                <p>Avg Quiz Score</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon green">
                <i class="ri-checkbox-circle-line"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo $completion_rate; ?>%</h3>
                <p>Completion Rate</p>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="analytics-grid">
        
        <!-- Chart Section -->
        <div class="card" style="padding: 1.5rem;">
            <h3 style="margin-bottom: 1.5rem; font-size: 1.1rem; display: flex; justify-content: space-between;">
                <span>Performance by Course</span>
                <span class="badge">Top 10</span>
            </h3>
            <div style="position: relative; height: 300px;">
                <canvas id="coursePerformanceChart"></canvas>
            </div>
        </div>

        <!-- Leaderboard Section -->
        <div class="card" style="padding: 0; overflow: hidden;">
            <div style="padding: 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.05);">
                <h3 style="margin: 0; font-size: 1.1rem; color: #fbbf24; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="ri-trophy-fill"></i> Top Students
                </h3>
            </div>
            
            <?php if(empty($top_students)): ?>
                 <div style="padding: 2rem; text-align: center; color: var(--text-muted);">
                    No data available yet.
                 </div>
            <?php else: ?>
                <div style="display: flex; flex-direction: column;">
                    <?php foreach($top_students as $index => $student): ?>
                        <div style="padding: 1rem 1.5rem; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid rgba(255,255,255,0.03);">
                            <div style="display: flex; align-items: center; gap: 1rem;">
                                <div style="font-weight: 800; color: rgba(255,255,255,0.2); font-size: 1.25rem; width: 20px;">
                                    <?php echo $index + 1; ?>
                                </div>
                                <div>
                                    <div style="font-weight: 600; color: white;"><?php echo htmlspecialchars($student['full_name']); ?></div>
                                    <div style="font-size: 0.75rem; color: var(--text-muted);"><?php echo $student['quizzes_taken']; ?> Quizzes Taken</div>
                                </div>
                            </div>
                            <div class="badge" style="background: rgba(16, 185, 129, 0.1); color: #34d399;">
                                <?php echo round($student['student_avg'], 1); ?>%
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// Chart Config
const ctx = document.getElementById('coursePerformanceChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($chart_labels); ?>,
        datasets: [{
            label: 'Average Score',
            data: <?php echo json_encode($chart_data); ?>,
            backgroundColor: 'rgba(56, 189, 248, 0.6)',
            borderColor: 'rgba(56, 189, 248, 1)',
            borderWidth: 1,
            borderRadius: 6
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                max: 100,
                grid: { color: 'rgba(255, 255, 255, 0.05)' },
                ticks: { color: '#94a3b8' }
            },
            x: {
                grid: { display: false },
                ticks: { color: '#94a3b8' }
            }
        },
        plugins: {
            legend: { display: false }
        }
    }
});
</script>

<?php include 'includes/footer.php'; ?>
