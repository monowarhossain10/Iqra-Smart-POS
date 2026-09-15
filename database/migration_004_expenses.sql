CREATE TABLE IF NOT EXISTS expenses (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    category VARCHAR(100) NOT NULL,
    description VARCHAR(255) NULL,
    amount DECIMAL(12,2) NOT NULL,
    expense_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_expenses_date (expense_date),
    CONSTRAINT fk_expenses_user FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB;
