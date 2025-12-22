-- Database Initialization Script for EduFlip
-- --------------------------------------------------------

CREATE DATABASE IF NOT EXISTS eduflip;
USE eduflip;

-- 1. Table: users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('mahasiswa', 'dosen', 'admin') DEFAULT 'mahasiswa',
    status ENUM('active', 'pending', 'suspended') DEFAULT 'active',
    last_login DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Table: courses
CREATE TABLE IF NOT EXISTS courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_code VARCHAR(50) NOT NULL,
    course_name VARCHAR(255) NOT NULL,
    description TEXT,
    thumbnail VARCHAR(255) NULL,
    category VARCHAR(100) DEFAULT 'general',
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
);

-- 3. Table: course_enrollments
CREATE TABLE IF NOT EXISTS course_enrollments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    course_id INT NOT NULL,
    status ENUM('active', 'completed') DEFAULT 'active',
    enrolled_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    UNIQUE KEY unique_enrollment (student_id, course_id)
);

-- 4. Table: materials
CREATE TABLE IF NOT EXISTS materials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    file_type VARCHAR(50) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    order_sequence INT DEFAULT 0,
    prerequisite_material_id INT NULL,
    file_size INT DEFAULT 0,
    uploaded_by INT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    FOREIGN KEY (prerequisite_material_id) REFERENCES materials(id) ON DELETE SET NULL
);

-- 5. Table: student_material_progress
CREATE TABLE IF NOT EXISTS student_material_progress (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    material_id INT NOT NULL,
    status ENUM('locked', 'unlocked', 'completed') DEFAULT 'locked',
    completed_at DATETIME NULL,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (material_id) REFERENCES materials(id) ON DELETE CASCADE
);

-- 6. Table: forum_threads
CREATE TABLE IF NOT EXISTS forum_threads (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NULL,
    author_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    category VARCHAR(50) DEFAULT 'general',
    is_pinned TINYINT(1) DEFAULT 0,
    views INT DEFAULT 0,
    reply_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 7. Table: forum_replies
CREATE TABLE IF NOT EXISTS forum_replies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    thread_id INT NOT NULL,
    author_id INT NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (thread_id) REFERENCES forum_threads(id) ON DELETE CASCADE,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 8. Table: quizzes
CREATE TABLE IF NOT EXISTS quizzes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    duration INT DEFAULT 30,
    passing_score INT DEFAULT 70,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);

-- 9. Table: questions
CREATE TABLE IF NOT EXISTS questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quiz_id INT NOT NULL,
    question_text TEXT NOT NULL,
    points INT DEFAULT 10,
    order_num INT DEFAULT 0,
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE
);

-- 10. Table: question_options
CREATE TABLE IF NOT EXISTS question_options (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question_id INT NOT NULL,
    option_text TEXT NOT NULL,
    is_correct TINYINT(1) DEFAULT 0,
    order_num INT DEFAULT 0,
    FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE
);

-- 11. Table: quiz_attempts
CREATE TABLE IF NOT EXISTS quiz_attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quiz_id INT NOT NULL,
    student_id INT NOT NULL,
    total_points INT DEFAULT 0,
    earned_points INT DEFAULT 0,
    score DECIMAL(5,2) DEFAULT 0.00,
    status ENUM('in_progress', 'completed') DEFAULT 'in_progress',
    started_at DATETIME NOT NULL,
    submitted_at DATETIME NULL,
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 12. Table: student_answers
CREATE TABLE IF NOT EXISTS student_answers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    attempt_id INT NOT NULL,
    question_id INT NOT NULL,
    selected_option_id INT NOT NULL,
    is_correct TINYINT(1) DEFAULT 0,
    points_earned INT DEFAULT 0,
    FOREIGN KEY (attempt_id) REFERENCES quiz_attempts(id) ON DELETE CASCADE,
    FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE,
    FOREIGN KEY (selected_option_id) REFERENCES question_options(id) ON DELETE CASCADE
);

-- 13. Table: certificates
CREATE TABLE IF NOT EXISTS certificates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    course_id INT NOT NULL,
    certificate_code VARCHAR(50) NOT NULL UNIQUE,
    issued_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);


-- --------------------------------------------------------
-- SEED DATA
-- --------------------------------------------------------

-- Default Users (Password: password)
INSERT INTO users (full_name, email, password_hash, role, status) VALUES 
('Admin System', 'admin@eduflip.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'active'),
('Dr. Budi Santoso', 'dosen@eduflip.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'dosen', 'active'),
('Siswa Teladan', 'mahasiswa@eduflip.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'mahasiswa', 'active');

-- Courses (Including UII Courses)
INSERT INTO courses (course_code, course_name, description, category, created_by) VALUES
('CS101', 'Intro to Computer Science', 'Learn the basics of algorithms and data structures.', 'Technology', 2),
('ENG202', 'Advanced English', 'Mastering business communication.', 'Language', 2),
('DSA301', 'Algorithms & Data Structures', 'Kuasai fondasi pemrograman: Big-O, Array, Linked List, Stack, Queue, Tree, Graph, Sorting, dan Searching. Materi lengkap dengan contoh kode dan visualisasi.', 'Technology', 2),
('FPA001', 'Fundamen Pengembangan Aplikasi', 'Belajar Fundamental Pengembangan Aplikasi - Materi UII', 'curriculum', 2),
('LP001', 'Logika Pemrograman', 'Belajar Logika Pemrograman dan Computational Thinking - Materi UII', 'curriculum', 1);

-- Course Enrollments (Student enrolled in CS101, DSA301, and UII courses)
INSERT INTO course_enrollments (student_id, course_id, status) VALUES 
(3, 1, 'active'),
(3, 3, 'active'),
(3, 4, 'active'),
(3, 5, 'active');

-- Forum Thread
INSERT INTO forum_threads (course_id, author_id, title, content, category) VALUES
(1, 2, 'Welcome to CS101', 'Welcome everyone! Please introduce yourselves here.', 'announcement'),
(3, 2, 'Selamat Datang di DSA301!', 'Halo semua! Ini adalah forum diskusi untuk course Algorithms & Data Structures. Silakan bertanya jika ada materi yang kurang jelas.', 'announcement');

-- ========================================
-- DSA301 Course Materials (5 Modules)
-- ========================================
INSERT INTO materials (course_id, title, file_type, file_path, order_sequence, prerequisite_material_id) VALUES
(3, 'Modul 1: Pengantar Algoritma & Big-O', 'html', '/assets/content/dsa/module1_intro_bigo.html', 1, NULL),
(3, 'Modul 2: Array & Linked List', 'html', '/assets/content/dsa/module2_array_linkedlist.html', 2, 1),
(3, 'Modul 3: Stack & Queue', 'html', '/assets/content/dsa/module3_stack_queue.html', 3, 2),
(3, 'Modul 4: Tree & Graph', 'html', '/assets/content/dsa/module4_tree_graph.html', 4, 3),
(3, 'Modul 5: Sorting & Searching', 'html', '/assets/content/dsa/module5_sorting_searching.html', 5, 4);

-- ========================================
-- CS101 Course Materials (5 Modules)
-- ========================================
INSERT INTO materials (course_id, title, file_type, file_path, order_sequence, prerequisite_material_id) VALUES
(1, 'Modul 1: Apa Itu Ilmu Komputer?', 'html', '/assets/content/cs101/module1_what_is_cs.html', 1, NULL),
(1, 'Modul 2: Dasar-Dasar Pemrograman', 'html', '/assets/content/cs101/module2_programming_fundamentals.html', 2, 6),
(1, 'Modul 3: Algoritma dan Pemecahan Masalah', 'html', '/assets/content/cs101/module3_algorithms_problem_solving.html', 3, 7),
(1, 'Modul 4: Representasi Data', 'html', '/assets/content/cs101/module4_data_representation.html', 4, 8),
(1, 'Modul 5: Jaringan dan Internet', 'html', '/assets/content/cs101/module5_networks_internet.html', 5, 9);

-- ========================================
-- UII Course Materials (FPA001 & LP001)
-- ========================================
INSERT INTO materials (course_id, title, file_type, file_path, order_sequence, prerequisite_material_id) VALUES
(5, 'Pertemuan 1: Computational Thinking 1', 'pdf', 'assets/uploads/materials/mat_6948a2e2d3f01.pdf', 1, NULL),
(5, 'Pertemuan 2: Computational Thinking 2', 'pdf', 'assets/uploads/materials/mat_6948a302b7b38.pdf', 2, NULL),
(5, 'Pertemuan 3: Computational Thinking 2', 'pdf', 'assets/uploads/materials/mat_6948a33b52946.pdf', 3, NULL);

-- ========================================
-- DSA301 Quiz with 10 Questions
-- ========================================
INSERT INTO quizzes (course_id, title, description, duration, passing_score) VALUES
(3, 'Final Assessment: DSA Mastery', 'Quiz komprehensif untuk menguji pemahaman Anda tentang Algorithms & Data Structures. Minimal skor 70% untuk lulus.', 30, 70);

-- Questions for DSA Quiz (quiz_id = 1)
INSERT INTO questions (quiz_id, question_text, points, order_num) VALUES
(1, 'Apa kompleksitas waktu untuk mengakses elemen array berdasarkan index?', 10, 1),
(1, 'Struktur data mana yang mengikuti prinsip LIFO (Last In First Out)?', 10, 2),
(1, 'Berapa kompleksitas waktu Binary Search pada array yang sudah terurut?', 10, 3),
(1, 'Pada Linked List, berapa kompleksitas untuk menambah elemen di awal (prepend)?', 10, 4),
(1, 'Algoritma sorting mana yang memiliki kompleksitas O(n²) di worst case DAN best case?', 10, 5),
(1, 'BFS (Breadth-First Search) menggunakan struktur data apa untuk menyimpan node yang akan dikunjungi?', 10, 6),
(1, 'Pada Binary Search Tree (BST), nilai node di subtree kiri...', 10, 7),
(1, 'Mana yang BUKAN merupakan keuntungan menggunakan Linked List dibanding Array?', 10, 8),
(1, 'Merge Sort memiliki kompleksitas waktu...', 10, 9),
(1, 'Traversal tree yang menghasilkan output terurut (sorted) pada BST adalah...', 10, 10);

-- Options for Question 1: Array access complexity
INSERT INTO question_options (question_id, option_text, is_correct, order_num) VALUES
(1, 'O(1) - Constant Time', 1, 1),
(1, 'O(n) - Linear Time', 0, 2),
(1, 'O(log n) - Logarithmic Time', 0, 3),
(1, 'O(n²) - Quadratic Time', 0, 4);

-- Options for Question 2: LIFO
INSERT INTO question_options (question_id, option_text, is_correct, order_num) VALUES
(2, 'Queue', 0, 1),
(2, 'Stack', 1, 2),
(2, 'Array', 0, 3),
(2, 'Linked List', 0, 4);

-- Options for Question 3: Binary Search complexity
INSERT INTO question_options (question_id, option_text, is_correct, order_num) VALUES
(3, 'O(n)', 0, 1),
(3, 'O(n²)', 0, 2),
(3, 'O(log n)', 1, 3),
(3, 'O(1)', 0, 4);

-- Options for Question 4: Linked List prepend
INSERT INTO question_options (question_id, option_text, is_correct, order_num) VALUES
(4, 'O(n)', 0, 1),
(4, 'O(log n)', 0, 2),
(4, 'O(1)', 1, 3),
(4, 'O(n²)', 0, 4);

-- Options for Question 5: O(n²) always
INSERT INTO question_options (question_id, option_text, is_correct, order_num) VALUES
(5, 'Merge Sort', 0, 1),
(5, 'Quick Sort', 0, 2),
(5, 'Insertion Sort', 0, 3),
(5, 'Selection Sort', 1, 4);

-- Options for Question 6: BFS uses
INSERT INTO question_options (question_id, option_text, is_correct, order_num) VALUES
(6, 'Stack', 0, 1),
(6, 'Queue', 1, 2),
(6, 'Array', 0, 3),
(6, 'Tree', 0, 4);

-- Options for Question 7: BST left subtree
INSERT INTO question_options (question_id, option_text, is_correct, order_num) VALUES
(7, 'Selalu lebih besar dari node parent', 0, 1),
(7, 'Selalu lebih kecil dari node parent', 1, 2),
(7, 'Selalu sama dengan node parent', 0, 3),
(7, 'Tidak ada aturan khusus', 0, 4);

-- Options for Question 8: NOT advantage of Linked List
INSERT INTO question_options (question_id, option_text, is_correct, order_num) VALUES
(8, 'Ukuran dinamis (bisa grow/shrink)', 0, 1),
(8, 'Insert di awal O(1)', 0, 2),
(8, 'Akses elemen by index O(1)', 1, 3),
(8, 'Tidak membutuhkan memori kontinu', 0, 4);

-- Options for Question 9: Merge Sort complexity
INSERT INTO question_options (question_id, option_text, is_correct, order_num) VALUES
(9, 'O(n) di semua kasus', 0, 1),
(9, 'O(n log n) di semua kasus', 1, 2),
(9, 'O(n²) di worst case', 0, 3),
(9, 'O(log n) di best case', 0, 4);

-- Options for Question 10: Sorted traversal
INSERT INTO question_options (question_id, option_text, is_correct, order_num) VALUES
(10, 'Preorder', 0, 1),
(10, 'Postorder', 0, 2),
(10, 'Inorder', 1, 3),
(10, 'Level-order', 0, 4);


-- ========================================
-- CS101 Quiz with 10 Questions
-- ========================================
INSERT INTO quizzes (course_id, title, description, duration, passing_score) VALUES
(1, 'CS101 Final Exam', 'Uji pemahaman Anda tentang dasar-dasar ilmu komputer, pemrograman, algoritma, dan jaringan.', 20, 70);

-- Questions for CS101 Quiz (quiz_id = 2 assuming auto-increment)
INSERT INTO questions (quiz_id, question_text, points, order_num) VALUES
(2, 'Sistem bilangan berbasis 2 yang hanya terdiri dari angka 0 dan 1 disebut...', 10, 1),
(2, 'Manakah yang termasuk perangkat keras (Hardware)?', 10, 2),
(2, 'Tipe data yang digunakan untuk menyimpan nilai benar/salah adalah...', 10, 3),
(2, 'Manakah penulisan variabel yang BENAR dalam pemrograman (umumnya)?', 10, 4),
(2, 'Simbol "Diamond" (Belah Ketupat) pada Flowchart menunjukkan...', 10, 5),
(2, 'Apa output dari operasi logika: TRUE AND FALSE?', 10, 6),
(2, '1 Byte setara dengan berapa Bit?', 10, 7),
(2, 'Format gambar yang mendukung transparansi adalah...', 10, 8),
(2, 'Protokol yang digunakan untuk mentransfer halaman web secara aman adalah...', 10, 9),
(2, 'Apa fungsi dari DNS (Domain Name System)?', 10, 10);

-- Options for Q1: Binary
INSERT INTO question_options (question_id, option_text, is_correct, order_num) VALUES
(11, 'Desimal', 0, 1),
(11, 'Biner', 1, 2),
(11, 'Heksadesimal', 0, 3),
(11, 'Oktal', 0, 4);

-- Options for Q2: Hardware
INSERT INTO question_options (question_id, option_text, is_correct, order_num) VALUES
(12, 'Windows', 0, 1),
(12, 'Microsoft Word', 0, 2),
(12, 'Processor (CPU)', 1, 3),
(12, 'Google Chrome', 0, 4);

-- Options for Q3: Boolean
INSERT INTO question_options (question_id, option_text, is_correct, order_num) VALUES
(13, 'Integer', 0, 1),
(13, 'String', 0, 2),
(13, 'Boolean', 1, 3),
(13, 'Float', 0, 4);

-- Options for Q4: Variable Naming
INSERT INTO question_options (question_id, option_text, is_correct, order_num) VALUES
(14, '2nama', 0, 1),
(14, 'nama pengguna', 0, 2),
(14, 'nama_pengguna', 1, 3),
(14, 'if', 0, 4);

-- Options for Q5: Flowchart Diamond
INSERT INTO question_options (question_id, option_text, is_correct, order_num) VALUES
(15, 'Proses', 0, 1),
(15, 'Input/Output', 0, 2),
(15, 'Keputusan (Decision)', 1, 3),
(15, 'Mulai/Selesai', 0, 4);

-- Options for Q6: Logic AND
INSERT INTO question_options (question_id, option_text, is_correct, order_num) VALUES
(16, 'TRUE', 0, 1),
(16, 'FALSE', 1, 2),
(16, 'NULL', 0, 3),
(16, 'Error', 0, 4);

-- Options for Q7: Byte
INSERT INTO question_options (question_id, option_text, is_correct, order_num) VALUES
(17, '4 Bit', 0, 1),
(17, '8 Bit', 1, 2),
(17, '16 Bit', 0, 3),
(17, '32 Bit', 0, 4);

-- Options for Q8: Image Format
INSERT INTO question_options (question_id, option_text, is_correct, order_num) VALUES
(18, 'JPG', 0, 1),
(18, 'PNG', 1, 2),
(18, 'BMP', 0, 3),
(18, 'MP3', 0, 4);

-- Options for Q9: HTTPS
INSERT INTO question_options (question_id, option_text, is_correct, order_num) VALUES
(19, 'HTTP', 0, 1),
(19, 'HTTPS', 1, 2),
(19, 'FTP', 0, 3),
(19, 'SMTP', 0, 4);

-- Options for Q10: DNS
INSERT INTO question_options (question_id, option_text, is_correct, order_num) VALUES
(20, 'Menghubungkan komputer', 0, 1),
(20, 'Menerjemahkan nama domain ke IP', 1, 2),
(20, 'Menyimpan website', 0, 3),
(20, 'Mengamankan jaringan', 0, 4);
