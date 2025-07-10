<?php
declare(strict_types=1);

// 1) Composer autoload
require_once 'vendor/autoload.php';

// 2) Composer bootstrap
require_once 'bootstrap.php';

// 3) envSetter
require_once __DIR__ . '/envSetter.util.php';

echo "🔄 Starting PostgreSQL Database Reset...\n";

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

    // Drop existing tables to ensure clean reset
    echo "🗑️ Dropping existing tables...\n";
    $dropTables = [
        'tasks',
        'project_users', 
        'projects',
        'users'
    ];
    
    foreach ($dropTables as $table) {
        try {
            $pdo->exec("DROP TABLE IF EXISTS {$table} CASCADE;");
            echo "   ✅ Dropped table: {$table}\n";
        } catch (PDOException $e) {
            echo "   ⚠️ Could not drop table {$table}: " . $e->getMessage() . "\n";
        }
    }

    // Apply schema files in correct order
    $schemaFiles = [
        'users.model.sql',
        'projects.model.sql',
        'project_users.model.sql',
        'tasks.model.sql'
    ];

    foreach ($schemaFiles as $file) {
        echo "📄 Applying schema from database/{$file}...\n";
        $sql = file_get_contents("database/{$file}");
        
        if ($sql === false) {
            throw new RuntimeException("Could not read database/{$file}");
        } else {
            echo "   ✅ Read successful from database/{$file}\n";
        }
        
        // Execute the SQL
        $pdo->exec($sql);
        echo "   ✅ Table created successfully from {$file}\n";
    }

    // Insert sample data for testing
    echo "📝 Inserting sample data...\n";
    
    // Sample users
    $pdo->exec("
        INSERT INTO users (first_name, middle_name, last_name, password, username, role) VALUES
        ('John', 'Michael', 'Doe', 'password123', 'john.doe', 'admin'),
        ('Jane', NULL, 'Smith', 'password456', 'jane.smith', 'user'),
        ('Bob', 'William', 'Johnson', 'password789', 'bob.johnson', 'manager')
    ");
    
    // Sample projects
    $pdo->exec("
        INSERT INTO projects (name, description, status, start_date, end_date, created_by) VALUES
        ('Project Alpha', 'First project for testing', 'active', '2025-01-01', '2025-12-31', 
         (SELECT id FROM users WHERE username = 'john.doe')),
        ('Project Beta', 'Second project for development', 'active', '2025-02-01', '2025-11-30', 
         (SELECT id FROM users WHERE username = 'jane.smith'))
    ");
    
    echo "   ✅ Sample data inserted successfully!\n";

    // Verify tables were created
    echo "🔍 Verifying tables...\n";
    $result = $pdo->query("
        SELECT table_name 
        FROM information_schema.tables 
        WHERE table_schema = 'public' 
        ORDER BY table_name
    ");
    
    $tables = $result->fetchAll(PDO::FETCH_COLUMN);
    foreach ($tables as $table) {
        echo "   ✅ Table exists: {$table}\n";
    }

    echo "\n🎉 PostgreSQL reset complete! ✅\n";
    echo "📊 Database is ready for use with sample data!\n";

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
