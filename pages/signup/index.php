<?php
require_once __DIR__ . '/../../utils/auth.util.php';
require_once __DIR__ . '/../../utils/envSetter.util.php';

// Redirect if already logged in
if (Auth::isLoggedIn()) {
    header('Location: /pages/dashboard/');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $middle_name = trim($_POST['middle_name'] ?? '');
    
    if (!empty($username) && !empty($password) && !empty($first_name) && !empty($last_name)) {
        try {
            // Get database connection
            global $databases;
            $dsn = "pgsql:host={$databases['pgHost']};port={$databases['pgPort']};dbname={$databases['pgDB']}";
            $pdo = new PDO($dsn, $databases['pgUser'], $databases['pgPassword'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
            
            // Check if username already exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username");
            $stmt->execute([':username' => $username]);
            
            if ($stmt->fetch()) {
                $error = 'Username already exists.';
            } else {
                // Create new user
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("
                    INSERT INTO users (username, password, first_name, middle_name, last_name, role) 
                    VALUES (:username, :password, :first_name, :middle_name, :last_name, 'user')
                ");
                
                $stmt->execute([
                    ':username' => $username,
                    ':password' => $hashedPassword,
                    ':first_name' => $first_name,
                    ':middle_name' => $middle_name ?: null,
                    ':last_name' => $last_name
                ]);
                
                $success = 'Account created successfully! You can now login.';
            }
            
        } catch (PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    } else {
        $error = 'Username, password, first name, and last name are required.';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sign Up - AD-Task-3</title>
</head>
<body>
    <h1>Sign Up</h1>
    
    <?php if (isset($error)): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    
    <?php if (isset($success)): ?>
        <p style="color: green;"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>
    
    <form method="POST">
        <p>
            <label>Username:</label><br>
            <input type="text" name="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>
        </p>
        
        <p>
            <label>Password:</label><br>
            <input type="password" name="password" required>
        </p>
        
        <p>
            <label>First Name:</label><br>
            <input type="text" name="first_name" value="<?= htmlspecialchars($_POST['first_name'] ?? '') ?>" required>
        </p>
        
        <p>
            <label>Middle Name (optional):</label><br>
            <input type="text" name="middle_name" value="<?= htmlspecialchars($_POST['middle_name'] ?? '') ?>">
        </p>
        
        <p>
            <label>Last Name:</label><br>
            <input type="text" name="last_name" value="<?= htmlspecialchars($_POST['last_name'] ?? '') ?>" required>
        </p>
        
        <p>
            <button type="submit">Sign Up</button>
        </p>
    </form>
    
    <p><a href="/pages/login/"><button>Already have an account? Login</button></a></p>
    <p><a href="/"><button>Back to Home</button></a></p>
    
</body>
</html>
