CREATE DATABASE IF NOT EXISTS todo_mvc;

CREATE USER 'todo_mvc_user'@'%' IDENTIFIED BY 'todo_mvc_pwd';
GRANT ALL ON todo_mvc.* TO 'todo_mvc_user'@'%' IDENTIFIED BY 'todo_mvc_pwd';

FLUSH PRIVILEGES;

USE todo_mvc;
SET NAMES utf8;

-- todo_mvc dump
DROP TABLE IF EXISTS `ref_notes`;
CREATE TABLE ref_notes (
    id BIGINT UNSIGNED auto_increment NOT NULL,
    user_id INT(11) NOT NULL,
    description VARCHAR(1000) NOT NULL,
    is_completed BOOL DEFAULT false NOT NULL,
    is_deleted BOOL DEFAULT false NOT NULL,
    insert_stamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    update_stamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    CONSTRAINT `pk-ref_notes[id]` PRIMARY KEY (id)
)
    ENGINE=InnoDB
    DEFAULT CHARSET=utf8
    COLLATE=utf8_general_ci;

CREATE INDEX `ix-ref_notes[user, deleted, completed]` ON ref_notes (user_id,is_deleted,is_completed) USING BTREE;

-- PHPAuth MySQL dump

SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

-- Config table

DROP TABLE IF EXISTS `phpauth_config`;
CREATE TABLE `phpauth_config` (
    `setting` varchar(100) NOT NULL,
    `value` varchar(100) DEFAULT NULL,
    UNIQUE KEY `setting` (`setting`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE INDEX `ix-phpauth_config[setting]` ON phpauth_config (setting) USING BTREE;

INSERT INTO `phpauth_config` (`setting`, `value`) VALUES
    ('attack_mitigation_time',  '+30 minutes'),
    ('attempts_before_ban', '30'),
    ('attempts_before_verify',  '5'),
    ('bcrypt_cost', '10'),
    ('cookie_domain', NULL),
    ('cookie_forget', '+30 minutes'),
    ('cookie_http', '0'),
    ('cookie_name', 'phpauth_session_cookie'),
    ('cookie_path', '/'),
    ('cookie_remember', '+1 month'),
    ('cookie_secure', '0'),
    ('cookie_renew', '+5 minutes'),
    ('allow_concurrent_sessions', FALSE),
    ('emailmessage_suppress_activation',  '0'),
    ('emailmessage_suppress_reset', '0'),
    ('mail_charset','UTF-8'),
    ('password_min_score',  '0'),
    ('site_activation_page',  'activate'),
    ('site_email',  'no-reply@phpauth.cuonic.com'),
    ('site_key',  'fghuior.)/!/jdUkd8s2!7HVHG7777ghg'),
    ('site_name', 'PHPAuth'),
    ('site_password_reset_page',  'reset'),
    ('site_timezone', 'Asia/Vladivostok'),
    ('site_url',  'https://github.com/PHPAuth/PHPAuth'),
    ('site_language', 'en_GB'),
    ('smtp',  '0'),
    ('smtp_debug',  '0'),
    ('smtp_auth', '1'),
    ('smtp_host', 'smtp.example.com'),
    ('smtp_password', 'password'),
    ('smtp_port', '25'),
    ('smtp_security', NULL),
    ('smtp_username', 'email@example.com'),
    ('table_attempts',  'phpauth_attempts'),
    ('table_requests',  'phpauth_requests'),
    ('table_sessions',  'phpauth_sessions'),
    ('table_users', 'phpauth_users'),
    ('table_emails_banned', 'phpauth_emails_banned'),
    ('table_translations', 'phpauth_translation_dictionary'),
    ('verify_email_max_length', '100'),
    ('verify_email_min_length', '5'),
    ('verify_email_use_banlist',  '1'),
    ('verify_password_min_length',  '3'),
    ('request_key_expiration', '+10 minutes'),
    ('translation_source', 'php'),
    ('recaptcha_enabled', 0),
    ('recaptcha_site_key', ''),
    ('recaptcha_secret_key', '');

-- Attempts table

DROP TABLE IF EXISTS `phpauth_attempts`;
CREATE TABLE `phpauth_attempts` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `ip` char(39) NOT NULL,
    `expiredate` datetime NOT NULL,
    PRIMARY KEY (`id`),
    KEY `ip` (`ip`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE INDEX `ix-phpauth_attempts[ip]` ON phpauth_attempts (ip) USING BTREE;

-- Requests table

DROP TABLE IF EXISTS `phpauth_requests`;
CREATE TABLE `phpauth_requests` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `uid` int(11) NOT NULL,
    `token` CHAR(20) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
    `expire` datetime NOT NULL,
    `type` ENUM('activation','reset') CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
    PRIMARY KEY (`id`),
    KEY `type` (`type`),
    KEY `token` (`token`),
    KEY `uid` (`uid`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE INDEX `ix-phpauth_requests[id]` ON phpauth_requests (id) USING BTREE;
CREATE INDEX `ix-phpauth_requests[uid]` ON phpauth_requests (uid) USING BTREE;

-- Sessions table

DROP TABLE IF EXISTS `phpauth_sessions`;
CREATE TABLE `phpauth_sessions` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `uid` int(11) NOT NULL,
    `hash` char(40) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
    `expiredate` datetime NOT NULL,
    `ip` varchar(39) NOT NULL,
    `agent` varchar(200) NOT NULL,
    `cookie_crc` char(40) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE INDEX `ix-phpauth_sessions[id]` ON phpauth_sessions (id) USING BTREE;
CREATE INDEX `ix-phpauth_sessions[uid]` ON phpauth_sessions (uid) USING BTREE;

-- Users table

DROP TABLE IF EXISTS `phpauth_users`;
CREATE TABLE `phpauth_users` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `email` varchar(100) DEFAULT NULL,
    `password` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
    `isactive` tinyint(1) NOT NULL DEFAULT '0',
    `dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `email` (`email`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE INDEX `ix-phpauth_users[id]` ON phpauth_users (id) USING BTREE;
CREATE INDEX `ix-phpauth_users[email]` ON phpauth_users (email) USING BTREE;

-- Banned emails reference

DROP TABLE IF EXISTS `phpauth_emails_banned`;
CREATE TABLE `phpauth_emails_banned` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `domain` varchar(100) DEFAULT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE INDEX `ix-phpauth_emails_banned[id]` ON phpauth_emails_banned (id) USING BTREE;
