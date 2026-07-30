-- Create course_categories table
CREATE TABLE IF NOT EXISTS `course_categories` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `parent_id` BIGINT UNSIGNED NULL,
  `description` TEXT NULL,
  `order` INT NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  INDEX `course_categories_slug_is_active_index` (`slug`, `is_active`),
  INDEX `course_categories_parent_id_index` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create courses table
CREATE TABLE IF NOT EXISTS `courses` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `image_url` VARCHAR(255) NULL,
  `duration` VARCHAR(255) NULL,
  `source` VARCHAR(255) NULL,
  `short_description` TEXT NULL,
  `description` LONGTEXT NULL,
  `what_you_learn` LONGTEXT NULL,
  `who_this_for` LONGTEXT NULL,
  `prerequisites` LONGTEXT NULL,
  `info` JSON NULL,
  `sessions_count` INT NOT NULL DEFAULT 0,
  `language` VARCHAR(255) NOT NULL DEFAULT 'fa',
  `published_at` DATE NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
  `views_count` INT NOT NULL DEFAULT 0,
  `enrollments_count` INT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  INDEX `courses_slug_is_active_index` (`slug`, `is_active`),
  INDEX `courses_is_featured_index` (`is_featured`),
  INDEX `courses_published_at_index` (`published_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create course_sections table
CREATE TABLE IF NOT EXISTS `course_sections` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `course_id` BIGINT UNSIGNED NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT NULL,
  `order` INT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  INDEX `course_sections_course_id_order_index` (`course_id`, `order`),
  FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create course_videos table
CREATE TABLE IF NOT EXISTS `course_videos` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `course_id` BIGINT UNSIGNED NOT NULL,
  `section_id` BIGINT UNSIGNED NULL,
  `title` VARCHAR(255) NOT NULL,
  `video_url` VARCHAR(255) NOT NULL,
  `subtitle_url` VARCHAR(255) NULL,
  `type` ENUM('video', 'document') NOT NULL DEFAULT 'video',
  `duration` VARCHAR(255) NULL,
  `order` INT NOT NULL DEFAULT 0,
  `is_free` TINYINT(1) NOT NULL DEFAULT 0,
  `views_count` INT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  INDEX `course_videos_course_id_order_index` (`course_id`, `order`),
  INDEX `course_videos_section_id_index` (`section_id`),
  FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create course_enrollments table
CREATE TABLE IF NOT EXISTS `course_enrollments` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `course_id` BIGINT UNSIGNED NOT NULL,
  `status` ENUM('active', 'completed', 'cancelled') NOT NULL DEFAULT 'active',
  `progress_percentage` INT NOT NULL DEFAULT 0,
  `completed_at` TIMESTAMP NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  UNIQUE KEY `course_enrollments_user_id_course_id_unique` (`user_id`, `course_id`),
  INDEX `course_enrollments_status_index` (`status`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create course_course_category pivot table
CREATE TABLE IF NOT EXISTS `course_course_category` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `course_id` BIGINT UNSIGNED NOT NULL,
  `course_category_id` BIGINT UNSIGNED NOT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  UNIQUE KEY `course_course_category_unique` (`course_id`, `course_category_id`),
  FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`course_category_id`) REFERENCES `course_categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

