<?php

if (!function_exists('timeAgo')) {
    /**
     * Generate a "time ago" string from a datetime
     *
     * @param string $datetime
     * @return string
     */
    function timeAgo($datetime)
    {
        if (!$datetime) {
            return 'Never';
        }

        $time = time() - strtotime($datetime);

        if ($time < 60) {
            return 'Just now';
        } elseif ($time < 3600) {
            $minutes = floor($time / 60);
            return $minutes . 'm ago';
        } elseif ($time < 86400) {
            $hours = floor($time / 3600);
            return $hours . 'h ago';
        } elseif ($time < 2592000) {
            $days = floor($time / 86400);
            return $days . 'd ago';
        } elseif ($time < 31536000) {
            $months = floor($time / 2592000);
            return $months . ' month' . ($months > 1 ? 's' : '') . ' ago';
        } else {
            $years = floor($time / 31536000);
            return $years . ' year' . ($years > 1 ? 's' : '') . ' ago';
        }
    }
}

if (!function_exists('formatMessageTime')) {
    /**
     * Format message timestamp for display
     *
     * @param string $datetime
     * @return string
     */
    function formatMessageTime($datetime)
    {
        if (!$datetime) {
            return '';
        }

        $timestamp = strtotime($datetime);
        $today = date('Y-m-d');
        $messageDate = date('Y-m-d', $timestamp);

        if ($messageDate === $today) {
            return date('g:i A', $timestamp);
        } elseif ($messageDate === date('Y-m-d', strtotime('-1 day'))) {
            return 'Yesterday ' . date('g:i A', $timestamp);
        } else {
            return date('M j, g:i A', $timestamp);
        }
    }
}

if (!function_exists('getInitials')) {
    /**
     * Get initials from a name
     *
     * @param string $name
     * @return string
     */
    function getInitials($name)
    {
        $words = explode(' ', trim($name));
        $initials = '';
        
        foreach ($words as $word) {
            if (strlen($word) > 0) {
                $initials .= strtoupper($word[0]);
                if (strlen($initials) >= 2) {
                    break;
                }
            }
        }
        
        return $initials ?: strtoupper(substr($name, 0, 2));
    }
}

if (!function_exists('isOnlineRecently')) {
    /**
     * Check if user was online recently (within last 5 minutes)
     *
     * @param string $lastSeen
     * @return bool
     */
    function isOnlineRecently($lastSeen)
    {
        if (!$lastSeen) {
            return false;
        }
        
        $time = time() - strtotime($lastSeen);
        return $time < 300; // 5 minutes
    }
}

if (!function_exists('truncateMessage')) {
    /**
     * Truncate a message for preview
     *
     * @param string $message
     * @param int $length
     * @return string
     */
    function truncateMessage($message, $length = 50)
    {
        if (strlen($message) <= $length) {
            return $message;
        }
        
        return substr($message, 0, $length) . '...';
    }
}

if (!function_exists('sanitizeMessage')) {
    /**
     * Sanitize message content for display
     *
     * @param string $message
     * @return string
     */
    function sanitizeMessage($message)
    {
        // Convert URLs to links
        $message = preg_replace(
            '/(https?:\/\/[^\s]+)/',
            '<a href="$1" target="_blank" rel="noopener noreferrer">$1</a>',
            htmlspecialchars($message)
        );
        
        // Convert line breaks to <br>
        $message = nl2br($message);
        
        return $message;
    }
}