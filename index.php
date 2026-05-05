<?php
require_once 'config.php';
$page_title = 'Trang chủ';

$banners = getActiveBanners(); 
$teamBuildingEvents = getTeamBuildingEvents(4);
$latestEvents = getLatestEvents(8);
$testimonials = getAllTestimonials(8);
$latestArticles = getLatestArticles(4); 

include 'includes/fe/header.php';

function fixImagePath($path) {
    if (empty($path)) return '';
    if (preg_match('#^(https?:)?//#i', $path)) {
        return $path;
    }
    if (strpos($path, 'uploads/banner') === 0) {
        return $path;
    }
    if (strpos($path, 'uploads/') === 0) {
        return '' . $path;
    }
    return $path;
}
?>
<style>
:root {
    --primary-gold: #D4A147;
    --primary-gold-light: #E5B563;
    --primary-gold-dark: #B8862F;
    --text-primary: #F5F5F0;
    --text-secondary: #D0D0C8;
    --text-muted: #A0A098;
    --dark-bg: #1A1A1A;
    --dark-bg-secondary: #242424;
    --dark-bg-tertiary: #2E2E2E;
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    --shadow-gold: 0 8px 20px rgba(212, 161, 71, 0.25);
    --shadow-lg: 0 10px 30px 0 rgba(0, 0, 0, 0.4);
    --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

* { margin: 0; padding: 0; box-sizing: border-box; }
html { scroll-behavior: smooth; }

body {
font-family: 'Times New Roman', sans-serif;
    background: linear-gradient(135deg, #1A1A1A 0%, #242424 50%, #1F1F1F 100%);
    color: var(--text-primary);
    line-height: 1.6;
    min-height: 100vh;
}

.container { max-width: 1320px; margin: 0 auto; padding: 0 24px; }

/* ==================== HEADER ==================== */
.main-header {
    background: linear-gradient(180deg, rgba(26,26,26,0.98) 0%, rgba(36,36,36,0.95) 100%);
    padding: 12px 0;
    position: sticky;
    top: 0;
    z-index: 1000;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
    border-bottom: 2px solid var(--primary-gold);
    backdrop-filter: blur(10px);
}
.header-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 30px;
}
.header-brand {
    display: flex;
    align-items: center;
    gap: 12px;
    background: linear-gradient(135deg, var(--primary-gold) 0%, var(--primary-gold-light) 100%);
    padding: 10px 16px;
    border-radius: 16px;
    box-shadow: var(--shadow-gold);
    transition: var(--transition);
    flex-shrink: 0;
}
.header-brand:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 28px rgba(212, 161, 71, 0.35);
}
.brand-logo {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: linear-gradient(135deg, #FFD700, #FFA500);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    border: 2px solid rgba(255,255,255,0.3);
    transition: var(--transition);
}
.brand-logo:hover { transform: scale(1.05); }
.brand-info { display: flex; flex-direction: column; }
.brand-title {
    color: #1a1a1a;
    font-weight: 800;
    font-size: 1.3rem;
    letter-spacing: 1px;
    text-transform: uppercase;
    line-height: 1.2;
}
.brand-tagline {
    color: #333;
    font-size: 0.7rem;
    letter-spacing: 2px;
    text-transform: uppercase;
    font-weight: 600;
    opacity: 0.8;
}
.header-nav { flex: 1; display: flex; align-items: center; justify-content: center; }
.nav-list {
    display: flex;
    list-style: none;
    gap: 16px;
    margin: 0;
    padding: 0;
}
.nav-link {
    color: var(--primary-gold);
    padding: 8px 20px;
    text-decoration: none;
    font-weight: 700;
    font-size: 0.95rem;
    border-radius: 30px;
    transition: var(--transition);
    white-space: nowrap;
    background: rgba(212, 161, 71, 0.08);
    border: 1px solid transparent;
    letter-spacing: 0.3px;
}
.nav-link:hover {
    background: rgba(212, 161, 71, 0.15);
    border-color: var(--primary-gold);
    color: var(--primary-gold-light);
    transform: translateY(-2px);
}
.nav-link.active {
    background: linear-gradient(135deg, var(--primary-gold), var(--primary-gold-light)) !important;
    color: #1a1a1a !important;
    box-shadow: var(--shadow-gold) !important;
    border-color: var(--primary-gold) !important;
}
.nav-toggle {
    display: none;
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.25);
    border-radius: 10px;
    color: white;
    font-size: 1.1rem;
    cursor: pointer;
    padding: 8px;
    transition: var(--transition);
}
.nav-toggle:hover {
    background: var(--primary-gold);
    color: #1a1a1a;
    border-color: var(--primary-gold);
}
.header-cta { flex-shrink: 0; display: flex; align-items: center; gap: 8px; }
.btn-phone {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: auto;
    height: 44px;
    padding: 0 18px;
    background: linear-gradient(135deg, var(--primary-gold), var(--primary-gold-light));
    color: #1a1a1a;
    text-decoration: none;
    border-radius: 10px;
    font-weight: 700;
    font-size: 1rem;
    transition: all 0.3s cubic-bezier(0.2, 0.9, 0.4, 1.1);
    box-shadow: var(--shadow-gold);
    border: none;
    cursor: pointer;
}
.btn-phone .phone-text { display: inline !important; }
.btn-phone i { font-size: 1rem; line-height: 1; }
.btn-phone:hover {
    background: linear-gradient(135deg, var(--primary-gold-light), var(--primary-gold));
    transform: translateY(-2px) scale(1.02);
    box-shadow: 0 8px 20px rgba(212, 161, 71, 0.5);
}

.slider-container {
    position: relative;
    width: 100%;
    overflow: hidden;
    margin-bottom: 40px;
    border-radius: 0 0 30px 30px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.5);
}

.slider-wrapper {
    position: relative;
    width: 100%;
    aspect-ratio: 16 / 7;  
}

.slide {
    position: absolute;
    top: 0; left: 0;
    width: 100%; height: 100%;
    opacity: 0;
    transition: opacity 0.6s ease;
    z-index: 1;
}
.slide.active { opacity: 1; z-index: 2; }
.slide img {
    width: 100%; height: 100%;
    object-fit: cover;
    filter: brightness(0.75);
}
.slider-prev, .slider-next {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(0,0,0,0.6);
    backdrop-filter: blur(5px);
    color: white;
    border: none;
    width: 48px; height: 48px;
    border-radius: 50%;
    cursor: pointer;
    z-index: 10;
    font-size: 1.5rem;
    transition: 0.3s;
}
.slider-prev:hover, .slider-next:hover {
    background: var(--primary-gold);
    color: #1a1a1a;
}
.slider-prev { left: 20px; }
.slider-next { right: 20px; }
.slider-dots {
    position: absolute;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 12px;
    z-index: 10;
}
.dot {
    width: 12px;
    height: 12px;
    background: rgba(255,255,255,0.5);
    border-radius: 50%;
    cursor: pointer;
}
.dot.active {
    background: var(--primary-gold);
    transform: scale(1.3);
}

/* ==================== HERO (tĩnh, không dùng ở đây nhưng giữ lại) ==================== */
.hero {
    height: 500px;
    background: linear-gradient(135deg, rgba(26,26,26,0.7), rgba(36,36,36,0.7)), 
                url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 600"><defs><pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse"><path d="M 40 0 L 0 0 0 40" fill="none" stroke="rgba(212,161,71,0.1)" stroke-width="1"/></pattern></defs><rect width="1200" height="600" fill="url(%23grid)"/></svg>');
    background-size: cover;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    margin-bottom: 60px;
}
.hero h1 {
    font-size: 3.5rem;
    font-weight: 800;
    color: var(--primary-gold);
    text-shadow: 3px 3px 6px rgba(0,0,0,0.6);
}
.hero p {
    font-size: 1.3rem;
    color: var(--text-secondary);
    max-width: 700px;
}

/* ==================== SECTION HEADING ==================== */
.section-heading {
    text-align: center;
    margin-bottom: 50px;
}
.section-heading h2 {   
    font-size: 1.6rem;
    font-weight: 800;
    position: relative;
    display: inline-block;
    padding-bottom: 20px;
    margin-bottom: 20px;
    color: var(--primary-gold);
    text-transform: uppercase;
    letter-spacing: 1.5px;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
}
.section-heading h2::before {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 100px;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-gold), var(--primary-gold-light));
    border-radius: 2px;
    box-shadow: 0 4px 12px rgba(212, 161, 71, 0.3);
}
.section-heading p {
    color: var(--text-secondary);
    font-size: 1.05rem;
    max-width: 650px;
    margin: 15px auto 0;
}

.two-columns {
    display: flex;
    gap: 40px;
    margin-bottom: 60px;
}
.main-col { flex: 2; }
.side-col { flex: 1; }

.grid-3 {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 32px;
    margin-bottom: 60px;
}
.grid-4 {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 25px;
    margin-bottom: 60px;
}

/* Card chung cho event, news */
.card, .event-card {
    background: linear-gradient(135deg, #1A1A1A, #242424);
    border-radius: 24px;
    overflow: hidden;
    transition: var(--transition);
    box-shadow: 0 4px 12px 0 rgba(0, 0, 0, 0.3);
    border: 1px solid rgba(212, 161, 71, 0.2);
    display: flex;
    flex-direction: column;
    height: 100%;
    text-decoration: none;
    color: inherit;
}
.card:hover, .event-card:hover {
    transform: translateY(-8px);
    box-shadow: var(--shadow-lg);
    border-color: rgba(212, 161, 71, 0.5);
}
.card-img {
    height: 220px;
    background: #2a2a2a;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary-gold);
    font-size: 3rem;
    overflow: hidden;
}
.card-img img {
    width: 100%; height: 100%;
    object-fit: cover;
    transition: transform 0.5s;
}
.event-card:hover .card-img img { transform: scale(1.05); }
.img-placeholder {
    width: 100%; height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #2a2a2a;
}
.card-content {
    padding: 24px;
    flex: 1;
    display: flex;
    flex-direction: column;
}
.card-title {
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 8px;
    color: var(--text-primary);
    line-height: 1.4;
}
.card-date {
    font-size: 0.85rem;
    color: var(--primary-gold);
    margin-bottom: 12px;
    font-weight: 600;
}
.card-desc {
    font-size: 0.95rem;
    color: var(--text-secondary);
    line-height: 1.5;
    flex: 1;
}
.read-more, .card-link {
    color: var(--primary-gold);
    font-weight: 700;
    margin-top: 12px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: var(--transition);
}
.read-more:hover, .card-link:hover {
    color: var(--primary-gold-light);
    transform: translateX(6px);
}

.home-page .grid-3 {
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 32px;
    margin-bottom: 40px;
}

.home-page .grid-4 {
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 32px;
    margin-bottom: 40px;
}

.home-page .event-card {
    background: linear-gradient(135deg, #1A1A1A, #242424);
    border: 1px solid rgba(212, 161, 71, 0.2);
    border-radius: 24px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
}

.home-page .card-img {
    height: 190px;
    background: #2a2a2a;
    font-size: 3rem;
}

.home-page .card-content {
    padding: 18px 20px 20px;
}

.home-page .card-title {
    font-size: 1.15rem;
    line-height: 1.32;
    margin-bottom: 8px;
    color: var(--text-primary);
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.home-page .card-date {
    color: var(--primary-gold);
    font-size: 0.85rem;
    margin-bottom: 10px;
    font-weight: 600;
}

.home-page .card-desc {
    color: var(--text-secondary);
    font-size: 0.9rem;
    line-height: 1.42;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.home-page .read-more,
.home-page .btn-outline-light {
    display: inline-flex;
    align-items: center;
    width: fit-content;
    margin-top: 12px;
    font-size: 0.82rem;
    line-height: 1;
    padding: 7px 14px;
    border-radius: 18px;
    border: 1px solid var(--primary-gold);
    color: var(--primary-gold);
    text-decoration: none;
}

/* ==================== SIDEBAR ==================== */
.video-box {
    background: linear-gradient(135deg, #1A1A1A, #242424);
    border-radius: 24px;
    padding: 20px;
    margin-bottom: 30px;
    box-shadow: var(--shadow-md);
    border: 1px solid rgba(212,161,71,0.2);
}
.video-box h3 {
    font-size: 1.3rem;
    color: var(--primary-gold);
    margin-bottom: 15px;
    font-weight: 700;
}
.video-wrapper {
    position: relative;
    padding-bottom: 56.25%;
    height: 0;
    border-radius: 16px;
    overflow: hidden;
}
.video-wrapper iframe {
    position: absolute;
    top:0; left:0;
    width:100%; height:100%;
}
.btn-gradient {
    background: linear-gradient(135deg, var(--primary-gold), var(--primary-gold-light));
    color: #1a1a1a;
    font-weight: 700;
    border: none;
    border-radius: 40px;
    padding: 12px 28px;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
    transition: var(--transition);
    box-shadow: var(--shadow-gold);
}
.btn-gradient:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 28px rgba(212,161,71,0.4);
}
.btn-outline-light {
    display: inline-block;
    padding: 8px 20px;
    border: 1px solid var(--primary-gold);
    border-radius: 20px;
    color: var(--primary-gold);
    font-size: 0.85rem;
    font-weight: 600;
    margin-top: 12px;
    transition: var(--transition);
    text-decoration: none;
}
.btn-outline-light:hover {
    background: var(--primary-gold);
    color: #1a1a1a;
}

/* ==================== VISION - MISSION ==================== */
.vision-mission-section {
    background: #111;
    padding: 60px 32px;
    margin: 60px 0;
    border-radius: 32px;
}
.vmv-title {
    text-align: center;
    font-size: 1.6rem;
    font-weight: 800;
    color: var(--primary-gold);
    margin-bottom: 50px;
    position: relative;
}
.vmv-title::after {
    content: '';
    position: absolute;
    bottom: -20px;
    left: 50%;
    transform: translateX(-50%);
    width: 200px;
    height: 3px;
    background: var(--primary-gold);
}
.vmv-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 50px;
    margin: 50px 0;
}
.vmv-card {
    background: #1e1e1e;
    border-left: 6px solid var(--primary-gold);
    padding: 40px 30px;
    border-radius: 16px;
}
.vmv-card h3 {
    font-size: 1.8rem;
    color: var(--primary-gold);
    margin-bottom: 20px;
}
.vmv-card p, .vmv-card ul li {
    color: var(--text-secondary);
    line-height: 1.8;
}
.vmv-card ul { list-style: none; margin-top: 15px; }
.vmv-card ul li { padding-left: 25px; position: relative; margin-bottom: 12px; }
.vmv-card ul li::before {
    content: '●';
    position: absolute;
    left: 0;
    color: var(--primary-gold);
}
.core-values-title {
    text-align: center;
    font-size: 1.6rem;
    font-weight: 800;
    color: var(--primary-gold);
    margin: 50px 0;
}
.core-values-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 20px;
}
.core-value-item {
    background: #1e1e1e;
    border-top: 4px solid var(--primary-gold);
    padding: 32px 20px;
    text-align: center;
    border-radius: 16px;
}
.core-value-item h4 {
    font-size: 1.05rem;
    font-weight: 700;
    color: var(--text-primary);
}

/* ==================== TESTIMONIALS ==================== */
.testimonials-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 30px;
    margin: 40px 0;
}
.testimonial-card {
    background: linear-gradient(135deg, #1A1A1A, #242424);
    border-radius: 20px;
    padding: 28px;
    text-align: center;
    border: 1px solid rgba(212,161,71,0.2);
}
.testimonial-avatar img {
    width: 80px; height: 80px;
    border-radius: 50%;
    object-fit: cover;
    margin-bottom: 16px;
    border: 3px solid var(--primary-gold);
}
.testimonial-rating { color: var(--primary-gold); margin-bottom: 16px; }
.testimonial-text {
    font-style: italic;
    color: var(--text-secondary);
    margin: 16px 0;
}
.testimonial-author strong { display: block; color: var(--text-primary); }
.testimonial-author span { font-size: 0.85rem; color: var(--text-muted); }

/* ==================== FOOTER ==================== */
.footer {
        background: linear-gradient(180deg, rgba(26, 26, 26, 0.98) 0%, rgba(36, 36, 36, 0.95) 100%);
    color: var(--text-secondary);
    padding: 60px 0 30px;
    margin-top: 80px;
    border-top: 2px solid var(--primary-gold);
}
.footer-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 40px;
    margin-bottom: 40px;
}
.footer-col h4 {
    color: var(--primary-gold);
    font-size: 1.3rem;
    font-weight: 800;
    margin-bottom: 20px;
    position: relative;
    padding-bottom: 12px;
}
.footer-col h4::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0;
    width: 50px;
    height: 3px;
    background: linear-gradient(90deg, var(--primary-gold), transparent);
}
.footer-col p {
    margin-bottom: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.footer-col i { width: 28px; color: var(--primary-gold); }
.social-icons { display: flex; gap: 14px; margin-top: 18px; }
.social-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, rgba(212,161,71,0.15), rgba(212,161,71,0.05));
    border: 1px solid rgba(212,161,71,0.3);
    border-radius: 50%;
    color: var(--primary-gold);
    font-size: 1.3rem;
    transition: var(--transition);
    text-decoration: none;
}
.social-icon:hover {
    background: var(--primary-gold);
    color: #1a1a1a;
    transform: translateY(-5px);
    box-shadow: var(--shadow-gold);
}
.copyright {
    text-align: center;
    padding-top: 24px;
    border-top: 1px solid rgba(212,161,71,0.2);
    font-size: 0.85rem;
    color: var(--text-muted);
}

/* ==================== RESPONSIVE ==================== */
@media (max-width: 992px) {
    .two-columns { flex-direction: column; }
    .vmv-container { grid-template-columns: 1fr; gap: 30px; }
    .core-values-grid { grid-template-columns: repeat(auto-fit, minmax(150px,1fr)); }
}
@media (max-width: 768px) {
    .btn-phone { width: 44px; height: 44px; padding: 0; gap: 0; }
    .btn-phone .phone-text { display: none !important; }
    .nav-toggle { display: flex; }
    .nav-list {
        position: fixed; top: 0; right: -100%;
        width: 280px; height: 100vh;
        background: #242424;
        flex-direction: column;
        padding: 70px 20px;
        transition: right 0.3s;
        z-index: 9999;
    }
    .nav-list.show { right: 0; }
    .nav-link { color: var(--text-secondary); }
    .section-heading h2 { font-size: 1.6rem; }
    .grid-3, .grid-4, .testimonials-grid { grid-template-columns: 1fr; gap: 24px; }
    .home-page .grid-3,
    .home-page .grid-4 {
        grid-template-columns: 1fr;
        gap: 28px;
    }
    .home-page .event-card { border-radius: 24px; }
    .home-page .card-img { height: 180px; }
    .home-page .card-content { padding: 18px 20px 20px; }
    .home-page .card-title { font-size: 1.1rem; }
    .home-page .card-date { font-size: 0.82rem; margin-bottom: 10px; }
    .home-page .card-desc { font-size: 0.88rem; }
    .hero { height: 300px; }
}
@media (max-width: 576px) {
    .vmv-title { font-size: 2rem; }
    .core-values-grid { grid-template-columns: 1fr; }
}
</style>

<div class="slider-container">
    <div class="slider-wrapper" id="mainSlider">
        <?php if (!empty($banners)): ?>
            <?php foreach ($banners as $index => $banner): ?>
                <div class="slide <?= $index === 0 ? 'active' : '' ?>">
                    <img src="<?= htmlspecialchars(fixImagePath($banner['image_url'])) ?>" alt="<?= htmlspecialchars($banner['title']) ?>">
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="slide active">
                <img src="https://event5.mauthemewp.com/wp-content/uploads/2018/05/SLIDER2.jpg" alt="Default Slider">
            </div>
        <?php endif; ?>
    </div>
    <button class="slider-prev"><i class="fas fa-chevron-left"></i></button>
    <button class="slider-next"><i class="fas fa-chevron-right"></i></button>
    <div class="slider-dots"></div>
</div>

<main class="home-page">
    <div class="container">
        <div class="section-heading">
            <h2>Các Chương Trình Đã Thực Hiện</h2>
            <p>Những chương trình đẳng cấp, kết nối và bùng nổ</p>
        </div>
        <div class="two-columns">
            <div class="main-col">
                <div class="grid-3">
                    <?php foreach ($teamBuildingEvents as $event): ?>
                        <a href="event_detail.php?id=<?= $event['id'] ?>" class="event-card">
                            <div class="card-img">
                                <?php if (!empty($event['image']) && strpos($event['image'], 'data:image') !== false): ?>
                                    <div class="img-placeholder"><i class="fas fa-flag-checkered"></i></div>
                                <?php else: ?>
                                    <img src="<?= htmlspecialchars(fixImagePath($event['image'])) ?>" alt="<?= htmlspecialchars($event['title']) ?>">
                                <?php endif; ?>
                            </div>
                            <div class="card-content">
                                <h3 class="card-title"><?= htmlspecialchars($event['title']) ?></h3>
                                <div class="card-date"><i class="far fa-calendar-alt"></i> <?= date('d/m/Y', strtotime($event['created_at'])) ?></div>
                                <p class="card-desc"><?= cleanText($event['description'] ?? '') ?></p>
                                <span class="read-more">Xem thêm →</span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="side-col">
                <div class="video-box">
                    <h3><i class="fas fa-video"></i> Video Chương Trình</h3>
                    <div class="video-wrapper">
                        <iframe src="https://www.youtube.com/embed/mrPm9eXV1Hk" allowfullscreen></iframe>
                    </div>
                </div>
                <div class="video-box">
                    <h3><i class="fas fa-fire"></i> Người Truyền Lửa</h3>
                    <div class="video-wrapper">
                        <iframe src="https://www.youtube.com/embed/CM9yJOLiE4k" allowfullscreen></iframe>
                    </div>
                </div>
                <div style="text-align: center; margin-top: 24px;">
                    <a href="#" class="btn-gradient">
                        <i class="fas fa-download"></i>
                        <span>Tải Brochure</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- SECTION 2: EVENTS ORGANIZED -->
        <div class="section-heading" style="margin-top: 80px;">
            <h2>Những Sự Kiện Chúng Tôi Đã Tổ Chức</h2>
            <p>Hơn 500+ sự kiện lớn nhỏ cho các tập đoàn hàng đầu</p>
        </div>
        <div class="grid-4">
            <?php foreach ($latestEvents as $event): ?>
                <a href="event_detail.php?id=<?= $event['id'] ?>" class="event-card">
                    <div class="card-img">
                        <?php if (!empty($event['image']) && strpos($event['image'], 'data:image') !== false): ?>
                            <div class="img-placeholder"><i class="fas fa-calendar-check"></i></div>
                        <?php else: ?>
                            <img src="<?= htmlspecialchars(fixImagePath($event['image'])) ?>" alt="<?= htmlspecialchars($event['title']) ?>">
                        <?php endif; ?>
                    </div>
                    <div class="card-content">
                        <h3 class="card-title"><?= htmlspecialchars($event['title']) ?></h3>
                        <div class="card-date"><i class="far fa-calendar-alt"></i> <?= date('d/m/Y', strtotime($event['created_at'])) ?></div>
                        <p class="card-desc"><?= cleanText($event['description'] ?? '') ?></p>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- VISION, MISSION & CORE VALUES -->
        <div class="vision-mission-section">
            <div class="vmv-title">Tầm nhìn, Sứ mệnh & Giá trị cốt lõi</div>
            <div class="vmv-container">
                <div class="vmv-card">
                    <div class="vmv-icon"><i class="fas fa-eye"></i></div>
                    <h3>Tầm nhìn</h3>
                    <p>Trở thành doanh nghiệp dẫn đầu về dịch vụ sự kiện truyền thông và cung cấp nguồn nhân lực chất lượng cao. Chúng tôi tiên phong kiến tạo những trải nghiệm độc phát, định hình tiêu chuẩn mới cho ngành công nghiệp sự kiện tại Việt Nam và vương tầm khu vực.</p>
                </div>
                <div class="vmv-card">
                    <div class="vmv-icon"><i class="fas fa-bullseye"></i></div>
                    <h3>Sứ mệnh</h3>
                    <p>Trở thành nhà cung cấp dịch vụ tin cậy và thực thi trách nhiệm song hành cùng mục tiêu kinh doanh của khách hàng thông qua:</p>
                    <ul>
                        <li>Cung cấp giải pháp sự kiện toàn diện, tối ưu chi phí.</li>
                        <li>Kết nối và phát triển nguồn nhân lực chuyên nghiệp.</li>
                        <li>Tạo ra giá trị bền vững cho cộng đồng và đối tác.</li>
                    </ul>
                </div>
            </div>
            <div class="core-values-title">5 Giá trị cốt lõi</div>
            <div class="core-values-grid">
                <div class="core-value-item"><h4>Lợi ích khách hàng là then chốt</h4></div>
                <div class="core-value-item"><h4>Chất lượng dịch vụ vượt trội</h4></div>
                <div class="core-value-item"><h4>Sáng tạo và đổi mới</h4></div>
                <div class="core-value-item"><h4>Tận tâm và trách nhiệm</h4></div>
                <div class="core-value-item"><h4>Đồng hành cùng phát triển</h4></div>
            </div>
        </div>

        <!-- TESTIMONIALS -->
        <div class="section-heading" style="margin-top: 80px;">
            <h2>Đánh Giá Từ Khánh Hàng</h2>
            <p>Niềm tin từ các tập đoàn hàng đầu và đối tác chiến lược</p>
        </div>
        <div class="testimonials-grid">
            <?php foreach ($testimonials as $t): ?>
                <div class="testimonial-card">
                    <div class="testimonial-avatar">
                        <?php
                        $avatar = fixImagePath($t['avatar'] ?? '');
                        if (!$avatar) $avatar = 'https://via.placeholder.com/80x80?text=Avatar';
                        ?>
                        <img src="<?= htmlspecialchars($avatar) ?>" alt="<?= htmlspecialchars($t['name']) ?>">
                    </div>
                    <div class="testimonial-rating">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <?php if ($i <= $t['rating']): ?>
                                <i class="fas fa-star"></i>
                            <?php else: ?>
                                <i class="far fa-star"></i>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </div>
                    <p class="testimonial-text">"<?= cleanText($t['content']) ?>"</p>
                    <div class="testimonial-author">
                        <strong><?= htmlspecialchars($t['name']) ?></strong>
                        <span><?= htmlspecialchars($t['position']) ?>, <?= htmlspecialchars($t['company']) ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- LATEST ARTICLES -->
        <div class="section-heading" style="margin-top: 80px;">
            <h2>Tin Tức & Bài Viết</h2>
            <p>Cập nhật những xu hướng mới nhất trong tổ chức sự kiện</p>
        </div>
        <div class="grid-3 news-grid">
            <?php foreach ($latestArticles as $article): ?>
                <a href="article_detail.php?id=<?= $article['id'] ?>" class="event-card news-card">
                    <div class="card-img">
                        <?php if (!empty($article['image']) && strpos($article['image'], 'data:image') !== false): ?>
                            <div class="img-placeholder"><i class="fas fa-newspaper"></i></div>
                        <?php else: ?>
                            <img src="<?= htmlspecialchars(fixImagePath($article['image'])) ?>" alt="<?= htmlspecialchars($article['title']) ?>">
                        <?php endif; ?>
                    </div>
                    <div class="card-content">
                        <h3 class="card-title"><?= htmlspecialchars($article['title']) ?></h3>
                        <div class="card-date"><i class="far fa-calendar-alt"></i> <?= date('d/m/Y', strtotime($article['created_at'])) ?></div>
                        <p class="card-desc"><?= cleanText($article['description'] ?? '') ?></p>
                        <span class="btn-outline-light">Đọc tiếp</span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</main>

<?php include 'includes/fe/footer.php'; ?>
