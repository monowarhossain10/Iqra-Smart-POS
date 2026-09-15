ALTER TABLE vouchers MODIFY voucher_type ENUM('sale','purchase','service','mfs','utility','return','salary') NOT NULL;

CREATE TABLE IF NOT EXISTS product_returns (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    return_number VARCHAR(50) NOT NULL UNIQUE,
    source_voucher_id BIGINT UNSIGNED NOT NULL,
    return_type ENUM('sale','purchase') NOT NULL,
    processed_by INT UNSIGNED NOT NULL,
    total DECIMAL(12,2) NOT NULL DEFAULT 0,
    reason VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_returns_source (source_voucher_id),
    CONSTRAINT fk_returns_voucher FOREIGN KEY (source_voucher_id) REFERENCES vouchers(id),
    CONSTRAINT fk_returns_user FOREIGN KEY (processed_by) REFERENCES users(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS product_return_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    return_id BIGINT UNSIGNED NOT NULL,
    product_id INT UNSIGNED NOT NULL,
    quantity DECIMAL(12,3) NOT NULL,
    unit_price DECIMAL(12,2) NOT NULL,
    total DECIMAL(12,2) NOT NULL,
    CONSTRAINT fk_return_items_return FOREIGN KEY (return_id) REFERENCES product_returns(id) ON DELETE CASCADE,
    CONSTRAINT fk_return_items_product FOREIGN KEY (product_id) REFERENCES products(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS employee_salary_profiles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL UNIQUE,
    monthly_salary DECIMAL(12,2) NOT NULL DEFAULT 0,
    allowance DECIMAL(12,2) NOT NULL DEFAULT 0,
    effective_from DATE NOT NULL,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_salary_profile_user FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS salary_payments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    salary_month DATE NOT NULL,
    base_salary DECIMAL(12,2) NOT NULL,
    allowance DECIMAL(12,2) NOT NULL DEFAULT 0,
    deductions DECIMAL(12,2) NOT NULL DEFAULT 0,
    net_amount DECIMAL(12,2) NOT NULL,
    payment_method ENUM('cash','bKash','Nagad','Rocket','card','bank') NOT NULL DEFAULT 'cash',
    paid_by INT UNSIGNED NOT NULL,
    paid_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_salary_month_employee (user_id, salary_month),
    CONSTRAINT fk_salary_payment_user FOREIGN KEY (user_id) REFERENCES users(id),
    CONSTRAINT fk_salary_payment_paid_by FOREIGN KEY (paid_by) REFERENCES users(id)
) ENGINE=InnoDB;