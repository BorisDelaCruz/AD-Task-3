<?php
declare(strict_types=1);

require_once __DIR__ . '/../utils/auth.util.php';

/**
 * Authentication Controller
 * Centralized controller for all authentication-related operations
 */
class AuthController {
    
    /**
     * Process Login Request
     */
    public static function processLogin(array $credentials): array {
        try {
            // Validate credentials
            if (empty($credentials['username']) || empty($credentials['password'])) {
                return [
                    'success' => false,
                    'message' => 'Username and password are required.',
                    'redirect' => null
                ];
            }
            
            // Attempt login
            $result = Auth::login($credentials['username'], $credentials['password']);
            
            if ($result['success']) {
                return [
                    'success' => true,
                    'message' => 'Login successful! Redirecting to dashboard...',
                    'redirect' => '/pages/dashboard/',
                    'user' => $result['user']
                ];
            } else {
                return [
                    'success' => false,
                    'message' => $result['message'],
                    'redirect' => null
                ];
            }
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'An error occurred during login. Please try again.',
                'redirect' => null
            ];
        }
    }
    
    /**
     * Process Logout Request
     */
    public static function processLogout(): array {
        try {
            if (!Auth::isLoggedIn()) {
                return [
                    'success' => false,
                    'message' => 'No active session to logout.',
                    'redirect' => '/pages/login/'
                ];
            }
            
            $logoutSuccess = Auth::logout();
            
            if ($logoutSuccess) {
                return [
                    'success' => true,
                    'message' => 'Logout successful.',
                    'redirect' => '/pages/login/'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Logout failed. Please try again.',
                    'redirect' => null
                ];
            }
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'An error occurred during logout.',
                'redirect' => null
            ];
        }
    }
    
    /**
     * Get Current User Status
     */
    public static function getUserStatus(): array {
        if (Auth::isLoggedIn()) {
            $user = Auth::getUser();
            return [
                'logged_in' => true,
                'user' => [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'full_name' => Auth::getUserFullName(),
                    'role' => $user['role']
                ]
            ];
        } else {
            return [
                'logged_in' => false,
                'user' => null
            ];
        }
    }
}