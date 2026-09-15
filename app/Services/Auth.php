<?php
declare(strict_types=1);

final class Auth
{
    public static function attempt(string $email, string $password): bool
    {
        $statement = Database::connection()->prepare(
            'SELECT u.*, r.name AS role_name FROM users u JOIN roles r ON r.id = u.role_id WHERE u.email = :email AND u.is_active = 1 LIMIT 1'
        );
        $statement->execute(['email' => trim($email)]);
        $user = $statement->fetch();

        if (!$user || !password_verify($password, $user['password_hash'])) {
            return false;
        }

        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id' => (int) $user['id'],
            'name' => $user['full_name'],
            'email' => $user['email'],
            'role' => $user['role_name'],
        ];
        Database::connection()->prepare('UPDATE users SET last_login_at = NOW() WHERE id = ?')->execute([$user['id']]);
        return true;
    }

    public static function check(): bool
    {
        return !empty($_SESSION['user']);
    }

    public static function user(): array
    {
        return $_SESSION['user'] ?? [];
    }

    public static function logout(): void
    {
        $_SESSION = [];
        session_destroy();
    }

    public static function can(string $permission): bool
    {
        if (!self::check()) {
            return false;
        }

        static $permissions;
        if ($permissions === null) {
            $statement = Database::connection()->prepare(
                'SELECT p.name FROM permissions p JOIN role_permissions rp ON rp.permission_id = p.id JOIN users u ON u.role_id = rp.role_id WHERE u.id = ?'
            );
            $statement->execute([self::user()['id']]);
            $permissions = array_column($statement->fetchAll(), 'name');
        }

        return in_array($permission, $permissions, true);
    }
}