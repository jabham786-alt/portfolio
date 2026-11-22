-- Hammad Graphic database schema. Also created automatically by admin/setup.php.
CREATE TABLE admins (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 username VARCHAR(60) NOT NULL UNIQUE,
 password_hash VARCHAR(255) NOT NULL,
 created_at DATETIME NOT NULL,
 last_login DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE orders (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 order_code VARCHAR(24) NOT NULL UNIQUE,
 customer_name VARCHAR(100) NOT NULL,
 email VARCHAR(190) NOT NULL,
 whatsapp VARCHAR(30) NOT NULL,
 service VARCHAR(80) NOT NULL,
 budget VARCHAR(60) NULL,
 deadline DATE NULL,
 brief TEXT NOT NULL,
 attachment VARCHAR(80) NULL,
 status VARCHAR(24) NOT NULL DEFAULT 'pending',
 admin_note TEXT NULL,
 created_at DATETIME NOT NULL,
 updated_at DATETIME NOT NULL,
 INDEX idx_orders_status_created (status, created_at),
 INDEX idx_orders_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
