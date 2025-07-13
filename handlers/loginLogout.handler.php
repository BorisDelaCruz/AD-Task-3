<?php
declare(strict_types=1);

require_once __DIR__ . '/../utils/auth.util.php';
require_once __DIR__ . '/../utils/htmlEscape.util.php';

// Start session for flash messages
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/**
 * Simple Login/Logout Handler
 */

// Check request method and action
$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'login':
        handleLogin();
        break;
        
    case 'logout':
        handleLogout();
        break;
        
    default:
        // If no action specified, check if it's a direct form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && isset($_POST['password'])) {
            handleLogin();
        } else {
            // Return error for invalid action
            echo json_encode(['success' => false, 'message' => 'Invalid action specified.']);
        }
        break;
}

/**
 * Handle Login Logic
 */
function handleLogin(): void {
    try {
        // Get form data
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        
        // Basic validation
        if (empty($username) || empty($password)) {
            redirectWithError('Username and password are required.', '/pages/login/');
            return;
        }
        
        // Check if user is already logged in
        if (Auth::isLoggedIn()) {
            header('Location: /pages/dashboard/');
            exit;
        }
        
        // Attempt login
        $loginResult = Auth::login($username, $password);
        
        if ($loginResult['success']) {
            // Login successful
            $_SESSION['flash_message'] = 'Login successful! Welcome back.';
            $_SESSION['flash_type'] = 'success';
            header('Location: /pages/dashboard/');
            exit;
        } else {
            // Login failed
            redirectWithError($loginResult['message'], '/pages/login/');
        }
        
    } catch (Exception $e) {
        redirectWithError('An unexpected error occurred during login.', '/pages/login/');
    }
}

/**
 * Handle Logout Logic
 */
function handleLogout(): void {
    try {
        // Check if user is logged in
        if (!Auth::isLoggedIn()) {
            redirectWithError('No active session to logout.', '/pages/login/');
            return;
        }
        
        // Perform logout
        $logoutSuccess = Auth::logout();
        
        if ($logoutSuccess) {
            $_SESSION['flash_message'] = 'You have been logged out successfully.';
            $_SESSION['flash_type'] = 'success';
            header('Location: /pages/login/');
            exit;
        } else {
            redirectWithError('Logout failed. Please try again.', '/pages/logout/');
        }
        
    } catch (Exception $e) {
        redirectWithError('An unexpected error occurred during logout.', '/pages/logout/');
    }
}

/**
 * Redirect with error message
 */
function redirectWithError(string $message, string $location): void {
    $_SESSION['flash_message'] = $message;
    $_SESSION['flash_type'] = 'danger';
    header("Location: {$location}");
    exit;
}