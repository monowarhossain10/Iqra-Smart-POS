<?php
/**
 * Iqra Smart POS - Shared Hosting Bootstrapper
 * Redirects the web root traffic safely to the public entry point.
 */

// Define the relative path to the true public index
$publicIndex = __DIR__ . '/public/index.php';

if (file_exists($publicIndex)) {
    require_once $publicIndex;
} else {
    header("HTTP/1.1 500 Internal Server Error");
    echo "<h2>Internal Server Error</h2>";
    echo "The core application directory structure is missing or inaccessible.";
    exit;
}
