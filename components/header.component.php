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

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="/">
            <i class="fas fa-code"></i>
            <?= htmlspecialchars($config['app']['name']) ?>
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <?php if (!$isLoggedIn): ?>
                    <?php if (isset($navigation['public']) && is_array($navigation['public'])): ?>
                        <?php foreach ($navigation['public'] as $key => $item): ?>
                            <?php if (isset($item['url']) && isset($item['title'])): ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?= htmlspecialchars($item['url']) ?>">
                                        <i class="<?= htmlspecialchars($item['icon'] ?? 'fas fa-link') ?>"></i>
                                        <?= htmlspecialchars($item['title']) ?>
                                    </a>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php else: ?>
                    <?php if (isset($navigation['authenticated']) && is_array($navigation['authenticated'])): ?>
                        <?php foreach ($navigation['authenticated'] as $key => $item): ?>
                            <?php if (isset($item['roles']) && Auth::hasAnyRole($item['roles'])): ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?= htmlspecialchars($item['url']) ?>">
                                        <i class="<?= htmlspecialchars($item['icon'] ?? 'fas fa-link') ?>"></i>
                                        <?= htmlspecialchars($item['title']) ?>
                                    </a>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    
                    <?php if (Auth::hasRole('admin')): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-shield-alt"></i>
                                Admin
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="adminDropdown">
                                <?php if (isset($navigation['admin_only']) && is_array($navigation['admin_only'])): ?>
                                    <?php foreach ($navigation['admin_only'] as $key => $item): ?>
                                        <?php if (isset($item['url']) && isset($item['title'])): ?>
                                            <li><a class="dropdown-item" href="<?= htmlspecialchars($item['url']) ?>">
                                                <i class="<?= htmlspecialchars($item['icon'] ?? 'fas fa-link') ?>"></i>
                                                <?= htmlspecialchars($item['title']) ?>
                                            </a></li>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </ul>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>
            
            <ul class="navbar-nav">
                <?php if ($isLoggedIn): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user"></i>
                            <?= htmlspecialchars(Auth::getUserFullName()) ?>
                            <?php if (is_array($user) && isset($user['role']) && isset($config['roles'][$user['role']])): ?>
                                <span class="badge bg-<?= $config['roles'][$user['role']]['color'] ?> ms-1">
                                    <?= htmlspecialchars($config['roles'][$user['role']]['title']) ?>
                                </span>
                            <?php endif; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="/pages/profile/">
                                <i class="fas fa-user-edit"></i> Profile
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#" onclick="logout()">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a></li>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<?php if ($isLoggedIn): ?>
<script>
function logout() {
    if (confirm('Are you sure you want to logout?')) {
        fetch('/handlers/auth.handler.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'action=logout'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = data.redirect || '/pages/login/';
            } else {
                alert('Logout failed: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred during logout');
        });
    }
}
</script>
<?php endif; ?>
