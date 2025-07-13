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
        <link rel="stylesheet" href="/layouts/style.css">
        
        <?php foreach ($additionalCss as $css): ?>
            <link rel="stylesheet" href="<?= htmlspecialchars($css) ?>">
        <?php endforeach; ?>
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
        
        <?php foreach ($additionalJs as $js): ?>
            <script src="<?= htmlspecialchars($js) ?>"></script>
        <?php endforeach; ?>
    </body>
    </html>
    <?php
}
?>
