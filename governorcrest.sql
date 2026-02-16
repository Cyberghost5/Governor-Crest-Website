-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 16, 2026 at 04:49 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `governorcrest`
--

-- --------------------------------------------------------

--
-- Table structure for table `about_content`
--

CREATE TABLE `about_content` (
  `id` int NOT NULL,
  `section` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `content` text COLLATE utf8mb4_general_ci NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `about_content`
--

INSERT INTO `about_content` (`id`, `section`, `title`, `content`, `updated_at`) VALUES
(1, 'who_we_are', 'Who We Are', 'Governor Crest Limited is a dynamic multi-sector company headquartered in Bauchi State, Nigeria. Founded in 2023, we have quickly established ourselves as a trusted partner across various industries including real estate, automotive sales, agriculture, grooming services, logistics, and fashion. Our diverse portfolio allows us to serve our community comprehensively, providing quality solutions that enhance everyday life.', '2025-11-25 16:27:45'),
(2, 'mission', 'Our Mission', 'To deliver innovative, reliable, and affordable solutions across multiple sectors, enhancing the quality of life for individuals and businesses alike. We strive to be the preferred choice by combining integrity, excellence, and customer-centric service in everything we do.', '2025-11-25 16:27:45'),
(3, 'vision', 'Our Vision', 'To become Nigeria\'s leading diversified conglomerate, recognized for excellence, innovation, and positive impact across all sectors we serve. We envision a future where Governor Crest Limited is synonymous with quality, trust, and infinite possibilities for growth and success.', '2025-11-25 16:27:45');

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int NOT NULL,
  `username` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password`, `email`, `created_at`) VALUES
(1, 'admin', '$2y$10$JmHj4nVD9Ro.vag2lA5hweJSBe5ExheYFDKGDv1ishsvHvlbGCY3m', 'admin@governorcrest.com', '2025-11-25 16:27:45');

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `service` varchar(150) DEFAULT NULL,
  `preferred_date` date DEFAULT NULL,
  `preferred_time` varchar(50) DEFAULT NULL,
  `message` text,
  `status` enum('pending','confirmed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `full_name`, `email`, `phone`, `service`, `preferred_date`, `preferred_time`, `message`, `status`, `created_at`) VALUES
(1, 'Adebisi Covenant', 'adebisicovenant01@gmail.com', '+2349031704109', 'Land & Property', '2026-02-07', '14:32', 'I want to see you sir.', 'cancelled', '2026-02-06 12:31:34');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int NOT NULL,
  `full_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_general_ci NOT NULL,
  `status` enum('unread','read','replied') COLLATE utf8mb4_general_ci DEFAULT 'unread',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `full_name`, `email`, `phone`, `message`, `status`, `created_at`) VALUES
(1, 'John Loh', 'startcardsforyou@gmail.com', '3604580863', 'Hi,\r\n\r\nI saw you recently launched governorcrestlimited.com.\r\n\r\nI run an email marketing and outreach agency. I\'m starting a small group where website owners refer customers to each other to help each other grow.\r\n\r\nThe goal is simple: we form a group of a few complementary businesses so everyone can cross-refer customers to each other.\r\n\r\nWorth exploring? Happy to send you more information.\r\n\r\nRegards,\r\nJohn Loh', 'unread', '2025-11-26 11:24:36'),
(2, 'Anaya Prajapati', 'anaya.dgtlsolution@gmail.com', '7042514198', 'Hi http://governorcrestlimited.com,\r\n\r\nIf you’re looking to boost your website’s visibility, I can help you achieve top Google rankings.\r\n\r\nI’ll prepare a complete SEO plan with actionable steps and potential growth insights for your products or services.\r\n\r\nOnce you share your Keywords and target locations, I’ll send a full proposal.\r\n\r\nBest Regards,\r\nAnaya\r\nOnline SEO Consultant', 'unread', '2025-11-26 11:33:06'),
(4, 'Gemma Marshall', 'gemmamarshall811@gmail.com', '381981876', 'Hi there,\r\n\r\nWe run an Instagram growth service, which increases your number of followers both safely and practically. \r\n\r\n- Real, human followers: People follow you because they are interested in your business or niche.\r\n- Safe: All actions are made manually. We do not use any bots.\r\n- The price is from just $60 per month, and we can start immediately.\r\n\r\nIf you\'d like to see some of our previous work, let me know, and we can discuss it further.\r\n\r\nKind Regards,\r\nGemma', 'unread', '2025-11-26 19:54:53'),
(5, 'Deepak Parcha', 'parchad78@gmail.com', '9217127210', 'Hello http://governorcrestlimited.com,\r\nHope you\'re doing well?\r\n\r\nWe create high-quality, user-friendly websites that help businesses grow their online presence and generate more leads.\r\n\r\nCould you share the type of website you need and your target audience? I’ll guide you on how we can help.\r\n\r\nIf you’re interested, please feel free to share your WhatsApp number or refference website.\r\n\r\nBest regards,\r\nDeepak parcha', 'unread', '2025-11-26 21:38:22'),
(6, 'Brianna Belton', 'briannawebsolution@gmail.com', '1201201200', '\"Hi,\r\n\r\nI visited your website online and discovered that it was not showing up in any search results for the majority of keywords related to your company on Google, Yahoo, or Bing.\r\n\r\nDo you want more targeted visitors on your website?  We can place your website on Google’s 1st Page. yahoo, AOL, Bing. Etc.\r\n\r\nIf interested, kindly provide me your name, phone number, and email.\r\n \r\nRegards, \r\nBrianna Belton\"\r\n', 'unread', '2025-11-27 05:31:49'),
(7, 'Woodrow Lafountain', 'contact@search-registry.pro', '690829766', 'Add your governorcrestlimited.com website to Google Search Index so that it is listed in Web Search Results.\r\n\r\nSubmit governorcrestlimited.com at https://searchregister.org', 'unread', '2025-11-27 07:27:22'),
(8, 'Joanna Riggs', 'joannariggs278@gmail.com', '228317137', 'Hi,\r\n\r\nI just visited governorcrestlimited.com and wondered if you\'ve ever considered an impactful video to advertise your business? Our videos can generate impressive results on both your website and across social media.\r\n\r\nOur prices start from just $195 (USD).\r\n\r\nLet me know if you\'re interested in seeing samples of our previous work.\r\n\r\nRegards,\r\nJoanna\r\n\r\nUnsubscribe: https://unsubscribe.video/unsubscribe.php?d=governorcrestlimited.com', 'unread', '2025-11-27 11:45:31'),
(9, 'Quinn Vinson', 'join@simplyseo.pro', '6305863840', 'Hello,\r\n\r\nAdd governorcrestlimited.com website to SEODIRECTORY fort a better position in Web Search results order and to get an improvement in traffic:\r\n\r\n https://seodir.pro', 'unread', '2025-11-28 20:15:13'),
(10, 'Lucy Hardee', 'contact@domainreg.pro', '95090234', 'Add your governorcrestlimited.com website in Google Search Index to have it displayed in Web Search Results.\r\n\r\nRegister governorcrestlimited.com at https://searchregister.info', 'unread', '2025-12-07 20:36:32'),
(11, 'Emma Wilson', 'emma.wilson6162@gmail.com', '7021800568', 'Hi there,\r\n\r\nWe reach a large global audience of over 30 million and we\'re reaching out to ask if you\'d be interested in us promoting governorcrestlimited.com?\r\n\r\nWe also offer local promotion.\r\n\r\nIf growing your audience is a priority right now, which of these is more relevant to your current goals — local or global?\r\n\r\nIf you would like further information just get back in touch.\r\n\r\nKind Regards,\r\nEmma', 'unread', '2025-12-11 11:50:52'),
(12, 'Parthenia Langdon', 'info@domainsubmlt.info', '681950082', 'List governorcrestlimited.com in Google Search Index and it will be displayed in WebSearch Results.\r\n\r\nAdd governorcrestlimited.com now at https://searchregister.org', 'unread', '2025-12-17 17:57:48'),
(13, 'Andrewunemn', 'no.reply.NathanRouxson@gmail.com', '84436394118', 'Hi-ya! governorcrestlimited.com \r\n \r\nDid you know that it is possible to send business proposal wholly legit? \r\nWhen such proposals are sent, no personal data is used, and messages are sent to forms specifically designed to receive messages and appeals securely. By using Communication Forms, messages are more likely to be seen as important, which reduces the chance of them being marked as spam. \r\nCome and give our service a try – it’s free! \r\nYou can trust us to send up to 50,000 messages. \r\n \r\nThe cost of sending one million messages is $59. \r\n \r\nThis message was automatically generated. \r\n \r\nContact us. \r\nTelegram - https://t.me/FeedbackFormEU \r\nWhatsApp - +375259112693 \r\nWhatsApp  https://wa.me/+375259112693 \r\nWe only use chat for communication.', 'unread', '2025-12-23 06:50:58'),
(14, 'Joanna Holden', 'joannaholden1981@gmail.com', '7874275363', 'Hi there,\r\n\r\nWe run a Youtube growth service, where we can increase your subscriber count safely and practically. \r\n\r\n- Guaranteed: We guarantee to gain you 400+ new subscribers each month.\r\n- Real, human subscribers who subscribe because they are interested in your channel/videos.\r\n- Safe: All actions are done, without using any automated tasks / bots.\r\n\r\nOur price is just $90 (USD) per month and we can start immediately.\r\n\r\nIf you are interested then we can discuss further.\r\n\r\nKind Regards,\r\nJoanna', 'unread', '2025-12-25 18:01:10'),
(15, 'Mike Giinter Richard\r\n', 'mike@monkeydigital.co', '81345127396', 'Hi, \r\n \r\nSearch is changing faster than most businesses realize. \r\n \r\nMore buyers are now discovering products and services through AI-driven platforms — not only traditional search results. This is why we created the AI Rankings SEO Plan at Monkey Digital. \r\n \r\nIt’s designed to help websites become clear, trusted, and discoverable by AI systems that increasingly influence how people find and choose businesses. \r\n \r\nYou can view the plan here: \r\nhttps://www.monkeydigital.co/ai-rankings/ \r\n \r\nIf you’d like to see whether this approach makes sense for your site, feel free to reach out directly — even a quick question is fine. Whatsapp: https://wa.link/b87jor \r\n \r\n \r\n \r\nBest regards, \r\nMike Giinter Richard\r\n \r\nMonkey Digital \r\nmike@monkeydigital.co \r\nPhone/Whatsapp: +1 (775) 314-7914', 'unread', '2026-01-03 05:38:38'),
(16, 'Mike Christophe Schneider\r\n', 'info@digital-x-press.com', '88914269695', 'Hi, \r\nI recognize that most website owners struggle understanding that SEO is a continuous effort and a carefully organized regular commitment. \r\n \r\nUnfortunately, very few website owners have the dedication to recognize the progressive yet impactful results that can completely change their digital visibility. \r\n \r\nWith constant algorithm changes, a reliable, long-term strategy including Answer Engine Optimization (AEO) is critical for getting a strong return on investment. \r\n \r\nIf you see this as the best method, partner with us! \r\n \r\nDiscover Our Monthly SEO Services https://www.digital-x-press.com/unbeatable-seo/ \r\n \r\nTalk to Us on Instant Messaging https://www.digital-x-press.com/whatsapp-us/ \r\n \r\nWe provide remarkable performance for your resources, and you will appreciate choosing us as your SEO partner. \r\n \r\nWarm regards, \r\nDigital X SEO Experts \r\nPhone/WhatsApp: +1 (844) 754-1148', 'unread', '2026-01-10 10:46:31'),
(17, 'Mike Tomas Williams\r\n', 'info@professionalseocleanup.com', '84542226927', 'Hi, \r\nWhile reviewing governorcrestlimited.com, we spotted toxic backlinks that could put your site at risk of a Google penalty. Especially that this Google SPAM update had a high impact in ranks. This is an easy and quick fix for you. Totally free of charge. No obligations. \r\n \r\nFix it now: \r\nhttps://www.professionalseocleanup.com/ \r\n \r\nNeed help or questions? Chat here: \r\nhttps://www.professionalseocleanup.com/whatsapp/ \r\n \r\nBest, \r\nMike Tomas Williams\r\n \r\n+1 (855) 221-7591 \r\ninfo@professionalseocleanup.com', 'unread', '2026-01-31 00:30:21'),
(18, 'Mike Oliver Claes\r\n', 'mike@monkeydigital.co', '88435793158', 'Hi, \r\n \r\nSearch is changing faster than most businesses realize. \r\n \r\nMore buyers are now discovering products and services through AI-driven platforms — not only traditional search results. This is why we created the AI Rankings SEO Plan at Monkey Digital. \r\n \r\nIt’s designed to help websites become clear, trusted, and discoverable by AI systems that increasingly influence how people find and choose businesses. \r\n \r\nYou can view the plan here: \r\nhttps://www.monkeydigital.co/ai-rankings/ \r\n \r\nIf you’d like to see whether this approach makes sense for your site, feel free to reach out directly — even a quick question is fine. Whatsapp: https://wa.link/b87jor \r\n \r\n \r\n \r\nBest regards, \r\nMike Oliver Claes\r\n \r\nMonkey Digital \r\nmike@monkeydigital.co \r\nPhone/Whatsapp: +1 (775) 314-7914', 'unread', '2026-01-31 09:33:32'),
(19, 'Michalak Aleksandra', 'aleksandramichalakalek51@gmail.com', '81255992449', 'Good day. \r\nMy name is Michalak Aleksandra, a Poland based business consultant. \r\nRunning a business means juggling a million things, and getting the funding you need shouldn\'t be another hurdle. We\'ve helped businesses to secure debt financing for growth, inventory, or operations, without the typical bank delays. \r\nTogether with our partners (investors), we offer a straightforward, transparent process with clear terms, designed to get you funded quickly so you can focus on your business. \r\nReady to explore our services? Please feel free to contact me directly by email: michalakaleksandrama@gmail.com Let\'s make your business goals a reality, together. \r\nRegards, \r\nMichalak Aleksandra. \r\nEmail:michalakaleksandrama@gmail.com', 'read', '2026-02-05 22:04:07');

-- --------------------------------------------------------

--
-- Table structure for table `email_logs`
--

CREATE TABLE `email_logs` (
  `id` int NOT NULL,
  `appointment_id` int DEFAULT NULL,
  `to_email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `body` mediumtext,
  `method` varchar(50) DEFAULT NULL,
  `success` tinyint(1) DEFAULT '0',
  `error_message` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `email_logs`
--

INSERT INTO `email_logs` (`id`, `appointment_id`, `to_email`, `subject`, `body`, `method`, `success`, `error_message`, `created_at`) VALUES
(1, 1, 'adebisicovenant01@gmail.com', 'Governor Crest Limited - Your appointment has been confirmed', '<html><body><p>Dear Adebisi Covenant,</p><p>Your appointment request has been <strong>confirmed</strong> by Governor Crest Limited.</p><p><strong>Appointment Details</strong><br>Date: 2026-02-07<br>Time: 14:32<br>Location: No. 15. Ibnu Plaza Before NDIC Office, Bank Road Bauchi, Bauchi State, Nigeria.</p><p><strong>Your notes:</strong><br>I want to see you sir.</p><p>If you have any questions, reply to this email or call us at +234 814 771 4474</p><p>Regards,<br>Governor Crest Limited</p></body></html>', 'phpmailer', 1, '', '2026-02-06 13:28:40'),
(2, 1, 'adebisicovenant01@gmail.com', 'Governor Crest Limited - Appointment notification', '<html><body><p>Dear Adebisi Covenant,</p><p>This is a resend of your appointment notification. Current status: <strong>confirmed</strong>.</p><p><strong>Appointment Details</strong><br>Date: 2026-02-07<br>Time: 14:32<br>Location: No. 15. Ibnu Plaza Before NDIC Office, Bank Road Bauchi, Bauchi State, Nigeria.</p></body></html>', 'phpmailer', 1, '', '2026-02-06 13:28:42'),
(3, 1, 'adebisicovenant01@gmail.com', 'Governor Crest Limited - Your appointment has been cancelled', '<!DOCTYPE html>\n<html lang=\"en\">\n<head>\n    <meta charset=\"utf-8\">\n    <meta name=\"viewport\" content=\"width=device-width,initial-scale=1\">\n    <title>Governor Crest Limited - Appointment Notification</title>\n    <style>body{font-family:Inter,Arial,Helvetica,sans-serif;color:#222} .card{background:#fff;border-radius:8px;padding:20px;border:1px solid #eee} .muted{color:#666;font-size:14px}</style>\n</head>\n<body>\n    <div class=\"card\">\n        <h2 style=\"color:#f0a500;margin-top:0\">Governor Crest Limited</h2>\n        <p class=\"muted\">Dear Adebisi Covenant,</p>\n        <p>Your appointment has been <strong>Cancelled</strong>.</p>\n\n        <h4>Appointment Details</h4>\n        <p class=\"muted\">\n            Date: 2026-02-07<br>\n            Time: 14:32<br>            Location: No. 15. Ibnu Plaza Before NDIC Office, Bank Road Bauchi, Bauchi State, Nigeria.        </p>\n\n                    <h5>Notes</h5>\n            <p class=\"muted\">I want to see you sir.</p>\n        \n        <p class=\"muted\">If you have questions, reply to this email or call us at +234 814 771 4474.</p>\n        <p>Regards,<br>Governor Crest Limited</p>\n    </div>\n</body>\n</html>', 'phpmailer', 1, '', '2026-02-06 13:34:52'),
(4, 1, 'adebisicovenant01@gmail.com', 'Governor Crest Limited - Appointment notification', '<!DOCTYPE html>\n<html lang=\"en\">\n<head>\n    <meta charset=\"utf-8\">\n    <meta name=\"viewport\" content=\"width=device-width,initial-scale=1\">\n    <title>Governor Crest Limited - Appointment Notification</title>\n    <style>body{font-family:Inter,Arial,Helvetica,sans-serif;color:#222} .card{background:#fff;border-radius:8px;padding:20px;border:1px solid #eee} .muted{color:#666;font-size:14px}</style>\n</head>\n<body>\n    <div class=\"card\">\n        <h2 style=\"color:#f0a500;margin-top:0\">Governor Crest Limited</h2>\n        <p class=\"muted\">Dear Adebisi Covenant,</p>\n        <p>Your appointment has been <strong>Cancelled</strong>.</p>\n\n        <h4>Appointment Details</h4>\n        <p class=\"muted\">\n            Date: 2026-02-07<br>\n            Time: 14:32<br>            Location: No. 15. Ibnu Plaza Before NDIC Office, Bank Road Bauchi, Bauchi State, Nigeria.        </p>\n\n                    <h5>Notes</h5>\n            <p class=\"muted\">I want to see you sir.</p>\n        \n        <p class=\"muted\">If you have questions, reply to this email or call us at +234 814 771 4474.</p>\n        <p>Regards,<br>Governor Crest Limited</p>\n    </div>\n</body>\n</html>', 'phpmailer', 1, '', '2026-02-06 13:36:11'),
(5, 1, 'adebisicovenant01@gmail.com', 'Governor Crest Limited - Appointment notification', '<!DOCTYPE html>\r\n<html lang=\"en\">\r\n<head>\r\n    <meta charset=\"utf-8\">\r\n    <meta name=\"viewport\" content=\"width=device-width,initial-scale=1\">\r\n    <title>Governor Crest Limited - Appointment Notification</title>\r\n    <style>body{font-family:Inter,Arial,Helvetica,sans-serif;color:#222} .card{background:#fff;border-radius:8px;padding:20px;border:1px solid #eee} .muted{color:#666;font-size:14px}</style>\r\n</head>\r\n<body>\r\n    <div class=\"card\">\r\n        <h2 style=\"color:#f0a500;margin-top:0\">Governor Crest Limited</h2>\r\n        <p class=\"muted\">Dear Adebisi Covenant,</p>\r\n        <p>Your appointment has been <strong>Cancelled</strong>.</p>\r\n\r\n        <h4>Appointment Details</h4>\r\n        <p class=\"muted\">\r\n            Date: 2026-02-07<br>\r\n            Time: 14:32<br>            Location: No. 15. Ibnu Plaza Before NDIC Office, Bank Road Bauchi, Bauchi State, Nigeria.        </p>\r\n\r\n                    <h5>Notes</h5>\r\n            <p class=\"muted\">I want to see you sir.</p>\r\n        \r\n        <p class=\"muted\">If you have questions, reply to this email or call us at +234 814 771 4474.</p>\r\n        <p>Regards,<br>Governor Crest Limited</p>\r\n    </div>\r\n</body>\r\n</html>', 'phpmailer', 1, '', '2026-02-06 13:37:57');

-- --------------------------------------------------------

--
-- Table structure for table `old_services`
--

CREATE TABLE `old_services` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `icon` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci NOT NULL,
  `features` text COLLATE utf8mb4_general_ci NOT NULL,
  `image_url` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `display_order` int DEFAULT '0',
  `status` enum('active','inactive') COLLATE utf8mb4_general_ci DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `old_services`
--

INSERT INTO `old_services` (`id`, `name`, `icon`, `description`, `features`, `image_url`, `display_order`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Land & Property', 'bi-buildings', 'Comprehensive real estate solutions for residential and commercial needs', 'Property sales and leasing\nReal estate development\nLand acquisition and documentation\nProperty management services\nInvestment consultation', 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=800', 1, 'active', '2025-11-25 16:27:45', '2025-11-25 18:39:39'),
(2, 'Car Sales', 'bi-car-front', 'Quality vehicles at competitive prices with exceptional customer service', 'New and pre-owned vehicles\nCompetitive pricing and financing options\nWide selection of brands and models\nVehicle inspection and certification\nAfter-sales support', 'https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?w=800', 2, 'active', '2025-11-25 16:27:45', '2025-11-25 16:27:45'),
(3, 'Agriculture', 'bi-brightness-high', 'Modern farming solutions and agricultural business services', 'Commercial farming operations\nProduce distribution and supply\nAgricultural consulting\nFarm management services\nAgri-business solutions', 'https://images.unsplash.com/photo-1625246333195-78d9c38ad449?w=800', 3, 'active', '2025-11-25 16:27:45', '2025-11-25 16:27:45'),
(4, 'Unisex Salon', 'bi-scissors', 'Modern grooming and beauty care services for everyone', 'Professional haircuts and styling\nBeauty treatments and spa services\nModern grooming techniques\nExperienced stylists and beauticians\nWelcoming environment for all', 'https://images.unsplash.com/photo-1560066984-138dadb4c035?w=800', 4, 'active', '2025-11-25 16:27:45', '2025-11-25 16:27:45'),
(5, 'Logistics', 'bi-truck', 'Reliable transportation and delivery services across Nigeria', 'Freight and cargo transportation\nFast and reliable delivery services\nFleet management\nWarehousing solutions\nLast-mile delivery', 'https://images.unsplash.com/photo-1566576721346-d4a3b4eaeb55?w=800', 5, 'active', '2025-11-25 16:27:45', '2025-11-25 16:27:45'),
(6, 'Clothing', 'bi-bag', 'Stylish and affordable fashion for the modern Nigerian', 'Men\'s and women\'s fashion\nTraditional and contemporary styles\nQuality fabrics and materials\nAffordable pricing\nCustom tailoring services', 'https://images.unsplash.com/photo-1489987707025-afc232f7ea0f?w=800', 6, 'active', '2025-11-25 16:27:45', '2025-11-25 16:27:45');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci NOT NULL,
  `image_url` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `category` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_general_ci DEFAULT 'active',
  `display_order` int DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `icon` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci NOT NULL,
  `features` text COLLATE utf8mb4_general_ci NOT NULL,
  `image_url` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `display_order` int DEFAULT '0',
  `status` enum('active','inactive') COLLATE utf8mb4_general_ci DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `icon`, `description`, `features`, `image_url`, `display_order`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Land & Property', 'bi-buildings', 'Comprehensive real estate solutions for residential and commercial needs', 'Property sales and leasing\nReal estate development\nLand acquisition and documentation\nProperty management services\nInvestment consultation', 'images/lands.png', 1, 'active', '2025-11-25 16:27:45', '2025-11-26 12:53:30'),
(2, 'Car Sales', 'bi-car-front', 'Quality vehicles at competitive prices with exceptional customer service', 'New and pre-owned vehicles\nCompetitive pricing and financing options\nWide selection of brands and models\nVehicle inspection and certification\nAfter-sales support', 'images/cars.png', 2, 'active', '2025-11-25 16:27:45', '2025-11-26 12:53:39'),
(3, 'Agriculture', 'bi-brightness-high', 'Modern farming solutions and agricultural business services', 'Commercial farming operations\nProduce distribution and supply\nAgricultural consulting\nFarm management services\nAgri-business solutions', 'images/agric.png', 3, 'active', '2025-11-25 16:27:45', '2025-11-26 12:53:48'),
(4, 'Unisex Salon', 'bi-scissors', 'Modern grooming and beauty care services for everyone', 'Professional haircuts and styling\nBeauty treatments and spa services\nModern grooming techniques\nExperienced stylists and beauticians\nWelcoming environment for all', 'images/salon.png', 4, 'active', '2025-11-25 16:27:45', '2025-11-26 12:53:54'),
(5, 'Logistics', 'bi-truck', 'Reliable transportation and delivery services across Nigeria', 'Freight and cargo transportation\nFast and reliable delivery services\nFleet management\nWarehousing solutions\nLast-mile delivery', 'images/logistics.png', 5, 'active', '2025-11-25 16:27:45', '2025-11-26 12:54:07'),
(6, 'Clothing', 'bi-bag', 'Stylish and affordable fashion for the modern Nigerian', 'Men\'s and women\'s fashion\nTraditional and contemporary styles\nQuality fabrics and materials\nAffordable pricing\nCustom tailoring services', 'images/fashion.png', 6, 'active', '2025-11-25 16:27:45', '2025-11-26 12:54:13');

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--

CREATE TABLE `site_settings` (
  `id` int NOT NULL,
  `setting_key` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `setting_value` text COLLATE utf8mb4_general_ci NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `site_settings`
--

INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `updated_at`) VALUES
(1, 'company_name', 'Governor Crest Limited', '2025-11-25 16:27:45'),
(2, 'tagline', 'One Crest, Infinite Possibilities', '2025-11-25 16:27:45'),
(3, 'description', 'A multi-sector company driven by innovation and integrity', '2025-11-25 16:27:45'),
(4, 'email', 'governorcrest@gmail.com', '2025-11-25 16:41:07'),
(5, 'phone', '+234 814 771 4474', '2025-11-25 17:24:08'),
(6, 'address', 'No. 15. Ibnu Plaza Before NDIC Office, Bank Road Bauchi, Bauchi State, Nigeria.', '2026-02-06 10:49:08'),
(7, 'facebook', 'https://www.facebook.com/governorcrest/', '2025-11-25 17:24:08'),
(8, 'twitter', '#', '2025-11-25 16:27:45'),
(9, 'instagram', 'https://www.instagram.com/governor_crest_ltd/', '2025-11-25 17:24:08'),
(10, 'linkedin', 'https://www.linkedin.com/company/governor-crest/', '2025-11-25 17:24:08'),
(11, 'db_password', '?(dRyRrx!n1I', '2025-11-25 16:36:13'),
(12, 'db_username', 'governor_crest', '2025-11-25 16:36:13'),
(13, 'db_database', 'governor_crest', '2025-11-25 16:36:13'),
(34, 'smtp_host', 'mail.governorcrestlimited.com', '2026-02-06 13:34:42'),
(35, 'smtp_port', '465', '2026-02-06 13:34:39'),
(36, 'smtp_user', 'support@governorcrestlimited.com', '2026-02-06 13:34:36'),
(37, 'smtp_pass', 'Whyarewefriends1', '2026-02-06 13:34:30'),
(38, 'smtp_secure', 'ssl', '2026-02-06 13:34:23');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `about_content`
--
ALTER TABLE `about_content`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_logs`
--
ALTER TABLE `email_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `old_services`
--
ALTER TABLE `old_services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `about_content`
--
ALTER TABLE `about_content`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `email_logs`
--
ALTER TABLE `email_logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `old_services`
--
ALTER TABLE `old_services`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `site_settings`
--
ALTER TABLE `site_settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
