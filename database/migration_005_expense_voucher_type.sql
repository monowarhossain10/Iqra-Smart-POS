-- Add 'expense' to voucher_type enum in vouchers table
USE iqra_erp;

ALTER TABLE vouchers MODIFY COLUMN voucher_type ENUM('sale','purchase','service','mfs','utility','return','salary','income','expense') NOT NULL;
