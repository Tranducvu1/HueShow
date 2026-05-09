<?php
require_once 'config.php';

$company_name = "HueShow";
$slogan = "Kiến tạo sự kiện – Kết nối thành công";
$founded_year = "2020";

$recentPosts = getLatestArticles(4);

include 'includes/fe/header.php';
?>

<style>
    /* ==================== BIẾN MÀU CHÍNH (GOLD DARK THEME) ==================== */
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
        font-family: 'Arial', 'Calibri', sans-serif;
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
        gap: 8px;
        padding: 10px 22px;
        background: linear-gradient(135deg, var(--primary-gold), var(--primary-gold-light));
        color: #1a1a1a;
        text-decoration: none;
        border-radius: 30px;
        font-weight: 700;
        font-size: 0.9rem;
        transition: all 0.3s cubic-bezier(0.2, 0.9, 0.4, 1.1);
        box-shadow: var(--shadow-gold);
        border: none;
        cursor: pointer;
    }
    .btn-phone:hover {
        background: linear-gradient(135deg, var(--primary-gold-light), var(--primary-gold));
        transform: translateY(-2px) scale(1.02);
        box-shadow: 0 8px 20px rgba(212, 161, 71, 0.5);
    }

    /* ==================== HERO SECTION ==================== */
    .hero {
        height: 500px;
        background: linear-gradient(135deg, rgba(26,26,26,0.7), rgba(36,36,36,0.7)), 
                    url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 600"><defs><pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse"><path d="M 40 0 L 0 0 0 40" fill="none" stroke="rgba(212,161,71,0.1)" stroke-width="1"/></pattern></defs><rect width="1200" height="600" fill="url(%23grid)"/></svg>');
        background-size: cover;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        color: var(--text-primary);
        margin-bottom: 60px;
        position: relative;
        overflow: hidden;
    }
    .hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 50% 50%, rgba(212,161,71,0.15), transparent);
        pointer-events: none;
    }
    .hero-content {
        position: relative;
        z-index: 2;
        animation: fadeInUp 0.8s ease forwards;
    }
    .hero h1 {
        font-size: 3.5rem;
        font-weight: 800;
        letter-spacing: 2px;
        text-transform: uppercase;
        margin-bottom: 20px;
        color: var(--primary-gold);
        text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.6);
    }
    .hero p {
        font-size: 1.3rem;
        color: var(--text-secondary);
        max-width: 700px;
        margin-bottom: 30px;
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .intro-hero {
        background: linear-gradient(120deg, #0f172a 0%, #1a1a2e 100%);
        color: white;
        padding: 80px 0;
        text-align: center;
        margin-bottom: 40px;
    }
    .intro-hero h1 {
        margin-bottom: 16px;
                font-size: 1.6rem;
        color: var(--primary-gold);
    }
    .intro-hero p {
        font-size: 1.2rem;
        max-width: 700px;
        margin: 0 auto;
        opacity: 0.9;
    }

    /* ==================== LAYOUT & CARDS (DARK THEME) ==================== */
    .row-large {
        display: grid;
        grid-template-columns: minmax(0, 1.55fr) minmax(300px, 0.85fr);
        gap: 32px;
        margin-bottom: 40px;
        align-items: start;
    }
    .main-content {
        min-width: 0;
        background: linear-gradient(135deg, #1A1A1A, #242424);
        border-radius: 28px;
        padding: 34px;
        box-shadow: 0 20px 35px -12px rgba(0,0,0,0.4);
        border: 1px solid rgba(212,161,71,0.2);
    }
    .sidebar {
        min-width: 0;
        position: sticky;
        top: 100px;
        align-self: start;
    }
    .main-content h2 {
        font-size: 1.55rem;
        margin: 28px 0 14px;
        color: var(--primary-gold);
        font-weight: 800;
        line-height: 1.35;
    }
    .main-content p {
        margin-bottom: 1rem;
        line-height: 1.75;
        color: var(--text-secondary);
        font-size: 1rem;
    }
    .highlight-box {
        background: linear-gradient(135deg, rgba(212,161,71,0.14), rgba(212,161,71,0.06));
        border: 1px solid rgba(212,161,71,0.24);
        border-left: 5px solid var(--primary-gold);
        padding: 18px 22px;
        border-radius: 20px;
        margin: 20px 0;
        color: var(--text-secondary);
    }
    .btn-group {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        margin: 24px 0 16px;
    }
    .btn-icon, .btn-outline-icon {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 12px 24px;
        background: linear-gradient(135deg, var(--primary-gold), var(--primary-gold-light));
        color: #1a1a1a;
        font-weight: 700;
        border-radius: 60px;
        text-decoration: none;
        transition: all 0.25s ease;
        box-shadow: var(--shadow-gold);
        border: none;
        cursor: pointer;
    }
    .btn-icon:hover, .btn-outline-icon:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 18px -6px rgba(212,161,71,0.5);
        background: linear-gradient(135deg, var(--primary-gold-light), var(--primary-gold));
    }

    /* Widget & Card đồng bộ */
    .widget {
        background: linear-gradient(135deg, #1A1A1A, #242424);
        border-radius: 24px;
        padding: 22px;
        margin-bottom: 24px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.3);
        border: 1px solid rgba(212,161,71,0.2);
    }
    .widget-title {
        font-size: 1.2rem;
        font-weight: 700;
        margin-bottom: 16px;
        border-left: 5px solid var(--primary-gold);
        padding-left: 14px;
        color: var(--primary-gold);
    }
    .recent-posts, .categories {
        list-style: none;
    }
    .recent-posts li, .categories li {
        margin-bottom: 10px;
        padding-bottom: 10px;
        border-bottom: 1px solid rgba(212,161,71,0.2);
    }
    .recent-posts a, .categories a {
        text-decoration: none;
        color: var(--text-secondary);
        display: flex;
        align-items: center;
        gap: 8px;
        transition: 0.3s;
    }
    .recent-posts a:hover, .categories a:hover {
        color: var(--primary-gold);
    }
      .video-widget {
        position: relative;
        height: 250px;  
        border-radius: 16px;
        overflow: hidden;
    }
    .video-widget iframe {
        position: absolute;
        top:0; left:0;
        width:100%; height:100%;
    }
    .video-widget video {
        width: 100%;
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        border: 1px solid rgba(212,161,71,0.3);
    }

    /* Dịch vụ - service card */
    .services-section {
        margin: 70px 0 40px;
    }
    .section-title {
        text-align: center;
        font-size: 1.6rem;
        font-weight: 800;
        margin-bottom: 40px;
        color: var(--primary-gold);
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }
    .section-title::after {
        content: '';
        display: block;
        width: 70px;
        height: 3px;
        background: linear-gradient(90deg, var(--primary-gold), var(--primary-gold-light));
        margin: 12px auto 0;
    }
   .services-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 30px;
}
    .service-card {
        background: linear-gradient(135deg, #1A1A1A, #242424);
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 8px 20px rgba(0,0,0,0.3);
        border: 1px solid rgba(212,161,71,0.2);
    }
    .service-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(212,161,71,0.15);
        border-color: rgba(212,161,71,0.5);
    }
    .service-content {
        padding: 24px;
    }
    .service-title {
        font-size: 1.3rem;
        font-weight: 800;
        color: var(--primary-gold);
        margin-bottom: 12px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .service-title::before {
        content: '';
        display: block;
        width: 50px;
        height: 3px;
        background: var(--primary-gold);
        margin-bottom: 10px;
    }
    .service-description {
        color: var(--text-secondary);
        line-height: 1.6;
        font-size: 0.9rem;
    }

    /* Founder section */
    .founder-grid {
        display: grid;
        grid-template-columns: 240px minmax(0, 1fr);
        gap: 32px;
        align-items: center;
        margin-bottom: 40px;
        background: linear-gradient(135deg, #1A1A1A, #242424);
        border: 1px solid rgba(212,161,71,0.2);
        border-radius: 28px;
        padding: 28px;
        box-shadow: 0 20px 35px -12px rgba(0,0,0,0.4);
    }
    .founder-avatar {
        text-align: center;
    }
    .avatar-wrapper {
        width: 200px;
        height: 200px;
        margin: 0 auto;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-gold), var(--primary-gold-light));
        padding: 4px;
        box-shadow: 0 20px 30px -12px rgba(0,0,0,0.5);
        transition: transform 0.3s ease;
    }
    .avatar-wrapper:hover {
        transform: scale(1.02);
    }
    .founder-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid white;
        display: block;
        background: #1a1a1a;
    }
    .avatar-social {
        margin-top: 16px;
        display: flex;
        justify-content: center;
        gap: 16px;
    }
    .avatar-social a {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        background: rgba(212,161,71,0.15);
        border-radius: 50%;
        color: var(--primary-gold);
        transition: all 0.2s ease;
        text-decoration: none;
        border: 1px solid rgba(212,161,71,0.3);
    }
    .avatar-social a:hover {
        background: var(--primary-gold);
        color: #1a1a1a;
        transform: translateY(-3px);
    }
    .founder-info {
        flex: 1;
    }
    .founder-info h3 {
        font-size: 1.8rem;
        margin-bottom: 5px;
        color: var(--primary-gold);
    }
    .founder-info .title {
        font-size: 1rem;
        margin-bottom: 15px;
        color: var(--text-secondary);
        font-weight: 600;
    }
    .founder-info p {
        margin-bottom: 12px;
        line-height: 1.6;
        color: var(--text-secondary);
    }
    .founder-quote {
        font-style: italic;
        border-left: 4px solid var(--primary-gold);
        padding-left: 20px;
        margin-top: 16px;
        color: var(--text-secondary);
    }
    .founder-quote i {
        color: var(--primary-gold);
        margin-right: 8px;
    }

    /* Team section */
    .team-section {
        margin: 40px 0 60px;
    }
    .team-title {
        text-align: center;
        font-size: 1.8rem;
        font-weight: 800;
        margin-bottom: 30px;
        color: var(--primary-gold);
    }
    .team-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 25px;
    }
    .team-card {
        background: linear-gradient(135deg, #1A1A1A, #242424);
        border-radius: 24px;
        text-align: center;
        padding: 20px 12px;
        transition: 0.3s;
        box-shadow: 0 8px 20px rgba(0,0,0,0.3);
        border: 1px solid rgba(212,161,71,0.2);
    }
    .team-card:hover {
        transform: translateY(-6px);
        border-color: rgba(212,161,71,0.5);
    }
    .team-avatar-img {
        width: 90px;
        height: 90px;
        margin: 0 auto 12px;
        border-radius: 50%;
        overflow: hidden;
    }
    .team-card h4 {
        font-size: 1rem;
        margin-bottom: 4px;
        color: var(--primary-gold);
    }
    .team-card p {
        font-size: 0.85rem;
        color: var(--text-muted);
    }

    .achievements-section {
        margin: 70px 0 50px;
    }
    .achievements-title {
        text-align: center;
        font-size: 1.6rem;
        font-weight: 800;
        margin-bottom: 40px;
        color: var(--primary-gold);
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }
    .achievements-title::after {
        content: '';
        display: block;
        width: 70px;
        height: 3px;
        background: linear-gradient(90deg, var(--primary-gold), transparent);
        margin: 12px auto 0;
    }
    .achievement-row {
        margin-bottom: 35px;
    }
    .horizontal-scroll-row {
        display: flex;
        overflow-x: auto;
        gap: 20px;
        padding: 12px 0 20px;
        scrollbar-width: thin;
        scroll-behavior: smooth;
        -webkit-overflow-scrolling: touch;
    }
    .horizontal-scroll-row::-webkit-scrollbar {
        height: 6px;
    }
    .horizontal-scroll-row::-webkit-scrollbar-track {
        background: #2e2e2e;
        border-radius: 10px;
    }
    .horizontal-scroll-row::-webkit-scrollbar-thumb {
        background: var(--primary-gold);
        border-radius: 10px;
    }
    .achievement-item {
        flex: 0 0 auto;
        width: 260px;
        transition: transform 0.3s ease, box-shadow 0.3s;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 8px 20px rgba(0,0,0,0.3);
        background: #1e1e1e;
    }
    .achievement-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 28px rgba(212,161,71,0.2);
    }
    .achievement-item img {
        width: 100%;
        height: 180px;
        object-fit: cover;
        display: block;
        transition: transform 0.4s;
    }
    .achievement-item:hover img {
        transform: scale(1.02);
    }
    @media (max-width: 768px) {
        .achievement-item {
            width: 85vw;
            max-width: 320px;
        }
        .achievement-item img {
            height: 200px;
        }
        .horizontal-scroll-row {
            gap: 15px;
            padding: 8px 0 15px;
        }
    }

    /* Footer (giữ nguyên) */
    .footer {
        background: linear-gradient(180deg, #0a0a0a 0%, #1a1a1a 100%);
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
.lightbox {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.9);
    z-index: 9999;
    justify-content: center;
    align-items: center;
    cursor: pointer;
}
.lightbox img {
    max-width: 90%;
    max-height: 90%;
    object-fit: contain;
    border: 3px solid var(--primary-gold);
    border-radius: 12px;
    box-shadow: 0 0 30px rgba(0,0,0,0.5);
}
.lightbox .close-lightbox {
    position: absolute;
    top: 20px;
    right: 30px;
    font-size: 40px;
    color: white;
    cursor: pointer;
    font-weight: bold;
    transition: 0.3s;
}
.lightbox .close-lightbox:hover {
    color: var(--primary-gold);
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
    .footer-col i {
        width: 28px;
        color: var(--primary-gold);
    }
    .social-icons {
        display: flex;
        gap: 14px;
        margin-top: 18px;
    }
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
        border-color: var(--primary-gold);
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
        .row-large {
            grid-template-columns: 1fr;
        }
        .founder-grid {
            grid-template-columns: 1fr;
        }
        .section-heading h2 { font-size: 1.8rem; }
    }
    @media (max-width: 768px) {
        .btn-phone .phone-text { display: none; }
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
        .hero h1 { font-size: 2rem; }
        .section-heading h2 { font-size: 1.6rem; }
        .services-grid { grid-template-columns: 1fr; }
        .founder-grid { text-align: center; padding: 22px; }
        .founder-quote { text-align: left; }
        .main-content { padding: 22px; }
        .sidebar { position: static; }
        .avatar-wrapper { width: 150px; height: 150px; }
        .founder-avatar { flex: 0 0 auto; }
        .intro-hero { padding: 64px 0; }
        .intro-hero p { font-size: 1rem; }
    }
</style>

<!-- Hero Section -->
<section class="intro-hero">
    <div class="container">
        <h1>CHÀO MỪNG ĐẾN VỚI HUESHOW</h1>
        <p><?= htmlspecialchars($slogan) ?> – Nơi những ý tưởng sáng tạo trở thành hiện thực.</p>
    </div>
</section>

<div class="container">
    <!-- NHÀ SÁNG LẬP -->
    <div class="founder-grid">
        <div class="founder-avatar">
            <div class="avatar-wrapper">
                <img src="uploads/team/Tran_The_Phong.jpg" 
                     alt="Trần Thế Phong - Nhà sáng lập HueShow" 
                     class="founder-img"
                     loading="eager"
                     fetchpriority="high"
                     decoding="async"
                     onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?background=facc15&color=0f172a&bold=true&size=180&name=TP';">
            </div>
            <div class="avatar-social">
                <a href="https://www.facebook.com/share/183np1E25S/" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="https://zalo.me/0979663727" aria-label="Email"><i class="fas fa-comment-dots"></i></a>
            </div>
        </div>
        <div class="founder-info">
            <h3>Trần Thế Phong</h3>
            <div class="title">Nhà sáng lập & Giám đốc Điều hành</div>
            <p>Với hơn 10 năm kinh nghiệm trong lĩnh vực tổ chức sự kiện và quản lý doanh nghiệp, anh Trần Thế Phong đã sáng lập <strong><?= $company_name ?></strong> với khát vọng đưa dịch vụ team building và event Việt Nam vươn tầm khu vực. Nhờ tầm nhìn chiến lược và sự sáng tạo không ngừng, HueShow đã trở thành đối tác tin cậy của nhiều thương hiệu lớn.</p>
            <div class="founder-quote">
                <i class="fas fa-quote-left"></i>Chúng tôi không chỉ tạo ra sự kiện, chúng tôi kiến tạo cảm xúc và kết nối bền vững.<i class="fas fa-quote-right"></i>
            </div>
        </div>
    </div>

    <div class="row-large">
        <div class="main-content">
            <h2>📌 Về chúng tôi</h2>
            <p><strong><?= htmlspecialchars($company_name) ?></strong> được thành lập từ năm <?= htmlspecialchars($founded_year) ?> với sứ mệnh mang đến những chương trình Team Building, sự kiện doanh nghiệp chuyên nghiệp, sáng tạo và giàu cảm xúc. Chúng tôi tin rằng mỗi sự kiện không chỉ là nơi kết nối mà còn là cơ hội để thổi lửa đam mê và xây dựng văn hóa doanh nghiệp bền vững.</p>
            
           <div class="highlight-box">
    <i class="fas fa-quote-left"></i> Sự tin tưởng của khách hàng là giá trị tốt nhất mà chúng tôi có. <i class="fas fa-quote-right"></i>
</div>

            <p>Tập hợp những chuyên gia giàu kinh nghiệm, hội tụ đầy đủ yếu tố "Tâm - Trí - Lực". Chúng tôi không ngừng sáng tạo để biến mọi ý tưởng thành hiện thực hoàn hảo nhất. Chúng tôi đã tổ chức thành công hàng trăm sự kiện lớn nhỏ cho các tập đoàn, doanh nghiệp trong và ngoài nước.</p>

            <h2>🎯 Lĩnh vực hoạt động chính</h2>

            <ul class="intro-feature-list">
                <li><strong>Tổ chức sự kiện doanh nghiệp:</strong> Hội nghị, hội thảo, Gala dinner, khai trương, kỷ niệm.</li>
                <li><strong>Team Building:</strong> Các chương trình gắn kết nhân viên, đào tạo kỹ năng mềm, thể thao đồng đội.</li>
                <li><strong>Sản xuất Media & Truyền thông:</strong> Quay phim, chụp ảnh, thiết kế hình ảnh, video clip quảng bá.</li>
                <li><strong>Cho thuê thiết bị & Đạo cụ:</strong> Âm thanh, ánh sáng, sân khấu, dụng cụ team building.</li>

                <li><strong>Hỗ trợ nhân sự – Cộng tác viên sự kiện:</strong> Cung cấp MC chuyên nghiệp, PG, khánh tiết, lễ tân, mascot, dancer, nhân viên hậu cần, bảo vệ, kỹ thuật ánh sáng – âm thanh,… Đáp ứng mọi quy mô sự kiện.</li>
            </ul>



            <div class="intro-info-group">
                <h2>📌 Về sự kiện</h2>
                <ul class="intro-feature-list">
                    <li>HueShow chuyên tổ chức các chương trình hội nghị, lễ hội, sự kiện doanh nghiệp với quy mô từ 50 đến hàng nghìn khách mời.</li>
                </ul>
            </div>







            <div class="intro-info-group">
                <h2>📰 Hoạt động nổi bật</h2>
                <ul class="intro-feature-list">
                    <li>HUẾ SHOW TUYỂN DANCER HOẠT NÁO &amp; SHOWCASE</li>
                    <li>📢📢📢 Thông báo</li>
                    <li>DANCE CLASS – CHIÊU SINH LỚP NHẢY 0 ĐỒNG</li>
                    <li>TUYỂN DỤNG NHÂN VIÊN SỰ KIỆN / CHẠY VIỆC EVENT</li>
                </ul>
            </div>



            <div class="intro-info-group">
                <h2>🤝 Khách hàng &amp; đối tác tiêu biểu</h2>
                <ul class="intro-feature-list">
                    <li>Nha Khoa Nụ Cười Việt, Honda, Sunhouse, Panasonic, VPBank, Agribank, Tetra Pak, Boehringer...</li>
                </ul>
            </div>

            <div class="btn-group">
                <a href="contact.php" class="btn-icon"><i class="fas fa-calendar-check"></i> Nhận tư vấn ngay</a>
                <a href="teambuilding.php" class="btn-outline-icon"><i class="fas fa-users"></i> Xem các chương trình</a>
                <a href="tel:0989898989" class="btn-icon"><i class="fas fa-phone-alt"></i> Gọi hotline</a>
            </div>

            <p style="margin-top: 12px; font-style: italic;">Hãy để <?= htmlspecialchars($company_name) ?> đồng hành cùng bạn trong những sự kiện quan trọng nhất!</p>
        </div>

        <div class="sidebar">
            <div class="widget">
                <div class="widget-title">🤝 Giải pháp trọn gói</div>
                <p><strong>𝓗𝓤𝓔 𝓢𝓗𝓞𝓦 – GIẢI PHÁP TRỌN GÓI CHO MỌI SỰ KIỆN 🎯</strong></p>
                <p>Bạn đang không biết tìm một đơn vị tổ chức sự kiện chuyên nghiệp – sáng tạo – tối ưu chi phí?<br>✨ Đừng lo lắng, đã có 𝓗𝓤𝓔 𝓢𝓗𝓞𝓦!!!</p>
                <p>💪 Nhằm mang đến giải pháp giúp bạn biến ý tưởng thành những trải nghiệm thực tế ấn tượng với những dịch vụ nổi bật:<br>
                ⭕ <strong>Event</strong> – Tổ chức sự kiện trọn gói (Khai trương, Lễ ra mắt, hội nghị, Gala, lửa trại,...)<br>
                ⭕ <strong>Media</strong> – Sản xuất hình ảnh & video (Quay phim, chụp ảnh, Video highlight, TVC…)<br>
                ⭕ <strong>Teambuilding</strong> – Kết nối đội ngũ (Kịch bản sáng tạo, hoạt động hấp dẫn)<br>
                ⭕ <strong>Support</strong> – Nhân sự & thiết bị (MC, PG, dancer, mascot, âm thanh, ánh sáng, sân khấu,…)<br>
                ⭕ Ngoài ra, còn đa dạng các dịch vụ khác theo yêu cầu.</p>
                <p>💡 Vậy còn ngần ngại gì mà không chọn 𝓗𝓤𝓔 𝓢𝓗𝓞𝓦?<br>Bạn chỉ cần đưa ra ý tưởng – 𝓗𝓤𝓔 𝓢𝓗𝓞𝓦 sẽ giúp bạn hoàn thiện mọi thứ một cách trọn vẹn.</p>
            </div>

            <div class="widget">
                <div class="widget-title">📌 Về chúng tôi</div>
                <p><?= htmlspecialchars($company_name) ?> – Đơn vị tổ chức team building và sự kiện hàng đầu, mang đến trải nghiệm độc đáo và chuyên nghiệp.</p>
            </div>
            <div class="widget">
                <div class="widget-title">📰 Bài viết mới</div>
                <ul class="recent-posts">
                    <?php if (!empty($recentPosts)): ?>
                        <?php foreach ($recentPosts as $post): ?>
                            <li><a href="articles.php?id=<?= $post['id'] ?>"><i class="fas fa-angle-right"></i> <?= htmlspecialchars($post['title']) ?></a></li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li>Chưa có bài viết nào.</li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="widget video-widget">
                <video controls muted preload="metadata" playsinline>
                    <source src="uploads/video/first.mp4" type="video/mp4">
                    Trình duyệt của bạn không hỗ trợ video HTML5.
                </video>
            </div>
        </div>
    </div>

    <div class="achievements-section">
<h2 class="achievements-title"><i class="fas fa-trophy"></i> HÀNH TRÌNH VÀ THÀNH TỰU ĐẠT ĐƯỢC</h2>  
      <?php
        $totalAchievements = 14;
        $imagesPerRow = [5, 5, 4]; // 5 + 5 + 4 = 14
        $counter = 1;
        foreach ($imagesPerRow as $rowIdx => $count):
        ?>
            <div class="achievement-row">
                <div class="horizontal-scroll-row">
                    <?php for ($i = 0; $i < $count; $i++): 
                        $imageNumber = $counter++;
                        $imagePath = "uploads/achievements/achievements{$imageNumber}.png";
                       
                        if (!file_exists($imagePath)) $imagePath = "https://placehold.co/400x300/242424/D4A147?text=Thanh+tuu+{$imageNumber}";
                    ?>
                    <div class="achievement-item">
                        <img src="<?= htmlspecialchars($imagePath) ?>" alt="Thành tựu <?= $imageNumber ?>" loading="lazy" decoding="async">
                    </div>
                    <?php endfor; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="services-section">
        <h2 class="section-title">Dịch vụ nổi bật</h2>
        <div class="services-grid">
            <div class="service-card">
                <div class="service-content">
                    <h3 class="service-title">Tư vấn & Tổ chức sự kiện</h3>
                    <p class="service-description">Lên ý tưởng sáng tạo, lập kế hoạch chi tiết và triển khai các sự kiện chuyên nghiệp với quy mô đa dạng, đáp ứng mọi yêu cầu.</p>
                </div>
            </div>
            <div class="service-card">
                <div class="service-content">
                    <h3 class="service-title">Sáng tạo Media</h3>
                    <p class="service-description">Sản xuất hình ảnh, video chất lượng cao, mang đậm tính nghệ thuật, phục vụ hiệu quả cho chiến dịch truyền thông và quảng bá thương hiệu.</p>
                </div>
            </div>
            <div class="service-card">
                <div class="service-content">
                    <h3 class="service-title">Teambuilding</h3>
                    <p class="service-description">Thiết kế và tổ chức các chương trình hoạt động tập thể độc đáo, giúp gắn kết nhân sự, khơi dậy năng lượng và tinh thần đồng đội.</p>
                </div>
            </div>
            <div class="service-card">
                <div class="service-content">
                    <h3 class="service-title">Cung cấp nhân sự & Đào tạo</h3>
                    <p class="service-description">
                        <strong>📌 Đội ngũ hỗ trợ đa dạng:</strong> MC, PG, khánh tiết, lễ tân, mascot, dancer, nhân viên hậu cần, kỹ thuật viên âm thanh – ánh sáng.<br>
                        <strong>🎓 Đào tạo:</strong> Kỹ năng tổ chức sự kiện, kỹ năng làm việc nhóm, giao tiếp khách hàng.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.getElementById('navToggle');
    const navList   = document.getElementById('navList');

    if (toggleBtn && navList) {

        function openMenu() {
            navList.classList.add('show');
            document.body.classList.add('menu-open');
            toggleBtn.querySelector('i').className = 'fas fa-times';
        }

        function closeMenu() {
            navList.classList.remove('show');
            document.body.classList.remove('menu-open');
            toggleBtn.querySelector('i').className = 'fas fa-bars';
        }

        toggleBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            navList.classList.contains('show') ? closeMenu() : openMenu();
        });

        document.addEventListener('click', function (e) {
            if (navList.classList.contains('show') &&
                !navList.contains(e.target) &&
                !toggleBtn.contains(e.target)) {
                closeMenu();
            }
        });

        navList.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', function () {
                closeMenu();
            });
        });

    }

    const header = document.querySelector('.main-header');
    if (header) {
        header.style.transform = 'none';
    }

    document.querySelectorAll('.horizontal-scroll-row').forEach(row => {
        let isDown = false;
        let startX;
        let scrollLeft;
        row.addEventListener('mousedown', (e) => {
            isDown = true;
            startX = e.pageX - row.offsetLeft;
            scrollLeft = row.scrollLeft;
        });
        row.addEventListener('mouseleave', () => { isDown = false; });
        row.addEventListener('mouseup', () => { isDown = false; });
        row.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - row.offsetLeft;
            const walk = (x - startX) * 1.5;
            row.scrollLeft = scrollLeft - walk;
        });
    });
});

const lightbox = document.createElement('div');
lightbox.className = 'lightbox';
lightbox.innerHTML = `
    <span class="close-lightbox">&times;</span>
    <img src="" alt="Phóng to">
`;
document.body.appendChild(lightbox);

const lightboxImg = lightbox.querySelector('img');
const closeBtn = lightbox.querySelector('.close-lightbox');

document.querySelectorAll('.achievement-item img').forEach(img => {
    img.addEventListener('click', (e) => {
        e.stopPropagation();
        lightboxImg.src = img.src;
        lightbox.style.display = 'flex';
    });
});

function closeLightbox() {
    lightbox.style.display = 'none';
    lightboxImg.src = '';
}
lightbox.addEventListener('click', closeLightbox);
closeBtn.addEventListener('click', (e) => {
    e.stopPropagation();
    closeLightbox();
});
</script>

<?php include 'includes/fe/footer.php'; ?>