<?php
require_once __DIR__ . '/../../utils/auth.util.php';

// Start session for flash messages
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
$isLoggedIn = Auth::isLoggedIn();
$user = $isLoggedIn ? Auth::getUser() : null;

// Get flash messages
$flashMessage = $_SESSION['flash_message'] ?? '';
$flashType = $_SESSION['flash_type'] ?? '';

// Clear flash messages
unset($_SESSION['flash_message'], $_SESSION['flash_type']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Logout - AD-Task-3</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <!-- Flash Messages Display -->
    <?php if (!empty($flashMessage)): ?>
        <div class="<?= $flashType === 'success' ? 'success' : 'error' ?>">
            <?= htmlspecialchars($flashMessage) ?>
        </div>
    <?php endif; ?>
    
    <!-- Message container for AJAX responses -->
    <div id="message-container"></div>
    
    <?php if ($isLoggedIn): ?>
        <div class="logout-container">
            <h1>Logout Confirmation</h1>
            
            <div class="user-info">
                <strong>Hello, <?= htmlspecialchars(Auth::getUserFullName()) ?>!</strong><br>
                <small>Role: <?= htmlspecialchars($user['role']) ?></small><br>
                <small>Session duration: <?= gmdate("H:i:s", Auth::getSessionDuration()) ?></small>
            </div>
            
            <div class="confirmation-text">
                <p>Are you sure you want to log out of your account?</p>
                <p><small>You will need to enter your credentials again to access the system.</small></p>
            </div>
            
            <div class="button-group">
                <form method="POST" action="/handlers/auth.handler.php" id="logoutForm" style="display: inline;">
                    <input type="hidden" name="action" value="logout">
                    <button type="submit" class="btn-danger" id="logoutBtn">
                        <span class="btn-text">Yes, Logout</span>
                        <span class="loading" id="loadingText">Logging out...</span>
                    </button>
                </form>
                
                <a href="/pages/dashboard/" class="btn btn-secondary">Cancel</a>
            </div>
        </div>
    <?php else: ?>
        <div class="logout-container">
            <h1>Already Logged Out</h1>
            
            <div class="already-logged-out">
                <p>You are currently not logged into the system.</p>
                <p>Please login to access your account.</p>
            </div>
            
            <div class="button-group">
                <a href="/pages/login/" class="btn btn-primary">Login Now</a>
                <a href="/" class="btn btn-secondary">Go Home</a>
            </div>
        </div>
    <?php endif; ?>
    
    <script src="assets/script.js"></script>
    <?php if ($isLoggedIn): ?>
    <script>
        // Initialize session warning with PHP data
        initSessionWarning(<?= Auth::getSessionDuration() ?>);
    </script>
    <?php endif; ?>
</body>
</html>
