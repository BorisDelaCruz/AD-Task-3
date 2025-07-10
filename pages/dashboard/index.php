<?php
require_once __DIR__ . '/../../utils/auth.util.php';

// Require authentication
Auth::requireAuth();

$user = Auth::getUser();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - AD-Task-3</title>
</head>
<body>
    <h1>Dashboard</h1>
    <p>Welcome, <?= htmlspecialchars(Auth::getUserFullName()) ?>!</p>
    <p>Your role: <?= htmlspecialchars($user['role']) ?></p>
    
    <h2>Quick Actions</h2>
    <p><a href="/pages/logout/"><button>Logout</button></a></p>
    <p><a href="/"><button>Back to Home</button></a></p>
    
</body>
</html>
