<?php
session_start();
define('BASE_URL', '/hueshow/');

define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_NAME', getenv('DB_NAME') ?: 'hueshow_db');
define('DB_PORT', (int)(getenv('DB_PORT') ?: 3306));
define('DB_CHARSET', 'utf8mb4');
try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
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

// Improved tableExists with information_schema
function tableExists($tableName) {
    global $conn;

    $tableName = trim($tableName);
    if ($tableName === '') {
        return false;
    }

    try {
        $stmt = $conn->prepare("SELECT 1 FROM information_schema.TABLES WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?");
        if (!$stmt) {
            return false;
        }

        $dbName = DB_NAME;
        $stmt->bind_param("ss", $dbName, $tableName);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        return $result && $result->num_rows > 0;
    } catch (Exception $e) {
        return false;
    }
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

// ==================== ARTICLES ====================
function getAllArticles($search = '') {
    global $conn;
    
    if (!tableExists('articles')) {
        return [];
    }

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
    $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $result ?: [];
}

function getLatestArticles($limit = 3) {
    global $conn;
    
    if (!tableExists('articles')) {
        return [];
    }

    $limit = (int)$limit;
    $stmt = $conn->prepare("SELECT * FROM articles WHERE status = 'published' ORDER BY created_at DESC LIMIT ?");
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $result ?: [];
}

function getArticleById($id) {
    global $conn;
    
    if (!tableExists('articles')) {
        return null;
    }

    $id = (int)$id;
    $stmt = $conn->prepare("SELECT * FROM articles WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $result;
}

function getAllArticlesAdmin($search = '') {
    global $conn;
    
    if (!tableExists('articles')) {
        return [];
    }

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
    $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $result ?: [];
}

function createArticle($title, $slug, $description, $image, $status, $featured) {
    global $conn;
    
    if (!tableExists('articles')) {
        throw new Exception("Table articles does not exist");
    }

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
    $stmt->close();
    return true;
}

function updateArticle($id, $title, $slug, $description, $image, $status, $featured) {
    global $conn;
    
    if (!tableExists('articles')) {
        throw new Exception("Table articles does not exist");
    }

    $id = (int)$id;
    $title = sanitize($title);
    $slug = sanitize($slug);
    $description = sanitize($description);
    $image = sanitize($image);
    $status = sanitize($status);
    $featured = $featured ? 1 : 0;
    
    $stmt = $conn->prepare("UPDATE articles SET title=?, slug=?, description=?, image=?, status=?, featured=? WHERE id=?");
    $stmt->bind_param("sssssii", $title, $slug, $description, $image, $status, $featured, $id);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function deleteArticle($id) {
    global $conn;
    
    if (!tableExists('articles')) {
        return false;
    }

    $id = (int)$id;
    $stmt = $conn->prepare("DELETE FROM articles WHERE id = ?");
    $stmt->bind_param("i", $id);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

// ==================== EVENTS ====================
function getAllEvents($search = '') {
    global $conn;
    
    if (!tableExists('events')) {
        return [];
    }

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
    $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $result ?: [];
}

function getLatestEvents($limit = 6) {
    global $conn;
    
    if (!tableExists('events')) {
        return [];
    }

    $limit = (int)$limit;
    $stmt = $conn->prepare("SELECT * FROM events WHERE status = 'published' ORDER BY event_date DESC, created_at DESC LIMIT ?");
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $result ?: [];
}

function getAllEventsAdmin($search = '') {
    global $conn;
    
    if (!tableExists('events')) {
        return [];
    }

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
    $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $result ?: [];
}

function createEvent($title, $slug, $description, $image, $event_date, $status, $featured) {
    global $conn;
    
    if (!tableExists('events')) {
        throw new Exception("Table events does not exist");
    }

    $title = sanitize($title);
    $slug = sanitize($slug);
    $description = sanitize($description);
    $image = sanitize($image);
    $event_date = $event_date ?: null;
    $status = sanitize($status);
    $featured = $featured ? 1 : 0;
    
    $stmt = $conn->prepare("INSERT INTO events (title, slug, description, image, event_date, status, featured) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssi", $title, $slug, $description, $image, $event_date, $status, $featured);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function updateEvent($id, $title, $slug, $description, $image, $event_date, $status, $featured) {
    global $conn;
    
    if (!tableExists('events')) {
        throw new Exception("Table events does not exist");
    }

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
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function deleteEvent($id) {
    global $conn;
    
    if (!tableExists('events')) {
        return false;
    }

    $id = (int)$id;
    $stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
    $stmt->bind_param("i", $id);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function getEventById($id) {
    global $conn;
    
    if (!tableExists('events')) {
        return null;
    }

    $id = (int)$id;
    $stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $result;
}

function getTeamBuildingEvents($limit = 4) {
    global $conn;
    
    if (!tableExists('events')) {
        return [];
    }

    $limit = (int)$limit;
    
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
    $stmt->close();
    
    if (count($teamEvents) >= $limit) {
        return array_slice($teamEvents, 0, $limit);
    }
    
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
    $stmt2->close();
    
    return array_merge($teamEvents, $normalEvents);
}

function cleanText($text) {
    if (empty($text)) return '';
    
    $text = stripslashes($text);
    $search = ["\\r\\n", "\\n", "\\r", "\\\\r\\\\n", "\\\\n", "\\\\r"];
    $text = str_replace($search, "\n", $text);
    $text = str_replace(["\r\n", "\r"], "\n", $text);
    
    return nl2br(htmlspecialchars($text, ENT_QUOTES, 'UTF-8'));
}

// ==================== SERVICES ====================
function getAllServices($search = '') {
    global $conn;
    
    if (!tableExists('services')) {
        return [];
    }

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
    $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $result ?: [];
}

function getLatestServices($limit = 6) {
    global $conn;
    
    if (!tableExists('services')) {
        return [];
    }

    $limit = (int)$limit;
    $stmt = $conn->prepare("SELECT * FROM services WHERE status = 'published' ORDER BY event_date DESC, created_at DESC LIMIT ?");
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $result ?: [];
}

function getServiceById($id) {
    global $conn;
    
    if (!tableExists('services')) {
        return null;
    }

    $id = (int)$id;
    $stmt = $conn->prepare("SELECT * FROM services WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $result;
}

function getAllServicesAdmin($search = '') {
    global $conn;
    
    if (!tableExists('services')) {
        return [];
    }

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
    $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $result ?: [];
}

function createService($title, $description, $image, $event_date, $status, $featured) {
    global $conn;
    
    if (!tableExists('services')) {
        throw new Exception("Table services does not exist");
    }

    $title = sanitize($title);
    $description = sanitize($description);
    $image = sanitize($image);
    $event_date = $event_date ?: null;
    $status = sanitize($status);
    $featured = $featured ? 1 : 0;
    
    $stmt = $conn->prepare("INSERT INTO services (title, description, image, event_date, status, featured) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssi", $title, $description, $image, $event_date, $status, $featured);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function updateService($id, $title, $description, $image, $event_date, $status, $featured) {
    global $conn;
    
    if (!tableExists('services')) {
        throw new Exception("Table services does not exist");
    }

    $id = (int)$id;
    $title = sanitize($title);
    $description = sanitize($description);
    $image = sanitize($image);
    $event_date = $event_date ?: null;
    $status = sanitize($status);
    $featured = $featured ? 1 : 0;
    
    $stmt = $conn->prepare("UPDATE services SET title=?, description=?, image=?, event_date=?, status=?, featured=? WHERE id=?");
    $stmt->bind_param("sssssii", $title, $description, $image, $event_date, $status, $featured, $id);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function deleteService($id) {
    global $conn;
    
    if (!tableExists('services')) {
        return false;
    }

    $id = (int)$id;
    $stmt = $conn->prepare("DELETE FROM services WHERE id = ?");
    $stmt->bind_param("i", $id);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

// ==================== USERS ====================
function getAllUsers($search = '') {
    global $conn;
    
    if (!tableExists('users')) {
        return [];
    }

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
    $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $result ?: [];
}

function getUserById($id) {
    global $conn;
    
    if (!tableExists('users')) {
        return null;
    }

    $id = (int)$id;
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $result;
}

function createUser($username, $email, $password, $fullname, $role = 'user') {
    global $conn;
    
    if (!tableExists('users')) {
        throw new Exception("Table users does not exist");
    }

    $username = sanitize($username);
    $email = sanitize($email);
    $fullname = sanitize($fullname);
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, fullname, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $username, $email, $hashed, $fullname, $role);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function updateUser($id, $username, $email, $fullname, $role) {
    global $conn;
    
    if (!tableExists('users')) {
        return false;
    }

    $id = (int)$id;
    $username = sanitize($username);
    $email = sanitize($email);
    $fullname = sanitize($fullname);
    $role = sanitize($role);
    
    $stmt = $conn->prepare("UPDATE users SET username=?, email=?, fullname=?, role=? WHERE id=?");
    $stmt->bind_param("ssssi", $username, $email, $fullname, $role, $id);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function deleteUser($id) {
    global $conn;
    
    if (!tableExists('users')) {
        return false;
    }

    $id = (int)$id;
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

// ==================== BANNERS ====================
function getAllBanners() {
    global $conn;

    if (!tableExists('banners')) {
        return [];
    }

    $result = $conn->query("SELECT * FROM banners ORDER BY order_index ASC");
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

function getActiveBanners() {
    global $conn;

    if (!tableExists('banners')) {
        return [];
    }

    $stmt = $conn->prepare("SELECT * FROM banners WHERE status = 'active' ORDER BY order_index ASC");
    if (!$stmt) {
        return [];
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

function getBannerById($id) {
    global $conn;

    if (!tableExists('banners')) {
        return null;
    }

    $id = (int)$id;
    $stmt = $conn->prepare("SELECT * FROM banners WHERE id = ?");
    if (!$stmt) {
        return null;
    }

    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    return $result;
}

function createBanner($title, $image_url, $link, $order_index, $status) {
    global $conn;

    if (!tableExists('banners')) {
        return false;
    }

    $title = sanitize($title);
    $image_url = sanitize($image_url);
    $link = sanitize($link);
    $order_index = (int)$order_index;
    $status = sanitize($status);

    $stmt = $conn->prepare("INSERT INTO banners (title, image_url, link, order_index, status) VALUES (?, ?, ?, ?, ?)");
    if (!$stmt) {
        return false;
    }

    $stmt->bind_param("sssis", $title, $image_url, $link, $order_index, $status);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function updateBanner($id, $title, $image_url, $link, $order_index, $status) {
    global $conn;

    if (!tableExists('banners')) {
        return false;
    }

    $id = (int)$id;
    $title = sanitize($title);
    $image_url = sanitize($image_url);
    $link = sanitize($link);
    $order_index = (int)$order_index;
    $status = sanitize($status);

    $stmt = $conn->prepare("UPDATE banners SET title=?, image_url=?, link=?, order_index=?, status=? WHERE id=?");
    if (!$stmt) {
        return false;
    }

    $stmt->bind_param("sssisi", $title, $image_url, $link, $order_index, $status, $id);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function deleteBanner($id) {
    global $conn;

    if (!tableExists('banners')) {
        return false;
    }

    $id = (int)$id;
    $stmt = $conn->prepare("DELETE FROM banners WHERE id = ?");
    if (!$stmt) {
        return false;
    }

    $stmt->bind_param("i", $id);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

// ==================== TESTIMONIALS ====================
function getAllTestimonials($limit = null) {
    global $conn;
    
    if (!tableExists('testimonials')) {
        return [];
    }

    $sql = "SELECT * FROM testimonials WHERE status = 'active' ORDER BY order_index ASC, created_at DESC";
    if ($limit) $sql .= " LIMIT " . (int)$limit;
    $result = $conn->query($sql);
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

function getAllTestimonialsAdmin() {
    global $conn;
    
    if (!tableExists('testimonials')) {
        return [];
    }

    $result = $conn->query("SELECT * FROM testimonials ORDER BY order_index ASC, created_at DESC");
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

function getTestimonialById($id) {
    global $conn;
    
    if (!tableExists('testimonials')) {
        return null;
    }

    $id = (int)$id;
    $stmt = $conn->prepare("SELECT * FROM testimonials WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $result;
}

function createTestimonial($name, $position, $company, $avatar, $content, $rating, $order_index, $status) {
    global $conn;
    
    if (!tableExists('testimonials')) {
        throw new Exception("Table testimonials does not exist");
    }

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
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function updateTestimonial($id, $name, $position, $company, $avatar, $content, $rating, $order_index, $status) {
    global $conn;
    
    if (!tableExists('testimonials')) {
        return false;
    }

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
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function deleteTestimonial($id) {
    global $conn;
    
    if (!tableExists('testimonials')) {
        return false;
    }

    $id = (int)$id;
    $stmt = $conn->prepare("DELETE FROM testimonials WHERE id = ?");
    $stmt->bind_param("i", $id);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}
?>