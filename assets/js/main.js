// main.js - FIXED VERSION (Slider, Menu Mobile, Animation, Sticky Header)
document.addEventListener('DOMContentLoaded', function() {
    // ========== SLIDER (KHÔNG BỊ TRẮNG, XỬ LÝ NHIỀU SLIDE) ==========
    const slides = document.querySelectorAll('#mainSlider .slide');
    const prevBtn = document.querySelector('.slider-prev');
    const nextBtn = document.querySelector('.slider-next');
    const dotsContainer = document.querySelector('.slider-dots');
    const sliderContainer = document.querySelector('.slider-container');
    
    if (slides.length === 0) return;
    
    let currentIndex = 0;
    let interval;
    let touchStartX = 0;
    let touchEndX = 0;

    function updateSlides() {
        slides.forEach((slide, idx) => {
            const img = slide.querySelector('img');
            if (idx === currentIndex) {
                slide.classList.add('active');
                slide.style.opacity = '1';
                slide.style.zIndex = '2';
                if (img) {
                    img.style.transition = 'transform 8s ease';
                    img.style.transform = 'scale(1.05)';
                }
            } else {
                slide.classList.remove('active');
                slide.style.opacity = '0';
                slide.style.zIndex = '1';
                if (img) {
                    img.style.transition = 'none';
                    img.style.transform = 'scale(1)';
                }
            }
        });
        updateDots();
    }

    function updateDots() {
        if (!dotsContainer) return;
        dotsContainer.innerHTML = '';
        slides.forEach((_, idx) => {
            const dot = document.createElement('span');
            dot.classList.add('dot');
            if (idx === currentIndex) dot.classList.add('active');
            dot.addEventListener('click', () => goToSlide(idx));
            dotsContainer.appendChild(dot);
        });
    }

    function goToSlide(index) {
        if (index < 0) index = slides.length - 1;
        if (index >= slides.length) index = 0;
        if (currentIndex === index) return;
        currentIndex = index;
        updateSlides();
        resetInterval();
    }

    function nextSlide() { goToSlide(currentIndex + 1); }
    function prevSlide() { goToSlide(currentIndex - 1); }

    function resetInterval() {
        if (interval) clearInterval(interval);
        if (slides.length > 1) {
            interval = setInterval(nextSlide, 5000);
        }
    }

    if (slides.length > 1) {
        if (prevBtn && nextBtn) {
            prevBtn.addEventListener('click', prevSlide);
            nextBtn.addEventListener('click', nextSlide);
        }
        
        const sliderWrapper = document.querySelector('.slider-wrapper');
        if (sliderWrapper) {
            sliderWrapper.addEventListener('touchstart', (e) => {
                touchStartX = e.changedTouches[0].screenX;
            });
            sliderWrapper.addEventListener('touchend', (e) => {
                touchEndX = e.changedTouches[0].screenX;
                if (touchEndX < touchStartX - 50) nextSlide();
                if (touchEndX > touchStartX + 50) prevSlide();
            });
        }
        
        if (sliderContainer) {
            sliderContainer.addEventListener('mouseenter', () => {
                if (interval) clearInterval(interval);
            });
            sliderContainer.addEventListener('mouseleave', resetInterval);
        }
        
        updateSlides();
        resetInterval();
    } else {
        if (prevBtn) prevBtn.style.display = 'none';
        if (nextBtn) nextBtn.style.display = 'none';
        if (dotsContainer) dotsContainer.style.display = 'none';
        if (slides[0]) {
            slides[0].style.opacity = '1';
            slides[0].style.zIndex = '2';
            slides[0].classList.add('active');
        }
    }

    // ========== STICKY HEADER (Ẩn/Hiện khi cuộn) ==========
    const header = document.querySelector('.main-header');
    if (header) {
        let lastScroll = 0;
        let ticking = false;
        window.addEventListener('scroll', () => {
            if (!ticking) {
                requestAnimationFrame(() => {
                    const currentScroll = window.pageYOffset;
                    if (currentScroll > lastScroll && currentScroll > 100) {
                        header.style.transform = 'translateY(-100%)';
                    } else {
                        header.style.transform = 'translateY(0)';
                    }
                    lastScroll = currentScroll;
                    ticking = false;
                });
                ticking = true;
            }
        });
        header.addEventListener('mouseenter', () => {
            header.style.transform = 'translateY(0)';
        });
    }

    // ========== SMOOTH SCROLL CHO CÁC LINK NỘI BỘ ==========
    const navLinks = document.querySelectorAll('.nav-links a[href^="#"]');
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                e.preventDefault();
                targetElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // ========== ANIMATION KHI CUỘN (Event Cards, Testimonials) ==========
    const animateElements = document.querySelectorAll('.event-card, .testimonial-card');
    if (animateElements.length) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });
        animateElements.forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(el);
        });
    }
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('navToggle');
    const navList = document.getElementById('navList');
    
    if (toggleBtn && navList) {
        toggleBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            navList.classList.toggle('show');
            document.body.classList.toggle('menu-open');
            
            // Đổi icon hamburger ↔ close
            const icon = toggleBtn.querySelector('i');
            if (navList.classList.contains('show')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });
        
        // Đóng menu khi click ra ngoài
        document.addEventListener('click', function(event) {
            if (navList.classList.contains('show') && 
                !navList.contains(event.target) && 
                !toggleBtn.contains(event.target)) {
                navList.classList.remove('show');
                document.body.classList.remove('menu-open');
                const icon = toggleBtn.querySelector('i');
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });
        
        // Đóng menu khi click vào link
        navList.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', function() {
                navList.classList.remove('show');
                document.body.classList.remove('menu-open');
                const icon = toggleBtn.querySelector('i');
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            });
        });
    }
});
    const sliderImages = document.querySelectorAll('#mainSlider .slide img');
    let imagesLoaded = 0;
    if (sliderImages.length) {
        sliderImages.forEach(img => {
            if (img.complete) {
                imagesLoaded++;
            } else {
                img.addEventListener('load', () => {
                    imagesLoaded++;
                    if (imagesLoaded === sliderImages.length) {
                        document.querySelector('.slider-container')?.classList.add('loaded');
                    }
                });
                img.addEventListener('error', () => {
                    imagesLoaded++;
                });
            }
        });
    }
});