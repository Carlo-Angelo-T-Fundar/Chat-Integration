<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['username', 'email', 'password', 'is_online', 'last_seen'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'username' => 'required|alpha_numeric|min_length[3]|max_length[50]|is_unique[users.username]',
        'email' => 'required|valid_email|is_unique[users.email]',
        'password' => 'required|min_length[6]',
    ];

    protected $validationMessages = [
        'username' => [
            'required' => 'Username is required',
            'alpha_numeric' => 'Username can only contain letters and numbers',
            'min_length' => 'Username must be at least 3 characters long',
            'max_length' => 'Username cannot exceed 50 characters',
            'is_unique' => 'Username already exists',
        ],
        'email' => [
            'required' => 'Email is required',
            'valid_email' => 'Please enter a valid email address',
            'is_unique' => 'Email already exists',
        ],
        'password' => [
            'required' => 'Password is required',
            'min_length' => 'Password must be at least 6 characters long',
        ],
    ];

    protected $skipValidation = false;

    // Callbacks
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }
        return $data;
    }

    public function verifyPassword($inputPassword, $hashedPassword)
    {
        return password_verify($inputPassword, $hashedPassword);
    }

    public function authenticate($username, $password)
    {
        $user = $this->where('username', $username)
                     ->orWhere('email', $username)
                     ->first();

        if ($user && $this->verifyPassword($password, $user['password'])) {
            return $user;
        }

        return false;
    }

    public function updateOnlineStatus($userId, $isOnline = true)
    {
        $data = [
            'is_online' => $isOnline,
            'last_seen' => date('Y-m-d H:i:s')
        ];

        return $this->update($userId, $data);
    }

    public function getOnlineUsers()
    {
        return $this->where('is_online', true)
                    ->select('id, username, email, is_online, last_seen')
                    ->findAll();
    }

    public function getAllUsersExcept($userId)
    {
        return $this->where('id !=', $userId)
                    ->select('id, username, email, is_online, last_seen')
                    ->orderBy('is_online', 'DESC')
                    ->orderBy('last_seen', 'DESC')
                    ->findAll();
    }

    public function getUserById($id)
    {
        return $this->select('id, username, email, is_online, last_seen, created_at')
                    ->find($id);
    }
}