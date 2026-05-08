document.addEventListener('DOMContentLoaded', function () {

    const toggleBtn = document.getElementById('navToggle');
    const navList = document.getElementById('navList');
    const navNav = document.getElementById('headerNav') || document.querySelector('.header-nav');
    const closeBtn = document.getElementById('menuCloseBtn') || document.querySelector('.menu-close-btn');
    const body = document.body;

    if (toggleBtn && navList && navNav) {
        const toggleIcon = toggleBtn.querySelector('i');

        function openMenu() {
            navNav.classList.add('show');
            navList.classList.add('show');
            body.classList.add('menu-open');
            toggleBtn.setAttribute('aria-expanded', 'true');
            toggleBtn.setAttribute('aria-label', 'Đóng menu');
            if (toggleIcon) toggleIcon.className = 'fas fa-times';
        }

        function closeMenu() {
            navNav.classList.remove('show');
            navList.classList.remove('show');
            body.classList.remove('menu-open');
            toggleBtn.setAttribute('aria-expanded', 'false');
            toggleBtn.setAttribute('aria-label', 'Mở menu');
            if (toggleIcon) toggleIcon.className = 'fas fa-bars';
        }

        toggleBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            navNav.classList.contains('show') ? closeMenu() : openMenu();
        });

        if (closeBtn) {
            closeBtn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                closeMenu();
            });
        }

        document.addEventListener('click', function (e) {
            if (e.target.closest('#menuCloseBtn, .menu-close-btn')) {
                e.preventDefault();
                e.stopPropagation();
                closeMenu();
            }
        }, true);

        document.addEventListener('click', function (e) {
            if (navNav.classList.contains('show') &&
                !navNav.contains(e.target) &&
                !toggleBtn.contains(e.target)) {
                closeMenu();
            }
        });

        navList.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', function () {
                closeMenu();
            });
        });

        window.addEventListener('resize', function () {
            if (window.innerWidth > 768) closeMenu();
        });
    }

    // ==================== KEEP HEADER STABLE ====================
    const header = document.querySelector('.main-header');
    if (header) {
        header.style.transform = 'none';
        window.addEventListener('scroll', () => {
            header.style.transform = 'none';
        }, { passive: true });
    }

    // ==================== SLIDER ====================
    const slides = document.querySelectorAll('#mainSlider .slide');
    const prevBtn = document.querySelector('.slider-prev');
    const nextBtn = document.querySelector('.slider-next');
    const dotsContainer = document.querySelector('.slider-dots');
    const sliderContainer = document.querySelector('.slider-container');

    if (slides.length > 0) {
        let currentIndex = 0, interval;
        let touchStartX = 0, touchEndX = 0;

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
            if (slides.length > 1) interval = setInterval(nextSlide, 5000);
        }

        if (slides.length > 1) {
            if (prevBtn) prevBtn.addEventListener('click', prevSlide);
            if (nextBtn) nextBtn.addEventListener('click', nextSlide);

            const sliderWrapper = document.querySelector('.slider-wrapper');
            if (sliderWrapper) {
                sliderWrapper.addEventListener('touchstart', e => {
                    touchStartX = e.changedTouches[0].screenX;
                });
                sliderWrapper.addEventListener('touchend', e => {
                    touchEndX = e.changedTouches[0].screenX;
                    if (touchEndX < touchStartX - 50) nextSlide();
                    if (touchEndX > touchStartX + 50) prevSlide();
                });
            }

            if (sliderContainer) {
                sliderContainer.addEventListener('mouseenter', () => clearInterval(interval));
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
    }

    // ==================== SCROLL ANIMATION ====================
    const cards = document.querySelectorAll('.event-card, .testimonial-card');
    if (cards.length) {
        const obs = new IntersectionObserver(entries => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    e.target.style.opacity = '1';
                    e.target.style.transform = 'translateY(0)';
                    obs.unobserve(e.target);
                }
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

        cards.forEach(el => {
            el.style.cssText += 'opacity:0;transform:translateY(20px);transition:opacity .6s ease,transform .6s ease;';
            obs.observe(el);
        });
    }

});
