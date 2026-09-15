<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/bootstrap.php';

if (PHP_SAPI !== 'cli') {
    exit("Run this file from the command line.\n");
}

$name = $argv[1] ?? null;
$email = $argv[2] ?? null;
$password = $argv[3] ?? null;
if (!$name || !$email || !$password || strlen($password) < 10) {
    exit("Usage: php database/create_admin.php \"Admin Name\" admin@example.com StrongPassword\nPassword must be at least 10 characters.\n");
}

$db = Database::connection();
$roleId = $db->query("SELECT id FROM roles WHERE name = 'Admin'")->fetchColumn();
$statement = $db->prepare('INSERT INTO users (role_id, full_name, email, password_hash) VALUES (?, ?, ?, ?)');
$statement->execute([$roleId, $name, $email, password_hash($password, PASSWORD_DEFAULT)]);
echo "Admin user created: {$email}\n";