<?php
declare(strict_types=1);

// Define paths
define('UTILS_PATH', __DIR__);
define('DATABASE_PATH', dirname(__DIR__) . '/database');
define('DUMMIES_PATH', dirname(__DIR__) . '/staticDatas/dummies');

// Database configuration from compose.yaml
$databases = [
    'pgHost' => 'username-postgresql',  // Use service name for internal container communication
    'pgPort' => '5432',                 // Use internal port
    'pgUser' => 'user',
    'pgPassword' => 'password',
    'pgDB' => 'mydatabase',
    
    'mongoHost' => 'username-mongodb',   // Use service name for internal container communication
    'mongoPort' => '27017',             // Use internal port
    'mongoUser' => 'root',
    'mongoPassword' => 'rootPassword',
    'mongoDB' => 'mydatabase'
];

// Make databases array globally available
$GLOBALS['databases'] = $databases;
