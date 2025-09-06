-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 06, 2025 at 07:10 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `services_14040428`
--

-- --------------------------------------------------------

--
-- Table structure for table `awareness_source_table`
--

CREATE TABLE `awareness_source_table` (
  `id` int(11) NOT NULL,
  `fa_title` varchar(20) NOT NULL,
  `en_title` varchar(20) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `awareness_source_table`
--

INSERT INTO `awareness_source_table` (`id`, `fa_title`, `en_title`, `is_active`) VALUES
(1, 'پیشنهاد دوستان', 'Friends\' suggestion', 0),
(2, 'پیج اینستاگرام', 'Instagram page', 0),
(3, 'تبلیغ اینستاگرام', 'Instagram ads', 0),
(4, 'تبلیغات محیطی', 'Outdoor ads', 0),
(5, 'انتخاب تصادفی', 'Random selection', 0),
(6, 'سایر', 'Other', 0);

-- --------------------------------------------------------

--
-- Table structure for table `duration_table`
--

CREATE TABLE `duration_table` (
  `id` int(11) NOT NULL,
  `title` varchar(30) NOT NULL,
  `duration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `duration_table`
--

INSERT INTO `duration_table` (`id`, `title`, `duration`) VALUES
(1, 'تقریبا  نیم ساعت', 30),
(2, 'تقریبا یک ساعت', 60),
(3, 'تقریبا یک ساعت و نیم', 90),
(4, 'تقریبا دو ساعت', 120),
(5, 'تقریبا دو ساعت و نیم', 150),
(6, 'تقریبا سه ساعت', 180),
(7, 'تقریبا سه ساعت و نیم', 210),
(8, 'تقریبا چهار ساعت', 240),
(9, 'تقریبا چهار ساعت و نیم', 270),
(10, 'تقریبا پنج ساعت', 300);

-- --------------------------------------------------------

--
-- Table structure for table `employee_service_table`
--

CREATE TABLE `employee_service_table` (
  `id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `price` bigint(20) NOT NULL,
  `free_hour` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `estimated_duration` int(11) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `employee_service_table`
--

INSERT INTO `employee_service_table` (`id`, `service_id`, `user_id`, `price`, `free_hour`, `estimated_duration`, `deleted`) VALUES
(135, 10, 93, 2500, '2025-09-02 15:53:26', 1, 0),
(137, 16, 93, 4500, '2025-09-02 15:53:26', 3, 0),
(145, 11, 98, 222, '2025-09-02 16:17:35', 1, 0),
(146, 19, 98, 333, '2025-09-02 16:17:35', 5, 0);

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `level` varchar(20) NOT NULL,
  `message` text NOT NULL,
  `context` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`context`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_status_table`
--

CREATE TABLE `payment_status_table` (
  `id` int(11) NOT NULL,
  `code` varchar(50) DEFAULT NULL,
  `fa_title` varchar(50) DEFAULT NULL,
  `en_title` varchar(50) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `payment_status_table`
--

INSERT INTO `payment_status_table` (`id`, `code`, `fa_title`, `en_title`, `is_active`) VALUES
(1, 'unpaid', 'پرداخت نشده', 'Unpaid', 1),
(2, 'partially_paid', 'پیش‌پرداخت', 'Partially Paid', 1),
(3, 'fully_paid', 'پرداخت کامل', 'Fully Paid', 1),
(4, 'pending', 'در انتظار پرداخت', 'Pending Payment', 1),
(5, 'refunded', 'عودت داده شده', 'Refunded', 1),
(6, 'cancelled', 'لغو شده', 'Cancelled', 1),
(7, 'failed', 'ناموفق', 'Failed', 1),
(8, 'deferred', 'تسویه معوق', 'Deferred Payment', 1);

-- --------------------------------------------------------

--
-- Table structure for table `quality_score_table`
--

CREATE TABLE `quality_score_table` (
  `id` int(11) NOT NULL,
  `fa_name` varchar(50) NOT NULL,
  `en_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `quality_score_table`
--

INSERT INTO `quality_score_table` (`id`, `fa_name`, `en_name`) VALUES
(1, 'خیلی خوب', 'Excellent'),
(2, 'خوب', 'Good'),
(3, 'متوسط', 'Average'),
(4, 'بد', 'Bad'),
(5, 'خیلی بد', 'Very Bad');

-- --------------------------------------------------------

--
-- Table structure for table `service_table`
--

CREATE TABLE `service_table` (
  `id` int(11) NOT NULL,
  `service_key` varchar(50) NOT NULL,
  `fa_title` varchar(100) NOT NULL,
  `en_title` varchar(100) NOT NULL,
  `parent_id` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `service_table`
--

INSERT INTO `service_table` (`id`, `service_key`, `fa_title`, `en_title`, `parent_id`, `created_at`, `updated_at`, `deleted`) VALUES
(1, 'hair_services', 'خدمات مو', 'Hair Services', 0, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(2, 'eyebrow_lash', 'خدمات ابرو و مژه', 'Eyebrow & Eyelash Services', 0, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(3, 'nail_services', 'خدمات ناخن', 'Nail Services', 0, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(4, 'skin_care', 'خدمات پوست و صورت', 'Skin & Facial Care', 0, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(5, 'makeup', 'خدمات آرایش', 'Makeup Services', 0, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(6, 'repair', 'خدمات ترمیم و کاشت', 'Repair & Enhancement Services', 0, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(7, 'laser', 'خدمات لیزر و اپیلاسیون', 'Laser & Hair Removal', 0, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(8, 'massage', 'خدمات ماساژ و ریلکسیشن', 'Massage & Relaxation', 0, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(9, 'haircut', 'کوتاهی مو', 'Haircut', 1, '2025-06-20 10:50:29', '2025-07-08 11:58:54', 0),
(10, 'hair_coloring', 'رنگ مو', 'Hair Coloring', 1, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(11, 'highlight_bleach', 'هایلایت و بلیچ', 'Highlighting & Bleaching', 1, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(12, 'balayage_ombre', 'مش و بالیاژ', 'Balayage & Ombre', 1, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(13, 'perm_waving', 'تافت و فر مو', 'Perm & Hair Waving', 1, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(14, 'hair_straightening', 'صاف کردن مو', 'Hair Straightening', 1, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(15, 'keratin_treatment', 'کراتین مو', 'Keratin Treatment', 1, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(16, 'hair_botox', 'بوتاکس مو', 'Hair Botox', 1, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(17, 'hair_extensions', 'اکستنشن مو', 'Hair Extensions', 1, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(18, 'braiding', 'بافت مو', 'Braiding', 1, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(19, 'updo_styling', 'شینیون و آرایش مو', 'Updo & Styling', 1, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(20, 'hair_mask', 'ماسک مو', 'Hair Mask Treatment', 1, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(21, 'light_ombre_balayage', 'لایت + آمبره + بالیاژ', 'Light + Ombre + Balayage', 1, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(22, 'scalp_scrub', 'اسکراپ اسکالپ', 'Scalp Scrub', 1, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(23, 'eyebrow_shaping', 'طراحی ابرو', 'Eyebrow Shaping', 2, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(24, 'microblading', 'میکروبلیدینگ ابرو', 'Microblading', 2, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(25, 'eyebrow_tinting', 'رنگ ابرو', 'Eyebrow Tinting', 2, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(26, 'eyebrow_lamination', 'لمینیت ابرو', 'Eyebrow Lamination', 2, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(27, 'lash_extensions', 'اکستنشن مژه', 'Eyelash Extensions', 2, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(28, 'lash_lift', 'لیفت مژه', 'Eyelash Lift', 2, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(29, 'lash_tinting', 'رنگ مژه', 'Eyelash Tinting', 2, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(30, 'lash_extension_L', 'اکستنشن مژه L', 'Lash Extension - L Style', 2, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(31, 'spiky_lash', 'اکستنشن مژه اسپایکی', 'Spiky Lash Extensions', 2, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(32, 'colored_lash', 'اکستنشن مژه رنگی', 'Colored Lash Extensions', 2, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(33, 'mega_volume_lash', 'اکستنشن مژه مگاوالیوم', 'Mega Volume Lashes', 2, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(34, 'volume_lash', 'اکستنشن مژه والیوم', 'Volume Lashes', 2, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(35, 'classic_lash', 'اکستنشن مژه کلاسیک', 'Classic Lash Extensions', 2, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(36, 'lash_tinting2', 'بن مژه', 'Lash Tinting', 2, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(37, 'spiky_colored_L_refill', 'ترمیم مژه اسپایکی + رنگی + L', 'Spiky + Colored + L Lash Refill', 2, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(38, 'volume_lash_refill', 'ترمیم مژه والیوم', 'Volume Lash Refill', 2, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(39, 'classic_lash_refill', 'ترمیم مژه کلاسیک', 'Classic Lash Refill', 2, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(40, 'temp_lashes', 'مژه موقت معمولی', 'Temporary Lashes', 2, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(41, 'kylie_temp_lashes', 'مژه موقت کایلی', 'Kylie Temporary Lashes', 2, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(42, 'eyebrow_fibrosis', 'فیبروز ابرو', 'Eyebrow Fibrosis Treatment', 2, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(43, 'eyebrow_lift_botox', 'لیفت ابرو + بوتاکس', 'Eyebrow Lift + Botox', 2, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(44, 'manicure', 'مانیکور', 'Manicure', 3, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(45, 'pedicure', 'پدیکور', 'Pedicure', 3, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(46, 'gel_nails', 'ناخن ژله‌ای', 'Gel Nails', 3, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(47, 'acrylic_nails', 'ناخن اکریلیک', 'Acrylic Nails', 3, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(48, 'dip_powder', 'ناخن دیپاوکس', 'Dip Powder Nails', 3, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(49, 'nail_art', 'طراحی ناخن', 'Nail Art', 3, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(50, 'podology', 'پدولوژی', 'Podology - Advanced Foot Care', 3, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(51, 'pedicure_callus', 'پدیکور و کفسابی', 'Pedicure & Callus Removal', 3, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(52, 'polygel_repair', 'ترمیم پلی ژل', 'Polygel Nail Repair', 3, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(53, 'polygel_toe_repair', 'ترمیم پلی ژل پا', 'Polygel Toe Repair', 3, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(54, 'powder_dip_repair', 'ترمیم ناخن پودر ساده', 'Powder Dip Nail Repair', 3, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(55, 'peach_repair', 'ترمیم هلویی', 'Peach Nail Repair', 3, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(56, 'full_foot_repair', 'ترمیم کل پا ساده', 'Full Foot Basic Repair', 3, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(57, 'russian_gel', 'ژل پولیش روسی', 'Russian Gel Manicure', 3, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(58, 'nail_strengthening', 'استحکام‌سازی و حجیم‌سازی ناخن', 'Nail Strengthening & Thickening', 3, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(59, 'foot_filing', 'سوهان کشی پا', 'Foot Filing', 3, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(60, 'polygel_application', 'کاشت پلی ژل', 'Polygel Nail Application', 3, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(61, 'polygel_toe_application', 'کاشت پلی ژل پا', 'Polygel Toe Application', 3, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(62, 'basic_toe_extension', 'کاشت شصت پا ساده', 'Basic Toe Extension', 3, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(63, 'hand_lamination', 'کاشت لمینیت دست', 'Hand Lamination', 3, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(64, 'toe_lamination', 'کاشت لمینیت شصت پا', 'Toe Lamination', 3, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(65, 'full_foot_lamination', 'کاشت لمینیت کل پا', 'Full Foot Lamination', 3, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(66, 'basic_powder_dip', 'کاشت ناخن پودر ساده', 'Basic Powder Dip Nails', 3, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(67, 'peach_powder_dip', 'کاشت ناخن پودر هلویی', 'Peach Powder Dip Nails', 3, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(68, 'full_foot_basic', 'کاشت کل پا ساده', 'Full Foot Basic Application', 3, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(69, 'peach_powder_overlay', 'کاور ناخن پودر هلویی', 'Peach Powder Dip Overlay', 3, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(70, 'basic_powder_overlay', 'کاور ناخن پودر ساده', 'Basic Powder Dip Overlay', 3, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(71, 'facial_cleansing', 'پاکسازی پوست', 'Facial Cleansing', 4, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(72, 'microdermabrasion', 'میکرودرم ابریژن', 'Microdermabrasion', 4, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(73, 'thread_lift', 'لیفت صورت با نخ', 'Thread Lift', 4, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(74, 'facial_mask', 'ماسک صورت', 'Facial Mask', 4, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(75, 'skin_rejuvenation', 'جوانسازی پوست', 'Skin Rejuvenation', 4, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(76, 'hydrafacial', 'هایدرافیشیال', 'Hydrafacial', 4, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(77, 'mesotherapy', 'مزوتراپی صورت', 'Mesotherapy', 4, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(78, 'skin_treatments', 'پوست', 'Skin Treatments', 4, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(79, 'bridal_makeup', 'آرایش عروس', 'Bridal Makeup', 5, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(80, 'daily_makeup', 'آرایش روزانه', 'Daily Makeup', 5, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(81, 'evening_makeup', 'آرایش ویژه مراسم', 'Evening/Party Makeup', 5, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(82, 'permanent_makeup', 'آرایش دائم', 'Permanent Makeup', 5, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(83, 'contouring', 'کانتورینگ صورت', 'Contouring & Highlighting', 5, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(84, 'eyeliner', 'خط چشم', 'Eyeliner', 5, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(85, 'lip_liner', 'خط لب', 'Lip Liner', 5, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(86, 'tinting', 'رنگ', 'Tinting', 5, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(87, 'tattoo_removal', 'ریموو تتو', 'Tattoo Removal', 5, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(88, 'lip_shading', 'شیدینگ لب', 'Lip Shading', 5, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(89, 'basic_toe_repair', 'ترمیم شصت پا ساده', 'Basic Toe Repair', 6, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(90, 'fibrosis_repair', 'ترمیم فیبروز', 'Fibrosis Repair', 6, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(91, 'lamination_repair', 'ترمیم لمینیت', 'Lamination Repair', 6, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(92, 'toe_lamination_repair', 'ترمیم لمینیت شصت پا', 'Toe Lamination Repair', 6, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(93, 'full_foot_lamination_repair', 'ترمیم لمینیت کل پا', 'Full Foot Lamination Repair', 6, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(94, 'laser_hair_removal', 'لیزر موهای زائد', 'Laser Hair Removal', 7, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(95, 'waxing', 'اپیلاسیون با موم', 'Waxing', 7, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(96, 'threading', 'اپیلاسیون با نخ', 'Threading', 7, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(97, 'sugaring', 'اپیلاسیون شمع', 'Sugaring', 7, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(98, 'facial_massage', 'ماساژ صورت', 'Facial Massage', 8, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(99, 'body_massage', 'ماساژ بدن', 'Body Massage', 8, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(100, 'aromatherapy', 'ماساژ آروماتراپی', 'Aromatherapy Massage', 8, '2025-06-20 10:50:29', '2025-06-20 10:50:29', 0),
(104, 'kkk', 'سلام', 'salam', 0, '2025-09-03 10:23:06', '2025-09-03 10:23:06', 0),
(105, '', '', '', 0, '2025-09-03 11:11:19', '2025-09-03 11:11:19', 0),
(106, '', '', '', 0, '2025-09-03 12:21:10', '2025-09-03 12:21:10', 0),
(107, '', '22222222222222222222222222342wrergdfgdfgdfgdfgdfg22222222222222222222222222', '', 0, '2025-09-03 12:37:13', '2025-09-03 12:37:13', 0);

-- --------------------------------------------------------

--
-- Table structure for table `service_visit_relation_table`
--

CREATE TABLE `service_visit_relation_table` (
  `id` int(11) NOT NULL,
  `visit_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `price` bigint(20) NOT NULL,
  `initial_payment` bigint(20) NOT NULL,
  `payment_status` int(11) NOT NULL,
  `visit_status` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `service_visit_relation_table`
--

INSERT INTO `service_visit_relation_table` (`id`, `visit_id`, `service_id`, `price`, `initial_payment`, `payment_status`, `visit_status`, `employee_id`, `deleted`) VALUES
(32, 54, 82, 650, 100, 1, 1, 93, 0),
(33, 55, 82, 650, 100, 1, 1, 93, 0);

-- --------------------------------------------------------

--
-- Table structure for table `surveys_table`
--

CREATE TABLE `surveys_table` (
  `id` int(11) NOT NULL,
  `service_visit_relation_id` int(11) NOT NULL,
  `quality_score_id` int(11) NOT NULL,
  `behavior_score` int(11) NOT NULL,
  `onTime_score` int(11) NOT NULL,
  `tools_score` int(11) NOT NULL,
  `feedback_text` varchar(150) NOT NULL,
  `survey_datetime` datetime NOT NULL,
  `employee_id` int(11) NOT NULL,
  `register_datetime` datetime NOT NULL DEFAULT current_timestamp(),
  `link` varchar(100) NOT NULL,
  `submitted` tinyint(1) NOT NULL DEFAULT 0,
  `submit_datetime` datetime NOT NULL,
  `awareness_source_id` int(11) NOT NULL,
  `awareness_source_text` varchar(150) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_table`
--

CREATE TABLE `user_table` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `birth_date` datetime NOT NULL,
  `phone_number` varchar(11) NOT NULL,
  `register_datetime` datetime NOT NULL DEFAULT current_timestamp(),
  `user_type` int(11) NOT NULL,
  `password` varchar(200) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user_table`
--

INSERT INTO `user_table` (`id`, `first_name`, `last_name`, `birth_date`, `phone_number`, `register_datetime`, `user_type`, `password`, `is_active`, `deleted`) VALUES
(23, 'مدیر', 'محمدپور', '2005-05-04 00:00:00', '09928896128', '2025-07-02 09:44:51', 4, '$2y$10$YScAjNkNhGvpuYW7oT0m4ei3cFr0.wNtRWyEoXdYe.3P1EG1YVnL6', 1, 0),
(92, 'ستایش', 'اپراتور', '2025-07-09 00:00:00', '09217447961', '2025-07-08 09:08:47', 3, '$2y$10$Egp/gaMb2vMxnBtTAwEsPOcV.I0Wbzbv2Fom6rrft0drc2ZCGHFke', 1, 0),
(93, 'ستی کارمند', 'mp', '2025-07-25 00:00:00', '09217447980', '2025-07-08 09:10:22', 1, '$2y$10$wwUfQl1rXu0y0JsEQNUBFuJqMEt82hVyZ/XuWLyNWYViuMaavNyhC', 1, 0),
(94, 'مشتری', 'محمدی', '2005-05-06 00:00:00', '09928896125', '2025-07-08 10:22:27', 2, '$2y$10$8thUT7et3xy1Bg7T9DBvdeGa8C5WuIuH3YifRqBKBFWrp3M9RmObm', 1, 0),
(95, 'hamed', 'soltani', '2000-01-01 00:00:00', '09351602513', '2025-07-19 21:34:36', 4, '$2y$10$bxJmRFsLe1UuvoIdghBKTuGjSTvhEmBPxdklFaQJosSNf.Yn0KC1e', 1, 0),
(97, 'jhamed', 'soltan', '2000-01-01 00:00:00', '09351602514', '2025-07-19 21:47:56', 2, '$2y$10$VwNMZ4HmhutZvJFCrOflnuusP2AZyExrV/.DTEW9pp3.K5ulx3LAu', 0, 0),
(98, 'هومن', 'سیدی', '2000-01-01 00:00:00', '09928896129', '2025-07-20 09:28:10', 1, '$2y$10$1UqGNtu5z.gGf4BxXMxyJu5EU0TqDv71zpYTJdzZ1O1XKq3VEO9MS', 1, 1),
(99, 'hamed 55', 'soltanifar', '2000-01-01 00:00:00', '09351602515', '2025-07-25 21:22:52', 2, '$2y$10$s8NZAOWkPFcyKJEKQuvwR.eqUHYhw1.n.JaJiF0jRGW8U.Mpxs8l6', 1, 1),
(100, 'hamed', 'soltani', '2000-01-01 00:00:00', '09351602516', '2025-08-01 21:23:59', 2, '$2y$10$tyKnn.u.hmJytAeQG5zOMu.ye58ypy8Bb8qxgMq6HyaLqed8qglMC', 0, 0),
(103, 'ghasem', 'soltan', '0000-00-00 00:00:00', '09351602519', '2025-08-26 09:27:19', 2, '$2y$10$R/dAfo2iodl//enJ46JybeJF1XpZVBANEncwJG4htboGjl8UmjC8q', 1, 0),
(104, 'مجتبی', 'فnbvg', '0000-00-00 00:00:00', '09351602529', '2025-08-28 16:18:25', 2, '$2y$10$T12.8hS/yySgjvumGSyH1eqTJCigTInD6uTo82j6K922fjK2XsYVW', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_type_table`
--

CREATE TABLE `user_type_table` (
  `id` int(11) NOT NULL,
  `title` varchar(10) NOT NULL,
  `en_title` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user_type_table`
--

INSERT INTO `user_type_table` (`id`, `title`, `en_title`) VALUES
(1, 'کارمند', 'employee'),
(2, 'مشتری', 'customer'),
(3, 'اپراتور', 'operator'),
(4, 'مدیر', 'admin'),
(5, 'سیستم', 'system');

-- --------------------------------------------------------

--
-- Table structure for table `visit_status_table`
--

CREATE TABLE `visit_status_table` (
  `id` int(11) NOT NULL,
  `fa_title` varchar(50) NOT NULL,
  `en_title` varchar(50) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `visit_status_table`
--

INSERT INTO `visit_status_table` (`id`, `fa_title`, `en_title`, `is_active`) VALUES
(1, 'رزرو شده', 'Booked', 1),
(2, 'تأیید شده', 'Confirmed', 1),
(3, 'لغو شده', 'Cancelled', 1),
(4, 'در حال انجام', 'In Progress', 1),
(5, 'انجام شده', 'Completed', 1),
(6, 'عدم حضور', 'No-Show', 1),
(7, 'موکول شده', 'Postponed', 1);

-- --------------------------------------------------------

--
-- Table structure for table `visit_table`
--

CREATE TABLE `visit_table` (
  `id` int(11) NOT NULL,
  `registrant_user_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `visit_datetime` datetime NOT NULL,
  `register_datetime` datetime NOT NULL,
  `note` text NOT NULL,
  `deleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `visit_table`
--

INSERT INTO `visit_table` (`id`, `registrant_user_id`, `customer_id`, `visit_datetime`, `register_datetime`, `note`, `deleted`) VALUES
(54, 94, 94, '2025-07-07 12:00:00', '2025-07-08 13:59:06', '', 0),
(55, 94, 94, '2025-07-08 12:00:00', '2025-07-08 14:03:07', '', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `awareness_source_table`
--
ALTER TABLE `awareness_source_table`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `duration_table`
--
ALTER TABLE `duration_table`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_service_table`
--
ALTER TABLE `employee_service_table`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_key` (`user_id`),
  ADD KEY `service_table_key` (`service_id`),
  ADD KEY `duration_key` (`estimated_duration`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_status_table`
--
ALTER TABLE `payment_status_table`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `quality_score_table`
--
ALTER TABLE `quality_score_table`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_table`
--
ALTER TABLE `service_table`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_visit_relation_table`
--
ALTER TABLE `service_visit_relation_table`
  ADD PRIMARY KEY (`id`),
  ADD KEY `visit_key` (`visit_id`),
  ADD KEY `service_key` (`service_id`),
  ADD KEY `visit_status_key` (`visit_status`),
  ADD KEY `payment_status_key` (`payment_status`),
  ADD KEY `employee_key` (`employee_id`);

--
-- Indexes for table `surveys_table`
--
ALTER TABLE `surveys_table`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_visit_relation_key` (`service_visit_relation_id`),
  ADD KEY `employee_survay_id` (`employee_id`),
  ADD KEY `quality_score_id_key` (`quality_score_id`);

--
-- Indexes for table `user_table`
--
ALTER TABLE `user_table`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone_number` (`phone_number`),
  ADD KEY `userType` (`user_type`);

--
-- Indexes for table `user_type_table`
--
ALTER TABLE `user_type_table`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `visit_status_table`
--
ALTER TABLE `visit_status_table`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `visit_table`
--
ALTER TABLE `visit_table`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_key_k2` (`customer_id`),
  ADD KEY `registrant_user_id_key` (`registrant_user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `awareness_source_table`
--
ALTER TABLE `awareness_source_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `duration_table`
--
ALTER TABLE `duration_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `employee_service_table`
--
ALTER TABLE `employee_service_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=147;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_status_table`
--
ALTER TABLE `payment_status_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `quality_score_table`
--
ALTER TABLE `quality_score_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `service_table`
--
ALTER TABLE `service_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT for table `service_visit_relation_table`
--
ALTER TABLE `service_visit_relation_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `surveys_table`
--
ALTER TABLE `surveys_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_table`
--
ALTER TABLE `user_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT for table `user_type_table`
--
ALTER TABLE `user_type_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `visit_status_table`
--
ALTER TABLE `visit_status_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `visit_table`
--
ALTER TABLE `visit_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `employee_service_table`
--
ALTER TABLE `employee_service_table`
  ADD CONSTRAINT `duration_key` FOREIGN KEY (`estimated_duration`) REFERENCES `duration_table` (`id`),
  ADD CONSTRAINT `service_table_key` FOREIGN KEY (`service_id`) REFERENCES `service_table` (`id`),
  ADD CONSTRAINT `user_key` FOREIGN KEY (`user_id`) REFERENCES `user_table` (`id`);

--
-- Constraints for table `service_visit_relation_table`
--
ALTER TABLE `service_visit_relation_table`
  ADD CONSTRAINT `employee_key` FOREIGN KEY (`employee_id`) REFERENCES `user_table` (`id`),
  ADD CONSTRAINT `payment_status_key` FOREIGN KEY (`payment_status`) REFERENCES `payment_status_table` (`id`),
  ADD CONSTRAINT `service_key` FOREIGN KEY (`service_id`) REFERENCES `service_table` (`id`),
  ADD CONSTRAINT `visit_key` FOREIGN KEY (`visit_id`) REFERENCES `visit_table` (`id`),
  ADD CONSTRAINT `visit_status_key` FOREIGN KEY (`visit_status`) REFERENCES `visit_status_table` (`id`);

--
-- Constraints for table `surveys_table`
--
ALTER TABLE `surveys_table`
  ADD CONSTRAINT `employee_survay_id` FOREIGN KEY (`employee_id`) REFERENCES `user_table` (`id`),
  ADD CONSTRAINT `quality_score_id_key` FOREIGN KEY (`quality_score_id`) REFERENCES `quality_score_table` (`id`),
  ADD CONSTRAINT `service_visit_relation_key` FOREIGN KEY (`service_visit_relation_id`) REFERENCES `service_visit_relation_table` (`id`);

--
-- Constraints for table `user_table`
--
ALTER TABLE `user_table`
  ADD CONSTRAINT `user_type_key` FOREIGN KEY (`user_type`) REFERENCES `user_type_table` (`id`);

--
-- Constraints for table `visit_table`
--
ALTER TABLE `visit_table`
  ADD CONSTRAINT `registrant_user_id_key` FOREIGN KEY (`registrant_user_id`) REFERENCES `user_table` (`id`),
  ADD CONSTRAINT `user_key_k2` FOREIGN KEY (`customer_id`) REFERENCES `user_table` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
