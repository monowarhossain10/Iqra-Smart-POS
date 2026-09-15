# Iqra Stationary Solutions ERP

A PHP 8.1+ / MySQL 8 retail ERP foundation for stationery sales, services, inventory, suppliers, MFS, utility collection and reporting.

## Local setup

1. Copy `.env.example` to `.env` and set the database credentials. The current lightweight bootstrap reads environment variables supplied by Apache/PHP; in production, inject them through the host rather than committing secrets.
2. Install the database schema from the project root:

```bash
php database/install.php
```

Alternatively, import `database/schema.sql` directly into MySQL.

3. Create the first administrator from the project root:

```bash
php database/create_admin.php "Iqra Admin" admin@example.com "use-a-strong-password"
```

4. Point Apache at `public/` (recommended), enable `mod_rewrite`, and open the configured `APP_URL`.

When upgrading an existing installation, apply the voucher and MFS reconciliation migration:

```bash
php database/migrate.php
```

For product suppliers, editable barcodes, and product label printing, apply:

```bash
php database/migrate.php migration_006_product_suppliers.sql
```

For persistent POS held bills, apply:

```bash
php database/migrate.php migration_007_pos_held_bills.sql
```

For per-product purchase discounts and supplier payment reconciliation, apply:

```bash
php database/migrate.php migration_008_purchase_discounts_payments.sql
```

For product descriptions and searchable purchase product entry, apply:

```bash
php database/migrate.php migration_009_product_descriptions.sql
```

For the returns and payroll features on an existing installation, run:

```bash
php database/migrate.php migration_002_returns_salaries.sql
```

Completed sales, purchases, services, MFS transactions and utility payments create printable vouchers. MFS reconciliation is available from the MFS Ledger page, where administrators can also update provider account numbers without changing their float balances.

For PHP's built-in server during development:

```bash
php -S localhost:8000 -t public
```

Use `APP_URL=http://localhost:8000` for that server.

## Structure

- `config/` environment and secure PDO connection
- `app/Services/` authentication, permission and dashboard logic
- `app/Controllers/` JSON/API request handlers
- `resources/views/` server-rendered Bootstrap views
- `public/assets/` CSS and ES6 browser interactions
- `database/` normalized schema and first-admin provisioning

All write endpoints should preserve the existing CSRF token convention, use PDO prepared statements, and check `Auth::can()` before adding new mutations.