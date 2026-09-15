<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';

if (PHP_SAPI !== 'cli') {
    exit("Run this installer from the command line.\n");
}

$host = env('DB_HOST', '127.0.0.1');
$port = env('DB_PORT', '3306');
$name = env('DB_NAME', 'iqra_erp');
$user = env('DB_USER', 'root');
$password = env('DB_PASSWORD', '');

try {
    $server = new PDO("mysql:host={$host};port={$port};charset=utf8mb4", $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    $server->exec('CREATE DATABASE IF NOT EXISTS `' . str_replace('`', '``', $name) . '` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
    $db = new PDO("mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4", $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    $schema = file_get_contents(__DIR__ . '/schema.sql');
    $schema = preg_replace('/^CREATE DATABASE.*?;\s*|^USE .*?;\s*/mi', '', (string) $schema);
    foreach (array_filter(array_map('trim', explode(';', (string) $schema))) as $statement) {
        $db->exec($statement);
    }
    echo "Database '{$name}' is ready.\n";
} catch (Throwable $error) {
    fwrite(STDERR, "Database setup failed: {$error->getMessage()}\n");
    exit(1);
}