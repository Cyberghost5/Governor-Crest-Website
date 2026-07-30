-- Database Schema for Governor Crest Real Estate Conference 2026

CREATE TABLE IF NOT EXISTS `conference_registrations` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `ticket_code` VARCHAR(50) NOT NULL,
  `full_name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(50) NOT NULL,
  `occupation` VARCHAR(255) DEFAULT NULL,
  `questions` TEXT DEFAULT NULL,
  `status` ENUM('confirmed', 'cancelled') DEFAULT 'confirmed',
  `checked_in` TINYINT(1) DEFAULT 0,
  `checked_in_at` DATETIME DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ticket_code` (`ticket_code`),
  KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `conference_guests` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `designation` VARCHAR(255) NOT NULL,
  `company` VARCHAR(255) DEFAULT NULL,
  `bio` TEXT DEFAULT NULL,
  `image_url` VARCHAR(500) NOT NULL,
  `display_order` INT(11) DEFAULT 0,
  `status` ENUM('active', 'inactive') DEFAULT 'active',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed default special guest speakers
INSERT INTO `conference_guests` (`name`, `designation`, `company`, `bio`, `image_url`, `display_order`) VALUES
('Arc. Ibrahim Bello', 'Keynote Speaker & Real Estate Strategist', 'Governor Crest Limited', 'Over 18 years of pioneering sustainable urban housing projects and real estate investment masterclasses across Northern Nigeria.', 'https://images.unsplash.com/photo-1560250097-0b93528c311a?w=800&auto=format&fit=crop&q=80', 1),
('Dr. Amina Abubakar', 'Senior Land Documentation & Investment Analyst', 'Apex Urban Developers', 'Renowned authority on land title acquisition, Governor\'s Consent procedures, and high-yield property portfolios.', 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=800&auto=format&fit=crop&q=80', 2),
('Engr. Chukwuma Eze', 'Chief Structural Consultant & Commercial Developer', 'Crestwood Infrastructure', 'Specialist in affordable eco-friendly housing technologies, Smart Cities integration, and modern architectural engineering.', 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?w=800&auto=format&fit=crop&q=80', 3);
