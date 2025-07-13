<?php
require_once __DIR__ . '/../../utils/auth.util.php';
require_once __DIR__ . '/../../utils/envSetter.util.php';

// Start session for flash messages
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Redirect if already logged in
if (Auth::isLoggedIn()) {
    header('Location: /pages/dashboard/');
    exit;
}

// Handle direct form submission (fallback)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $middle_name = trim($_POST['middle_name'] ?? '');
    
    if (!empty($username) && !empty($password) && !empty($first_name) && !empty($last_name)) {
        try {
            // Get database connection
            global $databases;
            $dsn = "pgsql:host={$databases['pgHost']};port={$databases['pgPort']};dbname={$databases['pgDB']}";
            $pdo = new PDO($dsn, $databases['pgUser'], $databases['pgPassword'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
            
            // Check if username already exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username");
            $stmt->execute([':username' => $username]);
            
            if ($stmt->fetch()) {
                $error = 'Username already exists.';
            } else {
                // Create new user
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("
                    INSERT INTO users (username, password, first_name, middle_name, last_name, role) 
                    VALUES (:username, :password, :first_name, :middle_name, :last_name, 'user')
                ");
                
                $stmt->execute([
                    ':username' => $username,
                    ':password' => $hashedPassword,
                    ':first_name' => $first_name,
                    ':middle_name' => $middle_name ?: null,
                    ':last_name' => $last_name
                ]);
                
                $success = 'Account created successfully! You can now login.';
            }
            
        } catch (PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    } else {
        $error = 'Username, password, first name, and last name are required.';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sign Up - AD-Task-3</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <h1>Sign Up</h1>
    
    <!-- Error/Success messages from server -->
    <?php if (isset($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <?php if (isset($success)): ?>
        <div class="success">
            <?= htmlspecialchars($success) ?>
            <br><a href="/pages/login/">Click here to login</a>
        </div>
    <?php endif; ?>
    
    <!-- Message container for AJAX responses -->
    <div id="message-container"></div>
    
    <form method="POST" action="" id="signupForm">
        <div class="form-group">
            <label for="username">Username <span class="required">*</span>:</label>
            <input 
                type="text" 
                id="username" 
                name="username" 
                value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" 
                required 
                autocomplete="username"
                placeholder="Choose a unique username"
            >
        </div>
        
        <div class="form-group">
            <label for="password">Password <span class="required">*</span>:</label>
            <input 
                type="password" 
                id="password" 
                name="password" 
                required 
                autocomplete="new-password"
                placeholder="Choose a strong password"
            >
        </div>
        
        <div class="form-group">
            <label for="first_name">First Name <span class="required">*</span>:</label>
            <input 
                type="text" 
                id="first_name" 
                name="first_name" 
                value="<?= htmlspecialchars($_POST['first_name'] ?? '') ?>" 
                required 
                autocomplete="given-name"
                placeholder="Your first name"
            >
        </div>
        
        <div class="form-group">
            <label for="middle_name">Middle Name (optional):</label>
            <input 
                type="text" 
                id="middle_name" 
                name="middle_name" 
                value="<?= htmlspecialchars($_POST['middle_name'] ?? '') ?>" 
                autocomplete="additional-name"
                placeholder="Your middle name (optional)"
            >
        </div>
        
        <div class="form-group">
            <label for="last_name">Last Name <span class="required">*</span>:</label>
            <input 
                type="text" 
                id="last_name" 
                name="last_name" 
                value="<?= htmlspecialchars($_POST['last_name'] ?? '') ?>" 
                required 
                autocomplete="family-name"
                placeholder="Your last name"
            >
        </div>
        
        <button type="submit" id="signupBtn">
            <span class="btn-text">Sign Up</span>
            <span class="loading" id="loadingText">Creating account...</span>
        </button>
    </form>
    
    <div class="links">
        <a href="/pages/login/">Already have an account? Login</a> |
        <a href="/">Back to Home</a>
    </div>
    
    <script src="assets/script.js"></script>
</body>
</html>
