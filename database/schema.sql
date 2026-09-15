CREATE DATABASE IF NOT EXISTS iqra_erp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE iqra_erp;

CREATE TABLE roles (
    id TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    description VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE permissions (
    id SMALLINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(80) NOT NULL UNIQUE,
    label VARCHAR(120) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE role_permissions (
    role_id TINYINT UNSIGNED NOT NULL,
    permission_id SMALLINT UNSIGNED NOT NULL,
    PRIMARY KEY (role_id, permission_id),
    CONSTRAINT fk_rp_role FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
    CONSTRAINT fk_rp_permission FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    role_id TINYINT UNSIGNED NOT NULL,
    full_name VARCHAR(120) NOT NULL,
    email VARCHAR(160) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    phone VARCHAR(30) NULL,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    last_login_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_users_role_active (role_id, is_active),
    CONSTRAINT fk_users_role FOREIGN KEY (role_id) REFERENCES roles(id)
) ENGINE=InnoDB;

CREATE TABLE categories (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description VARCHAR(255) NULL,
    is_active BOOLEAN NOT NULL DEFAULT TRUE
) ENGINE=InnoDB;

CREATE TABLE suppliers (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    contact_person VARCHAR(120) NULL,
    phone VARCHAR(30) NULL,
    email VARCHAR(160) NULL,
    address TEXT NULL,
    opening_due DECIMAL(12,2) NOT NULL DEFAULT 0,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_suppliers_name (name)
) ENGINE=InnoDB;

CREATE TABLE products (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id INT UNSIGNED NULL,
    supplier_id INT UNSIGNED NULL,
    sku VARCHAR(60) NOT NULL UNIQUE,
    barcode VARCHAR(80) NULL UNIQUE,
    name VARCHAR(180) NOT NULL,
    unit VARCHAR(30) NOT NULL DEFAULT 'piece',
    buying_price DECIMAL(12,2) NOT NULL DEFAULT 0,
    selling_price DECIMAL(12,2) NOT NULL DEFAULT 0,
    stock_quantity DECIMAL(12,3) NOT NULL DEFAULT 0,
    low_stock_threshold DECIMAL(12,3) NOT NULL DEFAULT 5,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_products_search (name, sku, barcode),
    INDEX idx_products_stock (stock_quantity, low_stock_threshold),
    CONSTRAINT fk_products_category FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
    ,CONSTRAINT fk_products_supplier FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE purchase_orders (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    supplier_id INT UNSIGNED NOT NULL,
    created_by INT UNSIGNED NOT NULL,
    order_number VARCHAR(40) NOT NULL UNIQUE,
    status ENUM('draft','ordered','partial','received','cancelled') NOT NULL DEFAULT 'draft',
    subtotal DECIMAL(12,2) NOT NULL DEFAULT 0,
    discount DECIMAL(12,2) NOT NULL DEFAULT 0,
    total DECIMAL(12,2) NOT NULL DEFAULT 0,
    ordered_at DATETIME NULL,
    received_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_po_supplier FOREIGN KEY (supplier_id) REFERENCES suppliers(id),
    CONSTRAINT fk_po_user FOREIGN KEY (created_by) REFERENCES users(id)
) ENGINE=InnoDB;

CREATE TABLE purchase_order_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    purchase_order_id BIGINT UNSIGNED NOT NULL,
    product_id INT UNSIGNED NOT NULL,
    quantity DECIMAL(12,3) NOT NULL,
    received_quantity DECIMAL(12,3) NOT NULL DEFAULT 0,
    unit_cost DECIMAL(12,2) NOT NULL,
    total DECIMAL(12,2) GENERATED ALWAYS AS (quantity * unit_cost) STORED,
    CONSTRAINT fk_poi_order FOREIGN KEY (purchase_order_id) REFERENCES purchase_orders(id) ON DELETE CASCADE,
    CONSTRAINT fk_poi_product FOREIGN KEY (product_id) REFERENCES products(id)
) ENGINE=InnoDB;

CREATE TABLE sales (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    invoice_number VARCHAR(40) NOT NULL UNIQUE,
    customer_name VARCHAR(150) NULL,
    operator_id INT UNSIGNED NOT NULL,
    subtotal DECIMAL(12,2) NOT NULL DEFAULT 0,
    discount DECIMAL(12,2) NOT NULL DEFAULT 0,
    total DECIMAL(12,2) NOT NULL DEFAULT 0,
    paid_amount DECIMAL(12,2) NOT NULL DEFAULT 0,
    payment_method ENUM('cash','bKash','Nagad','Rocket','card','mixed') NOT NULL DEFAULT 'cash',
    status ENUM('paid','partial','void') NOT NULL DEFAULT 'paid',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_sales_date_operator (created_at, operator_id),
    CONSTRAINT fk_sales_operator FOREIGN KEY (operator_id) REFERENCES users(id)
) ENGINE=InnoDB;

CREATE TABLE sale_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    sale_id BIGINT UNSIGNED NOT NULL,
    product_id INT UNSIGNED NOT NULL,
    quantity DECIMAL(12,3) NOT NULL,
    unit_price DECIMAL(12,2) NOT NULL,
    unit_cost DECIMAL(12,2) NOT NULL,
    discount DECIMAL(12,2) NOT NULL DEFAULT 0,
    total DECIMAL(12,2) NOT NULL,
    CONSTRAINT fk_si_sale FOREIGN KEY (sale_id) REFERENCES sales(id) ON DELETE CASCADE,
    CONSTRAINT fk_si_product FOREIGN KEY (product_id) REFERENCES products(id)
) ENGINE=InnoDB;

CREATE TABLE pos_held_bills (
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

CREATE TABLE service_types (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL UNIQUE,
    default_fee DECIMAL(12,2) NOT NULL DEFAULT 0,
    is_active BOOLEAN NOT NULL DEFAULT TRUE
) ENGINE=InnoDB;

CREATE TABLE operator_work_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    operator_id INT UNSIGNED NOT NULL,
    service_type_id INT UNSIGNED NULL,
    customer_name VARCHAR(150) NULL,
    customer_phone VARCHAR(30) NULL,
    task_description VARCHAR(255) NOT NULL,
    fee DECIMAL(12,2) NOT NULL DEFAULT 0,
    payment_method ENUM('cash','bKash','Nagad','Rocket','card') NOT NULL DEFAULT 'cash',
    status ENUM('completed','pending','cancelled') NOT NULL DEFAULT 'completed',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_work_logs_operator_date (operator_id, created_at),
    CONSTRAINT fk_work_operator FOREIGN KEY (operator_id) REFERENCES users(id),
    CONSTRAINT fk_work_service FOREIGN KEY (service_type_id) REFERENCES service_types(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE mfs_accounts (
    id TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    provider ENUM('bKash','Nagad','Rocket') NOT NULL UNIQUE,
    account_number VARCHAR(30) NOT NULL,
    opening_float DECIMAL(12,2) NOT NULL DEFAULT 0,
    current_float DECIMAL(12,2) NOT NULL DEFAULT 0,
    is_active BOOLEAN NOT NULL DEFAULT TRUE
) ENGINE=InnoDB;

CREATE TABLE mfs_transactions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    account_id TINYINT UNSIGNED NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    transaction_type ENUM('cash_in','cash_out','send_money','merchant_payment') NOT NULL,
    reference_number VARCHAR(80) NULL,
    customer_name VARCHAR(150) NULL,
    amount DECIMAL(12,2) NOT NULL,
    commission DECIMAL(12,2) NOT NULL DEFAULT 0,
    note VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_mfs_date_provider (created_at, account_id),
    CONSTRAINT fk_mfs_account FOREIGN KEY (account_id) REFERENCES mfs_accounts(id),
    CONSTRAINT fk_mfs_user FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB;

CREATE TABLE vouchers (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    voucher_number VARCHAR(50) NOT NULL UNIQUE,
    voucher_type ENUM('sale','purchase','service','mfs','utility','return','salary','income','expense') NOT NULL,
    reference_id BIGINT UNSIGNED NULL,
    created_by INT UNSIGNED NOT NULL,
    amount DECIMAL(12,2) NOT NULL DEFAULT 0,
    payload JSON NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_vouchers_type_reference (voucher_type, reference_id),
    INDEX idx_vouchers_created_at (created_at),
    CONSTRAINT fk_vouchers_user FOREIGN KEY (created_by) REFERENCES users(id)
) ENGINE=InnoDB;

CREATE TABLE product_returns (
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

CREATE TABLE product_return_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    return_id BIGINT UNSIGNED NOT NULL,
    product_id INT UNSIGNED NOT NULL,
    quantity DECIMAL(12,3) NOT NULL,
    unit_price DECIMAL(12,2) NOT NULL,
    total DECIMAL(12,2) NOT NULL,
    CONSTRAINT fk_return_items_return FOREIGN KEY (return_id) REFERENCES product_returns(id) ON DELETE CASCADE,
    CONSTRAINT fk_return_items_product FOREIGN KEY (product_id) REFERENCES products(id)
) ENGINE=InnoDB;

CREATE TABLE employee_salary_profiles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL UNIQUE,
    monthly_salary DECIMAL(12,2) NOT NULL DEFAULT 0,
    allowance DECIMAL(12,2) NOT NULL DEFAULT 0,
    effective_from DATE NOT NULL,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_salary_profile_user FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB;

CREATE TABLE salary_payments (
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

CREATE TABLE mfs_daily_reconciliations (
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

CREATE TABLE utility_vendors (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    service_type VARCHAR(80) NOT NULL,
    is_active BOOLEAN NOT NULL DEFAULT TRUE
) ENGINE=InnoDB;

CREATE TABLE utility_payments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    vendor_id INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    customer_name VARCHAR(150) NULL,
    account_number VARCHAR(80) NOT NULL,
    bill_amount DECIMAL(12,2) NOT NULL,
    service_charge DECIMAL(12,2) NOT NULL DEFAULT 0,
    status ENUM('paid','pending','failed') NOT NULL DEFAULT 'paid',
    reference_number VARCHAR(80) NULL,
    paid_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_utility_date_status (created_at, status),
    CONSTRAINT fk_utility_vendor FOREIGN KEY (vendor_id) REFERENCES utility_vendors(id),
    CONSTRAINT fk_utility_user FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB;

CREATE TABLE expenses (
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

CREATE TABLE income_records (
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

INSERT INTO roles (name, description) VALUES
('Admin', 'Full system access'), ('Manager', 'Operations and reporting access'), ('Computer Operator', 'POS and service operations');
INSERT INTO permissions (name, label) VALUES
('view_dashboard', 'View dashboard'), ('manage_products', 'Manage products'), ('manage_purchases', 'Manage purchases'),
('process_sales', 'Process sales'), ('manage_services', 'Manage service billing'), ('manage_mfs', 'Manage MFS ledger'),
('manage_utility', 'Manage utility bills'), ('view_financials', 'View financial ledgers'), ('manage_users', 'Manage users'),
('delete_records', 'Delete records');
INSERT INTO role_permissions (role_id, permission_id) SELECT r.id, p.id FROM roles r CROSS JOIN permissions p WHERE r.name = 'Admin';
INSERT INTO role_permissions (role_id, permission_id) SELECT r.id, p.id FROM roles r JOIN permissions p ON p.name IN ('view_dashboard','manage_products','manage_purchases','process_sales','manage_services','manage_mfs','manage_utility','view_financials') WHERE r.name = 'Manager';
INSERT INTO role_permissions (role_id, permission_id) SELECT r.id, p.id FROM roles r JOIN permissions p ON p.name IN ('view_dashboard','process_sales','manage_services') WHERE r.name = 'Computer Operator';
INSERT INTO categories (name, description) VALUES ('Writing', 'Pens, pencils and markers'), ('Paper', 'Notebooks and paper products'), ('Office', 'Office essentials');
INSERT INTO suppliers (name, contact_person, phone, email) VALUES ('General Stationery Supply', 'Accounts Desk', '01700000000', 'supplier@example.local');
INSERT INTO service_types (name, default_fee) VALUES ('Printing', 5), ('Scanning', 10), ('Typing', 100), ('Document Formatting', 150), ('Online Form', 50);
INSERT INTO utility_vendors (name, service_type) VALUES ('DESCO', 'Electricity'), ('Titas', 'Gas'), ('Link3', 'Internet');
INSERT INTO mfs_accounts (provider, account_number, opening_float, current_float) VALUES ('bKash', '01700000000', 25000, 25000), ('Nagad', '01800000000', 15000, 15000), ('Rocket', '01900000000', 10000, 10000);