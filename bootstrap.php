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
    
    // Log missing class errors
    error_log("Autoload failed for class: $class");
});

// Set custom error handler for better debugging
set_error_handler(function(int $errno, string $errstr, string $errfile, int $errline): bool {
    if (!(error_reporting() & $errno)) {
        return false;
    }
    
    $error_message = "Error [$errno]: $errstr in $errfile on line $errline";
    error_log($error_message);
    
    if (APP_ENV === 'local') {
        echo "<div style='background: #fee; border: 1px solid #c00; padding: 10px; margin: 10px; font-family: monospace;'>"
            . "<strong>Error:</strong> $error_message</div>";
    }
    
    return true;
});

// Set exception handler
set_exception_handler(function(Throwable $exception): void {
    $error_message = "Uncaught Exception: " . $exception->getMessage() . " in " . $exception->getFile() . " on line " . $exception->getLine();
    error_log($error_message);
    
    if (APP_ENV === 'local') {
        echo "<div style='background: #fee; border: 1px solid #c00; padding: 10px; margin: 10px; font-family: monospace;'>"
            . "<strong>Exception:</strong> $error_message<br>"
            . "<pre>" . $exception->getTraceAsString() . "</pre></div>";
    } else {
        echo "<h1>Internal Server Error</h1><p>An error occurred. Please try again later.</p>";
    }
});