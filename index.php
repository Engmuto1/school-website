<?php
/**
 * School Website - Entry Point
 * Modern PHP MVC Application
 */

// Define constants
define('ROOT_PATH', __DIR__);
define('APP_PATH', ROOT_PATH . '/app');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('STORAGE_PATH', ROOT_PATH . '/storage');
define('CONFIG_PATH', ROOT_PATH . '/config');

// Auto-loader for classes
spl_autoload_register(function ($class) {
    // Remove the App\ namespace prefix and convert to file path
    $class = str_replace('App\\', '', $class);
    $class = str_replace('\\', '/', $class);
    
    $file = APP_PATH . '/' . $class . '.php';
    
    if (file_exists($file)) {
        require_once $file;
        return true;
    }
    
    return false;
});

// Load configuration
require_once CONFIG_PATH . '/config.php';

// Initialize error handler
$errorHandler = new App\Core\ErrorHandler();

// Initialize the application
try {
    $app = new App\Core\SimpleRouter();
    $app->run();
} catch (Exception $e) {
    // Log error and show user-friendly message
    error_log($e->getMessage());
    http_response_code(500);
    echo "An error occurred. Please try again later.";
}