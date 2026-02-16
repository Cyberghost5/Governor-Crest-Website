-- phpMyAdmin SQL Dump
-- Create Database
CREATE DATABASE IF NOT EXISTS `governor_crest` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `governor_crest`;

-- Table structure for table `site_settings`
CREATE TABLE IF NOT EXISTS `site_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default settings
INSERT INTO `site_settings` (`setting_key`, `setting_value`) VALUES
('company_name', 'Governor Crest Limited'),
('tagline', 'One Crest, Infinite Possibilities'),
('description', 'A multi-sector company driven by innovation and integrity'),
('email', 'info@governorcrest.com'),
('phone', '+234 XXX XXX XXXX'),
('address', 'Bauchi State, Nigeria'),
('facebook', '#'),
('twitter', '#'),
('instagram', '#'),
('linkedin', '#');

-- Table structure for table `about_content`
CREATE TABLE IF NOT EXISTS `about_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `section` varchar(100) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default about content
INSERT INTO `about_content` (`section`, `title`, `content`) VALUES
('who_we_are', 'Who We Are', 'Governor Crest Limited is a dynamic multi-sector company headquartered in Bauchi State, Nigeria. Founded in 2023, we have quickly established ourselves as a trusted partner across various industries including real estate, automotive sales, agriculture, grooming services, logistics, and fashion. Our diverse portfolio allows us to serve our community comprehensively, providing quality solutions that enhance everyday life.'),
('mission', 'Our Mission', 'To deliver innovative, reliable, and affordable solutions across multiple sectors, enhancing the quality of life for individuals and businesses alike. We strive to be the preferred choice by combining integrity, excellence, and customer-centric service in everything we do.'),
('vision', 'Our Vision', 'To become Nigeria\'s leading diversified conglomerate, recognized for excellence, innovation, and positive impact across all sectors we serve. We envision a future where Governor Crest Limited is synonymous with quality, trust, and infinite possibilities for growth and success.');

-- Table structure for table `services`
CREATE TABLE IF NOT EXISTS `services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `icon` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `features` text NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `display_order` int(11) DEFAULT '0',
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default services
INSERT INTO `services` (`name`, `icon`, `description`, `features`, `image_url`, `display_order`) VALUES
('Land & Property', 'bi-building', 'Comprehensive real estate solutions for residential and commercial needs', 'Property sales and leasing\nReal estate development\nLand acquisition and documentation\nProperty management services\nInvestment consultation', 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=800', 1),
('Car Sales', 'bi-car-front', 'Quality vehicles at competitive prices with exceptional customer service', 'New and pre-owned vehicles\nCompetitive pricing and financing options\nWide selection of brands and models\nVehicle inspection and certification\nAfter-sales support', 'https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?w=800', 2),
('Agriculture', 'bi-brightness-high', 'Modern farming solutions and agricultural business services', 'Commercial farming operations\nProduce distribution and supply\nAgricultural consulting\nFarm management services\nAgri-business solutions', 'https://images.unsplash.com/photo-1625246333195-78d9c38ad449?w=800', 3),
('Unisex Salon', 'bi-scissors', 'Modern grooming and beauty care services for everyone', 'Professional haircuts and styling\nBeauty treatments and spa services\nModern grooming techniques\nExperienced stylists and beauticians\nWelcoming environment for all', 'https://images.unsplash.com/photo-1560066984-138dadb4c035?w=800', 4),
('Logistics', 'bi-truck', 'Reliable transportation and delivery services across Nigeria', 'Freight and cargo transportation\nFast and reliable delivery services\nFleet management\nWarehousing solutions\nLast-mile delivery', 'https://images.unsplash.com/photo-1566576721346-d4a3b4eaeb55?w=800', 5),
('Clothing', 'bi-bag', 'Stylish and affordable fashion for the modern Nigerian', 'Men\'s and women\'s fashion\nTraditional and contemporary styles\nQuality fabrics and materials\nAffordable pricing\nCustom tailoring services', 'https://images.unsplash.com/photo-1489987707025-afc232f7ea0f?w=800', 6);

-- Table structure for table `projects`
CREATE TABLE IF NOT EXISTS `projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `display_order` int(11) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `contact_messages`
CREATE TABLE IF NOT EXISTS `contact_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `message` text NOT NULL,
  `status` enum('unread','read','replied') DEFAULT 'unread',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `admin_users`
CREATE TABLE IF NOT EXISTS `admin_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default admin user (username: admin, password: admin123)
INSERT INTO `admin_users` (`username`, `password`, `email`) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@governorcrest.com');
