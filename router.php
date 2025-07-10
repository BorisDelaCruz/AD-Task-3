<?php
require __DIR__ . '/bootstrap.php';

if (php_sapi_name() === 'cli-server') {
    $urlPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $file = BASE_PATH . $urlPath;
    
    // Serve static files directly
    if (is_file($file)) {
        return false;
    }
}

// Get the requested URI
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = rtrim($uri, '/');

// Remove query parameters
$uri = strtok($uri, '?');

// Route handling
switch ($uri) {
    case '':
    case '/':
        require BASE_PATH . '/index.php';
        break;
        
    case '/pages/login':
        require BASE_PATH . '/pages/login/index.php';
        break;
        
    case '/pages/signup':
        require BASE_PATH . '/pages/signup/index.php';
        break;
        
    case '/pages/logout':
        require BASE_PATH . '/pages/logout/index.php';
        break;
        
    case '/pages/dashboard':
        require BASE_PATH . '/pages/dashboard/index.php';
        break;
        
    case '/pages/error':
        require BASE_PATH . '/pages/error/index.php';
        break;
        
    // Handler routes
    case '/handlers/auth.handler.php':
        require BASE_PATH . '/handlers/auth.handler.php';
        break;
        
    case '/handlers/dashboard.handler.php':
        require BASE_PATH . '/handlers/dashboard.handler.php';
        break;
        
    case '/handlers/mongodbChecker.handler.php':
        require BASE_PATH . '/handlers/mongodbChecker.handler.php';
        break;
        
    case '/handlers/postgreChecker.handler.php':
        require BASE_PATH . '/handlers/postgreChecker.handler.php';
        break;
        
    // Static assets
    default:
        // Handle other routes
        if (preg_match('/^\/pages\/([^\/]+)(?:\/([^\/]+))?(?:\/(.*))?$/', $uri, $matches)) {
            $page = $matches[1];
            $subpage = $matches[2] ?? '';
            $extra = $matches[3] ?? '';
            
            // Construct the file path
            if ($subpage) {
                $file = BASE_PATH . "/pages/$page/$subpage/index.php";
            } else {
                $file = BASE_PATH . "/pages/$page/index.php";
            }
            
            // Check if file exists
            if (is_file($file)) {
                require $file;
            } else {
                // 404 - Page not found
                header("HTTP/1.0 404 Not Found");
                $_GET['code'] = '404';
                require BASE_PATH . '/pages/error/index.php';
            }
        } elseif (preg_match('/^\/handlers\/([^\/]+)$/', $uri, $matches)) {
            $handler = $matches[1];
            $file = BASE_PATH . "/handlers/$handler";
            
            if (is_file($file)) {
                require $file;
            } else {
                // 404 - Handler not found
                header("HTTP/1.0 404 Not Found");
                $_GET['code'] = '404';
                require BASE_PATH . '/pages/error/index.php';
            }
        } else {
            // Check if it's a static file in assets
            $file = BASE_PATH . $uri;
            if (is_file($file)) {
                return false; // Let the server handle static files
            }
            
            // 404 - Not found
            header("HTTP/1.0 404 Not Found");
            $_GET['code'] = '404';
            require BASE_PATH . '/pages/error/index.php';
        }
        break;
}
