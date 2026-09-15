ALTER TABLE purchase_orders
    ADD COLUMN paid_amount DECIMAL(12,2) NOT NULL DEFAULT 0 AFTER total,
    ADD COLUMN payment_method ENUM('cash','bKash','Nagad','Rocket','card','bank') NOT NULL DEFAULT 'cash' AFTER paid_amount,
    ADD COLUMN payment_reference VARCHAR(100) NULL AFTER payment_method,
    ADD COLUMN paid_at DATETIME NULL AFTER payment_reference;

ALTER TABLE purchase_order_items
    ADD COLUMN discount DECIMAL(12,2) NOT NULL DEFAULT 0 AFTER unit_cost;

ALTER TABLE purchase_order_items
    MODIFY total DECIMAL(12,2) GENERATED ALWAYS AS ((quantity * unit_cost) - discount) STORED;