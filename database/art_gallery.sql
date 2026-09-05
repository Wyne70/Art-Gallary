-- =====================================================
-- DATABASE SCHEMA: art_gallery_db
-- ERWYNE ARTSPACE
-- =====================================================

CREATE DATABASE IF NOT EXISTS `art_gallery_db` 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE `art_gallery_db`;

-- Temporarily disable foreign key checks so tables can be dropped and rebuilt
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `artworks`;
DROP TABLE IF EXISTS `artists`;
DROP TABLE IF EXISTS `contact_messages`;
DROP TABLE IF EXISTS `users`;

-- -----------------------------------------------------
-- 1. USERS TABLE
-- -----------------------------------------------------
CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('admin', 'user') DEFAULT 'admin',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- 2. ARTISTS TABLE
-- -----------------------------------------------------
CREATE TABLE `artists` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(200) NOT NULL,
  `bio` TEXT NULL,
  `profile_image` VARCHAR(255) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- 3. ARTWORKS TABLE
-- -----------------------------------------------------
CREATE TABLE `artworks` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `artist_id` INT NOT NULL,
  `title` VARCHAR(200) NOT NULL,
  `description` TEXT NOT NULL,
  `category` VARCHAR(100) NOT NULL,
  `year_created` INT NULL,
  `medium` VARCHAR(150) NULL,
  `price` DECIMAL(10, 2) NULL,
  `image` VARCHAR(255) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_artworks_artist` 
    FOREIGN KEY (`artist_id`) 
    REFERENCES `artists` (`id`) 
    ON DELETE CASCADE 
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- 4. CONTACT MESSAGES TABLE
-- -----------------------------------------------------
CREATE TABLE `contact_messages` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(150) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `subject` VARCHAR(200) NOT NULL,
  `message` TEXT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1;

-- =====================================================
-- SEED DATA
-- =====================================================

-- Default Administrator
-- Username: admin
-- Password: admin123
INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`)
VALUES (1, 'admin', 'admin@artspace.local', '$2y$10$tZ92M3p8/YnEaE6k7vXnPeC6R0nEaQx2E1q8b0.pP6.B5Qj7oFfye', 'admin')
ON DUPLICATE KEY UPDATE 
  `password` = '$2y$10$tZ92M3p8/YnEaE6k7vXnPeC6R0nEaQx2E1q8b0.pP6.B5Qj7oFfye', 
  `role` = 'admin';

-- Sample Artists
INSERT INTO `artists` (`id`, `name`, `bio`, `profile_image`) VALUES
(1, 'Elena Rostova', 'Contemporary painter specializing in expressive oil landscapes and modern chiaroscuro techniques.', NULL),
(2, 'Marcus Vance', 'Digital artist and concept designer focusing on cyberpunk architecture and futuristic environments.', NULL),
(3, 'Sophia Alcantara', 'Documentary and fine-art photographer exploring coastal narratives and ambient lighting.', NULL),
(4, 'David Chen', 'Sculptor working with raw geometric metals, industrial stone, and dynamic balance.', NULL),
(5, 'Aria Thorne', 'Visual storyteller and illustrator blending whimsical folklore with saturated palettes.', NULL);

-- Sample Artworks
INSERT INTO `artworks` (
    `artist_id`,
    `title`,
    `description`,
    `category`,
    `year_created`,
    `medium`,
    `price`,
    `image`
) VALUES
(1, 'Golden Silence', 'A contemplative painting inspired by quiet moments and warm evening light.', 'Painting', 2025, 'Oil on Canvas', 25000.00, NULL),
(1, 'Echoes of Nature', 'An expressive landscape exploring the relationship between humans and nature.', 'Landscape', 2024, 'Acrylic on Canvas', 18500.00, NULL),
(2, 'Digital Dreams', 'A futuristic digital composition inspired by technology and imagination.', 'Digital Art', 2025, 'Digital Illustration', 15000.00, NULL),
(2, 'Neon City', 'A cyberpunk-inspired cityscape filled with lights and futuristic architecture.', 'Digital Art', 2025, 'Digital Art', 17500.00, NULL),
(3, 'Morning Light', 'A peaceful photograph capturing the first light of the morning.', 'Photography', 2024, 'Digital Photography', 8500.00, NULL),
(3, 'Island Stories', 'A documentary-style photograph celebrating island life and community.', 'Photography', 2025, 'Photography Print', 9500.00, NULL),
(4, 'Form and Balance', 'A modern sculpture exploring balance, movement, and geometric form.', 'Sculpture', 2023, 'Metal and Stone', 32000.00, NULL),
(4, 'The Observer', 'A conceptual sculpture representing curiosity and human perception.', 'Sculpture', 2024, 'Mixed Media', 28000.00, NULL),
(5, 'Little Universe', 'A colorful illustration inspired by imagination and childhood dreams.', 'Illustration', 2025, 'Digital Illustration', 7500.00, NULL),
(5, 'The Wanderer', 'An imaginative character illustration about adventure and discovery.', 'Illustration', 2025, 'Digital Illustration', 9000.00, NULL);