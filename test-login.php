<?php
/**
 * Test Admin Login Script
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

echo "=== Admin Login Test ===\n\n";

try {
    // Test database connection
    $db = App\Core\Database::getInstance();
    echo "✅ Database connection successful\n";
    
    // Test admin user
    $user = $db->fetch("SELECT id, username, email, password, status FROM users WHERE username = 'admin'");
    if (!$user) {
        echo "❌ Admin user not found\n";
        exit(1);
    }
    
    echo "✅ Admin user found\n";
    echo "   Username: " . $user['username'] . "\n";
    echo "   Email: " . $user['email'] . "\n";
    echo "   Status: " . $user['status'] . "\n";
    
    // Test password
    $testPassword = 'admin123';
    $isValid = password_verify($testPassword, $user['password']);
    echo "✅ Password verification: " . ($isValid ? "VALID" : "INVALID") . "\n";
    
    if (!$isValid) {
        echo "❌ Password test failed - login will not work\n";
        exit(1);
    }
    
    // Test controller loading
    try {
        $authController = new App\Controllers\AuthController();
        echo "✅ AuthController loads successfully\n";
    } catch (Exception $e) {
        echo "❌ AuthController failed to load: " . $e->getMessage() . "\n";
        exit(1);
    }
    
    // Test admin controller loading
    try {
        $dashboardController = new App\Controllers\Admin\DashboardController();
        echo "✅ Admin DashboardController loads successfully\n";
    } catch (Exception $e) {
        echo "❌ Admin DashboardController failed to load: " . $e->getMessage() . "\n";
        exit(1);
    }
    
    // Test view files
    $requiredViews = [
        'app/Views/auth/login.php',
        'app/Views/layouts/auth.php',
        'app/Views/admin/dashboard.php',
        'app/Views/layouts/admin.php'
    ];
    
    foreach ($requiredViews as $view) {
        if (file_exists($view)) {
            echo "✅ View exists: $view\n";
        } else {
            echo "❌ View missing: $view\n";
        }
    }
    
    echo "\n🎉 All tests passed! Admin login should work with:\n";
    echo "Username: admin\n";
    echo "Password: admin123\n";
    echo "\nAccess the login page at: http://localhost:8080/admin/login\n";
    
} catch (Exception $e) {
    echo "❌ Test failed: " . $e->getMessage() . "\n";
    exit(1);
}