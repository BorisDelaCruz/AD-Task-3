<?php
require_once __DIR__ . '/../../utils/auth.util.php';

// Get error code from URL parameter
$errorCode = $_GET['code'] ?? '404';
$errorCode = htmlspecialchars($errorCode);

// Define error messages
$errors = [
    '400' => [
        'title' => 'Bad Request',
        'message' => 'The request could not be understood by the server.',
        'icon' => 'fas fa-exclamation-triangle',
        'color' => 'warning'
    ],
    '401' => [
        'title' => 'Unauthorized',
        'message' => 'You need to log in to access this resource.',
        'icon' => 'fas fa-lock',
        'color' => 'danger'
    ],
    '403' => [
        'title' => 'Forbidden',
        'message' => 'You don\'t have permission to access this resource.',
        'icon' => 'fas fa-ban',
        'color' => 'danger'
    ],
    '404' => [
        'title' => 'Page Not Found',
        'message' => 'The page you are looking for could not be found.',
        'icon' => 'fas fa-search',
        'color' => 'info'
    ],
    '500' => [
        'title' => 'Internal Server Error',
        'message' => 'Something went wrong on our end. Please try again later.',
        'icon' => 'fas fa-server',
        'color' => 'danger'
    ],
    '503' => [
        'title' => 'Service Unavailable',
        'message' => 'The service is temporarily unavailable. Please try again later.',
        'icon' => 'fas fa-tools',
        'color' => 'warning'
    ]
];

// Get error details or default to 404
$error = $errors[$errorCode] ?? $errors['404'];

// Set appropriate HTTP status code
http_response_code((int)$errorCode);

// Start output buffering for layout
ob_start();
?>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="text-center">
            <!-- Error Icon -->
            <div class="mb-4">
                <i class="<?= $error['icon'] ?> text-<?= $error['color'] ?>" style="font-size: 6rem;"></i>
            </div>
            
            <!-- Error Code -->
            <h1 class="display-1 fw-bold text-<?= $error['color'] ?>"><?= $errorCode ?></h1>
            
            <!-- Error Title -->
            <h2 class="mb-3"><?= htmlspecialchars($error['title']) ?></h2>
            
            <!-- Error Message -->
            <p class="lead mb-4 text-muted">
                <?= htmlspecialchars($error['message']) ?>
            </p>
            
            <!-- Action Buttons -->
            <div class="d-flex justify-content-center gap-2 flex-wrap">
                <button class="btn btn-primary" onclick="window.history.back()">
                    <i class="fas fa-arrow-left"></i>
                    Go Back
                </button>
                
                <a href="/" class="btn btn-outline-primary">
                    <i class="fas fa-home"></i>
                    Go Home
                </a>
                
                <?php if (!Auth::isLoggedIn() && in_array($errorCode, ['401', '403'])): ?>
                    <a href="/pages/login/" class="btn btn-success">
                        <i class="fas fa-sign-in-alt"></i>
                        Login
                    </a>
                <?php endif; ?>
                
                <?php if (Auth::isLoggedIn()): ?>
                    <a href="/pages/dashboard/" class="btn btn-info">
                        <i class="fas fa-tachometer-alt"></i>
                        Dashboard
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Additional Help Section -->
<div class="row justify-content-center mt-5">
    <div class="col-md-8 col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-question-circle"></i>
                    Need Help?
                </h5>
            </div>
            <div class="card-body">
                <p class="mb-3">If you're experiencing issues, here are some suggestions:</p>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="fas fa-check text-success"></i>
                        Check the URL for any typos
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success"></i>
                        Make sure you're logged in if accessing protected content
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success"></i>
                        Try refreshing the page
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success"></i>
                        Clear your browser cache and cookies
                    </li>
                </ul>
                
                <?php if ($errorCode === '500'): ?>
                    <div class="alert alert-warning mt-3">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Technical Issue:</strong> 
                        If this problem persists, please contact the system administrator.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
// Add some interactive behavior
document.addEventListener('DOMContentLoaded', function() {
    // Auto-redirect for 401 errors after 5 seconds if not logged in
    <?php if ($errorCode === '401' && !Auth::isLoggedIn()): ?>
        setTimeout(function() {
            if (confirm('Would you like to be redirected to the login page?')) {
                window.location.href = '/pages/login/';
            }
        }, 5000);
    <?php endif; ?>
    
    // Add animation to the error icon
    const errorIcon = document.querySelector('.text-<?= $error['color'] ?>');
    if (errorIcon) {
        errorIcon.style.animation = 'pulse 2s infinite';
    }
});

// Add CSS animation
const style = document.createElement('style');
style.textContent = `
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }
`;
document.head.appendChild(style);
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/main.layout.php';
renderLayout('Error ' . $errorCode, $content);
?>
