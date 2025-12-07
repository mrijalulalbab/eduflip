<?php include '../includes/header.php'; ?>

<!-- Hero Section -->
<section class="hero">
    <!-- Decorative Orbit -->
    <div style="position: absolute; width: 600px; height: 600px; border: 1px solid rgba(255,255,255,0.03); border-radius: 50%; top: -100px; right: -100px; z-index: -2;"></div>
    <div style="position: absolute; width: 400px; height: 400px; border: 1px solid rgba(255,255,255,0.05); border-radius: 50%; top: 0; right: 0; z-index: -2;"></div>

    <div class="container grid grid-cols-2 items-center gap-4">
        <div class="hero-content">
            <span class="badge animate-pulse-glow">✨ #1 Platform for Flipped Learning</span>
            <h1>Learn Without Limits with <span class="text-gradient">EduFlip</span></h1>
            <p>A modern learning platform for university students and professionals. Access hundreds of quality courses, interactive quizzes, and accredited certifications.</p>
            
            <div class="flex gap-2">
                <a href="register.php" class="btn btn-primary">Start Learning Free <i class="ri-arrow-right-line" style="margin-left: 8px;"></i></a>
                <a href="#" class="btn btn-outline"><i class="ri-play-fill-circle-line"></i> Watch Demo</a>
            </div>
            
            <div class="hero-stats">
                <div class="stat-group">
                    <h3 class="text-secondary counter">50K+</h3>
                    <p class="text-muted">Active Students</p>
                </div>
                <div class="stat-group">
                    <h3 class="text-secondary counter">200+</h3>
                    <p class="text-muted">Courses Available</p>
                </div>
                <div class="stat-group">
                    <h3 class="text-secondary counter">100+</h3>
                    <p class="text-muted">Partner Institutions</p>
                </div>
            </div>
        </div>
        
        <div class="hero-image animate-float" style="position: relative;">
            <!-- Mockup of Dashboard Card -->
            <div class="card" style="position: relative; z-index: 2; transform: perspective(1000px) rotateY(-5deg) rotateX(5deg);">
                <div class="flex justify-between items-center" style="margin-bottom: 2rem;">
                    <div class="flex items-center gap-2">
                        <div style="width: 40px; height: 40px; background: rgba(32,178,170,0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: var(--secondary);"><i class="ri-code-box-line"></i></div>
                        <div>
                            <h4 style="margin: 0;">Algorithms & Data Structures</h4>
                            <small class="text-muted">12 Modules • 45 Quizzes</small>
                        </div>
                    </div>
                    <span class="text-secondary" style="font-weight: 700;">75%</span>
                </div>
                
                <div style="height: 6px; background: rgba(255,255,255,0.1); border-radius: 3px; margin-bottom: 2rem;">
                    <div style="width: 75%; height: 100%; background: var(--secondary); border-radius: 3px; box-shadow: 0 0 10px var(--secondary);"></div>
                </div>
                
                <div class="flex flex-col gap-2">
                    <div style="padding: 1rem; background: rgba(255,255,255,0.03); border-radius: 8px; display: flex; align-items: center; gap: 1rem;">
                        <i class="ri-checkbox-circle-fill text-secondary"></i>
                        <span>Algorithm Introduction</span>
                    </div>
                    <div style="padding: 1rem; background: rgba(255,255,255,0.03); border-radius: 8px; display: flex; align-items: center; gap: 1rem;">
                        <i class="ri-checkbox-circle-fill text-secondary"></i>
                        <span>Data Structures Basics</span>
                    </div>
                    <div style="padding: 1rem; background: rgba(255,255,255,0.03); border-radius: 8px; display: flex; align-items: center; gap: 1rem; opacity: 0.5;">
                        <div style="width: 16px; height: 16px; border: 2px solid #555; border-radius: 50%;"></div>
                        <span>Recursion & Iteration</span>
                    </div>
                </div>
                
                <div style="margin-top: 2rem; background: var(--secondary); color: var(--bg-darker); padding: 1rem; border-radius: 8px; text-align: center; font-weight: 600; cursor: pointer; transition: 0.3s;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                    Continue Learning <i class="ri-arrow-right-line"></i>
                </div>
            </div>
            
            <!-- Float element -->
            <div style="position: absolute; bottom: 20px; left: -20px; background: rgba(255,255,255,0.95); color: black; padding: 1rem 1.5rem; border-radius: 12px; display: flex; align-items: center; gap: 1rem; box-shadow: 0 10px 30px rgba(0,0,0,0.3); z-index: 3; animation: float 6s ease-in-out infinite reverse;">
                <div style="background: #e6fffa; padding: 8px; border-radius: 50%; color: var(--secondary);"><i class="ri-award-fill"></i></div>
                <div>
                    <div style="font-weight: 700; font-size: 0.9rem;">Certificate Earned!</div>
                    <div style="font-size: 0.8rem; color: #666;">Web Development</div>
                </div>
            </div>
            
             <!-- Decorative Blob -->
             <div style="position: absolute; width: 300px; height: 300px; background: var(--secondary); filter: blur(100px); opacity: 0.2; top: 20%; left: 20%; z-index: 1;"></div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="section" style="background: var(--bg-darker); position: relative; overflow: hidden;">
    <!-- Background Glow -->
    <div style="position: absolute; width: 800px; height: 800px; background: radial-gradient(circle, rgba(32,178,170,0.05) 0%, transparent 70%); top: 50%; left: 50%; transform: translate(-50%, -50%); pointer-events: none;"></div>

    <div class="container header-center">
        <div class="section-header reveal-element">
            <small class="text-secondary" style="letter-spacing: 2px; text-transform: uppercase; font-weight: 600;">Key Features</small>
            <h2 class="section-title">Everything You Need to <span class="text-secondary">Succeed</span></h2>
            <p class="text-muted">Complete platform with modern features designed to maximize your learning experience.</p>
        </div>
        
        <div class="grid grid-cols-4 gap-4">
            <div class="card reveal-element">
                <div class="card-icon"><i class="ri-book-read-line"></i></div>
                <h3>Structured Curriculum</h3>
                <p>Systematically arranged learning materials with progressive modules.</p>
            </div>
            <div class="card reveal-element" style="transition-delay: 0.1s;">
                <div class="card-icon"><i class="ri-team-line"></i></div>
                <h3>Collaborative Learning</h3>
                <p>Features for discussion forums and peer-to-peer help.</p>
            </div>
            <div class="card reveal-element" style="transition-delay: 0.2s;">
                <div class="card-icon"><i class="ri-award-line"></i></div>
                <h3>Accredited Certs</h3>
                <p>Get recognized certificates upon course completion.</p>
            </div>
            <div class="card reveal-element" style="transition-delay: 0.3s;">
                <div class="card-icon"><i class="ri-question-answer-line"></i></div>
                <h3>Interactive Quizzes</h3>
                <p>Real-time knowledge checks with instant feedback.</p>
            </div>
            <div class="card reveal-element">
                <div class="card-icon"><i class="ri-bar-chart-line"></i></div>
                <h3>Progress Tracking</h3>
                <p>Comprehensive analytics dashboard to monitor your growth.</p>
            </div>
            <div class="card reveal-element" style="transition-delay: 0.1s;">
                <div class="card-icon"><i class="ri-shield-check-line"></i></div>
                <h3>Lifetime Access</h3>
                <p>Access your materials forever once enrolled.</p>
            </div>
            <div class="card reveal-element" style="transition-delay: 0.2s;">
                <div class="card-icon"><i class="ri-flashlight-line"></i></div>
                <h3>Micro-learning</h3>
                <p>Bite-sized content for better retention and focus.</p>
            </div>
            <div class="card reveal-element" style="transition-delay: 0.3s;">
                <div class="card-icon"><i class="ri-globe-line"></i></div>
                <h3>Multi-Device</h3>
                <p>Learn anywhere, anytime on mobile, tablet, or desktop.</p>
            </div>
        </div>
    </div>
</section>

<!-- Popular Courses -->
<section class="section">
    <div class="container">
        <div class="flex justify-between items-center reveal-element" style="margin-bottom: 3rem;">
            <div>
                <small class="text-secondary" style="letter-spacing: 2px; text-transform: uppercase; font-weight: 600;">Popular Courses</small>
                <h2 class="section-title">Learn In-Demand <span class="text-secondary">Skills</span></h2>
            </div>
            <a href="#" class="btn btn-outline">View All Courses <i class="ri-arrow-right-line"></i></a>
        </div>
        
        <div class="grid grid-cols-4 gap-4">
            <!-- Course 1 -->
            <div class="card course-card reveal-element">
                <div class="course-image">
                    <img src="https://images.unsplash.com/photo-1555066931-4365d14bab8c?auto=format&fit=crop&q=80&w=400" alt="Coding">
                    <span style="position: absolute; top: 10px; left: 10px; background: var(--secondary); color: white; padding: 4px 12px; border-radius: 4px; font-size: 0.8rem; font-weight: 600;">Bestseller</span>
                </div>
                <div class="course-content">
                    <small class="text-secondary">INFORMATICS</small>
                    <h3>Algorithms & Data Structures</h3>
                    <p class="text-muted" style="margin-bottom: 1rem;">by Dr. Budi Santoso</p>
                    <div class="course-meta">
                        <span><i class="ri-time-line"></i> 24h</span>
                        <span><i class="ri-user-line"></i> 3.4k</span>
                        <span class="text-warning"><i class="ri-star-fill"></i> 4.9</span>
                    </div>
                    <div class="course-price">Rp 299.000 <span style="text-decoration: line-through; color: var(--text-muted); font-size: 0.9rem; margin-left: 8px;">Rp 599.000</span></div>
                </div>
            </div>
            
             <!-- Course 2 -->
             <div class="card course-card reveal-element" style="transition-delay: 0.1s;">
                <div class="course-image">
                    <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&q=80&w=400" alt="Data Science">
                    <span style="position: absolute; top: 10px; left: 10px; background: #10b981; color: white; padding: 4px 12px; border-radius: 4px; font-size: 0.8rem; font-weight: 600;">New</span>
                </div>
                <div class="course-content">
                    <small class="text-secondary">DATA SCIENCE</small>
                    <h3>Machine Learning Fundamentals</h3>
                    <p class="text-muted" style="margin-bottom: 1rem;">by Prof. Siti Rahayu</p>
                    <div class="course-meta">
                        <span><i class="ri-time-line"></i> 32h</span>
                        <span><i class="ri-user-line"></i> 2.1k</span>
                        <span class="text-warning"><i class="ri-star-fill"></i> 4.8</span>
                    </div>
                    <div class="course-price">Rp 449.000 <span style="text-decoration: line-through; color: var(--text-muted); font-size: 0.9rem; margin-left: 8px;">Rp 899.000</span></div>
                </div>
            </div>
            
            <!-- Course 3 -->
            <div class="card course-card reveal-element" style="transition-delay: 0.2s;">
                <div class="course-image">
                    <img src="https://images.unsplash.com/photo-1547658719-da2b51169166?auto=format&fit=crop&q=80&w=400" alt="Web Dev">
                    <span style="position: absolute; top: 10px; left: 10px; background: #6366f1; color: white; padding: 4px 12px; border-radius: 4px; font-size: 0.8rem; font-weight: 600;">Popular</span>
                </div>
                <div class="course-content">
                    <small class="text-secondary">PROGRAMMING</small>
                    <h3>Full-Stack Web Development</h3>
                    <p class="text-muted" style="margin-bottom: 1rem;">by Ahmad Wijaya</p>
                    <div class="course-meta">
                        <span><i class="ri-time-line"></i> 48h</span>
                        <span><i class="ri-user-line"></i> 5.2k</span>
                        <span class="text-warning"><i class="ri-star-fill"></i> 4.9</span>
                    </div>
                    <div class="course-price">Rp 399.000 <span style="text-decoration: line-through; color: var(--text-muted); font-size: 0.9rem; margin-left: 8px;">Rp 799.000</span></div>
                </div>
            </div>
            
            <!-- Course 4 -->
            <div class="card course-card reveal-element" style="transition-delay: 0.3s;">
                <div class="course-image">
                    <img src="https://images.unsplash.com/photo-1561070791-2526d30994b5?auto=format&fit=crop&q=80&w=400" alt="Design">
                </div>
                <div class="course-content">
                    <small class="text-secondary">DESIGN</small>
                    <h3>UI/UX Design Masterclass</h3>
                    <p class="text-muted" style="margin-bottom: 1rem;">by Dewi Kartika</p>
                    <div class="course-meta">
                        <span><i class="ri-time-line"></i> 28h</span>
                        <span><i class="ri-user-line"></i> 1.8k</span>
                        <span class="text-warning"><i class="ri-star-fill"></i> 4.7</span>
                    </div>
                    <div class="course-price">Rp 349.000 <span style="text-decoration: line-through; color: var(--text-muted); font-size: 0.9rem; margin-left: 8px;">Rp 699.000</span></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="section" style="background: var(--bg-darker); position: relative;">
    <div class="container">
        <div class="section-header reveal-element">
            <small class="text-secondary" style="letter-spacing: 2px; text-transform: uppercase; font-weight: 600;">Testimonials</small>
            <h2 class="section-title">What Our Students <span class="text-secondary">Say</span></h2>
        </div>

        <div class="grid grid-cols-3 gap-4">
            <!-- Testimonial 1 -->
            <div class="card reveal-element" style="text-align: left;">
                <div class="flex items-center gap-2" style="margin-bottom: 1.5rem;">
                    <div style="width: 50px; height: 50px; border-radius: 50%; background: #2d3748; overflow: hidden;">
                        <img src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&q=80&w=100" alt="Student">
                    </div>
                    <div>
                        <h4 style="margin: 0; font-size: 1rem;">Rudi Hermawan</h4>
                        <small class="text-secondary">Computer Science Student</small>
                    </div>
                </div>
                <p class="text-muted">"EduFlip has completely changed how I study. The flipped classroom model allows me to learn at my own pace, and the quizzes really help reinforce the material."</p>
                <div style="margin-top: 1.5rem; color: var(--warning);">
                    <i class="ri-star-fill"></i>
                    <i class="ri-star-fill"></i>
                    <i class="ri-star-fill"></i>
                    <i class="ri-star-fill"></i>
                    <i class="ri-star-fill"></i>
                </div>
            </div>

            <!-- Testimonial 2 -->
            <div class="card reveal-element" style="text-align: left; transition-delay: 0.1s;">
                 <div class="flex items-center gap-2" style="margin-bottom: 1.5rem;">
                    <div style="width: 50px; height: 50px; border-radius: 50%; background: #2d3748; overflow: hidden;">
                        <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&q=80&w=100" alt="Student">
                    </div>
                    <div>
                        <h4 style="margin: 0; font-size: 1rem;">Siti Aminah</h4>
                        <small class="text-secondary">Information Systems</small>
                    </div>
                </div>
                <p class="text-muted">"The interactive modules are fantastic! I love being able to discuss topics in the forums with other students. It feels like a real community."</p>
                <div style="margin-top: 1.5rem; color: var(--warning);">
                    <i class="ri-star-fill"></i>
                    <i class="ri-star-fill"></i>
                    <i class="ri-star-fill"></i>
                    <i class="ri-star-fill"></i>
                    <i class="ri-star-fill"></i>
                </div>
            </div>

            <!-- Testimonial 3 -->
            <div class="card reveal-element" style="text-align: left; transition-delay: 0.2s;">
                 <div class="flex items-center gap-2" style="margin-bottom: 1.5rem;">
                    <div style="width: 50px; height: 50px; border-radius: 50%; background: #2d3748; overflow: hidden;">
                        <img src="https://images.unsplash.com/photo-1599566150163-29194dcaad36?auto=format&fit=crop&q=80&w=100" alt="Student">
                    </div>
                    <div>
                        <h4 style="margin: 0; font-size: 1rem;">Budi Prakoso</h4>
                        <small class="text-secondary">Software Engineering</small>
                    </div>
                </div>
                <p class="text-muted">"Finally, a platform that understands modern learning. The dashboard is intuitive, and tracking my progress motivates me to keep going."</p>
                <div style="margin-top: 1.5rem; color: var(--warning);">
                    <i class="ri-star-fill"></i>
                    <i class="ri-star-fill"></i>
                    <i class="ri-star-fill"></i>
                    <i class="ri-star-fill"></i>
                    <i class="ri-star-half-fill"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="section">
    <div class="container header-center">
        <div class="section-header reveal-element">
            <span class="badge" style="background: rgba(32,178,170,0.1); color: var(--secondary);">Common Questions</span>
            <h2 class="section-title">Frequently Asked <span class="text-secondary">Questions</span></h2>
        </div>
        
        <div class="grid grid-cols-2 gap-4" style="text-align: left;">
            <div class="card reveal-element" style="border-left: 4px solid var(--secondary);">
                <h4 style="margin-bottom: 0.5rem;">How does the flipped classroom work?</h4>
                <p class="text-muted">You study the materials (videos, slides) at your own pace before class, then use class time for discussions and practice.</p>
            </div>
            <div class="card reveal-element" style="border-left: 4px solid var(--secondary); transition-delay: 0.1s;">
                <h4 style="margin-bottom: 0.5rem;">Is the certificate accredited?</h4>
                <p class="text-muted">Yes, our certificates are recognized by major partner universities and industry leaders.</p>
            </div>
            <div class="card reveal-element" style="border-left: 4px solid var(--secondary); transition-delay: 0.2s;">
                <h4 style="margin-bottom: 0.5rem;">Can I access materials offline?</h4>
                <p class="text-muted">Absolutely! You can download PDF modules and slides to study without an internet connection.</p>
            </div>
            <div class="card reveal-element" style="border-left: 4px solid var(--secondary); transition-delay: 0.3s;">
                <h4 style="margin-bottom: 0.5rem;">Is there student support?</h4>
                <p class="text-muted">We have 24/7 AI support and community forums where you can get help from peers and lecturers.</p>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="section" style="background: var(--bg-darker); position: relative;">
     <div style="position: absolute; width: 600px; height: 600px; background: radial-gradient(circle, rgba(15, 82, 186, 0.05) 0%, transparent 70%); bottom: -200px; left: -200px; pointer-events: none;"></div>
     
    <div class="container grid grid-cols-2 items-center gap-4">
        <div class="reveal-element">
            <small class="text-secondary" style="letter-spacing: 2px; text-transform: uppercase; font-weight: 600;">Contact Us</small>
            <h2 class="section-title" style="margin-bottom: 1rem;">Get in <span class="text-secondary">Touch</span></h2>
            <p class="text-muted" style="margin-bottom: 2rem;">Have questions about our platform or want to partner with us? Send us a message and we'll reply within 24 hours.</p>
            
            <div class="flex flex-col gap-2">
                <div class="flex items-center gap-2">
                    <div style="width: 40px; height: 40px; background: rgba(255,255,255,0.05); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: var(--secondary);"><i class="ri-mail-send-line"></i></div>
                    <div>
                        <div style="font-weight: 600;">Email Us</div>
                        <div class="text-muted">support@eduflip.com</div>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <div style="width: 40px; height: 40px; background: rgba(255,255,255,0.05); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: var(--secondary);"><i class="ri-map-pin-line"></i></div>
                    <div>
                        <div style="font-weight: 600;">Visit Us</div>
                        <div class="text-muted">Jakarta, Indonesia</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card reveal-element" style="background: var(--bg-card); border: 1px solid rgba(255,255,255,0.1);">
            <form>
                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input type="text" class="form-control" placeholder="John Doe">
                </div>
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" class="form-control" placeholder="john@example.com">
                </div>
                <div class="form-group">
                    <label class="form-label">Message</label>
                    <textarea class="form-control" rows="4" placeholder="How can we help?"></textarea>
                </div>
                <button type="button" class="btn btn-primary" style="width: 100%;">Send Message</button>
            </form>
        </div>
    </div>
</section>

<!-- AI Chat Widget -->
<div id="ai-widget" style="position: fixed; bottom: 30px; right: 30px; z-index: 1000; display: flex; flex-direction: column; align-items: flex-end;">
    <!-- Chat Window (Hidden by default) -->
    <div id="chat-window" style="display: none; width: 350px; background: var(--bg-card); border: 1px solid rgba(255,255,255,0.1); border-radius: 1rem; box-shadow: 0 20px 50px rgba(0,0,0,0.5); overflow: hidden; margin-bottom: 1rem; animation: slideUpFade 0.3s ease-out;">
        <div style="background: var(--secondary); padding: 1rem; color: var(--bg-darker); font-weight: 700; display: flex; justify-content: space-between; align-items: center;">
            <div class="flex items-center gap-2"><i class="ri-robot-2-fill"></i> EduFlip Assistant</div>
            <button onclick="toggleChat()" style="background: none; border: none; color: currentColor; cursor: pointer;"><i class="ri-close-line"></i></button>
        </div>
        <div style="padding: 1rem; height: 300px; overflow-y: auto; display: flex; flex-direction: column; gap: 1rem;">
            <div style="align-self: flex-start; background: rgba(255,255,255,0.05); padding: 0.8rem; border-radius: 1rem 1rem 1rem 0; max-width: 80%;">
                Hi! I'm your AI learning assistant. How can I help you today?
            </div>
        </div>
        <div style="padding: 1rem; border-top: 1px solid rgba(255,255,255,0.1); display: flex; gap: 0.5rem;">
            <input type="text" placeholder="Type a message..." style="flex: 1; background: rgba(255,255,255,0.05); border: none; padding: 0.5rem 1rem; border-radius: 2rem; color: white;">
            <button style="background: var(--secondary); border: none; width: 35px; height: 35px; border-radius: 50%; color: var(--bg-darker); display: flex; align-items: center; justify-content: center; cursor: pointer;"><i class="ri-send-plane-fill"></i></button>
        </div>
    </div>

    <!-- Toggle Button -->
    <button onclick="toggleChat()" class="animate-pulse-glow" style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, var(--secondary), #2dd4bf); border: none; color: var(--bg-darker); font-size: 1.5rem; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 20px rgba(32,178,170,0.4); transition: transform 0.3s;">
        <i class="ri-message-3-fill"></i>
    </button>
</div>

<script>
function toggleChat() {
    const chatWindow = document.getElementById('chat-window');
    if (chatWindow.style.display === 'none') {
        chatWindow.style.display = 'block';
    } else {
        chatWindow.style.display = 'none';
    }
}
</script>

<!-- CTA Section -->
<section class="section reveal-element">
    <div class="container">
        <div style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); padding: 5rem; border-radius: 2rem; text-align: center; border: 1px solid rgba(255,255,255,0.1); position: relative; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.5);">
            <!-- CTA Background FX -->
            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: radial-gradient(circle at 50% 50%, rgba(32,178,170,0.1), transparent 60%); z-index: 0;"></div>
            
            <div style="position: relative; z-index: 1;">
                <span class="badge" style="background: rgba(255,255,255,0.1); color: white;">Join 50,000+ Learners</span>
                <h2 style="font-size: 3rem; margin: 1.5rem 0; font-weight: 800;">Ready to Start Your Learning Journey?</h2>
                <p style="color: var(--text-muted); max-width: 600px; margin: 0 auto 2.5rem;">Register now and get free access to 5 popular courses. No credit card required, no commitment.</p>
                
                <div class="flex justify-center gap-2">
                    <a href="register.php" class="btn btn-primary" style="padding: 1rem 2rem; font-size: 1.1rem;">Register for Free <i class="ri-arrow-right-line"></i></a>
                    <a href="#" class="btn btn-outline" style="border-color: rgba(255,255,255,0.2); color: white;">Explore Courses</a>
                </div>
                
                <p style="margin-top: 1.5rem; font-size: 0.9rem; color: var(--text-muted);">Already have an account? <a href="login.php" class="text-secondary">Login here</a></p>
            </div>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
