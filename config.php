<?php
// config.php - Cấu hình kết nối Database, session, helper functions
session_start();
define('BASE_URL', '/hueshow/');

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');  // change if needed
define('DB_NAME', 'hueshow_db');
define('DB_CHARSET', 'utf8mb4');

// Connect to database
try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $conn->set_charset(DB_CHARSET);
} catch (Exception $e) {
    die("Database connection error: " . $e->getMessage());
}

function sanitize($data) {
    return strip_tags(trim($data));
}

// Helper: check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function requireAdmin() {
    if (!isLoggedIn() || !isAdmin()) {
        header('Location: admin_login.php');
        exit;
    }
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: admin_login.php');
        exit;
    }
}

// ==================== ARTICLES (Bài viết tin tức) ====================
// Frontend: lấy bài viết đã xuất bản
function getAllArticles($search = '') {
    global $conn;
    $sql = "SELECT * FROM articles WHERE status = 'published'";
    if (!empty($search)) {
        $sql .= " AND (title LIKE ? OR description LIKE ?)";
        $stmt = $conn->prepare($sql);
        $term = "%$search%";
        $stmt->bind_param("ss", $term, $term);
    } else {
        $stmt = $conn->prepare($sql);
    }
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getLatestArticles($limit = 3) {
    global $conn;
    $limit = (int)$limit;
    $stmt = $conn->prepare("SELECT * FROM articles WHERE status = 'published' ORDER BY created_at DESC LIMIT ?");
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getArticleById($id) {
    global $conn;
    $id = (int)$id;
    $stmt = $conn->prepare("SELECT * FROM articles WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Admin: lấy tất cả bài viết (kể cả draft)
function getAllArticlesAdmin($search = '') {
    global $conn;
    $sql = "SELECT * FROM articles";
    if (!empty($search)) {
        $sql .= " WHERE title LIKE ? OR description LIKE ?";
        $term = "%$search%";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $term, $term);
    } else {
        $stmt = $conn->prepare($sql);
    }
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
function createArticle($title, $slug, $description, $image, $status, $featured) {
    global $conn;
    $title = sanitize($title);
    $slug = sanitize($slug);
    $description = sanitize($description);
    $image = sanitize($image);
    $status = sanitize($status);
    $featured = $featured ? 1 : 0;

    $stmt = $conn->prepare("INSERT INTO articles (title, slug, description, image, status, featured) VALUES (?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("sssssi", $title, $slug, $description, $image, $status, $featured);
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }
    return true;
}
function updateArticle($id, $title, $slug, $description, $image, $status, $featured) {
    global $conn;
    $id = (int)$id;
    $title = sanitize($title);
    $slug = sanitize($slug);
    $description = sanitize($description);
    $image = sanitize($image);
    $status = sanitize($status);
    $featured = $featured ? 1 : 0;
    $stmt = $conn->prepare("UPDATE articles SET title=?, slug=?, description=?, image=?, status=?, featured=? WHERE id=?");
    $stmt->bind_param("sssssii", $title, $slug, $description, $image, $status, $featured, $id);
    return $stmt->execute();
}

function deleteArticle($id) {
    global $conn;
    $id = (int)$id;
    $stmt = $conn->prepare("DELETE FROM articles WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

// ==================== EVENTS (Sự kiện) ====================
// Frontend: lấy sự kiện đã xuất bản
function getAllEvents($search = '') {
    global $conn;
    $sql = "SELECT * FROM events WHERE status = 'published'";
    if (!empty($search)) {
        $sql .= " AND (title LIKE ? OR description LIKE ?)";
        $stmt = $conn->prepare($sql);
        $term = "%$search%";
        $stmt->bind_param("ss", $term, $term);
    } else {
        $stmt = $conn->prepare($sql);
    }
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getLatestEvents($limit = 6) {
    global $conn;
    $limit = (int)$limit;
    $stmt = $conn->prepare("SELECT * FROM events WHERE status = 'published' ORDER BY event_date DESC, created_at DESC LIMIT ?");
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// ==================== SERVICES (Dịch vụ) ====================
// Frontend: lấy dịch vụ đã xuất bản
function getAllServices($search = '') {
    global $conn;
    $sql = "SELECT * FROM services WHERE status = 'published'";
    if (!empty($search)) {
        $sql .= " AND (title LIKE ? OR description LIKE ?)";
        $stmt = $conn->prepare($sql);
        $term = "%$search%";
        $stmt->bind_param("ss", $term, $term);
    } else {
        $stmt = $conn->prepare($sql);
    }
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getLatestServices($limit = 6) {
    global $conn;
    $limit = (int)$limit;
    $stmt = $conn->prepare("SELECT * FROM services WHERE status = 'published' ORDER BY event_date DESC, created_at DESC LIMIT ?");
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getServiceById($id) {
    global $conn;
    $id = (int)$id;
    $stmt = $conn->prepare("SELECT * FROM services WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Admin: lấy tất cả dịch vụ (kể cả draft)
function getAllServicesAdmin($search = '') {
    global $conn;
    $sql = "SELECT * FROM services";
    if (!empty($search)) {
        $sql .= " WHERE title LIKE ? OR description LIKE ?";
        $term = "%$search%";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $term, $term);
    } else {
        $stmt = $conn->prepare($sql);
    }
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function createService($title, $description, $image, $event_date, $status, $featured) {
    global $conn;
    $title = sanitize($title);
    $description = sanitize($description);
    $image = sanitize($image);
    $event_date = $event_date ?: null;
    $status = sanitize($status);
    $featured = $featured ? 1 : 0;
    $stmt = $conn->prepare("INSERT INTO services (title, description, image, event_date, status, featured) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssi", $title, $description, $image, $event_date, $status, $featured);
    return $stmt->execute();
}

function updateService($id, $title, $description, $image, $event_date, $status, $featured) {
    global $conn;
    $id = (int)$id;
    $title = sanitize($title);
    $description = sanitize($description);
    $image = sanitize($image);
    $event_date = $event_date ?: null;
    $status = sanitize($status);
    $featured = $featured ? 1 : 0;
    $stmt = $conn->prepare("UPDATE services SET title=?, description=?, image=?, event_date=?, status=?, featured=? WHERE id=?");
    $stmt->bind_param("sssssii", $title, $description, $image, $event_date, $status, $featured, $id);
    return $stmt->execute();
}

function deleteService($id) {
    global $conn;
    $id = (int)$id;
    $stmt = $conn->prepare("DELETE FROM services WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}


function getTeamBuildingEvents($limit = 4) {
    global $conn;
    $limit = (int)$limit;
    
    // Lấy tất cả sự kiện team building hoặc nổi bật (không giới hạn)
    $sql = "SELECT *, 1 as priority FROM events 
            WHERE status = 'published' 
            AND (LOWER(title) LIKE LOWER(?) OR LOWER(title) LIKE LOWER(?) OR featured = 1)
            ORDER BY featured DESC, event_date DESC";
    $stmt = $conn->prepare($sql);
    $term1 = '%team building%';
    $term2 = '%teambuilding%';
    $stmt->bind_param("ss", $term1, $term2);
    $stmt->execute();
    $teamEvents = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    
    // Nếu đủ số lượng, chỉ lấy $limit cái đầu
    if (count($teamEvents) >= $limit) {
        return array_slice($teamEvents, 0, $limit);
    }
    
    // Thiếu bao nhiêu thì lấy sự kiện thường bù vào
    $remaining = $limit - count($teamEvents);
    $sql2 = "SELECT *, 0 as priority FROM events 
             WHERE status = 'published' 
             AND (LOWER(title) NOT LIKE LOWER(?) AND LOWER(title) NOT LIKE LOWER(?) AND featured = 0)
             ORDER BY created_at DESC 
             LIMIT ?";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param("ssi", $term1, $term2, $remaining);
    $stmt2->execute();
    $normalEvents = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);
    
    return array_merge($teamEvents, $normalEvents);
}
function getEventById($id) {
    global $conn;
    $id = (int)$id;
    $stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}


function cleanText($text) {
    if (empty($text)) return '';
    
    // Loại bỏ dấu backslash thừa (do real_escape_string hoặc addslashes)
    $text = stripslashes($text);
    
    // Xử lý literal \r\n (nếu có) thành xuống dòng thực
    $search = ["\\r\\n", "\\n", "\\r", "\\\\r\\\\n", "\\\\n", "\\\\r"];
    $text = str_replace($search, "\n", $text);
    
    // Chuyển xuống dòng thực tế thành <br>
    $text = str_replace(["\r\n", "\r"], "\n", $text);
    
    // Escape HTML để an toàn, sau đó chuyển \n thành <br>
    return nl2br(htmlspecialchars($text, ENT_QUOTES, 'UTF-8'));
}

// Admin: lấy tất cả sự kiện (kể cả draft)
function getAllEventsAdmin($search = '') {
    global $conn;
    $sql = "SELECT * FROM events";
    if (!empty($search)) {
        $sql .= " WHERE title LIKE ? OR description LIKE ?";
        $term = "%$search%";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $term, $term);
    } else {
        $stmt = $conn->prepare($sql);
    }
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function createEvent($title, $slug, $description, $image, $event_date, $status, $featured) {
    global $conn;
    $title = sanitize($title);
    $slug = sanitize($slug);
    $description = sanitize($description);
    $image = sanitize($image);
    $event_date = $event_date ?: null;
    $status = sanitize($status);
    $featured = $featured ? 1 : 0;
    $stmt = $conn->prepare("INSERT INTO events (title, slug, description, image, event_date, status, featured) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssi", $title, $slug, $description, $image, $event_date, $status, $featured);
    return $stmt->execute();
}

function updateEvent($id, $title, $slug, $description, $image, $event_date, $status, $featured) {
    global $conn;
    $id = (int)$id;
    $title = sanitize($title);
    $slug = sanitize($slug);
    $description = sanitize($description);
    $image = sanitize($image);
    $event_date = $event_date ?: null;
    $status = sanitize($status);
    $featured = $featured ? 1 : 0;
    $stmt = $conn->prepare("UPDATE events SET title=?, slug=?, description=?, image=?, event_date=?, status=?, featured=? WHERE id=?");
    $stmt->bind_param("ssssssii", $title, $slug, $description, $image, $event_date, $status, $featured, $id);
    return $stmt->execute();
}

function deleteEvent($id) {
    global $conn;
    $id = (int)$id;
    $stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

// ==================== USER FUNCTIONS ====================
function getAllUsers($search = '') {
    global $conn;
    $sql = "SELECT id, username, email, fullname, role, created_at FROM users";
    if (!empty($search)) {
        $sql .= " WHERE username LIKE ? OR email LIKE ? OR fullname LIKE ?";
        $stmt = $conn->prepare($sql);
        $term = "%$search%";
        $stmt->bind_param("sss", $term, $term, $term);
    } else {
        $stmt = $conn->prepare($sql);
    }
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getUserById($id) {
    global $conn;
    $id = (int)$id;
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function createUser($username, $email, $password, $fullname, $role = 'user') {
    global $conn;
    $username = sanitize($username);
    $email = sanitize($email);
    $fullname = sanitize($fullname);
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, fullname, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $username, $email, $hashed, $fullname, $role);
    return $stmt->execute();
}

function updateUser($id, $username, $email, $fullname, $role) {
    global $conn;
    $id = (int)$id;
    $username = sanitize($username);
    $email = sanitize($email);
    $fullname = sanitize($fullname);
    $role = sanitize($role);
    $stmt = $conn->prepare("UPDATE users SET username=?, email=?, fullname=?, role=? WHERE id=?");
    $stmt->bind_param("ssssi", $username, $email, $fullname, $role, $id);
    return $stmt->execute();
}

function deleteUser($id) {
    global $conn;
    $id = (int)$id;
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

// ==================== BANNER FUNCTIONS ====================
function getAllBanners() {
    global $conn;
    $result = $conn->query("SELECT * FROM banners ORDER BY order_index ASC");
    return $result->fetch_all(MYSQLI_ASSOC);
}

function getActiveBanners() {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM banners WHERE status = 'active' ORDER BY order_index ASC");
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getBannerById($id) {
    global $conn;
    $id = (int)$id;
    $stmt = $conn->prepare("SELECT * FROM banners WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function createBanner($title, $image_url, $link, $order_index, $status) {
    global $conn;
    $title = sanitize($title);
    $image_url = sanitize($image_url);
    $link = sanitize($link);
    $order_index = (int)$order_index;
    $status = sanitize($status);
    $stmt = $conn->prepare("INSERT INTO banners (title, image_url, link, order_index, status) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssis", $title, $image_url, $link, $order_index, $status);
    return $stmt->execute();
}

function updateBanner($id, $title, $image_url, $link, $order_index, $status) {
    global $conn;
    $id = (int)$id;
    $title = sanitize($title);
    $image_url = sanitize($image_url);
    $link = sanitize($link);
    $order_index = (int)$order_index;
    $status = sanitize($status);
    $stmt = $conn->prepare("UPDATE banners SET title=?, image_url=?, link=?, order_index=?, status=? WHERE id=?");
    $stmt->bind_param("sssisi", $title, $image_url, $link, $order_index, $status, $id);
    return $stmt->execute();
}

function deleteBanner($id) {
    global $conn;
    $id = (int)$id;
    $stmt = $conn->prepare("DELETE FROM banners WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

// ==================== TESTIMONIAL FUNCTIONS ====================
function getAllTestimonials($limit = null) {
    global $conn;
    $sql = "SELECT * FROM testimonials WHERE status = 'active' ORDER BY order_index ASC, created_at DESC";
    if ($limit) $sql .= " LIMIT " . (int)$limit;
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

function getAllTestimonialsAdmin() {
    global $conn;
    $result = $conn->query("SELECT * FROM testimonials ORDER BY order_index ASC, created_at DESC");
    return $result->fetch_all(MYSQLI_ASSOC);
}

function getTestimonialById($id) {
    global $conn;
    $id = (int)$id;
    $stmt = $conn->prepare("SELECT * FROM testimonials WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function createTestimonial($name, $position, $company, $avatar, $content, $rating, $order_index, $status) {
    global $conn;
    $name = sanitize($name);
    $position = sanitize($position);
    $company = sanitize($company);
    $avatar = sanitize($avatar);
    $content = sanitize($content);
    $rating = (int)$rating;
    $order_index = (int)$order_index;
    $status = sanitize($status);
    $stmt = $conn->prepare("INSERT INTO testimonials (name, position, company, avatar, content, rating, order_index, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssiis", $name, $position, $company, $avatar, $content, $rating, $order_index, $status);
    return $stmt->execute();
}

function updateTestimonial($id, $name, $position, $company, $avatar, $content, $rating, $order_index, $status) {
    global $conn;
    $id = (int)$id;
    $name = sanitize($name);
    $position = sanitize($position);
    $company = sanitize($company);
    $avatar = sanitize($avatar);
    $content = sanitize($content);
    $rating = (int)$rating;
    $order_index = (int)$order_index;
    $status = sanitize($status);
    $stmt = $conn->prepare("UPDATE testimonials SET name=?, position=?, company=?, avatar=?, content=?, rating=?, order_index=?, status=? WHERE id=?");
    $stmt->bind_param("sssssiisi", $name, $position, $company, $avatar, $content, $rating, $order_index, $status, $id);
    return $stmt->execute();
}

function deleteTestimonial($id) {
    global $conn;
    $id = (int)$id;
    $stmt = $conn->prepare("DELETE FROM testimonials WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}
?>