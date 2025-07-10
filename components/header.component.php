<?php
require_once __DIR__ . '/../utils/auth.util.php';
$user = Auth::getUser();
$isLoggedIn = Auth::isLoggedIn();
$navigation = require_once __DIR__ . '/../staticDatas/navigation.staticData.php';
$config = require_once __DIR__ . '/../staticDatas/appConfig.staticData.php';

// Ensure config is properly loaded
if (!is_array($config)) {
    $config = [
        'app' => [
            'name' => 'AD-Task-3',
            'description' => 'Project Management System'
        ],
        'roles' => [
            'admin' => ['title' => 'Admin', 'color' => 'danger'],
            'manager' => ['title' => 'Manager', 'color' => 'warning'],
            'developer' => ['title' => 'Developer', 'color' => 'success'],
            'designer' => ['title' => 'Designer', 'color' => 'info'],
            'user' => ['title' => 'User', 'color' => 'secondary']
        ]
    ];
}

// Ensure navigation is properly loaded
if (!is_array($navigation)) {
    $navigation = [
        'public' => [],
        'authenticated' => [],
        'admin_only' => []
    ];
}
?>

<nav style="background-color: #333; color: white; padding: 15px 0;">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 20px; display: flex; justify-content: space-between; align-items: center;">
        <a href="/" style="color: white; text-decoration: none; font-size: 24px; font-weight: bold;">
            <?= htmlspecialchars($config['app']['name']) ?>
        </a>
        
        <div style="display: flex; align-items: center; gap: 20px;">
            <?php if (!$isLoggedIn): ?>
                <a href="/pages/login/" style="color: white; text-decoration: none; padding: 8px 16px; border: 1px solid white; border-radius: 4px;">Login</a>
                <a href="/pages/signup/" style="color: white; text-decoration: none; padding: 8px 16px; border: 1px solid white; border-radius: 4px;">Sign Up</a>
            <?php else: ?>
                <span style="color: #ccc;">Welcome, <?= htmlspecialchars($user['first_name']) ?>!</span>
                <a href="/pages/dashboard/" style="color: white; text-decoration: none; padding: 8px 16px; border: 1px solid white; border-radius: 4px;">Dashboard</a>
                <a href="/pages/logout/" style="color: white; text-decoration: none; padding: 8px 16px; border: 1px solid white; border-radius: 4px;">Logout</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
