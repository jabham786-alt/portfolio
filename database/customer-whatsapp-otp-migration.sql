-- Hammad Graphic: one active WhatsApp registration OTP per customer.
-- Run once after the main customer-accounts-payments migration.
-- OTP values are stored as password hashes, never as plain text.
CREATE TABLE IF NOT EXISTS customer_registration_otps (
    customer_id BIGINT UNSIGNED NOT NULL PRIMARY KEY,
    otp_hash VARCHAR(255) NOT NULL,
    expires_at DATETIME NOT NULL,
    attempts TINYINT UNSIGNED NOT NULL DEFAULT 0,
    send_count TINYINT UNSIGNED NOT NULL DEFAULT 1,
    send_window_started_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    sent_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_registration_otp_customer
        FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE,
    KEY idx_registration_otp_expiry (expires_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
