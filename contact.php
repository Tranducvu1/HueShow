<?php
// contact.php - Trang liên hệ với form, bản đồ và link chỉ đường
require_once 'config.php';
$page_title = 'Liên hệ';
include 'includes/fe/header.php';

$success = false;
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    if (empty($name) || empty($email) || empty($message)) {
        $error = 'Vui lòng điền đầy đủ thông tin bắt buộc.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email không hợp lệ.';
    } else {
        $success = true;
    }
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

        .hero-contact {
            background: linear-gradient(120deg, #0f172a, #1a1a2e);
            padding: 60px 0;
            text-align: center;
            margin-bottom: 48px;
            border-bottom: 2px solid var(--gold);
        }
        .hero-contact h1 {
            font-size: 1.6rem;
            font-weight: 800;
            margin-bottom: 16px;
            color: var(--gold);
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            font-family: 'Times New Roman', sans-serif;
        }
        .hero-contact p {
            font-size: 1.2rem;
            color: var(--text-secondary);
            max-width: 700px;
            margin: 0 auto;
            font-family: 'Times New Roman', sans-serif;
        }


        /* ==================== LAYOUT 2 CỘT ==================== */
        .two-columns {
            display: grid;
            grid-template-columns: minmax(0, 1.45fr) minmax(300px, 0.9fr);
            gap: 32px;
            margin: 40px 0 60px;
            align-items: start;
        }
        .main-col {
            min-width: 0;
        }
        .side-col {
            min-width: 0;
            position: sticky;
            top: 100px;
            align-self: start;
        }

        /* ==================== FORM CONTAINER ==================== */
        .contact-form-container {
            background: linear-gradient(135deg, #1A1A1A, #242424);
            border-radius: 28px;
            padding: 34px;
            box-shadow: 0 20px 35px -12px rgba(0,0,0,0.35);
            border: 1px solid rgba(212,161,71,0.2);
        }
        .contact-form-container h3 {
            font-size: 1.5rem;
            margin-bottom: 20px;
            color: var(--gold);
            border-left: 5px solid var(--gold);
            padding-left: 15px;
            font-family: 'Times New Roman', sans-serif;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 14px 18px;
            border: 1px solid rgba(212,161,71,0.3);
            border-radius: 20px;
            background: var(--dark-bg-tertiary);
            color: var(--text-primary);
            font-family: 'Times New Roman', sans-serif;
            font-size: 0.95rem;
            transition: var(--transition);
        }
        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--gold);
            box-shadow: 0 0 0 3px rgba(212,161,71,0.2);
        }
        .submit-btn {
            background: linear-gradient(135deg, var(--gold), #E5B563);
            color: #1a1a1a;
            font-weight: bold;
            padding: 14px 32px;
            border: none;
            border-radius: 40px;
            cursor: pointer;
            font-size: 1rem;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: var(--shadow-gold);
            font-family: 'Times New Roman', sans-serif;
        }
        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(212,161,71,0.4);
            background: linear-gradient(135deg, #E5B563, var(--gold));
        }
        .submit-btn:active {
            transform: scale(0.96);
        }
        .alert {
            padding: 12px 20px;
            border-radius: 20px;
            margin-bottom: 20px;
            font-family: 'Times New Roman', sans-serif;
        }
        .alert-success {
            background: rgba(34,197,94,0.15);
            color: #86efac;
            border-left: 4px solid #22c55e;
        }
        .alert-error {
            background: rgba(239,68,68,0.15);
            color: #fca5a5;
            border-left: 4px solid #ef4444;
        }

        /* ==================== INFO CARD & WIDGET ==================== */
        .info-card, .widget {
            background: linear-gradient(135deg, #1A1A1A, #242424);
            border-radius: 24px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: var(--shadow-md);
            border: 1px solid rgba(212,161,71,0.2);
        }
        .contact-highlight-list {
            list-style: none;
            display: grid;
            gap: 12px;
            margin: 0;
            padding: 0;
        }
        .contact-highlight-list li {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            color: var(--text-secondary);
            line-height: 1.65;
        }
        .contact-highlight-list i {
            color: var(--gold);
            margin-top: 4px;
            flex-shrink: 0;
        }
        .info-card h4, .widget-title {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 16px;
            border-left: 5px solid var(--gold);
            padding-left: 14px;
            color: var(--gold);
            font-family: 'Times New Roman', sans-serif;
        }
        .info-item {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 16px;
            color: var(--text-secondary);
            font-family: 'Times New Roman', sans-serif;
        }
        .info-item i {
            width: 32px;
            color: var(--gold);
            font-size: 1.2rem;
        }
        .info-item a, .info-item span {
            color: var(--text-secondary);
            text-decoration: none;
            transition: var(--transition);
        }
        .info-item a:hover {
            color: var(--gold);
            text-decoration: underline;
        }
        .map-container {
            border-radius: 20px;
            overflow: hidden;
            margin-top: 20px;
            border: 1px solid rgba(212,161,71,0.3);
        }
        .map-container iframe {
            width: 100%;
            height: 250px;
            border: 0;
        }

        /* Widget components */
        .recent-posts {
            list-style: none;
        }
        .recent-posts li {
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px solid rgba(212,161,71,0.2);
        }
        .recent-posts a {
            text-decoration: none;
            color: var(--text-secondary);
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: var(--transition);
            font-family: 'Times New Roman', sans-serif;
        }
        .recent-posts a:hover {
            color: var(--gold);
        }
        .recent-posts a i {
            color: var(--gold);
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
        .widget ul {
            list-style: none;
        }
        .widget ul li {
            margin-bottom: 10px;
        }
        .widget ul li a {
            display: block;
            color: var(--text-secondary);
            text-decoration: none;
            transition: var(--transition);
            font-family: 'Times New Roman', sans-serif;
        }
        .widget ul li a:hover {
            color: var(--gold);
        }
        .widget ul li a i {
            margin-right: 8px;
            color: var(--gold);
        }

        /* ==================== HOTLINE FIXED ==================== */
        .hotline-fixed {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: linear-gradient(135deg, var(--gold), #B8862F);
            color: #1a1a1a;
            padding: 12px 24px;
            border-radius: 60px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: bold;
            text-decoration: none;
            z-index: 100;
            box-shadow: var(--shadow-gold);
            transition: var(--transition);
            font-family: 'Times New Roman', sans-serif;
        }
        .hotline-fixed:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 28px rgba(212,161,71,0.5);
            color: #1a1a1a;
        }

        /* ==================== RESPONSIVE ==================== */
        @media (max-width: 992px) {
            .two-columns {
                grid-template-columns: 1fr;
            }
            .side-col {
                position: static;
            }
            .hero-contact h1 {
                font-size: 2rem;
            }
        }
        @media (max-width: 768px) {
            .contact-form-container,
            .info-card, .widget {
                padding: 22px;
            }
            .hero-contact h1 {
                font-size: 1.8rem;
            }
        }
        @media (max-width: 576px) {
            .hero-contact h1 {
                font-size: 1.5rem;
            }
            .hero-contact p {
                font-size: 0.95rem;
            }
        }
    </style>

<section class="hero-contact">
    <div class="container">
        <h1>📞 LIÊN HỆ VỚI CHÚNG TÔI</h1>
        <p>Hãy để lại thông tin, đội ngũ HueShow sẽ tư vấn giải pháp sự kiện và team building phù hợp nhất cho bạn.</p>
    </div>
</section>

<div class="container">
    <div class="two-columns">
        <div class="main-col">
            <div class="contact-form-container">
                <h3>✉️ Gửi yêu cầu tư vấn</h3>
                <?php if ($success): ?>
                    <div class="alert alert-success">Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi trong thời gian sớm nhất.</div>
                <?php elseif ($error): ?>
                    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <form method="POST" action="">
                    <div class="form-group">
                        <input type="text" name="name" placeholder="Họ và tên *" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <input type="email" name="email" placeholder="Email *" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <input type="tel" name="phone" placeholder="Số điện thoại" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <textarea name="message" rows="5" placeholder="Nội dung cần tư vấn *" required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="submit-btn"><i class="fas fa-paper-plane"></i> Gửi liên hệ</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="side-col">
            <!-- Thông tin liên hệ + map -->
            <div class="info-card">
                <h4><i class="fas fa-map-pin"></i> Thông tin HueShow</h4>
                <div class="info-item">
                    <i class="fas fa-building"></i>
                    <a href="https://www.google.com/maps/dir/?api=1&destination=26+Nguy%E1%BB%85n+C%C3%B4ng+Tr%E1%BB%A9,+Th%C3%A0nh+ph%E1%BB%91+Hu%E1%BA%BF" target="_blank" rel="noopener noreferrer">
                        26 Nguyễn Công Trứ, Thành Phố Huế
                    </a>
                </div>
                <div class="info-item">
                    <i class="fas fa-phone-alt"></i>
                    <a href="tel:0989898989">097 966 37 27</a>
                </div>
                <div class="info-item">
                    <i class="fas fa-envelope"></i>
                    <a href="mailto:sukienhueshow@gmail.com">sukienhueshow@gmail.com</a>
                </div>
                <div class="info-item">
                    <i class="fas fa-clock"></i>
                    <span>Thứ 2 – Thứ 7: 8:00 - 18:00</span>
                </div>
                <div class="map-container">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1916.217476395723!2d107.590638017239!3d16.46393118850395!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3141a12f2f5f5f5f%3A0x0!2zMjYgTmd1eeG7hW4gQ8O0bmcgVHLDuiwgVHAuIEh14bq_LCBUaMOgbmggcGjhu5EgSOG7hywgVmnhu4d0IE5hbQ!5e0!3m2!1svi!2s!4v1700000000000!5m2!1svi!2s" allowfullscreen loading="lazy"></iframe>
                </div>
            </div>
            </div>
    </div>
</div>

<?php include 'includes/fe/footer.php'; ?>

<a href="tel:0979663727" class="hotline-fixed"><i class="fas fa-phone-alt"></i> Hotline: 097 966 37 27</a>

<script>
    document.querySelector('.submit-btn')?.addEventListener('click', function() {
        this.style.transform = 'scale(0.96)';
        setTimeout(() => { this.style.transform = ''; }, 100);
    });
</script>
