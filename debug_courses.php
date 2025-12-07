<?php
require_once 'web/includes/config.php';
require_once 'web/includes/courses.php';

echo "--- Debugging Courses ---\n";
try {
    // 1. Check raw count
    $stmt = $pdo->query("SELECT count(*) FROM courses");
    echo "Total courses in DB: " . $stmt->fetchColumn() . "\n";

    // 2. Check getCourses logic
    echo "\nCalling getCourses()...\n";
    $courses = getCourses();
    echo "getCourses returned " . count($courses) . " items.\n";
    
    if (empty($courses)) {
        echo "Why empty? Checking JOIN...\n";
        $check = $pdo->query("SELECT c.id, c.created_by, u.id as user_id FROM courses c LEFT JOIN users u ON c.created_by = u.id");
        $results = $check->fetchAll(PDO::FETCH_ASSOC);
        print_r($results);
    } else {
        foreach ($courses as $c) {
            echo " - [{$c['id']}] {$c['course_name']} (Instructor: {$c['instructor_name']})\n";
        }
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
