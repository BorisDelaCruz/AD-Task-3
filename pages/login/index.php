<?php
require_once __DIR__ . '/../../utils/auth.util.php';

// Redirect if already logged in
if (Auth::isLoggedIn()) {
    header('Location: /pages/dashboard/');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (!empty($username) && !empty($password)) {
        $result = Auth::login($username, $password);
        
        if ($result['success']) {
            header('Location: /pages/dashboard/');
            exit;
        } else {
            $error = $result['message'];
        }
    } else {
        $error = 'Username and password are required.';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - AD-Task-3</title>
</head>
<body>
    <h1>Login</h1>
    
    <?php if (isset($error)): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    
    <form method="POST">
        <p>
            <label>Username:</label><br>
            <input type="text" name="username" required>
        </p>
        
        <p>
            <label>Password:</label><br>
            <input type="password" name="password" required>
        </p>
        
        <p>
            <button type="submit">Login</button>
        </p>
    </form>
    
    <h3>Demo Accounts:</h3>
    <p>Admin: jane.doe / SecurePass456</p>
    <p>Manager: bob.wilson / MyPassword789</p>
    <p>Developer: alice.johnson / AlicePass123</p>
    <p>Designer: john.smith / p@ssW0rd1234</p>
    <p>User: charlie.brown / CharlieSecure456</p>
    
    <p><a href="/pages/signup/"><button>Sign Up</button></a></p>
    <p><a href="/"><button>Back to Home</button></a></p>
    
</body>
</html>
