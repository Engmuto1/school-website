<?php
/**
 * Check Admin Users Script
 */

define('ROOT_PATH', __DIR__);
define('APP_PATH', ROOT_PATH . '/app');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('STORAGE_PATH', ROOT_PATH . '/storage');
define('CONFIG_PATH', ROOT_PATH . '/config');

// Auto-loader for classes
spl_autoload_register(function ($class) {
    $class = str_replace('App\\', '', $class);
    $class = str_replace('\\', '/', $class);
    $file = APP_PATH . '/' . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
        return true;
    }
    return false;
});

require_once CONFIG_PATH . '/config.php';

echo "=== Admin User Check ===\n\n";

try {
    $db = App\Core\Database::getInstance();
    
    // Check if users table exists
    $tables = $db->fetchAll("SHOW TABLES LIKE 'users'");
    if (empty($tables)) {
        echo "❌ Users table does not exist. Please run the migration first.\n";
        echo "Run: c:\\xampp\\php\\php.exe c:\\xampp\\htdocs\\school\\migrate.php\n";
        exit(1);
    }
    
    echo "✅ Users table exists\n\n";
    
    // Get all admin users
    $users = $db->fetchAll("SELECT id, username, email, first_name, last_name, role, status, created_at FROM users WHERE role = 'admin'");
    
    if (empty($users)) {
        echo "❌ No admin users found in database!\n\n";
        echo "Creating default admin user...\n";
        
        // Create default admin user
        $userData = [
            'username' => 'admin',
            'email' => 'admin@school.com',
            'password' => password_hash('admin123', PASSWORD_DEFAULT),
            'first_name' => 'Administrator',
            'last_name' => 'User',
            'role' => 'admin',
            'status' => 'active'
        ];
        
        $userId = $db->insert('users', $userData);
        
        if ($userId) {
            echo "✅ Admin user created successfully!\n";
            echo "Username: admin\n";
            echo "Password: admin123\n";
            echo "Email: admin@school.com\n";
        } else {
            echo "❌ Failed to create admin user\n";
        }
    } else {
        echo "Admin users found:\n";
        foreach ($users as $user) {
            echo "---\n";
            echo "ID: " . $user['id'] . "\n";
            echo "Username: " . $user['username'] . "\n";
            echo "Email: " . $user['email'] . "\n";
            echo "Name: " . $user['first_name'] . ' ' . $user['last_name'] . "\n";
            echo "Status: " . $user['status'] . "\n";
            echo "Created: " . $user['created_at'] . "\n";
        }
        
        echo "\n=== Default Credentials ===\n";
        echo "Username: admin\n";
        echo "Password: admin123\n";
        echo "\nIf these don't work, the password may have been changed.\n";
    }
    
    // Test password hash
    $testUser = $db->fetch("SELECT password FROM users WHERE username = 'admin'");
    if ($testUser) {
        $isValid = password_verify('admin123', $testUser['password']);
        echo "\n=== Password Test ===\n";
        echo "Password 'admin123' is " . ($isValid ? "✅ VALID" : "❌ INVALID") . " for user 'admin'\n";
        
        if (!$isValid) {
            echo "\n🔧 Resetting admin password to 'admin123'...\n";
            $newHash = password_hash('admin123', PASSWORD_DEFAULT);
            $updated = $db->update('users', ['password' => $newHash], 'username = "admin"', []);
            if ($updated) {
                echo "✅ Password reset successfully!\n";
            } else {
                echo "❌ Failed to reset password\n";
            }
        }
    }
    
} catch (Exception $e) {
    echo "❌ Database Error: " . $e->getMessage() . "\n";
    echo "\nPlease check:\n";
    echo "1. MySQL service is running\n";
    echo "2. Database credentials in .env or config.php\n";
    echo "3. Database 'school_website' exists\n";
}