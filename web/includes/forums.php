<?php
require_once 'config.php';

// Get threads (optional course filter)
function getThreads($course_id = null, $search = '') {
    global $pdo;
    $sql = "SELECT t.*, u.full_name as author_name, c.course_code 
            FROM forum_threads t
            JOIN users u ON t.author_id = u.id
            LEFT JOIN courses c ON t.course_id = c.id
            WHERE 1=1";
            
    $params = [];
    
    if ($course_id) {
        $sql .= " AND t.course_id = ?";
        $params[] = $course_id;
    }
    
    if (!empty($search)) {
        $sql .= " AND (t.title LIKE ? OR t.content LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }
    
    $sql .= " ORDER BY t.is_pinned DESC, t.created_at DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get single thread
function getThread($id) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT t.*, u.full_name as author_name, c.course_name, c.course_code 
        FROM forum_threads t
        JOIN users u ON t.author_id = u.id
        LEFT JOIN courses c ON t.course_id = c.id
        WHERE t.id = ?
    ");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Get replies
function getReplies($thread_id) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT r.*, u.full_name as author_name, u.role as author_role
        FROM forum_replies r
        JOIN users u ON r.author_id = u.id
        WHERE r.thread_id = ?
        ORDER BY r.created_at ASC
    ");
    $stmt->execute([$thread_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Create Thread
function createThread($course_id, $author_id, $title, $content, $category) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("
            INSERT INTO forum_threads (course_id, author_id, title, content, category, created_at)
            VALUES (?, ?, ?, ?, ?, NOW())
        ");
        $stmt->execute([$course_id, $author_id, $title, $content, $category]);
        return $pdo->lastInsertId();
    } catch (PDOException $e) {
        return false;
    }
}

// Create Reply
function createReply($thread_id, $author_id, $content) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("
            INSERT INTO forum_replies (thread_id, author_id, content, created_at)
            VALUES (?, ?, ?, NOW())
        ");
        $stmt->execute([$thread_id, $author_id, $content]);
        
        // Update reply count
        $stmt = $pdo->prepare("UPDATE forum_threads SET reply_count = reply_count + 1 WHERE id = ?");
        $stmt->execute([$thread_id]);
        
        return true;
    } catch (PDOException $e) {
        return false;
    }
}
// Get threads for all courses taught by an instructor
function getInstructorThreads($instructor_id, $search = '') {
    global $pdo;
    $sql = "SELECT t.*, u.full_name as author_name, c.course_code 
            FROM forum_threads t
            JOIN users u ON t.author_id = u.id
            JOIN courses c ON t.course_id = c.id
            WHERE c.created_by = ?";
            
    $params = [$instructor_id];
    
    if (!empty($search)) {
        $sql .= " AND (t.title LIKE ? OR t.content LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }
    
    $sql .= " ORDER BY t.created_at DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
