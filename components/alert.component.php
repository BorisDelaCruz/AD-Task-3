<?php
/**
 * Alert Component
 * 
 * Usage:
 * include 'components/alert.component.php';
 * renderAlert('success', 'Operation completed successfully!');
 * renderAlert('danger', 'An error occurred!', true); // dismissible
 */

function renderAlert(string $type, string $message, bool $dismissible = false, array $additionalClasses = []): void {
    $alertClasses = ['alert', "alert-{$type}"];
    
    if ($dismissible) {
        $alertClasses[] = 'alert-dismissible';
    }
    
    if (!empty($additionalClasses)) {
        $alertClasses = array_merge($alertClasses, $additionalClasses);
    }
    
    $classString = implode(' ', $alertClasses);
    
    echo "<div class=\"{$classString}\" role=\"alert\">";
    echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
    
    if ($dismissible) {
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
    }
    
    echo '</div>';
}

// Function to render alert from session data (flash messages)
function renderFlashAlert(): void {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    if (isset($_SESSION['flash_message'])) {
        $flash = $_SESSION['flash_message'];
        renderAlert($flash['type'], $flash['message'], true);
        unset($_SESSION['flash_message']);
    }
}

// Function to set flash message
function setFlashMessage(string $type, string $message): void {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    $_SESSION['flash_message'] = [
        'type' => $type,
        'message' => $message
    ];
}
?>
