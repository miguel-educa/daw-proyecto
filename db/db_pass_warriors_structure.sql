DROP DATABASE IF EXISTS `pass_warriors`;
CREATE DATABASE IF NOT EXISTS `pass_warriors`;
USE `pass_warriors`;


-- Tabla Users
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
    `id` CHAR(36) NOT NULL,
    `username` VARCHAR(30) NOT NULL,
    `name` VARCHAR(50) NOT NULL,
    `master_password` CHAR(60) NOT NULL,
    `master_password_edited_at` TIMESTAMP NOT NULL DEFAULT (CURRENT_TIMESTAMP),
    `recuperation_code` CHAR(24) NOT NULL,
    `recuperation_code_edited_at` TIMESTAMP NOT NULL DEFAULT (CURRENT_TIMESTAMP),
    `totp_2fa_secret` CHAR(32) DEFAULT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT (CURRENT_TIMESTAMP),
    `updated_at` TIMESTAMP NOT NULL DEFAULT (CURRENT_TIMESTAMP) ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    CONSTRAINT `ck_users_uuid` CHECK (regexp_like(`id`,_utf8mb4'^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$')),
    UNIQUE KEY `u_users_username` (`username`),
    UNIQUE KEY `u_users_recuperation_code` (`recuperation_code`)
);


-- Tabla Sessions
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
    `id` CHAR(36) NOT NULL,
    `user_id` CHAR(36) NOT NULL,
    `token` CHAR(64) NOT NULL,
    `token_created_at` TIMESTAMP NOT NULL DEFAULT (CURRENT_TIMESTAMP),
    `token_expires_at` TIMESTAMP NOT NULL DEFAULT (CURRENT_TIMESTAMP),
    `user_agent` VARCHAR(255) DEFAULT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT (CURRENT_TIMESTAMP),
    `updated_at` TIMESTAMP NOT NULL DEFAULT (CURRENT_TIMESTAMP) ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    CONSTRAINT `ck_sessions_uuid` CHECK (regexp_like(`id`,_utf8mb4'^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$')),
    CONSTRAINT `fk_sessions_users` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON UPDATE CASCADE ON DELETE CASCADE,
    UNIQUE KEY `u_sessions_token` (`token`)
);


-- Tabla Folders
DROP TABLE IF EXISTS `folders`;
CREATE TABLE `folders` (
    `id` CHAR(36) NOT NULL,
    `owner_user_id` CHAR(36) NOT NULL,
    `name` VARCHAR(50) NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT (CURRENT_TIMESTAMP),
    `updated_at` TIMESTAMP NOT NULL DEFAULT (CURRENT_TIMESTAMP) ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    CONSTRAINT `ck_folders_uuid` CHECK (regexp_like(`id`,_utf8mb4'^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$')),
    CONSTRAINT `fk_folders_users` FOREIGN KEY (`owner_user_id`) REFERENCES `users`(`id`) ON UPDATE CASCADE ON DELETE CASCADE,
    UNIQUE KEY `u_folders_owner_user_folder_name` (`owner_user_id`, `name`)
);


-- Tabla Passwords
DROP TABLE IF EXISTS `passwords`;
CREATE TABLE `passwords` (
    `id` CHAR(36) NOT NULL,
    `owner_user_id` CHAR(36) NOT NULL,
    `folder_id` CHAR(36) DEFAULT NULL,
    `name` VARCHAR(50) NOT NULL,
    `password` VARCHAR(100) DEFAULT NULL,
    `username` VARCHAR(50) DEFAULT NULL,
    `urls` JSON DEFAULT NULL,
    `notes` TEXT DEFAULT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT (CURRENT_TIMESTAMP),
    `updated_at` TIMESTAMP NOT NULL DEFAULT (CURRENT_TIMESTAMP) ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    CONSTRAINT `ck_passwords_uuid` CHECK (regexp_like(`id`,_utf8mb4'^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$')),
    CONSTRAINT `fk_passwords_users` FOREIGN KEY (`owner_user_id`) REFERENCES `users`(`id`) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT `fk_passwords_folders` FOREIGN KEY (`folder_id`) REFERENCES `folders`(`id`) ON UPDATE CASCADE ON DELETE SET NULL
);


-- Tabla Shared Passwords
DROP TABLE IF EXISTS `shared_passwords`;
CREATE TABLE `shared_passwords` (
    `id` CHAR(36) NOT NULL,
    `shared_user_id` CHAR(36) NOT NULL,
    `password_id` CHAR(36) NOT NULL,
    `permission` ENUM('read-only', 'read-write') DEFAULT 'read-only',
    `created_at` TIMESTAMP NOT NULL DEFAULT (CURRENT_TIMESTAMP),
    `updated_at` TIMESTAMP NOT NULL DEFAULT (CURRENT_TIMESTAMP) ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    CONSTRAINT `ck_shared_passwords_uuid` CHECK (regexp_like(`id`,_utf8mb4'^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$')),
    CONSTRAINT `fk_shared_passwords_users` FOREIGN KEY (`shared_user_id`) REFERENCES `users`(`id`) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT `fk_shared_passwords_passwords` FOREIGN KEY (`password_id`) REFERENCES `passwords`(`id`) ON UPDATE CASCADE ON DELETE CASCADE,
    UNIQUE KEY `u_shared_passwords_shared_user_password` (`shared_user_id`, `password_id`)
);
