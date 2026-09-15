CREATE TABLE IF NOT EXISTS vouchers (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    voucher_number VARCHAR(50) NOT NULL UNIQUE,
    voucher_type ENUM('sale','purchase','service','mfs','utility') NOT NULL,
    reference_id BIGINT UNSIGNED NULL,
    created_by INT UNSIGNED NOT NULL,
    amount DECIMAL(12,2) NOT NULL DEFAULT 0,
    payload JSON NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_vouchers_type_reference (voucher_type, reference_id),
    INDEX idx_vouchers_created_at (created_at),
    CONSTRAINT fk_vouchers_user FOREIGN KEY (created_by) REFERENCES users(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS mfs_daily_reconciliations (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    account_id TINYINT UNSIGNED NOT NULL,
    business_date DATE NOT NULL,
    opening_float DECIMAL(12,2) NOT NULL,
    expected_closing DECIMAL(12,2) NOT NULL,
    actual_closing DECIMAL(12,2) NOT NULL,
    difference DECIMAL(12,2) NOT NULL,
    notes VARCHAR(255) NULL,
    reconciled_by INT UNSIGNED NOT NULL,
    reconciled_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_mfs_reconciliation_day (account_id, business_date),
    CONSTRAINT fk_reconcile_account FOREIGN KEY (account_id) REFERENCES mfs_accounts(id),
    CONSTRAINT fk_reconcile_user FOREIGN KEY (reconciled_by) REFERENCES users(id)
) ENGINE=InnoDB;