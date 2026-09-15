CREATE TABLE IF NOT EXISTS pos_held_bills (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    customer_name VARCHAR(150) NULL,
    discount DECIMAL(12,2) NOT NULL DEFAULT 0,
    payment_method ENUM('cash','bKash','Nagad','Rocket','card','mixed') NOT NULL DEFAULT 'cash',
    paid_amount DECIMAL(12,2) NOT NULL DEFAULT 0,
    cart JSON NOT NULL,
    note VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_held_bills_user_updated (user_id, updated_at),
    CONSTRAINT fk_held_bill_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;