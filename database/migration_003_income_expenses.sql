ALTER TABLE vouchers MODIFY voucher_type ENUM('sale','purchase','service','mfs','utility','return','salary','income') NOT NULL;

CREATE TABLE IF NOT EXISTS income_records (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    category VARCHAR(100) NOT NULL,
    description VARCHAR(255) NULL,
    amount DECIMAL(12,2) NOT NULL,
    income_date DATE NOT NULL,
    payment_method ENUM('cash','bKash','Nagad','Rocket','card','bank') NOT NULL DEFAULT 'cash',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_income_date (income_date),
    CONSTRAINT fk_income_user FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB;