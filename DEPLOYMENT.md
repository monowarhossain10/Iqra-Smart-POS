# Deployment Guide for IQRA Stationary Solutions

## Infinite Free: iqrapos.free.je

These steps are for the hosting account shown in the supplied control-panel details. Its domain document root is fixed at `htdocs`, so deploy the project root there; do not move only the contents of `public/`.

1. **Rotate the database password before deployment.** The password was visible in the shared screenshot. Set a new password in the hosting panel, then use the new value only in the private `.env` file. Do not put it in this guide, source control, or a support message.
2. **Enable HTTPS** for `iqrapos.free.je` in the hosting panel and verify the certificate works. The supplied `.env.example` uses `https://iqrapos.free.je`; if HTTPS is not active yet, change `APP_URL` to `http://iqrapos.free.je` temporarily and switch it back after SSL is enabled. Do not use real credentials over plain HTTP.
3. **Upload the project root into `htdocs`.** Upload `index.php`, `.htaccess`, `bootstrap.php`, and the `app/`, `config/`, `database/`, `public/`, `resources/`, and `storage/` directories. Do not upload `.git/`, `cookies.txt`, local backups, or logs. Keep `public/` as a child directory; the root front controller serves the application and `public/assets/` serves its CSS and JavaScript.
4. **Create the private environment file.** Copy `.env.example` to `.env` in `htdocs`, set `DB_PASSWORD` to the newly rotated password, and keep the other supplied values as shown. `.env` is excluded by `.gitignore` and denied by the root `.htaccess`.
5. **Import the schema into the assigned database.** Open phpMyAdmin from the hosting panel, select `if0_40311012_iqrapos`, then import `database/schema.sql`. This file intentionally does not create or select a database; do not import it into one of the other databases listed in the panel. Back up first and do not import the fresh-install schema over existing tables.
6. **Create the first administrator.** If the host permits remote MySQL from your local CLI, run `php database/create_admin.php "Iqra Admin" admin@example.com "YOUR-STRONG-ADMIN-PASSWORD"` locally after configuring `.env`. Otherwise, generate a password hash locally with PHP and insert a row in phpMyAdmin into `users` with `role_id` selected from the `Admin` row in `roles`; never store the plain admin password in SQL.
7. **Verify the deployment.** Visit `https://iqrapos.free.je/`, sign in, check the dashboard and POS, then make a small test transaction if appropriate. Confirm `/config/config.php`, `/database/schema.sql`, and `/.env` are denied (403/404), and test POS search and mobile layout.

The host-provided database values in `.env.example` are the database hostname, username, and assigned database name from the panel; do not use `localhost` or the account/domain name as the database name. Choose PHP 8.1 or newer in the hosting panel.

The remaining sections below describe general Apache/VPS deployment and troubleshooting. They are not steps to configure Infinite Free's server or document root.

## Prerequisites

- PHP 8.1 or higher
- MySQL 5.7 or higher / MariaDB 10.3 or higher
- Apache with mod_rewrite enabled
- SSL certificate (recommended for production)

## Pre-Deployment Checklist

### 1. Database Setup

```bash
# Import database schema
mysql -u username -p database_name < database/schema.sql

# Create admin user if needed
php database/create_admin.php
```

### 2. Environment Configuration

```bash
# Copy example environment file
cp .env.example .env

# Edit .env with your production values
nano .env
```

### 3. File Permissions

```bash
# Set proper permissions
chmod 755 public
chmod 644 public/.htaccess
chmod 755 storage/logs
chmod 644 storage/logs/error.log
```

### 4. Apache Configuration

Ensure the following Apache modules are enabled:
- mod_rewrite
- mod_deflate
- mod_expires

## Production Server Setup

### Document Root

Set your document root to the `public` directory:
```
/var/www/html/your-domain.com/public
```

### Apache Virtual Host Example

```apache
<VirtualHost *:80>
    ServerName your-domain.com
    DocumentRoot /var/www/html/your-domain.com/public

    <Directory /var/www/html/your-domain.com/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/your-domain.com-error.log
    CustomLog ${APACHE_LOG_DIR}/your-domain.com-access.log combined
</VirtualHost>
```

### SSL Configuration (Recommended)

```apache
<VirtualHost *:443>
    ServerName your-domain.com
    DocumentRoot /var/www/html/your-domain.com/public

    SSLEngine on
    SSLCertificateFile /path/to/certificate.crt
    SSLCertificateKeyFile /path/to/private.key

    <Directory /var/www/html/your-domain.com/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

## Security Considerations

### 1. Disable Directory Browsing
Already configured in `public/.htaccess`

### 2. Protect Sensitive Files
Already configured in `public/.htaccess` for:
- `.env` files
- Log files
- Configuration directories

### 3. Enable HTTPS
Uncomment the HTTPS redirect in `public/.htaccess`:
```apache
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

### 4. Session Security
Session cookies are configured with:
- `httponly` flag (prevents JavaScript access)
- `secure` flag (only sent over HTTPS)
- `samesite` Lax (CSRF protection)

## Troubleshooting

### Blank Pages

1. Check error logs:
```bash
tail -f storage/logs/error.log
```

2. Enable error display temporarily in `config/config.php`:
```php
ini_set('display_errors', '1');
```

3. Check Apache error logs:
```bash
tail -f /var/log/apache2/error.log
```

### Database Connection Issues

1. Verify database credentials in `.env`
2. Check database server is running
3. Ensure database user has proper permissions

### Permission Issues

```bash
# Fix common permission issues
sudo chown -R www-data:www-data storage/logs
sudo chmod -R 755 storage/logs
```

### URL Rewriting Not Working

1. Ensure mod_rewrite is enabled:
```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

2. Check `.htaccess` is being read:
```bash
# Test by adding garbage to .htaccess - should cause 500 error
```

## Performance Optimization

### 1. Enable OPcache
Edit `php.ini`:
```ini
opcache.enable=1
opcache.memory_consumption=128
opcache.max_accelerated_files=4000
opcache.revalidate_freq=60
```

### 2. Enable Compression
Already configured in `public/.htaccess`

### 3. Browser Caching
Already configured in `public/.htaccess`

## Monitoring

### Log Files

- Application errors: `storage/logs/error.log`
- Apache errors: `/var/log/apache2/error.log`
- Apache access: `/var/log/apache2/access.log`

### Health Check

Create a simple health check endpoint by accessing:
```
https://your-domain.com/?route=dashboard
```

## Backup Strategy

### Database Backup
```bash
mysqldump -u username -p database_name > backup_$(date +%Y%m%d).sql
```

### File Backup
```bash
tar -czf backup_$(date +%Y%m%d).tar.gz /path/to/application
```

## Post-Deployment Verification

1. [ ] Login page loads correctly
2. [ ] Authentication works
3. [ ] Dashboard displays data
4. [ ] All menu items navigate correctly
5. [ ] Forms submit successfully
6. [ ] Vouchers print correctly
7. [ ] Product labels print correctly
8. [ ] Error logs are being written
9. [ ] SSL certificate is valid (if using HTTPS)
10. [ ] Mobile responsive design works

## Support

For issues not covered in this guide, check:
1. `storage/logs/error.log` for application errors
2. Apache error logs for server errors
3. Network tab in browser for failed requests