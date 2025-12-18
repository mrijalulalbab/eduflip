<?php
require_once '../includes/header.php';
?>

<div class="main-content">
    <!-- Hero Section -->
    <div class="hero" style="min-height: 50vh; align-items: center; padding-bottom: 2rem; position: relative; overflow: hidden;">
        <!-- Background Elements -->
        <div style="position: absolute; top: 10%; left: 5%; width: 200px; height: 200px; background: rgba(56, 189, 248, 0.2); border-radius: 50%; filter: blur(80px); animation: float 6s infinite;"></div>
        <div style="position: absolute; bottom: 10%; right: 5%; width: 300px; height: 300px; background: rgba(167, 139, 250, 0.15); border-radius: 50%; filter: blur(80px); animation: float 8s infinite reverse;"></div>

        <div class="container" style="text-align: center; position: relative; z-index: 1;">
            <p style="color: var(--secondary); font-weight: 600; letter-spacing: 2px; margin-bottom: 1rem;">OUR STORY</p>
            <h1 class="hero-title gradient-text" style="font-size: 3.5rem; margin-bottom: 1.5rem; line-height: 1.2;">
                Empowering the Next<br>Generation of Coders
            </h1>
            <p class="hero-subtitle" style="max-width: 700px; margin: 0 auto; color: var(--text-muted); font-size: 1.1rem;">
                EduFlip exists to bridge the gap between traditional education and industry demands. 
                We believe quality tech education should be accessible, engaging, and practical.
            </p>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="container" style="margin-bottom: 6rem;">
        <div class="glass-panel" style="padding: 3rem; border-radius: 24px; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 2rem; text-align: center;">
            <div>
                <div style="font-size: 2.5rem; font-weight: 800; color: white; margin-bottom: 0.5rem;">5,000+</div>
                <div style="color: var(--text-muted); font-size: 0.9rem;">Active Students</div>
            </div>
            <div>
                <div style="font-size: 2.5rem; font-weight: 800; color: var(--secondary); margin-bottom: 0.5rem;">120+</div>
                <div style="color: var(--text-muted); font-size: 0.9rem;">Expert Courses</div>
            </div>
            <div>
                <div style="font-size: 2.5rem; font-weight: 800; color: #a78bfa; margin-bottom: 0.5rem;">98%</div>
                <div style="color: var(--text-muted); font-size: 0.9rem;">Satisfaction Rate</div>
            </div>
            <div>
                <div style="font-size: 2.5rem; font-weight: 800; color: #f472b6; margin-bottom: 0.5rem;">24/7</div>
                <div style="color: var(--text-muted); font-size: 0.9rem;">Live Support</div>
            </div>
        </div>
    </div>

    <!-- Mission & Vision -->
    <div class="container" style="margin-bottom: 8rem;">
        <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 4rem; align-items: center;">
            <div style="position: relative;">
                <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&q=80&w=800" alt="Team collaborating" style="border-radius: 24px; width: 100%; box-shadow: 0 20px 40px rgba(0,0,0,0.3);">
                <!-- Floating Badge -->
                <div class="glass-panel" style="position: absolute; bottom: -20px; right: -20px; padding: 1.5rem; border-radius: 16px; max-width: 200px;">
                    <div style="font-weight: 700; font-size: 1.1rem; margin-bottom: 0.25rem;">Built for You</div>
                    <p style="font-size: 0.8rem; color: var(--text-muted);">Designed by developers, for developers.</p>
                </div>
            </div>
            
            <div>
                <h2 style="font-size: 2.5rem; font-weight: 700; margin-bottom: 1.5rem;">More Than Just a <span style="color: var(--secondary);">LMS</span></h2>
                <div style="display: flex; flex-direction: column; gap: 2rem;">
                    <div style="display: flex; gap: 1.5rem;">
                        <div style="width: 50px; height: 50px; background: rgba(56, 189, 248, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: var(--primary); font-size: 1.5rem; flex-shrink: 0;">
                            <i class="ri-lightbulb-flash-line"></i>
                        </div>
                        <div>
                            <h4 style="font-size: 1.2rem; margin-bottom: 0.5rem;">Innovation First</h4>
                            <p style="color: var(--text-muted); line-height: 1.6;">We constantly update our curriculum to include the latest technologies like AI, Blockchain, and cloud computing.</p>
                        </div>
                    </div>
                    
                    <div style="display: flex; gap: 1.5rem;">
                        <div style="width: 50px; height: 50px; background: rgba(32, 178, 170, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: var(--secondary); font-size: 1.5rem; flex-shrink: 0;">
                            <i class="ri-team-line"></i>
                        </div>
                        <div>
                            <h4 style="font-size: 1.2rem; margin-bottom: 0.5rem;">Community Driven</h4>
                            <p style="color: var(--text-muted); line-height: 1.6;">Learning is better together. Our platform fosters collaboration through forums, peer reviews, and group projects.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Team Section -->
    <div class="container" style="margin-bottom: 8rem; text-align: center;">
        <h2 style="font-size: 2.5rem; font-weight: 700; margin-bottom: 3rem;">Meet the <span style="color: var(--secondary);">Dream Team</span></h2>
        
        <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem;">
            <!-- Member 1 -->
            <div class="glass-panel hover-card" style="padding: 2rem; border-radius: 20px;">
                <img src="https://images.unsplash.com/photo-1568602471122-7832951cc4c5?auto=format&fit=crop&q=80&w=200&h=200" alt="CEO" style="width: 100px; height: 100px; border-radius: 50%; margin: 0 auto 1.5rem; object-fit: cover; border: 3px solid var(--secondary);">
                <h4 style="font-size: 1.2rem; margin-bottom: 0.25rem;">Alex Johnson</h4>
                <p style="color: var(--secondary); font-size: 0.9rem; margin-bottom: 1rem;">Founder & CEO</p>
                <div style="display: flex; justify-content: center; gap: 1rem; color: var(--text-muted);">
                    <i class="ri-linkedin-fill" style="cursor: pointer; font-size: 1.2rem;"></i>
                    <i class="ri-twitter-x-line" style="cursor: pointer; font-size: 1.2rem;"></i>
                </div>
            </div>

            <!-- Member 2 -->
            <div class="glass-panel hover-card" style="padding: 2rem; border-radius: 20px;">
                <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&q=80&w=200&h=200" alt="CTO" style="width: 100px; height: 100px; border-radius: 50%; margin: 0 auto 1.5rem; object-fit: cover; border: 3px solid #a78bfa;">
                <h4 style="font-size: 1.2rem; margin-bottom: 0.25rem;">Sarah Williams</h4>
                <p style="color: #a78bfa; font-size: 0.9rem; margin-bottom: 1rem;">Head of Engineering</p>
                <div style="display: flex; justify-content: center; gap: 1rem; color: var(--text-muted);">
                    <i class="ri-linkedin-fill" style="cursor: pointer; font-size: 1.2rem;"></i>
                    <i class="ri-github-fill" style="cursor: pointer; font-size: 1.2rem;"></i>
                </div>
            </div>

            <!-- Member 3 -->
            <div class="glass-panel hover-card" style="padding: 2rem; border-radius: 20px;">
                <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&q=80&w=200&h=200" alt="Designer" style="width: 100px; height: 100px; border-radius: 50%; margin: 0 auto 1.5rem; object-fit: cover; border: 3px solid #f472b6;">
                <h4 style="font-size: 1.2rem; margin-bottom: 0.25rem;">Michael Chen</h4>
                <p style="color: #f472b6; font-size: 0.9rem; margin-bottom: 1rem;">Lead Designer</p>
                <div style="display: flex; justify-content: center; gap: 1rem; color: var(--text-muted);">
                    <i class="ri-linkedin-fill" style="cursor: pointer; font-size: 1.2rem;"></i>
                    <i class="ri-dribbble-line" style="cursor: pointer; font-size: 1.2rem;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
