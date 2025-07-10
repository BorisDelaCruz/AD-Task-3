<?php
declare(strict_types=1);

require_once __DIR__ . '/../utils/auth.util.php';
require_once __DIR__ . '/../utils/htmlEscape.util.php';

header('Content-Type: application/json');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Get the action from POST data
$action = $_POST['action'] ?? '';

switch ($action) {
    case 'login':
        handleLogin();
        break;
        
    case 'logout':
        handleLogout();
        break;
        
    default:
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        break;
}

function handleLogin(): void {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Validate input
    if (empty($username) || empty($password)) {
        echo json_encode([
            'success' => false, 
            'message' => 'Username and password are required.'
        ]);
        return;
    }
    
    // Sanitize username
    $username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
    
    // Attempt login
    $result = Auth::login($username, $password);
    
    if ($result['success']) {
        echo json_encode([
            'success' => true,
            'message' => $result['message'],
            'redirect' => '/pages/dashboard/',
            'user' => [
                'id' => $result['user']['id'],
                'username' => $result['user']['username'],
                'full_name' => Auth::getUserFullName(),
                'role' => $result['user']['role']
            ]
        ]);
    } else {
        http_response_code(401);
        echo json_encode([
            'success' => false,
            'message' => $result['message']
        ]);
    }
}

function handleLogout(): void {
    $success = Auth::logout();
    
    if ($success) {
        echo json_encode([
            'success' => true,
            'message' => 'Logged out successfully.',
            'redirect' => '/pages/login/'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Logout failed.'
        ]);
    }
}
