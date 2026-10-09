/*
MySQL Data Transfer
Source Host: localhost
Source Database: mylogin
Target Host: localhost
Target Database: mylogin
Date: 9/22/2015 6:44:31 PM
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for active_guests
-- ----------------------------
CREATE TABLE `active_guests` (
  `ip` varchar(15) NOT NULL,
  `timestamp` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for active_users
-- ----------------------------
CREATE TABLE `active_users` (
  `username` varchar(30) NOT NULL,
  `timestamp` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for banned_users
-- ----------------------------
CREATE TABLE `banned_users` (
  `username` varchar(30) NOT NULL,
  `timestamp` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for users
-- ----------------------------
CREATE TABLE `users` (
  `username` varchar(30) NOT NULL,
  `password` varchar(32) default NULL,
  `userid` varchar(32) default NULL,
  `userlevel` tinyint(1) unsigned NOT NULL,
  `email` varchar(50) default NULL,
  `timestamp` int(11) unsigned NOT NULL,
  `parent_directory` varchar(30) NOT NULL,
  PRIMARY KEY  (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records 
-- ----------------------------
INSERT INTO `active_guests` VALUES ('127.0.0.1', '1442975449');
INSERT INTO `users` VALUES ('admin', '21232f297a57a5a743894a0e4a801fc3', '2044310123b1991abcc1bbaa6d542825', '9', 'arman@3g.com', '1442975081', 'admin');
INSERT INTO `users` VALUES ('master1', 'd5802d05bbf0881de2fd823c9560619e', 'a516e723c2eb32fc93402206d8327ad2', '8', 'master1@3g.com', '1442974264', 'admin');
INSERT INTO `users` VALUES ('master1agent1', 'bc6a6d13b10264fa960eddb401342243', '208413dbac8039b518be9f4cb40452af', '1', 'master1agent1@3g.com', '1442974298', 'master1');
INSERT INTO `users` VALUES ('master1agent1member1', '77e8ca40094f38029e99f8e9b6b6edf7', 'ad1988fbb51db1a4dd1310284e1f8954', '2', 'master1agent1member1@3g.com', '1442912022', 'master1agent1');
INSERT INTO `users` VALUES ('master1agent1member2', '6cea8991d4a932fce929f81299d73070', '0', '2', 'master1agent1member2@3g.com', '1442911991', 'master1agent1');
INSERT INTO `users` VALUES ('master1agent2', '66323d3087956e162291cafca8c223ee', '0', '1', 'master1agent2@gmail.com', '1442911821', 'master1');
INSERT INTO `users` VALUES ('master2', '5b9de42bf3fa2534e0d7ae695b12aeab', '91fc9ab68b46a7d1e72fec74246e1d5f', '8', 'master2@3g.com', '1442974483', 'admin');
INSERT INTO `users` VALUES ('master2agent1', '99d66c305f3583738a0889ce4b7cee8e', '5568461648ee8ad472d061f66182e370', '1', 'master2agent@3g.com', '1442974524', 'master2');
INSERT INTO `users` VALUES ('master2agent1member1', 'b882179ce34767d621fc4425b761ae20', '0', '2', 'master2agent1member1@3g.com', '1442974512', 'master2agent1');
INSERT INTO `users` VALUES ('master2agent2', '3b04cb6eac6b48e7520523932f5f885f', '0', '1', 'master2agent2@3g.com', '1442974456', 'master2');
INSERT INTO `users` VALUES ('master3', '2925bf35562c4def8fc90dc08a74c6a3', '689de9d2e01c7c389e3be3bdfe9783e5', '8', 'master3@3g.com', '1442975143', 'admin');
INSERT INTO `users` VALUES ('master3agent1', 'b72af00c5cd73f6957ba2b4ac6427884', 'fefb28cd0ca731c342b5a26e43e529cc', '1', 'master3agent1@3g.com', '1442975214', 'master3');
INSERT INTO `users` VALUES ('master3agent1member1', '4083f8801b76dfb9c1cdfc4bcbbe8062', '0', '2', 'master3agent1member1@3g.com', '1442975176', 'master3agent1');
INSERT INTO `users` VALUES ('master3agent1member2', '489994b84e78ce3ce87a14b806c61e4f', '0', '2', 'master3agent1member2@3g.com', '1442975198', 'master3agent1');
INSERT INTO `users` VALUES ('master3agent2', 'e61cfc028842d595e476d268639f5fb6', '0', '1', 'master3agent2@3g.com', '1442975136', 'master3');
INSERT INTO `users` VALUES ('master4', '5d89b98df6b9ed356f5bb3278a6aca7d', '796aad907bc9c85fc8b2b5168bbdaf39', '8', 'master4@3g.com', '1442975284', 'admin');
INSERT INTO `users` VALUES ('master4agent1', '77b03996fddf7cd26eb20a8467d7b243', '6e87896380fa39f163c76d03202bf672', '1', 'master4agent1@3g.com', '1442975357', 'master4');
INSERT INTO `users` VALUES ('master4agent1member1', '3092c8c2749810f453bc7cdfd8798182', '0', '2', 'master4agent1member1@3g.com', '1442975328', 'master4agent1');
INSERT INTO `users` VALUES ('master4agent1member2', 'e89efc28ddaf3fd62c30cad524575566', '0', '2', 'master4agent1member2@3g.com', '1442975341', 'master4agent1');
INSERT INTO `users` VALUES ('master4agent2', 'acd2362781f529ea59dba3a37d2eae71', '0', '1', 'master4agent2@3g.com', '1442975263', 'master4');

-- --------------------------------------------------------
-- VPS Digital Services Company Schema Extensions
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `services` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(150) NOT NULL,
  `slug` VARCHAR(150) NOT NULL,
  `short_desc` TEXT DEFAULT NULL,
  `full_desc` MEDIUMTEXT DEFAULT NULL,
  `icon` VARCHAR(100) DEFAULT 'code',
  `image` VARCHAR(255) DEFAULT NULL,
  `category` VARCHAR(100) DEFAULT 'Development',
  `features` TEXT DEFAULT NULL,
  `display_order` INT(11) DEFAULT 0,
  `is_featured` TINYINT(1) DEFAULT 1,
  `is_active` TINYINT(1) DEFAULT 1,
  `meta_title` VARCHAR(255) DEFAULT NULL,
  `meta_desc` TEXT DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `projects` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `reference_no` VARCHAR(50) NOT NULL,
  `client_name` VARCHAR(150) NOT NULL,
  `client_email` VARCHAR(150) NOT NULL,
  `client_phone` VARCHAR(50) DEFAULT NULL,
  `client_whatsapp` VARCHAR(50) DEFAULT NULL,
  `company_name` VARCHAR(150) DEFAULT NULL,
  `country` VARCHAR(100) DEFAULT NULL,
  `preferred_contact` VARCHAR(50) DEFAULT 'email',
  `title` VARCHAR(255) NOT NULL,
  `service_id` INT(11) DEFAULT NULL,
  `service_name` VARCHAR(150) DEFAULT NULL,
  `description` MEDIUMTEXT NOT NULL,
  `objectives` TEXT DEFAULT NULL,
  `scope_features` TEXT DEFAULT NULL,
  `timeline` VARCHAR(100) DEFAULT NULL,
  `budget_type` VARCHAR(50) DEFAULT 'fixed',
  `budget_amount` VARCHAR(100) DEFAULT NULL,
  `currency` VARCHAR(20) DEFAULT 'USD',
  `budget_flexible` TINYINT(1) DEFAULT 0,
  `additional_notes` TEXT DEFAULT NULL,
  `status` VARCHAR(50) DEFAULT 'Pending Review',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ref_unique` (`reference_no`),
  KEY `idx_status` (`status`),
  KEY `idx_service` (`service_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `project_attachments` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `project_id` INT(11) NOT NULL,
  `original_name` VARCHAR(255) NOT NULL,
  `file_path` VARCHAR(255) NOT NULL,
  `file_size` INT(11) NOT NULL,
  `mime_type` VARCHAR(100) NOT NULL,
  `uploaded_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_project_att` (`project_id`),
  CONSTRAINT `fk_project_att` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `project_status_history` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `project_id` INT(11) NOT NULL,
  `previous_status` VARCHAR(50) DEFAULT NULL,
  `new_status` VARCHAR(50) NOT NULL,
  `changed_by` VARCHAR(50) NOT NULL,
  `comment` TEXT DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_project_hist` (`project_id`),
  CONSTRAINT `fk_project_hist` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `project_notes` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `project_id` INT(11) NOT NULL,
  `admin_username` VARCHAR(50) NOT NULL,
  `note` TEXT NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_project_note` (`project_id`),
  CONSTRAINT `fk_project_note` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `team` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(150) NOT NULL,
  `slug` VARCHAR(150) NOT NULL,
  `title` VARCHAR(150) NOT NULL,
  `image` VARCHAR(255) DEFAULT NULL,
  `initials` VARCHAR(10) DEFAULT 'VP',
  `short_intro` VARCHAR(255) DEFAULT NULL,
  `bio` TEXT DEFAULT NULL,
  `specialties` TEXT DEFAULT NULL,
  `skills` TEXT DEFAULT NULL,
  `experience` VARCHAR(100) DEFAULT NULL,
  `social_linkedin` VARCHAR(255) DEFAULT NULL,
  `social_github` VARCHAR(255) DEFAULT NULL,
  `social_twitter` VARCHAR(255) DEFAULT NULL,
  `display_order` INT(11) DEFAULT 0,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `team_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `portfolio` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(200) NOT NULL,
  `slug` VARCHAR(200) NOT NULL,
  `category` VARCHAR(50) NOT NULL,
  `service_id` INT(11) DEFAULT NULL,
  `short_desc` TEXT DEFAULT NULL,
  `full_desc` MEDIUMTEXT DEFAULT NULL,
  `client_name` VARCHAR(150) DEFAULT NULL,
  `industry` VARCHAR(100) DEFAULT NULL,
  `technologies` VARCHAR(255) DEFAULT NULL,
  `featured_image` VARCHAR(255) DEFAULT NULL,
  `gallery_images` TEXT DEFAULT NULL,
  `challenge` TEXT DEFAULT NULL,
  `solution` TEXT DEFAULT NULL,
  `results` TEXT DEFAULT NULL,
  `completion_date` VARCHAR(50) DEFAULT NULL,
  `project_url` VARCHAR(255) DEFAULT NULL,
  `is_featured` TINYINT(1) DEFAULT 0,
  `is_published` TINYINT(1) DEFAULT 1,
  `meta_title` VARCHAR(255) DEFAULT NULL,
  `meta_desc` TEXT DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `portfolio_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `blog_categories` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(100) NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bcat_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `blog_posts` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL,
  `category_id` INT(11) DEFAULT NULL,
  `category_name` VARCHAR(100) DEFAULT 'General',
  `featured_image` VARCHAR(255) DEFAULT NULL,
  `icon` VARCHAR(50) DEFAULT 'article',
  `excerpt` TEXT DEFAULT NULL,
  `content` MEDIUMTEXT NOT NULL,
  `author` VARCHAR(100) DEFAULT 'VPS Team',
  `reading_time` VARCHAR(20) DEFAULT '5 min read',
  `tags` VARCHAR(255) DEFAULT NULL,
  `is_published` TINYINT(1) DEFAULT 1,
  `published_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `meta_title` VARCHAR(255) DEFAULT NULL,
  `meta_desc` TEXT DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `blog_slug_unique` (`slug`),
  KEY `idx_blog_cat` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `contact_messages` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(150) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `phone` VARCHAR(50) DEFAULT NULL,
  `subject` VARCHAR(255) NOT NULL,
  `message` TEXT NOT NULL,
  `is_read` TINYINT(1) DEFAULT 0,
  `status` VARCHAR(50) DEFAULT 'New',
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_msg_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `settings` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `setting_key` VARCHAR(100) NOT NULL,
  `setting_value` TEXT DEFAULT NULL,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key_unique` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `newsletter_subscribers` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(150) NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `news_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

