<?php
require_once __DIR__ . '/handlers/mongodbChecker.handler.php';
require_once __DIR__ . '/handlers/postgreChecker.handler.php';
require_once __DIR__ . '/utils/auth.util.php';

// Check if user is logged in
$isLoggedIn = Auth::isLoggedIn();

// Get database connection status
$postgresStatus = checkPostgreSQLConnection();
$mongoStatus = checkMongoDBConnection();
?>

<!DOCTYPE html>
<html>
<head>
    <title>AD-Task-3</title>
</head>
<body>
    <h1>Welcome to AD-Task-3</h1>
    <p>A simple project management system with PostgreSQL and MongoDB integration.</p>
    
    <?php if (!$isLoggedIn): ?>
        <p>Get started by logging into your account.</p>
        <a href="/pages/login/">
            <button>Login</button>
        </a>
        <a href="/pages/signup/">
            <button>Sign Up</button>
        </a>
    <?php else: ?>
        <p>Welcome back, <?= htmlspecialchars(Auth::getUserFullName()) ?>!</p>
        <a href="/pages/dashboard/">
            <button>Dashboard</button>
        </a>
        <a href="/pages/logout/">
            <button>Logout</button>
        </a>
    <?php endif; ?>
    
    <h2>System Status</h2>
    <?= $postgresStatus ?>
    <?= $mongoStatus ?>

</body>
</html>
