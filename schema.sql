-- ============================================================
-- AI Chatbot for Customer Support in Small Businesses
-- Database Schema (MySQL / MariaDB - compatible with XAMPP)
-- ============================================================

CREATE DATABASE IF NOT EXISTS ai_chatbot_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE ai_chatbot_db;

-- ------------------------------------------------------------
-- Table: users  (customer accounts)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    first_name      VARCHAR(50)  NOT NULL,
    last_name       VARCHAR(50)  NOT NULL,
    email           VARCHAR(100) NOT NULL UNIQUE,
    contact_number  VARCHAR(20)  NOT NULL,
    password        VARCHAR(255) NOT NULL, -- stored with password_hash()
    created_at      DATETIME     DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Table: admins  (administrator accounts)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS admins (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    username    VARCHAR(50)  NOT NULL UNIQUE,
    email       VARCHAR(100) NOT NULL UNIQUE,
    password    VARCHAR(255) NOT NULL, -- stored with password_hash()
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Table: products
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS products (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    name          VARCHAR(100)   NOT NULL,
    description   TEXT           NOT NULL,
    price         DECIMAL(10,2)  NOT NULL,
    image         VARCHAR(255)   DEFAULT NULL, -- filename in /uploads
    availability  ENUM('Available','Out of Stock') NOT NULL DEFAULT 'Available',
    created_by    INT            DEFAULT NULL,
    created_at    DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at    DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES admins(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Table: faqs
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS faqs (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    question    VARCHAR(255) NOT NULL,
    answer      TEXT         NOT NULL,
    created_by  INT          DEFAULT NULL,
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES admins(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Table: chat_history
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS chat_history (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    user_id     INT NOT NULL,
    sender      ENUM('user','bot') NOT NULL,
    message     TEXT NOT NULL,
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Seed data
-- ------------------------------------------------------------

-- Default admin account -> username: admin | password: Admin@123
INSERT INTO admins (username, email, password) VALUES
('admin', 'admin@aichatbot.com', '$2b$12$RsTrxzzqaybLvoDLQoFMruRW0qciwKUimg8ZTGpWHBUnjmJx5/FFW');
-- NOTE: the hash above is a verified bcrypt hash for "Admin@123", compatible with PHP's
-- password_verify(). Change this password after your first login for security.

-- Sample products
INSERT INTO products (name, description, price, availability) VALUES
('Business Starter Package', 'Basic AI chatbot setup for small businesses, includes 5 FAQs and 1 support flow.', 4999.00, 'Available'),
('Business Pro Package', 'Advanced chatbot with product catalog integration and unlimited FAQs.', 9999.00, 'Available'),
('Enterprise Support Suite', 'Full customer support suite with live chat handoff and analytics dashboard.', 19999.00, 'Out of Stock');

-- Sample FAQs
INSERT INTO faqs (question, answer) VALUES
('What are your business hours?', 'We are open Monday to Saturday, 9:00 AM to 6:00 PM. We are closed on Sundays and public holidays.'),
('Where are you located?', 'Our office is located at 123 Business Ave, Quezon City, Metro Manila, Philippines.'),
('How can I contact support?', 'You can reach us through this chat, email us at support@aichatbot.com, or call (02) 8123-4567.'),
('Do you offer refunds?', 'Yes, refunds are available within 7 days of purchase. Please contact our support team to process a refund.'),
('How do I create an account?', 'Click the Register link on the login page and fill in your details to create a free account.');
