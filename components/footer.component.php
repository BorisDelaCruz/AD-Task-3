<?php
$config = require_once __DIR__ . '/../staticDatas/appConfig.staticData.php';
?>

<footer class="bg-dark text-light py-4 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h5><?= htmlspecialchars($config['app']['name']) ?></h5>
                <p class="mb-0"><?= htmlspecialchars($config['app']['description']) ?></p>
                <small class="text-muted">Version <?= htmlspecialchars($config['app']['version']) ?></small>
            </div>
            <div class="col-md-6 text-md-end">
                <p class="mb-0">
                    <i class="fas fa-code"></i>
                    Developed by <?= htmlspecialchars($config['app']['author']) ?>
                </p>
                <small class="text-muted">
                    &copy; <?= date('Y') ?> All rights reserved.
                </small>
            </div>
        </div>
        <hr class="my-3">
        <div class="row">
            <div class="col-12 text-center">
                <div class="d-flex justify-content-center align-items-center">
                    <span class="me-3">
                        <i class="fas fa-database text-success"></i>
                        PostgreSQL
                    </span>
                    <span class="me-3">
                        <i class="fab fa-php text-primary"></i>
                        PHP <?= PHP_VERSION ?>
                    </span>
                    <span>
                        <i class="fab fa-bootstrap text-info"></i>
                        Bootstrap 5
                    </span>
                </div>
            </div>
        </div>
    </div>
</footer>
