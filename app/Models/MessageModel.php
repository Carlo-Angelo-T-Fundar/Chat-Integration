<?php

namespace App\Models;

use CodeIgniter\Model;

class MessageModel extends Model
{
    protected $table = 'messages';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['sender_id', 'receiver_id', 'message', 'is_read'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'sender_id' => 'required|integer',
        'receiver_id' => 'required|integer',
        'message' => 'required|min_length[1]|max_length[1000]',
    ];

    protected $validationMessages = [
        'sender_id' => [
            'required' => 'Sender ID is required',
            'integer' => 'Sender ID must be an integer',
        ],
        'receiver_id' => [
            'required' => 'Receiver ID is required',
            'integer' => 'Receiver ID must be an integer',
        ],
        'message' => [
            'required' => 'Message is required',
            'min_length' => 'Message cannot be empty',
            'max_length' => 'Message cannot exceed 1000 characters',
        ],
    ];

    protected $skipValidation = false;

    public function sendMessage($senderId, $receiverId, $message)
    {
        $data = [
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'message' => $message,
            'is_read' => false
        ];

        return $this->insert($data);
    }

    public function getConversation($user1Id, $user2Id, $limit = 50)
    {
        return $this->db->table($this->table)
            ->select('messages.*, 
                     sender.username as sender_name, 
                     receiver.username as receiver_name')
            ->join('users as sender', 'sender.id = messages.sender_id')
            ->join('users as receiver', 'receiver.id = messages.receiver_id')
            ->where('(sender_id = ' . $user1Id . ' AND receiver_id = ' . $user2Id . ')')
            ->orWhere('(sender_id = ' . $user2Id . ' AND receiver_id = ' . $user1Id . ')')
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    public function getLastMessages($userId)
    {
        // Use a simpler approach with UNION to get the latest messages
        // Get the latest message for each unique conversation
        $sql = "
            SELECT m1.*, 
                   sender.username as sender_name, 
                   receiver.username as receiver_name,
                   CASE 
                       WHEN m1.sender_id = ? THEN receiver.id
                       ELSE sender.id
                   END as contact_id,
                   CASE 
                       WHEN m1.sender_id = ? THEN receiver.username
                       ELSE sender.username
                   END as contact_name
            FROM {$this->table} m1
            INNER JOIN users as sender ON sender.id = m1.sender_id
            INNER JOIN users as receiver ON receiver.id = m1.receiver_id
            INNER JOIN (
                SELECT 
                    GREATEST(sender_id, receiver_id) as user1,
                    LEAST(sender_id, receiver_id) as user2,
                    MAX(id) as max_id
                FROM {$this->table}
                WHERE sender_id = ? OR receiver_id = ?
                GROUP BY GREATEST(sender_id, receiver_id), LEAST(sender_id, receiver_id)
            ) m2 ON m1.id = m2.max_id
            ORDER BY m1.created_at DESC
        ";
        
        return $this->db->query($sql, [$userId, $userId, $userId, $userId])->getResultArray();
    }

    public function markAsRead($senderId, $receiverId)
    {
        return $this->where('sender_id', $senderId)
                    ->where('receiver_id', $receiverId)
                    ->where('is_read', false)
                    ->set(['is_read' => true])
                    ->update();
    }

    public function getUnreadCount($userId)
    {
        return $this->where('receiver_id', $userId)
                    ->where('is_read', false)
                    ->countAllResults();
    }

    public function getUnreadCountFrom($senderId, $receiverId)
    {
        return $this->where('sender_id', $senderId)
                    ->where('receiver_id', $receiverId)
                    ->where('is_read', false)
                    ->countAllResults();
    }

    public function getRecentMessages($userId, $limit = 20)
    {
        return $this->db->table($this->table)
            ->select('messages.*, 
                     sender.username as sender_name, 
                     receiver.username as receiver_name')
            ->join('users as sender', 'sender.id = messages.sender_id')
            ->join('users as receiver', 'receiver.id = messages.receiver_id')
            ->where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }
}