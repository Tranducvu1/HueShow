-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 29, 2026 at 06:01 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hueshow_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE `articles` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `slug` varchar(255) NOT NULL,
  `excerpt` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('draft','published') DEFAULT 'draft',
  `featured` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`id`, `title`, `description`, `slug`, `excerpt`, `image`, `status`, `featured`, `created_at`) VALUES
(1, '111', '11111', 'https://www.facebook.com/share/p/1GhM7g5ReL/', NULL, 'uploads/articles/1777465963_69f1fa6b3827e.jpg', 'published', 0, '2026-04-29 12:16:24');

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `image_url` varchar(255) NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `order_index` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`id`, `title`, `image_url`, `link`, `order_index`, `status`, `created_at`) VALUES
(2, '1', 'uploads/banners/1777456717_69f1d64de47b6.png', '', 0, 'active', '2026-04-29 09:58:37'),
(3, 'chuyên nghiệp', 'uploads/banners/1777456756_69f1d674a9a24.jpg', '', 0, 'active', '2026-04-29 09:59:16');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('draft','published') DEFAULT 'published',
  `featured` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `event_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `slug`, `description`, `image`, `status`, `featured`, `created_at`, `event_date`) VALUES
(2, 'HUẾ SHOW – ENTERTAINMENT GAMESHOW KHUẤY ĐỘNG MỌI SỰ KIỆN', 'hue-show-entertainment-gameshow-khuay-dong-moi-su-kien', 'Bạn muốn chương trình của mình trở nên sôi động – thu hút – đáng nhớ? Một Gameshow được đầu tư bài bản chính là “chìa khóa” tạo nên cảm xúc và sự kết nối! DỊCH VỤ ENTERTAINMENT – GAMESHOW BAO GỒM: Xây dựng kịch bản sáng tạo, MC hoạt náo chuyên nghiệp, Setup đạo cụ sân khấu... Đã triển khai – Đã khuấy động – Và sẵn sàng “cháy” cùng sự kiện của bạn!', 'uploads/articles/1777459509_69f1e13513b44.jpg', 'published', 0, '2026-04-29 08:32:36', NULL),
(3, 'DANCE SHOWCASE FESTIVAL 2025 – TỎA SÁNG NỤ CƯỜI VIỆT', 'dance-showcase-festival-2025-toa-sang-nu-cuoi-viet', 'Tự hào do Huế Show Event & Media tổ chức. Gần 2 tháng rực rỡ khép lại với hơn 70 đội từ khắp mọi miền đăng ký dự thi, 34 đội tranh tài chung kết và 15 đội góp mặt đêm Gala. Đây không chỉ là cuộc thi – mà là hành trình gắn kết đam mê, lan tỏa nụ cười và thắp lên những bước nhảy đầy cảm hứng.', 'uploads/articles/1777459481_69f1e119105c7.jpg', 'published', 1, '2026-04-29 08:32:36', NULL),
(4, '𝐂𝐇𝐎̛𝐈 𝐒𝐔̛̣ 𝐊𝐈𝐄̣̂𝐍 𝐏𝐇𝐀̉𝐈 𝐂𝐇𝐀̂́𝐓 – 𝐂𝐇𝐎̣𝐍 𝐇𝐔𝐄̂́ 𝐒𝐇𝐎𝐖 𝐋𝐀̀ 𝐍𝐇𝐀̂́𝐓', 'choi-su-kien-phai-chat-chon-hue-show-la-nhat', 'Huế Show Event & Media – Người bạn đồng hành đáng tin cậy của doanh nghiệp miền Trung! Chúng tôi mang đến giải pháp MICE trọn gói (Hội nghị, hội thảo, Gala, du lịch khen thưởng) – sáng tạo, chuyên nghiệp, KHÔNG PHÁT SINH chi phí. Đội ngũ “cứu hộ sự kiện” xuất hiện trong 15 phút, giải quyết mọi tình huống.', 'uploads/articles/1777459599_69f1e18fdb7b5.jpg', 'published', 0, '2026-04-29 08:32:36', NULL),
(5, '𝐂𝐇𝐎̛𝐈 𝐒𝐔̛̣ 𝐊𝐈𝐄̣̂𝐍 𝐏𝐇𝐀̉𝐈 𝐂𝐇𝐀̂́𝐓 – 𝐂𝐇𝐎̣𝐍 𝐇𝐔𝐄̂́ 𝐒𝐇𝐎𝐖 𝐋𝐀̀ 𝐍𝐇𝐀̂́𝐓', 'https://www.facebook.com/story.php?story_fbid=717199790847448&id=100076722994104&rdid=EdJq1w6K84OgqifZ#', '👉 Huế Show Event & Media – Người bạn đồng hành đáng tin cậy của doanh nghiệp miền Trung!\\r\\nBạn đang chuẩn bị một chương trình 🎤 hội nghị – 🎓 hội thảo – 🎉 gala –           🏖️ du lịch khen thưởng cho công ty?\\r\\n  📍 Nhưng lại gặp những “nỗi đau” quá quen thuộc:\\r\\n  😵‍💫 Thiếu ý tưởng – thiếu thời gian – thiếu người điều phối?\\r\\n  💸 Chi phí \\\"nở phình\\\", báo giá một kiểu – làm thật một kiểu?\\r\\n  💥 Sự kiện quan trọng mà chỉ một lỗi nhỏ cũng đủ toang cả chiến lược?\\r\\n  🧠 ĐỪNG LO – HUẾ SHOW “CÂN TẤT” 💪\\r\\nChúng tôi mang đến giải pháp MICE trọn gói – sáng tạo, chuyên nghiệp, KHÔNG PHÁT SINH:\\r\\n  🛠️ Lên ý tưởng & kịch bản độc quyền theo mục tiêu doanh nghiệp\\r\\n  🎨 Thiết kế – thi công – vận hành chương trình trọn gói từ A-Z\\r\\n  🔊 Âm thanh – ánh sáng – sân khấu – truyền thông đồng bộ, chỉn chu\\r\\n  📊 Báo giá rõ ràng – cam kết không phát sinh bất ngờ\\r\\n  🧯 Đội ngũ “cứu hộ sự kiện” xuất hiện trong 15 phút, giải quyết mọi tình huống 🔥\\r\\n  🎁 QUÀ TẶNG ĐẶC BIỆT: Checklist 10 BƯỚC TỔ CHỨC MICE KHÔNG LỖI dành riêng cho doanh nghiệp miền Trung\\r\\n📩 INBOX ngay từ khóa “MICE HUẾ SHOW” để nhận file ngay hôm nay!', 'uploads/articles/1777459664_69f1e1d07897d.jpg', 'published', 0, '2026-04-29 10:47:44', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `position` varchar(100) DEFAULT NULL,
  `company` varchar(200) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `content` text NOT NULL,
  `rating` tinyint(1) DEFAULT 5,
  `order_index` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `testimonials`
--

INSERT INTO `testimonials` (`id`, `name`, `position`, `company`, `avatar`, `content`, `rating`, `order_index`, `status`, `created_at`) VALUES
(1, 'Mr. Lê Nam', 'CEO', 'Webdesign', 'https://randomuser.me/api/portraits/men/32.jpg', 'Đội ngũ kỹ sư quản lý, công nhân của công ty đã thực hiện dự án thành công đảm bảo tiến độ bàn giao, công tác an toàn lao động được quản lý rất tuyệt vời.', 5, 1, 'active', '2026-04-29 08:44:17'),
(2, 'Mr. Nguyễn Hải Nam', 'Manager', 'Webdesign', 'https://randomuser.me/api/portraits/men/45.jpg', 'Ít có một đội nhóm nào mà ở khu vực miền Bắc có sự chuyên nghiệp trong từng khâu chuẩn bị và tổ chức, một hệ thống gametool đạt chất lượng và sáng tạo.', 5, 2, 'active', '2026-04-29 08:44:17'),
(3, 'Ms. Phương Thảo', 'HR Director', 'VPBank', 'https://randomuser.me/api/portraits/women/68.jpg', 'Chương trình team building \'Win Together\' đã thực sự gắn kết nhân viên và truyền cảm hứng. Cảm ơn HueShow!', 5, 3, 'active', '2026-04-29 08:44:17'),
(4, 'Mr. David Wilson', 'Operation Manager', 'Boehringer', 'https://randomuser.me/api/portraits/men/89.jpg', 'Professional and creative. The \'Break Through\' event exceeded our expectations.', 5, 4, 'active', '2026-04-29 08:44:17'),
(5, 'Ms. Trần Thị Mai', 'Marketing Lead', 'Sony Vietnam', 'https://randomuser.me/api/portraits/women/44.jpg', 'Chương trình diễn ra suôn sẻ, chuyên nghiệp từ khâu chuẩn bị đến triển khai. Rất ấn tượng!', 4, 5, 'active', '2026-04-29 08:44:17'),
(6, 'Mr. Hồ Đức Phúc', 'Event Manager', 'Tetra Pak', 'https://randomuser.me/api/portraits/men/72.jpg', 'Town Hall Meeting kết hợp team building cực kỳ thành công. HueShow hiểu rõ nhu cầu của chúng tôi.', 5, 6, 'active', '2026-04-29 08:44:17');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `fullname`, `role`, `created_at`) VALUES
(1, 'Tranducvuwork@gmail.com', '$2y$10$46N4nmXlhGlebRHqds.rxuP67EAwuq8cHYrVI0sPMBhiU6UOSu/aK', 'tranducvuht@gmail.com', 'Tranducvuwork@gmail.com', 'admin', '2026-04-29 08:33:30'),
(2, 'Tranducvuwork1@gmail.com', '$2y$10$URFdAode49TmMTBEUTZga.vn5MkavYrn0tMrfiOlhpJuGBya0Xc/y', 'tranducvuht@gmail.com1', 'Trần Đức vũ1', 'admin', '2026-04-29 15:50:46');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
