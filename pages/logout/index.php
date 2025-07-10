<?php
require_once __DIR__ . '/../../utils/auth.util.php';

// Handle logout
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    Auth::logout();
    header('Location: /pages/login/');
    exit;
}

// Check if user is logged in
$isLoggedIn = Auth::isLoggedIn();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Logout - AD-Task-3</title>
</head>
<body>
    <?php if ($isLoggedIn): ?>
        <h1>Logout Confirmation</h1>
        <p>Hello, <?= htmlspecialchars(Auth::getUserFullName()) ?>!</p>
        <p>Are you sure you want to log out of your account?</p>
        
        <form method="POST">
            <button type="submit">Yes, Logout</button>
        </form>
        
        <p><a href="/pages/dashboard/"><button>Cancel</button></a></p>
    <?php else: ?>
        <h1>Already Logged Out</h1>
        <p>You are currently not logged into the system.</p>
        
        <p><a href="/pages/login/"><button>Login Now</button></a></p>
        <p><a href="/"><button>Go Home</button></a></p>
    <?php endif; ?>
    
</body>
</html>
