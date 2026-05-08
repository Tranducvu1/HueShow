<?php
require_once 'config.php';

if (!function_exists('fixImagePath')) {
    function fixImagePath($path) {
        if (empty($path)) return '';
        if (preg_match('#^(https?:)?//#i', $path)) return $path;
        if (strpos($path, 'uploads/') === 0) return $path;
        return $path;
    }
}

$serviceSlug = isset($_GET['slug']) ? trim($_GET['slug']) : '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$servicePresetMap = [
    'market-setup-san-khau' => [
        'title' => 'MARKET & SETUP SÂN KHẤU',
        'description' => 'HueShow cung cấp dịch vụ market & setup sân khấu chuyên nghiệp cho hội nghị, lễ khai trương, activation, roadshow và các chương trình biểu diễn quy mô lớn. Chúng tôi đảm nhận từ khảo sát mặt bằng, lên phối cảnh, dựng sân khấu, backdrop, booth, cổng chào đến âm thanh, ánh sáng và các hạng mục trang trí đồng bộ.',
        'image' => 'uploads/logo/logo1.jpg',
        'created_at' => date('Y-m-d H:i:s'),
        'event_date' => date('Y-m-d'),
        'featured' => 1,
        'status' => 'published'
    ],
    'ho-tro-nhan-su' => [
        'title' => 'HỖ TRỢ NHÂN SỰ - MC, CA SĨ, PG, MASCOT “CÂY NHÀ...”',
        'description' => 'Dịch vụ nhân sự sự kiện của HueShow mang đến đội ngũ MC, ca sĩ, dancer, PG, PB, mascot và nhân sự hỗ trợ vận hành phù hợp từng chương trình. Chúng tôi chú trọng hình ảnh, tác phong, khả năng giao tiếp và sự chuyên nghiệp để tạo nên trải nghiệm tốt nhất cho khách mời và thương hiệu.',
        'image' => 'uploads/logo/logo2.jpg',
        'created_at' => date('Y-m-d H:i:s'),
        'event_date' => date('Y-m-d'),
        'featured' => 1,
        'status' => 'published'
    ],
    'ho-tro-kich-ban-tron-goi' => [
        'title' => 'HỖ TRỢ LÊN KỊCH BẢN & HOÀN THIỆN TRỌN GÓI SỰ KIỆN',
        'description' => 'HueShow hỗ trợ xây dựng concept, flow chương trình, timeline, lời dẫn MC, cue vận hành và kịch bản tổng thể cho sự kiện. Từ ý tưởng ban đầu đến bản vận hành chi tiết, chúng tôi giúp doanh nghiệp tối ưu nội dung, cảm xúc và hiệu quả truyền thông trong từng chương trình.',
        'image' => 'uploads/logo/logo3.jpg',
        'created_at' => date('Y-m-d H:i:s'),
        'event_date' => date('Y-m-d'),
        'featured' => 1,
        'status' => 'published'
    ],
    'cung-cap-dancer-vu-doan' => [
        'title' => 'CUNG CẤP DANCER, VŨ ĐOÀN - THỔI HỒN VÀO TỪNG BƯỚC',
        'description' => 'Dịch vụ dancer và vũ đoàn chuyên nghiệp giúp sân khấu trở nên bùng nổ và giàu cảm xúc hơn. HueShow cung cấp các tiết mục đa dạng từ opening dance, flashmob, biểu diễn chủ đề đến minh họa cho ca sĩ, góp phần nâng tầm hình ảnh chương trình và tạo điểm nhấn mạnh mẽ.',
        'image' => 'uploads/logo/logo4.jpg',
        'created_at' => date('Y-m-d H:i:s'),
        'event_date' => date('Y-m-d'),
        'featured' => 1,
        'status' => 'published'
    ],
    'to-chuc-khai-truong' => [
        'title' => 'DỊCH VỤ TỔ CHỨC KHAI TRƯƠNG CHUYÊN NGHIỆP',
        'description' => 'Tổ chức khai trương là một trong những dịch vụ thế mạnh của HueShow. Chúng tôi triển khai trọn gói từ nghi thức cắt băng, múa lân, sân khấu, truyền thông đến trải nghiệm khách mời nhằm tạo ấn tượng ban đầu mạnh mẽ, góp phần lan tỏa hình ảnh thương hiệu ngay từ ngày đầu ra mắt.',
        'image' => 'uploads/logo/logo5.jpg',
        'created_at' => date('Y-m-d H:i:s'),
        'event_date' => date('Y-m-d'),
        'featured' => 1,
        'status' => 'published'
    ],
    'dem-nhac-nang-tho' => [
        'title' => 'ĐÊM NHẠC NÀNG THƠ CÙNG ALTIS BAND',
        'description' => 'HueShow đồng hành tổ chức các đêm nhạc theo chủ đề với định hướng giàu cảm xúc và dấu ấn nghệ thuật. Từ setup sân khấu, ánh sáng, âm thanh đến vận hành biểu diễn, chúng tôi mang lại không gian chỉn chu, lãng mạn và phù hợp với tinh thần thương hiệu hoặc chương trình nghệ thuật.',
        'image' => 'uploads/logo/logo6.jpg',
        'created_at' => date('Y-m-d H:i:s'),
        'event_date' => date('Y-m-d'),
        'featured' => 1,
        'status' => 'published'
    ],
    'le-ra-quan-bat-dong-san' => [
        'title' => 'TỔ CHỨC LỄ RA QUÂN DỰ ÁN BẤT ĐỘNG SẢN',
        'description' => 'Dành cho các chủ đầu tư và đơn vị phân phối, HueShow triển khai lễ ra quân bất động sản với concept hiện đại, khí thế và đồng bộ nhận diện thương hiệu. Chúng tôi chú trọng hiệu ứng sân khấu, nội dung truyền lửa và trải nghiệm đội ngũ bán hàng để tạo đà bứt phá cho chiến dịch.',
        'image' => 'uploads/logo/logo7.jpg',
        'created_at' => date('Y-m-d H:i:s'),
        'event_date' => date('Y-m-d'),
        'featured' => 1,
        'status' => 'published'
    ],
    'le-ky-niem-20-nam' => [
        'title' => 'TỔ CHỨC LỄ KỶ NIỆM 20 NĂM – SỰ KIỆN TRƯỜNG ĐẠI HỌC',
        'description' => 'HueShow tổ chức các lễ kỷ niệm, anniversary và sự kiện dấu mốc với quy mô trang trọng, cảm xúc và giàu giá trị kết nối. Chúng tôi kết hợp nghi lễ, hình ảnh tư liệu, tiết mục biểu diễn và thiết kế không gian để tạo nên một chương trình đáng nhớ cho tập thể và khách mời.',
        'image' => 'uploads/logo/logo8.jpg',
        'created_at' => date('Y-m-d H:i:s'),
        'event_date' => date('Y-m-d'),
        'featured' => 1,
        'status' => 'published'
    ]
];

if ($serviceSlug !== '' && isset($servicePresetMap[$serviceSlug])) {
    $service = $servicePresetMap[$serviceSlug];
    $service['id'] = 0;
    $page_title = htmlspecialchars($service['title']);
    $otherServices = [];
} else {
    if ($id <= 0) {
        header('Location: teambuilding.php');
        exit;
    }

    $service = getServiceById($id);
    if (!$service || $service['status'] !== 'published') {
        header('Location: teambuilding.php');
        exit;
    }

    $page_title = htmlspecialchars($service['title']);

    $allServices = getLatestServices(6);
    $otherServices = array_filter($allServices, function($s) use ($id) {
        return $s['id'] != $id;
    });
    $otherServices = array_slice($otherServices, 0, 4);
}

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

.section-heading {
    text-align: center;
    margin-bottom: 50px;
}
.section-heading h2 {   
    font-size: 1.6rem;
    font-weight: 800;
    position: relative;
    display: inline-block;
    color: var(--primary-gold);
    text-transform: uppercase;
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

.grid-3.news-grid {
    grid-template-columns: repeat(4, 1fr);
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

.service-detail-hero {
    background: linear-gradient(120deg, #0f172a, #1a1a2e);
    color: var(--text-primary);
    padding: 60px 0;
    text-align: center;
    margin-bottom: 48px;
    border-bottom: 2px solid var(--primary-gold);
}
.service-detail-hero h1 {
    font-family: 'Arial', 'Calibri', sans-serif;
    font-size: 2.8rem;
    font-weight: 800;
    margin-bottom: 16px;
    color: var(--primary-gold);
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
}
.service-meta {
    font-family: 'Arial', 'Calibri', sans-serif;
    font-size: 1rem;
    display: flex;
    justify-content: center;
    gap: 24px;
    flex-wrap: wrap;
    color: var(--text-secondary);
}
.service-meta i { margin-right: 6px; color: var(--primary-gold); }

.btn-back {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: linear-gradient(135deg, var(--primary-gold), var(--primary-gold-light));
    color: #1a1a1a;
    padding: 10px 24px;
    border-radius: 40px;
    text-decoration: none;
    font-weight: 700;
    transition: var(--transition);
    box-shadow: var(--shadow-gold);
    margin-bottom: 20px;
    border: none;
    cursor: pointer;
}
.btn-back:hover {
    transform: translateX(-3px);
    box-shadow: 0 8px 18px rgba(212,161,71,0.4);
    background: linear-gradient(135deg, var(--primary-gold-light), var(--primary-gold));
}
.service-detail-hero .section-heading h2 {
    font-size: 1.6rem !important; 
}

.service-detail-content {
    background: linear-gradient(135deg, #1A1A1A, #242424);
    border-radius: 28px;
    padding: 40px;
    margin-bottom: 60px;
    box-shadow: var(--shadow-lg);
    border: 1px solid rgba(212,161,71,0.2);
}
.service-detail-content img {
    width: 100%;
    max-height: 500px;
    object-fit: cover;
    border-radius: 20px;
    margin-bottom: 30px;
    border: 1px solid rgba(212,161,71,0.3);
}
.description {
    font-size: 1.05rem;
    line-height: 1.8;
    color: var(--text-secondary);
}
.description p, .description li {
    color: var(--text-secondary);
}

.other-services {
    margin: 60px 0 40px;
}
.other-title {
    font-family: 'Arial', 'Calibri', sans-serif;
    font-size: 1.8rem;
    font-weight: 700;
    text-align: center;
    margin-bottom: 30px;
    position: relative;
    padding-bottom: 15px;
    color: var(--primary-gold);
}
.other-title::before {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 80px;
    height: 3px;
    background: linear-gradient(90deg, var(--primary-gold), var(--primary-gold-light));
    border-radius: 2px;
}

.services-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 30px;
}
.service-item {
    background: linear-gradient(135deg, #1A1A1A, #242424);
    border-radius: 24px;
    overflow: hidden;
    transition: var(--transition);
    text-decoration: none;
    color: inherit;
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    border: 1px solid rgba(212,161,71,0.2);
    display: flex;
    flex-direction: column;
}
.service-item:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
    border-color: rgba(212,161,71,0.5);
}
.service-item img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}
.service-item .info {
    padding: 20px;
    flex: 1;
    display: flex;
    flex-direction: column;
}
.service-item h4 {
    font-family: 'Arial', 'Calibri', sans-serif;
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 10px;
    color: var(--text-primary);
}
.service-item .date {
    font-size: 0.85rem;
    color: var(--primary-gold);
    margin-bottom: 12px;
}
.service-item p {
    font-family: 'Arial', 'Calibri', sans-serif;
    font-size: 0.9rem;
    color: var(--text-secondary);
    margin-bottom: 12px;
    line-height: 1.5;
}
.read-more {
    margin-top: auto;
    font-weight: 600;
    font-size: 0.85rem;
    color: var(--primary-gold);
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: var(--transition);
}
.read-more:hover {
    color: var(--primary-gold-light);
    transform: translateX(5px);
}

/* Responsive */
@media (max-width: 768px) {
    .service-detail-hero h1 { font-size: 2rem; }
    .service-detail-content { padding: 24px; }
    .services-grid { grid-template-columns: 1fr; }
    .service-meta { flex-direction: column; gap: 8px; align-items: center; }
}
@media (max-width: 576px) {
    .service-detail-hero h1 { font-size: 1.6rem; }
    .other-title { font-size: 1.4rem; }
}
</style>

<section class="service-detail-hero">
    <div class="container">
        <div class="section-heading">
        <h2><?= htmlspecialchars($service['title']) ?></h2>
        </div>
        <div class="service-meta">
            <span><i class="far fa-calendar-alt"></i> Đăng: <?= date('d/m/Y', strtotime($service['created_at'])) ?></span>
            <?php if (!empty($service['event_date'])): ?>
            <span><i class="fas fa-clock"></i> Thực hiện: <?= date('d/m/Y', strtotime($service['event_date'])) ?></span>
            <?php endif; ?>
            <?php if ($service['featured']): ?>
            <span><i class="fas fa-star"></i> Dịch vụ nổi bật</span>
            <?php endif; ?>
        </div>
    </div>
</section>

<div class="container">
    <a href="teambuilding.php" class="btn-back"><i class="fas fa-arrow-left"></i> Quay lại danh sách dịch vụ</a>
    
    <div class="service-detail-content">
        <?php if (!empty($service['image'])): ?>
            <img src="<?= htmlspecialchars(fixImagePath($service['image'])) ?>" alt="<?= htmlspecialchars($service['title']) ?>">
        <?php endif; ?>
        <div class="description">
            <?= cleanText($service['description']) ?>
        </div>
    </div>

    <?php if (!empty($otherServices)): ?>
    <div class="other-services">
        <div class="other-title">📌 Các dịch vụ khác</div>
        <div class="services-grid">
            <?php foreach ($otherServices as $other): ?>
                <a href="service_detail.php?id=<?= $other['id'] ?>" class="service-item">
                    <?php if (!empty($other['image'])): ?>
                        <img src="<?= htmlspecialchars(fixImagePath($other['image'])) ?>" alt="<?= htmlspecialchars($other['title']) ?>">
                    <?php else: ?>
                        <div style="height:200px; background: #2a2a2a; display:flex; align-items:center; justify-content:center; color: var(--primary-gold);">🎬</div>
                    <?php endif; ?>
                    <div class="info">
                        <h4><?= htmlspecialchars($other['title']) ?></h4>
                        <div class="date"><i class="far fa-calendar-alt"></i> <?= !empty($other['event_date']) ? date('d/m/Y', strtotime($other['event_date'])) : 'Liên hệ' ?></div>
                        <p><?= htmlspecialchars(substr($other['description'] ?? '', 0, 100)) ?>...</p>
                        <div class="read-more">Xem chi tiết <i class="fas fa-arrow-right"></i></div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php include 'includes/fe/footer.php'; ?>