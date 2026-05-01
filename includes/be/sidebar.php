<?php
$current_file = basename($_SERVER['PHP_SELF']);
if (!defined('BASE_URL')) {
    define('BASE_URL', '/hueshow/');
}
?>
<div class="sidebar">
    <div class="sidebar-header">
        <h3><i class="fas fa-calendar-alt"></i> HueShow Admin</h3>
    </div>
    <ul class="sidebar-menu">
        <li class="<?= $current_file == 'admin_dashboard.php' ? 'active' : '' ?>">
            <a href="<?= BASE_URL ?>admin/admin_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        </li>
        <li class="<?= $current_file == 'admin_users.php' ? 'active' : '' ?>">
            <a href="<?= BASE_URL ?>admin/admin_users.php"><i class="fas fa-users"></i> Quản lý người dùng</a>
        </li>
        <li class="<?= in_array($current_file, ['admin_articles.php', 'admin_articles_add.php', 'admin_articles_edit.php']) ? 'active' : '' ?>">
            <a href="<?= BASE_URL ?>admin/admin_articles.php"><i class="fas fa-newspaper"></i> Quản lý bài viết</a>
        </li>
         <li class="<?= in_array($current_file, ['admin_services.php', 'admin_services_add.php', 'admin_services_edit.php']) ? 'active' : '' ?>">
            <a href="<?= BASE_URL ?>admin/admin_services.php"><i class="fas fa-newspaper"></i> Quản lý dịch vụ</a>
        </li>
        <li class="<?= in_array($current_file, ['admin_events.php', 'admin_events_add.php', 'admin_events_edit.php']) ? 'active' : '' ?>">
            <a href="<?= BASE_URL ?>admin/admin_events.php"><i class="fas fa-calendar-check"></i> Quản lý sự kiện</a>
        </li>
        <li class="<?= in_array($current_file, ['admin_banners.php', 'admin_banner_add.php', 'admin_banner_edit.php']) ? 'active' : '' ?>">
            <a href="<?= BASE_URL ?>admin/admin_banners.php"><i class="fas fa-images"></i> Quản lý Banner</a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>admin/admin_logout.php"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a>
        </li>
    </ul>
</div>