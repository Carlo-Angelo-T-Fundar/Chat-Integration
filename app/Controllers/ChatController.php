<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\MessageModel;
use CodeIgniter\Controller;

class ChatController extends BaseController
{
    protected $userModel;
    protected $messageModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->messageModel = new MessageModel();
    }

    public function index()
    {
        $userId = session()->get('user_id');
        
        if (!$userId) {
            return redirect()->to(base_url('login'));
        }

        // Update user online status
        $this->userModel->updateOnlineStatus($userId, true);

        // Get all users except current user
        $users = $this->userModel->getAllUsersExcept($userId);
        
        // Get recent conversations
        $recentMessages = $this->messageModel->getLastMessages($userId);
        
        // Get unread message count
        $unreadCount = $this->messageModel->getUnreadCount($userId);

        $data = [
            'users' => $users,
            'recent_messages' => $recentMessages,
            'unread_count' => $unreadCount,
            'current_user_id' => $userId
        ];

        return view('chat/index', $data);
    }

    public function conversation($contactId = null)
    {
        $userId = session()->get('user_id');
        
        if (!$userId) {
            return $this->response->setJSON(['error' => 'Unauthorized']);
        }

        if (!$contactId) {
            return $this->response->setJSON(['error' => 'Contact ID required']);
        }

        // Get conversation messages
        $messages = $this->messageModel->getConversation($userId, $contactId);
        
        // Mark messages as read
        $this->messageModel->markAsRead($contactId, $userId);
        
        // Get contact info
        $contact = $this->userModel->getUserById($contactId);

        return $this->response->setJSON([
            'messages' => array_reverse($messages), // Reverse to show oldest first
            'contact' => $contact
        ]);
    }

    public function sendMessage()
    {
        $userId = session()->get('user_id');
        
        if (!$userId) {
            return $this->response->setJSON(['error' => 'Unauthorized']);
        }

        $receiverId = $this->request->getPost('receiver_id');
        $message = $this->request->getPost('message');

        if (!$receiverId || !$message) {
            return $this->response->setJSON(['error' => 'Receiver ID and message are required']);
        }

        $messageId = $this->messageModel->sendMessage($userId, $receiverId, $message);

        if ($messageId) {
            // Get the message with sender info
            $sentMessage = $this->messageModel->db->table('messages')
                ->select('messages.*, users.username as sender_name')
                ->join('users', 'users.id = messages.sender_id')
                ->where('messages.id', $messageId)
                ->get()
                ->getRowArray();

            return $this->response->setJSON([
                'success' => true,
                'message' => $sentMessage
            ]);
        } else {
            return $this->response->setJSON(['error' => 'Failed to send message']);
        }
    }

    public function getUsers()
    {
        $userId = session()->get('user_id');
        
        if (!$userId) {
            return $this->response->setJSON(['error' => 'Unauthorized']);
        }

        $users = $this->userModel->getAllUsersExcept($userId);
        
        // Add unread count for each user
        foreach ($users as &$user) {
            $user['unread_count'] = $this->messageModel->getUnreadCountFrom($user['id'], $userId);
        }

        return $this->response->setJSON(['users' => $users]);
    }

    public function getRecentMessages()
    {
        $userId = session()->get('user_id');
        
        if (!$userId) {
            return $this->response->setJSON(['error' => 'Unauthorized']);
        }

        $recentMessages = $this->messageModel->getLastMessages($userId);

        return $this->response->setJSON(['messages' => $recentMessages]);
    }

    public function checkNewMessages()
    {
        $userId = session()->get('user_id');
        $lastCheck = $this->request->getGet('last_check');
        
        if (!$userId) {
            return $this->response->setJSON(['error' => 'Unauthorized']);
        }

        $builder = $this->messageModel->db->table('messages')
            ->select('messages.*, sender.username as sender_name')
            ->join('users as sender', 'sender.id = messages.sender_id')
            ->where('messages.receiver_id', $userId);

        if ($lastCheck) {
            $builder->where('messages.created_at >', $lastCheck);
        }

        $newMessages = $builder->orderBy('messages.created_at', 'ASC')
                              ->get()
                              ->getResultArray();

        // Update online status
        $this->userModel->updateOnlineStatus($userId, true);

        return $this->response->setJSON([
            'messages' => $newMessages,
            'count' => count($newMessages)
        ]);
    }

    public function updateOnlineStatus()
    {
        $userId = session()->get('user_id');
        
        if (!$userId) {
            return $this->response->setJSON(['error' => 'Unauthorized']);
        }

        $this->userModel->updateOnlineStatus($userId, true);

        return $this->response->setJSON(['success' => true]);
    }

    public function setOffline()
    {
        $userId = session()->get('user_id');
        
        if ($userId) {
            $this->userModel->updateOnlineStatus($userId, false);
        }

        return $this->response->setJSON(['success' => true]);
    }

    public function search()
    {
        $userId = session()->get('user_id');
        $query = $this->request->getGet('q');
        
        if (!$userId) {
            return $this->response->setJSON(['error' => 'Unauthorized']);
        }

        if (!$query) {
            return $this->response->setJSON(['users' => []]);
        }

        $users = $this->userModel->where('id !=', $userId)
                                ->groupStart()
                                    ->like('username', $query)
                                    ->orLike('email', $query)
                                ->groupEnd()
                                ->select('id, username, email, is_online, last_seen')
                                ->findAll();

        return $this->response->setJSON(['users' => $users]);
    }
}