<?php
/**
 * Application Health Check and Validation Script
 */

// Define constants first
define('ROOT_PATH', __DIR__);
define('APP_PATH', ROOT_PATH . '/app');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('STORAGE_PATH', ROOT_PATH . '/storage');
define('CONFIG_PATH', ROOT_PATH . '/config');

// Auto-loader for classes
spl_autoload_register(function ($class) {
    // Remove the App\\ namespace prefix and convert to file path
    $class = str_replace('App\\', '', $class);
    $class = str_replace('\\', '/', $class);
    
    $file = APP_PATH . '/' . $class . '.php';
    
    if (file_exists($file)) {
        require_once $file;
        return true;
    }
    
    return false;
});

require_once __DIR__ . '/config/config.php';

echo "=== School Website Health Check ===\n\n";

$errors = [];
$warnings = [];
$passes = 0;
$total = 0;

function check($description, $condition, $isWarning = false) {
    global $errors, $warnings, $passes, $total;
    $total++;
    
    if ($condition) {
        echo "✓ {$description}\n";
        $passes++;
    } else {
        if ($isWarning) {
            echo "⚠ {$description}\n";
            $warnings[] = $description;
        } else {
            echo "✗ {$description}\n";
            $errors[] = $description;
        }
    }
}

// Configuration checks
echo "Configuration Checks:\n";
check("APP_NAME is defined", defined('APP_NAME'));
check("Database constants are defined", defined('DB_HOST') && defined('DB_NAME') && defined('DB_USER'));
check("Security constants are defined", defined('CSRF_TOKEN_NAME') && defined('SESSION_LIFETIME'));
check("File upload constants are defined", defined('MAX_FILE_SIZE') && defined('UPLOAD_PATH'));

// Directory structure checks
echo "\nDirectory Structure:\n";
check("APP_PATH exists", is_dir(APP_PATH));
check("PUBLIC_PATH exists", is_dir(PUBLIC_PATH));
check("STORAGE_PATH exists", is_dir(STORAGE_PATH));
check("Config directory exists", is_dir(CONFIG_PATH));
check("Views directory exists", is_dir(APP_PATH . '/Views'));
check("Models directory exists", is_dir(APP_PATH . '/Models'));
check("Controllers directory exists", is_dir(APP_PATH . '/Controllers'));
check("Core directory exists", is_dir(APP_PATH . '/Core'));

// File existence checks
echo "\nCore Files:\n";
check("Environment class exists", file_exists(APP_PATH . '/Core/Environment.php'));
check("Logger class exists", file_exists(APP_PATH . '/Core/Logger.php'));
check("ErrorHandler class exists", file_exists(APP_PATH . '/Core/ErrorHandler.php'));
check("Database class exists", file_exists(APP_PATH . '/Core/Database.php'));
check("Controller base class exists", file_exists(APP_PATH . '/Core/Controller.php'));
check("Model base class exists", file_exists(APP_PATH . '/Core/Model.php'));
check("SimpleRouter class exists", file_exists(APP_PATH . '/Core/SimpleRouter.php'));

// Model files
echo "\nModel Files:\n";
check("User model exists", file_exists(APP_PATH . '/Models/User.php'));
check("News model exists", file_exists(APP_PATH . '/Models/News.php'));
check("Event model exists", file_exists(APP_PATH . '/Models/Event.php'));
check("Setting model exists", file_exists(APP_PATH . '/Models/Setting.php'));
check("Message model exists", file_exists(APP_PATH . '/Models/Message.php'));
check("Admission model exists", file_exists(APP_PATH . '/Models/Admission.php'));
check("Media model exists", file_exists(APP_PATH . '/Models/Media.php'));
check("Page model exists", file_exists(APP_PATH . '/Models/Page.php'));

// Controller files
echo "\nController Files:\n";
check("HomeController exists", file_exists(APP_PATH . '/Controllers/HomeController.php'));
check("AuthController exists", file_exists(APP_PATH . '/Controllers/AuthController.php'));
check("PublicController exists", file_exists(APP_PATH . '/Controllers/PublicController.php'));
check("NewsController exists", file_exists(APP_PATH . '/Controllers/NewsController.php'));
check("EventController exists", file_exists(APP_PATH . '/Controllers/EventController.php'));

// View files
echo "\nView Files:\n";
check("Public layout exists", file_exists(APP_PATH . '/Views/layouts/public.php'));
check("Auth layout exists", file_exists(APP_PATH . '/Views/layouts/auth.php'));
check("Home view exists", file_exists(APP_PATH . '/Views/public/home.php'));
check("About view exists", file_exists(APP_PATH . '/Views/public/about.php'));
check("Academics view exists", file_exists(APP_PATH . '/Views/public/academics.php'));
check("Admissions view exists", file_exists(APP_PATH . '/Views/public/admissions.php'));
check("Contact view exists", file_exists(APP_PATH . '/Views/public/contact.php'));
check("Gallery view exists", file_exists(APP_PATH . '/Views/public/gallery.php'));
check("Downloads view exists", file_exists(APP_PATH . '/Views/public/downloads.php'));
check("404 error page exists", file_exists(APP_PATH . '/Views/errors/404.php'));
check("500 error page exists", file_exists(APP_PATH . '/Views/errors/500.php'));

// Helper files
echo "\nHelper Files:\n";
check("Validator helper exists", file_exists(APP_PATH . '/Helpers/Validator.php'));
check("FileUploader helper exists", file_exists(APP_PATH . '/Helpers/FileUploader.php'));

// Middleware files
echo "\nMiddleware Files:\n";
check("AuthMiddleware exists", file_exists(APP_PATH . '/Middleware/AuthMiddleware.php'));
check("GuestMiddleware exists", file_exists(APP_PATH . '/Middleware/GuestMiddleware.php'));

// Database connection test
echo "\nDatabase Connection:\n";
try {
    $db = App\Core\Database::getInstance();
    check("Database connection successful", true);
    
    // Test basic queries
    $result = $db->fetch("SELECT COUNT(*) as count FROM users");
    check("Users table accessible", is_array($result) && isset($result['count']));
    
    $result = $db->fetch("SELECT COUNT(*) as count FROM settings");
    check("Settings table accessible", is_array($result) && isset($result['count']));
    
} catch (Exception $e) {
    check("Database connection failed: " . $e->getMessage(), false);
}

// Class loading test
echo "\nClass Loading:\n";
try {
    new App\Core\Environment();
    check("Environment class loads", true);
} catch (Exception $e) {
    check("Environment class loading failed", false);
}

try {
    App\Core\Logger::getInstance();
    check("Logger class loads", true);
} catch (Exception $e) {
    check("Logger class loading failed", false);
}

try {
    new App\Models\User();
    check("User model loads", true);
} catch (Exception $e) {
    check("User model loading failed", false);
}

// Security checks
echo "\nSecurity Checks:\n";
check("CSRF token name is configured", defined('CSRF_TOKEN_NAME') && !empty(CSRF_TOKEN_NAME));
check("Session lifetime is set", defined('SESSION_LIFETIME') && SESSION_LIFETIME > 0);
check("Password minimum length is set", defined('PASSWORD_MIN_LENGTH') && PASSWORD_MIN_LENGTH >= 8);
check("File upload size limit is set", defined('MAX_FILE_SIZE') && MAX_FILE_SIZE > 0);

// File permissions
echo "\nFile Permissions:\n";
check("Upload directory is writable", is_writable(PUBLIC_PATH . '/' . UPLOAD_PATH));
check("Storage directory is writable", is_writable(STORAGE_PATH));
check("Logs directory is writable", is_writable(STORAGE_PATH . '/logs'));

// Environment file
echo "\nEnvironment Configuration:\n";
check(".env.example exists", file_exists(ROOT_PATH . '/.env.example'));
check(".htaccess exists", file_exists(ROOT_PATH . '/.htaccess'));

// Summary
echo "\n=== Summary ===\n";
echo "Total checks: {$total}\n";
echo "Passed: {$passes}\n";
echo "Warnings: " . count($warnings) . "\n";
echo "Errors: " . count($errors) . "\n";

if (!empty($warnings)) {
    echo "\nWarnings:\n";
    foreach ($warnings as $warning) {
        echo "⚠ {$warning}\n";
    }
}

if (!empty($errors)) {
    echo "\nErrors:\n";
    foreach ($errors as $error) {
        echo "✗ {$error}\n";
    }
    echo "\nPlease fix the errors above before running the application.\n";
    exit(1);
} else {
    echo "\n🎉 All checks passed! Your application is ready to run.\n";
    echo "\nTo start the application:\n";
    echo "1. Start your web server (Apache/Nginx or PHP built-in server)\n";
    echo "2. Access your site at the configured URL\n";
    echo "3. Use admin/admin123 to login to the admin panel\n";
}