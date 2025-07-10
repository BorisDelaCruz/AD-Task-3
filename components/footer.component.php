<?php
$config = require_once __DIR__ . '/../staticDatas/appConfig.staticData.php';
?>

<footer style="background-color: #333; color: white; padding: 20px; margin-top: 20px;">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 250px; margin-bottom: 20px;">
                <h5 style="margin: 0 0 10px 0;"><?= htmlspecialchars($config['app']['name']) ?></h5>
                <p style="margin: 0 0 5px 0;"><?= htmlspecialchars($config['app']['description']) ?></p>
                <small style="color: #ccc;">Version <?= htmlspecialchars($config['app']['version']) ?></small>
            </div>
            <div style="flex: 1; min-width: 250px; text-align: right;">
                <p style="margin: 0 0 5px 0;">
                    Developed by <?= htmlspecialchars($config['app']['author']) ?>
                </p>
                <small style="color: #ccc;">
                    &copy; <?= date('Y') ?> All rights reserved.
                </small>
            </div>
        </div>
        <hr style="margin: 20px 0; border-color: #555;">
        <div style="text-align: center;">
            <div style="display: flex; justify-content: center; align-items: center; gap: 20px; flex-wrap: wrap;">
                <span>PostgreSQL</span>
                <span>PHP <?= PHP_VERSION ?></span>
                <span>Docker</span>
            </div>
        </div>
    </div>
</footer>
