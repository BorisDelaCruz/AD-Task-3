<?php
require_once __DIR__ . '/../../utils/auth.util.php';

// Start session for flash messages
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Redirect if already logged in
if (Auth::isLoggedIn()) {
    header('Location: /pages/dashboard/');
    exit;
}

// Get flash messages
$flashMessage = $_SESSION['flash_message'] ?? '';
$flashType = $_SESSION['flash_type'] ?? '';

// Clear flash messages
unset($_SESSION['flash_message'], $_SESSION['flash_type']);

$error = '';
$success = '';

// Handle form submission directly
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Username and password are required.';
    } else {
        // Attempt login
        $loginResult = Auth::login($username, $password);
        
        if ($loginResult['success']) {
            // Login successful - redirect to dashboard
            header('Location: /pages/dashboard/');
            exit;
        } else {
            $error = $loginResult['message'];
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - AD-Task-3</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <h1>Login</h1>
    
    <!-- Flash Messages Display -->
    <?php if (!empty($flashMessage)): ?>
        <div class="<?= $flashType === 'success' ? 'success' : 'error' ?>">
            <?= htmlspecialchars($flashMessage) ?>
        </div>
    <?php endif; ?>
    
    <!-- Error/Success messages will be inserted here by JavaScript -->
    <div id="message-container"></div>
    
    <!-- 
    CONNECTING BACKEND AND FRONTEND: 
    1. Form action points to the handler: /handlers/auth.handler.php
    2. Method is POST to match the handler
    3. Input names match exactly with $_POST keys in handler
    -->
    <form method="POST" action="/handlers/auth.handler.php" id="loginForm">
        <!-- Hidden input to specify action for the handler -->
        <input type="hidden" name="action" value="login">
        
        <div class="form-group">
            <label for="username">Username:</label>
            <!-- 
            BACKEND CONNECTION: 
            name="username" matches $_POST['username'] in auth.handler.php 
            -->
            <input 
                type="text" 
                id="username" 
                name="username" 
                required 
                autocomplete="username"
                placeholder="Enter your username"
            >
        </div>
        
        <div class="form-group">
            <label for="password">Password:</label>
            <!-- 
            BACKEND CONNECTION: 
            name="password" matches $_POST['password'] in auth.handler.php 
            -->
            <input 
                type="password" 
                id="password" 
                name="password" 
                required 
                autocomplete="current-password"
                placeholder="Enter your password"
            >
        </div>
        
        <!-- 
        FRONTEND TRIGGER: 
        Button submits form to the handler via POST method 
        -->
        <button type="submit" id="loginBtn">
            <span class="btn-text">Login</span>
            <span class="loading" id="loadingText">Logging in...</span>
        </button>
    </form>
    
    <!-- Demo Accounts with Click-to-Fill functionality -->
    <div class="demo-accounts">
        <h3>Demo Accounts (Click to auto-fill):</h3>
        <p onclick="fillLogin('jane.doe', 'SecurePass456')">
            <strong>Admin:</strong> jane.doe / SecurePass456
        </p>
        <p onclick="fillLogin('bob.wilson', 'MyPassword789')">
            <strong>Manager:</strong> bob.wilson / MyPassword789
        </p>
        <p onclick="fillLogin('alice.johnson', 'AlicePass123')">
            <strong>Developer:</strong> alice.johnson / AlicePass123
        </p>
        <p onclick="fillLogin('john.smith', 'p@ssW0rd1234')">
            <strong>Designer:</strong> john.smith / p@ssW0rd1234
        </p>
        <p onclick="fillLogin('charlie.brown', 'CharlieSecure456')">
            <strong>User:</strong> charlie.brown / CharlieSecure456
        </p>
    </div>
    
    <div class="links">
        <a href="/pages/signup/">Sign Up</a> |
        <a href="/">Back to Home</a>
    </div>
    
    <script src="assets/script.js"></script>
</body>
</html>
