# Chat JavaScript & Frontend Documentation

## 📋 Overview

This document provides complete JavaScript implementation for the enhanced chat system, including:
- Real-time message delivery with Laravel Echo
- Typing indicators
- Online status tracking
- File attachments
- Read receipts
- Browser notifications
- Message search

---

## 🚀 Setup & Installation

### 1. Install Dependencies

```bash
# Navigate to Laravel project root
cd /var/www/digiwaxx-app

# Install Laravel Echo and Pusher
npm install --save laravel-echo pusher-js

# OR if using Socket.io
npm install --save laravel-echo socket.io-client

# Install other dependencies
npm install
```

### 2. Initialize Laravel Echo

Create file: `public/assets/js/chat-echo.js`

```javascript
/**
 * Laravel Echo Configuration
 * Real-time broadcasting client setup
 */

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// Make Pusher available globally
window.Pusher = Pusher;

// Initialize Echo
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY || 'your-pusher-key',
    cluster: process.env.MIX_PUSHER_APP_CLUSTER || 'your-cluster',
    forceTLS: true,
    encrypted: true,

    // Authentication endpoint
    authEndpoint: '/broadcasting/auth',

    // Auth headers (include CSRF token)
    auth: {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    }
});

// Connection status logging
window.Echo.connector.pusher.connection.bind('connected', function() {
    console.log('✅ Echo connected');
});

window.Echo.connector.pusher.connection.bind('disconnected', function() {
    console.log('❌ Echo disconnected');
});

window.Echo.connector.pusher.connection.bind('error', function(err) {
    console.error('⚠️ Echo error:', err);
});
```

---

## 💬 Chat Core JavaScript

Create file: `public/assets/js/chat-enhanced.js`

```javascript
/**
 * Enhanced Chat System
 * Main JavaScript implementation for real-time chat features
 */

class EnhancedChat {
    constructor(options) {
        this.userId = options.userId;
        this.userType = options.userType;  // 1 = Client, 2 = Member
        this.currentConversationPartnerId = null;
        this.currentConversationPartnerType = null;
        this.typingTimer = null;
        this.typingTimeout = 3000;  // Stop typing after 3 seconds
        this.heartbeatInterval = null;

        this.init();
    }

    /**
     * Initialize chat system
     */
    init() {
        console.log('🚀 Initializing Enhanced Chat...');

        // Setup event listeners
        this.setupMessageListeners();
        this.setupTypingIndicator();
        this.setupFileAttachment();
        this.setupMarkAsRead();
        this.setupSearch();

        // Start real-time features
        this.subscribeToChannels();
        this.startHeartbeat();
        this.setOnlineStatus(true);
        this.updateUnreadBadge();

        // Setup page unload handler
        window.addEventListener('beforeunload', () => {
            this.setOnlineStatus(false);
        });

        console.log('✅ Enhanced Chat initialized');
    }

    /**
     * Subscribe to real-time channels
     */
    subscribeToChannels() {
        const channelName = `private-chat.user.${this.userType}.${this.userId}`;
        console.log(`📡 Subscribing to channel: ${channelName}`);

        // Subscribe to private channel for direct messages
        window.Echo.private(channelName)
            .listen('.new.message', (data) => {
                console.log('📨 New message received:', data);
                this.handleNewMessage(data);
            })
            .listen('.message.read', (data) => {
                console.log('✓✓ Message read:', data);
                this.handleMessageRead(data);
            })
            .listen('.user.typing', (data) => {
                console.log('✍️ User typing:', data);
                this.handleUserTyping(data);
            });

        // Subscribe to presence channel for online status
        window.Echo.join('chat.presence')
            .here((users) => {
                console.log('👥 Users online:', users);
                this.updateOnlineUsers(users);
            })
            .joining((user) => {
                console.log('✅ User joined:', user);
                this.handleUserOnline(user);
            })
            .leaving((user) => {
                console.log('❌ User left:', user);
                this.handleUserOffline(user);
            })
            .listen('.user.status', (data) => {
                console.log('📊 User status update:', data);
                this.handleUserStatusChange(data);
            });
    }

    /**
     * Handle incoming new message
     */
    handleNewMessage(data) {
        // Show browser notification
        this.showNotification(
            `New message from ${data.senderName}`,
            data.message,
            data.hasAttachment ? '📎' : '💬'
        );

        // Play notification sound
        this.playNotificationSound();

        // Update unread badge
        this.updateUnreadBadge();

        // If we're viewing this conversation, append message
        if (this.currentConversationPartnerId === data.senderId &&
            this.currentConversationPartnerType === data.senderType) {
            this.appendMessageToConversation(data);
            this.markConversationAsRead();
        } else {
            // Update message list
            this.refreshMessageList();
        }
    }

    /**
     * Handle message read receipt
     */
    handleMessageRead(data) {
        // Update message UI to show read receipt (double checkmark)
        $(`.message[data-message-id="${data.messageId}"]`)
            .find('.read-receipt')
            .removeClass('delivered')
            .addClass('read')
            .html('✓✓');
    }

    /**
     * Handle user typing indicator
     */
    handleUserTyping(data) {
        if (!data.isTyping) {
            this.hideTypingIndicator(data.userId, data.userType);
            return;
        }

        // Only show if this is the current conversation
        if (this.currentConversationPartnerId === data.userId &&
            this.currentConversationPartnerType === data.userType) {
            this.showTypingIndicator(data.userName);

            // Auto-hide after 5 seconds
            setTimeout(() => {
                this.hideTypingIndicator(data.userId, data.userType);
            }, 5000);
        }
    }

    /**
     * Handle user online status
     */
    handleUserOnline(user) {
        $(`.user-status[data-user-id="${user.id}"][data-user-type="${user.type}"]`)
            .addClass('online')
            .removeClass('offline')
            .attr('title', 'Online');
    }

    /**
     * Handle user offline status
     */
    handleUserOffline(user) {
        $(`.user-status[data-user-id="${user.id}"][data-user-type="${user.type}"]`)
            .addClass('offline')
            .removeClass('online')
            .attr('title', 'Offline');
    }

    /**
     * Handle user status change
     */
    handleUserStatusChange(data) {
        if (data.isOnline) {
            this.handleUserOnline(data);
        } else {
            this.handleUserOffline(data);
        }
    }

    /**
     * Send message
     */
    sendMessage(partnerId, partnerType, message, attachment = null) {
        const url = this.userType === 2 ? '/member/chat/send-with-attachment' : '/client/chat/send-with-attachment';
        const partnerKey = this.userType === 2 ? 'clientId' : 'memberId';

        let formData = new FormData();
        formData.append(partnerKey, partnerId);
        formData.append('message', message);

        if (attachment) {
            formData.append('attachment', attachment);
        }

        return $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    }

    /**
     * Mark message as read
     */
    markMessageAsRead(messageId) {
        const url = this.userType === 2 ?
            `/member/chat/mark-read/${messageId}` :
            `/client/chat/mark-read/${messageId}`;

        return $.post(url, {
            _token: $('meta[name="csrf-token"]').attr('content')
        });
    }

    /**
     * Mark entire conversation as read
     */
    markConversationAsRead() {
        if (!this.currentConversationPartnerId) return;

        const url = this.userType === 2 ?
            `/member/chat/mark-conversation-read/${this.currentConversationPartnerId}` :
            `/client/chat/mark-conversation-read/${this.currentConversationPartnerId}`;

        $.post(url, {
            _token: $('meta[name="csrf-token"]').attr('content')
        }).done((response) => {
            console.log(`✓ ${response.count} messages marked as read`);
            this.updateUnreadBadge();
        });
    }

    /**
     * Update typing indicator
     */
    sendTypingIndicator(isTyping) {
        if (!this.currentConversationPartnerId) return;

        const url = this.userType === 2 ?
            `/member/chat/typing/${this.currentConversationPartnerId}` :
            `/client/chat/typing/${this.currentConversationPartnerId}`;

        $.post(url, {
            _token: $('meta[name="csrf-token"]').attr('content'),
            isTyping: isTyping
        });
    }

    /**
     * Set online/offline status
     */
    setOnlineStatus(isOnline) {
        const url = this.userType === 2 ?
            (isOnline ? '/member/chat/status/online' : '/member/chat/status/offline') :
            (isOnline ? '/client/chat/status/online' : '/client/chat/status/offline');

        $.post(url, {
            _token: $('meta[name="csrf-token"]').attr('content')
        }).done(() => {
            console.log(`Status set to: ${isOnline ? 'online' : 'offline'}`);
        });
    }

    /**
     * Send heartbeat to keep online status active
     */
    startHeartbeat() {
        this.heartbeatInterval = setInterval(() => {
            const url = this.userType === 2 ? '/member/chat/heartbeat' : '/client/chat/heartbeat';

            $.post(url, {
                _token: $('meta[name="csrf-token"]').attr('content')
            }).done((response) => {
                // Update unread count if changed
                if (response.unreadCount !== undefined) {
                    this.updateUnreadBadge(response.unreadCount);
                }
            }).fail(() => {
                console.error('Heartbeat failed');
            });
        }, 30000);  // Every 30 seconds
    }

    /**
     * Update unread message badge
     */
    updateUnreadBadge(count = null) {
        if (count !== null) {
            this.displayUnreadBadge(count);
            return;
        }

        const url = this.userType === 2 ? '/member/chat/unread-count' : '/client/chat/unread-count';

        $.get(url).done((response) => {
            this.displayUnreadBadge(response.count);
        });
    }

    /**
     * Display unread badge
     */
    displayUnreadBadge(count) {
        const $badge = $('.chat-unread-badge');

        if (count > 0) {
            $badge.text(count > 99 ? '99+' : count).show();
        } else {
            $badge.hide();
        }
    }

    /**
     * Search messages
     */
    searchMessages(keyword) {
        const url = this.userType === 2 ? '/member/chat/search' : '/client/chat/search';

        return $.get(url, { q: keyword });
    }

    /**
     * Setup message input listeners
     */
    setupMessageListeners() {
        const self = this;

        // Message form submit
        $(document).on('submit', '#chat-message-form', function(e) {
            e.preventDefault();

            const $form = $(this);
            const $input = $form.find('[name="message"]');
            const message = $input.val().trim();
            const attachment = $form.find('[name="attachment"]')[0]?.files[0];

            if (!message && !attachment) {
                alert('Please enter a message or attach a file');
                return;
            }

            // Disable form
            $form.find('button[type="submit"]').prop('disabled', true);

            self.sendMessage(
                self.currentConversationPartnerId,
                self.currentConversationPartnerType,
                message,
                attachment
            ).done((response) => {
                console.log('Message sent:', response);

                // Clear input
                $input.val('');
                $form.find('[name="attachment"]').val('');
                $('.attachment-preview').remove();

                // Stop typing indicator
                self.sendTypingIndicator(false);

                // Append message to conversation
                self.appendMessageToConversation({
                    message: message,
                    senderId: self.userId,
                    senderType: self.userType,
                    hasAttachment: !!attachment,
                    attachmentType: attachment?.type.split('/')[0]
                });

                // Scroll to bottom
                self.scrollToBottom();

            }).fail((xhr) => {
                console.error('Failed to send message:', xhr);
                alert('Failed to send message. Please try again.');

            }).always(() => {
                $form.find('button[type="submit"]').prop('disabled', false);
                $input.focus();
            });
        });

        // Mark conversation as read when opened
        $(document).on('click', '.conversation-item', function() {
            const partnerId = $(this).data('partner-id');
            const partnerType = $(this).data('partner-type');

            self.currentConversationPartnerId = partnerId;
            self.currentConversationPartnerType = partnerType;

            // Mark as read after a short delay
            setTimeout(() => {
                self.markConversationAsRead();
            }, 500);
        });
    }

    /**
     * Setup typing indicator
     */
    setupTypingIndicator() {
        const self = this;

        $(document).on('input', '#chat-message-form [name="message"]', function() {
            clearTimeout(self.typingTimer);

            // Send typing start
            self.sendTypingIndicator(true);

            // Set timeout to send typing stop
            self.typingTimer = setTimeout(() => {
                self.sendTypingIndicator(false);
            }, self.typingTimeout);
        });
    }

    /**
     * Show typing indicator in UI
     */
    showTypingIndicator(userName) {
        const $indicator = $('#typing-indicator');

        if ($indicator.length) {
            $indicator.html(`<em>${userName} is typing...</em>`).show();
        } else {
            $('.chat-messages').append(
                `<div id="typing-indicator" class="typing-indicator">
                    <em>${userName} is typing...</em>
                </div>`
            );
        }

        this.scrollToBottom();
    }

    /**
     * Hide typing indicator
     */
    hideTypingIndicator(userId, userType) {
        if (this.currentConversationPartnerId === userId &&
            this.currentConversationPartnerType === userType) {
            $('#typing-indicator').remove();
        }
    }

    /**
     * Setup file attachment preview
     */
    setupFileAttachment() {
        $(document).on('change', '#chat-message-form [name="attachment"]', function() {
            const file = this.files[0];

            if (!file) {
                $('.attachment-preview').remove();
                return;
            }

            // Validate file size (10MB max)
            if (file.size > 10 * 1024 * 1024) {
                alert('File size exceeds 10MB limit');
                $(this).val('');
                return;
            }

            // Show preview
            const $preview = $(`
                <div class="attachment-preview">
                    <span class="file-name">${file.name}</span>
                    <span class="file-size">${formatFileSize(file.size)}</span>
                    <button type="button" class="remove-attachment" title="Remove">×</button>
                </div>
            `);

            $('.attachment-preview').remove();
            $('#chat-message-form').prepend($preview);
        });

        // Remove attachment
        $(document).on('click', '.remove-attachment', function() {
            $('#chat-message-form [name="attachment"]').val('');
            $('.attachment-preview').remove();
        });
    }

    /**
     * Setup mark as read on scroll
     */
    setupMarkAsRead() {
        const self = this;

        $(document).on('scroll', '.chat-messages', function() {
            // Mark messages as read when they enter viewport
            $('.chat-message.unread').each(function() {
                if (self.isElementInViewport($(this))) {
                    const messageId = $(this).data('message-id');
                    self.markMessageAsRead(messageId);
                    $(this).removeClass('unread');
                }
            });
        });
    }

    /**
     * Setup message search
     */
    setupSearch() {
        const self = this;

        $(document).on('input', '#chat-search-input', debounce(function() {
            const keyword = $(this).val().trim();

            if (keyword.length < 2) {
                $('#chat-search-results').empty();
                return;
            }

            self.searchMessages(keyword).done((response) => {
                self.displaySearchResults(response.results);
            });
        }, 300));
    }

    /**
     * Display search results
     */
    displaySearchResults(results) {
        const $container = $('#chat-search-results');
        $container.empty();

        if (results.length === 0) {
            $container.html('<p class="no-results">No messages found</p>');
            return;
        }

        results.forEach(result => {
            const $item = $(`
                <div class="search-result-item" data-message-id="${result.messageId}">
                    <div class="message-preview">${escapeHtml(result.message)}</div>
                    <div class="message-date">${formatDate(result.dateTime)}</div>
                </div>
            `);

            $container.append($item);
        });
    }

    /**
     * Browser notification
     */
    showNotification(title, body, icon = '💬') {
        if (!('Notification' in window)) return;

        if (Notification.permission === 'granted') {
            new Notification(title, {
                body: body,
                icon: icon,
                badge: '/images/logo.png',
                tag: 'chat-notification'
            });
        } else if (Notification.permission !== 'denied') {
            Notification.requestPermission().then(permission => {
                if (permission === 'granted') {
                    new Notification(title, { body: body, icon: icon });
                }
            });
        }
    }

    /**
     * Play notification sound
     */
    playNotificationSound() {
        const audio = new Audio('/sounds/notification.mp3');
        audio.volume = 0.5;
        audio.play().catch(err => console.log('Audio play failed:', err));
    }

    /**
     * Utility: Check if element is in viewport
     */
    isElementInViewport(el) {
        const rect = el[0].getBoundingClientRect();
        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
            rect.right <= (window.innerWidth || document.documentElement.clientWidth)
        );
    }

    /**
     * Scroll chat to bottom
     */
    scrollToBottom() {
        const $messages = $('.chat-messages');
        $messages.scrollTop($messages[0].scrollHeight);
    }

    /**
     * Append message to conversation view
     */
    appendMessageToConversation(data) {
        const isSent = data.senderId === this.userId && data.senderType === this.userType;
        const messageClass = isSent ? 'sent' : 'received';

        const $message = $(`
            <div class="chat-message ${messageClass}" data-message-id="${data.messageId}">
                <div class="message-content">
                    ${escapeHtml(data.message)}
                    ${data.hasAttachment ? '<span class="attachment-icon">📎</span>' : ''}
                </div>
                <div class="message-meta">
                    <span class="message-time">${formatTime(new Date())}</span>
                    ${isSent ? '<span class="read-receipt">✓</span>' : ''}
                </div>
            </div>
        `);

        $('.chat-messages').append($message);
        this.scrollToBottom();
    }

    /**
     * Refresh message list
     */
    refreshMessageList() {
        // Reload the message list
        location.reload();  // Simple approach, can be optimized with AJAX
    }

    /**
     * Update online users list
     */
    updateOnlineUsers(users) {
        users.forEach(user => {
            this.handleUserOnline(user);
        });
    }
}

// Utility functions
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, m => map[m]);
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString() + ' ' + date.toLocaleTimeString();
}

function formatTime(date) {
    return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
}

// Export for use
window.EnhancedChat = EnhancedChat;
```

---

## 🎬 Initialization in Views

Add to your chat views (Member/Client dashboard):

```html
<!-- In head section -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Load scripts before closing body tag -->
<script src="/js/app.js"></script>
<script src="/assets/js/chat-echo.js"></script>
<script src="/assets/js/chat-enhanced.js"></script>

<script>
$(document).ready(function() {
    // Initialize enhanced chat
    @if(Session::has('memberId'))
        const chat = new EnhancedChat({
            userId: {{ Session::get('memberId') }},
            userType: 2  // Member
        });
        window.chatInstance = chat;
    @elseif(Session::has('clientId'))
        const chat = new EnhancedChat({
            userId: {{ Session::get('clientId') }},
            userType: 1  // Client
        });
        window.chatInstance = chat;
    @endif
});
</script>
```

---

## 📚 Related Documentation

- **CHAT_VIEWS_DOCUMENTATION.md** - HTML templates for chat UI
- **CHAT_ROUTES_DOCUMENTATION.md** - API routes
- **CHAT_CONTROLLER_ENHANCEMENTS.md** - Backend methods
- **CHAT_ENHANCEMENT_IMPLEMENTATION_GUIDE.md** - Main guide

---

**Next Step:** Update your views with the chat UI components (see CHAT_VIEWS_DOCUMENTATION.md)
