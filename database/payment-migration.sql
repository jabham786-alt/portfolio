-- Run ONCE in phpMyAdmin after selecting the Hammad Graphic database.
ALTER TABLE orders
 ADD COLUMN quote_amount DECIMAL(10,2) NULL,
 ADD COLUMN payment_token CHAR(64) NULL,
 ADD COLUMN payment_method VARCHAR(20) NULL,
 ADD COLUMN payment_reference VARCHAR(120) NULL,
 ADD COLUMN payment_proof VARCHAR(100) NULL,
 ADD COLUMN payment_status VARCHAR(24) NOT NULL DEFAULT 'unpaid',
 ADD COLUMN paid_at DATETIME NULL,
 ADD UNIQUE KEY uq_orders_payment_token (payment_token),
 ADD INDEX idx_orders_payment_status (payment_status);
CREATE TABLE payment_settings (
 setting_key VARCHAR(40) PRIMARY KEY,
 setting_value VARCHAR(190) NOT NULL,
 updated_at DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
