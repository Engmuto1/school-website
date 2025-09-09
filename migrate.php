<?php
/**
 * Database Migration Runner Script
 * Usage: php migrate.php
 */

echo "=== School Website Database Migration ===\n\n";

// Check if we're running from command line
if (php_sapi_name() !== 'cli') {
    echo "This script should be run from the command line.\n";
    exit(1);
}

// Load the migration
require_once __DIR__ . '/database/migrations/001_create_initial_tables.php';

try {
    $migration = new DatabaseMigration();
    $migration->runMigrations();
    
    echo "\n=== Migration Summary ===\n";
    echo "✓ Database schema created\n";
    echo "✓ Sample data inserted\n";
    echo "✓ Default admin user created (admin/admin123)\n";
    echo "\nYou can now access your school website!\n";
    
} catch (Exception $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
    exit(1);
}