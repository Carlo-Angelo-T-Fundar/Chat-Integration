<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Admin user data
        $adminData = [
            'username' => 'admin',
            'email' => 'admin@chatapp.com',
            'password' => password_hash('admin123', PASSWORD_DEFAULT),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        // Test user data
        $testData = [
            'username' => 'testuser',
            'email' => 'test@chatapp.com',
            'password' => password_hash('test123', PASSWORD_DEFAULT),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        // Insert users if they don't exist
        $userModel = new \App\Models\UserModel();
        
        if (!$userModel->where('username', 'admin')->first()) {
            $this->db->table('users')->insert($adminData);
            echo "Admin user created.\n";
        }
        
        if (!$userModel->where('username', 'testuser')->first()) {
            $this->db->table('users')->insert($testData);
            echo "Test user created.\n";
        }
    }
}