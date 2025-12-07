/**
 * EduFlip Main Script
 * Handles scroll animations, sticky navigation, and interactive effects.
 */

document.addEventListener('DOMContentLoaded', () => {

    // Smooth Scroll for Anchor Links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });

    // Intersection Observer for Scroll Animations
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.1
    };

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
                observer.unobserve(entry.target); // Only animate once
            }
        });
    }, observerOptions);

    // Elements to animate
    // We observe anything that already has the class .reveal-element
    // PLUS specific structural elements we haven't manually tagged yet
    const targets = document.querySelectorAll('.reveal-element, .card, .section-title, .section-header p, .hero-content > *, .hero-image');

    targets.forEach(el => {
        el.classList.add('reveal-element'); // Ensure class exists
        observer.observe(el);
    });

    // Navbar Glass Effect on Scroll
    const navbar = document.querySelector('.navbar');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            navbar.classList.add('navbar-scrolled');
        } else {
            navbar.classList.remove('navbar-scrolled');
        }
    });

    // Mouse Move Parallax Effect for Hero
    const heroImage = document.querySelector('.hero-image');
    if (heroImage) {
        document.addEventListener('mousemove', (e) => {
            const x = (window.innerWidth - e.pageX * 2) / 100;
            const y = (window.innerHeight - e.pageY * 2) / 100;

            heroImage.style.transform = `translateX(${x}px) translateY(${y}px)`;
        });
    }
});
