<?php
declare(strict_types=1);

// 1) Composer autoload
require_once 'vendor/autoload.php';

// 2) Composer bootstrap
require_once 'bootstrap.php';

// 3) envSetter
require_once __DIR__ . '/envSetter.util.php';

// Load dummy data
$users = require_once DUMMIES_PATH . '/users.staticData.php';
$projects = require_once DUMMIES_PATH . '/projects.staticData.php';
$tasks = require_once DUMMIES_PATH . '/tasks.staticData.php';
$project_users = require_once DUMMIES_PATH . '/project_users.staticData.php';

echo "🌱 Starting PostgreSQL Database Seeding...\n";

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

    // Check if tables exist
    echo "🔍 Checking database tables...\n";
    $result = $pdo->query("
        SELECT table_name 
        FROM information_schema.tables 
        WHERE table_schema = 'public' 
        ORDER BY table_name
    ");
    
    $tables = $result->fetchAll(PDO::FETCH_COLUMN);
    if (empty($tables)) {
        echo "❌ No tables found! Please run the database reset first.\n";
        exit(1);
    }
    
    foreach ($tables as $table) {
        echo "   ✅ Table found: {$table}\n";
    }

    // Clear existing data
    echo "🗑️ Clearing existing data...\n";
    $pdo->exec("TRUNCATE TABLE tasks, project_users, projects, users RESTART IDENTITY CASCADE;");
    echo "   ✅ All tables cleared successfully!\n";

    // Seed users
    echo "🌱 Seeding users...\n";
    $stmt = $pdo->prepare("
        INSERT INTO users (username, role, first_name, middle_name, last_name, password)
        VALUES (:username, :role, :fn, :mn, :ln, :pw)
    ");
    
    foreach ($users as $u) {
        $stmt->execute([
            ':username' => $u['username'],
            ':role' => $u['role'],
            ':fn' => $u['first_name'],
            ':mn' => $u['middle_name'],
            ':ln' => $u['last_name'],
            ':pw' => password_hash($u['password'], PASSWORD_DEFAULT),
        ]);
    }
    echo "   ✅ " . count($users) . " users seeded successfully!\n";

    // Seed projects
    echo "🌱 Seeding projects...\n";
    $stmt = $pdo->prepare("
        INSERT INTO projects (name, description, status, start_date, end_date, created_by)
        VALUES (:name, :description, :status, :start_date, :end_date, 
                (SELECT id FROM users WHERE username = :created_by_username))
    ");
    
    foreach ($projects as $p) {
        $stmt->execute([
            ':name' => $p['name'],
            ':description' => $p['description'],
            ':status' => $p['status'],
            ':start_date' => $p['start_date'],
            ':end_date' => $p['end_date'],
            ':created_by_username' => $p['created_by_username'],
        ]);
    }
    echo "   ✅ " . count($projects) . " projects seeded successfully!\n";

    // Seed project_users
    echo "🌱 Seeding project-user assignments...\n";
    $stmt = $pdo->prepare("
        INSERT INTO project_users (project_id, user_id, role)
        VALUES (
            (SELECT id FROM projects WHERE name = :project_name),
            (SELECT id FROM users WHERE username = :user_username),
            :role
        )
    ");
    
    foreach ($project_users as $pu) {
        $stmt->execute([
            ':project_name' => $pu['project_name'],
            ':user_username' => $pu['user_username'],
            ':role' => $pu['role'],
        ]);
    }
    echo "   ✅ " . count($project_users) . " project-user assignments seeded successfully!\n";

    // Seed tasks
    echo "🌱 Seeding tasks...\n";
    $stmt = $pdo->prepare("
        INSERT INTO tasks (title, description, status, priority, project_id, assigned_to, created_by, due_date)
        VALUES (
            :title, :description, :status, :priority,
            (SELECT id FROM projects WHERE name = :project_name),
            (SELECT id FROM users WHERE username = :assigned_to_username),
            (SELECT id FROM users WHERE username = :created_by_username),
            :due_date
        )
    ");
    
    foreach ($tasks as $t) {
        $stmt->execute([
            ':title' => $t['title'],
            ':description' => $t['description'],
            ':status' => $t['status'],
            ':priority' => $t['priority'],
            ':project_name' => $t['project_name'],
            ':assigned_to_username' => $t['assigned_to_username'],
            ':created_by_username' => $t['created_by_username'],
            ':due_date' => $t['due_date'],
        ]);
    }
    echo "   ✅ " . count($tasks) . " tasks seeded successfully!\n";

    // Verify seeded data
    echo "🔍 Verifying seeded data...\n";
    $counts = [];
    foreach (['users', 'projects', 'project_users', 'tasks'] as $table) {
        $result = $pdo->query("SELECT COUNT(*) FROM {$table}");
        $count = $result->fetchColumn();
        $counts[$table] = $count;
        echo "   ✅ {$table}: {$count} records\n";
    }

    echo "\n🎉 PostgreSQL seeding complete! ✅\n";
    echo "📊 Database is ready for use with seeded data:\n";
    echo "   👥 Users: {$counts['users']}\n";
    echo "   📋 Projects: {$counts['projects']}\n";
    echo "   🔗 Project-User Assignments: {$counts['project_users']}\n";
    echo "   ✅ Tasks: {$counts['tasks']}\n";

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
