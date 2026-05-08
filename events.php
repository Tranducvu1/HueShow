<?php
// events.php - Danh sách sự kiện đã tổ chức
require_once 'config.php';

// Định nghĩa hàm fixImagePath nếu chưa có (để dùng ngay)
if (!function_exists('fixImagePath')) {
    function fixImagePath($path) {
        if (empty($path)) return '';
        if (preg_match('#^(https?:)?//#i', $path)) return $path;
        if (strpos($path, 'uploads/') === 0) return $path;
        if (strpos($path, 'uploads/') === 0) return '../' . $path;
        return $path;
    }
}

$page_title = 'Sự kiện';

$projectCategories = [
    'entertainment-game-show' => 'ENTERTAINMENT GAME SHOW',
    'dance-festival' => 'DANCE FESTIVAL',
    'teambuilding-du-an' => 'TEAMBUILDING',
    'fashion-show' => 'FASHION SHOW',
    'chuong-trinh-yep' => 'CHƯƠNG TRÌNH YEP',
];

$selectedProject = isset($_GET['project']) ? trim($_GET['project']) : '';
$selectedYear = isset($_GET['year']) ? trim($_GET['year']) : '';
$searchKeyword = isset($_GET['search']) ? trim($_GET['search']) : '';

if (function_exists('getLatestEvents')) {
    $events = getLatestEvents(24);
} else {
    $events = getAllEvents();
}

foreach ($events as &$event) {
    $haystack = mb_strtolower(($event['title'] ?? '') . ' ' . ($event['description'] ?? ''), 'UTF-8');
    $event['project_category'] = 'entertainment-game-show';

    if (str_contains($haystack, 'dance') || str_contains($haystack, 'festival') || str_contains($haystack, 'nhảy')) {
        $event['project_category'] = 'dance-festival';
    } elseif (str_contains($haystack, 'team') || str_contains($haystack, 'building')) {
        $event['project_category'] = 'teambuilding-du-an';
    } elseif (str_contains($haystack, 'fashion') || str_contains($haystack, 'thời trang')) {
        $event['project_category'] = 'fashion-show';
    } elseif (str_contains($haystack, 'yep') || str_contains($haystack, 'year end') || str_contains($haystack, 'cuối năm')) {
        $event['project_category'] = 'chuong-trinh-yep';
    }
}
unset($event);

$availableYears = [];
foreach ($events as $event) {
    $dateSource = !empty($event['event_date']) ? $event['event_date'] : ($event['created_at'] ?? '');
    if (!empty($dateSource)) {
        $year = date('Y', strtotime($dateSource));
        if ($year) $availableYears[] = $year;
    }
}
$availableYears = array_values(array_unique($availableYears));
rsort($availableYears);

$events = array_values(array_filter($events, function ($event) use ($selectedProject, $selectedYear, $searchKeyword) {
    $matchesProject = true;
    $matchesYear = true;
    $matchesSearch = true;

    if ($selectedProject !== '') {
        $matchesProject = ($event['project_category'] ?? '') === $selectedProject;
    }

    if ($selectedYear !== '') {
        $dateSource = !empty($event['event_date']) ? $event['event_date'] : ($event['created_at'] ?? '');
        $matchesYear = !empty($dateSource) && date('Y', strtotime($dateSource)) === $selectedYear;
    }

    if ($searchKeyword !== '') {
        $haystack = mb_strtolower(($event['title'] ?? '') . ' ' . ($event['description'] ?? ''), 'UTF-8');
        $needle = mb_strtolower($searchKeyword, 'UTF-8');
        $matchesSearch = str_contains($haystack, $needle);
    }

    return $matchesProject && $matchesYear && $matchesSearch;
}));

if ($selectedProject !== '' && isset($projectCategories[$selectedProject])) {
    $page_title = $projectCategories[$selectedProject];
}

$recentPosts = getLatestArticles(5);

include 'includes/fe/header.php';
?>

<style>
/* ==================== DARK THEME VARIABLES ==================== */
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
    color: #1a1a1a;
}

.hero-event {
    background: linear-gradient(120deg, #0f172a, #1a1a2e);
    padding: 80px 0;
    text-align: center;
        font-family: 'Times New Roman', sans-serif;
    margin-bottom: 48px;
    border-bottom: 2px solid var(--primary-gold);
}
.hero-event h1 {
    font-size: 1.6rem;
    font-weight: 800;
    margin-bottom: 16px;
    color: var(--primary-gold);
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
}
.hero-event p {
    font-size: 1.2rem;
    color: var(--text-secondary);
    max-width: 700px;
    margin: 0 auto;
}

/* ==================== LAYOUT 2 CỘT ==================== */
.two-columns {
    display: flex;
    gap: 40px;
    margin-bottom: 60px;
}
.main-col { flex: 2; }
.side-col { flex: 1; }

/* ==================== FILTER FORM ==================== */
.filter-form {
    display: grid;
    grid-template-columns: minmax(0, 1.5fr) minmax(0, 1.1fr) minmax(0, 0.8fr) auto;
    gap: 16px;
    align-items: end;
    background: linear-gradient(135deg,#1A1A1A,#242424);
    padding: 22px;
    border-radius: 24px;
    border: 1px solid rgba(212,161,71,0.2);
    box-shadow: var(--shadow-md);
}
.filter-field label {
    display: block;
    margin-bottom: 8px;
    color: var(--primary-gold);
    font-weight: 700;
    font-size: 0.95rem;
}
.filter-field input,
.filter-field select {
    width: 100%;
    min-height: 52px;
    padding: 14px 16px;
    border-radius: 14px;
    border: 1px solid rgba(212,161,71,0.25);
    background: #2a2a2a;
    color: var(--text-primary);
    outline: none;
}
.filter-field input:focus,
.filter-field select:focus {
    border-color: var(--primary-gold);
    box-shadow: 0 0 0 3px rgba(212,161,71,0.12);
}
.filter-actions {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}
.filter-actions button,
.filter-actions a {
    min-height: 52px;
    padding: 14px 18px;
    border-radius: 14px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    text-decoration: none;
}
.filter-actions button {
    border: none;
    background: linear-gradient(135deg, var(--primary-gold), var(--primary-gold-light));
    color: #1a1a1a;
    cursor: pointer;
}
.filter-actions a {
    border: 1px solid rgba(212,161,71,0.25);
    color: var(--text-primary);
    background: #2a2a2a;
}

/* ==================== GRID CARDS ==================== */
.grid-cards {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 32px;
    margin-bottom: 40px;
}

.event-card {
    background: linear-gradient(135deg, #1A1A1A, #242424);
    border-radius: 24px;
    overflow: hidden;
    transition: var(--transition);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    border: 1px solid rgba(212, 161, 71, 0.2);
    text-decoration: none;
    display: flex;
    flex-direction: column;
    height: 100%;
    color: inherit;
}
.event-card:hover {
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
    overflow: hidden;
}
.card-img img {
    width: 100%; height: 100%;
    object-fit: cover;
    transition: transform 0.5s;
}
.event-card:hover .card-img img { transform: scale(1.05); }
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

/* ==================== SIDEBAR WIDGETS ==================== */
.widget {
    background: linear-gradient(135deg, #1A1A1A, #242424);
    border-radius: 24px;
    padding: 20px;
    margin-bottom: 24px;
    box-shadow: var(--shadow-md);
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
.widget p, .widget div {
    font-size: 0.95rem;
    line-height: 1.5;
    color: var(--text-secondary);
}
.recent-posts {
    list-style: none;
}
.recent-posts li {
    margin-bottom: 10px;
    padding-bottom: 10px;
    border-bottom: 1px solid rgba(212,161,71,0.2);
}
.recent-posts a {
    text-decoration: none;
    color: var(--text-secondary);
    display: flex;
    align-items: center;
    gap: 8px;
    transition: 0.3s;
    font-size: 0.9rem;
}
.recent-posts a:hover {
    color: var(--primary-gold);
}
.recent-posts a i {
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


/* ==================== FOOTER ==================== */
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
    .filter-form {
        grid-template-columns: 1fr 1fr;
    }
    .filter-field-search {
        grid-column: 1 / -1;
    }
    .filter-actions {
        grid-column: 1 / -1;
    }
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
    .hero-event h1 { font-size: 2rem; }
    .grid-cards { grid-template-columns: 1fr; gap: 24px; }
    .widget { margin-bottom: 20px; }
    .filter-form {
        grid-template-columns: 1fr;
        padding: 18px;
        gap: 14px;
    }
    .filter-field-search,
    .filter-actions {
        grid-column: auto;
    }
    .filter-actions {
        grid-template-columns: 1fr;
    }
}
@media (max-width: 576px) {
    .hero-event h1 { font-size: 1.6rem; }
    .hero-event p { font-size: 1rem; }
    .filter-field label {
        font-size: 0.9rem;
    }
    .filter-field input,
    .filter-field select,
    .filter-actions button,
    .filter-actions a {
        min-height: 48px;
        padding: 12px 14px;
        font-size: 0.95rem;
    }
}
</style>

<section class="hero-event">
    <div class="container">
        <h1>🎉 <?= htmlspecialchars($selectedProject !== '' && isset($projectCategories[$selectedProject]) ? $projectCategories[$selectedProject] : 'DỰ ÁN TIÊU BIỂU') ?></h1>
        <p>Chúng tôi tự hào là đơn vị tổ chức hàng trăm sự kiện lớn nhỏ – từ hội nghị, lễ hội đến các chương trình tri ân khách hàng.</p>
    </div>
</section>

<div class="container" style="margin-bottom: 30px;">
    <form method="GET" action="events.php" class="filter-form">
        <div class="filter-field filter-field-search">
            <label for="searchFilter">Tìm kiếm dự án</label>
            <input id="searchFilter" type="text" name="search" value="<?= htmlspecialchars($searchKeyword) ?>" placeholder="Nhập tên dự án hoặc từ khóa...">
        </div>
        <div class="filter-field">
            <label for="projectFilter">Lọc theo dự án</label>
            <select id="projectFilter" name="project">
                <option value="">Tất cả dự án</option>
                <?php foreach ($projectCategories as $slug => $label): ?>
                    <option value="<?= htmlspecialchars($slug) ?>" <?= $selectedProject === $slug ? 'selected' : '' ?>><?= htmlspecialchars($label) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="filter-field">
            <label for="yearFilter">Chọn năm</label>
            <select id="yearFilter" name="year">
                <option value="">Tất cả năm</option>
                <?php foreach ($availableYears as $year): ?>
                    <option value="<?= htmlspecialchars($year) ?>" <?= $selectedYear === (string)$year ? 'selected' : '' ?>><?= htmlspecialchars($year) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="filter-actions">
            <button type="submit">Lọc</button>
            <a href="events.php">Reset</a>
        </div>
    </form>
</div>

<div class="container">
    <div class="grid-cards" style="grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); margin-bottom: 60px;">
        <?php if (!empty($events)): ?>
            <?php foreach ($events as $event): ?>
            <a href="event_detail.php?id=<?= $event['id'] ?>" class="event-card">
                <div class="card-img">
                    <?php if (!empty($event['image'])): ?>
                        <img src="<?= htmlspecialchars(fixImagePath($event['image'])) ?>" alt="<?= htmlspecialchars($event['title']) ?>">
                    <?php else: ?>
                        <div style="background: #2a2a2a; width:100%; height:100%; display:flex; align-items:center; justify-content:center; color:var(--primary-gold); font-size:2rem;">🎪</div>
                    <?php endif; ?>
                </div>
                <div class="card-content">
                    <div style="display:flex; align-items:center; justify-content:space-between; gap:10px; margin-bottom:10px; flex-wrap:wrap;">
                        <span style="display:inline-flex; align-items:center; padding:6px 12px; border-radius:999px; background:rgba(212,161,71,0.12); color:var(--primary-gold); font-size:0.78rem; font-weight:800; text-transform:uppercase;">
                            <?= htmlspecialchars($projectCategories[$event['project_category']] ?? 'Dự án') ?>
                        </span>
                        <span class="card-date" style="margin-bottom:0;"><i class="far fa-calendar-alt"></i> <?= date('d/m/Y', strtotime(!empty($event['event_date']) ? $event['event_date'] : $event['created_at'])) ?></span>
                    </div>
                    <h3 class="card-title"><?= htmlspecialchars($event['title']) ?></h3>
                    <p class="card-desc"><?= cleanText(substr($event['description'] ?? '', 0, 140)) ?>...</p>
                    <span class="read-more" style="margin-top:16px;">Xem chi tiết <i class="fas fa-arrow-right"></i></span>
                </div>
            </a>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="grid-column:1 / -1; background:linear-gradient(135deg,#1A1A1A,#242424); border:1px solid rgba(212,161,71,0.2); border-radius:24px; padding:40px 24px; text-align:center; color:var(--text-secondary);">
                Không tìm thấy dự án phù hợp với bộ lọc đã chọn.
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/fe/footer.php'; ?>