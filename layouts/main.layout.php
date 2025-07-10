<?php
$config = require_once __DIR__ . '/../staticDatas/appConfig.staticData.php';

function renderLayout(string $title, string $content, array $additionalCss = [], array $additionalJs = []): void {
    global $config;
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= htmlspecialchars($title) ?> - <?= htmlspecialchars($config['app']['name']) ?></title>
        
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                min-height: 100vh;
                display: flex;
                flex-direction: column;
            }
            
            main {
                flex: 1;
                padding: 20px;
            }
            
            .container {
                max-width: 1200px;
                margin: 0 auto;
                padding: 0 20px;
            }
            
            .alert {
                padding: 15px;
                margin-bottom: 20px;
                border: 1px solid transparent;
                border-radius: 4px;
            }
            
            .alert-success {
                color: #3c763d;
                background-color: #dff0d8;
                border-color: #d6e9c6;
            }
            
            .alert-danger {
                color: #a94442;
                background-color: #f2dede;
                border-color: #ebccd1;
            }
            
            .alert-info {
                color: #31708f;
                background-color: #d9edf7;
                border-color: #bce8f1;
            }
            
            .alert-warning {
                color: #8a6d3b;
                background-color: #fcf8e3;
                border-color: #faebcc;
            }
        </style>
    </head>
    <body>
        <!-- Header -->
        <?php include __DIR__ . '/../components/header.component.php'; ?>
        
        <!-- Main Content -->
        <main>
            <div class="container">
                <?php
                // Include alert component for flash messages
                include __DIR__ . '/../components/alert.component.php';
                renderFlashAlert();
                ?>
                
                <?= $content ?>
            </div>
        </main>
        
        <!-- Footer -->
        <?php include __DIR__ . '/../components/footer.component.php'; ?>
    </body>
    </html>
            $(document).ajaxError(function(event, jqXHR, ajaxSettings, thrownError) {
                console.error('AJAX Error:', thrownError);
    </body>
    </html>
    <?php
}
?>
