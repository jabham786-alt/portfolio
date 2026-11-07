-- Hammad Graphic content manager and Zoom enquiry migration.
-- Run this file ONCE after the existing payment-migration.sql.
CREATE TABLE portfolio_items (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 title VARCHAR(160) NOT NULL,
 category VARCHAR(60) NOT NULL,
 description TEXT NULL,
 customer_label VARCHAR(120) NULL,
 project_date DATE NULL,
 media_kind ENUM('image','video','audio','link') NOT NULL DEFAULT 'image',
 media_path VARCHAR(255) NULL,
 external_url VARCHAR(500) NULL,
 status ENUM('draft','published') NOT NULL DEFAULT 'draft',
 created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 published_at DATETIME NULL,
 INDEX idx_portfolio_status_date(status,published_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE zoom_requests (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 customer_name VARCHAR(120) NOT NULL,
 email VARCHAR(190) NOT NULL,
 whatsapp VARCHAR(40) NOT NULL,
 project_topic VARCHAR(180) NOT NULL,
 preferred_at DATETIME NULL,
 message TEXT NULL,
 meeting_url VARCHAR(500) NULL,
 status ENUM('new','contacted','scheduled','completed','cancelled') NOT NULL DEFAULT 'new',
 created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 INDEX idx_zoom_status_date(status,created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Editable homepage content and portrait settings.
CREATE TABLE site_settings (
 setting_key VARCHAR(60) PRIMARY KEY,
 setting_value TEXT NOT NULL,
 updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
