#!/usr/bin/env php
<?php

/**
 * Chat App Setup Script
 * 
 * This script sets up the database and runs migrations for the chat application.
 */

// Define paths
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR);

// Set the path to the CodeIgniter 4 directory
$pathsPath = realpath(__DIR__ . '/app/Config/Paths.php');
require $pathsPath;

// Load the framework bootstrap file
require SYSTEMPATH . 'bootstrap.php';

$app = Config\Services::codeigniter();
$app->initialize();

echo "=== Chat App Setup Script ===\n\n";

try {
    // Get database instance
    $db = \Config\Database::connect();
    
    echo "1. Testing database connection...\n";
    
    // Test database connection
    if ($db->connID) {
        echo "   ✓ Database connection successful\n";
    } else {
        throw new Exception("Failed to connect to database");
    }
    
    echo "\n2. Creating database if it doesn't exist...\n";
    
    // Create database if it doesn't exist
    $dbName = $db->getDatabase();
    $db->query("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "   ✓ Database '{$dbName}' ready\n";
    
    echo "\n3. Running migrations...\n";
    
    // Run migrations
    $migrate = \Config\Services::migrations();
    
    try {
        $migrate->latest();
        echo "   ✓ Migrations completed successfully\n";
    } catch (Exception $e) {
        echo "   ⚠ Migration warning: " . $e->getMessage() . "\n";
        echo "   This might be normal if migrations were already run.\n";
    }
    
    echo "\n4. Checking tables...\n";
    
    // Check if tables exist
    $tables = $db->listTables();
    $requiredTables = ['users', 'messages'];
    $missingTables = [];
    
    foreach ($requiredTables as $table) {
        if (in_array($table, $tables)) {
            echo "   ✓ Table '{$table}' exists\n";
        } else {
            $missingTables[] = $table;
        }
    }
    
    if (!empty($missingTables)) {
        echo "   ⚠ Missing tables: " . implode(', ', $missingTables) . "\n";
        echo "   Please check your migration files.\n";
    }
    
    echo "\n5. Creating sample user (optional)...\n";
    
    // Check if we should create a sample user
    $userModel = new \App\Models\UserModel();
    $existingUsers = $userModel->countAllResults();
    
    if ($existingUsers == 0) {
        $sampleUser = [
            'username' => 'admin',
            'email' => 'admin@chatapp.com',
            'password' => 'admin123'
        ];
        
        if ($userModel->insert($sampleUser)) {
            echo "   ✓ Sample user created:\n";
            echo "     Username: admin\n";
            echo "     Email: admin@chatapp.com\n";
            echo "     Password: admin123\n";
        } else {
            echo "   ⚠ Failed to create sample user\n";
            $errors = $userModel->errors();
            foreach ($errors as $error) {
                echo "     Error: {$error}\n";
            }
        }
    } else {
        echo "   → Users already exist ({$existingUsers} users found)\n";
    }
    
    echo "\n=== Setup Complete! ===\n";
    echo "\nYour chat application is ready to use!\n";
    echo "\nNext steps:\n";
    echo "1. Start your web server (XAMPP, WAMP, etc.)\n";
    echo "2. Navigate to your application URL\n";
    echo "3. Create accounts or use the sample admin account\n";
    echo "4. Start chatting!\n\n";
    
} catch (Exception $e) {
    echo "\n❌ Setup failed: " . $e->getMessage() . "\n";
    echo "\nPlease check:\n";
    echo "1. Database connection settings in app/Config/Database.php\n";
    echo "2. MySQL/MariaDB is running\n";
    echo "3. Database user has proper permissions\n\n";
    exit(1);
}