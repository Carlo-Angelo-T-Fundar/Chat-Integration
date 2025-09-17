<?php

/**
 * Create Sample User Script
 */

// Define paths for CodeIgniter bootstrap
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR);

// Bootstrap CodeIgniter
require_once __DIR__ . '/app/Config/Paths.php';
require_once SYSTEMPATH . 'bootstrap.php';

$app = Config\Services::codeigniter();
$app->initialize();

echo "Creating sample user...\n";

try {
    $userModel = new \App\Models\UserModel();
    
    // Check if admin user already exists
    $existingUser = $userModel->where('username', 'admin')->first();
    
    if ($existingUser) {
        echo "Admin user already exists!\n";
        echo "Username: admin\n";
        echo "Password: admin123\n";
    } else {
        // Create admin user
        $userData = [
            'username' => 'admin',
            'email' => 'admin@chatapp.com',
            'password' => 'admin123'
        ];
        
        if ($userModel->insert($userData)) {
            echo "Sample admin user created successfully!\n";
            echo "Username: admin\n";
            echo "Email: admin@chatapp.com\n";
            echo "Password: admin123\n";
        } else {
            echo "Failed to create user:\n";
            print_r($userModel->errors());
        }
    }
    
    // Create a second test user
    $existingUser2 = $userModel->where('username', 'testuser')->first();
    
    if (!$existingUser2) {
        $userData2 = [
            'username' => 'testuser',
            'email' => 'test@chatapp.com',
            'password' => 'test123'
        ];
        
        if ($userModel->insert($userData2)) {
            echo "\nSecond test user created!\n";
            echo "Username: testuser\n";
            echo "Email: test@chatapp.com\n";
            echo "Password: test123\n";
        }
    } else {
        echo "\nTest user already exists!\n";
        echo "Username: testuser\n";
        echo "Password: test123\n";
    }
    
    echo "\nYou can now access the application at: http://localhost:8081\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}