<?php
require_once 'config.php';

// Fetch all courses (with optional search and category)
function getCourses($search = '', $limit = 100, $category = null) {
    global $pdo;
    $sql = "SELECT c.*, u.full_name as instructor_name 
            FROM courses c 
            JOIN users u ON c.created_by = u.id 
            WHERE 1=1"; 
    
    $params = [];
    
    if ($category) {
        $sql .= " AND c.category = ?";
        $params[] = $category;
    }

    if (!empty($search)) {
        $sql .= " AND (c.course_name LIKE ? OR c.description LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }
    
    $sql .= " ORDER BY c.created_at DESC LIMIT $limit";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get single course details
function getCourseById($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT c.*, u.full_name as instructor_name 
                          FROM courses c 
                          JOIN users u ON c.created_by = u.id 
                          WHERE c.id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Check enrollment status
function isEnrolled($user_id, $course_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM course_enrollments WHERE student_id = ? AND course_id = ?");
    $stmt->execute([$user_id, $course_id]);
    return $stmt->fetch() !== false;
}

// Enroll user
function enrollUser($user_id, $course_id) {
    global $pdo;
    if (isEnrolled($user_id, $course_id)) {
        return ['success' => false, 'message' => 'Already enrolled.'];
    }
    
    try {
        $stmt = $pdo->prepare("INSERT INTO course_enrollments (student_id, course_id) VALUES (?, ?)");
        $stmt->execute([$user_id, $course_id]);
        return ['success' => true, 'message' => 'Successfully enrolled!'];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
    }
}

// Get specific material
function getMaterial($material_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM materials WHERE id = ?");
    $stmt->execute([$material_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Get course materials
function getCourseMaterials($course_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM materials WHERE course_id = ? ORDER BY order_sequence ASC");
    $stmt->execute([$course_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// --- Lecturer Functions ---

// Create new course
function createCourse($instructor_id, $data) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("
            INSERT INTO courses (course_code, course_name, description, created_by, category, thumbnail, created_at)
            VALUES (?, ?, ?, ?, ?, ?, NOW())
        ");
        
        $stmt->execute([
            $data['course_code'],
            $data['course_name'],
            $data['description'],
            $instructor_id,
            $data['category'] ?? 'general',
            $data['thumbnail'] ?? null
        ]);
        
        return ['success' => true, 'course_id' => $pdo->lastInsertId()];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => $e->getMessage()];
    }
}

// Get courses by instructor
function getCoursesByInstructor($instructor_id) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT c.*, u.full_name as instructor_name,
        (SELECT COUNT(*) FROM course_enrollments WHERE course_id = c.id) as student_count
        FROM courses c
        JOIN users u ON c.created_by = u.id
        WHERE c.created_by = ?
        ORDER BY c.created_at DESC
    ");
    $stmt->execute([$instructor_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Add material to course
function addMaterial($course_id, $title, $type, $path) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("INSERT INTO materials (course_id, title, file_type, file_path, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([$course_id, $title, $type, $path]);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}
?>
