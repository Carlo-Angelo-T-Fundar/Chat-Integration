<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class AuthController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function login()
    {
        // If user is already logged in, redirect to chat
        if (session()->get('user_id')) {
            return redirect()->to(base_url('chat'));
        }

        if ($this->request->getMethod() === 'POST') {
            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');

            $user = $this->userModel->authenticate($username, $password);

            if ($user) {
                // Set session data
                session()->set([
                    'user_id' => $user['id'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'is_logged_in' => true
                ]);

                // Update online status
                $this->userModel->updateOnlineStatus($user['id'], true);

                return redirect()->to(base_url('chat'))->with('success', 'Welcome back, ' . $user['username'] . '!');
            } else {
                return redirect()->back()->with('error', 'Invalid username or password');
            }
        }

        return view('auth/login');
    }

    public function register()
    {
        // If user is already logged in, redirect to chat
        if (session()->get('user_id')) {
            return redirect()->to(base_url('chat'));
        }

        if ($this->request->getMethod() === 'POST') {
            $data = [
                'username' => $this->request->getPost('username'),
                'email' => $this->request->getPost('email'),
                'password' => $this->request->getPost('password'),
            ];

            $confirmPassword = $this->request->getPost('confirm_password');

            // Check if passwords match
            if ($data['password'] !== $confirmPassword) {
                return redirect()->back()->with('error', 'Passwords do not match')->withInput();
            }

            if ($this->userModel->insert($data)) {
                return redirect()->to(base_url('login'))->with('success', 'Account created successfully! Please log in.');
            } else {
                $errors = $this->userModel->errors();
                $errorMessages = [];
                
                foreach ($errors as $field => $message) {
                    $errorMessages[] = $message;
                }
                
                return redirect()->back()->with('error', implode('<br>', $errorMessages))->withInput();
            }
        }

        return view('auth/register');
    }

    public function logout()
    {
        $userId = session()->get('user_id');
        
        if ($userId) {
            // Update online status to offline
            $this->userModel->updateOnlineStatus($userId, false);
        }

        // Destroy session
        session()->destroy();

        return redirect()->to(base_url('login'))->with('success', 'You have been logged out successfully.');
    }

    public function profile()
    {
        $userId = session()->get('user_id');
        
        if (!$userId) {
            return redirect()->to(base_url('login'));
        }

        $user = $this->userModel->getUserById($userId);

        if ($this->request->getMethod() === 'POST') {
            $data = [
                'username' => $this->request->getPost('username'),
                'email' => $this->request->getPost('email'),
            ];

            $newPassword = $this->request->getPost('new_password');
            $confirmPassword = $this->request->getPost('confirm_password');

            if (!empty($newPassword)) {
                if ($newPassword !== $confirmPassword) {
                    return redirect()->back()->with('error', 'Passwords do not match');
                }
                $data['password'] = $newPassword;
            }

            // Temporarily disable unique validation for current user
            $this->userModel->setValidationRule('username', 'required|alpha_numeric|min_length[3]|max_length[50]|is_unique[users.username,id,' . $userId . ']');
            $this->userModel->setValidationRule('email', 'required|valid_email|is_unique[users.email,id,' . $userId . ']');

            if ($this->userModel->update($userId, $data)) {
                // Update session data
                session()->set([
                    'username' => $data['username'],
                    'email' => $data['email']
                ]);
                
                return redirect()->back()->with('success', 'Profile updated successfully!');
            } else {
                $errors = $this->userModel->errors();
                $errorMessages = [];
                
                foreach ($errors as $field => $message) {
                    $errorMessages[] = $message;
                }
                
                return redirect()->back()->with('error', implode('<br>', $errorMessages));
            }
        }

        return view('auth/profile', ['user' => $user]);
    }
}