<?php
if (!isset($base_url)) {
    $base_url = '../../';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - EduFlip</title>
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    
    <!-- Styles -->
    <link rel="stylesheet" href="../assets/css/main.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../assets/css/student.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="dashboard-container">
        <?php include 'includes/sidebar.php'; ?>
        
        <div class="dashboard-main">
            <!-- Top Header Component -->
            <header class="dashboard-header">
                <div style="display: flex; align-items: center; gap: 1rem; flex: 1; max-width: 600px;">
                     <!-- Mobile Sidebar Toggle -->
                     <button class="sidebar-toggle" onclick="toggleSidebar()">
                        <i class="ri-menu-2-line"></i>
                    </button>
                    
                    <!-- Smart Search -->
                    <div class="search-container" style="flex: 1; position: relative;">
                        <div class="search-input-wrapper">
                            <i class="ri-search-line"></i>
                            <input type="text" id="global-search" placeholder="Search courses, practice, discussions..." autocomplete="off">
                            <div id="search-spinner" class="search-spinner hidden"></div>
                        </div>
                        
                        <!-- Search Results Dropdown -->
                        <div id="search-results" class="search-results hidden">
                            <!-- Results injected by JS -->
                        </div>
                    </div>
                </div>
                
                <div class="user-snippet">
                    <div class="user-info">
                        <div class="user-name"><?php echo htmlspecialchars($_SESSION['full_name']); ?></div>
                        <div class="user-role">Student</div>
                    </div>
                    <div class="user-avatar">
                        <?php echo strtoupper(substr($_SESSION['full_name'], 0, 1)); ?>
                    </div>
                </div>
            </header>

            <style>
                .search-input-wrapper {
                    position: relative;
                    width: 100%;
                }
                
                .search-input-wrapper i {
                    position: absolute;
                    left: 12px;
                    top: 50%;
                    transform: translateY(-50%);
                    color: #94a3b8;
                    font-size: 1.1rem;
                }
                
                #global-search {
                    width: 100%;
                    background: rgba(15, 23, 42, 0.5); /* Slate 900 semi-transparent */
                    border: 1px solid rgba(255, 255, 255, 0.1);
                    border-radius: 8px;
                    padding: 10px 12px 10px 40px;
                    color: white;
                    font-size: 0.95rem;
                    transition: all 0.2s;
                }
                
                #global-search:focus {
                    outline: none;
                    background: rgba(15, 23, 42, 0.8);
                    border-color: var(--color-primary, #3b82f6);
                    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
                }
                
                .search-results {
                    position: absolute;
                    top: 110%;
                    left: 0;
                    right: 0;
                    background: #1e293b; /* Slate 800 */
                    border: 1px solid rgba(255, 255, 255, 0.1);
                    border-radius: 12px;
                    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
                    max-height: 400px;
                    overflow-y: auto;
                    z-index: 1000;
                    padding: 8px;
                }
                
                .search-results.hidden { display: none; }
                
                .search-result-item {
                    display: flex;
                    align-items: center;
                    gap: 12px;
                    padding: 10px;
                    border-radius: 8px;
                    text-decoration: none;
                    color: white;
                    transition: background 0.2s;
                }
                
                .search-result-item:hover {
                    background: rgba(255, 255, 255, 0.05);
                }
                
                .result-icon {
                    width: 36px;
                    height: 36px;
                    border-radius: 8px;
                    background: rgba(255, 255, 255, 0.05);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    color: #94a3b8;
                    font-size: 1.1rem;
                }
                
                .result-content {
                    flex: 1;
                    min-width: 0;
                }
                
                .result-title {
                    font-size: 0.9rem;
                    font-weight: 500;
                    color: #f1f5f9;
                    margin-bottom: 2px;
                    white-space: nowrap;
                    overflow: hidden;
                    text-overflow: ellipsis;
                }
                
                .result-subtitle {
                    font-size: 0.75rem;
                    color: #94a3b8;
                    display: flex;
                    align-items: center;
                    gap: 6px;
                }
                
                .result-badge {
                    font-size: 0.65rem;
                    padding: 1px 6px;
                    border-radius: 4px;
                    text-transform: uppercase;
                    font-weight: 600;
                    letter-spacing: 0.5px;
                }
                
                .badge-course { background: rgba(59, 130, 246, 0.15); color: #60a5fa; }
                .badge-material { background: rgba(16, 185, 129, 0.15); color: #34d399; }
                .badge-forum { background: rgba(245, 158, 11, 0.15); color: #fbbf24; }
                .badge-practice { background: rgba(168, 85, 247, 0.15); color: #c084fc; }
                
                .no-results {
                    padding: 20px;
                    text-align: center;
                    color: #94a3b8;
                    font-size: 0.9rem;
                }
                
                .search-spinner {
                    position: absolute;
                    right: 12px;
                    top: 50%;
                    transform: translateY(-50%);
                    width: 16px;
                    height: 16px;
                    border: 2px solid rgba(255,255,255,0.1);
                    border-top-color: var(--color-primary, #3b82f6);
                    border-radius: 50%;
                    animation: spin 0.8s linear infinite;
                }
                .search-spinner.hidden { display: none; }
                
                @keyframes spin { to { transform: translateY(-50%) rotate(360deg); } }
            </style>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const searchInput = document.getElementById('global-search');
                    const resultsContainer = document.getElementById('search-results');
                    const spinner = document.getElementById('search-spinner');
                    let debounceTimer;

                    // Close results when clicking outside
                    document.addEventListener('click', (e) => {
                        if (!e.target.closest('.search-container')) {
                            resultsContainer.classList.add('hidden');
                        }
                    });

                    searchInput.addEventListener('input', function(e) {
                        const query = e.target.value.trim();
                        
                        clearTimeout(debounceTimer);
                        
                        if (query.length < 3) {
                            resultsContainer.classList.add('hidden');
                            return;
                        }
                        
                        spinner.classList.remove('hidden');
                        
                        debounceTimer = setTimeout(() => {
                            fetchResults(query);
                        }, 300);
                    });
                    
                    async function fetchResults(query) {
                        try {
                            const response = await fetch(`../student/api/search.php?q=${encodeURIComponent(query)}`);
                            const data = await response.json();
                            
                            spinner.classList.add('hidden');
                            
                            if (data.success) {
                                renderResults(data.results);
                            }
                        } catch (error) {
                            console.error('Search error:', error);
                            spinner.classList.add('hidden');
                        }
                    }
                    
                    function renderResults(results) {
                        if (results.length === 0) {
                            resultsContainer.innerHTML = '<div class="no-results">No results found</div>';
                        } else {
                            resultsContainer.innerHTML = results.map(item => `
                                <a href="${item.url}" class="search-result-item">
                                    <div class="result-icon">
                                        <i class="${item.icon}"></i>
                                    </div>
                                    <div class="result-content">
                                        <div class="result-title">${highlightMatch(item.title, searchInput.value)}</div>
                                        <div class="result-subtitle">
                                            <span class="result-badge badge-${item.type}">${item.type}</span>
                                            ${item.subtitle ? ' • ' + item.subtitle : ''}
                                        </div>
                                    </div>
                                    <i class="ri-arrow-right-s-line" style="color: #64748b;"></i>
                                </a>
                            `).join('');
                        }
                        resultsContainer.classList.remove('hidden');
                    }
                    
                    function highlightMatch(text, query) {
                        const regex = new RegExp(`(${query})`, 'gi');
                        return text.replace(regex, '<span style="color: #60a5fa; font-weight: 700;">$1</span>');
                    }
                });
            </script>

            <!-- Sidebar Overlay for Mobile -->
            <div id="sidebar-overlay" class="sidebar-overlay" onclick="toggleSidebar()"></div>

            <script>
            function toggleSidebar() {
                document.querySelector('.dashboard-sidebar').classList.toggle('active');
                document.getElementById('sidebar-overlay').classList.toggle('active');
                document.body.style.overflow = document.body.style.overflow === 'hidden' ? '' : 'hidden'; // Prevent background scrolling
            }
            </script>
