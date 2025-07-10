<?php
declare(strict_types=1);

require_once __DIR__ . '/../utils/auth.util.php';
require_once __DIR__ . '/../utils/envSetter.util.php';

header('Content-Type: application/json');

// Require authentication
if (!Auth::isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Authentication required']);
    exit;
}

// Only allow GET requests
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Get the action from GET data
$action = $_GET['action'] ?? '';

try {
    // Get database connection
    global $databases;
    $dsn = "pgsql:host={$databases['pgHost']};port={$databases['pgPort']};dbname={$databases['pgDB']}";
    $pdo = new PDO($dsn, $databases['pgUser'], $databases['pgPassword'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    
    $user = Auth::getUser();
    $userId = $user['id'];
    $userRole = $user['role'];
    
    switch ($action) {
        case 'stats':
            handleStats($pdo, $userId, $userRole);
            break;
            
        case 'activity':
            handleActivity($pdo, $userId, $userRole);
            break;
            
        case 'deadlines':
            handleDeadlines($pdo, $userId, $userRole);
            break;
            
        default:
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
            break;
    }
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}

function handleStats(PDO $pdo, string $userId, string $userRole): void {
    $stats = [
        'projects' => 0,
        'tasks' => 0,
        'pending' => 0,
        'completed' => 0
    ];
    
    if ($userRole === 'admin') {
        // Admin sees all statistics
        $stmt = $pdo->query("SELECT COUNT(*) FROM projects WHERE status = 'active'");
        $stats['projects'] = $stmt->fetchColumn();
        
        $stmt = $pdo->query("SELECT COUNT(*) FROM tasks");
        $stats['tasks'] = $stmt->fetchColumn();
        
        $stmt = $pdo->query("SELECT COUNT(*) FROM tasks WHERE status = 'pending'");
        $stats['pending'] = $stmt->fetchColumn();
        
        $stmt = $pdo->query("SELECT COUNT(*) FROM tasks WHERE status = 'completed'");
        $stats['completed'] = $stmt->fetchColumn();
        
    } elseif ($userRole === 'manager') {
        // Manager sees projects they created and related tasks
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM projects WHERE created_by = :user_id AND status = 'active'");
        $stmt->execute([':user_id' => $userId]);
        $stats['projects'] = $stmt->fetchColumn();
        
        $stmt = $pdo->prepare("
            SELECT COUNT(*) FROM tasks t
            JOIN projects p ON t.project_id = p.id
            WHERE p.created_by = :user_id OR t.assigned_to = :user_id
        ");
        $stmt->execute([':user_id' => $userId]);
        $stats['tasks'] = $stmt->fetchColumn();
        
        $stmt = $pdo->prepare("
            SELECT COUNT(*) FROM tasks t
            JOIN projects p ON t.project_id = p.id
            WHERE (p.created_by = :user_id OR t.assigned_to = :user_id) AND t.status = 'pending'
        ");
        $stmt->execute([':user_id' => $userId]);
        $stats['pending'] = $stmt->fetchColumn();
        
        $stmt = $pdo->prepare("
            SELECT COUNT(*) FROM tasks t
            JOIN projects p ON t.project_id = p.id
            WHERE (p.created_by = :user_id OR t.assigned_to = :user_id) AND t.status = 'completed'
        ");
        $stmt->execute([':user_id' => $userId]);
        $stats['completed'] = $stmt->fetchColumn();
        
    } else {
        // Regular users see only their assigned projects and tasks
        $stmt = $pdo->prepare("
            SELECT COUNT(DISTINCT p.id) FROM projects p
            JOIN project_users pu ON p.id = pu.project_id
            WHERE pu.user_id = :user_id AND p.status = 'active'
        ");
        $stmt->execute([':user_id' => $userId]);
        $stats['projects'] = $stmt->fetchColumn();
        
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM tasks WHERE assigned_to = :user_id");
        $stmt->execute([':user_id' => $userId]);
        $stats['tasks'] = $stmt->fetchColumn();
        
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM tasks WHERE assigned_to = :user_id AND status = 'pending'");
        $stmt->execute([':user_id' => $userId]);
        $stats['pending'] = $stmt->fetchColumn();
        
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM tasks WHERE assigned_to = :user_id AND status = 'completed'");
        $stmt->execute([':user_id' => $userId]);
        $stats['completed'] = $stmt->fetchColumn();
    }
    
    echo json_encode(['success' => true, 'stats' => $stats]);
}

function handleActivity(PDO $pdo, string $userId, string $userRole): void {
    $activity = [];
    
    try {
        if ($userRole === 'admin') {
            // Admin sees all recent activity
            $stmt = $pdo->query("
                SELECT 
                    'task' as type,
                    t.title,
                    t.status,
                    t.created_at,
                    p.name as project_name,
                    u.first_name || ' ' || u.last_name as user_name
                FROM tasks t
                JOIN projects p ON t.project_id = p.id
                LEFT JOIN users u ON t.assigned_to = u.id
                ORDER BY t.created_at DESC
                LIMIT 10
            ");
        } else {
            // Other users see only their related activity
            $stmt = $pdo->prepare("
                SELECT 
                    'task' as type,
                    t.title,
                    t.status,
                    t.created_at,
                    p.name as project_name,
                    u.first_name || ' ' || u.last_name as user_name
                FROM tasks t
                JOIN projects p ON t.project_id = p.id
                LEFT JOIN users u ON t.assigned_to = u.id
                WHERE t.assigned_to = :user_id OR t.created_by = :user_id
                ORDER BY t.created_at DESC
                LIMIT 10
            ");
            $stmt->execute([':user_id' => $userId]);
        }
        
        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($tasks as $task) {
            $icon = 'fas fa-tasks';
            $color = 'primary';
            
            if ($task['status'] === 'completed') {
                $icon = 'fas fa-check-circle';
                $color = 'success';
            } elseif ($task['status'] === 'in_progress') {
                $icon = 'fas fa-spinner';
                $color = 'warning';
            }
            
            $activity[] = [
                'icon' => $icon,
                'color' => $color,
                'title' => $task['title'],
                'description' => $task['project_name'] . ' - ' . ucfirst($task['status']),
                'time' => date('M j, Y g:i A', strtotime($task['created_at']))
            ];
        }
        
    } catch (Exception $e) {
        error_log('Activity query error: ' . $e->getMessage());
    }
    
    echo json_encode(['success' => true, 'activity' => $activity]);
}

function handleDeadlines(PDO $pdo, string $userId, string $userRole): void {
    $deadlines = [];
    
    try {
        if ($userRole === 'admin') {
            // Admin sees all upcoming deadlines
            $stmt = $pdo->query("
                SELECT 
                    t.title,
                    t.due_date,
                    p.name as project_name
                FROM tasks t
                JOIN projects p ON t.project_id = p.id
                WHERE t.due_date >= CURRENT_DATE
                  AND t.status != 'completed'
                ORDER BY t.due_date ASC
                LIMIT 10
            ");
        } else {
            // Other users see only their assigned deadlines
            $stmt = $pdo->prepare("
                SELECT 
                    t.title,
                    t.due_date,
                    p.name as project_name
                FROM tasks t
                JOIN projects p ON t.project_id = p.id
                WHERE t.assigned_to = :user_id
                  AND t.due_date >= CURRENT_DATE
                  AND t.status != 'completed'
                ORDER BY t.due_date ASC
                LIMIT 10
            ");
            $stmt->execute([':user_id' => $userId]);
        }
        
        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($tasks as $task) {
            $dueDate = new DateTime($task['due_date']);
            $now = new DateTime();
            $interval = $now->diff($dueDate);
            
            $urgency = 'secondary';
            if ($interval->days <= 1) {
                $urgency = 'danger';
            } elseif ($interval->days <= 3) {
                $urgency = 'warning';
            } elseif ($interval->days <= 7) {
                $urgency = 'info';
            }
            
            $deadlines[] = [
                'title' => $task['title'],
                'project' => $task['project_name'],
                'due_date' => $dueDate->format('M j'),
                'urgency' => $urgency
            ];
        }
        
    } catch (Exception $e) {
        error_log('Deadlines query error: ' . $e->getMessage());
    }
    
    echo json_encode(['success' => true, 'deadlines' => $deadlines]);
}
