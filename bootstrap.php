<?php
declare(strict_types=1);

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

spl_autoload_register(function (string $class): void {
    $paths = [
        __DIR__ . '/app/Controllers/' . $class . '.php',
        __DIR__ . '/app/Models/' . $class . '.php',
        __DIR__ . '/app/Services/' . $class . '.php',
    ];

    foreach ($paths as $path) {
        if (is_file($path)) {
            require_once $path;
            return;
        }
    }
});