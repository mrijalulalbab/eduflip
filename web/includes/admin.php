<?php
require_once 'config.php';

// --- Admin Stats ---

function getAdminStats() {
    global $pdo;
    
    $stats = [];
    
    // Total Users
    $stmt = $pdo->query("SELECT COUNT(*) FROM users");
    $stats['total_users'] = $stmt->fetchColumn();
    
    // Total Students
    $stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'mahasiswa'");
    $stats['total_students'] = $stmt->fetchColumn();
    
    // Total Lecturers
    $stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'dosen'");
    $stats['total_lecturers'] = $stmt->fetchColumn();
    
    // Total Courses
    $stmt = $pdo->query("SELECT COUNT(*) FROM courses");
    $stats['total_courses'] = $stmt->fetchColumn();
    
    return $stats;
}

// --- User Management ---

function getAllUsers($search = '', $role_filter = '') {
    global $pdo;
    
    $sql = "SELECT * FROM users WHERE 1=1";
    $params = [];
    
    if (!empty($search)) {
        $sql .= " AND (full_name LIKE ? OR email LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }
    
    if (!empty($role_filter)) {
        $sql .= " AND role = ?";
        $params[] = $role_filter;
    }
    
    $sql .= " ORDER BY created_at DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getUserById($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function updateUserStatus($id, $status) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("UPDATE users SET status = ? WHERE id = ?");
        $stmt->execute([$status, $id]);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

function deleteUser($id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

// --- Admin Course Management ---

function getAllCoursesAdmin($search = '') {
    global $pdo;
    
    $sql = "SELECT c.*, u.full_name as instructor_name, 
            (SELECT COUNT(*) FROM course_enrollments WHERE course_id = c.id) as student_count
            FROM courses c
            JOIN users u ON c.created_by = u.id
            WHERE 1=1";
            
    $params = [];
    
    if (!empty($search)) {
        $sql .= " AND (c.course_name LIKE ? OR c.course_code LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }
    
    $sql .= " ORDER BY c.created_at DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
