<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<style>
    .chat-container {
        height: calc(100vh - 120px);
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }
    
    .users-sidebar {
        background: #f8f9fa;
        border-right: 1px solid #e0e0e0;
        height: 100%;
        overflow-y: auto;
        overflow-x: hidden;
        display: flex;
        flex-direction: column;
    }
    
    .users-list-container {
        flex: 1;
        overflow-y: auto;
        overflow-x: hidden;
        min-height: 0;
    }
    
    .user-item {
        padding: 15px;
        border-bottom: 1px solid #e0e0e0;
        cursor: pointer;
        transition: background-color 0.3s ease;
        position: relative;
    }
    
    .user-item:hover {
        background-color: #e9ecef;
    }
    
    .user-item.active {
        background-color: #667eea;
        color: white;
    }
    
    .user-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 18px;
    }
    
    .user-info h6 {
        margin: 0;
        font-size: 14px;
        font-weight: 600;
    }
    
    .user-status {
        font-size: 12px;
        opacity: 0.7;
    }
    
    .online-indicator {
        position: absolute;
        bottom: 2px;
        right: 2px;
        width: 12px;
        height: 12px;
        background: #28a745;
        border: 2px solid white;
        border-radius: 50%;
    }
    
    .offline-indicator {
        background: #6c757d;
    }
    
    .unread-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: #dc3545;
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: bold;
    }
    
    .chat-area {
        height: 100%;
        display: flex;
        flex-direction: column;
        position: relative;
        overflow: hidden;
    }
    
    .chat-header {
        background: #fff;
        border-bottom: 1px solid #e0e0e0;
        padding: 15px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-shrink: 0;
        position: relative;
        z-index: 10;
    }
    
    .chat-messages {
        flex: 1;
        overflow-y: auto;
        overflow-x: hidden;
        padding: 20px;
        padding-bottom: 100px; /* Space for fixed input at bottom */
        background: #f8f9fa;
        min-height: 0;
        max-height: calc(100vh - 280px); /* Fixed height calculation */
        scroll-behavior: auto; /* Changed from smooth to auto for better responsiveness */
        scrollbar-width: thin; /* For Firefox */
        scrollbar-color: #c0c0c0 transparent; /* For Firefox */
        overscroll-behavior: contain; /* Prevent scroll chaining */
    }
    
    /* Custom scrollbar for Webkit browsers */
    .chat-messages::-webkit-scrollbar {
        width: 6px;
    }
    
    .chat-messages::-webkit-scrollbar-track {
        background: transparent;
    }
    
    .chat-messages::-webkit-scrollbar-thumb {
        background: #c0c0c0;
        border-radius: 3px;
    }
    
    .chat-messages::-webkit-scrollbar-thumb:hover {
        background: #a0a0a0;
    }
    
    .chat-messages-container {
        flex: 1;
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }
    
    .chat-input-fixed {
        background: white;
        border-top: 1px solid #e0e0e0;
        padding: 15px 20px;
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 100;
        box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
        flex-shrink: 0;
    }
    
    .message {
        margin-bottom: 15px;
        display: flex;
        align-items: flex-end;
        scroll-margin-bottom: 20px;
    }
    
    .message.sent {
        justify-content: flex-end;
    }
    
    .message.received {
        justify-content: flex-start;
    }
    
    .message-content {
        max-width: 70%;
        padding: 12px 16px;
        border-radius: 18px;
        position: relative;
        word-wrap: break-word;
    }
    
    .message.sent .message-content {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-bottom-right-radius: 4px;
    }
    
    .message.received .message-content {
        background: white;
        border: 1px solid #e0e0e0;
        border-bottom-left-radius: 4px;
    }
    
    .message-time {
        font-size: 11px;
        opacity: 0.7;
        margin-top: 5px;
        text-align: right;
    }
    
    .message.received .message-time {
        text-align: left;
    }
    
    .chat-input-fixed {
        background: white;
        border-top: 1px solid #e0e0e0;
        padding: 15px 20px;
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 100;
        box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
        flex-shrink: 0;
    }
    
    .scroll-to-bottom {
        position: absolute;
        bottom: 120px; /* Increased from 100px to move it higher above the fixed input */
        right: 20px;
        background: #667eea;
        color: white;
        border: none;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        display: none;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        cursor: pointer;
        transition: all 0.3s ease;
        z-index: 200; /* Increased z-index to ensure it's above everything */
    }
    
    .scroll-to-bottom:hover {
        background: #5a67d8;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0,0,0,0.2);
    }
    
    .scroll-to-bottom.show {
        display: flex;
    }
    
    .unread-indicator {
        position: absolute;
        top: -5px;
        right: -5px;
        background: #dc3545;
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        display: none;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: bold;
    }
    
    .welcome-message-compact {
        padding: 0;
        margin: 0;
        line-height: 1;
    }
    
    .welcome-container {
        display: flex;
        align-items: center;
        justify-content: center;
        height: auto;
        min-height: 80px;
        max-height: 120px;
        padding: 15px;
        margin: 20px auto;
        width: fit-content;
        max-width: 200px;
    }
    
    .search-box {
        padding: 15px;
        border-bottom: 1px solid #e0e0e0;
    }
    
    .typing-indicator {
        display: none;
        padding: 10px 20px;
        font-style: italic;
        color: #6c757d;
        font-size: 14px;
        background: white;
        border-top: 1px solid #e0e0e0;
        position: absolute;
        bottom: 95px; /* Adjusted to position above the fixed input */
        left: 0;
        right: 0;
        z-index: 50;
    }
    
    .emoji-btn {
        border: none;
        background: none;
        font-size: 20px;
        cursor: pointer;
        padding: 5px;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }
    
    .emoji-btn:hover {
        background-color: #f8f9fa;
    }
    
    /* Ensure proper scrolling for sidebar and chat */
    .users-sidebar::-webkit-scrollbar,
    .chat-messages::-webkit-scrollbar,
    .users-list-container::-webkit-scrollbar {
        width: 6px;
    }
    
    .users-sidebar::-webkit-scrollbar-track,
    .chat-messages::-webkit-scrollbar-track,
    .users-list-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }
    
    .users-sidebar::-webkit-scrollbar-thumb,
    .chat-messages::-webkit-scrollbar-thumb,
    .users-list-container::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }
    
    .users-sidebar::-webkit-scrollbar-thumb:hover,
    .chat-messages::-webkit-scrollbar-thumb:hover,
    .users-list-container::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
    
    /* Ensure chat input stays at bottom */
    #chatInterface {
        min-height: 0;
        display: flex !important;
        flex-direction: column;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .scroll-to-bottom {
            bottom: 130px; /* Even higher on mobile to account for virtual keyboard */
            right: 15px;
            width: 45px;
            height: 45px;
        }
        
        .chat-messages {
            padding: 15px;
        }
        
        .chat-input-fixed {
            padding: 10px 15px;
        }
        
        .welcome-message-compact {
            padding: 0;
            margin: 0;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-12">
            <div class="chat-container">
                <div class="row h-100 g-0">
                    <!-- Users Sidebar -->
                    <div class="col-md-4 col-lg-3">
                        <div class="users-sidebar">
                            <!-- Search Box -->
                            <div class="search-box">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="userSearch" placeholder="Search users...">
                                    <button class="btn btn-outline-secondary" type="button" id="searchBtn">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Online Users Count -->
                            <div class="px-3 py-2 bg-light border-bottom">
                                <small class="text-muted">
                                    <i class="fas fa-users me-1"></i>
                                    <span id="onlineCount">0</span> users online
                                </small>
                            </div>
                            
                            <!-- Users List -->
                            <div class="users-list-container">
                                <div id="usersList">
                                    <?php if (!empty($users)): ?>
                                        <?php foreach ($users as $user): ?>
                                            <div class="user-item" 
                                                 data-user-id="<?= $user['id'] ?>" 
                                                 data-username="<?= esc($user['username']) ?>">
                                                <div class="d-flex align-items-center">
                                                    <div class="user-avatar position-relative me-3">
                                                        <?= strtoupper(substr($user['username'], 0, 2)) ?>
                                                        <div class="online-indicator <?= $user['is_online'] ? '' : 'offline-indicator' ?>"></div>
                                                    </div>
                                                    <div class="user-info flex-grow-1">
                                                        <h6><?= esc($user['username']) ?></h6>
                                                        <div class="user-status">
                                                            <?php if ($user['is_online']): ?>
                                                                <i class="fas fa-circle text-success me-1" style="font-size: 8px;"></i>Online
                                                            <?php else: ?>
                                                                <i class="fas fa-circle text-secondary me-1" style="font-size: 8px;"></i>
                                                                Last seen <?= timeAgo($user['last_seen']) ?>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                    <div class="unread-badge" style="display: none;">0</div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="text-center py-5">
                                            <i class="fas fa-users fa-2x text-muted mb-3"></i>
                                            <p class="text-muted">No other users found</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Chat Area -->
                    <div class="col-md-8 col-lg-9">
                        <div class="chat-area">
                            <!-- Welcome Message (shown when no chat selected) -->
                            <div id="welcomeMessage" class="welcome-container">
                                <div class="welcome-message-compact">
                                    <div class="text-center">
                                        <i class="fas fa-comment-dots text-muted mb-1" style="font-size: 1.2rem;"></i>
                                        <div style="font-size: 0.9rem; color: #6c757d; margin-bottom: 2px;">Select a user</div>
                                        <div style="font-size: 0.75rem; color: #adb5bd;">Start chatting</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Chat Interface (hidden initially) -->
                            <div id="chatInterface" style="display: none;" class="h-100 d-flex flex-column position-relative">
                                <!-- Chat Header -->
                                <div class="chat-header">
                                    <div class="d-flex align-items-center">
                                        <div class="user-avatar me-3" id="chatUserAvatar">
                                            --
                                        </div>
                                        <div>
                                            <h6 class="mb-0" id="chatUserName">Select a user</h6>
                                            <small class="text-muted" id="chatUserStatus">Offline</small>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <button class="btn btn-sm btn-outline-secondary me-2" id="clearChatBtn" title="Clear chat">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary" id="chatInfoBtn" title="Chat info">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Chat Messages Container -->
                                <div class="chat-messages-container">
                                    <!-- Chat Messages -->
                                    <div class="chat-messages" id="chatMessages">
                                        <!-- Messages will be loaded here -->
                                        <div id="scrollAnchor" style="height: 1px;"></div>
                                    </div>
                                    
                                    <!-- Scroll to Bottom Button -->
                                    <button class="scroll-to-bottom" id="scrollToBottomBtn" title="Scroll to bottom">
                                        <i class="fas fa-chevron-down"></i>
                                        <div class="unread-indicator" id="unreadIndicator">0</div>
                                    </button>
                                    
                                    <!-- Typing Indicator -->
                                    <div class="typing-indicator" id="typingIndicator">
                                        <i class="fas fa-circle-notch fa-spin me-2"></i>
                                        <span id="typingUser">Someone</span> is typing...
                                    </div>
                                </div>
                                
                                <!-- Chat Input - Fixed at bottom -->
                                <div class="chat-input-fixed">
                                    <form id="messageForm">
                                        <div class="input-group">
                                            <button type="button" class="emoji-btn" id="emojiBtn" title="Add emoji">
                                                😊
                                            </button>
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="messageInput" 
                                                   placeholder="Type a message..."
                                                   maxlength="1000"
                                                   autocomplete="off">
                                            <button class="btn btn-primary" type="submit" id="sendBtn">
                                                <i class="fas fa-paper-plane"></i>
                                            </button>
                                        </div>
                                    </form>
                                    <div class="mt-2">
                                        <small class="text-muted">
                                            Press Enter to send • Shift+Enter for new line
                                        </small>
                                    </div>
                                </div>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
let currentChatUser = null;
let lastMessageCheck = null;
let messageCheckInterval = null;
let currentUserId = <?= session()->get('user_id') ?>;
let displayedMessageIds = new Set(); // Track displayed message IDs to prevent duplicates
const baseUrl = '<?= base_url() ?>';

$(document).ready(function() {
    // Initialize chat
    loadUsers();
    updateOnlineStatus();
    
    // Set up mutation observer for chat messages
    const chatMessagesContainer = document.getElementById('chatMessages');
    if (chatMessagesContainer) {
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                    // New message added, scroll to bottom
                    setTimeout(function() {
                        scrollToBottom();
                    }, 50);
                }
            });
        });
        
        observer.observe(chatMessagesContainer, {
            childList: true,
            subtree: true
        });
    }
    
    // Set up periodic checks
    setInterval(updateOnlineStatus, 30000); // Update online status every 30 seconds
    
    // User search functionality
    $('#userSearch').on('input', function() {
        const query = $(this).val().toLowerCase();
        $('.user-item').each(function() {
            const username = $(this).data('username').toLowerCase();
            if (username.includes(query)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
    
    // User selection
    $(document).on('click', '.user-item', function() {
        const userId = $(this).data('user-id');
        const username = $(this).data('username');
        selectUser(userId, username);
    });
    
    // Message form submission
    $('#messageForm').on('submit', function(e) {
        e.preventDefault();
        sendMessage();
    });
    
    // Message input handling
    $('#messageInput').on('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });
    
    // Handle window resize to maintain scroll position - ultra fast
    $(window).on('resize', throttle(function() {
        if (currentChatUser) {
            setTimeout(function() {
                scrollToBottom();
            }, 25); // Ultra fast timing
        }
    }, 200)); // Faster throttle for resize
    
    // Handle focus events to ensure scrolling works - instant
    $('#messageInput').on('focus', function() {
        if (currentChatUser) {
            setTimeout(function() {
                scrollToBottom();
            }, 50); // Faster focus response
        }
    });
    
    // Scroll to bottom button functionality
    $('#scrollToBottomBtn').on('click', function() {
        forceScrollToBottom();
        $(this).removeClass('show');
        $('#unreadIndicator').hide().text('0');
    });
    
    // Throttle function to limit scroll event frequency - very lightweight
    function throttle(func, limit) {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        }
    }
    
    // Handle chat messages scroll to show/hide scroll button - ultra fast
    $('#chatMessages').on('scroll', throttle(function() {
        const chatMessages = this;
        const scrollTop = chatMessages.scrollTop;
        const scrollHeight = chatMessages.scrollHeight;
        const clientHeight = chatMessages.clientHeight;
        const scrollBottom = scrollHeight - scrollTop - clientHeight;
        
        // Show scroll button if user is not at bottom (with smaller threshold for more accurate detection)
        if (scrollBottom > 5) { // Very small threshold - only show if truly not at bottom
            $('#scrollToBottomBtn').addClass('show');
        } else {
            $('#scrollToBottomBtn').removeClass('show');
            $('#unreadIndicator').hide().text('0');
        }
    }, 50)); // Reduced throttle to 50ms for ultra-fast response
    
    // Emoji button (placeholder)
    $('#emojiBtn').on('click', function() {
        const input = $('#messageInput');
        const currentText = input.val();
        const emojis = ['😊', '😂', '❤️', '👍', '👎', '😢', '😡', '🎉', '🔥', '💯'];
        const randomEmoji = emojis[Math.floor(Math.random() * emojis.length)];
        input.val(currentText + randomEmoji);
        input.focus();
    });
    
    // Auto-scroll to bottom of chat - ultra fast
    function scrollToBottom() {
        const chatMessages = $('#chatMessages');
        if (chatMessages.length > 0) {
            chatMessages.animate({
                scrollTop: chatMessages[0].scrollHeight
            }, 100); // Reduced from 150ms to 100ms
        }
    }
    
    // Force scroll to bottom regardless of user position - instant
    function forceScrollToBottom() {
        const chatMessages = $('#chatMessages');
        if (chatMessages.length > 0) {
            chatMessages.scrollTop(chatMessages[0].scrollHeight);
        }
    }
    
    // Check if user is at bottom of chat
    function isAtBottom() {
        const chatMessages = $('#chatMessages');
        if (chatMessages.length === 0) return true;
        
        const scrollTop = chatMessages.scrollTop();
        const scrollHeight = chatMessages[0].scrollHeight;
        const clientHeight = chatMessages.height();
        const scrollBottom = scrollHeight - scrollTop - clientHeight;
        
        return scrollBottom <= 5; // Very strict detection - only 5px tolerance
    }
    
    // Check scroll position and update button visibility
    function checkScrollPosition() {
        const chatMessages = $('#chatMessages')[0];
        if (!chatMessages) return;
        
        const scrollTop = chatMessages.scrollTop;
        const scrollHeight = chatMessages.scrollHeight;
        const clientHeight = chatMessages.clientHeight;
        const scrollBottom = scrollHeight - scrollTop - clientHeight;
        
        if (scrollBottom <= 5) {
            $('#scrollToBottomBtn').removeClass('show');
            $('#unreadIndicator').hide().text('0');
        }
    }
    
    // Load users list
    function loadUsers() {
        $.get(baseUrl + 'chat/users', function(response) {
            if (response.users) {
                updateUsersList(response.users);
            }
        });
    }
    
    // Update users list
    function updateUsersList(users) {
        const usersList = $('#usersList');
        usersList.empty();
        
        if (users.length === 0) {
            usersList.html(`
                <div class="text-center py-5">
                    <i class="fas fa-users fa-2x text-muted mb-3"></i>
                    <p class="text-muted">No other users found</p>
                </div>
            `);
            return;
        }
        
        let onlineCount = 0;
        users.forEach(user => {
            if (user.is_online) onlineCount++;
            
            const userItem = $(`
                <div class="user-item" data-user-id="${user.id}" data-username="${user.username}">
                    <div class="d-flex align-items-center">
                        <div class="user-avatar position-relative me-3">
                            ${user.username.substring(0, 2).toUpperCase()}
                            <div class="online-indicator ${user.is_online ? '' : 'offline-indicator'}"></div>
                        </div>
                        <div class="user-info flex-grow-1">
                            <h6>${user.username}</h6>
                            <div class="user-status">
                                <i class="fas fa-circle ${user.is_online ? 'text-success' : 'text-secondary'} me-1" style="font-size: 8px;"></i>
                                ${user.is_online ? 'Online' : 'Last seen ' + timeAgo(user.last_seen)}
                            </div>
                        </div>
                        ${user.unread_count > 0 ? `<div class="unread-badge">${user.unread_count}</div>` : ''}
                    </div>
                </div>
            `);
            usersList.append(userItem);
        });
        
        $('#onlineCount').text(onlineCount);
    }
    
    // Select user for chat
    function selectUser(userId, username) {
        currentChatUser = userId;
        
        // Clear displayed message IDs when switching users
        displayedMessageIds.clear();
        
        // Update UI
        $('.user-item').removeClass('active');
        $(`.user-item[data-user-id="${userId}"]`).addClass('active');
        
        // Show chat interface
        $('#welcomeMessage').hide();
        $('#chatInterface').show();
        
        // Reset scroll button and unread indicator
        $('#scrollToBottomBtn').removeClass('show');
        $('#unreadIndicator').hide().text('0');
        
        // Update chat header
        $('#chatUserName').text(username);
        $('#chatUserAvatar').text(username.substring(0, 2).toUpperCase());
        
        // Load conversation
        loadConversation(userId);
        
        // Start checking for new messages
        if (messageCheckInterval) {
            clearInterval(messageCheckInterval);
        }
        messageCheckInterval = setInterval(function() {
            checkNewMessages();
        }, 3000);
        
        // Focus on message input
        $('#messageInput').focus();
    }
    
    // Load conversation
    function loadConversation(userId) {
        $.get(baseUrl + `chat/conversation/${userId}`, function(response) {
            if (response.messages) {
                displayMessages(response.messages);
                // Update user status
                if (response.contact) {
                    updateChatUserStatus(response.contact);
                }
                // Ensure we're scrolled to bottom after loading conversation
                setTimeout(function() {
                    forceScrollToBottom();
                }, 100); // Keep this at 100ms for conversation loading
            }
        });
    }
    
    // Display messages
    function displayMessages(messages) {
        const chatMessages = $('#chatMessages');
        
        // Clear ALL content from chat messages container
        chatMessages.empty();
        
        // Clear the displayed message IDs when loading fresh conversation
        displayedMessageIds.clear();
        
        // Add scroll anchor back
        chatMessages.append('<div id="scrollAnchor"></div>');
        const scrollAnchor = $('#scrollAnchor');
        
        if (messages.length === 0) {
            const emptyMessage = $(`
                <div class="text-center py-4">
                    <i class="fas fa-comment fa-2x text-muted mb-3"></i>
                    <p class="text-muted">No messages yet. Start the conversation!</p>
                </div>
            `);
            scrollAnchor.before(emptyMessage);
            return;
        }
        
        messages.forEach(message => {
            // Track this message ID to prevent future duplicates
            displayedMessageIds.add(message.id);
            const messageHtml = createMessageHtml(message);
            scrollAnchor.before(messageHtml);
        });
        
        // Force scroll to bottom after all messages are loaded
        setTimeout(function() {
            forceScrollToBottom();
            // Check scroll position after scrolling
            setTimeout(checkScrollPosition, 100);
        }, 50); // Reduced from 100ms to 50ms
    }
    
    // Create message HTML
    function createMessageHtml(message) {
        const isSent = message.sender_id == currentUserId;
        const messageClass = isSent ? 'sent' : 'received';
        const time = formatMessageTime(message.created_at);
        
        return $(`
            <div class="message ${messageClass}" data-message-id="${message.id}">
                <div class="message-content">
                    <div>${escapeHtml(message.message)}</div>
                    <div class="message-time">${time}</div>
                </div>
            </div>
        `);
    }
    
    // Send message
    function sendMessage() {
        const messageText = $('#messageInput').val().trim();
        if (!messageText || !currentChatUser) return;
        
        $.post(baseUrl + 'chat/send', {
            receiver_id: currentChatUser,
            message: messageText
        }, function(response) {
            if (response.success) {
                $('#messageInput').val('');
                // Track this message ID to prevent duplicates
                displayedMessageIds.add(response.message.id);
                const messageHtml = createMessageHtml(response.message);
                const scrollAnchor = $('#scrollAnchor');
                scrollAnchor.before(messageHtml);
                // Scroll to bottom after sending message
                setTimeout(function() {
                    forceScrollToBottom();
                    // Check scroll position after scrolling
                    setTimeout(checkScrollPosition, 50);
                }, 25); // Very fast for sent messages
            } else {
                alert('Failed to send message: ' + (response.error || 'Unknown error'));
            }
        });
    }
    
    // Check for new messages
    function checkNewMessages() {
        if (!currentChatUser) return;
        
        const params = lastMessageCheck ? `?last_check=${lastMessageCheck}` : '';
        $.get(baseUrl + 'chat/check-messages' + params, function(response) {
            if (response.messages && response.messages.length > 0) {
                const scrollAnchor = $('#scrollAnchor');
                let hasNewMessages = false;
                let newMessageCount = 0;
                
                response.messages.forEach(message => {
                    // Only add messages that haven't been displayed yet
                    // AND are from the current conversation
                    if (!displayedMessageIds.has(message.id) && 
                        ((message.sender_id == currentChatUser && message.receiver_id == currentUserId) ||
                         (message.sender_id == currentUserId && message.receiver_id == currentChatUser))) {
                        
                        // Track this message ID
                        displayedMessageIds.add(message.id);
                        const messageHtml = createMessageHtml(message);
                        scrollAnchor.before(messageHtml);
                        hasNewMessages = true;
                        
                        // Only count messages from other user for unread indicator
                        if (message.sender_id == currentChatUser) {
                            newMessageCount++;
                        }
                    }
                });
                
                if (hasNewMessages) {
                    // Only auto-scroll if user is at bottom
                    if (isAtBottom()) {
                        setTimeout(function() {
                            forceScrollToBottom();
                            // Check scroll position after auto-scroll
                            setTimeout(checkScrollPosition, 50);
                        }, 25); // Fast auto-scroll for new messages
                    } else if (newMessageCount > 0) {
                        // Show unread indicator only for messages from other user
                        const currentUnread = parseInt($('#unreadIndicator').text()) || 0;
                        const totalUnread = currentUnread + newMessageCount;
                        $('#unreadIndicator').text(totalUnread).show();
                        $('#scrollToBottomBtn').addClass('show');
                    }
                }
            }
            lastMessageCheck = new Date().toISOString();
        });
    }
    
    // Update online status
    function updateOnlineStatus() {
        $.post(baseUrl + 'chat/online-status');
        loadUsers(); // Refresh users list to update online status
    }
    
    // Update chat user status
    function updateChatUserStatus(user) {
        const statusText = user.is_online ? 'Online' : `Last seen ${timeAgo(user.last_seen)}`;
        $('#chatUserStatus').text(statusText);
    }
    
    // Utility functions
    function timeAgo(dateString) {
        if (!dateString) return 'Never';
        const date = new Date(dateString);
        const now = new Date();
        const diff = now - date;
        const minutes = Math.floor(diff / 60000);
        const hours = Math.floor(diff / 3600000);
        const days = Math.floor(diff / 86400000);
        
        if (minutes < 1) return 'Just now';
        if (minutes < 60) return `${minutes}m ago`;
        if (hours < 24) return `${hours}h ago`;
        return `${days}d ago`;
    }
    
    function formatMessageTime(dateString) {
        const date = new Date(dateString);
        return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    }
    
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
});

// Page unload cleanup and logout
$(window).on('beforeunload', function() {
    // Clear intervals
    if (messageCheckInterval) {
        clearInterval(messageCheckInterval);
    }
    
    // Set user offline and logout
    $.ajax({
        url: baseUrl + 'auth/logout',
        type: 'POST',
        async: false, // Synchronous to ensure it completes before page unloads
        data: {
            auto_logout: true
        }
    });
});

// Also handle page visibility changes (when tab becomes hidden)
$(document).on('visibilitychange', function() {
    if (document.hidden) {
        // User switched tabs or minimized browser
        // Update online status to offline after a delay
        setTimeout(function() {
            if (document.hidden) {
                $.post(baseUrl + 'chat/set-offline');
            }
        }, 5000); // 5 second delay
    } else {
        // User came back to the tab
        $.post(baseUrl + 'chat/online-status');
    }
});

// Handle tab close/browser close more reliably
window.addEventListener('beforeunload', function(e) {
    // Set user offline
    navigator.sendBeacon(baseUrl + 'chat/set-offline');
});

// Handle page navigation away
window.addEventListener('pagehide', function(e) {
    // Set user offline
    navigator.sendBeacon(baseUrl + 'chat/set-offline');
});
</script>
<?= $this->endSection() ?>