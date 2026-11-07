-- Hammad Graphic analytics: run once in phpMyAdmin after selecting your database.
-- Cloudflare handles anonymous page/performance analytics. This local table records
-- only aggregate-friendly page views and approved button-click event categories.
CREATE TABLE IF NOT EXISTS analytics_events (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 visitor_hash CHAR(64) NOT NULL,
 event_type ENUM('page_view','whatsapp_click','contact_click','order_click') NOT NULL,
 page_path VARCHAR(255) NOT NULL DEFAULT '/',
 created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 INDEX idx_analytics_created_type (created_at,event_type),
 INDEX idx_analytics_visitor_date (visitor_hash,created_at),
 INDEX idx_analytics_page_date (page_path,created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Requires site_settings from database/content-migration.sql.
-- Run content-migration.sql first, then run this file once.
