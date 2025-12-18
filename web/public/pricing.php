<?php
require_once '../includes/header.php';
?>

<div class="main-content">
    <!-- Hero Section -->
    <div class="hero" style="min-height: 40vh; align-items: flex-end; padding-bottom: 4rem;">
        <div class="container" style="text-align: center;">
            <h1 class="hero-title gradient-text" style="font-size: 3.5rem; margin-bottom: 1rem;">
                Invest in Your Future
            </h1>
            <p class="hero-subtitle" style="max-width: 600px; margin: 0 auto; color: var(--text-muted);">
                Choose the plan that fits your learning journey. Transparent pricing, no hidden fees.
            </p>
        </div>
    </div>

    <!-- Pricing Cards -->
    <div class="container" style="padding-bottom: 6rem;">
        <!-- Toggle -->
        <div id="billingToggle" style="display: flex; justify-content: center; align-items: center; gap: 1rem; margin-bottom: 3rem; cursor: pointer;">
            <span id="monthlyLabel" style="color: white; font-weight: 600; transition: all 0.3s ease;">Monthly</span>
            <div id="toggleSwitch" style="width: 50px; height: 26px; background: var(--bg-card); border: 1px solid var(--secondary); border-radius: 999px; position: relative; transition: all 0.3s ease;">
                <div id="toggleCircle" style="width: 18px; height: 18px; background: var(--secondary); border-radius: 50%; position: absolute; left: 3px; top: 3px; transition: all 0.3s cubic-bezier(0.4, 0.0, 0.2, 1);"></div>
            </div>
            <span id="yearlyLabel" style="color: var(--text-muted); font-weight: 500; transition: all 0.3s ease;">
                Yearly <span style="font-size: 0.75rem; color: var(--bg-darker); background: var(--secondary); padding: 2px 8px; border-radius: 99px; margin-left: 0.5rem;">Save 20%</span>
            </span>
        </div>

        <div class="row" style="display: flex; flex-wrap: wrap; justify-content: center; gap: 2rem;">
            
            <!-- Starter Plan -->
            <div class="glass-panel hover-card" style="padding: 2.5rem; border-radius: 24px; flex: 1 1 300px; max-width: 380px;">
                <h3 style="font-size: 1.5rem; margin-bottom: 0.5rem;">Starter</h3>
                <p style="color: var(--text-muted); margin-bottom: 2rem;">Perfect for exploring new topics.</p>
                
                <div style="font-size: 3rem; font-weight: 800; margin-bottom: 2rem; display: flex; align-items: baseline; gap: 0.5rem;">
                    $<span class="price-amount" data-monthly="0" data-yearly="0">0</span> <span class="price-period" style="font-size: 1rem; font-weight: 400; color: var(--text-muted);">/ month</span>
                </div>

                <a href="register.php" class="btn btn-outline" style="width: 100%; justify-content: center; margin-bottom: 2rem;">Get Started Free</a>

                <ul style="display: flex; flex-direction: column; gap: 1rem;">
                    <li style="display: flex; gap: 0.75rem; color: var(--text-muted);">
                        <i class="ri-checkbox-circle-fill" style="color: var(--text-main);"></i> Access to 3 Free Courses
                    </li>
                    <li style="display: flex; gap: 0.75rem; color: var(--text-muted);">
                        <i class="ri-checkbox-circle-fill" style="color: var(--text-main);"></i> Community Support
                    </li>
                    <li style="display: flex; gap: 0.75rem; color: var(--text-muted);">
                        <i class="ri-checkbox-circle-fill" style="color: var(--text-main);"></i> Basic Quizzes
                    </li>
                </ul>
            </div>

            <!-- Pro Plan -->
            <div class="glass-panel hover-card" style="padding: 2.5rem; border-radius: 24px; border: 1px solid var(--secondary); background: linear-gradient(180deg, rgba(32, 178, 170, 0.1) 0%, rgba(15, 23, 42, 0) 100%); position: relative; margin-top: -10px; flex: 1 1 300px; max-width: 380px;">
                <div style="position: absolute; top: -14px; left: 50%; transform: translateX(-50%); background: var(--secondary); color: var(--bg-darker); padding: 4px 12px; border-radius: 99px; font-size: 0.85rem; font-weight: 700; box-shadow: 0 4px 12px rgba(32, 178, 170, 0.3);">MOST POPULAR</div>
                
                <h3 style="font-size: 1.5rem; margin-bottom: 0.5rem;">Pro Learner</h3>
                <p style="color: var(--text-muted); margin-bottom: 2rem;">Everything you need to master skills.</p>
                
                <div style="font-size: 3rem; font-weight: 800; margin-bottom: 2rem; display: flex; align-items: baseline; gap: 0.5rem;">
                    $<span class="price-amount" data-monthly="19" data-yearly="190">19</span> <span class="price-period" style="font-size: 1rem; font-weight: 400; color: var(--text-muted);">/ month</span>
                </div>

                <a href="register.php" class="btn btn-primary" style="width: 100%; justify-content: center; margin-bottom: 2rem;">Start Free Trial</a>

                <ul style="display: flex; flex-direction: column; gap: 1rem;">
                    <li style="display: flex; gap: 0.75rem;">
                        <i class="ri-checkbox-circle-fill" style="color: var(--secondary);"></i> <strong>Unlimited Course Access</strong>
                    </li>
                    <li style="display: flex; gap: 0.75rem;">
                        <i class="ri-checkbox-circle-fill" style="color: var(--secondary);"></i> Verified Certificates
                    </li>
                    <li style="display: flex; gap: 0.75rem;">
                        <i class="ri-checkbox-circle-fill" style="color: var(--secondary);"></i> Offline Downloads
                    </li>
                    <li style="display: flex; gap: 0.75rem;">
                        <i class="ri-checkbox-circle-fill" style="color: var(--secondary);"></i> Priority Support
                    </li>
                </ul>
            </div>

            <!-- Enterprise Plan -->
            <div class="glass-panel hover-card" style="padding: 2.5rem; border-radius: 24px; flex: 1 1 300px; max-width: 380px;">
                <h3 style="font-size: 1.5rem; margin-bottom: 0.5rem;">Enterprise</h3>
                <p style="color: var(--text-muted); margin-bottom: 2rem;">For teams and organizations.</p>
                
                <div style="font-size: 3rem; font-weight: 800; margin-bottom: 2rem; display: flex; align-items: baseline; gap: 0.5rem;">
                    $<span class="price-amount" data-monthly="49" data-yearly="490">49</span> <span class="price-period" style="font-size: 1rem; font-weight: 400; color: var(--text-muted);">/ user</span>
                </div>

                <a href="#" class="btn btn-outline" style="width: 100%; justify-content: center; margin-bottom: 2rem;">Contact Sales</a>

                <ul style="display: flex; flex-direction: column; gap: 1rem;">
                    <li style="display: flex; gap: 0.75rem; color: var(--text-muted);">
                        <i class="ri-checkbox-circle-fill" style="color: var(--text-main);"></i> Everything in Pro
                    </li>
                    <li style="display: flex; gap: 0.75rem; color: var(--text-muted);">
                        <i class="ri-checkbox-circle-fill" style="color: var(--text-main);"></i> Admin Dashboard
                    </li>
                    <li style="display: flex; gap: 0.75rem; color: var(--text-muted);">
                        <i class="ri-checkbox-circle-fill" style="color: var(--text-main);"></i> SSO Integration
                    </li>
                    <li style="display: flex; gap: 0.75rem; color: var(--text-muted);">
                        <i class="ri-checkbox-circle-fill" style="color: var(--text-main);"></i> Custom Learning Paths
                    </li>
                </ul>
            </div>

        </div>

        <!-- FAQ Section -->
        <div style="margin-top: 6rem; max-width: 800px; margin-left: auto; margin-right: auto;">
            <h2 style="text-align: center; margin-bottom: 3rem;">Frequently Asked Questions</h2>
            
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                <div class="glass-panel" style="padding: 1.5rem; border-radius: 12px;">
                    <h4 style="margin-bottom: 0.5rem; color: white;">Can I cancel anytime?</h4>
                    <p style="color: var(--text-muted);">Yes, absolutely. There are no locking contracts, and you can cancel your subscription at any time from your dashboard.</p>
                </div>
                <div class="glass-panel" style="padding: 1.5rem; border-radius: 12px;">
                    <h4 style="margin-bottom: 0.5rem; color: white;">Do you offer student discounts?</h4>
                    <p style="color: var(--text-muted);">We offer a 50% discount for students with a valid .edu email address. Contact support to apply.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggle = document.getElementById('billingToggle');
    const circle = document.getElementById('toggleCircle');
    const monthlyLabel = document.getElementById('monthlyLabel');
    const yearlyLabel = document.getElementById('yearlyLabel');
    const amounts = document.querySelectorAll('.price-amount');
    const periods = document.querySelectorAll('.price-period');
    
    let isYearly = false;

    toggle.addEventListener('click', function() {
        isYearly = !isYearly;
        
        // Animate Switch
        if(isYearly) {
            circle.style.transform = 'translateX(24px)';
            monthlyLabel.style.color = 'var(--text-muted)';
            monthlyLabel.style.fontWeight = '500';
            yearlyLabel.style.color = 'white';
            yearlyLabel.style.fontWeight = '600';
        } else {
            circle.style.transform = 'translateX(0)';
            monthlyLabel.style.color = 'white';
            monthlyLabel.style.fontWeight = '600';
            yearlyLabel.style.color = 'var(--text-muted)';
            yearlyLabel.style.fontWeight = '500';
        }

        // Update Prices
        amounts.forEach(amount => {
            // Simple counting animation
            const end = isYearly ? parseInt(amount.dataset.yearly) : parseInt(amount.dataset.monthly);
            amount.innerText = end;
        });

        // Update Period Text
        periods.forEach(period => {
            period.innerText = isYearly ? '/ year' : '/ month';
        });
    });
});
</script>

<?php require_once '../includes/footer.php'; ?>
