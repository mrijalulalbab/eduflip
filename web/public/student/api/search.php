<?php
require_once '../../../includes/config.php';
require_once '../../../includes/auth.php';

header('Content-Type: application/json');

// Auth Check
if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$query = $_GET['q'] ?? '';
$query = trim($query);

// Min 3 chars
if (strlen($query) < 3) {
    echo json_encode(['success' => true, 'results' => []]);
    exit;
}

$results = [];

try {
    // 1. Search Courses
    $stmt = $pdo->prepare("
        SELECT id, course_name as title, description, 'course' as type, 'ri-book-open-line' as icon 
        FROM courses 
        WHERE course_name LIKE ? OR description LIKE ? 
        LIMIT 3
    ");
    $stmt->execute(["%$query%", "%$query%"]);
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($courses as $c) {
        $c['url'] = '../student/learn.php?id=' . $c['id'];
        $results[] = $c;
    }

    // 2. Search Materials
    $stmt = $pdo->prepare("
        SELECT m.id, m.title, c.course_name as subtitle, 'material' as type, 'ri-file-text-line' as icon, m.course_id 
        FROM materials m
        JOIN courses c ON m.course_id = c.id
        WHERE m.title LIKE ? 
        LIMIT 5
    ");
    $stmt->execute(["%$query%"]);
    $materials = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($materials as $m) {
        $m['url'] = '../student/learn.php?id=' . $m['course_id']; // Link to course for now (ideal: deep link to material)
        $results[] = $m;
    }

    // 3. Search Forums
    $stmt = $pdo->prepare("
        SELECT t.id, t.title, c.course_name as subtitle, 'forum' as type, 'ri-discuss-line' as icon
        FROM forum_threads t
        JOIN courses c ON t.course_id = c.id
        WHERE t.title LIKE ? OR t.content LIKE ?
        LIMIT 3
    ");
    $stmt->execute(["%$query%", "%$query%"]);
    $forums = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($forums as $f) {
        $f['url'] = '../student/forums.php?thread_id=' . $f['id'];
        $results[] = $f;
    }

    // 4. Search Practice Resources
    require_once '../includes/practice_data.php';
    $practice_count = 0;
    
    foreach ($resources as $cat) {
        if ($practice_count >= 3) break;
        
        foreach ($cat['items'] as $item) {
            // Case insensitive search
            if (stripos($item['name'], $query) !== false || stripos($item['desc'], $query) !== false) {
                $results[] = [
                    'id' => uniqid(), // Dummy ID
                    'title' => $item['name'],
                    'subtitle' => $item['desc'] . ' (' . $cat['category'] . ')',
                    'type' => 'practice',
                    'icon' => $item['icon'],
                    'url' => $item['url']
                ];
                $practice_count++;
                if ($practice_count >= 3) break;
            }
        }
    }

    echo json_encode(['success' => true, 'results' => $results]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
