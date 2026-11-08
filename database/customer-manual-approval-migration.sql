-- Hammad Graphic: manual admin approval + one-time WhatsApp activation link.
-- BACK UP the database first. Run AFTER customer-accounts-payments-migration.sql.
-- Safe for existing active/suspended accounts. Old pending_verification records
-- are converted to pending_admin; no customer is automatically activated.

ALTER TABLE customers
    MODIFY account_status ENUM(
        'pending_verification',
        'pending_admin',
        'approved_pending_activation',
        'active',
        'rejected',
        'suspended'
    ) NOT NULL DEFAULT 'pending_admin';

UPDATE customers
SET account_status='pending_admin', updated_at=NOW()
WHERE account_status='pending_verification';

ALTER TABLE customers
    MODIFY account_status ENUM(
        'pending_admin',
        'approved_pending_activation',
        'active',
        'rejected',
        'suspended'
    ) NOT NULL DEFAULT 'pending_admin';

CREATE TABLE IF NOT EXISTS customer_activation_tokens (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    customer_id BIGINT UNSIGNED NOT NULL,
    token_hash CHAR(64) NOT NULL,
    expires_at DATETIME NOT NULL,
    consumed_at DATETIME NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_customer_activation_token (token_hash),
    KEY idx_customer_activation_customer (customer_id, consumed_at, expires_at),
    CONSTRAINT fk_customer_activation_customer
        FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
