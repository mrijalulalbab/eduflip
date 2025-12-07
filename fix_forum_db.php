<?php
require_once 'web/includes/config.php';

try {
    echo "Creating Forum Tables...\n";
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Forum Threads
    $sql1 = "CREATE TABLE IF NOT EXISTS forum_threads (
        id INT PRIMARY KEY AUTO_INCREMENT,
        course_id INT NULL,
        author_id INT,
        title VARCHAR(255),
        content TEXT,
        category ENUM('question','discussion','announcement') DEFAULT 'discussion',
        is_pinned BOOLEAN DEFAULT FALSE,
        reply_count INT DEFAULT 0,
        view_count INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
        FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB;";
    $pdo->exec($sql1);
    echo "Table 'forum_threads' created.\n";

    // Forum Replies
    $sql2 = "CREATE TABLE IF NOT EXISTS forum_replies (
        id INT PRIMARY KEY AUTO_INCREMENT,
        thread_id INT NOT NULL,
        author_id INT NOT NULL,
        content TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (thread_id) REFERENCES forum_threads(id) ON DELETE CASCADE,
        FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB;";
    $pdo->exec($sql2);
    echo "Table 'forum_replies' created.\n";

    echo "Database repair complete.\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
