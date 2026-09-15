<?php
declare(strict_types=1);

$envFile = dirname(__DIR__) . '/.env';
if (is_file($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
            continue;
        }
        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value, " \t\n\r\0\x0B\"");
        $_ENV[$key] ??= $value;
    }
}

define('APP_NAME', 'Iqra Stationary Solutions');
$requestScheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$requestHost = $_SERVER['HTTP_HOST'] ?? null;
$defaultAppUrl = $requestHost ? $requestScheme . '://' . $requestHost : 'http://localhost/iqra';
define('APP_URL', rtrim($_ENV['APP_URL'] ?? $defaultAppUrl, '/'));
define('APP_ENV', $_ENV['APP_ENV'] ?? 'local');

date_default_timezone_set($_ENV['APP_TIMEZONE'] ?? 'Asia/Dhaka');

if (session_status() === PHP_SESSION_NONE) {
    session_name('iqra_erp_session');
    session_set_cookie_params([
        'httponly' => true,
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'samesite' => 'Lax',
    ]);
    session_start();
}

function env(string $key, mixed $default = null): mixed
{
    return $_ENV[$key] ?? $_SERVER[$key] ?? $default;
}

function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function csrf_token(): string
{
    if (empty($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['_csrf'];
}

function verify_csrf(?string $token): bool
{
    return is_string($token) && hash_equals($_SESSION['_csrf'] ?? '', $token);
}

function redirect(string $path): never
{
    header('Location: ' . APP_URL . '/' . ltrim($path, '/'));
    exit;
}