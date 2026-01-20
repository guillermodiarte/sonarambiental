document.addEventListener('DOMContentLoaded', () => {
    // Mobile Menu Toggle
    const mobileBtn = document.querySelector('.mobile-menu-btn');
    const navLinks = document.querySelector('.nav-links');

    if (mobileBtn && navLinks) {
        mobileBtn.addEventListener('click', () => {
            navLinks.classList.toggle('active');

            // Optional: Toggle icon between hamburger and close 'X'
            if (navLinks.classList.contains('active')) {
                mobileBtn.textContent = '✕';
            } else {
                mobileBtn.textContent = '☰';
            }
        });
    }

    // Scroll Animations (Simple Intersection Observer)
    const fadeElements = document.querySelectorAll('.service-card, .section-title, .project-card');

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    fadeElements.forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
        observer.observe(el);
    });

    // =========================================
    // CAROUSEL LOGIC
    // =========================================
    const track = document.querySelector('.carousel-track');
    if (track) {
        const slides = Array.from(track.children);
        const nav = document.querySelector('.carousel-nav');

        // Create indicators
        slides.forEach((_, index) => {
            const indicator = document.createElement('div');
            indicator.classList.add('carousel-indicator');
            if (index === 0) indicator.classList.add('active');
            nav.appendChild(indicator);

            indicator.addEventListener('click', () => {
                moveToSlide(index);
            });
        });

        const indicators = Array.from(nav.children);
        let currentIndex = 0;
        let autoPlayInterval;

        function moveToSlide(index) {
            // Ensure index is within bounds
            if (index < 0) index = slides.length - 1;
            if (index >= slides.length) index = 0;

            const slideWidth = slides[0].getBoundingClientRect().width;
            track.style.transform = 'translateX(-' + (slideWidth * index) + 'px)';

            indicators.forEach(ind => ind.classList.remove('active'));
            indicators[index].classList.add('active');

            currentIndex = index;
            resetAutoPlay();
        }

        function startAutoPlay() {
            autoPlayInterval = setInterval(() => {
                moveToSlide(currentIndex + 1);
            }, 5000); // 5 seconds
        }

        function resetAutoPlay() {
            clearInterval(autoPlayInterval);
            startAutoPlay();
        }

        // Handle window resize
        window.addEventListener('resize', () => {
            moveToSlide(currentIndex);
        });

        // Initialize
        startAutoPlay();
    }
});
