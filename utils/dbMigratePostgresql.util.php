<?php
declare(strict_types=1);

// 1) Composer autoload
require_once 'vendor/autoload.php';

// 2) Composer bootstrap
require_once 'bootstrap.php';

// 3) envSetter
require_once __DIR__ . '/envSetter.util.php';

echo "🔄 Starting PostgreSQL Database Migration...\n";

try {
    // Get database configuration
    $host = $databases['pgHost'];
    $port = $databases['pgPort'];
    $username = $databases['pgUser'];
    $password = $databases['pgPassword'];
    $dbname = $databases['pgDB'];

    // ——— Connect to PostgreSQL ———
    $dsn = "pgsql:host={$host};port={$port};dbname={$dbname}";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    echo "✅ Connected to PostgreSQL database successfully!\n";

    // Drop old tables
    echo "🗑️ Dropping old tables...\n";
    foreach ([
        'tasks',
        'project_users',
        'projects',
        'users',
    ] as $table) {
        // Use IF EXISTS so it won't error if the table is already gone
        $pdo->exec("DROP TABLE IF EXISTS {$table} CASCADE;");
        echo "   ✅ Dropped table: {$table}\n";
    }

    // Apply schema files in correct order (recreate tables)
    echo "📄 Applying schema from database/users.model.sql...\n";
    $sql = file_get_contents('database/users.model.sql');
    if ($sql === false) {
        throw new RuntimeException("Could not read database/users.model.sql");
    } else {
        echo "   ✅ Creation Success from the database/users.model.sql\n";
    }
    $pdo->exec($sql);

    echo "📄 Applying schema from database/projects.model.sql...\n";
    $sql = file_get_contents('database/projects.model.sql');
    if ($sql === false) {
        throw new RuntimeException("Could not read database/projects.model.sql");
    } else {
        echo "   ✅ Creation Success from the database/projects.model.sql\n";
    }
    $pdo->exec($sql);

    echo "📄 Applying schema from database/project_users.model.sql...\n";
    $sql = file_get_contents('database/project_users.model.sql');
    if ($sql === false) {
        throw new RuntimeException("Could not read database/project_users.model.sql");
    } else {
        echo "   ✅ Creation Success from the database/project_users.model.sql\n";
    }
    $pdo->exec($sql);

    echo "📄 Applying schema from database/tasks.model.sql...\n";
    $sql = file_get_contents('database/tasks.model.sql');
    if ($sql === false) {
        throw new RuntimeException("Could not read database/tasks.model.sql");
    } else {
        echo "   ✅ Creation Success from the database/tasks.model.sql\n";
    }
    $pdo->exec($sql);

    // Verify migration
    echo "🔍 Verifying migrated tables...\n";
    $result = $pdo->query("
        SELECT table_name 
        FROM information_schema.tables 
        WHERE table_schema = 'public' 
        ORDER BY table_name
    ");
    
    $tables = $result->fetchAll(PDO::FETCH_COLUMN);
    foreach ($tables as $table) {
        echo "   ✅ Table migrated: {$table}\n";
    }

    echo "\n🎉 PostgreSQL migration complete! ✅\n";
    echo "📊 Database schema has been updated and is ready for use!\n";

} catch (PDOException $e) {
    echo "❌ Database Error: " . $e->getMessage() . "\n";
    exit(1);
} catch (RuntimeException $e) {
    echo "❌ Runtime Error: " . $e->getMessage() . "\n";
    exit(1);
} catch (Exception $e) {
    echo "❌ General Error: " . $e->getMessage() . "\n";
    exit(1);
}
