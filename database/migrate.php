<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';

$host = env('DB_HOST', '127.0.0.1');
$port = env('DB_PORT', '3306');
$name = env('DB_NAME', 'iqra_erp');
$user = env('DB_USER', 'root');
$password = env('DB_PASSWORD', '');

try {
    $db = new PDO("mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4", $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false]);
    $migration = basename($argv[1] ?? 'migration_001_vouchers_mfs.sql');
    $sql = (string) file_get_contents(__DIR__ . '/' . $migration);
    foreach (array_filter(array_map('trim', explode(';', $sql))) as $statement) {
        $db->exec($statement);
    }
    echo "Migration {$migration} applied.\n";
} catch (Throwable $error) {
    fwrite(STDERR, "Migration failed: {$error->getMessage()}\n");
    exit(1);
}
