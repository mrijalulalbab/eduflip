-- Database creation
DROP DATABASE IF EXISTS eduflip;
CREATE DATABASE eduflip 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE eduflip;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    role ENUM('admin','dosen','mahasiswa') NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(255) NOT NULL,
    student_id VARCHAR(50) NULL,
    lecturer_id VARCHAR(50) NULL,
    phone VARCHAR(20),
    photo_url VARCHAR(500),
    department VARCHAR(100),
    study_program VARCHAR(100),
    batch_year INT,
    status ENUM('active','pending','suspended') DEFAULT 'pending',
    email_verified BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    INDEX idx_email (email),
    INDEX idx_role (role),
    INDEX idx_status (status)
) ENGINE=InnoDB;

-- Courses table
CREATE TABLE IF NOT EXISTS courses (
    id INT PRIMARY KEY AUTO_INCREMENT,
    course_code VARCHAR(20) UNIQUE NOT NULL,
    course_name VARCHAR(255) NOT NULL,
    description TEXT,
    thumbnail VARCHAR(255), -- Added thumbnail column
    created_by INT NOT NULL,
    category ENUM('general','curriculum') DEFAULT 'general',
    status ENUM('draft','published','archived') DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_status (status)
) ENGINE=InnoDB;

-- Course enrollments
CREATE TABLE IF NOT EXISTS course_enrollments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    course_id INT NOT NULL,
    student_id INT NOT NULL,
    enrolled_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('active','completed','dropped') DEFAULT 'active',
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_enrollment (course_id, student_id),
    INDEX idx_student (student_id)
) ENGINE=InnoDB;

-- Materials table
CREATE TABLE IF NOT EXISTS materials (
    id INT PRIMARY KEY AUTO_INCREMENT,
    course_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    file_path VARCHAR(500),
    file_type ENUM('pdf','video','slide','text','url') NOT NULL,
    file_size INT, -- in bytes
    order_sequence INT DEFAULT 0,
    prerequisite_material_id INT NULL,
    requires_quiz_pass BOOLEAN DEFAULT FALSE,
    minimum_quiz_score INT DEFAULT 70,
    uploaded_by INT NOT NULL,
    status ENUM('draft','published') DEFAULT 'published',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    FOREIGN KEY (prerequisite_material_id) REFERENCES materials(id) ON DELETE SET NULL,
    FOREIGN KEY (uploaded_by) REFERENCES users(id),
    INDEX idx_course (course_id),
    INDEX idx_order (order_sequence)
) ENGINE=InnoDB;

-- Quizzes table
CREATE TABLE IF NOT EXISTS quizzes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    course_id INT NOT NULL,
    material_id INT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    duration INT DEFAULT 0, -- minutes
    passing_score INT DEFAULT 70,
    max_attempts INT DEFAULT 3,
    randomize_questions BOOLEAN DEFAULT FALSE,
    randomize_options BOOLEAN DEFAULT FALSE,
    show_answers BOOLEAN DEFAULT TRUE,
    available_from DATETIME NULL,
    available_until DATETIME NULL,
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    FOREIGN KEY (material_id) REFERENCES materials(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id),
    INDEX idx_course (course_id)
) ENGINE=InnoDB;

-- Questions table
CREATE TABLE IF NOT EXISTS questions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    quiz_id INT NOT NULL,
    question_text TEXT NOT NULL,
    question_type ENUM('mcq_single','mcq_multiple','fill_blank','true_false','essay') NOT NULL,
    points INT DEFAULT 1,
    order_num INT DEFAULT 0,
    explanation TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE,
    INDEX idx_quiz (quiz_id)
) ENGINE=InnoDB;

-- Question options
CREATE TABLE IF NOT EXISTS question_options (
    id INT PRIMARY KEY AUTO_INCREMENT,
    question_id INT NOT NULL,
    option_text TEXT NOT NULL,
    is_correct BOOLEAN DEFAULT FALSE,
    order_num INT DEFAULT 0,
    FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE,
    INDEX idx_question (question_id)
) ENGINE=InnoDB;

-- Quiz attempts
CREATE TABLE IF NOT EXISTS quiz_attempts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    quiz_id INT NOT NULL,
    student_id INT NOT NULL,
    attempt_number INT DEFAULT 1,
    score DECIMAL(5,2) DEFAULT 0,
    total_points INT NOT NULL,
    earned_points DECIMAL(5,2) DEFAULT 0,
    time_taken INT DEFAULT 0, -- seconds
    started_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    submitted_at TIMESTAMP NULL,
    status ENUM('in_progress','completed','grading') DEFAULT 'in_progress',
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_student_quiz (student_id, quiz_id),
    INDEX idx_status (status)
) ENGINE=InnoDB;

-- Student answers
CREATE TABLE IF NOT EXISTS student_answers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    attempt_id INT NOT NULL,
    question_id INT NOT NULL,
    answer_text TEXT NULL,
    selected_option_id INT NULL,
    is_correct BOOLEAN NULL,
    points_earned DECIMAL(5,2) DEFAULT 0,
    manual_feedback TEXT NULL,
    graded_by INT NULL,
    graded_at TIMESTAMP NULL,
    FOREIGN KEY (attempt_id) REFERENCES quiz_attempts(id) ON DELETE CASCADE,
    FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE,
    FOREIGN KEY (selected_option_id) REFERENCES question_options(id) ON DELETE SET NULL,
    FOREIGN KEY (graded_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_attempt (attempt_id)
) ENGINE=InnoDB;


-- Forum tables
CREATE TABLE IF NOT EXISTS forum_threads (

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
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS forum_replies (
    id INT PRIMARY KEY AUTO_INCREMENT,
    thread_id INT NOT NULL,
    author_id INT NOT NULL,
    content TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (thread_id) REFERENCES forum_threads(id) ON DELETE CASCADE,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ==========================================
-- SEED DATA (For Testing)
-- ==========================================

-- 1. Users
-- Password for all is 'password'
INSERT INTO users (role, email, password_hash, full_name, status, email_verified) VALUES
('admin', 'admin@eduflip.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin User', 'active', TRUE),
('dosen', 'dosen@eduflip.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'John Lecturer', 'active', TRUE),
('mahasiswa', 'student@eduflip.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Jane Student', 'active', TRUE);

-- 2. Courses
INSERT INTO courses (course_code, course_name, description, created_by, status, thumbnail) VALUES
('CS101', 'Introduction to Computer Science', 'Learn the basics of programming and algorithms.', 2, 'published', 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?auto=format&fit=crop&q=80&w=400'),
('WEB202', 'Advanced Web Development', 'Master modern web technologies like React and Tailwind.', 2, 'published', 'https://images.unsplash.com/photo-1547658719-da2b51169166?auto=format&fit=crop&q=80&w=400');

-- 3. Enrollments (Jane Student enrolled in both)
INSERT INTO course_enrollments (course_id, student_id, status) VALUES
(1, 3, 'active'),
(2, 3, 'active');

-- 4. Materials
INSERT INTO materials (course_id, title, description, file_path, file_type, uploaded_by) VALUES
(1, 'Week 1: Introduction Slides', 'Basic concepts of CS', 'assets/uploads/materials/cs101_week1.pdf', 'pdf', 2),
(1, 'Lecture 1: Algorithms Video', 'Understanding sorting', 'assets/uploads/materials/cs101_algo.mp4', 'video', 2),
(2, 'React Hooks Guide', 'Deep dive into hooks', 'assets/uploads/materials/react_hooks.pdf', 'pdf', 2);

-- 5. Quizzes
INSERT INTO quizzes (course_id, title, description, duration, passing_score, created_by) VALUES
(1, 'CS101 Midterm Quiz', 'Test your knowledge on basics.', 30, 70, 2);

-- 6. Questions
INSERT INTO questions (quiz_id, question_text, question_type, points) VALUES
(1, 'What does HTML stand for?', 'mcq_single', 10),
(1, 'Which language is used for styling web pages?', 'mcq_single', 10);

-- 7. Question Options
-- Options for Q1 (ID: 1)
INSERT INTO question_options (question_id, option_text, is_correct) VALUES
(1, 'Hyper Text Markup Language', TRUE),
(1, 'High Tech Modern Language', FALSE),
(1, 'Hyper Transfer Mode Link', FALSE),
(1, 'Home Tool Markup Language', FALSE);

-- Options for Q2 (ID: 2)
INSERT INTO question_options (question_id, option_text, is_correct) VALUES
(2, 'HTML', FALSE),
(2, 'CSS', TRUE),
(2, 'PHP', FALSE),
(2, 'Python', FALSE);
