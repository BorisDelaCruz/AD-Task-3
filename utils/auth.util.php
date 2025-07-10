<?php
declare(strict_types=1);

class Auth {
    private static $pdo = null;
    
    private static function getConnection() {
        if (self::$pdo === null) {
            require_once __DIR__ . '/envSetter.util.php';
            global $databases;
            
            $dsn = "pgsql:host={$databases['pgHost']};port={$databases['pgPort']};dbname={$databases['pgDB']}";
            self::$pdo = new PDO($dsn, $databases['pgUser'], $databases['pgPassword'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
        }
        return self::$pdo;
    }
    
    public static function login(string $username, string $password): array {
        try {
            $pdo = self::getConnection();
            
            $stmt = $pdo->prepare("
                SELECT id, username, first_name, middle_name, last_name, password, role, created_at 
                FROM users 
                WHERE username = :username
            ");
            $stmt->execute([':username' => $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$user) {
                return ['success' => false, 'message' => 'Invalid username or password.'];
            }
            
            if (!password_verify($password, $user['password'])) {
                return ['success' => false, 'message' => 'Invalid username or password.'];
            }
            
            // Remove password from user data
            unset($user['password']);
            
            // Start session and store user data
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            
            $_SESSION['user'] = $user;
            $_SESSION['logged_in'] = true;
            $_SESSION['login_time'] = time();
            
            return [
                'success' => true, 
                'message' => 'Login successful.',
                'user' => $user
            ];
            
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
        }
    }
    
    public static function logout(): bool {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Clear all session data
        $_SESSION = array();
        
        // Destroy the session cookie
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time()-3600, '/');
        }
        
        // Destroy the session
        session_destroy();
        
        return true;
    }
    
    public static function isLoggedIn(): bool {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }
    
    public static function getUser(): ?array {
        if (!self::isLoggedIn()) {
            return null;
        }
        
        return $_SESSION['user'] ?? null;
    }
    
    public static function hasRole(string $role): bool {
        $user = self::getUser();
        if (!$user) {
            return false;
        }
        
        return $user['role'] === $role;
    }
    
    public static function hasAnyRole(array $roles): bool {
        $user = self::getUser();
        if (!$user) {
            return false;
        }
        
        return in_array($user['role'], $roles);
    }
    
    public static function requireAuth(): void {
        if (!self::isLoggedIn()) {
            header('Location: /pages/login/');
            exit;
        }
    }
    
    public static function requireRole(string $role): void {
        self::requireAuth();
        
        if (!self::hasRole($role)) {
            header('Location: /pages/error/?code=403');
            exit;
        }
    }
    
    public static function requireAnyRole(array $roles): void {
        self::requireAuth();
        
        if (!self::hasAnyRole($roles)) {
            header('Location: /pages/error/?code=403');
            exit;
        }
    }
    
    public static function getUserFullName(): string {
        $user = self::getUser();
        if (!$user) {
            return '';
        }
        
        $name = $user['first_name'];
        if (!empty($user['middle_name'])) {
            $name .= ' ' . $user['middle_name'];
        }
        $name .= ' ' . $user['last_name'];
        
        return $name;
    }
    
    public static function getSessionDuration(): int {
        if (!self::isLoggedIn()) {
            return 0;
        }
        
        return time() - ($_SESSION['login_time'] ?? time());
    }
}
