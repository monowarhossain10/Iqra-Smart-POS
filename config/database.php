<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

final class Database
{
    private static ?PDO $connection = null;

    public static function connection(): PDO
    {
        if (self::$connection instanceof PDO) {
            return self::$connection;
        }

        $host = env('DB_HOST', '127.0.0.1');
        $port = env('DB_PORT', '3306');
        $name = env('DB_NAME', 'iqra_erp');
        $user = env('DB_USER', 'root');
        $password = env('DB_PASSWORD', '');
        $dsn = "mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4";

        self::$connection = new PDO($dsn, $user, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);

        return self::$connection;
    }
}