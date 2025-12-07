# EduFlip - Product Requirements Document (PRD)
**Version:** 1.0  
**Last Updated:** December 6, 2024  
**Project Type:** Flipped Classroom Learning Management System  
**Tech Stack:** HTML, CSS, JavaScript, PHP, MySQL, Docker

---

## 📋 Table of Contents
1. [Executive Summary](#executive-summary)
2. [Product Overview](#product-overview)
3. [User Roles & Permissions](#user-roles--permissions)
4. [Core Features Specification](#core-features-specification)
5. [Technical Architecture](#technical-architecture)
6. [Database Schema](#database-schema)
7. [UI/UX Design Guidelines](#uiux-design-guidelines)
8. [API Integration](#api-integration)
9. [Security Requirements](#security-requirements)
10. [Docker Deployment Guide](#docker-deployment-guide)
11. [Testing Requirements](#testing-requirements)
12. [Project Timeline](#project-timeline)

---

## 1. Executive Summary

### 1.1 Product Vision
EduFlip is a web-based learning management system designed to support the **flipped classroom model**. Students learn course materials independently through digital modules before class sessions. Class time is then utilized for discussions, Q&A, and concept reinforcement.

### 1.2 Project Context
This project serves dual purposes:
- **PABW (Web-Based Application Development):** Full-stack web application development
- **CSN (Computer Systems and Networks):** Containerized deployment with Docker, DNS server configuration

### 1.3 Success Criteria
- ✅ Functional web application with all 11 core features
- ✅ Deployed in Docker containers (separate web server + database)
- ✅ BIND-based DNS server configuration
- ✅ Accessible from client browsers
- ✅ Complete documentation and presentation

---

## 2. Product Overview

### 2.1 Core Value Proposition
EduFlip provides:
- **Structured learning paths** with unlocking mechanism
- **Interactive assessments** with automated grading
- **AI-powered assistance** available 24/7
- **Community learning** through forums
- **Offline capability** for flexible learning
- **Progress tracking** for students and instructors

### 2.2 Target Users
- **University Students:** Primary learners using the platform
- **Lecturers/Instructors:** Content creators and course managers
- **Administrators:** System managers and content curators

### 2.3 Key Differentiators
- AI chatbot integration for instant help
- Systematic content unlocking based on competency
- Integrated external resources (W3Schools)
- Offline-first approach with sync capabilities
- Comprehensive analytics dashboard

---

## 3. User Roles & Permissions

### 3.1 Role Hierarchy

#### **ADMIN (Super User)**
**Primary Responsibilities:**
- Upload default course materials for all courses
- Manage all user accounts (create, edit, delete, suspend)
- Configure system settings
- Monitor platform usage and performance
- Generate comprehensive reports
- Manage course categories and tags

**Access Level:** FULL ACCESS to all features

**Key Permissions:**
```
✅ User Management (CRUD all users)
✅ Content Management (Upload/Edit/Delete all materials)
✅ System Configuration
✅ Analytics Dashboard (All users, all courses)
✅ Database Backup/Restore
✅ Role Assignment
✅ Forum Moderation (All forums)
❌ Cannot take quizzes as student
```

#### **DOSEN (Lecturer/Instructor)**
**Primary Responsibilities:**
- Receive ready-to-use materials from admin
- Upload additional course materials
- Create and manage quizzes/assessments
- Monitor student progress
- Moderate forum discussions
- Provide feedback to students

**Access Level:** COURSE-SPECIFIC ACCESS

**Key Permissions:**
```
✅ View assigned courses
✅ Upload materials for assigned courses
✅ Create/Edit/Delete quizzes for assigned courses
✅ View student progress and analytics (assigned courses only)
✅ Pin/Unpin forum posts
✅ Reply to forum discussions
✅ Grade assessments manually (if needed)
✅ Export student reports (assigned courses only)
❌ Cannot manage other lecturers' courses
❌ Cannot access admin settings
❌ Cannot delete admin-uploaded materials
```

#### **MAHASISWA (Student)**
**Primary Responsibilities:**
- Study course materials independently
- Complete quizzes and assessments
- Participate in forum discussions
- Track personal learning progress
- Download materials for offline study

**Access Level:** READ-ONLY for materials, INTERACTIVE for assessments

**Key Permissions:**
```
✅ View enrolled courses
✅ Read all course materials
✅ Take quizzes/assessments
✅ View personal progress dashboard
✅ Download materials (PDF, slides)
✅ Post/Reply in forums
✅ Use AI chatbot
✅ Re-take remedial exams (if allowed)
❌ Cannot upload materials
❌ Cannot view other students' detailed progress
❌ Cannot edit course content
❌ Cannot pin forum posts
```

### 3.2 Registration & Authentication Flow

```
┌─────────────────────────────────────────────┐
│         PUBLIC ACCESS (No Login)             │
│  - Landing Page                              │
│  - About Page                                │
│  - Features Overview                         │
│  - Contact Form                              │
└─────────────────────────────────────────────┘
                    ↓
┌─────────────────────────────────────────────┐
│           REGISTRATION PAGE                  │
│  User Type Selection:                        │
│    ○ Mahasiswa (Student)                     │
│    ○ Dosen (Lecturer)                        │
│                                              │
│  [Admin accounts created manually by admin]  │
└─────────────────────────────────────────────┘
                    ↓
┌─────────────────────────────────────────────┐
│           ACCOUNT APPROVAL                   │
│  - Dosen: Requires admin approval            │
│  - Mahasiswa: Auto-approved OR approval      │
│    (configurable by admin)                   │
└─────────────────────────────────────────────┘
                    ↓
┌─────────────────────────────────────────────┐
│         LOGIN & DASHBOARD REDIRECT           │
│  - Admin → Admin Dashboard                   │
│  - Dosen → Lecturer Dashboard                │
│  - Mahasiswa → Student Dashboard             │
└─────────────────────────────────────────────┘
```

---

## 4. Core Features Specification

### 4.1 Manajemen Materi Pembelajaran (Learning Content Management)

#### **Feature Overview**
Centralized content management system for organizing course materials including PDFs, slides, videos, and text documents. Materials are structured hierarchically following curriculum requirements.

#### **Key Components**

**A. Content Upload System**
- **File Types Supported:**
  - Documents: PDF, DOCX, PPTX
  - Videos: MP4, WEBM (embedded or uploaded)
  - Images: JPG, PNG, GIF
  - Text: Rich text editor for inline content
  
- **Upload Interface:**
  ```
  Admin/Dosen Dashboard → Courses → [Select Course] → Upload Material
  
  Form Fields:
  - Title: [Text input]
  - Description: [Rich text editor]
  - Material Type: [Dropdown: PDF/Video/Slide/Text]
  - File Upload: [Drag & drop or browse]
  - Category: [Dropdown: Lecture/Exercise/Reading]
  - Tags: [Multi-select or comma-separated]
  - Order/Sequence: [Number input]
  - Prerequisite Material: [Dropdown: None/Previous materials]
  - Status: [Draft/Published]
  ```

**B. Content Categorization**
- **Hierarchical Structure:**
  ```
  Course
    └── Week/Module
          └── Topic
                └── Material (Lecture/Exercise/Reading)
  ```

- **Tagging System:**
  - Custom tags for searchability
  - Predefined categories (e.g., "Fundamental", "Advanced", "Practice")
  - Difficulty level (Beginner, Intermediate, Advanced)

**C. Unlock System (Progressive Learning)**
- Materials locked by default until prerequisites are met
- Unlock conditions:
  - Sequential: Complete previous material + pass quiz (≥ minimum score)
  - Time-based: Available after specific date (optional)
  - Manual unlock: Admin/Dosen can unlock for specific students

- **Visual Indicators:**
  ```
  🔒 Locked - Requirements not met
  🔓 Unlocked - Available to study
  ✅ Completed - Material viewed + quiz passed
  ⏳ In Progress - Material opened but not completed
  ```

**D. Content Viewer**
- **PDF Viewer:** Embedded viewer with zoom, page navigation
- **Video Player:** HTML5 player with playback controls, speed adjustment
- **Slide Viewer:** Full-screen mode, navigation arrows
- **Progress Tracking:** Auto-save reading position, watch time

#### **User Stories**
```
As an ADMIN:
- I want to upload default materials so all courses have baseline content
- I want to organize materials hierarchically so students follow a structured path
- I want to bulk upload materials so I can save time

As a DOSEN:
- I want to see admin-uploaded materials so I can use ready-made content
- I want to add supplementary materials so students get additional resources
- I want to reorder materials so the flow matches my teaching style

As a MAHASISWA:
- I want to see locked/unlocked materials so I know what's available
- I want to download materials so I can study offline
- I want to track my completion so I know my progress
```

#### **Technical Implementation Notes**
```php
// Database table: materials
CREATE TABLE materials (
    id INT PRIMARY KEY AUTO_INCREMENT,
    course_id INT,
    title VARCHAR(255),
    description TEXT,
    file_path VARCHAR(500),
    file_type ENUM('pdf','video','slide','text'),
    order_sequence INT,
    prerequisite_material_id INT NULL,
    uploaded_by INT (user_id),
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id),
    FOREIGN KEY (prerequisite_material_id) REFERENCES materials(id)
);

// Student progress tracking
CREATE TABLE student_material_progress (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT,
    material_id INT,
    status ENUM('not_started','in_progress','completed'),
    progress_percentage INT DEFAULT 0,
    last_accessed TIMESTAMP,
    completed_at TIMESTAMP NULL,
    FOREIGN KEY (student_id) REFERENCES users(id),
    FOREIGN KEY (material_id) REFERENCES materials(id)
);
```

---

### 4.2 Sistem Evaluasi & Quiz Interaktif

#### **Feature Overview**
Comprehensive assessment system with automated grading, multiple question types, and detailed analytics.

#### **Key Components**

**A. Quiz Builder (Dosen Interface)**
- **Question Types:**
  1. **Multiple Choice (Single Answer)**
     ```
     Question: What is PHP?
     ○ Programming Language [Correct]
     ○ Database System
     ○ Web Server
     ○ Operating System
     ```
  
  2. **Multiple Choice (Multiple Answers)**
     ```
     Question: Select all valid HTTP methods:
     ☑ GET [Correct]
     ☑ POST [Correct]
     ☐ SEND
     ☑ PUT [Correct]
     ```
  
  3. **Fill in the Blank**
     ```
     Question: PHP stands for _______ Hypertext Preprocessor.
     Answer: [Text input] (Correct: "Personal")
     ```
  
  4. **True/False**
     ```
     Question: MySQL is a NoSQL database.
     ○ True
     ● False [Correct]
     ```
  
  5. **Essay/Short Answer (Manual Grading)**
     ```
     Question: Explain the MVC architecture.
     Answer: [Textarea]
     ```

- **Quiz Configuration:**
  ```
  Quiz Settings Panel:
  - Quiz Title: [Text]
  - Description: [Textarea]
  - Duration: [Number] minutes (0 = no limit)
  - Passing Score: [Number] % (default: 70%)
  - Attempts Allowed: [Number] (0 = unlimited)
  - Randomize Questions: [Checkbox]
  - Randomize Options: [Checkbox]
  - Show Correct Answers After Submission: [Checkbox]
  - Allow Review Before Submit: [Checkbox]
  - Available From: [DateTime picker]
  - Available Until: [DateTime picker]
  - Prerequisite Material: [Dropdown]
  ```

**B. Question Bank System**
- Store reusable questions per course
- Tag questions by topic/difficulty
- Import/export questions (CSV, JSON)
- Duplicate detection

**C. Quiz Taking Interface (Student)**
```
┌────────────────────────────────────────────┐
│  Quiz: PHP Fundamentals - Week 1           │
│  Time Remaining: 45:23                     │
│  Progress: [████░░░░░░] 4/10 questions     │
├────────────────────────────────────────────┤
│  Question 4 of 10                          │
│                                            │
│  What does PHP stand for?                  │
│                                            │
│  ○ Personal Home Page                      │
│  ○ PHP: Hypertext Preprocessor             │
│  ○ Private Hosting Protocol                │
│  ○ Programming Helper Platform             │
│                                            │
│  [Flag for Review]                         │
│                                            │
│  [← Previous]  [Save]  [Next →]            │
└────────────────────────────────────────────┘
```

**D. Auto-Grading System**
- Instant grading for objective questions (MCQ, T/F, Fill-in-blank)
- Partial credit for multiple-answer questions
- Manual grading queue for essay questions
- Grade calculation: `(Correct Points / Total Points) × 100`

**E. Results & Feedback**
```
Quiz Results Page:
┌────────────────────────────────────────────┐
│  📊 Quiz Results                            │
│                                            │
│  Score: 85/100 (Pass ✅)                   │
│  Correct: 17/20 questions                  │
│  Time Taken: 38:15 / 45:00                 │
│  Submitted: Dec 6, 2024 10:45 AM           │
│                                            │
│  📈 Performance Breakdown:                  │
│  - Multiple Choice: 14/15 (93%)            │
│  - Fill in Blank: 3/5 (60%)                │
│                                            │
│  [View Detailed Solutions]                 │
│  [Download Certificate] (if passed)        │
└────────────────────────────────────────────┘
```

**F. Analytics Dashboard (Dosen/Admin)**
```
Quiz Analytics:
┌────────────────────────────────────────────┐
│  Quiz: PHP Fundamentals                    │
│  Total Attempts: 45 students               │
│  Average Score: 76.5%                      │
│  Pass Rate: 82% (37/45)                    │
│                                            │
│  📊 Score Distribution:                     │
│  90-100: ████████ (8 students)             │
│  80-89:  ██████████████ (14 students)      │
│  70-79:  ███████████ (11 students)         │
│  60-69:  █████ (5 students)                │
│  <60:    ███ (3 students)                  │
│                                            │
│  🔍 Difficult Questions:                    │
│  Q7: 32% correct (Consider revising)       │
│  Q12: 45% correct (Needs clarification)    │
│                                            │
│  [Export Report] [View Individual Results] │
└────────────────────────────────────────────┘
```

#### **Technical Implementation Notes**
```php
// Database tables
CREATE TABLE quizzes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    course_id INT,
    material_id INT NULL,
    title VARCHAR(255),
    description TEXT,
    duration INT DEFAULT 0, -- minutes, 0 = unlimited
    passing_score INT DEFAULT 70,
    max_attempts INT DEFAULT 1,
    randomize_questions BOOLEAN DEFAULT FALSE,
    randomize_options BOOLEAN DEFAULT FALSE,
    show_answers BOOLEAN DEFAULT TRUE,
    available_from DATETIME,
    available_until DATETIME,
    created_by INT,
    created_at TIMESTAMP
);

CREATE TABLE questions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    quiz_id INT,
    question_text TEXT,
    question_type ENUM('mcq_single','mcq_multiple','fill_blank','true_false','essay'),
    points INT DEFAULT 1,
    order_num INT,
    explanation TEXT NULL, -- shown after answer
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id)
);

CREATE TABLE question_options (
    id INT PRIMARY KEY AUTO_INCREMENT,
    question_id INT,
    option_text TEXT,
    is_correct BOOLEAN DEFAULT FALSE,
    order_num INT,
    FOREIGN KEY (question_id) REFERENCES questions(id)
);

CREATE TABLE quiz_attempts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    quiz_id INT,
    student_id INT,
    attempt_number INT,
    score DECIMAL(5,2),
    total_points INT,
    earned_points INT,
    time_taken INT, -- seconds
    started_at TIMESTAMP,
    submitted_at TIMESTAMP,
    status ENUM('in_progress','completed','grading'),
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id),
    FOREIGN KEY (student_id) REFERENCES users(id)
);

CREATE TABLE student_answers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    attempt_id INT,
    question_id INT,
    answer_text TEXT, -- for fill-blank, essay
    selected_option_id INT NULL, -- for MCQ
    is_correct BOOLEAN NULL,
    points_earned DECIMAL(5,2),
    manual_feedback TEXT NULL,
    FOREIGN KEY (attempt_id) REFERENCES quiz_attempts(id)
);
```

#### **Timer Implementation (JavaScript)**
```javascript
// Quiz timer with auto-submit
let timeRemaining = quizDuration * 60; // convert to seconds
let timerInterval;

function startTimer() {
    timerInterval = setInterval(() => {
        timeRemaining--;
        updateTimerDisplay();
        
        if (timeRemaining <= 0) {
            clearInterval(timerInterval);
            autoSubmitQuiz();
        }
        
        // Warning at 5 minutes remaining
        if (timeRemaining === 300) {
            showWarning("5 minutes remaining!");
        }
    }, 1000);
}

function updateTimerDisplay() {
    const minutes = Math.floor(timeRemaining / 60);
    const seconds = timeRemaining % 60;
    document.getElementById('timer').textContent = 
        `${minutes}:${seconds.toString().padStart(2, '0')}`;
}
```

---

### 4.3 Forum Diskusi dan Tanya Jawab Online

#### **Feature Overview**
Community-driven discussion platform where students can ask questions, share knowledge, and collaborate with peers and instructors.

#### **Key Components**

**A. Forum Structure**
```
Course Forums
  └── Topic/Week Forum
        └── Thread (Question/Discussion)
              └── Replies
                    └── Nested Replies (optional)
```

**B. Create Discussion Thread (Student/Dosen)**
```
New Discussion Form:
┌────────────────────────────────────────────┐
│  📝 New Discussion                          │
│                                            │
│  Title: [Text input, max 200 chars]       │
│                                            │
│  Category:                                 │
│  ○ Question (need help)                    │
│  ○ Discussion (share knowledge)            │
│  ○ Announcement (info sharing)             │
│                                            │
│  Related Material: [Dropdown - optional]   │
│                                            │
│  Content: [Rich text editor]               │
│  - Bold, italic, underline                 │
│  - Code blocks                             │
│  - Insert images                           │
│  - Insert links                            │
│                                            │
│  Tags: [Multi-select]                      │
│  #php #mysql #week3 #arrays                │
│                                            │
│  [Attach Files] (max 5MB)                  │
│                                            │
│  [Cancel]  [Save Draft]  [Post]            │
└────────────────────────────────────────────┘
```

**C. Thread Display**
```
┌────────────────────────────────────────────┐
│  📌 PINNED by Dosen                         │
│  How to set up XAMPP on Windows            │
│  Posted by: Dosen A │ 2 days ago           │
│  25 replies │ 150 views                    │
│  #tutorial #setup #beginner                │
├────────────────────────────────────────────┤
│  Help: Error connecting to MySQL           │
│  Posted by: Mahasiswa X │ 3 hours ago      │
│  5 replies │ 12 views                      │
│  #help #mysql #error                       │
├────────────────────────────────────────────┤
│  Discussion: Best practices for PHP code   │
│  Posted by: Mahasiswa Y │ 1 day ago        │
│  18 replies │ 45 views                     │
│  #discussion #best-practices               │
└────────────────────────────────────────────┘
```

**D. Reply Interface**
```
Thread View:
┌────────────────────────────────────────────┐
│  Error connecting to MySQL                 │
│  Posted by: Mahasiswa X │ Dec 6, 10:00 AM  │
│                                            │
│  I'm getting "Access denied for user       │
│  'root'@'localhost'" when trying to        │
│  connect. Here's my code:                  │
│                                            │
│  ```php                                    │
│  $conn = mysqli_connect("localhost",       │
│      "root", "password", "my_db");         │
│  ```                                       │
│                                            │
│  [👍 5]  [Reply]  [Share]  [Report]        │
├────────────────────────────────────────────┤
│  💬 5 Replies                              │
│                                            │
│  ┌─ Mahasiswa Y │ Dec 6, 10:15 AM          │
│  │  Check if your MySQL service is         │
│  │  running. Also verify the password.     │
│  │  [👍 2]  [Reply]                        │
│  │                                         │
│  │  ┌─ Mahasiswa X │ Dec 6, 10:20 AM       │
│  │  │  @MahasiswaY Thanks! Service was     │
│  │  │  not running. Fixed now! ✅          │
│  │  │  [👍 1]                              │
│  │  └─                                     │
│  └─                                        │
│                                            │
│  ┌─ 📌 Dosen A │ Dec 6, 10:30 AM           │
│  │  Great teamwork! Remember to always     │
│  │  check:                                 │
│  │  1. Service status                      │
│  │  2. Credentials                         │
│  │  3. Port number (3306)                  │
│  │  [👍 8]  [Reply]                        │
│  └─                                        │
│                                            │
│  [Write a reply...]                        │
└────────────────────────────────────────────┘
```

**E. Mention System**
- Type `@` to mention users
- Autocomplete dropdown appears
- Mentioned user receives notification

**F. Pin/Unpin Feature (Dosen Only)**
```
Dosen Actions on Thread:
- 📌 Pin to top (visible to all students)
- 🔓 Unpin
- 🔒 Lock thread (no more replies)
- 🗑️ Delete thread (soft delete)
- ✏️ Edit thread (own threads only)
```

**G. Search & Filter**
```
Forum Search:
- Search by keyword
- Filter by:
  - Category (Question/Discussion/Announcement)
  - Tags
  - Date range
  - Author
  - Status (Answered/Unanswered)
  - Pinned only
```

#### **Technical Implementation Notes**
```php
CREATE TABLE forum_threads (
    id INT PRIMARY KEY AUTO_INCREMENT,
    course_id INT,
    material_id INT NULL,
    author_id INT,
    title VARCHAR(255),
    content TEXT,
    category ENUM('question','discussion','announcement'),
    is_pinned BOOLEAN DEFAULT FALSE,
    is_locked BOOLEAN DEFAULT FALSE,
    view_count INT DEFAULT 0,
    reply_count INT DEFAULT 0,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id),
    FOREIGN KEY (author_id) REFERENCES users(id)
);

CREATE TABLE forum_replies (
    id INT PRIMARY KEY AUTO_INCREMENT,
    thread_id INT,
    parent_reply_id INT NULL, -- for nested replies
    author_id INT,
    content TEXT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (thread_id) REFERENCES forum_threads(id),
    FOREIGN KEY (parent_reply_id) REFERENCES forum_replies(id),
    FOREIGN KEY (author_id) REFERENCES users(id)
);

CREATE TABLE forum_tags (
    id INT PRIMARY KEY AUTO_INCREMENT,
    thread_id INT,
    tag_name VARCHAR(50),
    FOREIGN KEY (thread_id) REFERENCES forum_threads(id)
);

CREATE TABLE forum_mentions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    thread_id INT NULL,
    reply_id INT NULL,
    mentioned_user_id INT,
    mentioned_by_user_id INT,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP,
    FOREIGN KEY (mentioned_user_id) REFERENCES users(id)
);
```

---

### 4.4 Fitur AI Chatbot

#### **Feature Overview**
AI-powered assistant providing instant help, explanations, and learning guidance 24/7 without requiring lecturer availability.

#### **Key Components**

**A. Chatbot Interface**
```
Floating Chat Widget (Bottom Right):
┌────────────────────────────────────────────┐
│  🤖 EduFlip Assistant                       │
│  [Minimize] [Close]                        │
├────────────────────────────────────────────┤
│  💬 Chat History                           │
│                                            │
│  🤖 Bot:                                   │
│  Hi! I'm your learning assistant. How can │
│  I help you today?                         │
│                                            │
│  👤 You:                                   │
│  What is a foreign key in MySQL?           │
│                                            │
│  🤖 Bot:                                   │
│  A foreign key is a column (or set of      │
│  columns) that creates a link between      │
│  two tables. It references the primary     │
│  key of another table...                   │
│                                            │
│  [Show related materials]                  │
│                                            │
│  👤 You:                                   │
│  Can you give me an example?               │
│                                            │
│  🤖 Bot:                                   │
│  Sure! Here's a simple example:            │
│  ```sql                                    │
│  CREATE TABLE orders (                     │
│    order_id INT PRIMARY KEY,               │
│    customer_id INT,                        │
│    FOREIGN KEY (customer_id)               │
│      REFERENCES customers(id)              │
│  );                                        │
│  ```                                       │
│                                            │
│  [Copy code] [Explain step-by-step]        │
│                                            │
├────────────────────────────────────────────┤
│  Type your message...                      │
│  [📎 Attach] [Send]                        │
└────────────────────────────────────────────┘
```

**B. AI Capabilities**
1. **Material Explanation:**
   - Answer questions about course content
   - Simplify complex concepts
   - Provide examples and analogies

2. **Code Help:**
   - Debug code snippets
   - Explain error messages
   - Suggest best practices

3. **Study Guidance:**
   - Recommend next steps
   - Suggest related materials
   - Create study plans

4. **Quiz Preparation:**
   - Generate practice questions
   - Review key concepts
   - Explain quiz topics

**C. Smart Features**
- **Quick Actions:**
  ```
  Suggested Questions:
  - Explain [current material topic]
  - What should I study next?
  - Help me with this error
  - Summarize this material
  ```

- **Material Linking:**
  - Bot can suggest relevant materials from the course
  - Deep link to specific sections
  - Recommend prerequisite materials if struggling

- **Code Formatting:**
  - Syntax highlighting for code snippets
  - Copy-to-clipboard functionality

**D. Conversation History**
- Save chat history per user
- Search previous conversations
- Export chat transcript

**E. Context Awareness (Optional - Future Enhancement)**
```
Context provided to AI:
- Current material student is viewing
- Student's progress (completed materials)
- Recent quiz performance
- Course syllabus
```

#### **API Integration Guide**

**Option 1: OpenAI API**
```javascript
// chatbot.js
async function sendMessageToAI(userMessage) {
    const response = await fetch('/api/chatbot.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            message: userMessage,
            conversation_id: getCurrentConversationId(),
            user_id: getCurrentUserId()
        })
    });
    
    const data = await response.json();
    return data.reply;
}
```

```php
// api/chatbot.php
<?php
require_once 'vendor/autoload.php';

$apiKey = getenv('OPENAI_API_KEY'); // Store in .env
$userMessage = $_POST['message'];

$ch = curl_init('https://api.openai.com/v1/chat/completions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $apiKey
]);

$data = json_encode([
    'model' => 'gpt-4',
    'messages' => [
        [
            'role' => 'system',
            'content' => 'You are EduFlip Assistant, a helpful learning companion for university students studying web development. Provide clear, concise explanations with examples.'
        ],
        [
            'role' => 'user',
            'content' => $userMessage
        ]
    ],
    'temperature' => 0.7,
    'max_tokens' => 500
]);

curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
$response = curl_exec($ch);
curl_close($ch);

$result = json_decode($response, true);
echo json_encode([
    'reply' => $result['choices'][0]['message']['content']
]);
?>
```

**Option 2: Claude API (Anthropic)**
```php
// Using Claude for more detailed explanations
$ch = curl_init('https://api.anthropic.com/v1/messages');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'x-api-key: ' . getenv('ANTHROPIC_API_KEY'),
    'anthropic-version: 2023-06-01'
]);

$data = json_encode([
    'model' => 'claude-3-sonnet-20240229',
    'max_tokens': 1024,
    'messages' => [
        [
            'role' => 'user',
            'content' => $userMessage
        ]
    ]
]);
```

**Rate Limiting (Optional):**
```php
// Limit API calls per user
CREATE TABLE chatbot_usage (
    user_id INT,
    date DATE,
    message_count INT DEFAULT 0,
    PRIMARY KEY (user_id, date)
);

// Check before API call
$today = date('Y-m-d');
$usage = getUsageCount($userId, $today);
if ($usage >= 50) { // 50 messages per day limit
    return ['error' => 'Daily limit reached'];
}
```

#### **Technical Implementation Notes**
```php
CREATE TABLE chatbot_conversations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE chatbot_messages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    conversation_id INT,
    role ENUM('user','assistant','system'),
    content TEXT,
    timestamp TIMESTAMP,
    FOREIGN KEY (conversation_id) REFERENCES chatbot_conversations(id)
);
```

---

### 4.5 Mode Offline / Download Materi

#### **Feature Overview**
Students can download course materials for offline study. Progress is automatically synchronized when they reconnect to the internet.

#### **Key Components**

**A. Download Interface**
```
Material View Page:
┌────────────────────────────────────────────┐
│  📄 Week 3: PHP Arrays                     │
│  Type: PDF Document │ Size: 2.5 MB         │
│                                            │
│  [📥 Download for Offline Study]           │
│  [📖 Read Online]                          │
│                                            │
│  Downloads available:                      │
│  • PDF (optimized for mobile)              │
│  • Slides (PPTX)                           │
│  • Video (MP4 - 720p) [Premium]            │
└────────────────────────────────────────────┘
```

**B. Bulk Download Feature**
```
Course Materials Page:
┌────────────────────────────────────────────┐
│  Select materials to download:             │
│                                            │
│  Week 1:                                   │
│  ☑ Introduction to PHP (2.1 MB)            │
│  ☑ PHP Syntax Basics (1.8 MB)              │
│                                            │
│  Week 2:                                   │
│  ☑ Variables and Data Types (3.2 MB)       │
│  ☐ Operators (2.5 MB)                      │
│                                            │
│  Total size: 7.1 MB                        │
│  [Download Selected (3 files)]             │
│  [Download All Week 1-2 (10.5 MB)]         │
└────────────────────────────────────────────┘
```

**C. Progress Sync Mechanism**
```
Sync Status Indicator:
┌────────────────────────────────────────────┐
│  📶 Connection Status: Online               │
│  ✅ All progress synced                     │
│  Last sync: 2 minutes ago                  │
│                                            │
│  Offline changes pending sync:             │
│  • Week 3 material viewed (85% complete)   │
│  • Quiz attempt saved locally              │
│                                            │
│  [Sync Now]  [View Sync Log]               │
└────────────────────────────────────────────┘
```

**D. Offline Reading Tracker**
```javascript
// Track reading progress locally
// When offline, save to localStorage
function trackProgress(materialId, percentage) {
    if (navigator.onLine) {
        // Send to server immediately
        saveProgressToServer(materialId, percentage);
    } else {
        // Save to localStorage for later sync
        let offlineProgress = JSON.parse(
            localStorage.getItem('offlineProgress') || '[]'
        );
        
        offlineProgress.push({
            material_id: materialId,
            progress: percentage,
            timestamp: new Date().toISOString()
        });
        
        localStorage.setItem('offlineProgress', 
            JSON.stringify(offlineProgress));
        
        showOfflineIndicator();
    }
}

// Auto-sync when connection restored
window.addEventListener('online', function() {
    syncOfflineProgress();
});

function syncOfflineProgress() {
    let offlineProgress = JSON.parse(
        localStorage.getItem('offlineProgress') || '[]'
    );
    
    if (offlineProgress.length === 0) return;
    
    fetch('/api/sync-progress.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            progress_data: offlineProgress
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            localStorage.removeItem('offlineProgress');
            showSyncSuccess();
        }
    });
}
```

**E. Download Manager**
```
My Downloads:
┌────────────────────────────────────────────┐
│  📥 Downloaded Materials (15 files)         │
│  Total storage used: 45.2 MB / 500 MB      │
│                                            │
│  ✅ Week 1: Introduction (2.1 MB)          │
│     Downloaded: Dec 1, 2024                │
│     Last accessed: 2 hours ago             │
│     [Delete]  [Re-download]                │
│                                            │
│  ✅ Week 2: PHP Basics (3.8 MB)            │
│     Downloaded: Dec 3, 2024                │
│     Last accessed: Yesterday               │
│     [Delete]  [Re-download]                │
│                                            │
│  [Clear All Downloads]                     │
│  [Download Recommendations (3 new)]        │
└────────────────────────────────────────────┘
```

#### **Technical Implementation Notes**
```php
// Track downloads
CREATE TABLE material_downloads (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    material_id INT,
    downloaded_at TIMESTAMP,
    file_size INT, -- bytes
    device_info VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (material_id) REFERENCES materials(id)
);

// Offline progress sync
CREATE TABLE offline_progress_sync (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    material_id INT,
    progress_percentage INT,
    recorded_at TIMESTAMP, -- offline timestamp
    synced_at TIMESTAMP, -- when uploaded to server
    FOREIGN KEY (user_id) REFERENCES users(id)
);
```

---

### 4.6 Integrated Website (W3Schools Reference)

#### **Feature Overview**
Seamless integration with W3Schools for additional learning resources, code examples, and interactive tutorials.

#### **Key Components**

**A. Embedded W3Schools**
```html
<!-- Embedded iframe approach -->
<div class="w3schools-container">
    <h3>📚 Additional Resources: W3Schools PHP Tutorial</h3>
    <iframe 
        src="https://www.w3schools.com/php/" 
        width="100%" 
        height="600px"
        frameborder="0"
        sandbox="allow-same-origin allow-scripts allow-popups allow-forms"
    ></iframe>
    <a href="https://www.w3schools.com/php/" target="_blank">
        Open in new tab →
    </a>
</div>
```

**B. Quick Links Panel**
```
Material Sidebar - External Resources:
┌────────────────────────────────────────────┐
│  🔗 Quick References                        │
│                                            │
│  W3Schools:                                │
│  • PHP Tutorial                            │
│  • MySQL Tutorial                          │
│  • JavaScript Basics                       │
│  • HTML Reference                          │
│  • CSS Reference                           │
│                                            │
│  MDN Web Docs:                             │
│  • JavaScript Guide                        │
│  • Web APIs                                │
│                                            │
│  PHP.net:                                  │
│  • Official Documentation                  │
│  • Function Reference                      │
└────────────────────────────────────────────┘
```

**C. Contextual Recommendations**
```php
// Recommend relevant W3Schools pages based on current material
function getW3SchoolsRecommendations($materialTopic) {
    $recommendations = [
        'arrays' => 'https://www.w3schools.com/php/php_arrays.asp',
        'loops' => 'https://www.w3schools.com/php/php_looping.asp',
        'functions' => 'https://www.w3schools.com/php/php_functions.asp',
        'mysql' => 'https://www.w3schools.com/php/php_mysql_intro.asp'
    ];
    
    return $recommendations[$materialTopic] ?? null;
}
```

**D. Practice Code Section**
```
Material Page - Try It Yourself:
┌────────────────────────────────────────────┐
│  💻 Practice Code                           │
│                                            │
│  Based on this material, try:              │
│  [Open W3Schools PHP Tryit Editor]         │
│                                            │
│  Or practice here:                         │
│  <textarea id="code-editor">               │
│  <?php                                     │
│  // Your code here                         │
│  ?>                                        │
│  </textarea>                               │
│                                            │
│  [Run Code] [Reset] [Save]                 │
│                                            │
│  Output:                                   │
│  <div id="output"></div>                   │
└────────────────────────────────────────────┘
```

#### **Technical Implementation Notes**
```php
// Track external resource usage
CREATE TABLE external_resource_clicks (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    material_id INT,
    resource_url VARCHAR(500),
    clicked_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
```

---

### 4.7 Mode Remedial / Ujian Ulang

#### **Feature Overview**
Students who fail to meet the passing score can retake quizzes with different question sets (if randomization is enabled) or the same questions for review.

#### **Key Components**

**A. Remedial Eligibility Check**
```
Quiz Results Page (Failed):
┌────────────────────────────────────────────┐
│  ❌ Quiz Not Passed                         │
│                                            │
│  Your Score: 62/100                        │
│  Passing Score: 70/100                     │
│  You need 8 more points to pass.           │
│                                            │
│  📚 Recommended Actions:                    │
│  1. Review material: "PHP Arrays Basics"   │
│  2. Check questions you missed (12/20)     │
│  3. Practice with AI chatbot               │
│                                            │
│  Remedial Options:                         │
│  • Retake immediately (Attempt 2/3)        │
│  • Schedule consultation with lecturer     │
│  • Study more and retake later             │
│                                            │
│  [📖 Review Material]  [🔄 Retake Quiz]    │
└────────────────────────────────────────────┘
```

**B. Attempt Limits & Cooldown**
```php
// Quiz settings for remedial
$quizSettings = [
    'max_attempts' => 3,
    'cooldown_hours' => 24, // Wait 24h between attempts
    'score_policy' => 'highest', // or 'latest', 'average'
    'must_review_before_retake' => true
];
```

**C. Different Question Sets (Randomization)**
```
Retake Configuration:
┌────────────────────────────────────────────┐
│  🔄 Quiz Retake - Attempt 2                 │
│                                            │
│  Note: Questions will be different from    │
│  your first attempt (randomized from       │
│  question bank).                           │
│                                            │
│  Before retaking:                          │
│  ☑ Reviewed incorrect answers              │
│  ☑ Studied recommended materials           │
│  ☐ Consulted with lecturer (optional)      │
│                                            │
│  Next available attempt: Now               │
│  Attempts remaining: 2/3                   │
│                                            │
│  [Start Retake]  [Cancel]                  │
└────────────────────────────────────────────┘
```

**D. Mandatory Consultation (After Multiple Failures)**
```
After 3 Failed Attempts:
┌────────────────────────────────────────────┐
│  ⚠️ Consultation Required                   │
│                                            │
│  You've used all 3 attempts.               │
│  Current best score: 65/100                │
│                                            │
│  To unlock additional attempts, you must:  │
│  1. Schedule consultation with lecturer    │
│  2. Complete remedial study plan           │
│  3. Get lecturer approval for retake       │
│                                            │
│  [Request Consultation]                    │
│  [View Study Plan]                         │
└────────────────────────────────────────────┘
```

**E. Lecturer Approval System**
```
Dosen Dashboard - Remedial Requests:
┌────────────────────────────────────────────┐
│  📋 Pending Remedial Approvals (5)          │
│                                            │
│  Student: Mahasiswa A                      │
│  Quiz: PHP Fundamentals Week 2             │
│  Attempts: 3/3 (Best: 65%)                 │
│  Last attempt: 2 days ago                  │
│  Reason: "Still confused about arrays"     │
│                                            │
│  [View Progress]  [Chat with Student]      │
│  [✅ Approve]  [❌ Recommend More Study]    │
│                                            │
│  If approved, grant:                       │
│  • Additional attempts: [1] [2] [3]        │
│  • Extended deadline: [Date picker]        │
│                                            │
│  [Approve with Note]                       │
└────────────────────────────────────────────┘
```

#### **Technical Implementation Notes**
```php
CREATE TABLE quiz_remedial_requests (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT,
    quiz_id INT,
    attempts_used INT,
    best_score DECIMAL(5,2),
    reason TEXT,
    status ENUM('pending','approved','rejected'),
    requested_at TIMESTAMP,
    reviewed_by INT NULL, -- dosen_id
    reviewed_at TIMESTAMP NULL,
    additional_attempts_granted INT DEFAULT 0,
    notes TEXT,
    FOREIGN KEY (student_id) REFERENCES users(id),
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id)
);

// Cooldown check
function canRetakeQuiz($studentId, $quizId) {
    $lastAttempt = getLastAttemptTime($studentId, $quizId);
    $cooldown = 24 * 3600; // 24 hours in seconds
    
    if (time() - $lastAttempt < $cooldown) {
        $hoursLeft = ceil(($cooldown - (time() - $lastAttempt)) / 3600);
        return [
            'can_retake' => false,
            'message' => "Please wait $hoursLeft more hours before retaking."
        ];
    }
    
    return ['can_retake' => true];
}
```

---

### 4.8 Sistem Ujian Unlocking

#### **Feature Overview**
Progressive content unlocking system that ensures students master prerequisites before advancing to more complex materials.

#### **Key Components**

**A. Unlocking Logic**
```
Material Unlock Requirements:
┌────────────────────────────────────────────┐
│  Week 2: PHP Functions                     │
│  Status: 🔒 Locked                          │
│                                            │
│  Requirements to unlock:                   │
│  ☑ Complete "Week 1: PHP Basics"           │
│  ☐ Pass "Week 1 Quiz" (≥70%)               │
│                                            │
│  Your progress:                            │
│  • Material viewed: Yes ✅                 │
│  • Quiz score: 62% ❌ (Need 70%)           │
│                                            │
│  [Retake Week 1 Quiz]                      │
└────────────────────────────────────────────┘
```

**B. Visual Progress Tracker**
```
Course Roadmap:
┌────────────────────────────────────────────┐
│  PHP Programming Course                    │
│                                            │
│  ✅ Week 1: Introduction                   │
│      └─ Quiz: 85% ✅                       │
│           │                                │
│  ✅ Week 2: Basics                         │
│      └─ Quiz: 78% ✅                       │
│           │                                │
│  🔓 Week 3: Arrays (Current)               │
│      └─ Quiz: Not attempted                │
│           │                                │
│  🔒 Week 4: Functions                      │
│      └─ Requires: Week 3 completion        │
│           │                                │
│  🔒 Week 5: OOP                            │
│      └─ Requires: Week 4 completion        │
└────────────────────────────────────────────┘
```

**C. Unlocking Rules Configuration (Admin/Dosen)**
```
Material Settings:
┌────────────────────────────────────────────┐
│  Unlock Configuration                      │
│                                            │
│  Unlock Type:                              │
│  ○ Sequential (after previous material)    │
│  ● Prerequisite-based (custom rules)       │
│  ○ Date-based (available after date)       │
│  ○ Always unlocked                         │
│                                            │
│  If Prerequisite-based:                    │
│  Select prerequisite material:             │
│  [Dropdown: Week 1 - PHP Basics]           │
│                                            │
│  Required conditions:                      │
│  ☑ Material must be viewed (100%)          │
│  ☑ Quiz must be passed                     │
│     Minimum score: [70]%                   │
│                                            │
│  Grace period after unlocking:             │
│  [14] days (0 = no deadline)               │
│                                            │
│  [Save Settings]                           │
└────────────────────────────────────────────┘
```

**D. Bulk Unlock (Emergency Override)**
```
Admin Panel - Emergency Unlock:
┌────────────────────────────────────────────┐
│  ⚠️ Emergency Material Unlock               │
│                                            │
│  Use this to manually unlock materials     │
│  for specific students (e.g., technical    │
│  issues, special arrangements).            │
│                                            │
│  Select Student: [Dropdown]                │
│  Select Material: [Dropdown]               │
│  Reason: [Textarea]                        │
│                                            │
│  ☐ Also unlock all subsequent materials    │
│  ☐ Send notification to student            │
│                                            │
│  [Unlock Material]  [Cancel]               │
└────────────────────────────────────────────┘
```

**E. Notification System**
```
Unlocked Material Notification:
┌────────────────────────────────────────────┐
│  🎉 New Material Unlocked!                  │
│                                            │
│  Congratulations! You've unlocked:         │
│  "Week 3: PHP Arrays and Loops"            │
│                                            │
│  You passed Week 2 Quiz with 78%           │
│                                            │
│  [Start Learning →]  [Remind Me Later]     │
└────────────────────────────────────────────┘
```

#### **Technical Implementation Notes**
```php
// Check if material is unlocked for student
function isMaterialUnlocked($studentId, $materialId) {
    $material = getMaterialDetails($materialId);
    
    // No prerequisite = always unlocked
    if (!$material['prerequisite_material_id']) {
        return true;
    }
    
    // Check prerequisite completion
    $prereq = getStudentProgress(
        $studentId, 
        $material['prerequisite_material_id']
    );
    
    if ($prereq['status'] != 'completed') {
        return false;
    }
    
    // Check quiz requirement
    if ($material['requires_quiz_pass']) {
        $quizScore = getQuizScore(
            $studentId, 
            $material['prerequisite_material_id']
        );
        
        if ($quizScore < $material['minimum_quiz_score']) {
            return false;
        }
    }
    
    return true;
}

// Auto-unlock when conditions met
function checkAndUnlockNextMaterials($studentId, $completedMaterialId) {
    // Find materials that have this as prerequisite
    $dependentMaterials = getMaterialsByPrerequisite($completedMaterialId);
    
    foreach ($dependentMaterials as $material) {
        if (isMaterialUnlocked($studentId, $material['id'])) {
            // Send unlock notification
            sendUnlockNotification($studentId, $material['id']);
        }
    }
}
```

---

### 4.9 Dashboard Kemajuan Mahasiswa

#### **Feature Overview**
Comprehensive progress tracking dashboard showing completion rates, quiz scores, learning streaks, and areas needing improvement.

#### **Key Components**

**A. Student Dashboard (Main View)**
```
Welcome back, Mahasiswa A! 👋
┌────────────────────────────────────────────┐
│  📊 Your Learning Progress                  │
│                                            │
│  Overall Progress: [████████░░] 78%        │
│                                            │
│  Current Courses (3):                      │
│  ┌──────────────────────────────────────┐  │
│  │ 📘 PHP Programming                    │  │
│  │ Progress: [████████░░] 85%            │  │
│  │ Next: Week 4 - Functions              │  │
│  │ Quiz Average: 82%                     │  │
│  └──────────────────────────────────────┘  │
│                                            │
│  ┌──────────────────────────────────────┐  │
│  │ 📗 MySQL Database                     │  │
│  │ Progress: [██████░░░░] 60%            │  │
│  │ Next: Week 3 - Joins                  │  │
│  │ Quiz Average: 75%                     │  │
│  └──────────────────────────────────────┘  │
│                                            │
│  🔥 Learning Streak: 7 days                │
│  ⏰ Study time this week: 8h 32m           │
│  🎯 Materials completed: 24/30             │
│  ✅ Quizzes passed: 18/20                  │
└────────────────────────────────────────────┘
```

**B. Detailed Course Progress**
```
Course: PHP Programming - Detailed View
┌────────────────────────────────────────────┐
│  📈 Progress Breakdown                      │
│                                            │
│  Materials:                                │
│  Week 1: ████████████ 100% (5/5)          │
│  Week 2: ████████████ 100% (6/6)          │
│  Week 3: ████████░░░░  80% (4/5)          │
│  Week 4: ░░░░░░░░░░░░   0% (0/6) 🔒       │
│                                            │
│  Quiz Performance:                         │
│  ┌────────────────────────────────────┐   │
│  │ Week 1 Quiz: 85% ✅ (Passed)       │   │
│  │ Week 2 Quiz: 78% ✅ (Passed)       │   │
│  │ Week 3 Quiz: 92% ✅ (Passed)       │   │
│  │ Week 4 Quiz: 🔒 Locked             │   │
│  └────────────────────────────────────┘   │
│                                            │
│  Time Investment:                          │
│  Total study time: 12h 45m                 │
│  Average per material: 28 minutes          │
│  Last activity: 2 hours ago                │
│                                            │
│  Forum Participation:                      │
│  • Questions asked: 5                      │
│  • Answers provided: 12                    │
│  • Reputation: ⭐⭐⭐⭐ (4.2/5)              │
└────────────────────────────────────────────┘
```

**C. Performance Analytics**
```
Quiz Performance Analysis:
┌────────────────────────────────────────────┐
│  📊 Strengths & Weaknesses                  │
│                                            │
│  💪 Strong Topics:                          │
│  • PHP Syntax: 95% avg                     │
│  • Arrays: 92% avg                         │
│  • Loops: 88% avg                          │
│                                            │
│  ⚠️ Needs Improvement:                      │
│  • Functions: 65% avg (Below target)       │
│  • File Handling: 58% avg (Below target)   │
│                                            │
│  Recommendations:                          │
│  1. Review "Functions" material            │
│  2. Practice with AI chatbot               │
│  3. Join study group for File Handling     │
│                                            │
│  [Generate Study Plan]                     │
└────────────────────────────────────────────┘
```

**D. Gamification Elements**
```
Achievements & Badges:
┌────────────────────────────────────────────┐
│  🏆 Your Achievements (8/20 unlocked)       │
│                                            │
│  ✅ First Steps (Complete first material)  │
│  ✅ Quiz Master (Pass 10 quizzes)          │
│  ✅ Helpful Peer (Answer 10 forum posts)   │
│  ✅ Week Warrior (7-day streak)            │
│  🔒 Perfect Score (Get 100% on any quiz)   │
│  🔒 Course Champion (Complete full course) │
│                                            │
│  Leaderboard Position:                     │
│  #23 out of 150 students (Top 15%)         │
│                                            │
│  [View Full Leaderboard]                   │
└────────────────────────────────────────────┘
```

**E. Dosen View - Class Analytics**
```
Dosen Dashboard - PHP Programming Class:
┌────────────────────────────────────────────┐
│  👥 Class Overview (45 students)            │
│                                            │
│  Average Progress: [██████░░░░] 64%        │
│  Average Quiz Score: 74.5%                 │
│  Pass Rate: 82% (37/45)                    │
│                                            │
│  Progress Distribution:                    │
│  90-100%: ████ (4 students)                │
│  70-89%:  ████████████ (18 students)       │
│  50-69%:  ████████ (15 students)           │
│  <50%:    ████ (8 students) ⚠️             │
│                                            │
│  Students Needing Attention:               │
│  • Mahasiswa X: 35% progress, 2 failed quizzes
│  • Mahasiswa Y: 42% progress, low engagement
│                                            │
│  [Export Report]  [Send Reminder Emails]   │
└────────────────────────────────────────────┘
```

#### **Technical Implementation Notes**
```php
// Dashboard queries
function getStudentDashboardData($studentId) {
    return [
        'enrolled_courses' => getEnrolledCourses($studentId),
        'overall_progress' => calculateOverallProgress($studentId),
        'recent_activity' => getRecentActivity($studentId, 10),
        'upcoming_deadlines' => getUpcomingDeadlines($studentId),
        'quiz_stats' => getQuizStatistics($studentId),
        'forum_stats' => getForumStatistics($studentId),
        'achievements' => getAchievements($studentId),
        'learning_streak' => getLearningStreak($studentId)
    ];
}

// Calculate progress
function calculateOverallProgress($studentId) {
    $courses = getEnrolledCourses($studentId);
    $totalProgress = 0;
    
    foreach ($courses as $course) {
        $completed = countCompletedMaterials($studentId, $course['id']);
        $total = countTotalMaterials($course['id']);
        $progress = ($completed / $total) * 100;
        $totalProgress += $progress;
    }
    
    return $totalProgress / count($courses);
}
```

---

### 4.10 Pencarian & Navigasi Pintar

#### **Feature Overview**
Intelligent search system with filters, suggestions, and quick navigation to help students find materials and information quickly.

#### **Key Components**

**A. Global Search Bar**
```
Header Search:
┌────────────────────────────────────────────┐
│  🔍 Search materials, quizzes, forums...    │
│     [Type to search]                       │
│                                            │
│  Quick suggestions:                        │
│  • PHP arrays (Material)                   │
│  • Week 3 Quiz (Assessment)                │
│  • Error connecting MySQL (Forum)          │
│  • What is MVC? (FAQ)                      │
└────────────────────────────────────────────┘
```

**B. Advanced Search Filters**
```
Search Results Page:
┌────────────────────────────────────────────┐
│  Search: "mysql connection"                │
│  Found 23 results                          │
│                                            │
│  Filters:                                  │
│  Content Type:                             │
│  ☑ All                                     │
│  ☐ Materials only                          │
│  ☐ Quizzes only                            │
│  ☐ Forum posts only                        │
│                                            │
│  Course:                                   │
│  ☑ All courses                             │
│  ☐ PHP Programming                         │
│  ☐ MySQL Database                          │
│                                            │
│  Status:                                   │
│  ☑ All                                     │
│  ☐ Unlocked only                           │
│  ☐ Completed only                          │
│                                            │
│  Sort by:                                  │
│  ● Relevance                               │
│  ○ Date (newest)                           │
│  ○ Popularity                              │
└────────────────────────────────────────────┘
```

**C. Smart Suggestions & Auto-complete**
```javascript
// Real-time search with debouncing
let searchTimeout;
const searchInput = document.getElementById('search');

searchInput.addEventListener('input', function(e) {
    clearTimeout(searchTimeout);
    
    searchTimeout = setTimeout(() => {
        const query = e.target.value;
        if (query.length >= 2) {
            fetchSuggestions(query);
        }
    }, 300); // Wait 300ms after user stops typing
});

async function fetchSuggestions(query) {
    const response = await fetch(`/api/search-suggestions.php?q=${query}`);
    const suggestions = await response.json();
    displaySuggestions(suggestions);
}
```

**D. Contextual Navigation**
```
Material Breadcrumb Navigation:
┌────────────────────────────────────────────┐
│  Home > Courses > PHP Programming >        │
│  Week 3 > Arrays and Loops                 │
│                                            │
│  Quick Jump:                               │
│  [← Previous: Variables]  [Next: Functions →]
│                                            │
│  In this section:                          │
│  • Introduction to Arrays (Current)        │
│  • Indexed Arrays                          │
│  • Associative Arrays                      │
│  • Multidimensional Arrays                 │
│  • Array Functions                         │
└────────────────────────────────────────────┘
```

**E. Topic-based Navigation**
```
Browse by Topic:
┌────────────────────────────────────────────┐
│  📚 Learning Paths                          │
│                                            │
│  Basics:                                   │
│  • Syntax & Variables (5 materials)        │
│  • Data Types (3 materials)                │
│  • Operators (4 materials)                 │
│                                            │
│  Intermediate:                             │
│  • Control Structures (6 materials)        │
│  • Functions (8 materials)                 │
│  • Arrays (7 materials)                    │
│                                            │
│  Advanced:                                 │
│  • OOP (10 materials) 🔒                   │
│  • Database Integration (12 materials) 🔒  │
│  • Security (8 materials) 🔒               │
└────────────────────────────────────────────┘
```

**F. FAQs & Quick Help**
```
Help Center - FAQs:
┌────────────────────────────────────────────┐
│  ❓ Frequently Asked Questions              │
│                                            │
│  Getting Started:                          │
│  • How do I enroll in a course?            │
│  • How does the unlock system work?        │
│  • Can I download materials?               │
│                                            │
│  Quizzes & Assessments:                    │
│  • How many attempts do I get?             │
│  • What is the passing score?              │
│  • Can I retake a quiz?                    │
│                                            │
│  Technical Issues:                         │
│  • Video not playing                       │
│  • Can't download PDF                      │
│  • Login problems                          │
│                                            │
│  [Ask AI Assistant] if not found           │
└────────────────────────────────────────────┘
```

#### **Technical Implementation Notes**
```php
// Full-text search implementation
CREATE TABLE search_index (
    id INT PRIMARY KEY AUTO_INCREMENT,
    content_type ENUM('material','quiz','forum','faq'),
    content_id INT,
    title VARCHAR(255),
    content TEXT,
    tags TEXT,
    course_id INT,
    FULLTEXT(title, content, tags)
);

// Search query
function performSearch($query, $filters = []) {
    $sql = "SELECT * FROM search_index WHERE 
            MATCH(title, content, tags) AGAINST(? IN NATURAL LANGUAGE MODE)";
    
    $params = [$query];
    
    if (!empty($filters['content_type'])) {
        $sql .= " AND content_type = ?";
        $params[] = $filters['content_type'];
    }
    
    if (!empty($filters['course_id'])) {
        $sql .= " AND course_id = ?";
        $params[] = $filters['course_id'];
    }
    
    return executeQuery($sql, $params);
}

// Auto-suggest
function getSearchSuggestions($query) {
    $sql = "SELECT title, content_type FROM search_index 
            WHERE title LIKE ? LIMIT 5";
    return executeQuery($sql, ["%$query%"]);
}
```

---

### 4.11 Sistem Login & Profil Pengguna

#### **Feature Overview**
Secure authentication system with role-based access control and comprehensive user profile management.

#### **Key Components**

**A. Login Page**
```
Login Interface:
┌────────────────────────────────────────────┐
│         Welcome to EduFlip 📚               │
│                                            │
│  Email:                                    │
│  [_________________________________]       │
│                                            │
│  Password:                                 │
│  [_________________________________] [👁️]  │
│                                            │
│  ☐ Remember me                             │
│                                            │
│  [Login]                                   │
│                                            │
│  [Forgot Password?]                        │
│                                            │
│  Don't have an account?                    │
│  [Register as Student] [Register as Dosen] │
└────────────────────────────────────────────┘
```

**B. Registration Forms**

**Student Registration:**
```
┌────────────────────────────────────────────┐
│  Create Student Account                    │
│                                            │
│  Full Name: [_______________________]      │
│  Student ID (NIM): [_________________]     │
│  Email: [____________________________]     │
│  Phone: [____________________________]     │
│  Password: [_________________________]     │
│  Confirm Password: [_________________]     │
│                                            │
│  Study Program:                            │
│  [Dropdown: Informatics/SI/etc.]           │
│                                            │
│  Batch/Year: [____]                        │
│                                            │
│  ☑ I agree to Terms & Conditions           │
│                                            │
│  [Register]  [Back to Login]               │
└────────────────────────────────────────────┘
```

**Lecturer Registration:**
```
┌────────────────────────────────────────────┐
│  Lecturer Registration Request             │
│                                            │
│  Full Name: [_______________________]      │
│  Lecturer ID (NIP): [_________________]    │
│  Email: [____________________________]     │
│  Phone: [____________________________]     │
│  Password: [_________________________]     │
│                                            │
│  Department:                               │
│  [Dropdown: Computer Science/etc.]         │
│                                            │
│  Expertise:                                │
│  [Multi-select: Web Dev/Database/etc.]     │
│                                            │
│  Verification Document (ID/Certificate):   │
│  [Upload File]                             │
│                                            │
│  Note: Your account will be reviewed       │
│  by admin before activation.               │
│                                            │
│  [Submit Request]  [Back]                  │
└────────────────────────────────────────────┘
```

**C. User Profile Page**
```
Profile - Mahasiswa A:
┌────────────────────────────────────────────┐
│  👤 Profile Photo                           │
│  [Upload/Change Photo]                     │
│                                            │
│  Personal Information:                     │
│  Full Name: Ahmad Mahasiswa                │
│  Student ID: 12345678                      │
│  Email: ahmad@student.edu                  │
│  Phone: +62 812-3456-7890                  │
│  Study Program: Informatics                │
│  Batch: 2022                               │
│                                            │
│  Account Status: ✅ Active                  │
│  Member since: Jan 15, 2024                │
│  Last login: 2 hours ago                   │
│                                            │
│  [Edit Profile]                            │
│  [Change Password]                         │
│  [Privacy Settings]                        │
└────────────────────────────────────────────┘
```

**D. Password Reset Flow**
```
Forgot Password:
Step 1: Enter email
┌────────────────────────────────────────────┐
│  Reset Password                            │
│                                            │
│  Enter your email address:                 │
│  [_________________________________]       │
│                                            │
│  [Send Reset Link]  [Back to Login]        │
└────────────────────────────────────────────┘

Step 2: Check email
┌────────────────────────────────────────────┐
│  ✅ Reset Link Sent!                        │
│                                            │
│  We've sent a password reset link to:      │
│  ahmad@student.edu                         │
│                                            │
│  Please check your email and click the     │
│  link to reset your password.              │
│                                            │
│  Didn't receive email?                     │
│  [Resend Link]                             │
└────────────────────────────────────────────┘

Step 3: New password
┌────────────────────────────────────────────┐
│  Create New Password                       │
│                                            │
│  New Password:                             │
│  [_________________________________]       │
│  • At least 8 characters                   │
│  • Include uppercase & lowercase           │
│  • Include numbers                         │
│                                            │
│  Confirm Password:                         │
│  [_________________________________]       │
│                                            │
│  [Reset Password]                          │
└────────────────────────────────────────────┘
```

**E. Account Settings**
```
Settings:
┌────────────────────────────────────────────┐
│  ⚙️ Account Settings                        │
│                                            │
│  Notifications:                            │
│  ☑ Email notifications                     │
│  ☑ New material available                  │
│  ☑ Quiz reminders                          │
│  ☑ Forum replies                           │
│  ☐ Weekly progress report                  │
│                                            │
│  Privacy:                                  │
│  ☑ Show my progress on leaderboard         │
│  ☐ Allow other students to message me     │
│                                            │
│  Preferences:                              │
│  Language: [English ▼]                     │
│  Timezone: [GMT+7 ▼]                       │
│  Theme: ○ Light  ● Dark  ○ Auto            │
│                                            │
│  [Save Settings]                           │
└────────────────────────────────────────────┘
```

#### **Technical Implementation Notes**
```php
// User table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    role ENUM('admin','dosen','mahasiswa') NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(255) NOT NULL,
    student_id VARCHAR(50) NULL, -- NIM for students
    lecturer_id VARCHAR(50) NULL, -- NIP for lecturers
    phone VARCHAR(20),
    photo_url VARCHAR(500),
    department VARCHAR(100),
    study_program VARCHAR(100),
    batch_year INT,
    status ENUM('active','pending','suspended') DEFAULT 'pending',
    email_verified BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL
);

// Authentication
function authenticateUser($email, $password) {
    $user = getUserByEmail($email);
    
    if (!$user) {
        return ['success' => false, 'message' => 'Invalid credentials'];
    }
    
    if ($user['status'] !== 'active') {
        return ['success' => false, 'message' => 'Account not activated'];
    }
    
    if (!password_verify($password, $user['password_hash'])) {
        return ['success' => false, 'message' => 'Invalid credentials'];
    }
    
    // Update last login
    updateLastLogin($user['id']);
    
    // Create session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['role'] = $user['role'];
    
    return ['success' => true, 'redirect' => getDashboardUrl($user['role'])];
}

// Password reset token
CREATE TABLE password_reset_tokens (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    token VARCHAR(64) UNIQUE,
    expires_at TIMESTAMP,
    used BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
```

---

## 5. Technical Architecture

### 5.1 System Architecture Overview

```
┌─────────────────────────────────────────────────────────────┐
│                        CLIENT LAYER                         │
│  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐   │
│  │ Desktop  │  │  Tablet  │  │  Mobile  │  │  Laptop  │   │
│  │ Browser  │  │ Browser  │  │ Browser  │  │ Browser  │   │
│  └────┬─────┘  └────┬─────┘  └────┬─────┘  └────┬─────┘   │
│       └──────────────┴──────────────┴──────────────┘        │
└───────────────────────────┬─────────────────────────────────┘
                            │ HTTPS
┌───────────────────────────┴─────────────────────────────────┐
│                    DOCKER CONTAINER 1                       │
│                      WEB SERVER                             │
│  ┌────────────────────────────────────────────────────────┐ │
│  │             Apache/Nginx Web Server                    │ │
│  │  ┌──────────────────────────────────────────────────┐  │ │
│  │  │  Frontend (HTML/CSS/JavaScript)                  │  │ │
│  │  │  - Landing Page                                  │  │ │
│  │  │  - Dashboard                                     │  │ │
│  │  │  - Course Pages                                  │  │ │
│  │  │  - Quiz Interface                                │  │ │
│  │  └──────────────────────────────────────────────────┘  │ │
│  │  ┌──────────────────────────────────────────────────┐  │ │
│  │  │  Backend (PHP)                                   │  │ │
│  │  │  - Authentication                                │  │ │
│  │  │  - API Endpoints                                 │  │ │
│  │  │  - Business Logic                                │  │ │
│  │  │  - File Upload Handler                           │  │ │
│  │  └──────────────────────────────────────────────────┘  │ │
│  └────────────────────────────────────────────────────────┘ │
└───────────────────────────┬─────────────────────────────────┘
                            │ TCP/IP via Docker Network
┌───────────────────────────┴─────────────────────────────────┐
│                    DOCKER CONTAINER 2                       │
│                    DATABASE SERVER                          │
│  ┌────────────────────────────────────────────────────────┐ │
│  │                   MySQL 8.0                            │ │
│  │  - User Management                                     │ │
│  │  - Course Content                                      │ │
│  │  - Assessment Data                                     │ │
│  │  - Progress Tracking                                   │ │
│  │  - Forum Data                                          │ │
│  └────────────────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────────────────┘

                            │
                            ↓
┌─────────────────────────────────────────────────────────────┐
│                  EXTERNAL SERVICES                          │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐      │
│  │  OpenAI API  │  │ W3Schools    │  │  SMTP Email  │      │
│  │  (Chatbot)   │  │ (Reference)  │  │  Service     │      │
│  └──────────────┘  └──────────────┘  └──────────────┘      │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│                    BIND DNS SERVER                          │
│                  (For CSN Project Requirement)              │
│  - Domain resolution                                        │
│  - Local DNS configuration                                  │
└─────────────────────────────────────────────────────────────┘
```

### 5.2 Technology Stack

**Frontend:**
- HTML5 (Semantic markup)
- CSS3 (Flexbox, Grid, Animations)
- JavaScript (ES6+, Vanilla JS)
- Optional: Bootstrap 5 or Tailwind CSS for rapid UI development

**Backend:**
- PHP 8.x (Native or optional framework like Laravel/CodeIgniter)
- Session management
- File handling
- API integration

**Database:**
- MySQL 8.0
- InnoDB engine (for transactions)
- Full-text search support

**Containerization:**
- Docker
- Docker Compose
- Alpine Linux base images (lightweight)

**Web Server:**
- Apache 2.4 or Nginx 1.24

**Additional Tools:**
- PHPMyAdmin (Database management)
- Git (Version control)
- Composer (PHP dependency management)

### 5.3 File Structure

```
eduflip/
├── docker-compose.yml
├── .env.example
├── .gitignore
├── README.md
│
├── web/                         # Docker volume mount point
│   ├── public/                  # Document root
│   │   ├── index.php            # Landing page
│   │   ├── login.php
│   │   ├── register.php
│   │   ├── dashboard.php
│   │   │
│   │   ├── assets/
│   │   │   ├── css/
│   │   │   │   ├── main.css
│   │   │   │   ├── dashboard.css
│   │   │   │   └── responsive.css
│   │   │   ├── js/
│   │   │   │   ├── main.js
│   │   │   │   ├── quiz.js
│   │   │   │   ├── chatbot.js
│   │   │   │   └── offline-sync.js
│   │   │   ├── images/
│   │   │   └── uploads/         # User uploaded files
│   │   │
│   │   ├── admin/
│   │   │   ├── index.php
│   │   │   ├── users.php
│   │   │   ├── courses.php
│   │   │   └── analytics.php
│   │   │
│   │   ├── dosen/
│   │   │   ├── index.php
│   │   │   ├── materials.php
│   │   │   ├── quizzes.php
│   │   │   └── students.php
│   │   │
│   │   ├── student/
│   │   │   ├── index.php
│   │   │   ├── courses.php
│   │   │   ├── quiz.php
│   │   │   └── forum.php
│   │   │
│   │   └── api/
│   │       ├── auth.php
│   │       ├── materials.php
│   │       ├── quizzes.php
│   │       ├── forum.php
│   │       ├── chatbot.php
│   │       └── progress.php
│   │
│   ├── includes/
│   │   ├── config.php           # Database connection
│   │   ├── functions.php        # Helper functions
│   │   ├── auth.php             # Authentication functions
│   │   └── header.php / footer.php
│   │
│   └── vendor/                  # Composer dependencies
│
├── database/
│   ├── init.sql                 # Initial schema
│   ├── migrations/              # Database migrations
│   └── seeds/                   # Sample data
│
├── docker/
│   ├── web/
│   │   └── Dockerfile
│   ├── mysql/
│   │   ├── Dockerfile
│   │   └── my.cnf
│   └── bind/
│       └── named.conf           # DNS configuration
│
└── docs/
    ├── API.md
    ├── DEPLOYMENT.md
    └── USER_GUIDE.md
```

---

## 6. Database Schema

### 6.1 Complete ERD

```
┌─────────────────┐
│     USERS       │
├─────────────────┤
│ id (PK)         │
│ role            │
│ email (UNIQUE)  │
│ password_hash   │
│ full_name       │
│ student_id      │
│ lecturer_id     │
│ status          │
└────────┬────────┘
         │
         │ 1:N
         ↓
┌─────────────────┐      ┌─────────────────┐
│    COURSES      │      │   MATERIALS     │
├─────────────────┤      ├─────────────────┤
│ id (PK)         │──┐   │ id (PK)         │
│ course_code     │  │   │ course_id (FK)  │
│ course_name     │  └──→│ title           │
│ description     │      │ file_path       │
│ created_by (FK) │      │ prerequisite_id │
└─────────────────┘      │ order_sequence  │
                         └────────┬────────┘
                                  │
                                  │ 1:N
                                  ↓
                         ┌─────────────────┐
                         │     QUIZZES     │
                         ├─────────────────┤
                         │ id (PK)         │
                         │ material_id (FK)│
                         │ duration        │
                         │ passing_score   │
                         └────────┬────────┘
                                  │
                                  │ 1:N
                                  ↓
                         ┌─────────────────┐
                         │   QUESTIONS     │
                         ├─────────────────┤
                         │ id (PK)         │
                         │ quiz_id (FK)    │
                         │ question_text   │
                         │ question_type   │
                         └────────┬────────┘
                                  │
                                  │ 1:N
                                  ↓
                         ┌─────────────────┐
                         │QUESTION_OPTIONS │
                         ├─────────────────┤
                         │ id (PK)         │
                         │ question_id(FK) │
                         │ option_text     │
                         │ is_correct      │
                         └─────────────────┘
```

### 6.2 Complete SQL Schema

```sql
-- Database creation
CREATE DATABASE IF NOT EXISTS eduflip 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE eduflip;

-- Users table
CREATE TABLE users (
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
CREATE TABLE courses (
    id INT PRIMARY KEY AUTO_INCREMENT,
    course_code VARCHAR(20) UNIQUE NOT NULL,
    course_name VARCHAR(255) NOT NULL,
    description TEXT,
    created_by INT NOT NULL,
    status ENUM('draft','published','archived') DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_status (status)
) ENGINE=InnoDB;

-- Course enrollments
CREATE TABLE course_enrollments (
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
CREATE TABLE materials (
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

-- Material tags
CREATE TABLE material_tags (
    id INT PRIMARY KEY AUTO_INCREMENT,
    material_id INT NOT NULL,
    tag_name VARCHAR(50) NOT NULL,
    FOREIGN KEY (material_id) REFERENCES materials(id) ON DELETE CASCADE,
    INDEX idx_tag (tag_name)
) ENGINE=InnoDB;

-- Student material progress
CREATE TABLE student_material_progress (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    material_id INT NOT NULL,
    status ENUM('not_started','in_progress','completed') DEFAULT 'not_started',
    progress_percentage INT DEFAULT 0,
    time_spent INT DEFAULT 0, -- seconds
    last_accessed TIMESTAMP NULL,
    completed_at TIMESTAMP NULL,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (material_id) REFERENCES materials(id) ON DELETE CASCADE,
    UNIQUE KEY unique_progress (student_id, material_id),
    INDEX idx_status (status)
) ENGINE=InnoDB;

-- Quizzes table
CREATE TABLE quizzes (
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
CREATE TABLE questions (
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
CREATE TABLE question_options (
    id INT PRIMARY KEY AUTO_INCREMENT,
    question_id INT NOT NULL,
    option_text TEXT NOT NULL,
    is_correct BOOLEAN DEFAULT FALSE,
    order_num INT DEFAULT 0,
    FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE,
    INDEX idx_question (question_id)
) ENGINE=InnoDB;

-- Quiz attempts
CREATE TABLE quiz_attempts (
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
CREATE TABLE student_answers (
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

-- Quiz remedial requests
CREATE TABLE quiz_remedial_requests (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    quiz_id INT NOT NULL,
    attempts_used INT NOT NULL,
    best_score DECIMAL(5,2) NOT NULL,
    reason TEXT,
    status ENUM('pending','approved','rejected') DEFAULT 'pending',
    requested_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    reviewed_by INT NULL,
    reviewed_at TIMESTAMP NULL,
    additional_attempts_granted INT DEFAULT 0,
    notes TEXT,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE,
    FOREIGN KEY (reviewed_by) REFERENCES users(id),
    INDEX idx_status (status)
) ENGINE=InnoDB;

-- Forum threads
CREATE TABLE forum_threads (
    id INT PRIMARY KEY AUTO_INCREMENT,
    course_id INT NOT NULL,
    material_id INT NULL,
    author_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    category ENUM('question','discussion','announcement') DEFAULT 'discussion',
    is_pinned BOOLEAN DEFAULT FALSE,
    is_locked BOOLEAN DEFAULT FALSE,
    view_count INT DEFAULT 0,
    reply_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    FOREIGN KEY (material_id) REFERENCES materials(id) ON DELETE SET NULL,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_course (course_id),
    INDEX idx_pinned (is_pinned),
    INDEX idx_created (created_at)
) ENGINE=InnoDB;

-- Forum replies
CREATE TABLE forum_replies (
    id INT PRIMARY KEY AUTO_INCREMENT,
    thread_id INT NOT NULL,
    parent_reply_id INT NULL,
    author_id INT NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (thread_id) REFERENCES forum_threads(id) ON DELETE CASCADE,
    FOREIGN KEY (parent_reply_id) REFERENCES forum_replies(id) ON DELETE CASCADE,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_thread (thread_id),
    INDEX idx_created (created_at)
) ENGINE=InnoDB;

-- Forum tags
CREATE TABLE forum_tags (
    id INT PRIMARY KEY AUTO_INCREMENT,
    thread_id INT NOT NULL,
    tag_name VARCHAR(50) NOT NULL,
    FOREIGN KEY (thread_id) REFERENCES forum_threads(id) ON DELETE CASCADE,
    INDEX idx_tag (tag_name)
) ENGINE=InnoDB;

-- Forum mentions
CREATE TABLE forum_mentions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    thread_id INT NULL,
    reply_id INT NULL,
    mentioned_user_id INT NOT NULL,
    mentioned_by_user_id INT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (thread_id) REFERENCES forum_threads(id) ON DELETE CASCADE,
    FOREIGN KEY (reply_id) REFERENCES forum_replies(id) ON DELETE CASCADE,
    FOREIGN KEY (mentioned_user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (mentioned_by_user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_mentioned_user (mentioned_user_id),
    INDEX idx_read (is_read)
) ENGINE=InnoDB;

-- Chatbot conversations
CREATE TABLE chatbot_conversations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    title VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user (user_id)
) ENGINE=InnoDB;

-- Chatbot messages
CREATE TABLE chatbot_messages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    conversation_id INT NOT NULL,
    role ENUM('user','assistant','system') NOT NULL,
    content TEXT NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (conversation_id) REFERENCES chatbot_conversations(id) ON DELETE CASCADE,
    INDEX idx_conversation (conversation_id)
) ENGINE=InnoDB;

-- Material downloads
CREATE TABLE material_downloads (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    material_id INT NOT NULL,
    downloaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    file_size INT,
    device_info VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (material_id) REFERENCES materials(id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_material (material_id)
) ENGINE=InnoDB;

-- Offline progress sync
CREATE TABLE offline_progress_sync (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    material_id INT NOT NULL,
    progress_percentage INT NOT NULL,
    recorded_at TIMESTAMP NOT NULL,
    synced_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (material_id) REFERENCES materials(id) ON DELETE CASCADE,
    INDEX idx_user (user_id)
) ENGINE=InnoDB;

-- External resource clicks
CREATE TABLE external_resource_clicks (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    material_id INT NULL,
    resource_url VARCHAR(500) NOT NULL,
    clicked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (material_id) REFERENCES materials(id) ON DELETE SET NULL,
    INDEX idx_user (user_id)
) ENGINE=InnoDB;

-- Search index (for full-text search)
CREATE TABLE search_index (
    id INT PRIMARY KEY AUTO_INCREMENT,
    content_type ENUM('material','quiz','forum','faq') NOT NULL,
    content_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    tags TEXT,
    course_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FULLTEXT KEY idx_search (title, content, tags),
    INDEX idx_content_type (content_type),
    INDEX idx_course (course_id)
) ENGINE=InnoDB;

-- Password reset tokens
CREATE TABLE password_reset_tokens (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    token VARCHAR(64) UNIQUE NOT NULL,
    expires_at TIMESTAMP NOT NULL,
    used BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_token (token),
    INDEX idx_expires (expires_at)
) ENGINE=InnoDB;

-- Email verification tokens
CREATE TABLE email_verification_tokens (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    token VARCHAR(64) UNIQUE NOT NULL,
    expires_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_token (token)
) ENGINE=InnoDB;

-- Notifications
CREATE TABLE notifications (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    type ENUM('info','success','warning','error') DEFAULT 'info',
    is_read BOOLEAN DEFAULT FALSE,
    related_type ENUM('material','quiz','forum','general') NULL,
    related_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_read (user_id, is_read),
    INDEX idx_created (created_at)
) ENGINE=InnoDB;

-- Activity logs
CREATE TABLE activity_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    action VARCHAR(100) NOT NULL,
    entity_type VARCHAR(50) NULL,
    entity_id INT NULL,
    description TEXT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_action (action),
    INDEX idx_created (created_at)
) ENGINE=InnoDB;

-- System settings
CREATE TABLE system_settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    setting_type ENUM('string','number','boolean','json') DEFAULT 'string',
    description TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_key (setting_key)
) ENGINE=InnoDB;
```

---

## 7. UI/UX Design Guidelines

### 7.1 Design Instructions for AI Agent

**CRITICAL INSTRUCTION FOR AI AGENT ANTIGRAVITY:**

When you receive the landing page screenshot, please analyze and extract the following design elements, then apply them CONSISTENTLY across all pages:

#### 7.1.1 Visual Elements to Extract
```
1. COLOR PALETTE:
   - Primary color (main brand color)
   - Secondary color (accent color)
   - Background colors (light/dark modes)
   - Text colors (headings, body, muted)
   - Success/warning/error/info colors
   - Button colors (primary, secondary, disabled states)
   - Link colors (default, hover, visited)

2. TYPOGRAPHY:
   - Font families (heading, body, code)
   - Font sizes (h1, h2, h3, h4, h5, h6, p, small)
   - Font weights (light, regular, medium, bold)
   - Line heights
   - Letter spacing

3. SPACING & LAYOUT:
   - Container max-width
   - Grid system (columns, gaps)
   - Padding values (small, medium, large)
   - Margin values
   - Border radius values
   - Section spacing

4. COMPONENTS:
   - Button styles (solid, outline, ghost)
   - Input field styles
   - Card/container styles
   - Navigation menu style
   - Modal/dialog styles
   - Alert/notification styles

5. INTERACTIVE STATES:
   - Hover effects
   - Focus states
   - Active states
   - Disabled states
   - Loading states
```

#### 7.1.2 Pages to Create with Consistent Design

**Apply the extracted design to ALL these pages:**

1. ✅ Landing Page (from screenshot)
2. ✅ Login Page
3. ✅ Registration Page (Student & Dosen)
4. ✅ Admin Dashboard
5. ✅ Dosen Dashboard
6. ✅ Student Dashboard
7. ✅ Course List Page
8. ✅ Course Detail Page
9. ✅ Material Viewer Page
10. ✅ Quiz Taking Interface
11. ✅ Quiz Results Page
12. ✅ Forum Page (Thread List)
13. ✅ Forum Thread View
14. ✅ User Profile Page
15. ✅ Settings Page
16. ✅ Search Results Page

#### 7.1.3 Responsive Design Requirements

```css
/* Mobile First Approach */
/* Base styles: Mobile (320px - 767px) */
.container {
    padding: 1rem;
}

/* Tablet (768px - 1023px) */
@media (min-width: 768px) {
    .container {
        padding: 2rem;
    }
}

/* Desktop (1024px+) */
@media (min-width: 1024px) {
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 3rem;
    }
}
```

#### 7.1.4 Accessibility Requirements

```
- ✅ WCAG 2.1 Level AA compliance
- ✅ Color contrast ratio ≥ 4.5:1 for normal text
- ✅ Color contrast ratio ≥ 3:1 for large text
- ✅ Keyboard navigation support
- ✅ Screen reader friendly (ARIA labels)
- ✅ Focus indicators visible
- ✅ Alt text for images
- ✅ Semantic HTML5 elements
```

### 7.2 Component Library (To be styled based on landing page)

#### Button Component
```html
<!-- Primary Button -->
<button class="btn btn-primary">
    Primary Action
</button>

<!-- Secondary Button -->
<button class="btn btn-secondary">
    Secondary Action
</button>

<!-- Outline Button -->
<button class="btn btn-outline">
    Outline Button
</button>

<!-- Disabled Button -->
<button class="btn btn-primary" disabled>
    Disabled
</button>

<!-- Loading Button -->
<button class="btn btn-primary btn-loading">
    <span class="spinner"></span> Loading...
</button>
```

#### Card Component
```html
<div class="card">
    <div class="card-header">
        <h3>Card Title</h3>
    </div>
    <div class="card-body">
        <p>Card content goes here.</p>
    </div>
    <div class="card-footer">
        <button class="btn btn-primary">Action</button>
    </div>
</div>
```

#### Input Field Component
```html
<div class="form-group">
    <label for="email">Email Address</label>
    <input 
        type="email" 
        id="email" 
        class="form-control" 
        placeholder="you@example.com"
    >
    <small class="form-text">We'll never share your email.</small>
</div>

<!-- Input with Error -->
<div class="form-group has-error">
    <label for="password">Password</label>
    <input 
        type="password" 
        id="password" 
        class="form-control is-invalid"
    >
    <span class="error-message">Password is required</span>
</div>
```

#### Alert Component
```html
<div class="alert alert-success">
    ✅ Success! Your changes have been saved.
</div>

<div class="alert alert-error">
    ❌ Error! Something went wrong.
</div>

<div class="alert alert-warning">
    ⚠️ Warning! Please review your input.
</div>

<div class="alert alert-info">
    ℹ️ Info: New material available.
</div>
```

### 7.3 Animation & Transitions

```css
/* Smooth transitions */
* {
    transition: all 0.3s ease;
}

/* Page transitions */
.page-enter {
    opacity: 0;
    transform: translateY(20px);
}

.page-enter-active {
    opacity: 1;
    transform: translateY(0);
}

/* Loading spinner */
@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.spinner {
    animation: spin 1s linear infinite;
}

/* Fade in */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.fade-in {
    animation: fadeIn 0.5s ease-in;
}
```

---

## 8. API Integration

### 8.1 AI Chatbot API Integration

#### 8.1.1 OpenAI Configuration

```php
// config/chatbot.php
<?php
return [
    'provider' => 'openai', // or 'anthropic'
    'api_key' => getenv('OPENAI_API_KEY'),
    'model' => 'gpt-4-turbo-preview',
    'max_tokens' => 1000,
    'temperature' => 0.7,
    'system_prompt' => 'You are EduFlip Assistant, a helpful learning companion for university students studying web development with PHP and MySQL. Provide clear, concise explanations with practical examples. Keep responses under 500 words.',
];
?>
```

#### 8.1.2 API Wrapper Class

```php
// includes/ChatbotAPI.php
<?php
class ChatbotAPI {
    private $apiKey;
    private $model;
    private $systemPrompt;
    
    public function __construct() {
        $config = include 'config/chatbot.php';
        $this->apiKey = $config['api_key'];
        $this->model = $config['model'];
        $this->systemPrompt = $config['system_prompt'];
    }
    
    public function sendMessage($userMessage, $conversationHistory = []) {
        $messages = [
            ['role' => 'system', 'content' => $this->systemPrompt]
        ];
        
        // Add conversation history
        foreach ($conversationHistory as $msg) {
            $messages[] = $msg;
        }
        
        // Add new user message
        $messages[] = ['role' => 'user', 'content' => $userMessage];
        
        $ch = curl_init('https://api.openai.com/v1/chat/completions');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->apiKey
            ],
            CURLOPT_POSTFIELDS => json_encode([
                'model' => $this->model,
                'messages' => $messages,
                'temperature' => 0.7,
                'max_tokens' => 1000
            ])
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode !== 200) {
            return [
                'success' => false,
                'error' => 'API request failed'
            ];
        }
        
        $data = json_decode($response, true);
        
        return [
            'success' => true,
            'message' => $data['choices'][0]['message']['content']
        ];
    }
}
?>
```

#### 8.1.3 Chatbot API Endpoint

```php
// api/chatbot.php
<?php
require_once '../includes/config.php';
require_once '../includes/ChatbotAPI.php';
require_once '../includes/auth.php';

header('Content-Type: application/json');

// Check authentication
if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$userId = $_SESSION['user_id'];
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['message'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Message is required']);
    exit;
}

$userMessage = $data['message'];
$conversationId = $data['conversation_id'] ?? null;

// Get or create conversation
if (!$conversationId) {
    $stmt = $pdo->prepare("
        INSERT INTO chatbot_conversations (user_id) 
        VALUES (?)
    ");
    $stmt->execute([$userId]);
    $conversationId = $pdo->lastInsertId();
} else {
    // Verify ownership
    $stmt = $pdo->prepare("
        SELECT user_id FROM chatbot_conversations 
        WHERE id = ?
    ");
    $stmt->execute([$conversationId]);
    $conv = $stmt->fetch();
    
    if (!$conv || $conv['user_id'] != $userId) {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
}

// Save user message
$stmt = $pdo->prepare("
    INSERT INTO chatbot_messages (conversation_id, role, content)
    VALUES (?, 'user', ?)
");
$stmt->execute([$conversationId, $userMessage]);

// Get conversation history (last 10 messages)
$stmt = $pdo->prepare("
    SELECT role, content 
    FROM chatbot_messages 
    WHERE conversation_id = ?
    ORDER BY timestamp DESC 
    LIMIT 10
");
$stmt->execute([$conversationId]);
$history = array_reverse($stmt->fetchAll(PDO::FETCH_ASSOC));

// Call AI API
$chatbot = new ChatbotAPI();
$result = $chatbot->sendMessage($userMessage, $history);

if (!$result['success']) {
    http_response_code(500);
    echo json_encode(['error' => 'AI service unavailable']);
    exit;
}

// Save assistant response
$stmt = $pdo->prepare("
    INSERT INTO chatbot_messages (conversation_id, role, content)
    VALUES (?, 'assistant', ?)
");
$stmt->execute([$conversationId, $result['message']]);

echo json_encode([
    'conversation_id' => $conversationId,
    'message' => $result['message']
]);
?>
```

### 8.2 Other API Endpoints

#### 8.2.1 Authentication API

```php
// api/auth.php
<?php
require_once '../includes/config.php';

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'login':
        // Handle login
        break;
    case 'register':
        // Handle registration
        break;
    case 'logout':
        // Handle logout
        break;
    default:
        http_response_code(400);
        echo json_encode(['error' => 'Invalid action']);
}
?>
```

#### 8.2.2 Materials API

```php
// api/materials.php
<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';

header('Content-Type: application/json');

requireAuth();

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

switch ($method) {
    case 'GET':
        if ($action === 'list') {
            // Get materials list
        } elseif ($action === 'detail') {
            // Get material detail
        }
        break;
    
    case 'POST':
        if ($action === 'progress') {
            // Update progress
        }
        break;
}
?>
```

---

## 9. Security Requirements

### 9.1 Authentication & Authorization

```php
// includes/auth.php
<?php
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
}

function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

function isLoggedIn() {
    return isset($_SESSION['user_id']) && isset($_SESSION['role']);
}

function requireAuth($allowedRoles = []) {
    if (!isLoggedIn()) {
        header('Location: /login.php');
        exit;
    }
    
    if (!empty($allowedRoles) && !in_array($_SESSION['role'], $allowedRoles)) {
        http_response_code(403);
        die('Access denied');
    }
}

function requireRole($role) {
    requireAuth([$role]);
}
?>
```

### 9.2 Input Validation & Sanitization

```php
// includes/validation.php
<?php
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validatePassword($password) {
    // At least 8 chars, 1 uppercase, 1 lowercase, 1 number
    return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $password);
}

function validateFile($file, $allowedTypes, $maxSize) {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }
    
    if ($file['size'] > $maxSize) {
        return false;
    }
    
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    return in_array($mimeType, $allowedTypes);
}
?>
```

### 9.3 CSRF Protection

```php
// includes/csrf.php
<?php
function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function csrfField() {
    return '<input type="hidden" name="csrf_token" value="' . generateCSRFToken() . '">';
}
?>
```

### 9.4 SQL Injection Prevention

```php
// Always use prepared statements
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);

// NEVER do this:
// $query = "SELECT * FROM users WHERE email = '$email'";
```

### 9.5 File Upload Security

```php
// includes/file-upload.php
<?php
function secureFileUpload($file, $uploadDir) {
    $allowedTypes = [
        'application/pdf',
        'application/vnd.ms-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'video/mp4',
        'video/webm',
        'image/jpeg',
        'image/png'
    ];
    
    $maxSize = 50 * 1024 * 1024; // 50MB
    
    if (!validateFile($file, $allowedTypes, $maxSize)) {
        return ['success' => false, 'error' => 'Invalid file'];
    }
    
    // Generate unique filename
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
    $filepath = $uploadDir . '/' . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return ['success' => true, 'path' => $filepath];
    }
    
    return ['success' => false, 'error' => 'Upload failed'];
}
?>
```

---

## 10. Docker Deployment Guide

### 10.1 Docker Compose Configuration

```yaml
# docker-compose.yml
version: '3.8'

services:
  web:
    build:
      context: ./docker/web
      dockerfile: Dockerfile
    container_name: eduflip-web
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./web:/var/www/html
      - ./docker/web/php.ini:/usr/local/etc/php/php.ini
    networks:
      - eduflip-network
    depends_on:
      - db
    environment:
      - DB_HOST=db
      - DB_NAME=eduflip
      - DB_USER=eduflip_user
      - DB_PASS=secure_password
    restart: unless-stopped

  db:
    build:
      context: ./docker/mysql
      dockerfile: Dockerfile
    container_name: eduflip-db
    ports:
      - "3306:3306"
    volumes:
      - eduflip-db-data:/var/lib/mysql
      - ./database/init.sql:/docker-entrypoint-initdb.d/init.sql
    networks:
      - eduflip-network
    environment:
      - MYSQL_ROOT_PASSWORD=root_password
      - MYSQL_DATABASE=eduflip
      - MYSQL_USER=eduflip_user
      - MYSQL_PASSWORD=secure_password
    restart: unless-stopped

  phpmyadmin:
    image: phpmyadmin:latest
    container_name: eduflip-phpmyadmin
    ports:
      - "8080:80"
    networks:
      - eduflip-network
    environment:
      - PMA_HOST=db
      - PMA_PORT=3306
    depends_on:
      - db
    restart: unless-stopped

  bind:
    build:
      context: ./docker/bind
      dockerfile: Dockerfile
    container_name: eduflip-dns
    ports:
      - "53:53/tcp"
      - "53:53/udp"
    volumes:
      - ./docker/bind/named.conf:/etc/bind/named.conf
      - ./docker/bind/zones:/etc/bind/zones
    networks:
      - eduflip-network
    restart: unless-stopped

networks:
  eduflip-network:
    driver: bridge

volumes:
  eduflip-db-data:
```

### 10.2 Web Server Dockerfile

```dockerfile
# docker/web/Dockerfile
FROM php:8.2-apache

# Install dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_mysql mysqli zip

# Enable Apache modules
RUN a2enmod rewrite ssl headers

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY ../../web /var/www/html

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Expose ports
EXPOSE 80 443

# Start Apache
CMD ["apache2-foreground"]
```

### 10.3 MySQL Dockerfile

```dockerfile
# docker/mysql/Dockerfile
FROM mysql:8.0

# Copy custom MySQL configuration
COPY my.cnf /etc/mysql/conf.d/

# Expose MySQL port
EXPOSE 3306
```

### 10.4 MySQL Custom Configuration

```ini
# docker/mysql/my.cnf
[mysqld]
# Character set
character-set-server=utf8mb4
collation-server=utf8mb4_unicode_ci

# Performance tuning
max_connections=200
innodb_buffer_pool_size=256M
innodb_log_file_size=64M
query_cache_size=0
query_cache_type=0

# Full-text search
ft_min_word_len=2

# Logging
general_log=0
slow_query_log=1
slow_query_log_file=/var/log/mysql/slow.log
long_query_time=2
```

### 10.5 BIND DNS Configuration

```dockerfile
# docker/bind/Dockerfile
FROM ubuntu:22.04

RUN apt-get update && apt-get install -y bind9 bind9utils bind9-doc

COPY named.conf /etc/bind/named.conf
COPY zones/ /etc/bind/zones/

EXPOSE 53/tcp 53/udp

CMD ["named", "-g", "-c", "/etc/bind/named.conf"]
```

```conf
// docker/bind/named.conf
options {
    directory "/var/cache/bind";
    recursion yes;
    allow-query { any; };
    forwarders {
        8.8.8.8;
        8.8.4.4;
    };
    dnssec-validation auto;
    listen-on-v6 { any; };
};

zone "eduflip.local" {
    type master;
    file "/etc/bind/zones/eduflip.local.zone";
};
```

```conf
// docker/bind/zones/eduflip.local.zone
$TTL    604800
@       IN      SOA     eduflip.local. admin.eduflip.local. (
                        2024120601      ; Serial
                        604800          ; Refresh
                        86400           ; Retry
                        2419200         ; Expire
                        604800 )        ; Negative Cache TTL
;
@       IN      NS      ns.eduflip.local.
@       IN      A       172.18.0.2
ns      IN      A       172.18.0.4
www     IN      A       172.18.0.2
db      IN      A       172.18.0.3
```

### 10.6 Environment Configuration

```bash
# .env.example
# Copy this file to .env and update values

# Application
APP_NAME=EduFlip
APP_ENV=production
APP_DEBUG=false
APP_URL=http://localhost

# Database
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=eduflip
DB_USERNAME=eduflip_user
DB_PASSWORD=secure_password

# AI Chatbot
OPENAI_API_KEY=your_openai_api_key_here
CHATBOT_MODEL=gpt-4-turbo-preview

# Email (SMTP)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@eduflip.local
MAIL_FROM_NAME=EduFlip

# Session
SESSION_LIFETIME=120
SESSION_SECURE_COOKIE=false

# File Upload
MAX_UPLOAD_SIZE=52428800
ALLOWED_FILE_TYPES=pdf,pptx,mp4,webm,jpg,png
```

### 10.7 Deployment Commands

```bash
# Initial setup
git clone <repository-url> eduflip
cd eduflip
cp .env.example .env
nano .env  # Edit with your values

# Build and start containers
docker-compose up -d --build

# Check container status
docker-compose ps

# View logs
docker-compose logs -f web
docker-compose logs -f db

# Access containers
docker exec -it eduflip-web bash
docker exec -it eduflip-db mysql -u root -p

# Stop containers
docker-compose stop

# Remove containers
docker-compose down

# Remove containers and volumes (WARNING: deletes data)
docker-compose down -v

# Restart specific service
docker-compose restart web
```

### 10.8 Database Migration

```bash
# Run initial migration
docker exec -it eduflip-db mysql -u eduflip_user -p eduflip < database/init.sql

# Or use docker-compose exec
docker-compose exec db mysql -u eduflip_user -p eduflip < database/init.sql
```

---

## 11. Testing Requirements

### 11.1 Unit Testing

```php
// tests/UserAuthTest.php
<?php
use PHPUnit\Framework\TestCase;

class UserAuthTest extends TestCase {
    public function testPasswordHashing() {
        $password = 'TestPassword123';
        $hash = hashPassword($password);
        
        $this->assertTrue(verifyPassword($password, $hash));
        $this->assertFalse(verifyPassword('WrongPassword', $hash));
    }
    
    public function testEmailValidation() {
        $this->assertTrue(validateEmail('user@example.com'));
        $this->assertFalse(validateEmail('invalid-email'));
    }
    
    public function testPasswordValidation() {
        $this->assertTrue(validatePassword('Valid123'));
        $this->assertFalse(validatePassword('weak'));
    }
}
?>
```

### 11.2 Integration Testing Checklist

**Authentication Flow:**
- [ ] User registration (student)
- [ ] User registration (dosen) with approval
- [ ] Login with valid credentials
- [ ] Login with invalid credentials
- [ ] Password reset flow
- [ ] Email verification

**Content Management:**
- [ ] Admin uploads material
- [ ] Dosen uploads material
- [ ] Material appears in student dashboard
- [ ] Download material
- [ ] Track reading progress

**Quiz System:**
- [ ] Create quiz with multiple question types
- [ ] Student takes quiz
- [ ] Timer functionality
- [ ] Auto-grading
- [ ] Manual grading (essay questions)
- [ ] Remedial request and approval

**Forum:**
- [ ] Create thread
- [ ] Reply to thread
- [ ] Mention user
- [ ] Pin thread (dosen only)
- [ ] Search forum

**Unlocking System:**
- [ ] Material locked until prerequisite complete
- [ ] Auto-unlock after quiz pass
- [ ] Admin manual unlock

**AI Chatbot:**
- [ ] Send message
- [ ] Receive response
- [ ] Conversation history
- [ ] API error handling

### 11.3 Browser Compatibility Testing

Test on:
- [ ] Chrome (latest)
- [ ] Firefox (latest)
- [ ] Safari (latest)
- [ ] Edge (latest)
- [ ] Mobile browsers (Chrome Mobile, Safari Mobile)

### 11.4 Performance Testing

```bash
# Load testing with Apache Bench
ab -n 1000 -c 10 http://localhost/

# Database query performance
EXPLAIN SELECT * FROM materials WHERE course_id = 1;

# Check slow query log
docker exec eduflip-db tail -f /var/log/mysql/slow.log
```

### 11.5 Security Testing Checklist

- [ ] SQL injection attempts blocked
- [ ] XSS attacks prevented
- [ ] CSRF tokens validated
- [ ] File upload restrictions enforced
- [ ] Session hijacking prevented
- [ ] Password requirements enforced
- [ ] Rate limiting works (if implemented)

---

## 12. Project Timeline & Milestones

### 12.1 Development Phases

**Phase 1: Foundation (Week 1-2)**
- [x] PRD completion
- [ ] Docker environment setup
- [ ] Database schema implementation
- [ ] Basic authentication system
- [ ] Landing page

**Phase 2: Core Features (Week 3-5)**
- [ ] Admin dashboard
- [ ] Material management
- [ ] Quiz system (basic)
- [ ] Student dashboard
- [ ] Progress tracking

**Phase 3: Advanced Features (Week 6-7)**
- [ ] Forum system
- [ ] AI chatbot integration
- [ ] Offline mode
- [ ] Search functionality
- [ ] Remedial system

**Phase 4: Polish & Testing (Week 8)**
- [ ] UI/UX refinement
- [ ] Bug fixes
- [ ] Performance optimization
- [ ] Security hardening
- [ ] Documentation

**Phase 5: Deployment & Presentation (Week 9-10)**
- [ ] Final deployment
- [ ] BIND DNS configuration
- [ ] User acceptance testing
- [ ] Presentation preparation
- [ ] Report writing

### 12.2 Deliverables Checklist

**Code & System:**
- [ ] Fully functional web application
- [ ] Docker containers (web + database)
- [ ] BIND DNS server configured
- [ ] All 11 core features implemented
- [ ] Accessible from client browser

**Documentation:**
- [ ] Technical documentation (API, database schema)
- [ ] User manual (student, dosen, admin)
- [ ] Deployment guide
- [ ] Source code comments

**Presentation Materials:**
- [ ] PowerPoint slides
- [ ] Live demo preparation
- [ ] Video demo (backup)
- [ ] Q&A preparation

**Report (PDF):**
- [ ] Executive summary
- [ ] System architecture
- [ ] Implementation details
- [ ] Testing results
- [ ] Challenges & solutions
- [ ] Screenshots
- [ ] Conclusion & future work

---

## 13. Additional Notes & Best Practices

### 13.1 Code Quality Standards

```php
// Use meaningful variable names
$studentId = $_SESSION['user_id'];  // Good
$sid = $_SESSION['user_id'];        // Bad

// Add comments for complex logic
// Calculate quiz score as percentage
$score = ($earnedPoints / $totalPoints) * 100;

// Use consistent formatting
if ($condition) {
    // Code here
} else {
    // Code here
}

// Follow PSR-12 coding standard for PHP
```

### 13.2 Git Workflow

```bash
# Feature branch workflow
git checkout -b feature/quiz-system
git commit -m "feat: implement quiz timer functionality"
git push origin feature/quiz-system

# Commit message format
# feat: new feature
# fix: bug fix
# docs: documentation
# style: formatting
# refactor: code restructuring
# test: adding tests
# chore: maintenance
```

### 13.3 Backup Strategy

```bash
# Database backup
docker exec eduflip-db mysqldump -u root -p eduflip > backup_$(date +%Y%m%d).sql

# Restore database
docker exec -i eduflip-db mysql -u root -p eduflip < backup_20241206.sql

# File backup
tar -czf uploads_backup_$(date +%Y%m%d).tar.gz web/public/assets/uploads/
```

### 13.4 Monitoring & Logging

```php
// includes/logger.php
<?php
function logActivity($userId, $action, $details = '') {
    global $pdo;
    $stmt = $pdo->prepare("
        INSERT INTO activity_logs (user_id, action, description, ip_address, user_agent)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $userId,
        $action,
        $details,
        $_SERVER['REMOTE_ADDR'],
        $_SERVER['HTTP_USER_AGENT']
    ]);
}

// Usage
logActivity($userId, 'quiz_completed', "Quiz ID: $quizId, Score: $score");
?>
```

---

## 14. Troubleshooting Guide

### 14.1 Common Issues

**Problem: Cannot connect to database**
```bash
# Check if database container is running
docker-compose ps

# Check database logs
docker-compose logs db

# Test connection
docker exec -it eduflip-db mysql -u eduflip_user -p

# Verify network
docker network inspect eduflip_eduflip-network
```

**Problem: File upload fails**
```php
// Check PHP settings
php -i | grep upload_max_filesize
php -i | grep post_max_size

// Update php.ini
upload_max_filesize = 50M
post_max_size = 50M
```

**Problem: AI chatbot not responding**
```bash
# Check API key
echo $OPENAI_API_KEY

# Test API connection
curl https://api.openai.com/v1/models \
  -H "Authorization: Bearer $OPENAI_API_KEY"

# Check error logs
tail -f web/error.log
```

---

## 15. Contact & Support

### 15.1 Project Team Roles

- **Project Lead:** Overall coordination
- **Backend Developer:** PHP, Database, API integration
- **Frontend Developer:** HTML, CSS, JavaScript, UI/UX
- **DevOps Engineer:** Docker, deployment, DNS configuration
- **QA Tester:** Testing, bug reporting

### 15.2 Resources

- **Documentation:** `/docs` folder
- **Issue Tracking:** GitHub Issues
- **Code Repository:** [GitHub URL]
- **Demo Site:** http://eduflip.local
- **PHPMyAdmin:** http://localhost:8080

---

## 16. Conclusion

This PRD provides comprehensive specifications for building EduFlip, a flipped classroom learning management system. The system integrates modern technologies including Docker containerization, AI-powered assistance, and progressive learning paths to create an engaging educational experience.

### Key Success Factors:
1. ✅ All 11 core features implemented
2. ✅ Professional UI/UX based on landing page design
3. ✅ Secure authentication and data protection
4. ✅ Proper Docker containerization
5. ✅ BIND DNS configuration
6. ✅ Comprehensive testing
7. ✅ Complete documentation

### Next Steps:
1. Review this PRD with team
2. Upload landing page screenshot to AI Agent
3. Begin Docker environment setup
4. Start Phase 1 development
5. Regular progress meetings

---

**Document Version:** 1.0  
**Last Updated:** December 6, 2024  
**Status:** Ready for Implementation

---

## FINAL INSTRUCTIONS FOR AI AGENT ANTIGRAVITY

Dear AI Agent,

When you receive the landing page screenshot along with this PRD, please:

1. **Analyze the screenshot** and extract all design elements (colors, fonts, spacing, components)
2. **Apply the design consistently** across all pages mentioned in Section 7
3. **Implement all 11 core features** as specified in Section 4
4. **Follow the database schema** exactly as defined in Section 6
5. **Use the file structure** outlined in Section 5.3
6. **Implement security measures** from Section 9
7. **Create Docker configuration** files from Section 10
8. **Write clean, commented code** following best practices from Section 13

**Priority Order:**
1. Authentication system (login, register, roles)
2. Material management (upload, view, download)
3. Quiz system (create, take, grade)
4. Dashboard (student, dosen, admin)
5. Forum system
6. AI chatbot integration
7. Unlocking system
8. Search & navigation
9. Polish & optimize

**Remember:**
- Keep UI/UX consistent with the landing page
- Make it responsive (mobile, tablet, desktop)
- Add loading states and error handling
- Use prepared statements for SQL
- Validate and sanitize all inputs
- Test each feature thoroughly

Good luck! 🚀